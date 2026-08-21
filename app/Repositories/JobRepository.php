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
            $companyName = $user->companyProfile ? $user->companyProfile->company_name : null;
            if ($companyName) {
                $query->where('company_name', $companyName);
            }
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
        return Job::findOrFail($id);
    }

    public function create(array $data)
    {
        return Job::create($data);
    }

    public function update($id, array $data)
    {
        $job = Job::findOrFail($id);
        $job->update($data);
        return $job;
    }

    public function delete($id)
    {
        $job = Job::findOrFail($id);
        return $job->delete();
    }
}
