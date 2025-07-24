@extends('backend.theme.tabbed')

@section('tab-navigation')
    @include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
<div class="tab-pane fade show active" id="tasks" role="tabpanel">
  <div class="card mb-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center  ">
        <h5 class="mb-0">
            <i class="fas fa-user-clock me-2"></i> Waiting Management
        </h5>
        <a href="{{ route('waiting-lists.create', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
            <i class="fas fa-plus-circle me-1"></i> New Waiting
        </a>
    </div>
    <div class="card-body">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-hover table-bordered data-table align-middle mb-0" id="WaitingTable" >
      <thead class="table-dark" >
        <tr>
          <th>ID</th>
          <th>Date</th>
          <th>Clinic</th>
          <th>Category</th>
          <th>Consult Note</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($waitingLists as $visit)
          <tr data-id="{{ $visit->id }}">
            <td>{{ $visit->id }}</td>
            <td>{{ format_date($visit->visit_date) }}</td>
            <td>{{ $visit->clinic->code ?? '-' }}</td>
            <td>{{ $visit->category->value ?? '-' }}</td>
            <td>{{ $visit->consult_note ?? '-' }}</td>
            <td class="text-end">
                <a href="{{ route('waiting-lists.edit', ['patient' => $patient, 'waiting_list' => $visit->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('waiting-lists.destroy',['patient' => $patient, 'waiting_list' => $visit->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">No visits found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
$('#WaitingTable').DataTable({
     paging: true,
     searching: true,
     ordering: true,
     info: true,
     lengthChange: true,
     pageLength: 10,
     columnDefs: [
       {
         targets: 4, // column index for "Start Date" (0-based)
         orderable: false   // Disable sorting
       }
     ]
});
</script>
@endpush