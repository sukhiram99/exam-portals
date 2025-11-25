<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ExamFormRepositoryInterface;

class ApiExamFormController extends Controller
{
    protected $forms;

    public function __construct(ExamFormRepositoryInterface $forms)
    {
        $this->forms = $forms;
    }

    public function index()
    {
        return response()->json(
            $this->forms->listByUser(auth()->id())
        );
    }

    public function store(Request $request)
    {
        $form = $this->forms->create([
            'user_id' => auth()->id(),
            'full_name' => $request->full_name,
            'email' => $request->email,
            'course' => $request->course
        ]);

        return response()->json($form, 201);
    }

    public function show($id)
    {
        return response()->json($this->forms->find($id));
    }

    public function update(Request $request, $id)
    {
        return response()->json($this->forms->update($id, $request->all()));
    }

    public function destroy($id)
    {
        $this->forms->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}

