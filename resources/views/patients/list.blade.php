<div class="table-responsive">
    <table class="table table-hover align-middle text-nowrap" id="PatientTable">
        <thead class="table-dark">
            <tr>
                <th style="width: 40px;">#</th>
                <th>Doctor</th>
                <th>Patient Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th style="width: 120px;">Date of Birth</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($patients as $index => $patient)
            <tr onclick="window.location='{{ guard_route('patients.edit', $patient->id) }}'" style="cursor: pointer;">

                <td>{{ $patients->firstItem() + $index }}</td>
                <td>{{ $patient->doctor?->name ?? '' }}</td>
                <td>
                    <div class="align-items-center gap-2 d-flex">
                        @if ($patient->patient_picture)
                        <img src="{{ asset('storage/' . $patient->patient_picture) }}" alt="Patient Picture" class="rounded-circle" width="40" height="40">
                        @else
                        <div class="rounded-circle bg-secondary d-inline-block text-white text-center" style="width: 40px; height: 40px; line-height: 40px;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        @endif
                        {{ $patient->full_name }}
                    </div>
                </td>
                <td>{{ $patient->address ?? '-' }}</td>
                <td>{{ $patient->phone ?? '' }}</td>
                <td>{{ format_date($patient->dob) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No patients found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{!! $patients->appends(request()->query())->links('pagination::bootstrap-5') !!}
