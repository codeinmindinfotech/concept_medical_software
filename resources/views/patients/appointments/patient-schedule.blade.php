@extends('backend.theme.default')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<link href="{{ asset('theme/main/css/custom_diary.css') }}" rel="stylesheet">
@endpush

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

    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-header bg-primary text-white d-flex align-items-center">
          <i class="fas fa-calendar-check me-2 fs-4"></i>
          <h5 class="mb-0">Patients Appointment Management</h5>
        </div>
      
        <div class="card-body">
          <h4 class="mb-4">Appointment Scheduler for <strong>{{ $patient->full_name }}</strong></h4>
      
          <input type="hidden" id="patient-id" value="{{ $patient->id }}">
      
          <div class="row gy-4">
            <!-- Left Column -->
            <div class="col-md-4">
              <div id="calendar" class="border rounded p-3 shadow-sm" style="min-height: 300px;"></div>
      
              <div class="mt-4">
                <h6 class="mb-3 text-secondary">Quick Date Selection</h6>
                <div class="row g-2">
                  <div class="col-6">
                    <div class="form-check">
                      <input class="form-check-input quick-date" type="checkbox" value="1y" id="quick1y">
                      <label class="form-check-label" for="quick1y">+1 Year</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input quick-date" type="checkbox" value="6m" id="quick6m">
                      <label class="form-check-label" for="quick6m">+6 Months</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input quick-date" type="checkbox" value="today" id="quickToday">
                      <label class="form-check-label" for="quickToday">Today</label>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-check">
                      <input class="form-check-input quick-date" type="checkbox" value="2m" id="quick2m">
                      <label class="form-check-label" for="quick2m">+2 Months</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input quick-date" type="checkbox" value="3m" id="quick3m">
                      <label class="form-check-label" for="quick3m">+3 Months</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input quick-date" type="checkbox" value="6w" id="quick6w">
                      <label class="form-check-label" for="quick6w">+6 Weeks</label>
                    </div>
                  </div>
                </div>
              </div>
      
              <div class="mt-4">
                <label for="clinic-select" class="form-label fw-semibold">Select Clinic:</label>
                <select id="clinic-select" class="form-select shadow-sm">
                  <option value="">-- Choose Clinic --</option>
                  @foreach($clinics as $clinic)
                    <option value="{{ $clinic->id }}" @if ($loop->first) selected @endif>{{ $clinic->name }}</option>
                  @endforeach
                </select>
              </div>
      
              <div id="appointment-stats" class="border rounded p-3 bg-light mt-4 shadow-sm">
                <h6 class="fw-bold mb-3 text-primary">Statistics</h6>
                <div id="appointment-stats-content" class="small text-muted">
                  Loading stats...
                </div>
              </div>
            </div>
      
            <!-- Right Column -->
            <div class="col-md-8 d-flex flex-column">
              <div class="d-flex align-items-center mb-3">
                <div class="dropdown me-3">
                  <button class="btn btn-primary dropdown-toggle shadow" type="button" id="todoDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Diary Options
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="todoDropdown" style="z-index: 1055;">
                    <li><a class="dropdown-item" href="#">Clinic Overview</a></li>
                    <li><a class="dropdown-item" href="#">Entire Day Report</a></li>
                    <li><a class="dropdown-item" href="#">Move Appointment</a></li>
                  </ul>
                </div>
      
                <div id="selected-date-display" class="fw-bold fs-5 text-secondary">
                  <!-- Selected date or info here -->
                </div>
              </div>
      
              <div id="slot-table" class="flex-grow-1 overflow-auto shadow-sm rounded border">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-dark sticky-top">
                      <tr>
                      <th data-sort="time">Time <i class="fa fa-sort"></i></th>
                      <th data-sort="type">Type <i class="fa fa-sort"></i></th>
                      <th data-sort="patient">Patient <i class="fa fa-sort"></i></th>
                      <th data-sort="dob">DOB <i class="fa fa-sort"></i></th>
                      <th data-sort="status">Status <i class="fa fa-sort"></i></th>
                      <th data-sort="note">Note <i class="fa fa-sort"></i></th>
                      <th>Appointment</th>
                    </tr>
                  </thead>
                  <tbody id="slot-body" class="align-middle">
                    <tr>
                      <td colspan="7" class="text-center text-muted">Select a clinic and a date</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
      
        </div>
      </div>
      
</div>
<!-- Status Change Modal -->
<div class="modal fade" id="statusChangeModal" tabindex="-1" aria-hidden="true" aria-labelledby="statusChangeModalLabel">
    <div class="modal-dialog">
        <form id="statusChangeForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="statusChangeModalLabel">Change Appointment Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="appointment_id" id="appointment_id">
                    <div class="mb-3">
                        <label for="appointment_status" class="form-label">Select Status:</label>
                        <select id="appointment_status" name="appointment_status" class="form-select">
                            @foreach($diary_status as $id => $value)
                                <option value="{{ $id }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Status</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
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
                            <div class="input-group">
                                <input readonly id="modal-dob" type="text"
                                    class="form-control "
                                    placeholder="YYYY-MM-DD">
                                <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                            </div>
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

