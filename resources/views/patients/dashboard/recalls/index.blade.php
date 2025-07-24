@extends('backend.theme.tabbed')

@section('tab-navigation')
    @include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
<div class="tab-pane fade show active" id="tasks" role="tabpanel">
    <a href="{{ route('recalls.recalls.create', $patient) }}" class="btn btn-primary mb-3">Create New Recall</a>

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
                <a href="{{ route('recalls.recalls.edit', ['patient' => $patient, 'recall' => $recall]) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('recalls.recalls.destroy', ['patient' => $patient, 'recall' => $recall]) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this task?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
          </tr>
          @endforeach
        </tbody>
    </table>
    
</div>
@endsection
@push('scripts')
<script>
$('#RecallTable').DataTable({
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