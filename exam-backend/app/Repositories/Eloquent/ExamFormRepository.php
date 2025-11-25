<?php

namespace App\Repositories\Eloquent;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Repositories\Interfaces\ExamFormRepositoryInterface;
use App\Models\ExamForm;

/**
 * Class ExamFormRepository.
 */
class ExamFormRepository implements ExamFormRepositoryInterface
{
    public function create(array $data)
    {
        return ExamForm::create($data);
    }

    public function find($id)
    {
        return ExamForm::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $form = $this->find($id);
        $form->update($data);
        return $form;
    }

    public function delete($id)
    {
        return ExamForm::destroy($id);
    }

    public function listByUser($userId)
    {
        return ExamForm::where('user_id', $userId)->get();
    }
}

