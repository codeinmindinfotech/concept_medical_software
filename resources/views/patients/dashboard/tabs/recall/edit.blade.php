<form id="recallForm" action="{{ route('recalls.update', $recall->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Recall Date</label>
        <input type="date" name="recall_date" class="form-control" value="{{ $recall->recall_date }}" required>
    </div>
    <div class="mb-3">
        <label>Status</label>
        <input type="text" name="status" class="form-control" value="{{ $recall->status }}" required>
    </div>
    <div class="mb-3">
        <label>Note</label>
        <textarea name="note" class="form-control">{{ $recall->note }}</textarea>
    </div>
    <button class="btn btn-success">Update</button>
</form>
