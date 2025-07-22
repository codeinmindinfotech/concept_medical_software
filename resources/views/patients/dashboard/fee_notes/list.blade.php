<div id="FeeNoteList">
  <div class="card shadow-sm mb-4">
    <div class="card-body" id="FeeNoteListContainer">
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
            <th>Actions</th>
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
              <div class="d-inline-flex gap-2">
                <button class="btn btn-outline-primary btn-sm editFeeNoteBtn" data-note='@json($note)'>
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-outline-danger btn-sm deleteFeeNote" data-id="{{ $note->id }}">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center">No fee notes</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>