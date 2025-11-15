@extends('layout.tabbed')

@section('tab-navigation')
@include('layout.partials.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
@php
$breadcrumbs = [
['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
// ['label' => 'Patients', 'url' =>guard_route('recalls.create', $patient)],
['label' => 'Create Recall Management'],
];
@endphp

@include('layout.partials.breadcrumb', [
'pageTitle' => 'Create Recall Management',
'breadcrumbs' => $breadcrumbs,
'backUrl' =>guard_route('recalls.create', $patient),
'isListPage' => true
])

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="table-responsive">
    <table class="table table-hover table-center mb-0" id="RecallTable">
        <thead>
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
                    <a href="{{guard_route('recalls.edit', ['patient' => $patient, 'recall' => $recall]) }}" class="btn btn-sm btn-warning">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form action="{{guard_route('recalls.destroy', ['patient' => $patient, 'recall' => $recall]) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this task?');">
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

