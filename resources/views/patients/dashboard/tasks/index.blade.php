@extends('backend.theme.tabbed')

@section('tab-navigation')
@include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
<div class="tab-pane fade show active" id="tasks" role="tabpanel">
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-user-clock me-2"></i> Task Management
            </h5>
            <a href="{{guard_route('tasks.tasks.create', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
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
                            <th width="150px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                        <tr>
                            <td>{{ $task->subject }}</td>
                            <td>{{ $task->creator->name ?? 'N/A' }}</td>
                            <td>{{ $task->owner->name ?? 'N/A' }}</td>
                            <td>{{ $task->category->value ?? 'N/A' }}</td>
                            <td>{{ ucfirst($task->priority) }}</td>
                            <td>{{ $task->status->value ?? 'N/A' }}</td>
                            <td>{{ format_date($task->start_date) }}</td>
                            <td>{{ format_date($task->end_date) }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{guard_route('tasks.tasks.edit', ['patient' => $patient, 'task' => $task->id]) }}" class="btn btn-sm btn-warning">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{guard_route('tasks.tasks.destroy', ['patient' => $patient, 'task' => $task->id]) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure to delete this Tasks?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-success open-add-followup" data-task-id="{{ $task->id }}" data-task-subject="{{ $task->subject }}" data-bs-toggle="modal" data-bs-target="#addFollowupModal">
                                        <i class="fa fa-plus"></i>
                                    </button>


                                    @if($task->followups->count())
                                    <button class="btn btn-info btn-sm toggle-followups" data-task-id="{{ $task->id }}">
                                        <i class="fa fa-comments"></i>
                                    </button>
                                    @endif
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

    <!-- Add Follow-up Modal -->
    <div class="modal fade" id="addFollowupModal" tabindex="-1" aria-labelledby="addFollowupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="addFollowupForm" method="POST" action="" class="validate-form">
                @csrf
                <input type="hidden" name="_method" value="POST" id="followupFormMethod">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addFollowupModalLabel">Add Follow-up</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="followup_date">Follow-up Date</label>
                            <div class="input-group">
                                <input id="followup_date" name="followup_date" type="text" class="form-control flatpickr @error('followup_date') is-invalid @enderror" placeholder="YYYY-MM-DD" value="{{ old('followup_date') }}">
                                <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                                @error('followup_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="note">Note</label>
                            <textarea name="note" id="note" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Save Follow-up</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@php
$tasksFollowups = $tasks->mapWithKeys(function($task) use ($patient) {
return [
$task->id => $task->followups->map(function($followup) use ($patient, $task) {
return [
'id' => $followup->id,
'date' => \Carbon\Carbon::parse($followup->followup_date)->format('Y-m-d'),
'note' => $followup->note,
'editUrl' =>guard_route('followups.storeOrUpdate', [$patient->id, $task->id, $followup->id]),
'deleteUrl' =>guard_route('followups.destroy', [$patient->id, $task->id, $followup->id]),
];
})->toArray()
];
});
@endphp


@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>

<script>
    var tasksFollowups = @json($tasksFollowups);
    $(document).ready(function() {

        var table = $('#TaskTable').DataTable({
            paging: true
            , searching: true
            , ordering: true
            , info: true
            , lengthChange: true
            , pageLength: 10
            , columnDefs: [{
                targets: 8
                , orderable: false
            }]
        });

        // Toggle child rows for follow-ups
        $('#TaskTable tbody').on('click', 'button.toggle-followups', function() {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            var taskId = $(this).data('task-id');

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                var followups = tasksFollowups[taskId] || [];
                var html = '<ul class="mb-0 ps-3 list-unstyled">';
                if (followups.length === 0) {
                    html += '<li>No follow-ups found.</li>';
                } else {
                    followups.forEach(function(fup) {
                        var deleteUrl = fup.deleteUrl.replace('FOLLOWUP_ID', fup.id);
                        var editBtn = `
        <button type="button" class="btn btn-sm btn-outline-primary edit-followup me-2"
            data-task-id="${taskId}"
            data-followup-id="${fup.id}"
            data-followup-date="${fup.date}"
            data-note="${fup.note}"
            data-edit-url="${fup.editUrl}"
            data-bs-toggle="modal"
            data-bs-target="#addFollowupModal">
            <i class="fa fa-edit"></i>
        </button>`;

                        var deleteForm = `
        <form action="${deleteUrl}" method="POST" class="d-inline" onsubmit="return confirm('Delete this follow-up?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
        </form>`;

                        html += `
        <div class="card mb-2 border shadow-sm">
            <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-bold">${fup.date}</div>
                    <div class="text-muted small">${fup.note}</div>
                </div>
                <div class="d-flex align-items-center">
                    ${editBtn}
                    ${deleteForm}
                </div>
            </div>
        </div>`;
                    });

                }
                html += '</ul>';

                row.child(html).show();
                tr.addClass('shown');
            }
        });

        // Add new follow-up
        $('.open-add-followup').on('click', function() {
            $('#addFollowupModalLabel').text('Add Follow-up');
            $('#addFollowupForm').attr('action', '');
            $('#followupFormMethod').val('POST');
            $('#addFollowupForm')[0].reset();

            const taskId = $(this).data('task-id');

            const actionUrl = "{{ url('patients/'.$patient->id.'/tasks') }}/" + taskId + "/followups";
            $('#addFollowupForm').attr('action', actionUrl);
        });

        // Edit existing follow-up
        $(document).on('click', '.edit-followup', function() {
            const taskId = $(this).data('task-id');
            const followupId = $(this).data('followup-id');
            const note = $(this).data('note');
            const date = $(this).data('followup-date');
            const editUrl = $(this).data('edit-url');

            $('#addFollowupModalLabel').text('Edit Follow-up');
            $('#followup_date').val(date);
            $('#note').val(note);
            $('#addFollowupForm').attr('action', editUrl);
            $('#followupFormMethod').val('POST');
        });
    });

</script>
@endpush
