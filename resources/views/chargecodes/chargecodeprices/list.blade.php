<table class="table table-bordered data-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Code</th>
            <th>Address</th>
            <th>Contact</th>
            <th>Contact Name</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($insurances as $i => $insurance)
        <tr style="cursor:pointer;" onmouseover="this.style.backgroundColor='#f0f8ff'"
            onmouseout="this.style.backgroundColor=''"
            onclick="window.location='{{ route('chargecodeprices.adjust-prices', $insurance->id) }}'">
            <td>{{ ++$i }}</td>
            <td>{{ $insurance->code }}</td>
            <td>{{ $insurance->address }}</td>
            <td>{{ $insurance->contact_name ?? '-' }}</td>
            <td>{{ $insurance->contact }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="3">There are no chargecodes.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{!! $insurances->appends(request()->query())->links('pagination::bootstrap-5') !!}