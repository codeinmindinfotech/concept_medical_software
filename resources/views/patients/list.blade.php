<div class="table-responsive">
    <table class="table table-hover align-middle text-nowrap" id="PatientTable">
        <thead class="table-dark">
            <tr>
                <th style="width: 40px;">#</th>
                <th>Patient Name</th>
                <th>Address</th>
                <th style="width: 120px;">Date of Birth</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($patients as $index => $patient)
            <tr>
                <td>{{ $patients->firstItem() + $index }}</td>
                <td>
                    <div class="align-items-center gap-2 d-flex">
                    @if ($patient->patient_picture)
                        <img src="{{ asset('storage/' . $patient->patient_picture) }}"
                             alt="Patient Picture"
                             class="rounded-circle"
                             width="40" height="40">
                    @else
                        <div class="rounded-circle bg-secondary d-inline-block text-white text-center" style="width: 40px; height: 40px; line-height: 40px;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    @endif
                    {{ $patient->full_name }}
                    </div>
                </td>
                <td>{{ $patient->address ?? '-' }}</td>
                <td>{{ format_date($patient->dob) }}</td>
                <td>
                    <div class="d-flex gap-1 justify-content-center flex-wrap">
                        @can('view', $patient)
                        <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-sm btn-info" title="View Details">
                            <i class="fa-solid fa-eye text-white"></i>
                        </a>
                        <a href="{{ route('patients.notes.index', $patient->id) }}" class="btn btn-sm btn-success" title="Notes">
                            <i class="fa-solid fa-notes-medical"></i>
                        </a>
                        <a href="{{ route('patients.history.index', $patient->id) }}" class="btn btn-sm btn-warning" title="History">
                            <i class="fas fa-history text-white"></i>
                        </a>
                        <a href="{{ route('patients.physical.index', $patient->id) }}" class="btn btn-sm btn-secondary" title="Physical Exams">
                            <i class="fas fa-book-open"></i>
                        </a>
                        <a href="{{ route('patients.audio.index', $patient->id) }}" class="btn btn-sm btn-light" title="Consultation">
                            <i class="fas fa-microphone"></i>
                        </a>
                        <a href="{{ route('tasks.tasks.index', ['patient' => $patient]) }}" class="btn btn-sm btn-info" title="Tasks">
                            <i class="fas fa-tasks text-white"></i>
                        </a>
                        <a href="{{ route('recalls.recalls.index', ['patient' => $patient]) }}" class="btn btn-sm btn-success" title="Recalls">
                            <i class="fas fa-bell"></i>
                        </a>
                        <a href="{{ route('waiting-lists.index', ['patient' => $patient]) }}" class="btn btn-sm btn-warning" title="Waiting Lists">
                            <i class="fas fa-notes-medical text-white"></i>
                        </a>
                        <a href="{{ route('fee-notes.index', ['patient' => $patient]) }}" class="btn btn-sm btn-secondary" title="Fee Notes">
                            <i class="fas fa-money-check-alt"></i>
                        </a>
                        <a href="{{ route('sms.index', ['patient' => $patient]) }}" class="btn btn-sm btn-danger" title="SMS">
                            <i class="fas fa-sms"></i>
                        </a>
                        <a href="{{ route('communications.index', ['patient' => $patient]) }}" class="btn btn-sm btn-dark" title="Communications">
                            <i class="fas fa-comments"></i>
                        </a>
                        <a href="{{ route('patients.appointments.schedule', ['patient' => $patient]) }}" class="btn btn-sm btn-dark" title="Communications">
                            <i class="fas fa-calender"></i>
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
                <td colspan="5" class="text-center">No patients found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{!! $patients->appends(request()->query())->links('pagination::bootstrap-5') !!}
