@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Create Task</div>

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

                        <form method="post" action="{{ $task->id ? '/task/' . $task->id : '/task' }}">
                            {{ csrf_field() }}
                            {{ method_field($task->id ? 'PATCH' : 'POST') }}
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter task title" autofocus required>
                            </div>
                            <div class="form-group">
                                <label for="text">Task</label>
                                <textarea class="form-control" id="text" rows="3" name="text" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="due_date">Due Date</label>
                                <div class="input-group date" id="due_date" class="datetimepicker">
                                    <input type='text' class="form-control" name="due_date" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="followers">Followers</label>
                                <select multiple class="form-control" id="followers" name="followers[]">
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="assignees">Assignees</label>
                                <select multiple class="form-control" id="assignees" name="assignees[]">
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="priority">Priority</label>
                                <select id="priority" class="form-control" name="priority">
                                    <option value="high">High</option>
                                    <option value="normal" selected>Normal</option>
                                    <option value="low">Low</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Create</button>
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

            $('#text').atwho({
                at: "@",
                data:[ '<?php echo implode("','", $users->pluck('name')->toArray()); ?>' ]
            });

            $('#text').on("inserted.atwho", function(event, $li, query) {
                console.log(event, query);
                console.log( $li);
            });
        });

    </script>
@endsection
