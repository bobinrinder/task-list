<?php

namespace App\Http\Controllers;

use App\Mail\TaskFollow;
use App\Task;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskAssign;

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

                // check if follow already exists
                if (!\App\Follow::where([['user_id', '=', (int)$follower], ['task_id', '=', $task->id]])->get()->count()) {

                    // create follow
                    $follow = \App\Follow::create(array(
                        'user_id' => (int)$follower,
                        'task_id' => $task->id
                    ));

                    // mail notification
                    if (\Auth::user()->id !== (int)$follower) {
                        Mail::to($follow->user)->send(new TaskFollow($task));
                    }
                }
            }
        }

        // add assignees
        $assignees = $request->get('assignees');
        if ($task && $assignees) {
            foreach ($assignees as $assignee) {

                // check if assignment already exists
                if (!\App\Assignment::where([['user_id', '=', (int)$assignee], ['task_id', '=', $task->id]])->get()->count()) {

                    // create assignment
                    $assignment = \App\Assignment::create(array(
                        'user_id' => (int)$assignee,
                        'task_id' => $task->id
                    ));

                    // mail notification if it's a self-assign
                    if (\Auth::user()->id !== (int)$assignee) {
                        Mail::to($assignment->user)->send(new TaskAssign($task));
                    }
                }
            }
        }

        // return
        return redirect('task/' . $task->id);
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
        $users = \App\User::get();

        return view('task.create', compact('users', 'task'));
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

        // set new values
        $task->title = $request->get('title');
        $task->text = $request->get('text');
        $task->due_date = $request->get('due_date');
        $task->priority = $request->get('priority');
        $task->save();

        // get old and new followers
        $followers = $request->get('followers');
        $oldFollowers = \App\Follow::where('task_id', '=', $task->id)->get()->pluck('user_id')->toArray();

        // remove old followers
        \App\Follow::where('task_id', '=', $task->id)->delete();

        // update followers
        if ($task && $followers) {

            foreach ($followers as $follower) {

                // check if follow already exists
                if (!\App\Follow::where([['user_id', '=', (int)$follower], ['task_id', '=', $task->id]])->get()->count()) {

                    // create follow
                    $follow = \App\Follow::create(array(
                        'user_id' => (int)$follower,
                        'task_id' => $task->id
                    ));

                    // mail notification
                    if (\Auth::user()->id !== (int)$follower && !in_array((int)$follower, $oldFollowers)) {
                        Mail::to($follow->user)->send(new TaskFollow($task));
                    }
                }
            }
        }

        // get old and new assignees
        $assignees = $request->get('assignees');
        $oldAssignees = \App\Assignment::where('task_id', '=', $task->id)->get()->pluck('user_id')->toArray();

        // remove old assignees
        \App\Assignment::where('task_id', '=', $task->id)->delete();

        // update assignees
        if ($task && $assignees) {
            foreach ($assignees as $assignee) {

                // check if assignment already exists
                if (!\App\Assignment::where([['user_id', '=', (int)$assignee], ['task_id', '=', $task->id]])->get()->count()) {

                    // create assignment
                    $assignment = \App\Assignment::create(array(
                        'user_id' => (int)$assignee,
                        'task_id' => $task->id
                    ));

                    // mail notification if it's a self-assign
                    if (\Auth::user()->id !== (int)$assignee && !in_array((int)$assignee, $oldAssignees)) {
                        Mail::to($assignment->user)->send(new TaskAssign($task));
                    }
                }
            }
        }

        // return
        return redirect('task/' . $task->id);
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
        $task->completed_user_id = \Auth::user()->id;
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
        $task->delete();

        return back();
    }
}
