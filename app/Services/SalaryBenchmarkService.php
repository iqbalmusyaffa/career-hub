<?php

namespace App\Services;

use App\Models\Job;
use App\Models\UmkReference;

class SalaryBenchmarkService
{
    /**
     * Complete job roles catalog categorized with market weight factors.
     */
    protected static array $rolesCatalog = [
        'Tech & Software Engineering' => [
            'Software Engineer' => ['weight' => 1.45, 'demand' => 'Sangat Tinggi', 'typical_skills' => ['Git', 'Algorithms', 'OOP', 'REST API', 'Databases']],
            'Backend Developer' => ['weight' => 1.50, 'demand' => 'Sangat Tinggi', 'typical_skills' => ['Laravel', 'Node.js', 'Golang', 'PostgreSQL', 'Redis', 'Docker']],
            'Frontend Developer' => ['weight' => 1.35, 'demand' => 'Tinggi', 'typical_skills' => ['React', 'Vue.js', 'Next.js', 'TypeScript', 'Tailwind CSS']],
            'Fullstack Developer' => ['weight' => 1.60, 'demand' => 'Sangat Tinggi', 'typical_skills' => ['Laravel', 'React', 'Node.js', 'MySQL', 'Docker', 'REST API']],
            'Mobile Developer' => ['weight' => 1.45, 'demand' => 'Tinggi', 'typical_skills' => ['Flutter', 'Kotlin', 'Swift', 'React Native', 'Firebase']],
            'DevOps / Cloud Engineer' => ['weight' => 1.75, 'demand' => 'Sangat Tinggi', 'typical_skills' => ['AWS', 'GCP', 'Docker', 'Kubernetes', 'CI/CD', 'Terraform']],
            'Site Reliability Engineer (SRE)' => ['weight' => 1.80, 'demand' => 'Sangat Tinggi', 'typical_skills' => ['Kubernetes', 'Prometheus', 'Linux', 'Golang', 'Incident Management']],
            'Solutions Architect' => ['weight' => 2.30, 'demand' => 'Sangat Tinggi', 'typical_skills' => ['Cloud Architecture', 'Microservices', 'Enterprise Design', 'Security']],
            'QA / Automation Engineer' => ['weight' => 1.25, 'demand' => 'Tinggi', 'typical_skills' => ['Selenium', 'Cypress', 'Playwright', 'Postman', 'JMeter']],
            'Cybersecurity Engineer' => ['weight' => 1.80, 'demand' => 'Sangat Tinggi', 'typical_skills' => ['Penetration Testing', 'SIEM', 'SOC', 'Network Security', 'ISO 27001']],
            'Embedded & IoT Engineer' => ['weight' => 1.40, 'demand' => 'Stabil', 'typical_skills' => ['C/C++', 'RTOS', 'MQTT', 'Microcontrollers', 'PCB Design']],
        ],
        'Data, AI & Analytics' => [
            'AI / Machine Learning Engineer' => ['weight' => 1.90, 'demand' => 'Sangat Tinggi', 'typical_skills' => ['Python', 'PyTorch', 'TensorFlow', 'LLM', 'GenAI', 'MLOps']],
            'Data Scientist' => ['weight' => 1.70, 'demand' => 'Sangat Tinggi', 'typical_skills' => ['Python', 'R', 'Machine Learning', 'SQL', 'Statistical Modeling']],
            'Data Engineer' => ['weight' => 1.65, 'demand' => 'Sangat Tinggi', 'typical_skills' => ['SQL', 'Python', 'Apache Spark', 'Kafka', 'Airflow', 'Data Warehousing']],
            'Data Analyst' => ['weight' => 1.30, 'demand' => 'Tinggi', 'typical_skills' => ['SQL', 'Excel', 'Power BI', 'Tableau', 'Python']],
            'Business Intelligence (BI) Specialist' => ['weight' => 1.35, 'demand' => 'Tinggi', 'typical_skills' => ['Power BI', 'Tableau', 'SQL Server', 'ETL', 'Data Modeling']],
        ],
        'Product & Management' => [
            'Product Manager' => ['weight' => 1.75, 'demand' => 'Sangat Tinggi', 'typical_skills' => ['Product Strategy', 'Scrum', 'Data-Driven', 'Wireframing', 'PRD']],
            'Associate Product Manager (APM)' => ['weight' => 1.30, 'demand' => 'Tinggi', 'typical_skills' => ['User Research', 'Agile', 'Product Analytics', 'Figma']],
            'Product Owner' => ['weight' => 1.55, 'demand' => 'Tinggi', 'typical_skills' => ['Agile', 'Scrum', 'Backlog Grooming', 'Stakeholder Management']],
            'UI/UX Designer' => ['weight' => 1.30, 'demand' => 'Tinggi', 'typical_skills' => ['Figma', 'Prototyping', 'Design System', 'User Research', 'Wireframing']],
            'UX Researcher' => ['weight' => 1.35, 'demand' => 'Tinggi', 'typical_skills' => ['Usability Testing', 'User Interview', 'Card Sorting', 'Analytics']],
            'Scrum Master / Agile Coach' => ['weight' => 1.50, 'demand' => 'Tinggi', 'typical_skills' => ['Scrum', 'Kanban', 'Jira', 'Agile Coaching', 'Sprint Planning']],
            'Project Manager' => ['weight' => 1.45, 'demand' => 'Tinggi', 'typical_skills' => ['PMP', 'Risk Management', 'Budgeting', 'Jira', 'Stakeholder Management']],
        ],
        'Sales, Growth & Marketing' => [
            'Digital Marketing Specialist' => ['weight' => 1.20, 'demand' => 'Tinggi', 'typical_skills' => ['Meta Ads', 'Google Ads', 'SEO', 'Content Strategy', 'Google Analytics']],
            'Performance Marketing / Media Buyer' => ['weight' => 1.35, 'demand' => 'Sangat Tinggi', 'typical_skills' => ['ROAS Optimization', 'Meta Ads Manager', 'TikTok Ads', 'Google Ads']],
            'SEO Specialist' => ['weight' => 1.20, 'demand' => 'Tinggi', 'typical_skills' => ['Ahrefs', 'Semrush', 'Technical SEO', 'On-Page SEO', 'Keyword Research']],
            'Social Media Specialist' => ['weight' => 1.05, 'demand' => 'Stabil', 'typical_skills' => ['Instagram', 'TikTok', 'Copywriting', 'Content Calendar', 'Canva']],
            'Content Writer / Copywriter' => ['weight' => 1.05, 'demand' => 'Stabil', 'typical_skills' => ['Copywriting', 'SEO Articles', 'Storytelling', 'Proofreading']],
            'Business Development (B2B)' => ['weight' => 1.35, 'demand' => 'Tinggi', 'typical_skills' => ['Negotiation', 'Partnership', 'B2B Sales', 'CRM', 'Market Expansion']],
            'Account Executive' => ['weight' => 1.25, 'demand' => 'Tinggi', 'typical_skills' => ['Prospecting', 'Closing', 'Hubspot', 'Sales Pitch', 'Relationship Management']],
            'Customer Success Specialist' => ['weight' => 1.15, 'demand' => 'Tinggi', 'typical_skills' => ['Client Retention', 'Onboarding', 'CRM', 'Customer NPS']],
        ],
        'Finance, Accounting & Legal' => [
            'Financial Analyst' => ['weight' => 1.45, 'demand' => 'Tinggi', 'typical_skills' => ['Financial Modeling', 'DCF', 'Excel Advanced', 'Valuation', 'Forecasting']],
            'Investment Analyst' => ['weight' => 1.65, 'demand' => 'Tinggi', 'typical_skills' => ['Due Diligence', 'Financial Modeling', 'Venture Capital', 'Bloomberg']],
            'Accounting Specialist' => ['weight' => 1.15, 'demand' => 'Stabil', 'typical_skills' => ['General Ledger', 'Journal Entries', 'Accurate', 'SAP', 'Financial Statements']],
            'Tax Specialist (Brevet A/B)' => ['weight' => 1.25, 'demand' => 'Tinggi', 'typical_skills' => ['e-Faktur', 'PPh 21/23/4(2)', 'PPN', 'Tax Planning', 'Brevet AB']],
            'Auditor' => ['weight' => 1.30, 'demand' => 'Tinggi', 'typical_skills' => ['Internal Audit', 'Risk Assessment', 'SOX Compliance', 'Working Papers']],
            'Corporate Legal Counsel' => ['weight' => 1.55, 'demand' => 'Tinggi', 'typical_skills' => ['Contract Drafting', 'M&A', 'Corporate Law', 'Compliance', 'Litigation']],
        ],
        'Human Resources & Operations' => [
            'HR Generalist' => ['weight' => 1.15, 'demand' => 'Stabil', 'typical_skills' => ['Employee Relations', 'Payroll', 'BPJS', 'UU Ketenagakerjaan', 'HRIS']],
            'HR Business Partner (HRBP)' => ['weight' => 1.60, 'demand' => 'Sangat Tinggi', 'typical_skills' => ['Strategic HR', 'Talent Management', 'Org Development', 'Change Management']],
            'Talent Acquisition / Recruiter' => ['weight' => 1.20, 'demand' => 'Tinggi', 'typical_skills' => ['Headhunting', 'LinkedIn Recruiter', 'Interviewing', 'ATS', 'Employer Branding']],
            'Compensation & Benefits (Comp & Ben)' => ['weight' => 1.35, 'demand' => 'Tinggi', 'typical_skills' => ['Salary Grading', 'Payroll', 'Tax Calculations', 'Market Surveys']],
            'Operations Specialist' => ['weight' => 1.15, 'demand' => 'Stabil', 'typical_skills' => ['Process Improvement', 'SOP Drafting', 'Inventory', 'Data Analysis']],
            'Supply Chain / Logistics Specialist' => ['weight' => 1.25, 'demand' => 'Tinggi', 'typical_skills' => ['Procurement', 'Vendor Management', 'SAP MM', 'Import/Export']],
        ],
        'Creative, Multimedia & Design' => [
            'Graphic Designer' => ['weight' => 1.10, 'demand' => 'Stabil', 'typical_skills' => ['Photoshop', 'Illustrator', 'Branding', 'Typography', 'Visual Identity']],
            'Video Editor & Motion Designer' => ['weight' => 1.20, 'demand' => 'Tinggi', 'typical_skills' => ['Premiere Pro', 'After Effects', 'Color Grading', 'Sound Design']],
            '3D Artist / Animator' => ['weight' => 1.35, 'demand' => 'Tinggi', 'typical_skills' => ['Blender', 'Maya', '3ds Max', 'Texturing', 'Lighting', 'Unreal Engine']],
            'Creative Director' => ['weight' => 2.00, 'demand' => 'Tinggi', 'typical_skills' => ['Art Direction', 'Brand Strategy', 'Campaign Conceptualization', 'Team Leadership']],
        ],
        'Administrative & Support' => [
            'Administrative Assistant' => ['weight' => 0.95, 'demand' => 'Stabil', 'typical_skills' => ['Microsoft Office', 'Google Workspace', 'Scheduling', 'Archiving', 'Filing']],
            'Executive Assistant' => ['weight' => 1.25, 'demand' => 'Tinggi', 'typical_skills' => ['Calendar Management', 'Travel Itinerary', 'Confidentiality', 'Bilingual']],
            'Customer Service / Support' => ['weight' => 0.90, 'demand' => 'Stabil', 'typical_skills' => ['Zendesk', 'Live Chat', 'Communication', 'Problem Solving', 'Empathy']],
        ],
    ];

