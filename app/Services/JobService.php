<?php

namespace App\Services;

use App\Interfaces\JobRepositoryInterface;

class JobService
{
    protected $jobRepository;

    public function __construct(JobRepositoryInterface $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    public function getAllJobs()
    {
        return $this->jobRepository->getAll();
    }

    public function getActiveJobs()
    {
        return $this->jobRepository->getActive();
    }

    public function getJobById($id)
    {
        return $this->jobRepository->findById($id);
    }

    public function createJob(array $data)
    {
        return $this->jobRepository->create($data);
    }

    public function updateJob($id, array $data)
    {
        return $this->jobRepository->update($id, $data);
    }

    public function deleteJob($id)
    {
        return $this->jobRepository->delete($id);
    }
}
