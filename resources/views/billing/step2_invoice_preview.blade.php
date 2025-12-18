@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
    ['label' => 'ChargeCodees', 'url' =>guard_route('chargecodes.index')],
    ['label' => 'Create ChargeCodee'],
    ];
    @endphp

    @include('layout.partials.breadcrumb', [
    'pageTitle' => 'Create ChargeCodee',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' =>guard_route('fee-note.create', ['patient' => $patient]),
    'isListPage' => false
    ])
    @session('success')
    <div class="alert alert-success" role="alert">
        {{ $value }}
    </div>
    @endsession

    @php
    $hasFilters = request()->hasAny(['search']);
    @endphp
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i> Charge Codes Management
            </div>
            <div>
                <a href="{{guard_route('chargecodeprices.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-dollar-sign"></i> Maintain Prices
                </a>
            </div>
        </div>

        <div class="card-body ">
            <form method="POST" action="{{ route('fee-notes.save', $patient) }}">
                @csrf

                <label>Bill To:</label>
                <select name="bill_to" class="form-control mb-3" required>
                    <option value="insurance">Insurance Co</option>
                    <option value="direct">Patient Direct</option>
                    <option value="solicitor">Solicitor</option>
                    <option value="third_party">Third Party</option>
                </select>
                <div id="FeeNoteTable" data-pagination-container>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Comment</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notes as $note)
                            <tr>
                                <td>{{ $note->id }}</td>
                                <td>{{ $note->comment }}</td>
                                <td>{{ $note->line_total }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form> 
                <p><strong>Invoice Total: </strong> ${{ $total }}</p>

                <form method="POST" action="{{ route('invoice.submit', $patient) }}">
                    @csrf
                    <label>Payment Amount:</label>
                    <input type="number" name="payment" class="form-control" step="0.01" max="{{ $total }}" required>
                
                    <button type="submit" class="btn btn-success mt-3">Save Transaction & Continue</button>
                </form>
        </div>
    </div>
</div>
</div>
@endsection
@push('scripts')
<script>
    $('#FeeNoteTable').DataTable({
     paging: true,
     searching: true,
     ordering: true,
     info: true,
     lengthChange: true,
     pageLength: 10,
    //  columnDefs: [
    //    {
    //      targets: 6, // column index for "Start Date" (0-based)
    //      orderable: false   // Disable sorting
    //    }
    //  ]
   });
</script>
@endpush