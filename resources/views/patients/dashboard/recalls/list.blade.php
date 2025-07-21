<div id="RecallList">
  <div class="card shadow-sm mb-4">
    <div class="card-body" id="RecallListContainer">
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
          @forelse($recalls as $recall)
          <tr data-id="{{ $recall->id }}">
            <td>{{ $recall->id }}</td>
            <td>{{ format_date($recall->recall_date) }}</td>
            <td>{{ $recall->note }}</td>
            <td>{{ $recall->status?->value }}</td>

            <td class="text-end">
              <div class="d-inline-flex gap-2">
                <button class="btn btn-outline-primary btn-sm editRecallBtn" data-recall='@json($recall)'>
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-outline-danger btn-sm deleteRecall" data-id="{{ $recall->id }}">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center">No recalls</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>