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
    'backUrl' =>guard_route('fee-note.create', ['patient' => $patient]),
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
            {{-- <div>
                <a href="{{guard_route('chargecodeprices.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-dollar-sign"></i> Maintain Prices
                </a>
            </div> --}}
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
                                <th>Select</th>
                                <th>Id</th>
                                <th>Date</th>
                                <th>Charge Code</th>
                                <th>Gross</th>
                                <th>Net</th>
                                <th>Total</th>
                                <th>Qty</th>
                                <th width="100px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feeNotes as $note)
                            <tr>
                                <td><input type="checkbox" name="fee_notes[]" value="{{ $note->id }}"></td>
                                <td>{{ $note->id }}</td>
                                    <td>{{ format_date($note->procedure_date) }}</td>
                                    <td>{{ $note->chargecode->code ?? '' }}</td>
                                    <td>{{ $note->charge_gross }}</td>
                                    <td>{{ $note->charge_net }}</td>
                                    <td>{{ $note->line_total }}</td>
                                    <td>{{ $note->qty }}</td>
                                    <td class="text-end">
                                      <a href="{{guard_route('fee-note.edit', ['patient' => $patient, 'fee_note' => $note]) }}" 
                                         class="btn btn-sm btn-warning" 
                                         title="Edit Fee Note">
                                          <i class="fa fa-edit"></i>
                                      </a>
                                  
                                      {{-- <form action="{{guard_route('fee-note.destroy', ['patient' => $patient, 'fee_note' => $note]) }}" 
                                            method="POST" 
                                            style="display:inline;" 
                                            onsubmit="return confirm('Are you sure you want to delete this fee note?');">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit" 
                                                  class="btn btn-sm btn-danger" 
                                                  title="Delete Fee Note">
                                              <i class="fa fa-trash"></i>
                                          </button>
                                      </form> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-primary">Continue to Invoice</button>
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
     columnDefs: [
       {
         targets: 0, // column index for "Start Date" (0-based)
         orderable: false   // Disable sorting
       }
     ]
   });
</script>
@endpush