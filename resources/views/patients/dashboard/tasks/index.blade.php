@extends('backend.theme.tabbed')

@section('tab-navigation')
@include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
<div class="tab-pane fade show active" id="tasks" role="tabpanel">
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center  ">
            <h5 class="mb-0">
                <i class="fas fa-user-clock me-2"></i> Task Management
            </h5>
            <a href="{{ route('tasks.tasks.create', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
                <i class="fas fa-plus-circle me-1"></i> New Task
            </a>
        </div>
        <div class="card-body">


            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0" id="TaskTable">

                    <thead class="table-dark">
                        <tr>
                            <th>Subject</th>
                            <th>Creator</th>
                            <th>Owner</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th width="100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                        <tr>
                            <td>{{ $task->subject }}</td>
                            <td>{{ $task->creator->name ?? 'N/A' }}</td>
                            <td>{{ $task->owner->name ?? 'N/A' }}</td>
                            <td>{{ $task->category->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($task->priority) }}</td>
                            <td>{{ $task->status->value ?? 'N/A' }}</td>
                            <td>{{ $task->start_date }}</td>
                            <td>{{ $task->end_date }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('tasks.tasks.edit', ['patient' => $patient, 'task' => $task->id]) }}" class="btn btn-sm btn-warning">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('tasks.tasks.destroy',['patient' => $patient, 'task' => $task->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

<script>
    $('#TaskTable').DataTable({
        paging: true
        , searching: true
        , ordering: true
        , info: true
        , lengthChange: true
        , pageLength: 10
        , columnDefs: [{
            targets: 8, // column index for "Start Date" (0-based)
            orderable: false // Disable sorting
        }]
    });

</script>
@endpush
