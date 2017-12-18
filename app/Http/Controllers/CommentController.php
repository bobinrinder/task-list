<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
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
        //
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
            'text' => 'required|string',
            'task_id' => 'exists:tasks,id',
            'assignees' => 'nullable|array',
            'followers' => 'nullable|array',
        ]);

        // get params
        $fields = $request->only([
            'text',
            'task_id',
        ]);

        // add user id
        $fields['user_id'] = \Auth::user()->id;

        // create task
        $comment = Comment::create($fields);

        // add followers
        $followers = $request->get('followers');
        if ($comment && $followers) {
            foreach ($followers as $follower) {
                \App\Follow::create(array(
                    'user_id' => (int)$follower,
                    'task_id' => $comment->id
                ));
            }
        }

        // add assignees
        $assignees = $request->get('assignees');
        if ($comment && $assignees) {
            foreach ($assignees as $assignee) {
                \App\Assignment::create(array(
                    'user_id' => (int)$assignee,
                    'task_id' => $comment->id
                ));
            }
        }

        // return
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFromEmail(Request $request)
    {
        // get user
        $user = \App\User::where('email', '=', $request->get('sender'))->first();
        $text = $request->get('stripped-text');
        $subject = $request->get('subject');
        $taskId = null;
        $comment = null;

        // search for task number in subject
        if (strpos($subject, '#') > -1) {
            $taskId = substr($subject, strpos($subject, '#') + 1);
        }

        // create comment
        if ($user && $text && $taskId) {
            $fields['text'] = $text;
            $fields['user_id'] = $user->id;
            $fields['task_id'] = $taskId;

            $comment = Comment::create($fields);
        }

        // return
        return $comment ? $comment->fresh() : 'Error';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
