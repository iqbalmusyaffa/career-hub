<?php

namespace App\Interfaces;

interface ApplicationRepositoryInterface
{
    public function getAll();
    public function getByJobId($jobId);
    public function getByUserId($userId);
    public function findById($id);
    public function create(array $data);
    public function updateStatus($id, $status);
}