@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
    let calendar;
    let selectedClinic = document.getElementById('clinic-select').value || null;
    let selectedDate = null;

    // Handle quick date checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('quick-date')) {
            // Uncheck other boxes (only one selection at a time)
            document.querySelectorAll('.quick-date').forEach(cb => {
                if (cb !== e.target) cb.checked = false;
            });

            let offset = e.target.value;
            let newDate = new Date();

            switch (offset) {
                case '1y':
                    newDate.setFullYear(newDate.getFullYear() + 1);
                    break;
                case '2m':
                    newDate.setMonth(newDate.getMonth() + 2);
                    break;
                case '3m':
                    newDate.setMonth(newDate.getMonth() + 3);
                    break;
                case '6m':
                    newDate.setMonth(newDate.getMonth() + 6);
                    break;
                case '6w':
                    newDate.setDate(newDate.getDate() + (6 * 7));
                    break;
                case 'today':
                    // already today
                    break;
            }

            selectedDate = newDate.toISOString().split('T')[0];

            // Update calendar view
            calendar.gotoDate(newDate);

            // Highlight the selected date
            document.querySelectorAll('.fc-daygrid-day.selected-date').forEach(el => {
                el.classList.remove('selected-date');
            });
            const targetCell = document.querySelector(`.fc-daygrid-day[data-date="${selectedDate}"]`);
            if (targetCell) {
                targetCell.classList.add('selected-date');
            }

            // Load appointments
            loadSlotsAndAppointments();
        }
    });

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
        `{{ route('patients.appointments.destroy', ['patient' => $patient->id, 'appointment' => '__APPOINTMENT_ID__']) }}`.replace('__APPOINTMENT_ID__', appointmentId),
        statusAppointment: (appointmentId) =>`{{ route('patients.appointments.updateStatus', ['patient' => $patient->id, 'appointment' => '__APPOINTMENT_ID__']) }}`
        .replace('__APPOINTMENT_ID__', appointmentId),
    };

    const slotDuration = window.currentSlotDuration || 15;

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

        const [year, month, day] = selectedDate.split('-');
        document.getElementById('selected-date-display').innerText =
            `Appointments for ${day}/${month}/${year}`;

        const data = await res.json();
        document.getElementById('slot-body').innerHTML = data.html || '<tr><td colspan="6">No data available</td></tr>';
   
        // Update stats
        if (data.stats) {
            renderAppointmentStats(data.stats);
        }
    }

    function renderAppointmentStats(data) {
        const container = document.getElementById('appointment-stats-content');

        let html = `
            <div class="border rounded p-2 bg-light mb-2">
                <strong>Total Appointments:</strong> ${data.total}
                <ul class="mb-0 mt-2 ps-3">`;

        for (const [type, count] of Object.entries(data.byType)) {
            const formatted = type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

            // Convert to valid CSS class: lowercase, replace spaces with underscores
            const className = `appointment-${type.toLowerCase().replace(/\s+/g, '_')}`;

            html += `
                <li>
                    <span class="badge ${className} text-dark">${formatted}</span>: 
                    <strong>${count}</strong>
                </li>`;
        }

        html += `</ul></div>`;
        container.innerHTML = html;
    }



    document.addEventListener('click', function (e) {
        if (e.target.closest('.edit-appointment')) {
            const button = e.target.closest('.edit-appointment');

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
            document.getElementById('modal-dob').value = "{{ format_date($patient->dob) }}";

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

    function openStatusModal(appointmentId, currentStatus) {
       
        document.getElementById('appointment_id').value = appointmentId;
        document.getElementById('appointment_status').value = currentStatus;

        const statusModal = new bootstrap.Modal(document.getElementById('statusChangeModal'));
        statusModal.show();
    }

    document.getElementById('statusChangeForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const appointmentId = document.getElementById('appointment_id').value;
        const appointment_status = document.getElementById('appointment_status').value;
        const url = routes.statusAppointment(appointmentId);
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                appointment_status: appointment_status
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Success', data.message || 'Status updated successfully.', 'success');
                const modal = bootstrap.Modal.getInstance(document.getElementById('statusChangeModal'));
                modal.hide();
                loadSlotsAndAppointments(); // reload UI
            } else {
                Swal.fire('Error', data.message || 'Failed to update status.', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error', 'Something went wrong.', 'error');
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
        const endTime = addMinutesToTime(startTime, 15); // Default 15 minutes (or based on slots)
        const patientName = "{{ $patient->full_name }}";

        document.getElementById('modal-patient-name').value = patientName;
        document.getElementById('modal-dob').value = "{{format_date($patient->dob)}}";
        document.getElementById('start_time').value = startTime;
        document.getElementById('end_time').value = endTime;
        document.getElementById('modal-appointment-date').value = selectedDate;

        document.getElementById('patient_need').value = '';
        document.getElementById('appointment_type').value = '';
        document.getElementById('appointment_note').value = '';

        const modal = new bootstrap.Modal(document.getElementById('bookAppointmentModal'));
        modal.show();

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
        // hide sidebar
        document.body.classList.add('sb-sidenav-toggled');
        initCalendar();
        loadSlotsAndAppointments();

    });

</script>
@endpush