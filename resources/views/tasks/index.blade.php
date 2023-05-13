@extends('layouts.app')

@section('content')
@php
    $csrfToken = csrf_token();
@endphp

    @auth
    <div class="container" id="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h1>{{ __('Tasks List') }}</h1></div>

                    <div class="card-body">
                        
                        <div>
                            <form method="GET" id="filterForm">
                                <label for="filter">Filter:</label>
                                <select id="filter" name="filter">
                                    <option value="all" @if ($filter == 'all') selected @endif>All</option>
                                    <option value="done" @if ($filter == 'done') selected @endif>Done</option>
                                    <option value="undone" @if ($filter == 'undone') selected @endif>Undone</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                            <form method="GET">
                                <label for="search">Search:</label>
                                <input type="text" id="search" name="search" value="{{ $search }}">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                        <div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Deadline</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    @foreach ($tasks as $task)
                                        <tr>
                                            <td>{{ $task->title }}</td>
                                            <td>{{ $task->deadline }}</td>
                                            <td>
                                                <form method="POST" id="updateTaskForm{{ $task->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="done" value="{{ $task->done ? 0 : 1 }}">
                                                    <button id="updateTaskButton{{ $task->id }}" type="button" class="btn btn-primary">
                                                        {{ $task->done ? 'Done' : 'Undone' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary" href="{{ route('tasks.edit', $task->id) }}">Edit</a>
                                                <button class="btn btn-primary" id="show-details{{ $task->id }}" data-task-id="{{ $task->id }}">Show details</button>
                                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                                 
                                            </td>
                                            <td>
                                                <div class="details-container"></div>
                                            </td>
                                        </tr>
                                        <script>
                                            $(document).ready(function() {
                                                $('#show-details{{ $task->id }}').click(function(event) {
                                                    event.preventDefault();
                                                    var taskId = $(this).data('task-id');
                                                    var detailsContainer = $(this).closest('tr').find('.details-container');
                                                    $.ajax({
                                                        url: "/tasklist/public/tasks/show/" + taskId,
                                                        dataType: 'json',
                                                        success: function(data) {
                                                            console.log(data);
                                                            //alert(data);
                                                            var task = data.task;
                                                            var html = '<h3> Szczegóły </h3>';
                                                            html += '<p>' + task.description + '</p>';
                                                            detailsContainer.html(html);
                                                            detailsContainer.show();
                                                        },
                                                        error: function() {
                                                            alert('An error occurred while processing your request.');
                                                        }
                                                    });
                                                });
                                            });
                                        </script>
                                        <script>
                                            $(document).ready(function() {
                                                $('button[id^="updateTaskButton"]').click(function(event) {
                                                    event.preventDefault();
                                                    var button = $(this);
                                                    var form = button.closest('form');
                                                    var doneValue = form.find('input[name="done"]').val();
                                                    var taskId = form.attr('id').replace('updateTaskForm', '');

                                                    $.ajax({
                                                        url: "/tasklist/public/tasks/" + taskId + "/updatestatus",
                                                        method: "POST",
                                                        headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                                            'X-HTTP-Method-Override': 'PUT'
                                                        },
                                                        data: form.serialize(),
                                                        success: function(response) {
                                                            var newStatus = response.status;
                                                            button.text(newStatus ? 'Done' : 'Undone');
                                                            button.val(newStatus);
                                                            form.find('input[name="done"]').val(newStatus ? 1 : 0);
                                                        },
                                                        error: function() {
                                                            alert('An error occurred while processing your request.');
                                                        }
                                                    });
                                                });
                                            });
                                        </script>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <a class="btn btn-primary" href="{{ route('tasks.create') }}">Create a new task</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
                    <script>
                        $(document).ready(function() {
                            $('#search').change(function(event) {
                                event.preventDefault();
                                var search = $('#search').val();
                                $.ajax({
                                    url: "{{ route('tasks.index') }}",
                                    data: {
                                        search: search
                                    },
                                    success: function(data) {
                                        console.log(data);
                                      // $('tbody').html(data);
                                    },
                                    error: function() {
                                        alert('An error occurred while processing your request.');
                                    }
                                });
                            });
                        });
                    </script>
                    <script>
                        $(document).ready(function() {
                            $('#filterForm').on('submit', function(event) {
                                event.preventDefault();
                                var filter = $('#filter').val();
                                $.ajax({
                                    url: "{{ route('tasks.filter') }}",
                                    data: {
                                        filter: filter
                                    },
                                    success: function(data) {
                                       //$('#taskTable tbody').html(data);
                                        //$('#tbody').html(data);
                                        $('#app').html(data);
                                        //console.log(data);
                                    },
                                    error: function() {
                                        alert('An error occurred while processing your request.');
                                    }
                                });
                            });
                        });
                    </script>
    </div>
    @endauth

    @guest
            Before proceeding, please check your email for a verification link. If you did not receive the email,
            <form action="{{ route('verification.resend') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="d-inline btn btn-link p-0">
                    click here to request another
                </button>.
            </form>
    @endguest

@endsection