    /**
     * Seniority levels and their multiplier factors.
     */
    protected static array $seniorityLevels = [
        'Intern' => [
            'name' => 'Internship / Magang',
            'years' => '0 Tahun (Mahasiswa / Fresh)',
            'mult' => 0.70,
            'desc' => 'Uang saku magang kompetitif & pembinaan awal'
        ],
        'Fresh Graduate' => [
            'name' => 'Fresh Graduate / Entry Level',
            'years' => '0 - 1 Tahun',
            'mult' => 1.00,
            'desc' => 'Memulai karir profesional dengan bimbingan senior'
        ],
        'Junior' => [
            'name' => 'Junior Level',
            'years' => '1 - 2 Tahun',
            'mult' => 1.38,
            'desc' => 'Mampu mengerjakan tugas secara mandiri dengan review berkala'
        ],
        'Mid' => [
            'name' => 'Mid Level',
            'years' => '2 - 5 Tahun',
            'mult' => 2.10,
            'desc' => 'Mandiri sepenuhnya, berkontribusi pada desain solusi dan efisiensi'
        ],
        'Senior' => [
            'name' => 'Senior Level',
            'years' => '5+ Tahun',
            'mult' => 3.35,
            'desc' => 'Menguasai domain keahlian, mentoring tim junior, pengambilan keputusan teknis'
        ],
        'Lead' => [
            'name' => 'Lead / Principal / Staff',
            'years' => '7+ Tahun',
            'mult' => 4.80,
            'desc' => 'Kepemimpinan teknis lintas tim, standardisasi arsitektur dan inovasi'
        ],
        'Manager' => [
            'name' => 'Managerial / Head of Dept',
            'years' => '8+ Tahun',
            'mult' => 6.20,
            'desc' => 'Strategi divisi, manajemen anggaran, pembinaan people & organisasi'
        ],
        'Director' => [
            'name' => 'Director / C-Level (Executive)',
            'years' => '10+ Tahun',
            'mult' => 9.50,
            'desc' => 'Eksekutif perusahaan, penentu visi, arah bisnis makro dan P&L'
        ],
    ];

