@extends('backend.theme.tabbed')

@section('tab-navigation')
@include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
<div class="tab-pane fade show active" id="tasks" role="tabpanel">
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center  ">
            <h5 class="mb-0">
                <i class="fas fa-user-clock me-2"></i>Fee Note List
            </h5>
            <a href="{{ route('fee-notes.create', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
                <i class="fas fa-plus-circle me-1"></i> Add Fee Note
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div id="FeeNoteList">
                <div class="card shadow-sm mb-4">
                    <div class="card-body" id="FeeNoteListContainer">
                        @if($feeNotes->count())
                        <table class="table table-bordered" id="FeeNoteTable">
                            <thead class="table-dark">
                                <tr>
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
                                @forelse($feeNotes as $note)
                                <tr data-id="{{ $note->id }}">
                                    <td>{{ $note->id }}</td>
                                    <td>{{ format_date($note->procedure_date) }}</td>
                                    <td>{{ $note->chargecode->code ?? '' }}</td>
                                    <td>{{ $note->charge_gross }}</td>
                                    <td>{{ $note->charge_net }}</td>
                                    <td>{{ $note->line_total }}</td>
                                    <td>{{ $note->qty }}</td>
                                    <td class="text-end">
                                      <a href="{{ route('fee-notes.edit', ['patient' => $patient, 'fee_note' => $note]) }}" 
                                         class="btn btn-sm btn-warning" 
                                         title="Edit Fee Note">
                                          <i class="fa fa-edit"></i>
                                      </a>
                                  
                                      <form action="{{ route('fee-notes.destroy', ['patient' => $patient, 'fee_note' => $note]) }}" 
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
                                      </form>
                                  </td>
                                  
                                  
                                  
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No fee notes</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @else
                        <p>No Fee Notes found.</p>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $('#FeeNoteTable').DataTable({
        paging: true
        , searching: true
        , ordering: true
        , info: true
        , lengthChange: true
        , pageLength: 10
        , columnDefs: [{
            targets: 7, // column index for "Start Date" (0-based)
            orderable: false // Disable sorting
        }]
    });

</script>
@endpush

