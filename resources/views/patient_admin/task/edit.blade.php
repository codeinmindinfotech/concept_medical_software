@extends('layout.mainlayout')

@section('content')

@component('components.admin.breadcrumb')
@slot('title') Edit Patient @endslot
@slot('li_1') Patients @endslot
@slot('li_2') Edit @endslot
@endcomponent

<div class="content">
    <div class="container">

        <div class="row">
            <div class="col-lg-9 col-xl-10">

                <h3 class="mb-4">Edit Task</h3>
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{guard_route('tasks.update', ['patient' => $task->patient_id, 'task' => $task->id]) }}" method="POST" data-ajax class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="patient_id" value="{{ $task->patient_id }}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="task_creator_id" class="form-label">Task Creator</label>
                            <select name="task_creator_id" class="form-control" required>
                                <option value="">-- Select Creator --</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $task->task_creator_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="task_owner_id" class="form-label">Task Owner</label>
                            <select name="task_owner_id" class="form-control" required>
                                <option value="">-- Select Owner --</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $task->task_owner_id == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">-- Select Category --</option>
                                @foreach($taskCategories as $id => $value)
                                <option value="{{ $id }}" {{ $task->category_id == $id ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Repeat for other fields similarly, filling values from $task -->
                        <div class="col-md-6 mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="{{ $task->subject }}" required>
                        </div>

                        <!-- Continue for priority, status, start_date, end_date, task description -->

                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select name="priority" class="form-control">
                                <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status_id" class="form-label">Status</label>
                            <select name="status_id" class="form-control" required>
                                <option value="">-- Select Status --</option>
                                @foreach($statuses as $id => $val)
                                <option value="{{ $id }}" {{ $task->status_id == $id ? 'selected' : '' }}>
                                    {{ $val }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <div class="cal-icon">
                                <input id="start_date" name="start_date" type="text" class="form-control datetimepicker" value="{{ $task->start_date }}" placeholder="YYYY-MM-DD">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <div class="cal-icon">
                                <input id="end_date" name="end_date" type="text" class="form-control datetimepicker" value="{{ $task->end_date }}" placeholder="YYYY-MM-DD">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="task" class="form-label">Task Description</label>
                            <textarea name="task" class="form-control" rows="3" required>{{ $task->task }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Task</button>
                    <a href="{{guard_route('tasks.index', ['patient' => $patient->id]) }}" class="btn btn-secondary">Cancel</a>
                </form>

            </div>
            <!-- Profile Sidebar -->
            @component('components.admin.tab-navigation', ['patient' => $patient])
            @endcomponent
        </div>

    </div>
</div>
@endsection