@extends('backend.theme.default')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<link href="{{ asset('theme/main/css/custom_diary.css') }}" rel="stylesheet">
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
                <h5 class="mb-0">Patients Appointment Management @if($patient)  For {{ $patient->full_name }} @endif</h5>
            </div>
            <button id="reset-filters" class="btn btn-outline-light btn-sm" title="Reset filters">
                <i class="fas fa-undo"></i> Reset
            </button>
        </div>

        <div class="card-body">
            <div class="row gy-4">
                <!-- Left Column -->
                <div class="col-md-3">

                    
            <form id="filter-form" class="row g-3 align-items-end mb-4">
                <!-- Patient select -->
                <div>
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
                                    <input class="form-check-input quick-date" type="checkbox" value="today"
                                        id="quickToday">
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
                    @foreach($groupedClinics as $type => $group)
                    <div class="mb-3">
                        <h6 class="fw-semibold text-uppercase text-secondary mb-2">{{ ucfirst($type) }}</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($group as $clc)
                            <div class="clinic-card d-flex align-items-center p-2 border rounded cursor-pointer
                                    @if ($loop->first && $loop->parent->first) border-3 border-primary @endif"
                                data-id="{{ $clc->id }}" data-type="{{ $clc->clinic_type }}"
                                data-color="{{ $clc->color ?? '#000000' }}"
                                style="border-color: {{ $clc->color ?? '#000000' }} !important;"
                                onclick="selectClinicMain('{{ $clc->name }}', '{{ $clc->id }}', this)">
                                <div class="rounded-circle me-2"
                                    style="width:12px; height:12px; background-color: {{ $clc->color ?? '#000000' }}; border:1px solid #000;">
                                </div>
                                <span class="clinic-name"
                                    style="font-weight:bold; color: {{ $clc->color ?? '#000000' }};">{{ $clc->name
                                    }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                    <input type="hidden" id="clinic-select"
                        value="{{ optional($groupedClinics->first()->first())->id }}">

                    {{-- @php
                    $groupedClinics = $clinics->groupBy('clinic_type');
                    @endphp
                    <div class="mt-4">
                        <label for="clinic-select" class="form-label fw-semibold">Select Clinic:</label>
                        <select id="clinic-select" class="form-select shadow-sm">
                            <option value="">-- Choose Clinic --</option>
                            @foreach($groupedClinics as $type => $group)
                            <optgroup label="{{ ucfirst($type) }}">
                                @foreach($group as $clinic)
                                <option value="{{ $clinic->id }}" data-type="{{ $clinic->clinic_type }}"
                                    style="background-color:{{ $clinic->color ?? '#ffffff' }} ; color: #000000;" @if
                                    ($loop->first && $loop->parent->first) selected @endif>
                                    {{ $clinic->name }}
                                </option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                    </div> --}}

                    <div id="appointment-stats" class="border rounded p-3 bg-light mt-4 shadow-sm">
                        <h6 class="fw-bold mb-3 text-primary">Statistics</h6>
                        <div id="appointment-stats-content" class="small text-muted">
                            Loading stats...
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-9 d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="dropdown me-3">
                            <button class="btn btn-primary dropdown-toggle shadow" type="button" id="todoDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Diary Options
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="todoDropdown" style="z-index: 1055;">
                                <li><a class="dropdown-item" onclick="openClinicOverviewCountModal()">Clinic
                                        Overview</a></li>
                                <li><a class="dropdown-item" onclick="openEntireDayReport()">Entire Day Report</a></li>
                                <li><a class="dropdown-item" href="#" onclick="openMoveAppointmentModal()">Move
                                        Appointment</a></li>
                                <li><a class="dropdown-item" href="#" onclick="openEntireDayReport()">Diary List</a>
                                </li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#setCalendarDaysModal">Set Calendar Days</a></li>

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
                                    <th>WhatsApp</th>
                                    <th style="width: 80px;">Actions<i class="fa fa-sort"></i></th>
                                </tr>
                            </thead>
                            <tbody id="slot-body" class="align-middle">
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Select a clinic and a date</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mb-3 justify-content-end p-3 border-bottom" id="manualSlotButton" class="d-flex "
                            style="display: none;">
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

<!-- Modal -->
<div class="modal fade" id="setCalendarDaysModal" tabindex="-1" aria-labelledby="setCalendarDaysModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title">Set Calendar Days</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-3">
                <div class="row">
                    <!-- LEFT: Clinic List -->
                    <div class="col-md-3 border-end">
                        <h6 class="fw-semibold mb-2">Clinics</h6>
                        @foreach($clinics as $clinic)
                        <div class="d-flex align-items-center mb-2" style="cursor:pointer;"
                            onclick="selectClinic('{{ $clinic->name }}', '{{ $clinic->id }}')">
                            <div class="me-2 rounded-circle"
                                style="width:12px; height:12px; background:{{ $clinic->color }};"></div>
                            <span>{{ $clinic->name }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- MIDDLE: Generated Dates -->
                    <div class="col-md-5 border-end">
                        <h6 class="fw-semibold mb-2">Dates</h6>
                        <table class="table table-sm table-bordered align-middle mb-0" id="dateTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Clinic</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <!-- RIGHT: Options -->
                    <div class="col-md-4">
                        <h6 class="fw-semibold mb-2">Options</h6>
                        <input type="hidden" id="selectedClinicId">

                        <div class="mb-2">
                            <label class="form-label mb-1">Clinic Name</label>
                            <input type="text" id="selectedClinic" class="form-control form-control-sm" readonly>
                        </div>

                        <div class="mb-2">
                            <label class="form-label mb-1">Start Date</label>
                            <input type="text" id="startDate" class="form-control form-control-sm">
                        </div>

                        <div class="mb-2">
                            <label class="form-label mb-1">Repeat</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="repeatType" value="weekly" checked>
                                <label class="form-check-label">Weekly</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="repeatType" value="fortnightly">
                                <label class="form-check-label">Fortnightly</label>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label mb-1">Number of Weeks</label>
                            <input type="number" id="repeatCount" class="form-control form-control-sm" min="1"
                                value="5">
                        </div>

                        <button class="btn btn-success btn-sm w-100 mt-2" onclick="generateDates()">Generate
                            Dates</button>
                    </div>
                </div>
            </div>

            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="saveCalendarDays()">Save</button>
            </div>
        </div>
    </div>
</div>


<!-- WhatsApp Modal (Only One Modal for All Appointments) -->
<div class="modal fade" id="whatsAppModal" tabindex="-1" aria-labelledby="whatsAppModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="whatsAppModalLabel">Send WhatsApp Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Custom Message Input -->
                <textarea id="customMessage" class="form-control" rows="4"
                    placeholder="Enter your message here...">Hello, I wanted to confirm my appointment for</textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="sendWhatsAppMessage()">Send Message</button>
            </div>
        </div>
    </div>
</div>

<!-- Move Appointment Modal -->
<x-move-appointment-modal :clinics="$clinics" id="moveAppointmentModal" title="Reschedule Appointment" />

<!-- Clinic Overview Count Modal -->
<div class="modal fade" id="clinicOverviewCountModal" tabindex="-1" aria-labelledby="clinicOverviewCountLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="clinicOverviewCountLabel">Clinic Appointment Count for <span
                        id="clinic-count-date"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="clinic-overview-count-body">
                <p class="text-muted">Loading data...</p>
            </div>
        </div>
    </div>
</div>

<!-- Hospital Booking Modal -->
<x-hospital-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ? $patient : ''"
    :procedures="$procedures" :flag="0"
    :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />

<!-- Status Change Modal -->
<x-status-modal :diary_status="$diary_status" :flag="0" />

<!-- Appointment Booking Modal -->
<x-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ? $patient : ''"
    :appointmentTypes="$appointmentTypes" :flag="0"
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

    document.addEventListener('DOMContentLoaded', function() {
        const clinicInput = document.getElementById('clinic-select');

        // Attach listener for when user clicks a clinic (value changes)
        clinicInput.addEventListener('change', function() {
            const selectedClinic = this.value;

            // Run your existing functions
            initCalendar();
            refreshCalendarEvents();
            loadSlotsAndAppointments();

            console.log('Clinic changed:', selectedClinic);
        });
    });

    function toggleManualSlotButton() {
        const selectedClinicId = document.getElementById('clinic-select').value;
        const manualSlotButton = document.getElementById('manualSlotButton');

        if (!selectedClinicId) {
            manualSlotButton.style.display = 'none';
            return;
        }

        const selectedCard = document.querySelector(`.clinic-card[data-id="${selectedClinicId}"]`);
        if (selectedCard) {
            const clinicType = selectedCard.dataset.type;
            manualSlotButton.style.display = clinicType === 'hospital' ? 'block' : 'none';
        } else {
            manualSlotButton.style.display = 'none';
        }
    }

document.addEventListener('DOMContentLoaded', function () {
    const clinicSelect = document.getElementById('clinic-select');
    const manualSlotButton = document.getElementById('manualSlotButton');

    window.toggleManualSlotButton = function() {
        const selectedClinicId = document.getElementById('clinic-select').value;
        if (!selectedClinicId) {
            manualSlotButton.style.display = 'none';
            return;
        }
        const selectedCard = document.querySelector(`.clinic-card[data-id="${selectedClinicId}"]`);
        if (selectedCard) {
            const clinicType = selectedCard.dataset.type;
            manualSlotButton.style.display = clinicType === 'hospital' ? 'block' : 'none';
        } else {
            manualSlotButton.style.display = 'none';
        }
    };

    clinicSelect.addEventListener('change', window.toggleManualSlotButton);
    window.toggleManualSlotButton();
});

// document.addEventListener('DOMContentLoaded', function () {
//     const clinicSelect = document.getElementById('clinic-select');
//     if (clinicSelect) {
//         clinicSelect.addEventListener('change', toggleManualSlotButton);
//     }
//     toggleManualSlotButton();
// });


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
                    // Source 1: URL-based
                    url: "{{ $patient ? guard_route('patients.appointments.calendarEvents', ['patient' => $patient ? $patient->id : '']) : guard_route('appointments.calendarEvents') }}",
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
                },
                {
                    // Source 2: function-based
                    events: function(fetchInfo, successCallback, failureCallback) {
                        fetch("{{ guard_route('calendar.fetchDays') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                clinic_id: selectedClinic,
                                start: fetchInfo.startStr,
                                end: fetchInfo.endStr
                            })
                        })
                        .then(response => response.json())
                        .then(events => successCallback(events))
                        .catch(error => {
                            console.error("Failed to load events", error);
                            failureCallback(error);
                        });
                    }

                }
            ],
            eventDidMount: function(info) {
                if (info.event.display === 'background') {
                    info.el.style.backgroundColor = 'transparent';
                    info.el.style.borderColor = info.event.borderColor  || '#592222'; //|| info.event.backgroundColor
                    info.el.style.borderWidth = '2px';       // ✅ add this
                    info.el.style.borderStyle = 'solid';     // ✅ and this
                    info.el.style.opacity = 1;
                    info.el.style.color = '#ffffff';
                    info.el.style.fontWeight = 'bold';
                }
            },
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
                title.style.borderColor = arg.event.borderColor;
                // title.style.backgroundColor = arg.event.borderColor;
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
                calendar.refetchEvents();
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
        reportUrl: "{{ guard_route('reports.entire-day') }}"

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
        if(clinicId){
            triggerClinicById(clinicId); // replace 5 with your clinicId
        }
        
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

        $('#globalLoader').show();
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
        
        $('#globalLoader').hide();
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

    function triggerClinicById(clinicId) {

        const clinicCard = document.querySelector(`.clinic-card[data-id='${clinicId}']`);
        if (clinicCard) {
            selectedClinic = clinicId;
            const clinicName = clinicCard.querySelector('.clinic-name').innerText;
            selectClinicMain(clinicName, clinicId, clinicCard);
        } else {
            console.warn(`Clinic card with ID ${clinicId} not found.`);
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
            const clinic_consultant = button.dataset.consultant;

            document.getElementById('bookAppointmentLabel').textContent = 'Edit Appointment';
            document.getElementById('modal-submit-btn').textContent = 'Update Appointment';

            document.getElementById('appointment-id').value = id;
            document.getElementById('appointment_type').value = type;
            document.getElementById('modal-appointment-date').value = date;
            document.getElementById('start_time').value = start;
            document.getElementById('end_time').value = end;
            document.getElementById('patient_need').value = need;
            document.getElementById('appointment_note').value = note;
            document.getElementById('appointment-id').value = id;
            document.getElementById('clinic_consultant').value = clinic_consultant;
            
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
            const consultant = button.dataset.consultant;

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
            document.getElementById('consultant').value = consultant;

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

        resetBtn.addEventListener('click', function () {
            patientSelect.value = '';

            // Optionally trigger reload
            const event = new Event('change');
            patientSelect.dispatchEvent(event);
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
        if (!selectedDate) {
            Swal.fire("Error", "Please select a date for the report.", "warning");
            return; // Stop if no date selected
        }
        window.open(`${routes.reportUrl}?date=${selectedDate}`, '_blank');
   }

</script>
<script>
    // JavaScript to dynamically set the WhatsApp message and phone number in the modal
    var currentAppointmentId, currentPhoneNumber;

    // Set up modal with data from the clicked button
    $('#whatsAppModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        currentAppointmentId = button.data('appointment-id');
        currentPhoneNumber = button.data('patient-phone');

        var defaultMessage = "Hello, I wanted to confirm my appointment for " + button.data('appointment-time');

        // Set the textarea content
        $('#customMessage').val(defaultMessage);
    });

    // Function to send WhatsApp message with custom text
    function sendWhatsAppMessage() {
        var message = $('#customMessage').val();
        var whatsAppUrl = "https://wa.me/" + currentPhoneNumber + "?text=" + encodeURIComponent(message);

        window.open(whatsAppUrl, "_blank");
    }
</script>
<script>
    function selectClinicMain(name, id, element) {
        // remove previous highlights
        document.querySelectorAll('.clinic-card').forEach(card => 
            card.classList.remove('border-primary', 'border-3')
        );

        // highlight the selected card
        element.classList.add('border-primary', 'border-3');

        // update hidden input
        const clinicInput = document.getElementById('clinic-select');
        clinicInput.value = id;

        // 🔥 update global variable
        selectedClinic = id;

        // toggle manual slot button
        toggleManualSlotButton();

        // refresh UI and data
        initCalendar();
        refreshCalendarEvents();
        loadSlotsAndAppointments()
    }

    let selectedAppointments = [];
    let selectedTargetDate = null;



    // Initialize flatpickr date pickers
    flatpickr("#fromDate", {
        dateFormat: "Y-m-d", // Customize the date format
        onChange: function(selectedDates, dateStr) {
            // Load appointments for the selected "from" date
            loadAppointmentsForDate(dateStr);
        }
    });

    flatpickr("#startDate", {
        dateFormat: "Y-m-d", // Customize the date format
    });

    document.getElementById('fromClinic').addEventListener('change', function() {
        const selectedDate = document.getElementById('fromDate').value;
        if (selectedDate) {
            loadAppointmentsForDate(selectedDate);
        } else {
            document.getElementById('fromDateDisplay').innerHTML = `<div class="text-muted">Please select a date first.</div>`;
        }
    });

    flatpickr("#toDate", {
        dateFormat: "Y-m-d", // Customize the date format
        onChange: function(selectedDates, dateStr) {
            // Set selected target date
            selectedTargetDate = dateStr;
            console.log('Target Date selected:', selectedTargetDate);

            // Load available time slots for the selected "to" date
            loadAvailableTimeSlots(dateStr);
        }
    });

    document.getElementById('toClinic').addEventListener('change', function() {
        const selectedTargetDate = document.getElementById('toDate').value;
        if (selectedTargetDate) {
            loadAvailableTimeSlots(selectedTargetDate);
        } else {
            document.getElementById('timeSlotsForTarget').innerHTML = `<div class="text-muted">Please select a date first.</div>`;
        }
    });

    function loadAppointmentsForDate(date) {
        const fromClinicSelect = document.getElementById('fromClinic');
        const displayContainer = document.getElementById('fromDateDisplay');

        displayContainer.innerHTML = `
            <div class="text-center py-3 text-muted">
                <div class="spinner-border text-primary me-2" role="status"></div>
                Loading appointments for ${date}...
            </div>
        `;

        fetch("{{ guard_route('appointments.forDate') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                date: date,
                clinic_id: fromClinicSelect.value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                displayContainer.innerHTML = data.appointments_html || 
                    `<div class="text-center text-muted py-3">No appointments on ${date}.</div>`;
            }
        })
        .catch(err => {
            console.error(err);
            displayContainer.innerHTML = `<div class="text-center text-danger py-3">Failed to load appointments.</div>`;
        });
    }

    function loadAvailableTimeSlots(date) {
        const toClinicSelect = document.getElementById('toClinic');
        const timeSlotsContainer = document.getElementById('timeSlotsForTarget');

        if (!toClinicSelect.value) {
            timeSlotsContainer.innerHTML = `<div class="text-danger">Please select a clinic first.</div>`;
            return;
        }

        // Fetch available time slots for the selected "to" date
        fetch("{{ guard_route('appointments.availableSlots') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                date: date,
                clinic_id: toClinicSelect.value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.slots.length > 0) {
                const slotButtons = data.slots.map(slot => `
                    <button 
                        type="button" 
                        class="btn btn-outline-success btn-sm slot-btn m-1 px-2 py-1" 
                        style="min-width: 80px; font-size: 0.85rem;"
                        data-slot="${slot}">
                        ${slot}
                    </button>
                `).join('');

                timeSlotsContainer.innerHTML = `
                    <div class="fw-semibold mb-1" style="font-size: 0.9rem;">Available Slots:</div>
                    <div class="d-flex flex-wrap justify-content-start">${slotButtons}</div>
                    <input type="hidden" id="selectedSlot" name="selected_slot" value="">
                `;

                // Add event listeners for selecting a slot
                document.querySelectorAll('.slot-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        // Remove "active" style from all buttons
                        document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('active', 'btn-success'));
                        document.querySelectorAll('.slot-btn').forEach(b => b.classList.add('btn-outline-success'));

                        // Mark this button as selected
                        this.classList.remove('btn-outline-success');
                        this.classList.add('btn-success', 'active');

                        // Store selected slot in hidden input
                        document.getElementById('selectedSlot').value = this.dataset.slot;
                    });
                });

            } else {
                timeSlotsContainer.innerHTML = `<div class="text-muted">No time slots available for ${date}.</div>`;
            }
        })
        .catch(err => {
            console.error(err);
            timeSlotsContainer.innerHTML = `<div class="text-danger">Failed to load time slots.</div>`;
        });
    }

    document.addEventListener("change", function(e) {
        if (e.target && e.target.name === 'appointment_ids[]') {
            selectedAppointments = Array.from(document.querySelectorAll('input[name="appointment_ids[]"]:checked')).map(cb => parseInt(cb.value));
            console.log("Selected Appointments:", selectedAppointments);
        }
    });
    // Submit the appointment move request
    function submitMoveAppointment() {
        const reason = document.getElementById('moveReason').value;
        if (!reason.trim()) {
            Swal.fire("Error", "Please provide a reason for moving the appointment.", "warning");
            return;
        }

        if (!selectedAppointments.length || !selectedTargetDate) {
            Swal.fire("Incomplete", "Please select at least one appointment and a new target date.", "warning");
            return;
        }

        const toClinicSelect = document.getElementById('toClinic');

        const selectedSlot = document.getElementById('selectedSlot')?.value || '';
        if (!selectedSlot) {
            Swal.fire("Incomplete", "Please select a time slot for the new appointment.", "warning");
            return;
        }
        fetch("{{ guard_route('appointments.move') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                appointment_ids: selectedAppointments,
                clinic_id: toClinicSelect.value,
                new_date: selectedTargetDate,
                time_slot: selectedSlot,
                reason,
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire("Success", "Appointments moved successfully!", "success");
                $('#moveAppointmentModal').modal('hide');
                    initCalendar();
                    refreshCalendarEvents();
                    loadSlotsAndAppointments();
                // Optionally, refresh other parts of the page here...
            } else {
                Swal.fire("Error", data.message || "Move failed.", "error");
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "An error occurred while moving appointments.", "error");
        });
    }
    function openMoveAppointmentModal() {
        // Open the modal (this assumes you're using Bootstrap's modal)
        const modal = new bootstrap.Modal(document.getElementById('moveAppointmentModal'));
        modal.show();
        
        // Optionally, you can clear selections or do additional actions here
        clearSelections();
    }

    function clearSelections() {
        document.getElementById('fromClinic').value = '';
        document.getElementById('fromDate').value = '';
        document.getElementById('toClinic').value = '';
        document.getElementById('toDate').value = '';
        document.getElementById('moveReason').value = '';
        document.getElementById('fromDateDisplay').innerHTML = '<div class="text-muted">No appointments selected</div>';
        document.getElementById('timeSlotsForTarget').innerHTML = '<div class="text-muted">Please select clinic and date</div>';
    }

    function selectClinic(name, id) {
        document.getElementById('selectedClinic').value = name;
        document.getElementById('selectedClinicId').value = id;
    }

    function generateDates() {
        const startDateInput = document.getElementById('startDate').value;
        const repeatType = document.querySelector('input[name="repeatType"]:checked').value;
        const repeatCount = parseInt(document.getElementById('repeatCount').value);
        const clinicName = document.getElementById('selectedClinic').value;

        if (!startDateInput || !clinicName) {
            Swal.fire("Error", "Please select a clinic and start date.", "error");
            return;
        }

        const startDate = new Date(startDateInput);
        const tbody = document.querySelector('#dateTable tbody');
        tbody.innerHTML = '';

        for (let i = 0; i < repeatCount; i++) {
            const date = new Date(startDate);
            date.setDate(startDate.getDate() + i * (repeatType === 'weekly' ? 7 : 14));
            const formattedDate = date.toISOString().split('T')[0];
            tbody.innerHTML += `
            <tr>
                <td>${formattedDate}</td>
                <td>${clinicName}</td>
                <td><input type="checkbox" value="${formattedDate}" checked></td>
            </tr>`;
        }
    }

    function saveCalendarDays() {
        const clinicId = document.getElementById('selectedClinicId').value;
        const dates = Array.from(document.querySelectorAll('#dateTable tbody input[type="checkbox"]:checked'))
            .map(cb => cb.value);

        if (!clinicId || dates.length === 0) {
            Swal.fire("Error", "Select a clinic and at least one date.", "error");
            return;
        }

        fetch('{{ guard_route('calendar.store') }}', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ clinic_id: clinicId, dates })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire("Success", data.message, "success");
                $('#setCalendarDaysModal').modal('hide');
                // Example usage:
                initCalendar();
                refreshCalendarEvents();
                loadSlotsAndAppointments();
                document.querySelector('#dateTable tbody').innerHTML = '';
                document.getElementById('startDate').value = '';
            }
        })
        .catch(err => console.error('Error:', err));
    }
    



    // Helper function to check if color is dark
    function isDarkColor(color) {
        if(!color) return false;
        color = color.replace('#','');
        const r = parseInt(color.substr(0,2),16);
        const g = parseInt(color.substr(2,2),16);
        const b = parseInt(color.substr(4,2),16);
        const brightness = (r*299 + g*587 + b*114) / 1000;
        return brightness < 128;
    }

    document.querySelectorAll('.clinic-card').forEach(card => {
        const color = card.dataset.color;
        if(color){
            const hex = color.replace('#','');
            const r = parseInt(hex.substr(0,2),16);
            const g = parseInt(hex.substr(2,2),16);
            const b = parseInt(hex.substr(4,2),16);
            const brightness = (r*299 + g*587 + b*114) / 1000;
            card.style.color = brightness < 128 ? '#ffffff' : '#000000';
        }
    });

</script>

@endpush