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
        // add user id
        $fields['text'] = var_dump($request->all());
        $fields['user_id'] = 1;

        // create task
        $comment = Comment::create($fields);

        // return
        return $comment;
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
