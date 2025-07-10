<!-- resources/views/waiting_lists/list.blade.php -->
<div class="card shadow-sm mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="card-title mb-0">Waiting Lists</h4>
    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addVisitModal">
      <i class="fas fa-plus"></i> Add Waiting
    </button>
  </div>
  <div class="card-body" data-pagination-container>
    <table class="table table-hover table-bordered data-table align-middle mb-0"  >
      <thead class="table-light" >
        <tr>
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
            <td>{{ format_date($visit->visit_date) }}</td>
            <td>
              {{ $visit->clinic->code }}
            </td>
            <td>{{ $visit->category->value }}</td>
            <td>
              {{ $visit->consult_note??'' }}
            </td>
            <td class="text-end">
              <div class="d-inline-flex gap-2">
                <button class="btn btn-outline-primary btn-sm edit-btn" data-id="{{ $visit->id }}">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-outline-danger btn-sm delete-btn" data-id="{{ $visit->id }}">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">No visits found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    {!! $waitingLists->appends(request()->query())->links('pagination::bootstrap-5') !!}


  </div>
</div>

@include('patients.waiting_lists.form', [
  'patient' => $patient,
  'clinics' => $clinics,
  'categories' => $categories
])
