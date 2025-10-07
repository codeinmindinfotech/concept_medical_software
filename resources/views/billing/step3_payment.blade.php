@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
    ['label' => 'Charge Code List'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Charge Code List',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' =>guard_route('chargecodes.create'),
    'isListPage' => true
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
            <div id="FeeNoteTable" data-pagination-container>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fee Note</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notes as $note)
                        <tr>
                            <td>{{ $note->comment }}</td>
                            <td>${{ $note->line_total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <p><strong>Total:</strong> ${{ $total }}</p>
            <p><strong>Paid:</strong> ${{ $paid }}</p>
            <p><strong>Owing:</strong> ${{ $owing }}</p>

            <form method="POST" action="{{ route('payment.save', $patient) }}">
                @csrf
                <label>Payment Method:</label>
                <select class="form-control mb-3" name="method">
                    <option>Cash</option>
                    <option>Card</option>
                    <option>Online</option>
                </select>

                <button type="submit" class="btn btn-primary">Save & Print Final Receipt</button>
            </form>
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