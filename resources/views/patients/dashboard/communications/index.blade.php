@extends('backend.theme.tabbed')

@section('tab-navigation')
    @include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
<div class="tab-pane fade show active" id="tasks" role="tabpanel">
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center  ">
            <h5 class="mb-0">
                <i class="fas fa-user-clock me-2"></i> Communication Management
            </h5>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-hover table-bordered data-table align-middle mb-0" 
            data-route="{{guard_route('communications.received', ['communication' => '__ID__']) }}" id="CommunicationTable">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Method</th>
                        <th>Received</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($communications as $communication)
                    <tr data-id="{{ $communication->id }}">
                        <td>{{ $communication->id }}</td>
                        <td>{{ format_date($communication->created_at) }}</td>
                        <td>{{ $communication->message ?? '-' }}</td>
                        <td>{{ $communication->method ?? '-' }}</td>
                        <td><input type="checkbox" onchange="markAsReceived({{ $communication->id }}, this)" /></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    function markAsReceived(id, checkbox) {
        if (!checkbox.checked) return;

        const routeTemplate = document.getElementById('CommunicationTable').dataset.route;
        const route = routeTemplate.replace('__ID__', id);

        fetch(route, {
                method: 'POST'
                , headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    , 'Content-Type': 'application/json'
                , }
            })
            .then(response => {
                if (response.ok) {
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) row.remove();
                } else {
                    alert('Failed to update.');
                    checkbox.checked = false;
                }
            })
            .catch(() => {
                alert('Request failed.');
                checkbox.checked = false;
            });
    }

    $('#CommunicationTable').DataTable({
        paging: true
        , searching: true
        , ordering: true
        , info: true
        , lengthChange: true
        , pageLength: 10
    , });

</script>
@endpush
