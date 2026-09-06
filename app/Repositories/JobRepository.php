<?php

namespace App\Repositories;

use App\Interfaces\JobRepositoryInterface;
use App\Models\Job;

class JobRepository implements JobRepositoryInterface
{
    public function getAll()
    {
        $query = Job::withCount('applications')->latest();

        $user = auth()->user();
        if ($user && !$user->hasRole('Super Admin')) {
            $companyProfile = $user->currentCompanyProfile();
            $companyName = $companyProfile ? $companyProfile->company_name : null;

            if ($companyName) {
                $query->where('company_name', 'LIKE', '%' . $companyName . '%');
            }
        }

        if (request()->filled('company_name')) {
            $comp = request('company_name');
            $query->where('company_name', 'like', "%{$comp}%");
        }

        if (request()->filled('major')) {
            $major = request('major');
            $query->where(function($q) use ($major) {
                $q->where('major_requirement', 'like', "%{$major}%")
                  ->orWhere('description', 'like', "%{$major}%")
                  ->orWhere('requirements', 'like', "%{$major}%");
            });
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('division', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        $perPage = (int) request('per_page', 10);

        return $query->paginate($perPage)->withQueryString();
    }

    public function getActive()
    {
        return Job::where('status', \App\Enums\JobStatus::ACTIVE)->latest()->get();
    }

    public function findById($id)
    {
        $realId = \App\Helpers\IdHasher::decode($id) ?? $id;
        return Job::findOrFail($realId);
    }

    public function create(array $data)
    {
        return Job::create($data);
    }

    public function update($id, array $data)
    {
        $realId = \App\Helpers\IdHasher::decode($id) ?? $id;
        $job = Job::findOrFail($realId);
        $job->update($data);
        return $job;
    }

    public function delete($id)
    {
        $realId = \App\Helpers\IdHasher::decode($id) ?? $id;
        $job = Job::findOrFail($realId);
        return $job->delete();
    }
}
