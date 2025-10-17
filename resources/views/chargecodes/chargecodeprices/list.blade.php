<table class="table table-bordered data-table" id="ChargeCodePriceTable">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Code</th>
            <th>Address</th>
            <th>Contact</th>
            <th>Contact Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($insurances as $i => $insurance)
        <tr style="cursor:pointer;" onmouseover="this.style.backgroundColor='#f0f8ff'"
            onmouseout="this.style.backgroundColor=''"
            onclick="window.location='{{guard_route('chargecodeprices.adjust-prices', $insurance->id) }}'">
            <td>{{ ++$i }}</td>
            <td>{{ $insurance->code }}</td>
            <td>{{ $insurance->address }}</td>
            <td>{{ $insurance->contact_name ?? '-' }}</td>
            <td>{{ $insurance->contact }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
{{-- {!! $insurances->appends(request()->query())->links('pagination::bootstrap-5') !!} --}}