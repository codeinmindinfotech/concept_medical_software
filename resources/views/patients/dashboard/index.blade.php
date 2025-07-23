@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="nav flex-column nav-pills" id="sidebar-tabs" role="tablist" aria-orientation="vertical">
                <button class="nav-link {{ $activeTab == 'recalls' ? 'active' : '' }}" id="tab-recalls-tab" data-bs-toggle="pill" data-bs-target="#tab-recalls" type="button" role="tab">
                    <i class="fas fa-bell me-2"></i> Recalls
                </button>
                {{-- Add other tabs here --}}
            </div>
        </div>

        <!-- Tab Content -->
        <div class="col-md-9">
            <div class="tab-content" id="sidebar-tabs-content">
                <div class="tab-pane fade {{ $activeTab == 'recalls' ? 'show active' : '' }}" id="tab-recalls" role="tabpanel">

                    <!-- Add Recall Button -->
                    <button id="showRecallFormBtn" class="btn btn-success mb-3">Add Recall</button>

                    <!-- Inline Recall Form (hidden by default) -->
                    <div id="recallFormContainer" style="display:none; margin-bottom: 1rem;">
                        <form id="recallForm" class="card card-body shadow-sm">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                            <input type="hidden" name="recall_id" id="recall_id">

                            <div class="mb-3">
                                <label for="recall_date" class="form-label">Recall Date</label>
                                <input type="date" id="recall_date" name="recall_date" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">Note</label>
                                <textarea id="note" name="note" class="form-control" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Recall</button>
                            <button type="button" id="cancelRecallFormBtn" class="btn btn-secondary ms-2">Cancel</button>
                        </form>
                    </div>

                    <!-- Recalls List -->
                    <div id="RecallListContainer">
                        @include('patients.dashboard.tabs.recall.list', ['recalls' => $recalls])
                    </div>

                </div>

                {{-- Other tab contents --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const showRecallFormBtn = document.getElementById('showRecallFormBtn');
    const recallFormContainer = document.getElementById('recallFormContainer');
    const cancelRecallFormBtn = document.getElementById('cancelRecallFormBtn');
    const recallForm = document.getElementById('recallForm');
    const recallListContainer = document.getElementById('RecallListContainer');

    // Show form
    showRecallFormBtn.addEventListener('click', () => {
        recallForm.reset();
        recallForm.recall_id.value = '';
        recallFormContainer.style.display = 'block';
        showRecallFormBtn.style.display = 'none';
    });

    // Cancel form
    cancelRecallFormBtn.addEventListener('click', () => {
        recallForm.reset();
        recallFormContainer.style.display = 'none';
        showRecallFormBtn.style.display = 'inline-block';
    });

    // Edit recall button handler (event delegation)
    recallListContainer.addEventListener('click', function(e) {
        if (e.target.closest('.editRecallBtn')) {
            const btn = e.target.closest('.editRecallBtn');
            const recall = JSON.parse(btn.getAttribute('data-recall'));
            recallForm.recall_id.value = recall.id;
            recallForm.recall_date.value = recall.recall_date;
            recallForm.note.value = recall.note;
            recallFormContainer.style.display = 'block';
            showRecallFormBtn.style.display = 'none';
            window.scrollTo({top: recallFormContainer.offsetTop - 20, behavior: 'smooth'});
        }

        if (e.target.closest('.deleteRecall')) {
            if (!confirm('Are you sure you want to delete this recall?')) return;

            const id = e.target.closest('.deleteRecall').getAttribute('data-id');

            fetch("{{ route('patients.recall.delete') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ id })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    recallListContainer.innerHTML = data.html;
                } else {
                    alert('Failed to delete recall');
                }
            })
            .catch(() => alert('Error deleting recall'));
        }
    });

    // Submit form with AJAX
    recallForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("{{ route('patients.recall.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                recallListContainer.innerHTML = data.html;
                recallForm.reset();
                recallFormContainer.style.display = 'none';
                showRecallFormBtn.style.display = 'inline-block';
            } else {
                alert('Failed to save recall');
            }
        })
        .catch(() => alert('Error saving recall'));
    });
});
</script>
@endpush
