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
                        <div class="mb-3" id="manualSlotButton" class="d-flex justify-content-end p-3 border-bottom" style="display: none;">
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
<!-- Hospital Booking Modal -->
<x-hospital-appointment-modal
    :clinics="$clinics"
    :patients="$patients"
    :patient="$patient ? $patient : ''"
    :procedures="$procedures"
    :flag="0"
    :action="$patient ? route('patients.appointments.store', ['patient' => $patient->id]) : route('appointments.storeGlobal')" />

<!-- Status Change Modal -->
<x-status-modal :diary_status="$diary_status" :flag="0" />

<!-- Appointment Booking Modal -->
<x-appointment-modal
    :clinics="$clinics"
    :patients="$patients"
    :patient="$patient ? $patient : ''"
    :appointmentTypes="$appointmentTypes"
    :flag="0"
    :action="$patient ? route('patients.appointments.store', ['patient' => $patient->id]) : route('appointments.storeGlobal')" />

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
                    url: "{{ $patient ? route('patients.appointments.calendarEvents', ['patient' => $patient ? $patient->id : '']) : route('appointments.calendarEvents') }}",
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
            eventsSet: function(events) {
                console.log('Loaded events:', events);
            },
            dayMaxEventRows: 1, // Avoid clutter
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
        fetchAppointments: "{{ $patient ? route('patients.appointments.byDate', ['patient' => $patient->id]) : route('appointments.byDateGlobal') }}",
        storeAppointment: "{{ $patient ? route('patients.appointments.store', ['patient' => $patient->id]) : route('appointments.storeGlobal') }}",
        storeHospitalAppointment: "{{ $patient ? route('hospital_appointments.store', ['patient' => $patient->id]) : route('hospital_appointments.storeGlobal') }}",
        destroyAppointment: (appointmentId, patientId) =>
            `{{ route('patients.appointments.destroy', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`
                .replace('__PATIENT_ID__', patientId)
                .replace('__APPOINTMENT_ID__', appointmentId),

        statusAppointment: (appointmentId, patientId) =>
            `{{ route('patients.appointments.updateStatus', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`
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

    async function loadSlotsAndAppointments() {
        const patientSelect = document.getElementById('patient-select');
        if (!selectedDate) return;
        const res = await fetch(routes.fetchAppointments, {
            method: 'POST'
            , headers: {
                'Content-Type': 'application/json'
                , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
            , body: JSON.stringify({
                clinic_id: selectedClinic,
                patientSelect: selectedPatient,
                date: selectedDate
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

            document.getElementById('modal-patient-name').value = "{{ $patient ? $patient->full_name : '' }}";
            document.getElementById('modal-dob').value = "{{ $patient ? format_date($patient->dob): '' }}";

            const modal = new bootstrap.Modal(document.getElementById('bookAppointmentModal'));
            modal.show();
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
            document.getElementById('hospital-patient-id').value = patient_id;
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

            document.getElementById('hospital-patient-name').value = "{{ $patient ? $patient->full_name : ''}}";
            document.getElementById('hospital-dob').value = "{{ $patient ? format_date($patient->dob): '' }}";

            const modal = new bootstrap.Modal(document.getElementById('manualBookingModal'));
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

    function bookSlot(startTime) {
        const endTime = addMinutesToTime(startTime, 15); // Default 15 minutes (or based on slots)
        const patientName = "{{ $patient ? $patient->full_name : ""}}";

        const modalPatientNameInput = document.getElementById('modal-patient-name');
        if (modalPatientNameInput) {
            modalPatientNameInput.value = patientName;
        }

        document.getElementById('modal-dob').value = "{{$patient ? format_date($patient->dob): ''}}";
        document.getElementById('start_time').value = startTime;
        document.getElementById('end_time').value = endTime;
        document.getElementById('modal-appointment-date').value = selectedDate;

        document.getElementById('patient_need').value = '';
        document.getElementById('appointment_type').value = '';
        document.getElementById('appointment_note').value = '';

        $('#appointment-patient-id').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#bookAppointmentModal')  // important for modals!
        });
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
        document.getElementById('hospital-patient-name').value = "{{ $patient ? $patient->full_name : '' }}";
        document.getElementById('hospital-dob').value = "{{ $patient ? format_date($patient->dob) : '' }}";
        document.getElementById('hospital-clinic-id').value = document.getElementById('clinic-select')?.value || null;
        document.getElementById('hospital_appointment_date').value = selectedDate;
        document.getElementById('admission_date').value = selectedDate;

        let finalUrl = routes.storeHospitalAppointment;
        $('#manualBookingForm').attr('data-action', finalUrl);

        const modal = new bootstrap.Modal(document.getElementById('manualBookingModal'));
        modal.show();
    }

    
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
        
            if (!appointmentId || !targetTimeSlot) return;
        
            // Move the dragged row to the new position in the DOM (optional visual update)
            targetRow.parentNode.insertBefore(draggedRow, targetRow.nextSibling);
        
            // Save to backend via AJAX
            saveAppointmentSlotChange(appointmentId, targetTimeSlot);
        }
        
        function saveAppointmentSlotChange(appointmentId, newTime) {
            fetch("{{ route('appointments.update-slot') }}", {
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
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', data.message || 'Error updating appointment.', 'error');
            });
        }
</script>

@endpush