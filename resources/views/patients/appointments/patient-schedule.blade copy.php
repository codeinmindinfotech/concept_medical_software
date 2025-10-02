@extends('backend.theme.default')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<link href="{{ asset('theme/main/css/custom_diary.css') }}" rel="stylesheet">
<style>
    #moveFromCalendar, #moveToCalendar {
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 10px;
        background-color: #fdfdfd;
    }
</style>

@endpush

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
    ['label' => 'Patients', 'url' =>guard_route('patients.index')],
    ['label' => 'Patients Appointment '],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Patients Appointment ',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' =>guard_route('patients.index'),
    'isListPage' => false
    ])

    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fas fa-calendar-check me-2 fs-4"></i>
                <h5 class="mb-0">Patients Appointment Management</h5>
            </div>
            <button id="reset-filters" class="btn btn-outline-light btn-sm" title="Reset filters">
                <i class="fas fa-undo"></i> Reset
            </button>
        </div>


        <div class="card-body">

            <form id="filter-form" class="row g-3 align-items-end mb-4">
                <!-- Patient select -->
                <div class="col-md-6 col-lg-4">
                    <label for="patient-select" class="form-label fw-semibold">Select Patient</label>
                    <select id="patient-select" name="patient-select" class="form-select" aria-label="Select Patient">
                        <option value="">-- All Patients --</option>
                        @foreach($patients as $patientItem)
                        <option value="{{ $patientItem->id }}">
                            {{ $patientItem->full_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date picker -->
                {{-- <div class="col-md-6 col-lg-4">
                    <label for="appointment-date" class="form-label fw-semibold">Select Date</label>
                    <input type="date" id="appointment-date" class="form-control" value="">
                </div> --}}
            </form>

            <!-- Show selected patient name and date -->
            @if($patient)
                <h4 class="mb-4">Appointment Scheduler for <strong>{{ $patient->full_name }}</strong></h4>
            @endif
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
                    @php
                    $groupedClinics = $clinics->groupBy('clinic_type');
                    @endphp
                    <div class="mt-4">
                        <label for="clinic-select" class="form-label fw-semibold">Select Clinic:</label>
                        <select id="clinic-select" class="form-select shadow-sm">
                            <option value="">-- Choose Clinic --</option>
                            @foreach($groupedClinics as $type => $group)
                            <optgroup label="{{ ucfirst($type) }}">
                                @foreach($group as $clinic)
                                <option value="{{ $clinic->id }}" data-type="{{ $clinic->clinic_type }}" style="background-color:{{ $clinic->color ?? '#ffffff' }} ; color: #000000;" @if ($loop->first && $loop->parent->first) selected @endif>
                                    {{ $clinic->name }}
                                </option>
                                @endforeach
                            </optgroup>
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
                                <li><a class="dropdown-item" onclick="openClinicOverviewCountModal()">Clinic Overview</a></li>
                                <li><a class="dropdown-item" onclick="openEntireDayReport()">Entire Day Report</a></li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="openMoveAppointmentModal()">Move Appointment</a>
                                </li>
                            </ul>
                        </div>

                        <div id="selected-date-display" class="fw-bold fs-5 text-secondary">
                            <!-- Selected date or info here -->
                        </div>
                    </div>

                    <div id="slot-table" class="table-responsive flex-grow-1 overflow-auto shadow-sm rounded border">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-dark sticky-top">
                                <tr class="align-middle">
                                    <th style="width: 80px;" data-sort="time">Time<i class="fa fa-sort"></i></th>
                                    <th style="width: 120px;" data-sort="type">Type<i class="fa fa-sort"></i></th>
                                    <th data-sort="patient">Patient<i class="fa fa-sort"></i></th>
                                    <th data-sort="dob" style="width: 110px;">DOB<i class="fa fa-sort"></i></th>
                                    <th data-sort="status" style="width: 130px;">Status<i class="fa fa-sort"></i></th>
                                    <th data-sort="note">Note<i class="fa fa-sort"></i></th>
                                    <th style="width: 80px;">Actions<i class="fa fa-sort"></i></th>
                                </tr>
                            </thead>
                            <tbody id="slot-body" class="align-middle">
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Select a clinic and a date</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mb-3 justify-content-end p-3 border-bottom" id="manualSlotButton" class="d-flex " style="display: none;">
                            <button class="btn btn-sm btn-outline-primary" onclick="openManualBookingModal()">
                                <i class="fas fa-plus me-1"></i> Add Manual Slot
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Move Appointment Modal -->
<div class="modal fade" id="moveAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Move Appointment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-4">
            <!-- Left Calendar with Appointments -->
            <div class="col-md-5">
              <h6>Select Appointment from</h6>
              <div id="moveFromCalendar"></div>
              <div id="fromDateDisplay" class="mt-2 text-muted"></div>
            </div>
  
            <!-- Middle Controls -->
            <div class="col-md-2 text-center d-flex flex-column justify-content-center align-items-center">
              <i class="bi bi-arrow-left-right fs-1 mb-3"></i>
              <textarea id="moveReason" class="form-control mb-3" placeholder="Reason for move"></textarea>
              <button class="btn btn-primary" onclick="submitMoveAppointment()">Move</button>
            </div>
  
            <!-- Right Calendar to select target date/time slot -->
            <div class="col-md-5">
              <h6>Select New Date & Time Slot</h6>
              <div id="moveToCalendar"></div>
              <div id="timeSlotsForTarget" class="mt-3">
                {{-- Time slots will be loaded after user picks target date --}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

 
<!-- Clinic Overview Count Modal -->
<div class="modal fade" id="clinicOverviewCountModal" tabindex="-1" aria-labelledby="clinicOverviewCountLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title" id="clinicOverviewCountLabel">Clinic Appointment Count for <span id="clinic-count-date"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="clinic-overview-count-body">
          <p class="text-muted">Loading data...</p>
        </div>
      </div>
    </div>
</div>

<!-- Hospital Booking Modal -->
<x-hospital-appointment-modal
    :clinics="$clinics"
    :patients="$patients"
    :patient="$patient ? $patient : ''"
    :procedures="$procedures"
    :flag="0"
    :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />

<!-- Status Change Modal -->
<x-status-modal :diary_status="$diary_status" :flag="0" />

<!-- Appointment Booking Modal -->
<x-appointment-modal
    :clinics="$clinics"
    :patients="$patients"
    :patient="$patient ? $patient : ''"
    :appointmentTypes="$appointmentTypes"
    :flag="0"
    :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />



  
@endsection


@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
<script src="{{ asset('theme/patient-diary.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
    let calendar;
    let selectedClinic = document.getElementById('clinic-select').value || null;
    let selectedDate = null;
    const patientId = "{{ $patient ? $patient->id : '' }}";
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
            refreshCalendarEvents();
            loadSlotsAndAppointments();
        }
    });

    let selectedPatient = document.getElementById('patient-select').value;

    // Listen to patient select change
    document.getElementById('patient-select').addEventListener('change', function() {
        selectedPatient = this.value;

        // Reset calendar & reload appointments for new patient
        initCalendar();
        refreshCalendarEvents();
        loadSlotsAndAppointments();
    });

    document.getElementById('clinic-select').addEventListener('change', function() {
        selectedClinic = this.value;
        
        initCalendar();
        refreshCalendarEvents();
        loadSlotsAndAppointments();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const clinicSelect = document.getElementById('clinic-select');
        const manualSlotButton = document.getElementById('manualSlotButton');

        function toggleManualSlotButton() {
            const selectedOption = clinicSelect.options[clinicSelect.selectedIndex];
            const clinicType = selectedOption.getAttribute('data-type');

            if (clinicType === 'hospital') {
                manualSlotButton.style.display = 'block';
            } else {
                manualSlotButton.style.display = 'none';
            }
        }

        clinicSelect.addEventListener('change', toggleManualSlotButton);

        toggleManualSlotButton();
    });

    function getLocalDateString() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
        const day = String(now.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    // Then whenever you want to update the events (e.g., after changing clinic or adding appointment):
    function refreshCalendarEvents() {
        if (calendar) {
            calendar.refetchEvents();
        }
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
            },
            eventSources: [
                {
                    url: "{{ $patient ?guard_route('patients.appointments.calendarEvents', ['patient' => $patient ? $patient->id : '']) :guard_route('appointments.calendarEvents') }}",
                    method: 'POST',
                    extraParams: function() {
                        return {
                            _token: '{{ csrf_token() }}',
                            clinic_id: selectedClinic,
                            patient_id: selectedPatient,  
                        }
                    },
                    failure: function() {
                        console.error('Failed to load events');
                    }
                }
            ],
            eventContent: function(arg) {
                const container = document.createElement('div');
                container.style.display = 'flex';
                container.style.alignItems = 'center';
                container.style.gap = '4px';

                const dot = document.createElement('div');
                dot.className = 'fc-daygrid-event-dot';
                dot.style.borderColor = arg.event.borderColor;

                const title = document.createElement('div');
                title.className = 'fc-event-title';
                title.innerText = arg.event.title;
                title.style.backgroundColor = arg.event.borderColor;
                title.style.setProperty('color', 'black', 'important'); 

                container.appendChild(dot);
                container.appendChild(title);

                return { domNodes: [container] };
            },
            moreLinkContent: function(args) {
                return '+' + args.num + '...    ' ;
            },
            // eventsSet: function(events) {
            //     console.log('Loaded events:', events);
            // },
            dayMaxEventRows: 0, // Avoid clutter
            fixedWeekCount: false, // Show only required number of weeks
            dayCellClassNames: function(arg) {
                return (arg.dateStr === selectedDate) ? ['selected-date'] : [];
            }
            , dateClick: function(info) {
                selectedDate = info.dateStr;
                loadSlotsAndAppointments();
                refreshCalendarEvents();
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
    const routes = {
        fetchAppointments: "{{ $patient ?guard_route('patients.appointments.byDate', ['patient' => $patient->id]) :guard_route('appointments.byDateGlobal') }}",
        storeAppointment: "{{ $patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal') }}",
        storeHospitalAppointment: "{{ $patient ?guard_route('hospital_appointments.store', ['patient' => $patient->id]) :guard_route('hospital_appointments.storeGlobal') }}",
        destroyAppointment: (appointmentId, patientId) =>
            `{{guard_route('patients.appointments.destroy', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`
                .replace('__PATIENT_ID__', patientId)
                .replace('__APPOINTMENT_ID__', appointmentId),

        statusAppointment: (appointmentId, patientId) =>
            `{{guard_route('patients.appointments.updateStatus', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`
                .replace('__PATIENT_ID__', patientId)
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

    async function loadSlotsAndAppointments(clinicId = null, date = null) {

        const modalElement = document.getElementById('clinicOverviewCountModal');
        if (modalElement) {
            const bsModal = bootstrap.Modal.getInstance(modalElement);
            if (bsModal) {
                bsModal.hide();
            }
        }

        // Fallback to global variables if parameters not provided
        const selectedClinicId = clinicId || selectedClinic;
        const selectedDateValue = date || selectedDate;
        selectedDate = selectedDateValue;
        const patientSelect = document.getElementById('patient-select');
        const selectedPatient = patientSelect ? patientSelect.value : null;

        if (!selectedClinicId || !selectedDateValue) return;

        const res = await fetch(routes.fetchAppointments, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                clinic_id: selectedClinicId,
                patientSelect: selectedPatient,
                date: selectedDateValue
            })
        });

        const [year, month, day] = selectedDateValue.split('-');
        document.getElementById('selected-date-display').innerText =
            `Appointments for ${day}/${month}/${year}`;

        const data = await res.json();
        document.getElementById('slot-body').innerHTML =
            data.html || '<tr><td colspan="7">No data available</td></tr>';

        const manualSlotButton = document.getElementById('manualSlotButton');
        if (manualSlotButton) {
            manualSlotButton.style.display = data.isOpen ? 'flex' : 'none';
        }

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
            const patient_id = button.dataset.patient_id;
            const dob = button.dataset.dob;

            document.getElementById('bookAppointmentLabel').textContent = 'Edit Appointment';
            document.getElementById('modal-submit-btn').textContent = 'Update Appointment';

            document.getElementById('appointment-id').value = id;
            document.getElementById('appointment_type').value = type;
            document.getElementById('modal-appointment-date').value = date;
            document.getElementById('start_time').value = start;
            document.getElementById('end_time').value = end;
            document.getElementById('patient_need').value = need;
            document.getElementById('appointment_note').value = note;
            
            const patientName = "{{ $patient ? $patient->full_name : ""}}";

            const modalPatientNameInput = document.getElementById('modal-patient-name');
            if (modalPatientNameInput) {
                modalPatientNameInput.value = patientName;
            }

            document.getElementById('modal-dob').value = "{{ $patient ? format_date($patient->dob): '' }}";

            const modal = new bootstrap.Modal(document.getElementById('bookAppointmentModal'));
            modal.show();
            
            if(patientName == ''){
                $('#patient-id').val(patient_id).trigger('change');
                $('#patient-id').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#bookAppointmentModal')  // important for modals!
                });
            }
            
        }

        if (e.target.closest('.edit-hospital-appointment')) {
            const button = e.target.closest('.edit-hospital-appointment');

            const id = button.dataset.id;
            const patient_id = button.dataset.patient_id;
            const date = button.dataset.date;
            const admission_date = button.dataset.admission_date;
            const start = button.dataset.start;
            const admission_time = button.dataset.admission_time;
            const need = button.dataset.need;
            const note = button.dataset.note;
            const procedure_id = button.dataset.procedure_id;
            const operation_duration = button.dataset.operation_duration;
            const ward = button.dataset.ward;
            const allergy = button.dataset.allergy;
            const clinic_id = button.dataset.clinic_id;

            document.getElementById('manualBookingLabel').textContent = 'Edit Appointment';
            document.getElementById('booking-submit-btn').textContent = 'Update Appointment';

            document.getElementById('hospital-appointment-id').value = id;
            document.getElementById('hospital_appointment_date').value = date;
            document.getElementById('hospital_start_time').value = start;
            document.getElementById('admission_time').value = admission_time;
            document.getElementById('admission_date').value = admission_date;
            document.getElementById('patient_need').value = need;
            document.getElementById('notes').value = note;
            document.getElementById('procedure_id').value = procedure_id;
            document.getElementById('operation_duration').value = operation_duration;
            document.getElementById('ward').value = ward;
            document.getElementById('allergy').value = allergy;
            document.getElementById('hospital-clinic-id').value = clinic_id;

            document.getElementById('hospital-dob').value = "{{ $patient ? format_date($patient->dob): '' }}";
            const patientName = "{{ $patient ? $patient->full_name : ''}}";
            const modalPatientNameInput = document.getElementById('hospital-patient-name');
            if (modalPatientNameInput) {
                modalPatientNameInput.value = patientName;
            } 

            const modal = new bootstrap.Modal(document.getElementById('manualBookingModal'));
            modal.show();
            
            $('#procedure_id').val(procedure_id).trigger('change');
                $('#procedure_id').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#manualBookingModal')  // important for modals!
                });
            if(patientName == ''){
                $('#hospital-patient-id').val(patient_id).trigger('change');
                $('#hospital-patient-id').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#manualBookingModal')  // important for modals!
                });
            }
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

    function bookSlot(startTime) {
        const endTime = addMinutesToTime(startTime, 15); // Default 15 minutes (or based on slots)
        const patientName = "{{ $patient ? $patient->full_name : ""}}";

        document.getElementById('modal-dob').value = "{{$patient ? format_date($patient->dob): ''}}";
        document.getElementById('start_time').value = startTime;
        document.getElementById('end_time').value = endTime;
        document.getElementById('modal-appointment-date').value = selectedDate;

        document.getElementById('patient_need').value = '';
        document.getElementById('appointment_type').value = '';
        document.getElementById('appointment_note').value = '';
        const modalPatientNameInput = document.getElementById('modal-patient-name');
        if (modalPatientNameInput) {
            modalPatientNameInput.value = patientName;
        } else {
            $('#patient-id').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#bookAppointmentModal')  // important for modals!
            });
        }
        
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
        refreshCalendarEvents();

        const resetBtn = document.getElementById('reset-filters');
        const patientSelect = document.getElementById('patient-select');
        // const dateInput = document.getElementById('appointment-date');

        resetBtn.addEventListener('click', function () {
            // Reset values
            patientSelect.value = '';
            // dateInput.value = new Date().toISOString().split('T')[0]; // today's date in YYYY-MM-DD

            // Optionally trigger reload
            const event = new Event('change');
            patientSelect.dispatchEvent(event);
            // dateInput.dispatchEvent(event);
        });
    });


    function openManualBookingModal() {
        const form = document.getElementById('manualBookingForm');
    
        form.reset();
        const patientName = "{{ $patient ? $patient->full_name : '' }}";
        const modalPatientNameInput = document.getElementById('hospital-patient-name');
        if (modalPatientNameInput) {
            modalPatientNameInput.value = patientName;
        } else {
            $('#hospital-patient-id').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#manualBookingModal')  // important for modals!
            });
        }
        document.getElementById('hospital-dob').value = "{{ $patient ? format_date($patient->dob) : '' }}";
        document.getElementById('hospital-clinic-id').value = document.getElementById('clinic-select')?.value || null;
        document.getElementById('hospital_appointment_date').value = selectedDate;
        document.getElementById('admission_date').value = selectedDate;
        $('#procedure_id').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#manualBookingModal')  // important for modals!
        });
        let finalUrl = routes.storeHospitalAppointment;
        $('#manualBookingForm').attr('data-action', finalUrl);

        const modal = new bootstrap.Modal(document.getElementById('manualBookingModal'));
        modal.show();
    }

    // Drag nad drop and save

    let draggedRow;    
    function onDragStart(event) {
        draggedRow = event.currentTarget;
        event.dataTransfer.effectAllowed = "move";
        event.dataTransfer.setData("text/plain", draggedRow.dataset.appointmentId);
    }
    
    function onDragOver(event) {
        event.preventDefault();
    }
    
    function onDrop(event) {
        event.preventDefault();
    
        const targetRow = event.currentTarget;
        const targetTimeSlot = targetRow.dataset.timeSlot;
    
        const appointmentId = draggedRow.dataset.appointmentId;
    
        if (!appointmentId || !targetTimeSlot ) return;
    
        targetRow.parentNode.insertBefore(draggedRow, targetRow.nextSibling);
    
        saveAppointmentSlotChange(appointmentId, targetTimeSlot);
    }
        
    function saveAppointmentSlotChange(appointmentId, newTime) {
        fetch("{{guard_route('appointments.update-slot') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                appointment_id: appointmentId,
                new_time: newTime
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    loadSlotsAndAppointments(); // Reload appointments
                    refreshCalendarEvents();
            } else {
                Swal.fire('Error', data.message || 'Failed to update appointment.', 'error');
                loadSlotsAndAppointments(); // Reload appointments
                refreshCalendarEvents();
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error', data.message || 'Error updating appointment.', 'error');
        });
    }

