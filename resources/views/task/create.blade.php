@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $task->id ? 'Edit' : 'Create'}} Task</div>

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
                                <input type="text" class="form-control" id="title"
                                       value="{{ $task->title ?? '' }}"
                                       name="title" placeholder="Enter task title" autofocus required>
                            </div>

                            <div class="form-group">
                                <label for="text">Task</label>
                                <textarea class="form-control" id="text" rows="3" name="text" required>{{ $task->text ?? '' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="due_date">Due Date</label>
                                <div class="input-group date" id="due_date" class="datetimepicker">
                                    <input type='text' class="form-control" name="due_date"
                                           value="{{ $task->due_date ?
                                                    $task->due_date->format('m/d/Y H:i A') : '' }}"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="followers">Followers</label><br>
                                <select multiple class="form-control" id="followers" name="followers[]">
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}" data-mention-name="{{ $user->name }}">
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <br>
                            </div>

                            <div class="form-group">
                                <label for="assignees">Assignees</label><br>
                                <select multiple class="form-control" id="assignees" name="assignees[]">
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <br>
                            </div>

                            <div class="form-group">
                                <label for="priority">Priority</label>
                                <select id="priority" class="form-control" name="priority">
                                    <option value="high" {{ $task->priority === 'high' ? 'selected' : '' }}>High</option>
                                    <option value="normal" {{ $task->priority === 'normal' || !$task->priority ? 'selected' : '' }}>Normal</option>
                                    <option value="low" {{ $task->priority === 'low' ? 'selected' : '' }}>Low</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ $task->id ? 'Update' : 'Create'}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function () {
            // set up datepicker
            $('#due_date').datetimepicker({
                sideBySide: true
                });

            // hook up multi select
            $('#followers').multiselect();
            $('#assignees').multiselect();

            // hook up auto complete
            $('#text').atwho({
                at: "@",
                data:[ '<?php echo implode("','", $users->pluck('name')->toArray()); ?>' ]
            });

            // hook up select of a follower after auto complete
            $('#text').on("inserted.atwho", function(event, $li, query) {
                var userId = $("option[data-mention-name='" + $li[0].innerText.trim() + "']").val();
                $('#followers').multiselect('select', userId);
            });


            @if ($task->id)
              // init followers on load once for edits
              $('#followers').multiselect('select', [ '<?php echo implode("','", $task->followers->pluck('user_id')->toArray()); ?>' ]);
              $('#assignees').multiselect('select', [ '<?php echo implode("','", $task->assignments->pluck('user_id')->toArray()); ?>' ]);
            @endif
        });

    </script>
@endsection
