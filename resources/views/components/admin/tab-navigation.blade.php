@props(['patient'])

@php
    $route = request()->route()->getName();
@endphp
<div class="col-12 col-md-2">
    <div class="nav flex-column nav-pills position-sticky" id="tab-nav"
        style="z-index: 989;top: 60px;"
        role="tablist" aria-orientation="vertical">
            <div class="card shadow-sm">
                <div class="card-header py-2 px-3 bg-light border-bottom">
                    <strong class="text-dark"><i class="fas fa-user-md me-2"></i> Patient Actions</strong>
                </div>

                <div class="card-body px-1 py-1">
                    <div class="d-grid gap-1">

                        @can('view', $patient)
                            <a href="{{ guard_route('patients.show', $patient->id) }}" class="{{ btnClass('patients.show', 'info') }}">
                                <i class="fa-solid fa-eye me-2"></i> View
                            </a>
                        @endcan

                        @can('update', $patient)
                            <a href="{{ guard_route('patients.edit', $patient->id) }}" class="{{ btnClass('patients.edit', 'primary') }}">
                                <i class="fa-solid fa-pen-to-square me-2"></i> Edit
                            </a>

                            <a href="{{ guard_route('patients.notes.index', $patient->id) }}" class="{{ btnClass('patients.notes.*', 'success') }}">
                                <i class="fa-solid fa-notes-medical me-2"></i> Notes 
                                <span class="badge bg-success  border border-white">
                                    {{ $patients->notes_count?? 0 }}
                                </span>
                            </a>

                            <a href="{{ guard_route('patients.history.index', $patient->id) }}" class="{{ btnClass('patients.history.*', 'warning') }}">
                                <i class="fas fa-history me-2"></i> History
                                <span class="badge bg-warning  border border-white">
                                    {{ $patients->histories_count??0 }}
                                </span>
                            </a>

                            <a href="{{ guard_route('patients.physical.index', $patient->id) }}" class="{{ btnClass('patients.physical.*', 'secondary') }}">
                                <i class="fas fa-book-open me-2"></i> Physical Exams
                            </a>

                            <a href="{{ guard_route('patients.audio.index', $patient->id) }}" class="{{ btnClass('patients.audio.*', 'dark') }}">
                                <i class="fas fa-microphone me-2"></i> Audio Consult
                            </a>

                            <a href="{{ guard_route('tasks.index', ['patient' => $patient]) }}" class="{{ btnClass('tasks.*', 'info') }}">
                                <i class="fas fa-tasks me-2"></i> Tasks 
                                <span class="badge bg-info border border-white ">
                                    {{ $patients->tasks_count??0 }}
                                </span>
                            </a>

                            <a href="{{ guard_route('recalls.index', ['patient' => $patient]) }}" class="{{ btnClass('recalls.*', 'success') }}">
                                <i class="fas fa-bell me-2"></i> Recalls 
                                <span class="badge bg-success  border border-white">
                                    {{ $patients->recall_count??0 }}
                                </span>
                            </a>

                            <a href="{{ guard_route('waiting-lists.index', ['patient' => $patient]) }}" class="{{ btnClass('waiting-lists.*', 'warning') }}">
                                <i class="fas fa-notes-medical me-2"></i> Waiting List
                                <span class="badge bg-warning  border border-white">
                                    {{ $patients->waiting_lists_count??0 }}
                                </span>
                            </a>

                            <a href="{{ guard_route('fee-notes.index', ['patient' => $patient]) }}" class="{{ btnClass('fee-notes.*', 'secondary') }}">
                                <i class="fas fa-money-check-alt me-2"></i> Fee Notes
                                <span class="badge bg-secondary border border-white ">
                                    {{ $patients->fee_note_list_count??0 }}
                                </span>
                            </a>

                            <a href="{{ guard_route('sms.index', ['patient' => $patient]) }}" class="{{ btnClass('sms.*', 'danger') }}">
                                <i class="fas fa-sms me-2"></i> SMS
                            </a>

                            <a href="{{ guard_route('communications.index', ['patient' => $patient]) }}" class="{{ btnClass('communications.*', 'dark') }}">
                                <i class="fas fa-comments me-2"></i> Communications
                            </a>

                            <a href="{{ guard_route('apts.index', ['patient' => $patient]) }}" class="{{ btnClass('apts.*', 'primary') }}">
                                <i class="fas fa-comments me-2"></i> Apt/Surgery
                                <span class="badge bg-primary  border border-white">
                                    {{ $patients->appointments_count??0 }}
                                </span>
                            </a>

                            <a href="{{ guard_route('patient-documents.index', ['patient' => $patient]) }}" class="{{ btnClass('patient-documents.*', 'warning') }}">
                                <i class="fas fa-file-alt me-2"></i> Documents
                                <span class="badge bg-warning  border border-white">
                                    {{ $patients->documents_count??0}}
                                </span>
                            </a>

                            <a href="{{ guard_route('patients.appointments.schedule', ['patient' => $patient]) }}" class="{{ btnClass('patients.appointments.*', 'primary') }}">
                                <i class="fas fa-calendar-check me-2"></i> Appointments 
                                <span class="badge bg-primary  border border-white">
                                    {{ $patients->appointments_count??0 }}
                                </span>
                            </a>
                        @endcan

                        @can('delete', $patient)
                            <form action="{{ guard_route('patients.destroy', $patient->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="w-100">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="{{ btnClass('patients.destroy', 'danger') }}">
                                    <i class="fa-solid fa-trash me-2"></i> Delete
                                </button>
                            </form>
                        @endcan

                    </div>
                </div>
            </div>
    </div>
</div>            