    /**
     * Skill premium catalog with market bonus percentage.
     */
    protected static array $skillBonuses = [
        'ai' => 12,
        'machine learning' => 12,
        'genai' => 12,
        'llm' => 12,
        'kubernetes' => 10,
        'aws' => 9,
        'gcp' => 9,
        'azure' => 9,
        'devops' => 9,
        'golang' => 8,
        'flutter' => 8,
        'react' => 7,
        'next.js' => 7,
        'laravel' => 7,
        'node.js' => 7,
        'python' => 8,
        'cybersecurity' => 10,
        'data science' => 9,
        'microservices' => 8,
        'postgresql' => 6,
        'ci/cd' => 7,
        'docker' => 6,
        'scrum' => 5,
        'pmp' => 8,
        'power bi' => 6,
        'financial modeling' => 8,
        'sap' => 8,
        'seo' => 5,
    ];

    /**
     * Get list of all available categories and roles.
     */
    public static function getRolesCatalog(): array
    {
        return self::$rolesCatalog;
    }

    /**
     * Get list of all available seniority levels.
     */
    public static function getSeniorityLevels(): array
    {
        return self::$seniorityLevels;
    }

    /**
     * Calculate comprehensive salary benchmark.
     */
    public static function calculate(array $params): array
    {
        $position = trim($params['position'] ?? 'Software Engineer');
        $location = trim($params['location'] ?? 'Jakarta');
        $levelKey = self::normalizeLevelKey($params['level'] ?? 'Fresh Graduate');
        $skillsInput = $params['skills'] ?? '';

        // 1. Resolve Job Role & Base Multiplier
        $roleInfo = self::resolveRoleInfo($position);
        $roleWeight = $roleInfo['weight'];
        $roleCategory = $roleInfo['category'];
        $roleDemand = $roleInfo['demand'];
        $canonicalRole = $roleInfo['matched_title'];

        // 2. Resolve Seniority Multiplier
        $levelData = self::$seniorityLevels[$levelKey] ?? self::$seniorityLevels['Fresh Graduate'];
        $levelMult = $levelData['mult'];

        // 3. Resolve Location & Regional Wage (UMK 2026)
        $umkRecord = null;
        try {
            $umkRecord = UmkReference::findByLocation($location);
        } catch (\Throwable $e) {
            $umkRecord = null;
        }
        
        $nationalBaseUmk = 5000000; // Baseline reference
        if ($umkRecord) {
            $baseUmk = (float) $umkRecord->umk_amount;
            $cityName = $umkRecord->city_district;
            $provinceName = $umkRecord->province;
            $legalDecree = $umkRecord->legal_decree;
            $isKnownRegion = true;
        } else {
            $cleanLoc = strtolower($location);
            $foundFallback = null;
            $defaultCityUmk = [
                'jakarta' => ['amount' => 5396761, 'city' => 'DKI Jakarta', 'province' => 'DKI Jakarta', 'decree' => 'Kepgub DKI Jakarta No. 833/2026'],
                'surabaya' => ['amount' => 4975000, 'city' => 'Kota Surabaya', 'province' => 'Jawa Timur', 'decree' => 'Kepgub Jatim No. 188/2026'],
                'bandung' => ['amount' => 4450000, 'city' => 'Kota Bandung', 'province' => 'Jawa Barat', 'decree' => 'Kepgub Jabar No. 561/2026'],
                'yogyakarta' => ['amount' => 2650000, 'city' => 'Kota Yogyakarta', 'province' => 'D.I. Yogyakarta', 'decree' => 'Kepgub DIY No. 421/2026'],
                'jogja' => ['amount' => 2650000, 'city' => 'Kota Yogyakarta', 'province' => 'D.I. Yogyakarta', 'decree' => 'Kepgub DIY No. 421/2026'],
                'semarang' => ['amount' => 3450000, 'city' => 'Kota Semarang', 'province' => 'Jawa Tengah', 'decree' => 'Kepgub Jateng No. 560/2026'],
                'medan' => ['amount' => 4050000, 'city' => 'Kota Medan', 'province' => 'Sumatera Utara', 'decree' => 'Kepgub Sumut No. 188/2026'],
                'denpasar' => ['amount' => 3350000, 'city' => 'Kota Denpasar', 'province' => 'Bali', 'decree' => 'Kepgub Bali No. 970/2026'],
                'bali' => ['amount' => 3350000, 'city' => 'Bali (Denpasar)', 'province' => 'Bali', 'decree' => 'Kepgub Bali No. 970/2026'],
                'makassar' => ['amount' => 3850000, 'city' => 'Kota Makassar', 'province' => 'Sulawesi Selatan', 'decree' => 'Kepgub Sulsel No. 110/2026'],
                'bekasi' => ['amount' => 5650000, 'city' => 'Kota Bekasi', 'province' => 'Jawa Barat', 'decree' => 'Kepgub Jabar No. 561/2026'],
                'karawang' => ['amount' => 5550000, 'city' => 'Kabupaten Karawang', 'province' => 'Jawa Barat', 'decree' => 'Kepgub Jabar No. 561/2026'],
                'tangerang' => ['amount' => 4950000, 'city' => 'Kota Tangerang', 'province' => 'Banten', 'decree' => 'Kepgub Banten No. 561/2026'],
            ];

            foreach ($defaultCityUmk as $k => $info) {
                if (str_contains($cleanLoc, $k)) {
                    $foundFallback = $info;
                    break;
                }
            }

            if ($foundFallback) {
                $baseUmk = (float) $foundFallback['amount'];
                $cityName = $foundFallback['city'];
                $provinceName = $foundFallback['province'];
                $legalDecree = $foundFallback['decree'];
                $isKnownRegion = true;
            } else {
                $baseUmk = 5396761;
                $cityName = $location ?: 'Nasional (Standar Jakarta)';
                $provinceName = 'Indonesia';
                $legalDecree = 'Standar Benchmark Nasional 2026';
                $isKnownRegion = false;
            }
        }

        // 4. Calculate Skill Premium
        $skillList = is_array($skillsInput) 
            ? $skillsInput 
            : array_filter(array_map('trim', explode(',', (string) $skillsInput)));

        $matchedSkillPremiums = [];
        $totalSkillBonusPct = 0;

        foreach ($skillList as $skill) {
            $lowerSkill = strtolower(trim($skill));
            if (empty($lowerSkill)) continue;

            $matched = false;
            foreach (self::$skillBonuses as $keyword => $pct) {
                if (str_contains($lowerSkill, $keyword)) {
                    $totalSkillBonusPct += $pct;
                    $matchedSkillPremiums[] = [
                        'skill' => ucwords($skill),
                        'bonus_pct' => $pct,
                    ];
                    $matched = true;
                    break;
                }
            }

            if (!$matched && strlen($lowerSkill) >= 3) {
                // Default minor skill recognition (+3%)
                $totalSkillBonusPct += 3;
                $matchedSkillPremiums[] = [
                    'skill' => ucwords($skill),
                    'bonus_pct' => 3,
                ];
            }
        }

        // Cap total skill bonus at 40%
        $totalSkillBonusPct = min(40, $totalSkillBonusPct);
        $skillMultiplier = 1 + ($totalSkillBonusPct / 100);

        // 5. Distinct Core Calculation Formula calibrated to Indonesian Market Realities:
        $regionRatio = $baseUmk / $nationalBaseUmk;
        
        // High-tech and specialized jobs have higher national liquidity
        $liquidityFactor = match(true) {
            $roleCategory === 'Tech & Software Engineering' || $roleCategory === 'Data, AI & Analytics' => 0.85 + (0.15 * $regionRatio),
            $roleCategory === 'Product & Management' || $roleCategory === 'Finance, Accounting & Legal' => 0.75 + (0.25 * $regionRatio),
            default => 0.60 + (0.40 * $regionRatio),
        };

        if ($levelKey === 'Intern') {
            $internRoleMult = 1 + max(0, ($roleWeight - 1.0) * 0.25);
            $medianSalary = (int) round($baseUmk * 0.75 * $internRoleMult * $skillMultiplier, -4);
            $minSalary = (int) round($baseUmk * 0.60, -4);
            $maxSalary = (int) round($medianSalary * 1.30, -4);
        } elseif ($levelKey === 'Fresh Graduate') {
            // Fresh Graduate entry-level bonus: role premium is normalized between +0% to +35% above UMK
            $entryRoleBonus = 1 + max(0, ($roleWeight - 1.0) * 0.35);
            $medianSalary = (int) round($baseUmk * $entryRoleBonus * $skillMultiplier, -4);
            // Minimum is strictly the regional UMK
            $minSalary = (int) $baseUmk;
            // Maximum for fresh grad (top unicorn / BUMN MT)
            $maxSalary = (int) round($medianSalary * 1.30, -4);
        } elseif ($levelKey === 'Junior') {
            $juniorRoleMult = 1 + max(0, ($roleWeight - 1.0) * 0.65);
            $medianSalary = (int) round($baseUmk * 1.45 * $juniorRoleMult * $liquidityFactor * $skillMultiplier, -4);
            $minSalary = (int) max(round($baseUmk * 1.15, -4), round($medianSalary * 0.80, -4));
            $maxSalary = (int) round($medianSalary * 1.40, -4);
        } elseif ($levelKey === 'Mid') {
            $medianSalary = (int) round($baseUmk * 2.20 * $roleWeight * $liquidityFactor * $skillMultiplier, -4);
            $minSalary = (int) max(round($baseUmk * 1.60, -4), round($medianSalary * 0.78, -4));
            $maxSalary = (int) round($medianSalary * 1.50, -4);
        } elseif ($levelKey === 'Senior') {
            $medianSalary = (int) round($baseUmk * 3.40 * $roleWeight * $liquidityFactor * $skillMultiplier, -4);
            $minSalary = (int) max(round($baseUmk * 2.40, -4), round($medianSalary * 0.75, -4));
            $maxSalary = (int) round($medianSalary * 1.55, -4);
        } elseif ($levelKey === 'Lead') {
            $medianSalary = (int) round($baseUmk * 4.80 * $roleWeight * $liquidityFactor * $skillMultiplier, -4);
            $minSalary = (int) max(round($baseUmk * 3.50, -4), round($medianSalary * 0.75, -4));
            $maxSalary = (int) round($medianSalary * 1.60, -4);
        } elseif ($levelKey === 'Manager') {
            $medianSalary = (int) round($baseUmk * 6.20 * $roleWeight * $liquidityFactor * $skillMultiplier, -4);
            $minSalary = (int) max(round($baseUmk * 4.50, -4), round($medianSalary * 0.75, -4));
            $maxSalary = (int) round($medianSalary * 1.65, -4);
        } else {
            // Director / C-Level
            $medianSalary = (int) round($baseUmk * 9.50 * $roleWeight * $liquidityFactor * $skillMultiplier, -4);
            $minSalary = (int) max(round($baseUmk * 7.00, -4), round($medianSalary * 0.75, -4));
            $maxSalary = (int) round($medianSalary * 1.70, -4);
        }

        // Ensure strictly min < median < max
        if ($medianSalary <= $minSalary) {
            $medianSalary = (int) round($minSalary * 1.15, -4);
        }
        if ($maxSalary <= $medianSalary) {
            $maxSalary = (int) round($medianSalary * 1.25, -4);
        }

        // 6. Net Take-Home Pay (THP) Estimation (Indonesian Tax & BPJS)
        $thpEstimate = self::calculateNetThp($medianSalary);

        // 7. Active Job Count & Samples from DB
        $jobQuery = Job::query()
            ->where(function ($q) use ($position, $canonicalRole, $roleCategory) {
                $q->where('title', 'like', "%{$position}%")
                  ->orWhere('title', 'like', "%{$canonicalRole}%")
                  ->orWhere('division', 'like', "%{$position}%");
            });

        $activeJobCount = (clone $jobQuery)->count();
        $sampleJobs = (clone $jobQuery)
            ->select('id', 'title', 'company_name', 'location', 'work_type', 'salary', 'created_at')
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($j) {
                return [
                    'id' => $j->id,
                    'title' => $j->title,
                    'company' => $j->company_name ?: 'Perusahaan Mitra',
                    'location' => $j->location,
                    'type' => $j->work_type,
                    'salary_display' => $j->salary ?: 'Gaji Kompetitif (Negosiasi)',
                    'detail_url' => url('/jobs/' . ($j->hash_id ?? $j->id)),
                ];
            });

