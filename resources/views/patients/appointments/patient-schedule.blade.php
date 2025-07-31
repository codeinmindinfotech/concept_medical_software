@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('dashboard.index')],
    ['label' => 'Patients', 'url' => route('patients.index')],
    ['label' => 'Patients Appointment '],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Patients Appointment ',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' => route('patients.index'),
    'isListPage' => false
    ])

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-microphone"></i> Patients Appointment Management
        </div>
        <div class="card-body">
            <h3>Appointment Scheduler for <strong>{{ $patient->full_name }}</strong></h3>

            <input type="hidden" id="patient-id" value="{{ $patient->id }}">

            {{-- SECTION 1: Calendar + Clinic Dropdown --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div id="calendar" style="border: 1px solid #ccc; padding: 10px;"></div>

                    <div class="mb-3">
                        <label for="clinic-select" class="form-label">Select Clinic:</label>
                        <select id="clinic-select" class="form-select">
                            <option value="">-- Choose Clinic --</option>
                            @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}" @if ($loop->first) selected @endif>{{ $clinic->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div id="selected-date-display" class="mb-3 fw-bold"></div>
                    <div id="slot-table" style="max-height: 700px; overflow-y: auto;">
                        <table class="table table-bordered mb-0">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th data-sort="time">Time <i class="fa fa-sort"></i></th>
                                    <th data-sort="type">Type <i class="fa fa-sort"></i></th>
                                    <th data-sort="patient">Patient <i class="fa fa-sort"></i></th>
                                    <th data-sort="dob">DOB <i class="fa fa-sort"></i></th>
                                    <th data-sort="status">Status <i class="fa fa-sort"></i></th>
                                    <th>Appointment</th>
                                </tr>
                            </thead>
                            <tbody id="slot-body">
                                <tr>
                                    <td colspan="6">Select a clinic and a date</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
<!-- Appointment Booking Modal -->
<div class="modal fade" id="bookAppointmentModal" tabindex="-1" aria-labelledby="bookAppointmentLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="bookAppointmentForm"
            data-action="{{ route('patients.appointments.store', ['patient' => $patient->id]) }}">

            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="bookAppointmentLabel">Book Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" class="form-control" id="appointment-id" name="appointment-id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Patient Name</label>
                            <input type="text" class="form-control" id="modal-patient-name" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="text" class="form-control" id="modal-dob" readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="appointment_type" class="form-label">Appointment Type</label>
                            <select class="form-select" id="appointment_type" name="appointment_type">
                                @foreach($appointment_types as $id => $value)
                                <option value="{{ $id }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="dob" class="form-label"><strong>Appointment Date</strong></label>
                            <div class="input-group">
                                <input id="modal-appointment-date" name="appointment_date" type="text"
                                    class="form-control flatpickr @error('dob') is-invalid @enderror"
                                    placeholder="YYYY-MM-DD">
                                <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Start Time</label>
                            <input type="text" class="form-control" id="start_time" name="start_time" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">End Time</label>
                            <input type="text" class="form-control" id="end_time" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Slots</label>
                            <div id="slot-options">
                                @for ($i = 1; $i <= 10; $i++) <div class="form-check form-check-inline">
                                    <input class="form-check-input apt-slot-radio" type="radio" name="apt_slots"
                                        id="slot{{ $i }}" {{ $i==1 ? 'checked' : '' }} value="{{ $i }}">
                                    <label class="form-check-label" for="slot{{ $i }}">{{ $i }}</label>
                            </div>
                            @endfor
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Patient Need</label>
                        <input type="text" class="form-control" id="patient_need" name="patient_need">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Appointment Note</label>
                        <input type="text" class="form-control" id="appointment_note" name="appointment_note">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="modal-submit-btn">Confirm Booking</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<style>
    #slot-table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
    }

    #calendar {
        background-color: #ffffff;
        border-radius: 12px;
        padding: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        /* slightly stronger shadow for better depth */
        width: 100%;
        max-width: 100%;
        min-height: 400px;
        box-sizing: border-box;
    }

    /* General calendar font and spacing */
    .fc {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 14px;
        color: #333;
    }

    .fc-toolbar-title {
        font-size: 20px;
        /* slightly larger for emphasis */
        font-weight: 700;
        color: #343a40;
        margin-bottom: 8px;
    }

    /* Buttons */
    .fc-button {
        background-color: #17a2b8;
        border: none;
        color: #fff;
        padding: 6px 14px;
        border-radius: 6px;
        font-weight: 600;
        transition: background-color 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .fc-button:hover,
    .fc-button:focus {
        background-color: #138496;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Highlight selected date */
    .fc-daygrid-day.selected-date {
        background-color: #007bff !important;
        color: #fff !important;
        border-radius: 8px;
        box-shadow: inset 0 0 8px rgba(0, 0, 0, 0.25);
    }

    /* Hover effect on day cells */
    .fc-daygrid-day:hover {
        background-color: #e6f0ff;
        transition: background-color 0.25s ease-in-out;
        cursor: pointer;
    }

    /* Sunday - soft red background */
    .fc-day-sun {
        background-color: #ffe6e6;
    }

    /* Saturday - dark background with white text */
    .fc-day-sat {
        background-color: #e6f2ff;
        /* light blue tint */
        color: #333;
        /* dark text */
    }

    /* Day headers */
    .fc-col-header-cell {
        background-color: #000000;
        font-weight: 700;
        padding: 12px 0;
        text-transform: uppercase;
        font-size: 14px;
        border-bottom: 2px solid #343a40;
    }

    .fc-col-header-cell a {
        color: #ffffff !important;
        text-decoration: none;
        /* optional: removes underline */
    }

    /* Today highlight */
    .fc-day-today {
        background-color: #fff3cd !important;
        border: 2px solid #ffeeba;
        border-radius: 6px;
        font-weight: 700;
    }

    /* Day numbers */
    .fc-daygrid-day-number {
        font-weight: 700;
        color: #495057;
    }

    /* Fix contrast on Saturday numbers */
    .fc-day-sat .fc-daygrid-day-number {
        color: #000000;
    }

    .appointment-first_visit {
        background-color: #e6f7ff;
        /* light blue */
    }

    .appointment-injection {
        background-color: #fff3cd;
        /* light yellow */
    }

    .appointment-medical_legal {
        background-color: #f0f0f0;
        /* light gray */
    }

    .appointment-post_op {
        background-color: #fce4ec;
        /* light pink */
    }

    .appointment-review_visit {
        background-color: #d4edda;
        /* light green */
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
    let calendar;
    let selectedClinic = document.getElementById('clinic-select').value || null;
    let selectedDate = null;

    document.getElementById('clinic-select').addEventListener('change', function() {
        selectedClinic = this.value;
        initCalendar();
        loadSlotsAndAppointments();
    });

    function getLocalDateString() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
        const day = String(now.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function initCalendar() {
        if (calendar) calendar.destroy();

        calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: 'dayGridMonth'
            , height: 'auto'
            , selectable: true
            , headerToolbar: {
                left: 'prev,next'
                , center: 'title'
                , right: ''
            }
            , dayMaxEventRows: 1, // Avoid clutter
            fixedWeekCount: false, // Show only required number of weeks
            dayCellClassNames: function(arg) {
                return (arg.dateStr === selectedDate) ? ['selected-date'] : [];
            }
            , dateClick: function(info) {
                selectedDate = info.dateStr;
                loadSlotsAndAppointments();

                // Remove previous selection
                document.querySelectorAll('.fc-daygrid-day.selected-date').forEach(el => {
                    el.classList.remove('selected-date');
                });

                // Highlight new date
                info.dayEl.classList.add('selected-date');
            }
            , datesSet: function() {
                if (!selectedDate) {
                    selectedDate = getLocalDateString();
                    const todayCell = document.querySelector(`.fc-daygrid-day[data-date="${selectedDate}"]`);
                    if (todayCell) {
                        todayCell.classList.add('selected-date');
                    }
                }
            }
        });

        calendar.render();
    }


    const patientId = "{{ $patient->id }}";

    const routes = {
        fetchAppointments: "{{ route('patients.appointments.byDate', ['patient' => $patient->id]) }}",
        storeAppointment: "{{ route('patients.appointments.store', ['patient' => $patient->id]) }}",
        destroyAppointment: (appointmentId) => 
        `{{ route('patients.appointments.destroy', ['patient' => $patient->id, 'appointment' => '__APPOINTMENT_ID__']) }}`.replace('__APPOINTMENT_ID__', appointmentId)
    };
    const slotDuration = window.currentSlotDuration || 15;


    // Bind radio button listener after modal is shown
    function setupSlotChangeHandler(startTime) {
        const radios = document.querySelectorAll('.apt-slot-radio');
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                const selectedSlots = parseInt(this.value);
                const endTime = addMinutesToTime(startTime, selectedSlots * slotDuration);
                document.getElementById('end_time').value = endTime;
            });
        });
    }

    async function loadSlotsAndAppointments() {
        const patientId = document.getElementById('patient-id').value;
        if (!selectedDate) return;
        const res = await fetch(routes.fetchAppointments, {
            method: 'POST'
            , headers: {
                'Content-Type': 'application/json'
                , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
            , body: JSON.stringify({
                clinic_id: selectedClinic
                , date: selectedDate
            })
        });

        const data = await res.json();
        document.getElementById('slot-body').innerHTML = data.html || '<tr><td colspan="6">No data available</td></tr>';
    }

    document.addEventListener('click', function (e) {
    if (e.target.closest('.edit-appointment')) {
        const button = e.target.closest('.edit-appointment');
        alert("Edit button clicked"); // debug

        const id = button.dataset.id;
        const type = button.dataset.type;
        const date = button.dataset.date;
        const start = button.dataset.start;
        const end = button.dataset.end;
        const need = button.dataset.need;
        const note = button.dataset.note;

        document.getElementById('bookAppointmentLabel').textContent = 'Edit Appointment';
        document.getElementById('modal-submit-btn').textContent = 'Update Appointment';

        document.getElementById('appointment-id').value = id;
        document.getElementById('appointment_type').value = type;
        document.getElementById('modal-appointment-date').value = date;
        document.getElementById('start_time').value = start;
        document.getElementById('end_time').value = end;
        document.getElementById('patient_need').value = need;
        document.getElementById('appointment_note').value = note;

        document.getElementById('modal-patient-name').value = "{{ $patient->full_name }}";
        document.getElementById('modal-dob').value = "{{ $patient->dob }}";

        const modal = new bootstrap.Modal(document.getElementById('bookAppointmentModal'));
        modal.show();
    }
});

    document.querySelectorAll('#slot-table thead th[data-sort]').forEach(th => {
        th.addEventListener('click', function() {
            const sortKey = this.getAttribute('data-sort');
            const tbody = document.getElementById('slot-body');
            const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => row.querySelector('td'));

            const isAsc = this.classList.toggle('asc');

            rows.sort((a, b) => {
                const aText = a.querySelector(`td:nth-child(${this.cellIndex + 1})`)?.textContent.trim() || '';
                const bText = b.querySelector(`td:nth-child(${this.cellIndex + 1})`)?.textContent.trim() || '';

                if (!isNaN(Date.parse(aText)) && !isNaN(Date.parse(bText))) {
                    return isAsc ? new Date(aText) - new Date(bText) : new Date(bText) - new Date(aText);
                } else if (!isNaN(aText) && !isNaN(bText)) {
                    return isAsc ? aText - bText : bText - aText;
                } else {
                    return isAsc ? aText.localeCompare(bText) : bText.localeCompare(aText);
                }
            });

            // Re-append sorted rows
            rows.forEach(row => tbody.appendChild(row));
        });
    });

    function deleteAppointment(appointmentId) {
        const url = routes.destroyAppointment(appointmentId);
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete the appointment.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        loadSlotsAndAppointments(); // Reload appointments
                    } else {
                        Swal.fire('Error', data.message || 'Failed to delete.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Something went wrong.', 'error');
                });
            }
        });
    }


    function bookSlot(startTime) {
        // $('#appointment_type').select2();

        const endTime = addMinutesToTime(startTime, 15); // Default 15 minutes (or based on slots)
        const patientName = "{{ $patient->full_name }}";

        document.getElementById('modal-patient-name').value = patientName;
        document.getElementById('modal-dob').value = "{{$patient->dob}}";
        // Set modal hidden input values
        document.getElementById('start_time').value = startTime;
        document.getElementById('end_time').value = endTime;
        document.getElementById('modal-appointment-date').value = selectedDate;

        // Optionally clear fields
        // $('#appointment_type').val('').trigger('change');
        document.getElementById('patient_need').value = '';
        document.getElementById('appointment_type').value = '';
        document.getElementById('appointment_note').value = '';

        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('bookAppointmentModal'));
        modal.show();

        // Setup slot radio handling
        setupSlotChangeHandler(startTime);
    }

    function addMinutesToTime(time, minsToAdd) {
        let [h, m] = time.split(':').map(Number);
        m += minsToAdd;
        if (m >= 60) {
            h += Math.floor(m / 60);
            m = m % 60;
        }
        return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
    }

    window.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        selectedDate = today.toISOString().split('T')[0]; // Format as YYYY-MM-DD
        initCalendar();
        loadSlotsAndAppointments();
    });

</script>
@endpush