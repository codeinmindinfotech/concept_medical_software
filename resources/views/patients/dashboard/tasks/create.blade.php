@extends('backend.theme.tabbed')

@section('tab-navigation')
    @include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
    <div class="tab-pane fade show active" id="tasks" role="tabpanel" aria-labelledby="tab-tasks">
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center  ">
                <h5 class="mb-0">
                    <i class="fas fa-user-clock me-2"></i> Task Management
                </h5>
                <a href="{{guard_route('tasks.tasks.index', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
                    <i class="fas fa-plus-circle me-1"></i> Task List
                </a>
            </div>
            <div class="card-body">
        <form action="{{guard_route('tasks.tasks.store', ['patient' => $patient->id]) }}" class="validate-form" method="POST">
            @csrf
            <input type="hidden" name="patient_id" value="{{ $patient->id }}">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="task_creator_id" class="form-label">Task Creator</label>
                    <select name="task_creator_id" id="task_creator_id" class="form-control" required>
                        <option value="">-- Select Creator --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="task_owner_id" class="form-label">Task Owner</label>
                    <select name="task_owner_id" id="task_owner_id" class="form-control" required>
                        <option value="">-- Select Owner --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="">-- Select Category --</option>
                        @foreach($taskcategories as $id => $value)
                            <option value="{{ $id }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="priority" class="form-label">Priority</label>
                    <select name="priority" id="priority" class="form-control">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status_id" class="form-label">Status</label>
                    <select name="status_id" id="status_id" class="form-control" required>
                        <option value="">-- Select Status --</option>
                        @foreach($statuses as $id => $val)
                            <option value="{{ $id }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <div class="input-group">
                        <input id="start_date" name="start_date" type="text"
                               class="form-control flatpickr @error('start_date') is-invalid @enderror"
                               placeholder="YYYY-MM-DD" value="{{ old('start_date') }}">
                        <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                        @error('start_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <div class="input-group">
                        <input id="end_date" name="end_date" type="text"
                               class="form-control flatpickr @error('end_date') is-invalid @enderror"
                               placeholder="YYYY-MM-DD" value="{{ old('end_date') }}">
                        <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                        @error('end_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <label for="task" class="form-label">Task Description</label>
                    <textarea name="task" id="task" class="form-control" rows="3" required>{{ old('task') }}</textarea>
                </div>
            </div>
            <a href="{{guard_route('tasks.tasks.index', ['patient' => $patient->id]) }}" class="btn btn-secondary">Cancel</a>
    
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Task</button>
        </form>
    </div>
</div>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush