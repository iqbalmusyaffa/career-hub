<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use ReflectionMethod;

class SystemFlowController extends Controller
{
    public function index()
    {
        $modelsData = $this->inspectModels();
        $mermaidErd = $this->generateMermaidErd($modelsData);
        $workflowSteps = $this->getRecruitmentWorkflowSteps();
        $routesSummary = $this->getRoutesSummary();
        $recentFlowLogs = \App\Models\AuditLog::with('user')->latest()->take(30)->get();

        return view('admin.system_flow.index', compact(
            'modelsData',
            'mermaidErd',
            'workflowSteps',
            'routesSummary',
            'recentFlowLogs'
        ));
    }

    /**
     * Dynamically scan app/Models/*.php and inspect Eloquent models & relationships.
     */
    private function inspectModels(): array
    {
        $modelsPath = app_path('Models');
        if (!file_exists($modelsPath)) {
            return [];
        }

        $files = scandir($modelsPath);
        $models = [];
        $relationships = [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || !str_ends_with($file, '.php')) {
                continue;
            }

            $className = 'App\\Models\\' . pathinfo($file, PATHINFO_FILENAME);

            if (!class_exists($className)) {
                continue;
            }

            $reflection = new ReflectionClass($className);
            if ($reflection->isAbstract() || !$reflection->isSubclassOf(\Illuminate\Database\Eloquent\Model::class)) {
                continue;
            }

            try {
                /** @var \Illuminate\Database\Eloquent\Model $instance */
                $instance = $reflection->newInstance();
                $tableName = $instance->getTable();
                $primaryKey = $instance->getKeyName();
                $fillable = $instance->getFillable();

                // Get table columns & data types
                $columns = [];
                if (Schema::hasTable($tableName)) {
                    try {
                        $rawColumns = Schema::getColumns($tableName);
                        foreach ($rawColumns as $col) {
                            $colName = is_array($col) ? $col['name'] : $col->name;
                            $colType = is_array($col) ? ($col['type_name'] ?? $col['type'] ?? 'string') : ($col->type_name ?? 'string');
                            $isNullable = is_array($col) ? ($col['nullable'] ?? false) : ($col->nullable ?? false);
                            
                            $columns[] = [
                                'name' => $colName,
                                'type' => (string) $colType,
                                'is_pk' => $colName === $primaryKey,
                                'is_fk' => str_ends_with($colName, '_id') || str_ends_with($colName, '_by'),
                                'nullable' => $isNullable,
                            ];
                        }
                    } catch (\Throwable $e) {
                        // Fallback column listing if getColumns is unsupported
                        $colList = Schema::getColumnListing($tableName);
                        foreach ($colList as $colName) {
                            $columns[] = [
                                'name' => $colName,
                                'type' => 'mixed',
                                'is_pk' => $colName === $primaryKey,
                                'is_fk' => str_ends_with($colName, '_id') || str_ends_with($colName, '_by'),
                                'nullable' => true,
                            ];
                        }
                    }
                }

                // Inspect model methods for Eloquent Relationships
                $modelRelations = [];
                $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

                foreach ($methods as $method) {
                    if ($method->class !== $className || $method->getNumberOfParameters() > 0) {
                        continue;
                    }

                    // Skip standard Eloquent model methods
                    if (in_array($method->name, ['getAttributes', 'getRelationships', 'getRelations', 'toArray', 'jsonSerialize', 'replicate'])) {
                        continue;
                    }

                    try {
                        $return = $method->invoke($instance);

                        if ($return instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                            $relatedModel = get_class($return->getRelated());
                            $relatedShortName = class_basename($relatedModel);
                            $relationType = class_basename(get_class($return));

                            $foreignKey = null;
                            $ownerKey = null;

                            if (method_exists($return, 'getForeignKeyName')) {
                                $foreignKey = $return->getForeignKeyName();
                            } elseif (method_exists($return, 'getQualifiedForeignKeyName')) {
                                $foreignKey = class_basename($return->getQualifiedForeignKeyName());
                            }

                            if (method_exists($return, 'getOwnerKeyName')) {
                                $ownerKey = $return->getOwnerKeyName();
                            }

                            $modelRelations[] = [
                                'method' => $method->name,
                                'type' => $relationType,
                                'related_model' => $relatedShortName,
                                'foreign_key' => $foreignKey,
                                'owner_key' => $ownerKey,
                            ];

                            $relationships[] = [
                                'from' => class_basename($className),
                                'from_table' => $tableName,
                                'to' => $relatedShortName,
                                'to_table' => (new $relatedModel())->getTable(),
                                'type' => $relationType,
                                'method' => $method->name,
                            ];
                        }
                    } catch (\Throwable $ex) {
                        // Ignore methods that require arguments or throw exceptions during invocation
                    }
                }

                $models[class_basename($className)] = [
                    'class' => $className,
                    'short_name' => class_basename($className),
                    'table' => $tableName,
                    'primary_key' => $primaryKey,
                    'fillable' => $fillable,
                    'columns' => $columns,
                    'relations' => $modelRelations,
                    'category' => $this->categorizeModel(class_basename($className)),
                ];

            } catch (\Throwable $e) {
                // Ignore instantiation issues
            }
        }

