<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ExamFormRepositoryInterface;

class WebExamFormController extends Controller
{
    protected $forms;

    public function __construct(ExamFormRepositoryInterface $forms)
    {
        $this->forms = $forms;
    }

    public function index()
    {
        $forms = $this->forms->listByUser(auth()->id());
        return view('exam.index', compact('forms'));
    }

    public function create()
    {
        return view('exam.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'email' => 'required|email',
            'course' => 'required'
        ]);

        $this->forms->create([
            'user_id' => auth()->id(),
            'full_name' => $request->full_name,
            'email' => $request->email,
            'course' => $request->course
        ]);

        return redirect()->route('web.forms.index')->with('success','Form Created');
    }

    public function edit($id)
    {
        $form = $this->forms->find($id);
        return view('exam.edit', compact('form'));
    }

    public function update(Request $request, $id)
    {
        $this->forms->update($id, $request->all());
        return redirect()->route('web.forms.index')->with('success','Form Updated');
    }

    public function destroy($id)
    {
        $this->forms->delete($id);
        return redirect()->route('web.forms.index')->with('success','Form Deleted');
    }
}

