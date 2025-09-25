@props(['patient'])

@php
    $route = request()->route()->getName();
@endphp

<div class="card shadow-sm">
    <div class="card-header py-2 px-3 bg-light border-bottom">
        <strong class="text-dark"><i class="fas fa-user-md me-2"></i>Patient Actions</strong>
    </div>

    <div class="card-body px-3 py-3">
        <div class="d-grid gap-2">

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
                </a>
                
                <a href="{{ guard_route('patients.upload-picture-form', $patient->id) }}" class="{{ btnClass('upload-picture.*', 'primary') }}">
                  <i class="fas fa-camera me-2"></i> Upload Picture
                </a>

                <a href="{{ guard_route('patients.history.index', $patient->id) }}" class="{{ btnClass('patients.history.*', 'warning') }}">
                    <i class="fas fa-history me-2"></i> History
                </a>

                <a href="{{ guard_route('patients.physical.index', $patient->id) }}" class="{{ btnClass('patients.physical.*', 'secondary') }}">
                    <i class="fas fa-book-open me-2"></i> Physical Exams
                </a>

                <a href="{{ guard_route('patients.audio.index', $patient->id) }}" class="{{ btnClass('patients.audio.*', 'dark') }}">
                    <i class="fas fa-microphone me-2"></i> Audio Consult
                </a>

                <a href="{{ guard_route('tasks.tasks.index', ['patient' => $patient]) }}" class="{{ btnClass('tasks.tasks.*', 'info') }}">
                    <i class="fas fa-tasks me-2"></i> Tasks
                </a>

                <a href="{{ guard_route('recalls.recalls.index', ['patient' => $patient]) }}" class="{{ btnClass('recalls.recalls.*', 'success') }}">
                    <i class="fas fa-bell me-2"></i> Recalls
                </a>

                <a href="{{ guard_route('waiting-lists.index', ['patient' => $patient]) }}" class="{{ btnClass('waiting-lists.*', 'warning') }}">
                    <i class="fas fa-notes-medical me-2"></i> Waiting List
                </a>

                <a href="{{ guard_route('fee-notes.index', ['patient' => $patient]) }}" class="{{ btnClass('fee-notes.*', 'secondary') }}">
                    <i class="fas fa-money-check-alt me-2"></i> Fee Notes
                </a>

                <a href="{{ guard_route('sms.index', ['patient' => $patient]) }}" class="{{ btnClass('sms.*', 'danger') }}">
                    <i class="fas fa-sms me-2"></i> SMS
                </a>

                <a href="{{ guard_route('communications.index', ['patient' => $patient]) }}" class="{{ btnClass('communications.*', 'dark') }}">
                    <i class="fas fa-comments me-2"></i> Communications
                </a>

                <a href="{{ guard_route('patients.appointments.schedule', ['patient' => $patient]) }}" class="{{ btnClass('patients.appointments.*', 'primary') }}">
                    <i class="fas fa-calendar-check me-2"></i> Appointments
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
