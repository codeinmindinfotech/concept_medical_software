@if($recalls->count())
<table class="table table-bordered" id="RecallTable">
    <thead class="table-dark">
        <tr>
            <th>Id</th>
            <th>Date</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($recalls as $recall)
        <tr data-id="{{ $recall->id }}">
            <td>{{ $recall->id }}</td>
            <td>{{ \Carbon\Carbon::parse($recall->recall_date)->format('Y-m-d') }}</td>
            <td>{{ $recall->note }}</td>
            <td class="text-end">
                <button class="btn btn-outline-primary btn-sm editRecallBtn" data-recall='@json($recall)'>
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-outline-danger btn-sm deleteRecall" data-id="{{ $recall->id }}">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No recalls found.</p>
@endif
