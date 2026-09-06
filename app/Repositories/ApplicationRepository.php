<?php

namespace App\Repositories;

use App\Interfaces\ApplicationRepositoryInterface;
use App\Models\Application;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    public function getAll()
    {
        $query = Application::with(['user.candidateProfile', 'job'])->latest();

        $user = auth()->user();
        if ($user && !$user->hasRole('Super Admin')) {
            $companyProfile = $user->currentCompanyProfile();
            $companyName = $companyProfile ? $companyProfile->company_name : null;

            if ($companyName) {
                $query->whereHas('job', function($j) use ($companyName) {
                    $j->where('company_name', 'LIKE', '%' . $companyName . '%');
                });
            }
        }

        if (request()->filled('job_id')) {
            $query->where('job_id', request('job_id'));
        }

        if (request()->filled('company_name')) {
            $comp = request('company_name');
            $query->whereHas('job', function($j) use ($comp) {
                $j->where('company_name', 'like', "%{$comp}%");
            });
        }

        if (request()->filled('major')) {
            $major = request('major');
            $query->whereHas('user.candidateProfile', function($cp) use ($major) {
                $cp->where('last_education', 'like', "%{$major}%")
                   ->orWhere('major', 'like', "%{$major}%")
                   ->orWhere('educations', 'like', "%{$major}%");
            });
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('job', function($j) use ($search) {
                    $j->where('title', 'like', "%{$search}%");
                });
            });
        }

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        $perPage = (int) request('per_page', 10);

        return $query->paginate($perPage)->withQueryString();
    }

    public function getStatusCounts()
    {
        $query = Application::query();
        $user = auth()->user();
        if ($user && !$user->hasRole('Super Admin')) {
            $companyProfile = $user->currentCompanyProfile();
            $companyName = $companyProfile ? $companyProfile->company_name : null;

            if ($companyName) {
                $query->whereHas('job', function($j) use ($companyName) {
                    $j->where('company_name', 'LIKE', '%' . $companyName . '%');
                });
            }
        }

        if (request()->filled('job_id')) {
            $query->where('job_id', request('job_id'));
        }

        if (request()->filled('company_name')) {
            $comp = request('company_name');
            $query->whereHas('job', function($j) use ($comp) {
                $j->where('company_name', 'like', "%{$comp}%");
            });
        }

        if (request()->filled('major')) {
            $major = request('major');
            $query->whereHas('user.candidateProfile', function($cp) use ($major) {
                $cp->where('last_education', 'like', "%{$major}%")
                   ->orWhere('major', 'like', "%{$major}%")
                   ->orWhere('educations', 'like', "%{$major}%");
            });
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('job', function($j) use ($search) {
                    $j->where('title', 'like', "%{$search}%");
                });
            });
        }

        $rawCounts = (clone $query)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status')->toArray();
        $totalAll = (clone $query)->count();

        return [
            'all' => $totalAll,
            'pending' => ($rawCounts['pending'] ?? 0) + ($rawCounts['reviewing'] ?? 0),
            'test' => ($rawCounts['test'] ?? 0),
            'interview' => ($rawCounts['interview'] ?? 0) + ($rawCounts['interview_hr'] ?? 0) + ($rawCounts['interview_user'] ?? 0),
            'accepted' => ($rawCounts['accepted'] ?? 0) + ($rawCounts['hired'] ?? 0) + ($rawCounts['offered'] ?? 0),
            'rejected' => ($rawCounts['rejected'] ?? 0),
        ];
    }

    public function getByJobId($jobId)
    {
        return Application::with('user.candidateProfile')->where('job_id', $jobId)->latest()->get();
    }

    public function getByUserId($userId)
    {
        return Application::with('job')->where('user_id', $userId)->latest()->get();
    }

    public function findById($id)
    {
        return Application::with(['user.candidateProfile', 'job'])->findByEncryptedIdOrFail($id);
    }

    public function create(array $data)
    {
        return Application::create($data);
    }

    public function updateStatus($id, $status)
    {
        $application = Application::findOrFail($id);
        $application->update(['status' => $status]);
        return $application;
    }
}
