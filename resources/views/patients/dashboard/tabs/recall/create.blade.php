<form id="recallForm" action="{{ route('recalls.store') }}" method="POST">
  @csrf
  <div class="mb-3">
      <label>Recall Date</label>
      <input type="date" name="recall_date" class="form-control" required>
  </div>
  <div class="mb-3">
      <label>Status</label>
      <input type="text" name="status" class="form-control" required>
  </div>
  <div class="mb-3">
      <label>Note</label>
      <textarea name="note" class="form-control"></textarea>
  </div>
  <button class="btn btn-success">Save</button>
</form>
