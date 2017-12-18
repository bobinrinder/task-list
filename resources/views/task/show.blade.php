@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>
                            {{ $task->title }}
                        </h3>
                        <em>created at {{ $task->created_at }} by {{ $task->user->name }} | Priority
                            @if ($task->priority === 'high')
                                <span class="label label-danger">High</span>
                            @elseif ($task->priority === 'low')
                                <span class="label label-warning">Low</span>
                            @else
                                <span class="label label-success">Normal</span>
                            @endif</em>
                        <br>
                        @if (!$task->start_date)
                            <form method="post" action="/task/{{ $task->id }}/start">
                                {{ csrf_field() }}
                                {{ method_field('PATCH') }}
                                <button type="submit" class="btn btn-success">Start Task</button>
                            </form>
                        @else
                            <strong>Task was started at {{ $task->start_date->format('m/d/Y H:i A') }}</strong>
                        @endif
                        @if (!$task->end_date && $task->start_date)
                            <form method="post" action="/task/{{ $task->id }}/end">
                                {{ csrf_field() }}
                                {{ method_field('PATCH') }}
                                <button type="submit" class="btn btn-danger">Finish Task</button>
                            </form>
                        @elseif ($task->end_date && $task->start_date)
                            | <strong>Task was finished at {{ $task->end_date->format('m/d/Y H:i A') }}</strong>
                        @endif
                    </div>

                    <div class="panel-heading">
                        <p>
                            {{ $task->text }}
                        </p>
                    </div>

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