        // 8. Cost of Living Tier
        $costTier = match(true) {
            $baseUmk >= 5000000 => 'Tier 1 (Metropolitan / Hub Industri)',
            $baseUmk >= 3500000 => 'Tier 2 (Kota Berkembang / Urban)',
            default => 'Tier 3 (Kota Regional / Standar Biaya Hidup Rendah)',
        };

        $ratioToUmk = round($medianSalary / max(1, $baseUmk), 2);

        return [
            'query' => [
                'position' => $position,
                'canonical_role' => $canonicalRole,
                'category' => $roleCategory,
                'level' => $levelKey,
                'level_name' => $levelData['name'],
                'years_of_experience' => $levelData['years'],
                'location' => $location,
                'skills' => $skillList,
            ],
            'salary_range' => [
                'currency' => 'IDR',
                'min_monthly' => $minSalary,
                'median_monthly' => $medianSalary,
                'max_monthly' => $maxSalary,
                'formatted' => [
                    'min' => 'Rp ' . number_format($minSalary, 0, ',', '.'),
                    'median' => 'Rp ' . number_format($medianSalary, 0, ',', '.'),
                    'max' => 'Rp ' . number_format($maxSalary, 0, ',', '.'),
                    'summary' => 'Rp ' . self::formatJuta($minSalary) . ' - Rp ' . self::formatJuta($maxSalary) . ' / bulan',
                ],
                'annual_estimate' => [
                    'min' => $minSalary * 13, // 12 months + 1 month THR
                    'median' => $medianSalary * 13,
                    'max' => $maxSalary * 13,
                    'formatted_median' => 'Rp ' . number_format($medianSalary * 13, 0, ',', '.') . ' / tahun (inc. THR)',
                ],
            ],
            'regional_benchmark' => [
                'city' => $cityName,
                'province' => $provinceName,
                'umk_amount_2026' => (int) $baseUmk,
                'formatted_umk' => 'Rp ' . number_format($baseUmk, 0, ',', '.'),
                'ratio_to_umk' => $ratioToUmk . 'x UMK Wilayah',
                'cost_of_living_tier' => $costTier,
                'legal_reference' => $legalDecree,
                'is_exact_region_match' => $isKnownRegion,
            ],
            'market_insights' => [
                'market_demand' => $roleDemand,
                'role_weight_factor' => $roleWeight,
                'experience_level_multiplier' => $levelMult,
                'skill_bonus_percentage' => $totalSkillBonusPct,
                'matched_skill_premiums' => $matchedSkillPremiums,
                'typical_in_demand_skills' => $roleInfo['typical_skills'] ?? [],
            ],
            'take_home_pay_estimate' => $thpEstimate,
            'job_market_availability' => [
                'active_jobs_count' => $activeJobCount,
                'sample_jobs' => $sampleJobs,
            ],
        ];
    }

    /**
     * Resolve role information and matching category from catalog.
     */
    protected static function resolveRoleInfo(string $position): array
    {
        $cleanPos = strtolower($position);

        foreach (self::$rolesCatalog as $category => $roles) {
            foreach ($roles as $roleName => $info) {
                if (str_contains($cleanPos, strtolower($roleName)) || str_contains(strtolower($roleName), $cleanPos)) {
                    return array_merge($info, [
                        'category' => $category,
                        'matched_title' => $roleName,
                    ]);
                }
            }
        }

        // Generic fallback heuristics based on keywords
        if (preg_match('/(engineer|developer|programmer|coder|devops|architect|sysadmin)/i', $cleanPos)) {
            return [
                'weight' => 1.40,
                'demand' => 'Tinggi',
                'category' => 'Tech & Software Engineering',
                'matched_title' => ucwords($position),
                'typical_skills' => ['Coding', 'Git', 'Databases', 'Problem Solving'],
            ];
        }
        if (preg_match('/(data|analytics|bi|intelligence|scientist)/i', $cleanPos)) {
            return [
                'weight' => 1.45,
                'demand' => 'Tinggi',
                'category' => 'Data, AI & Analytics',
                'matched_title' => ucwords($position),
                'typical_skills' => ['SQL', 'Python', 'Data Analysis', 'Reporting'],
            ];
        }
        if (preg_match('/(manager|lead|head|director|chief|vp)/i', $cleanPos)) {
            return [
                'weight' => 1.70,
                'demand' => 'Tinggi',
                'category' => 'Product & Management',
                'matched_title' => ucwords($position),
                'typical_skills' => ['Leadership', 'Strategic Planning', 'Management'],
            ];
        }
        if (preg_match('/(marketing|sales|growth|seo|ads|content)/i', $cleanPos)) {
            return [
                'weight' => 1.15,
                'demand' => 'Stabil',
                'category' => 'Sales, Growth & Marketing',
                'matched_title' => ucwords($position),
                'typical_skills' => ['Marketing', 'Communication', 'Campaigns'],
            ];
        }
        if (preg_match('/(finance|accounting|pajak|tax|audit)/i', $cleanPos)) {
            return [
                'weight' => 1.25,
                'demand' => 'Stabil',
                'category' => 'Finance, Accounting & Legal',
                'matched_title' => ucwords($position),
                'typical_skills' => ['Accounting', 'Taxation', 'Reporting', 'Excel'],
            ];
        }

        // Standard Default
        return [
            'weight' => 1.10,
            'demand' => 'Stabil',
            'category' => 'General Professional',
            'matched_title' => ucwords($position),
            'typical_skills' => ['Communication', 'Microsoft Office', 'Organization'],
        ];
    }

    /**
     * Normalize seniority level string to key.
     */
    protected static function normalizeLevelKey(string $level): string
    {
        $clean = strtolower($level);

        if (str_contains($clean, 'magang') || str_contains($clean, 'intern')) {
            return 'Intern';
        }
        if (str_contains($clean, 'fresh') || str_contains($clean, '0-1') || str_contains($clean, 'entry')) {
            return 'Fresh Graduate';
        }
        if (str_contains($clean, 'junior') || str_contains($clean, '1-2')) {
            return 'Junior';
        }
        if (str_contains($clean, 'mid') || str_contains($clean, '2-5')) {
            return 'Mid';
        }
        if (str_contains($clean, 'director') || str_contains($clean, 'c-level') || str_contains($clean, 'eksekutif')) {
            return 'Director';
        }
        if (str_contains($clean, 'manager') || str_contains($clean, 'head')) {
            return 'Manager';
        }
        if (str_contains($clean, 'lead') || str_contains($clean, 'principal') || str_contains($clean, 'staff')) {
            return 'Lead';
        }
        if (str_contains($clean, 'senior') || str_contains($clean, '5+')) {
            return 'Senior';
        }

        return 'Fresh Graduate';
    }

    /**
     * Calculate estimated Indonesian Net Take-Home Pay (THP).
     */
    protected static function calculateNetThp(int $grossMonthly): array
    {
        // BPJS Ketenagakerjaan: JHT (2%), JP (1% max cap ~Rp 10.042.300)
        $bpjsJht = (int) round($grossMonthly * 0.02);
        $bpjsJp = (int) round(min($grossMonthly, 10042300) * 0.01);
        
        // BPJS Kesehatan: 1% (max cap ~Rp 12.000.000)
        $bpjsKes = (int) round(min($grossMonthly, 12000000) * 0.01);

        $totalBpjsEmployee = $bpjsJht + $bpjsJp + $bpjsKes;

        // PPh 21 Effective Monthly Rate (TER Category A standard single employee)
        $pph21Rate = match(true) {
            $grossMonthly <= 5400000 => 0.00,
            $grossMonthly <= 5650000 => 0.0025,
            $grossMonthly <= 5950000 => 0.005,
            $grossMonthly <= 6300000 => 0.0075,
            $grossMonthly <= 6750000 => 0.01,
            $grossMonthly <= 7500000 => 0.0125,
            $grossMonthly <= 8550000 => 0.015,
            $grossMonthly <= 9650000 => 0.0175,
            $grossMonthly <= 10050000 => 0.02,
            $grossMonthly <= 10350000 => 0.0225,
            $grossMonthly <= 10700000 => 0.025,
            $grossMonthly <= 11050000 => 0.03,
            $grossMonthly <= 11600000 => 0.035,
            $grossMonthly <= 12500000 => 0.04,
            $grossMonthly <= 13750000 => 0.05,
            $grossMonthly <= 15100000 => 0.06,
            $grossMonthly <= 16950000 => 0.07,
            $grossMonthly <= 19750000 => 0.08,
            $grossMonthly <= 24150000 => 0.09,
            $grossMonthly <= 26450000 => 0.10,
            $grossMonthly <= 28000000 => 0.11,
            $grossMonthly <= 30050000 => 0.12,
            $grossMonthly <= 32400000 => 0.13,
            $grossMonthly <= 35400000 => 0.14,
            $grossMonthly <= 39100000 => 0.15,
            $grossMonthly <= 43850000 => 0.16,
            $grossMonthly <= 47800000 => 0.17,
            $grossMonthly <= 51400000 => 0.18,
            $grossMonthly <= 56300000 => 0.19,
            $grossMonthly <= 62200000 => 0.20,
            $grossMonthly <= 68600000 => 0.21,
            $grossMonthly <= 77500000 => 0.22,
            $grossMonthly <= 89000000 => 0.23,
            $grossMonthly <= 101900000 => 0.24,
            $grossMonthly <= 120000000 => 0.25,
            default => 0.26,
        };

        $pph21Monthly = (int) round($grossMonthly * $pph21Rate);
        $totalDeductions = $totalBpjsEmployee + $pph21Monthly;
        $netMonthlyThp = max(0, $grossMonthly - $totalDeductions);

        return [
            'gross_monthly' => $grossMonthly,
            'formatted_gross' => 'Rp ' . number_format($grossMonthly, 0, ',', '.'),
            'deductions' => [
                'bpjs_ketenagakerjaan_jht' => $bpjsJht,
                'bpjs_ketenagakerjaan_jp' => $bpjsJp,
                'bpjs_kesehatan' => $bpjsKes,
                'total_bpjs' => $totalBpjsEmployee,
                'estimated_pph21' => $pph21Monthly,
                'pph21_effective_rate_pct' => ($pph21Rate * 100) . '%',
                'total_deductions' => $totalDeductions,
            ],
            'net_take_home_pay' => $netMonthlyThp,
            'formatted_net_thp' => 'Rp ' . number_format($netMonthlyThp, 0, ',', '.'),
            'net_percentage' => round(($netMonthlyThp / max(1, $grossMonthly)) * 100, 1) . '%',
        ];
    }

    /**
     * Helper to format numbers into compact Indonesian rupiah millions (e.g., 14.5 Juta).
     */
    public static function formatJuta(int $amount): string
    {
        if ($amount >= 1000000) {
            $val = $amount / 1000000;
            return rtrim(rtrim(number_format($val, 1, ',', '.'), '0'), ',') . ' Juta';
        }
        return number_format($amount, 0, ',', '.');
    }
}
