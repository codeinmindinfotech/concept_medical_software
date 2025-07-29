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
            <h3>Appointment Scheduler for <strong>{{ $patient->surname }}</strong></h3>
        
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
                                <option value="{{ $clinic->id }}" @if ($loop->first) selected @endif>{{ $clinic->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div id="selected-date-display" class="mb-3 fw-bold"></div>
                    <div id="slot-table" style="max-height: 700px; overflow-y: auto;">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Type</th>
                                    <th>Patient</th>
                                    <th>DOB</th>
                                    <th>Appointment</th>
                                </tr>
                            </thead>
                            <tbody id="slot-body">
                                <tr><td colspan="6">Select a clinic and a date</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@latest/main.min.css" rel="stylesheet">
<style>
    
</style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
    let calendar;
    let selectedClinic = document.getElementById('clinic-select').value || null;
    let selectedDate = null;

    document.getElementById('clinic-select').addEventListener('change', function() {
        selectedClinic = this.value;
        initCalendar();
    });

    function initCalendar() {
        if (calendar) calendar.destroy();

        calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: 'dayGridMonth',
            height: 'auto', // fit inside container
            aspectRatio: 1.2, // Helps layout evenly in tight containers
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            titleFormat: { year: 'numeric', month: 'short' }, // e.g., Jul 2025
            buttonText: {
                today: 'Today'
            },
            dateClick: function(info) {
                selectedDate = info.dateStr;
                loadSlotsAndAppointments();
            },

            datesSet: function(viewInfo) {
                // If no date selected yet, pick today
                if (!selectedDate) {
                    selectedDate = new Date().toISOString().split('T')[0];
                    loadSlotsAndAppointments();
                }
            }
        });

        calendar.render();
    }

    const patientId = "{{ $patient->id }}";

    const routes = {
        fetchAppointments: "{{ route('patients.appointments.byDate', ['patient' => $patient->id]) }}"
        , storeAppointment: "{{ route('patients.appointments.store', ['patient' => $patient->id]) }}"
    };
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
        const appointments = data.appointments;
        const clinic = data.clinic;

        const dayName = new Date(selectedDate).toLocaleDateString('en-US', {
            weekday: 'short'
        }).toLowerCase();

        const start = clinic[`${dayName}_start_am`];
        const end = clinic[`${dayName}_finish_am`];
        const interval = parseInt(clinic[`${dayName}_interval`] || 15);
        document.getElementById('selected-date-display').innerText =
            `Appointments for ${new Date(selectedDate).toLocaleDateString()}`;
        const tbody = document.getElementById('slot-body');
        tbody.innerHTML = '';

        // if (!start || !end) {
        //     tbody.innerHTML = `<tr><td colspan="2">Clinic is closed on this day.</td></tr>`;
        //     return;
        // }

        // if (!appointments.length) {
        //     tbody.innerHTML = `<tr><td colspan="6">No appointments found for the selected date.</td></tr>`;
        //     return;
        // }

        const slots = generateTimeSlots(start, end, interval);

        console.log(slots);
        if (!slots.length) {
            tbody.innerHTML = `<tr><td colspan="6">Clinic is closed or time not configured.</td></tr>`;
            return;
        }

        slots.forEach(time => {
            const existing = appointments.find(app => app.start_time.slice(0, 5) === time);
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${time}</td>
                <td>${existing ? existing.appointment_type?.value || 'N/A' : ''}</td>
                <td>${existing ? existing.appointment_type?.value || '-' : ''}</td>
                <td>${existing && existing.patient ? `${existing.patient.first_name} ${existing.patient.surname}` : ''}</td>
                <td>${existing && existing.patient ? existing.patient.dob : ''}</td>
                <td>
                    ${existing ? '-' : `<button class="btn btn-sm btn-primary" onclick="bookSlot('${time}')">Book</button>`}
                </td>
            `;

            tbody.appendChild(row);
        });
    }

    function generateTimeSlots(start, end, interval) {
        let slots = [];
        let [sh, sm] = start.split(':').map(Number);
        let [eh, em] = end.split(':').map(Number);

        while (sh < eh || (sh === eh && sm < em)) {
            let time = `${String(sh).padStart(2, '0')}:${String(sm).padStart(2, '0')}`;
            slots.push(time);
            sm += interval;
            if (sm >= 60) {
                sm -= 60;
                sh += 1;
            }
        }

        return slots;
    }

    async function bookSlot(startTime) {
        const patientId = document.getElementById('patient-id').value;
        const endTime = addMinutesToTime(startTime, 15); // or based on apt_slots
        const confirm = window.confirm(`Book appointment at ${startTime}?`);
        if (!confirm) return;

        const res = await fetch(routes.storeAppointment, {
            method: 'POST'
            , headers: {
                'Content-Type': 'application/json'
                , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
            , body: JSON.stringify({
                clinic_id: selectedClinic
                , appointment_date: selectedDate
                , start_time: startTime
                , end_time: endTime
                , apt_slots: 1 // default to 1
            })
        });

        const result = await res.json();
        if (result.success) {
            alert('Appointment booked!');
            loadSlotsAndAppointments();
        } else {
            alert('Failed to book appointment.');
        }
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