function openClinicOverviewCountModal() {
    if (!selectedDate) {
        Swal.fire('Warning', 'Please select a date first.', 'info');
        return;
    }

    const modalBody = document.getElementById('clinic-overview-count-body');
    modalBody.innerHTML = '<p class="text-muted">Loading data...</p>';

    fetch(`{{ guard_route('appointments.clinicOverviewCounts') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
    })
    .then(res => res.text())
    .then(html => {
        modalBody.innerHTML = html;

        const modalElement = document.getElementById('clinicOverviewCountModal');
        if (!modalElement) {
            console.error("Modal element not found!");
            return;
        }

        // Optional: manually hide it first if it exists
        const existingModal = bootstrap.Modal.getInstance(modalElement);
        if (existingModal) {
            existingModal.hide();
        }

        // Then show it again
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    })
    .catch(err => {
        // console.error(err);
        modalBody.innerHTML = '<p class="text-danger">Failed to load data.</p>';
    });
}

function openEntireDayReport() {
    // const selectedDate = document.getElementById('entire-day-date').value;
    if (!selectedDate) {
        alert("Please select a date for the report.");
        return; // Stop if no date selected
    }
    window.open(`/reports/entire-day?date=${selectedDate}`, '_blank');

    // Redirect to the Laravel route with date as query parameter
    // window.location.href = `/reports/entire-day?date=${selectedDate}`;
}
</script>
<script>
    let moveFromCalendar, moveToCalendar;
    let selectedAppointment = null;
    let selectedTargetDate = null;

    function openMoveAppointmentModal() {
        const modal = new bootstrap.Modal(document.getElementById('moveAppointmentModal'));
        modal.show();

        setTimeout(() => {
            initMoveAppointmentCalendars();
        }, 300); // allow modal to open before rendering
    }

    function submitMoveAppointment() {
        if (!selectedAppointment) {
            Swal.fire("Error", "Please select an appointment to move.", "warning");
            return;
        }
        if (!selectedTargetDate) {
            Swal.fire("Error", "Please select a new date.", "warning");
            return;
        }

        const reason = document.getElementById('moveReason').value;
        if (!reason.trim()) {
            Swal.fire("Error", "Please provide a reason for moving the appointment.", "warning");
            return;
        }

        fetch("{{ guard_route('appointments.move') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                appointment_id: selectedAppointment.id,
                new_date: selectedTargetDate,
                reason: reason
            })
        })
        .then(async res => {
            const contentType = res.headers.get("content-type");
            const responseBody = await res.text(); // get full raw response

            if (!res.ok) {
                console.error("Server returned error (non-200):", res.status);
                console.error("Raw response body:", responseBody);
                throw new Error("Server error: " + res.status);
            }

            if (contentType && contentType.includes("application/json")) {
                return JSON.parse(responseBody);
            } else {
                console.warn("Expected JSON, but got:", contentType);
                console.log("Raw response:", responseBody);
                throw new Error("Unexpected response format");
            }
        })
        .then(data => {
            if (data.success) {
                Swal.fire("Success", "Appointment moved successfully!", "success");
                selectedAppointment = null;
                selectedTargetDate = null;
                document.getElementById('moveReason').value = '';
                document.getElementById('moveAppointmentModal').querySelector('.btn-close').click();

                refreshCalendarEvents();
                loadSlotsAndAppointments();
            } else {
                Swal.fire("Error", data.message || "Move failed.", "error");
            }
        })
        .catch(err => {
            console.error("Catch block error:", err);
            Swal.fire("Error", "Server error occurred.", "error");
        });
    }
</script> 
<script>
function selectAppointmentToMove(id, title, date) {
    selectedAppointment = { id, title, date };
    Swal.fire("Appointment Selected", `You selected: ${title} on ${date}`, "info");
}

function initMoveAppointmentCalendars() {
    const fromEl = document.getElementById('moveFromCalendar');
    const toEl = document.getElementById('moveToCalendar');
    const fromDateDisplay = document.getElementById('fromDateDisplay');

    // Clear previous calendars
    if (moveFromCalendar) moveFromCalendar.destroy();
    if (moveToCalendar) moveToCalendar.destroy();

    // LEFT Calendar: Pick date and show appointments list
    moveFromCalendar = new FullCalendar.Calendar(fromEl, {
        initialView: 'dayGridMonth',
        height: 400,
        dateClick: function(info) {
            const selectedDate = info.dateStr;
            selectedAppointment = null;
            fromDateDisplay.innerHTML = `<div class="text-muted">Loading appointments for <strong>${selectedDate}</strong>...</div>`;

            fetch("{{ guard_route('appointments.forDate') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    date: selectedDate,
                    clinic_id: selectedClinic
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.appointments.length > 0) {
                    const apptList = data.appointments.map(appt => `
                        <div class="card mb-2 p-2 appointment-item" style="cursor:pointer;" onclick="selectAppointmentToMove(${appt.id}, '${appt.title}', '${selectedDate}')">
                            <strong>${appt.title}</strong>${appt.start_time} — ${appt.end_time}
                        </div>
                    `).join('');
                    fromDateDisplay.innerHTML = `<div><strong>Appointments on ${selectedDate}:</strong>${apptList}</div>`;
                } else {
                    fromDateDisplay.innerHTML = `<div class="text-muted">No appointments on ${selectedDate}.</div>`;
                }
            })
            .catch(err => {
                console.error(err);
                fromDateDisplay.innerHTML = `<div class="text-danger">Failed to load appointments.</div>`;
            });
        }
    });
    moveFromCalendar.render();

    // RIGHT Calendar: pick new target date
    moveToCalendar = new FullCalendar.Calendar(toEl, {
        initialView: 'dayGridMonth',
        height: 400,
        dateClick: function(info) {
            selectedTargetDate = info.dateStr;
            Swal.fire("Target Date Selected", `Move to: ${info.dateStr}`, "success");
        }
    });
    moveToCalendar.render();
}
</script>

@endpush