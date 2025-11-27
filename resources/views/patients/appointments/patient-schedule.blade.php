<?php $page = 'appointment-list'; ?>
@extends('layout.mainlayout_admin')
@push('styles')
<link href="{{ asset('assets_admin/css/custom_diary.css') }}" rel="stylesheet">
@endpush
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="container-fluid px-4">
        @php
        $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'Patients', 'url' =>guard_route('patients.index')],
        ['label' => 'Patients Appointment '],
        ];
        @endphp

        @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Patients Appointment ',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('patients.index'),
        'isListPage' => false
        ])

        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-header  d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-check me-2 fs-4"></i>
                    <h5 class="mb-0">Patients Appointment Management @if($patient) For {{ $patient->full_name }} @endif</h5>
                </div>
                
                <button id="toggleLeftColumn" class="btn btn-secondary btn-sm d-flex align-items-center gap-1">
                    <i id="toggleIcon" class="fe fe-text-align-left"></i>
                    <span id="toggleText">Show Calendar</span>
                </button>
                
                
            </div>
             <div class="card-body">
                <div class="row gy-4">
                    <!-- Left Column -->
                    <div id="leftColumn" class="col-md-3 d-none">


                        <form id="filter-form" class="row g-3 align-items-end mb-4">
                            <!-- Patient select -->
                            <div class="d-flex justify-content-between align-items-center">

                                <!-- Left: Label -->
                                <label for="patient-select" class="form-label fw-semibold mb-0">
                                    Select Patient
                                </label>
                            
                                <!-- Right: Reset Button -->
                                <button id="reset-filters" class="btn btn-sm btn-primary" title="Reset filters">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                            
                            </div>
                            
                            <!-- Dropdown Below -->
                            <select id="patient-select" name="patient-select" 
                                    class="form-select" aria-label="Select Patient">
                                    <option value="">-- All Patients --</option>
                                    @foreach($patients as $patientItem)
                                        <option value="{{ $patientItem->id }}">
                                            {{ $patientItem->full_name }}
                                        </option>
                                    @endforeach
                            </select>
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
                            @foreach($groupedClinics as $type => $group)
                            <div class="mb-3">
                                <h6 class="fw-semibold text-uppercase text-secondary mb-2">{{ ucfirst($type) }}</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($group as $clc)
                                    <div class="clinic-card d-flex align-items-center p-2 border rounded cursor-pointer
                                        @if ($loop->first && $loop->parent->first) border-3 border-primary @endif" data-id="{{ $clc->id }}" data-type="{{ $clc->clinic_type }}" data-color="{{ $clc->color ?? '#000000' }}" style="border-color: {{ $clc->color ?? '#000000' }} !important;" onclick="selectClinicMain('{{ $clc->name }}', '{{ $clc->id }}', this)">
                                        <div class="rounded-circle me-2" style="width:12px; height:12px; background-color: {{ $clc->color ?? '#000000' }}; border:1px solid #000;">
                                        </div>
                                        <span class="clinic-name" style="font-weight:bold; color: {{ $clc->color ?? '#000000' }};">{{ $clc->name
                                        }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @php
                            // Get clinic_id from URL query parameter
                            $urlClinicId = request()->query('clinic_id');

                            // Fallback to first clinic ID if not in URL
                            $selectedClinicId = $urlClinicId ?: optional($groupedClinics->first()->first())->id;
                        @endphp
                        <input type="hidden" id="clinic-select" value="{{ $selectedClinicId }}">

                        <div id="appointment-stats" class="border rounded p-3 bg-light mt-4 shadow-sm">
                            <h6 class="fw-bold mb-3 text-primary">Statistics</h6>
                            <div id="appointment-stats-content" class="small text-muted">
                                Loading stats...
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div id="rightColumn" class="col-md-12 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="dropdown me-3">
                                <button class="btn btn-primary dropdown-toggle shadow" type="button" id="todoDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#setCalendarDaysModal">Set Calendar Days</a></li>

                                </ul>
                            </div>

                            <div id="selected-date-display" class="fw-bold fs-5 text-secondary">
                                <!-- Selected date or info here -->
                            </div>
                        </div>
                        <div id="slot-table" class="table-responsive">
                            {{-- <div id="slot-table" class="table-responsive flex-grow-1 overflow-auto shadow-sm rounded border"> --}}
                            <table class="table table-hover table-center mb-0">
                                <thead class="sticky-top">
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
</div>
<!-- /Page Wrapper -->
</div>
<!-- /Main Wrapper -->
@endsection

@push('modals')
    <!-- Modal -->
    <x-set-calendar-days-modal :clinics="$clinics" />

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
                    <textarea id="customMessage" class="form-control" rows="4" placeholder="Enter your message here...">Hello, I wanted to confirm my appointment for</textarea>
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
    <x-clinic-overview-count-modal />

    <!-- Hospital Booking Modal -->
    <x-hospital-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ? $patient : ''" :procedures="$procedures" :flag="0" :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />

    <!-- Status Change Modal -->
    <x-status-modal :diary_status="$diary_status" :flag="0" />

    <!-- Appointment Booking Modal -->
    <x-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ? $patient : ''" :appointmentTypes="$appointmentTypes" :flag="0" :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />
@endpush


@push('scripts')
<script src="{{ asset('assets_admin/js/patient-diary.js') }}"></script>
<script src="{{ asset('assets_admin/js/booking-diary.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
    window.Laravel = {
        csrfToken: "{{ csrf_token() }}"
    };
</script>
<script src="{{ URL::asset('/assets/js/calendar.js') }}"></script>
<script>
    
    // let calendar;
    let calendar = null;
    window.calendarInstance = null;

    let selectedClinic = document.getElementById('clinic-select').value || null;
    let selectedDate = null;
    const patientId = "{{ $patient ? $patient->id : '' }}";
    // Handle quick date checkboxes

    document.getElementById('toggleLeftColumn').addEventListener('click', function () {

        let left = document.getElementById('leftColumn');
        let right = document.getElementById('rightColumn');
        let text = document.getElementById('toggleText');

        left.classList.toggle('d-none');

        if (left.classList.contains('d-none')) {
            // Expand calendar to full width
            right.classList.remove('col-md-9');
            right.classList.add('col-md-12');

            // Change icon + text
            text.innerText = "Show Calendar";
        } else {
            right.classList.remove('col-md-12');
            right.classList.add('col-md-9');

            text.innerText = "Hide Calendar";

            // Re-render calendar when showing sidebar
            setTimeout(() => {
                if (window.calendarInstance) {
                    window.calendarInstance.updateSize();
                    window.calendarInstance.render();
                }
            }, 100);
        }
    });


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
            loadSlotsAndAppointments(selectedClinic,selectedDate);

            // loadSlotsAndAppointments();
        }
    });

    let selectedPatient = document.getElementById('patient-select').value;

    // Listen to patient select change
    document.getElementById('patient-select').addEventListener('change', function() {
        selectedPatient = this.value;

        // Reset calendar & reload appointments for new patient
        initCalendar();
        refreshCalendarEvents();
        loadSlotsAndAppointments(selectedClinic,selectedDate);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const clinicInput = document.getElementById('clinic-select');

        // Attach listener for when user clicks a clinic (value changes)
        clinicInput.addEventListener('change', function() {
            const selectedClinic = this.value;

            // Run your existing functions
            initCalendar();
            refreshCalendarEvents();
            loadSlotsAndAppointments(selectedClinic);

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
                loadSlotsAndAppointments(selectedClinic,selectedDate);
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

        window.calendarInstance = calendar; // make it globally accessible

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
        reportUrl: "{{ guard_route('reports.entire-day') }}",

        //set caledar days
        calendarDays: "{{ guard_route('calendar.days') }}",
		savecalendarDays: "{{ guard_route('calendar.store') }}",

        //move appoitnments
		appointmentsForDate: "{{ guard_route('appointments.forDate') }}",
        appointmentsAvailableSlots: "{{ guard_route('appointments.availableSlots') }}",
        appointmentsMove: "{{ guard_route('appointments.move') }}",

        //Clinic overview appoitnments
        appointmentsClinicOverviewCounts: "{{ guard_route('appointments.clinicOverviewCounts') }}",



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
        document.body.classList.add('mini-sidebar');

        initCalendar();
        loadSlotsAndAppointments(selectedClinic,selectedDate);
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
    function openEntireDayReport() {
        if (!selectedDate) {
            Swal.fire("Error", "Please select a date for the report.", "warning");
            return; // Stop if no date selected
        }
        window.open(`${routes.reportUrl}?date=${selectedDate}`, '_blank');
   }

</script>
<script>
let currentAppointmentId = null;
let currentPhoneNumber = null;

// When modal opens — fill with dynamic data
$('#whatsAppModal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    currentAppointmentId = button.data('appointment-id');
    currentPhoneNumber = button.data('patient-phone');
    const appointmentTime = button.data('appointment-time');
    const patientName = button.data('patient-name');

    const defaultMessage = `Hello ${patientName}, I wanted to confirm your appointment for ${appointmentTime}.`;
    $('#customMessage').val(defaultMessage);
});

// Send WhatsApp message via AJAX
function sendWhatsAppMessage() {
    // alert("sendWhatsAppMessage");
    const message = $('#customMessage').val();

    if (!currentPhoneNumber || !message.trim()) {
        alert('Phone number or message missing!');
        return;
    }

    $.ajax({
        url: "{{ guard_route('whatsapp.send.runtime') }}", // define route below
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            phone: currentPhoneNumber,
            message: message,
            appointment_id: currentAppointmentId
        },
        beforeSend: function() {
            $('#whatsAppModal .btn-success').prop('disabled', true).text('Sending...');
        },
        success: function(response) {
            $('#whatsAppModal').modal('hide');
            toastr.success('WhatsApp message sent successfully!');
        },
        error: function(xhr) {
            toastr.error('Failed to send message. Check console for details.');
            console.error(xhr.responseText);
        },
        complete: function() {
            $('#whatsAppModal .btn-success').prop('disabled', false).text('Send Message');
        }
    });
}

    // // Set up modal with data from the clicked button
    // $('#whatsAppModal').on('show.bs.modal', function (event) {
    //     var button = $(event.relatedTarget); // Button that triggered the modal
    //     currentAppointmentId = button.data('appointment-id');
    //     currentPhoneNumber = button.data('patient-phone');

    //     var defaultMessage = "Hello, I wanted to confirm my appointment for " + button.data('appointment-time');

    //     // Set the textarea content
    //     $('#customMessage').val(defaultMessage);
    // });

    // Function to send WhatsApp message with custom text
    // function sendWhatsAppMessage() {
    //     var message = $('#customMessage').val();
    //     var whatsAppUrl = "https://wa.me/" + currentPhoneNumber + "?text=" + encodeURIComponent(message);

    //     window.open(whatsAppUrl, "_blank");
    // }
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
        // loadSlotsAndAppointments(selectedClinic,selectedDate);

        loadSlotsAndAppointments();
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