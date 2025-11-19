@extends('layout.tabbed')

@section('tab-navigation')
@include('layout.partials.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
@php
$breadcrumbs = [
['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
['label' => 'Patients', 'url' =>guard_route('waiting-lists.create', $patient)],
['label' => 'Waiting List Management'],
];
@endphp

@include('layout.partials.breadcrumb', [
'pageTitle' => 'Waiting List Management',
'breadcrumbs' => $breadcrumbs,
'backUrl' =>guard_route('waiting-lists.create', $patient),
'isListPage' => true
])

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="table-responsive">
  <table class="table table-hover table-bordered data-table align-middle mb-0" id="WaitingTable">
      <thead>
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
          @foreach($waitingLists as $visit)
          <tr data-id="{{ $visit->id }}">
              <td>{{ $visit->id }}</td>
              <td>{{ format_date($visit->visit_date) }}</td>
              <td>{{ $visit->clinic->code ?? '-' }}</td>
              <td>{{ $visit->category->value ?? '-' }}</td>
              <td>{{ $visit->consult_note ?? '-' }}</td>
              <td >
                <a href="{{guard_route('waiting-lists.edit', ['patient' => $patient, 'waiting_list' => $visit->id]) }}" class="btn btn-sm bg-primary-light" title="Edit">
                  <i class="fa fa-edit"></i>
                </a>
                <form action="{{guard_route('waiting-lists.destroy',['patient' => $patient, 'waiting_list' => $visit->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');" style="display: inline;">
                  @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm bg-danger-light" title="Delete"><i class="fe fe-trash"></i> Delete</button>
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
    $('#WaitingTable').DataTable({
        paging: true
        , searching: true
        , ordering: true
        , info: true
        , lengthChange: true
        , pageLength: 10
        , columnDefs: [{
            targets: 5, // column index for "Start Date" (0-based)
            orderable: false // Disable sorting
        }]
    });

</script>
@endpush
