<?php

namespace App\Repositories\Interfaces;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class ExamFormRepositoryInterface.
 */
interface ExamFormRepositoryInterface
{
    public function create(array $data);
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
    public function listByUser($userId);
}

