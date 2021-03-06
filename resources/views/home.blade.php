@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <a href="/task/create" class="btn btn-primary" role="button">Create Task</a>
                        <br><br>
                        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Task</th>
                                <th>Created by</th>
                                <th>Assigned to</th>
                                <th>Followed by</th>
                                <th>Start date</th>
                                <th>End Date</th>
                                <th>Due Date</th>
                                <th>Done by</th>
                                <th>Duration</th>
                                <th>Priority</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    <td>{{ $task->id }}</td>
                                    <td><a href="/task/{{ $task->id }}"
                                           style="{{ $task->end_date ?  'text-decoration: line-through;' : ''}}">
                                            {{ $task->title }}
                                        </a>
                                    </td>
                                    <td>{{ $task->user->name }}</td>
                                    <td>
                                        @foreach ($task->assignments as $assignment)
                                            {{ $assignment->user->name }},
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($task->followers as $follower)
                                            {{ $follower->user->name }},
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $task->start_date ? $task->start_date->format('m/d/Y') : '' }}
                                    </td>
                                    <td>
                                        {{ $task->end_date ? $task->end_date->format('m/d/Y') : '' }}
                                    </td>
                                    <td>
                                        {{ $task->due_date ? $task->due_date->format('m/d/Y') : '' }}
                                    </td>
                                    <td>
                                        {{ $task->completedUser ? $task->completedUser->name : '' }}
                                    </td>
                                    <td>
                                        @if ($task->start_date && $task->end_date)
                                            {{ $task->end_date->diffInMinutes($task->start_date) }} min
                                        @endif
                                    </td>
                                    <td>
                                        @if ($task->priority === 'high')
                                            <span class="label label-danger">High</span>
                                        @elseif ($task->priority === 'low')
                                            <span class="label label-warning">Low</span>
                                        @else
                                            <span class="label label-success">Normal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="/task/{{ $task->id }}/edit" class="btn btn-default btn-sm">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                        <form method="post" action="/task/{{ $task->id }}" style="display: inline">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "order": [[ 0, 'desc' ]]
            });
        } );
    </script>
@endsection