        return [
            'models' => $models,
            'relationships' => $relationships,
            'total_models' => count($models),
            'total_relations' => count($relationships),
        ];
    }

    /**
     * Categorize models into functional modules.
     */
    private function categorizeModel(string $modelName): string
    {
        if (in_array($modelName, ['User', 'CandidateProfile', 'CompanyProfile', 'CompanyBranch', 'CompanyTeamMember', 'CompanyRoleRequest'])) {
            return 'Pengguna & Profil';
        }
        if (in_array($modelName, ['Job', 'SavedJob', 'JobTest', 'TestQuestion', 'CandidateTestResult', 'HeadcountBudget'])) {
            return 'Lowongan & Ujian';
        }
        if (in_array($modelName, ['Application', 'ApplicationMessage', 'ApplicationAgreement', 'CandidateEvaluation', 'HrInternalNote', 'OfferLetter', 'Interview', 'InterviewScorecard'])) {
            return 'Proses Rekrutmen';
        }
        if (in_array($modelName, ['CandidateOnboarding', 'InternshipPeriod', 'InternshipLogbook', 'InternshipEvaluation', 'InternshipTranscript', 'InternshipCertificate', 'EmployeeTermination', 'CompanyHoliday', 'CompanyHolidayOverride'])) {
            return 'Onboarding & Magang';
        }
        return 'Sistem & Administrasi';
    }

    /**
     * Generate dynamic Mermaid.js erDiagram syntax from inspected models.
     */
    private function generateMermaidErd(array $modelsData): string
    {
        $lines = ["erDiagram"];

        // Render Entity Attributes
        foreach ($modelsData['models'] as $shortName => $info) {
            $tableName = strtoupper($info['table']);
            $lines[] = "    {$tableName} {";

            foreach (array_slice($info['columns'], 0, 8) as $col) {
                $type = preg_replace('/[^a-zA-Z0-9]/', '', $col['type']);
                $type = $type ?: 'string';
                $name = $col['name'];
                $keyMarker = $col['is_pk'] ? 'PK' : ($col['is_fk'] ? 'FK' : '');
                $lines[] = "        {$type} {$name} {$keyMarker}";
            }

            $lines[] = "    }";
        }

        // Render Relationships
        $renderedRelations = [];
        foreach ($modelsData['relationships'] as $rel) {
            $fromTable = strtoupper($rel['from_table']);
            $toTable = strtoupper($rel['to_table']);
            $type = $rel['type'];

            $relationKey = "{$fromTable}_{$toTable}_{$type}";
            if (isset($renderedRelations[$relationKey])) {
                continue;
            }
            $renderedRelations[$relationKey] = true;
            $symbol = '||--o{';
            if ($type === 'BelongsTo') {
                $symbol = '}o--||';
            } elseif ($type === 'HasOne') {
                $symbol = '||--||';
            } elseif ($type === 'BelongsToMany') {
                $symbol = '}o--o{';
            }

            $lines[] = "    {$fromTable} {$symbol} {$toTable} : \"{$rel['method']}\"";
        }

        return implode("\n", $lines);
    }

    /**
     * Structured Candidate & Feature Workflow Steps based on system functionality.
     */
    private function getRecruitmentWorkflowSteps(): array
    {
        return [
            [
                'step' => 1,
                'title' => 'Publikasi Lowongan & Headcount Budget',
                'description' => 'HR / Company Owner membuat draf lowongan kerja, mengatur kriteria, serta mengaitkan anggaran headcount.',
                'icon' => 'fa-briefcase',
                'color' => 'blue',
                'status' => 'active / draft',
                'models' => ['Job', 'CompanyProfile', 'HeadcountBudget', 'CompanyBranch'],
                'events' => ['Job Created', 'Budget Allocation Verified'],
                'routes' => ['admin.jobs.create', 'admin.jobs.store', 'admin.headcount-budgets.store'],
            ],
            [
                'step' => 2,
                'title' => 'Pengajuan Lamaran oleh Kandidat',
                'description' => 'Pelamar mendaftar, mengunggah berkas CV (ATS/Kreatif), portofolio, dan menyetujui surat pernyataan.',
                'icon' => 'fa-file-signature',
                'color' => 'indigo',
                'status' => 'pending / screening',
                'models' => ['Application', 'User', 'CandidateProfile', 'CandidateDocument'],
                'events' => ['Application Submitted', 'Candidate Document Uploaded'],
                'routes' => ['jobs.apply', 'profile.candidate.documents.store'],
            ],
            [
                'step' => 3,
                'title' => 'Penilaian Online & Ujian Teknis',
                'description' => 'Kandidat mengerjakan online assessment (tes coding/psikotes) yang dibuat oleh HR secara otomatis.',
                'icon' => 'fa-laptop-code',
                'color' => 'amber',
                'status' => 'test',
                'models' => ['JobTest', 'TestQuestion', 'CandidateTestResult'],
                'events' => ['Test Invited', 'Test Completed', 'Score Auto-Calculated'],
                'routes' => ['candidate.tests.show', 'candidate.tests.submit', 'admin.jobs.test.edit'],
            ],
            [
                'step' => 4,
                'title' => 'Penjadwalan Wawancara HR & User',
                'description' => 'HR mengatur sesi interview (online/offline), mengirimkan Google Meet link/lokasi, dan mengundang tim interviewer.',
                'icon' => 'fa-calendar-check',
                'color' => 'purple',
                'status' => 'interview_hr / interview_user',
                'models' => ['Interview', 'InterviewScorecard', 'HrInternalNote', 'EmailTemplate'],
                'events' => ['Interview Scheduled', 'Email Invitation Sent'],
                'routes' => ['admin.applications.schedule-interview', 'admin.calendar.index'],
            ],
            [
                'step' => 5,
                'title' => 'Evaluasi & Scoring Scorecard',
                'description' => 'Tim HR & Dept Manager memberikan nilai kompetensi pada scorecard & catatan rahasia internal.',
                'icon' => 'fa-clipboard-check',
                'color' => 'cyan',
                'status' => 'background_check / shortlisted',
                'models' => ['CandidateEvaluation', 'InterviewScorecard', 'AuditLog'],
                'events' => ['Scorecard Submitted', 'Internal Review Logged'],
                'routes' => ['admin.applications.evaluations.store', 'admin.applications.scorecards.store'],
            ],
            [
                'step' => 6,
                'title' => 'Penerbitan Surat Penawaran (Offer Letter)',
                'description' => 'HR membuat PDF Surat Penawaran Kerja resmi dengan rincian gaji & benefit untuk disetujui pelamar.',
                'icon' => 'fa-file-pdf',
                'color' => 'emerald',
                'status' => 'offered / accepted',
                'models' => ['OfferLetter', 'ApplicationAgreement', 'EmailTemplate'],
                'events' => ['Offer Letter Generated PDF', 'Candidate Accepted Offer'],
                'routes' => ['admin.applications.offer-letter.create', 'admin.applications.agreements.create'],
            ],
            [
                'step' => 7,
                'title' => 'Onboarding Magang & Perjanjian Kerja',
                'description' => 'Kandidat resmi bergabung, mengisi data administrasi/BPJS, mengunggah pakta integritas, dan aktivasi periode magang.',
                'icon' => 'fa-user-check',
                'color' => 'teal',
                'status' => 'hired / onboarding',
                'models' => ['CandidateOnboarding', 'InternshipPeriod', 'CompanyHoliday'],
                'events' => ['Onboarding Verified', 'Period Activated'],
                'routes' => ['candidate.onboarding.create', 'admin.applications.verify-onboarding'],
            ],
            [
                'step' => 8,
                'title' => 'Presensi Real-time GPS & Anti-Fake GPS System',
                'description' => 'Peserta mengisi logbook harian dengan penguncian titik koordinat satelit GPS asli, anti-fake GPS alert, batas waktu 23:59 WIB, dan kebijakan anti-rapel.',
                'icon' => 'fa-location-dot',
                'color' => 'rose',
                'status' => 'present / sick / permission / absent',
                'models' => ['InternshipLogbook', 'CompanyHolidayOverride'],
                'events' => ['PRESENSI_MAGANG_SUBMIT', 'GPS Satellite Locked', 'Anti-Fake GPS Filtered'],
                'routes' => ['candidate.logbook.index', 'candidate.logbook.show', 'candidate.logbook.store'],
            ],
            [
                'step' => 9,
                'title' => 'Bimbingan & Review Keputusan Mentor (ACC)',
                'description' => 'Mentor meninjau uraian harian, memeriksa bukti surat sakit/izin, memberikan catatan bimbingan, atau melakukan ACC/Revisi.',
                'icon' => 'fa-user-tie',
                'color' => 'indigo',
                'status' => 'approved / rejected / action_required',
                'models' => ['InternshipLogbook', 'User', 'AuditLog'],
                'events' => ['MENTOR_ACC_PRESENSI', 'MENTOR_REJECT_PRESENSI'],
                'routes' => ['mentor.dashboard', 'mentor.logbooks.show', 'mentor.logbooks.approve', 'mentor.logbooks.reject'],
            ],
            [
                'step' => 10,
                'title' => 'Evaluasi Akhir, Transkrip & Sertifikat Magang',
                'description' => 'Mentor memberikan penilaian akhir pada 5 pilar kompetensi, penerbitan transkrip nilai akademik, serta sertifikat magang resmi ber-QR Code.',
                'icon' => 'fa-award',
                'color' => 'amber',
                'status' => 'completed / graduated',
                'models' => ['InternshipEvaluation', 'InternshipTranscript', 'InternshipCertificate'],
                'events' => ['Final Evaluation Generated', 'Certificate Issued'],
                'routes' => ['candidate.logbook.evaluation', 'admin.applications.certificates.create'],
            ],
        ];
    }

    /**
     * Get summary of registered routes in system.
     */
    private function getRoutesSummary(): array
    {
        $allRoutes = Route::getRoutes();
        $adminCount = 0;
        $candidateCount = 0;
        $apiCount = 0;
        $guestCount = 0;

        foreach ($allRoutes as $route) {
            $name = $route->getName() ?? '';
            $uri = $route->uri();

            if (str_starts_with($name, 'admin.') || str_contains($uri, 'admin')) {
                $adminCount++;
            } elseif (str_starts_with($name, 'api.') || str_contains($uri, 'api')) {
                $apiCount++;
            } elseif (in_array('guest', $route->middleware())) {
                $guestCount++;
            } else {
                $candidateCount++;
            }
        }

        return [
            'total_routes' => count($allRoutes),
            'admin_routes' => $adminCount,
            'candidate_routes' => $candidateCount,
            'api_routes' => $apiCount,
        ];
    }
}
