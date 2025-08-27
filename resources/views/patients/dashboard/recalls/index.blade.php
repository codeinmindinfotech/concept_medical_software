@extends('backend.theme.tabbed')

@section('tab-navigation')
@include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
<div class="tab-pane fade show active" id="tasks" role="tabpanel">
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center  ">
            <h5 class="mb-0">
                <i class="fas fa-user-clock me-2"></i> Recall Management
            </h5>
            <a href="{{guard_route('recalls.recalls.create', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
                <i class="fas fa-plus-circle me-1"></i> Add Recall
            </a>
        </div>
        <div class="card-body">

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered" id="RecallTable">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recalls as $recall)
                    <tr data-id="{{ $recall->id }}">
                        <td>{{ $recall->id }}</td>
                        <td>{{ format_date($recall->recall_date) }}</td>
                        <td>{{ $recall->note }}</td>
                        <td>{{ $recall->status?->value }}</td>
                        <td>
                            <a href="{{guard_route('recalls.recalls.edit', ['patient' => $patient, 'recall' => $recall]) }}" class="btn btn-sm btn-warning">
                              <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{guard_route('recalls.recalls.destroy', ['patient' => $patient, 'recall' => $recall]) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this task?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                  <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $('#RecallTable').DataTable({
        paging: true
        , searching: true
        , ordering: true
        , info: true
        , lengthChange: true
        , pageLength: 10
        , columnDefs: [{
            targets: 4, // column index for "Start Date" (0-based)
            orderable: false // Disable sorting
        }]
    });

</script>
@endpush
