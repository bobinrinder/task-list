@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>
                            {{ $task->title }}
                        </h3>
                        <em>created at {{ $task->created_at->format('m/d/Y H:i A') }} by {{ $task->user->name }} | Priority
                            @if ($task->priority === 'high')
                                <span class="label label-danger">High</span>
                            @elseif ($task->priority === 'low')
                                <span class="label label-warning">Low</span>
                            @else
                                <span class="label label-success">Normal</span>
                            @endif</em>
                    </div>

                    <div class="panel-heading">
                        <p>
                            {{ $task->text }}
                        </p>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>
                            Comments
                        </h4>
                        @foreach ($task->comments as $comment)
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <strong class="panel-title">{{ $comment->user->name }} </strong>
                                    <em>commented at {{ $comment->created_at }}</em>
                                </div>
                                <div class="panel-body">
                                    {{ $comment->text }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="post" action="/comment">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="text">Leave a comment</label>
                                <textarea class="form-control" id="text" rows="3" name="text" required></textarea>
                            </div>
                            <div class="form-group hidden">
                                <label for="followers">Followers</label>
                                <select multiple class="form-control" id="followers" name="followers[]">
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group hidden">
                                <label for="assignees">Assignees</label>
                                <select multiple class="form-control" id="assignees" name="assignees[]">
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" value="{{ $task->id }}" name="task_id">
                            <button type="submit" class="btn btn-primary">Comment</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        <h4>
                            Actions
                        </h4>
                        <a href="/home" class="btn btn-default btn-sm">
                            <i class="fa fa-backward" aria-hidden="true"></i> Back
                        </a>
                        &nbsp;&nbsp;
                        <a href="/task/{{ $task->id }}/edit" class="btn btn-info btn-sm">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                        </a>
                        &nbsp;&nbsp;
                        <form method="post" action="/task/{{ $task->id }}" style="display: inline">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                            </button>
                        </form>
                    </div>

                    @if ($task->due_date)
                        <div class="panel-heading">
                            <h4>
                                Due Date
                            </h4>
                            @if (!$task->due_date)
                                <em>not defined yet</em>
                            @else
                                <strong>{{ $task->due_date->format('m/d/Y H:i A') }}</strong>
                            @endif
                        </div>
                    @endif

                    <div class="panel-heading">
                        <h4>
                            Started at
                        </h4>
                        @if (!$task->start_date)
                            <em>not started yet</em>
                            <form method="post" action="/task/{{ $task->id }}/start">
                                {{ csrf_field() }}
                                {{ method_field('PATCH') }}
                                <button type="submit" class="btn btn-success">Start Task Now</button>
                            </form>
                        @else
                            <strong>{{ $task->start_date->format('m/d/Y H:i A') }}</strong>
                        @endif
                    </div>

                    @if ($task->start_date)
                        <div class="panel-heading">
                            <h4>
                                Finished at
                            </h4>
                            @if (!$task->end_date && $task->start_date)
                                <em>currently in progress</em>
                                <form method="post" action="/task/{{ $task->id }}/end">
                                    {{ csrf_field() }}
                                    {{ method_field('PATCH') }}
                                    <button type="submit" class="btn btn-danger">Finish Task</button>
                                </form>
                            @elseif ($task->end_date && $task->start_date)
                                <strong>{{ $task->end_date->format('m/d/Y H:i A') }}</strong><br>
                                <em>by {{ $task->completedUser->name }}</em>
                            @endif
                        </div>
                    @endif

                    @if ($task->start_date && $task->end_date)
                        <div class="panel-heading">
                            <h4>
                                Duration
                            </h4>
                            <strong>
                                {{ $task->end_date->diffInMinutes($task->start_date) }} min
                            </strong>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function () {
            $('#due_date').datetimepicker({
                sideBySide: true
                });
        });
    </script>
@endsection
