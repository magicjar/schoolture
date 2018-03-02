<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\StudentDashboardController;

class SubjectDashboardController extends StudentDashboardController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Subject Collection
        $subjects = $this->subject->orderBy('created_at', 'desc')->get();
        
        return view('dashboard.subject.create')->withSubjects($subjects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
        	'subject_code' => 'unique:subjects',
            'name' => 'required|unique:subjects',
        ]);
        
        $input = $request->all();
        
        $this->subject->create($input);
        
        return redirect()->back()->with('success', 'New Subject has been created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function teacher()
    {
        // Scope staff from position name
        $teachers = $this->staff->positionName('Teacher')->get();

        // Subject Collection
        $subjects = $this->subject->all();
        
        return view('dashboard.subject.assign-teacher')
            ->withSubjects($subjects)
            ->withTeachers($teachers);
    }

    public function teacherStore(Request $request)
    {
        $this->validate($request, [
            'staff_id' => 'required|unique:teachers_subjects,staff_id,NULL,NULL,subject_id,'. $request['subject_id'],
        ],[
            'staff_id.unique' => 'The staff has already been assigned with the subject.',
        ]);

        $id = $request['staff_id'];
        
        $input = $request->except('_token');
        
        $this->staff->find($id)->subject()->sync($input);
        
        return redirect()->back()->with('success', 'Teacher has been assigned with subject successfuly!');
    }
}