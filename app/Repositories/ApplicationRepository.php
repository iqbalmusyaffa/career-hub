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
            $ownerId = $user->id;
            $teamMember = \App\Models\CompanyTeamMember::where('user_id', $user->id)->first();
            if ($teamMember) {
                $ownerId = $teamMember->owner_id;
            }

            $ownerProfile = \App\Models\CompanyProfile::where('user_id', $ownerId)->first();
            $companyName = $ownerProfile ? $ownerProfile->company_name : ($user->companyProfile ? $user->companyProfile->company_name : null);

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
