<div class="table-responsive" >
    <table class="table table-hover align-middle text-nowrap" id="PatientTable">
        <thead class="table-dark">
            <tr>
                <th style="width: 40px;">#</th>
                <th>Patient Name</th>
                <th>Address</th>
                <th style="width: 120px;">Date of Birth</th>
                <th>Insurance</th>
                <th style="width: 170px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($patients as $index => $patient)
            <tr>
                <td>{{ $patients->firstItem() + $index }}</td>
                <td>
                    {{ optional($patient->title)->value ? $patient->title->value . ' ' : '' }}
                    {{ $patient->first_name }} {{ $patient->surname }}
                </td>
                <td>{{ $patient->address ?? '-' }}</td>
                <td>{{ format_date($patient->dob) }}</td>
                <td>{{ optional($patient->insurance)->code ?? '-' }}</td>
                <td>
                    <div class="d-flex gap-1 justify-content-center flex-wrap">
                        @can('view', $patient)
                        <a href="{{ route('patients.patient_dashboard', $patient->id) }}" class="btn btn-sm btn-dark" title="Dashboard">
                            <i class="fas fa-tachometer-alt"></i>
                        </a>
                        <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-sm btn-info" title="View Details">
                            <i class="fa-solid fa-eye text-white"></i>
                        </a>
                        <a href="{{ route('patients.notes.index', $patient->id) }}" class="btn btn-sm btn-success" title="Notes">
                            <i class="fa-solid fa-notes-medical"></i>
                        </a>
                        <a href="{{ route('patients.history.index', $patient->id) }}" class="btn btn-sm btn-warning" title="History">
                            <i class="fas fa-history"></i>
                        </a>
                        <a href="{{ route('patients.physical.index', $patient->id) }}" class="btn btn-sm btn-secondary" title="Physical Exams">
                            <i class="fas fa-book-open"></i>
                        </a>
                        <a href="{{ route('patients.audio.create', $patient->id) }}" class="btn btn-sm btn-secondary" title="Physical Exams">
                            <i class="fas fa-microphone"></i>	
                        </a>
                        @endcan

                        @can('update', $patient)
                        <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-primary" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        @endcan

                        @can('delete', $patient)
                        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure to delete this patient?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No patients found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{!! $patients->appends(request()->query())->links('pagination::bootstrap-5') !!}
