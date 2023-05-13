@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Add New Task</div>
                    <div class="panel-body">

                        <!-- New Task Form -->
                        <form action="{{ url('tasks/store') }}" method="POST" class="form-horizontal">
                            {{ csrf_field() }}

                            <!-- Task Title -->
                            <div class="form-group">
                                <label for="task-title" class="col-sm-3 control-label">Title</label>

                                <div class="col-sm-6">
                                    <input type="text" name="title" id="task-title" class="form-control" value="{{ old('title') }}">
                                </div>
                            </div>

                            <!-- Task Details -->
                            <div class="form-group">
                                <label for="task-details" class="col-sm-3 control-label">Details</label>

                                <div class="col-sm-6">
                                    <textarea name="description" id="task-details" class="form-control">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <!-- Task Deadline -->
                            <div class="form-group">
                                <label for="task-deadline" class="col-sm-3 control-label">Deadline</label>

                                <div class="col-sm-6">
                                    <input type="date" name="deadline" id="task-deadline" class="form-control" value="{{ old('deadline') }}">
                                </div>
                            </div>

                            <!-- Add Task Button -->
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-6">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fa fa-plus"></i> Add Task
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection