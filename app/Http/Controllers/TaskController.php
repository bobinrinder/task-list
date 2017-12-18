<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = \App\User::get();
        $task = new Task();

        return view('task.create', compact('users', 'task'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'required|string',
            'due_date' => 'nullable|date',
            'assignees' => 'nullable|array',
            'followers' => 'nullable|array',
            'priority' => [
                'required',
                Rule::in(['high', 'normal', 'low']),
            ],
        ]);

        // get params
        $fields = $request->only([
            'title',
            'text',
            'due_date',
            'priority',
        ]);

        // add user id
        $fields['user_id'] = \Auth::user()->id;

        // create task
        $task = Task::create($fields);

        // add followers
        $followers = $request->get('followers');
        if ($task && $followers) {
            foreach ($followers as $follower) {
                \App\Follow::create(array(
                    'user_id' => (int)$follower,
                    'task_id' => $task->id
                ));
            }
        }

        // add assignees
        $assignees = $request->get('assignees');
        if ($task && $assignees) {
            foreach ($assignees as $assignee) {
                \App\Assignment::create(array(
                    'user_id' => (int)$assignee,
                    'task_id' => $task->id
                ));
            }
        }

        // return
        return redirect('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        $users = \App\User::get();

        return view('task.show', compact('users', 'task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        // validate
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'text' => 'nullable|string',
            'due_date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'assignees' => 'nullable|array',
            'followers' => 'nullable|array',
            'priority' => [
                'nullable',
                Rule::in(['high', 'normal', 'low']),
            ],
        ]);

        // get params
        $task->start_date = $request->get('start_date');


        // add followers
        $followers = $request->get('followers');
        if ($task && $followers) {
            foreach ($followers as $follower) {
                \App\Follow::create(array(
                    'user_id' => (int)$follower,
                    'task_id' => $task->id
                ));
            }
        }

        // add assignees
        $assignees = $request->get('assignees');
        if ($task && $assignees) {
            foreach ($assignees as $assignee) {
                \App\Assignment::create(array(
                    'user_id' => (int)$assignee,
                    'task_id' => $task->id
                ));
            }
        }

        // return
        return redirect('home');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function start(Request $request, Task $task)
    {
        // set start date
        $task->start_date = \Carbon\Carbon::now();
        $task->save();

        // return
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function end(Request $request, Task $task)
    {
        // set start date
        $task->end_date = \Carbon\Carbon::now();
        $task->save();

        // return
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }
}
