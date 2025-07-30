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
<!-- Book Appointment Modal -->
<!-- Appointment Booking Modal -->
<div class="modal fade" id="bookAppointmentModal" tabindex="-1" aria-labelledby="bookAppointmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="bookAppointmentForm">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="bookAppointmentLabel">Book Appointment</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
  
          <div class="modal-body">
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
                <select class="form-select" id="appointment_type" name="appointment_type" required>
                  @foreach($appointment_types as $id => $value)
                    <option value="{{ $id }}">{{ $value }}</option>
                  @endforeach
                </select>
              </div>
  
              <div class="col-md-6">
                <label class="form-label">Appointment Date</label>
                <input type="text" class="form-control" name="appointment_date" id="modal-appointment-date" readonly>
              </div>
  
              <div class="col-md-4">
                <label class="form-label">Start Time</label>
                <input type="text" class="form-control" id="start_time" readonly>
              </div>
  
              <div class="col-md-4">
                <label class="form-label">End Time</label>
                <input type="text" class="form-control" id="end_time" readonly>
              </div>
  
              <div class="col-md-4">
                <label class="form-label">Slots</label>
                <div id="slot-options">
                  @for ($i = 1; $i <= 10; $i++)
                    <div class="form-check form-check-inline">
                      <input class="form-check-input apt-slot-radio" type="radio" name="apt_slots" id="slot{{ $i }}" value="{{ $i }}">
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
  
              {{-- <div class="col-md-6">
                <label class="form-label">Arrival Time</label>
                <input type="time" class="form-control" id="arrival_time" name="arrival_time">
              </div> --}}
            </div>
          </div>
  
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Confirm Booking</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  

@endsection
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<style>
   /* Calendar Container */
#calendar {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* slightly stronger shadow for better depth */
}

/* General calendar font and spacing */
.fc {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 14px;
    color: #333;
}

.fc-toolbar-title {
    font-size: 20px; /* slightly larger for emphasis */
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
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.fc-button:hover, .fc-button:focus {
    background-color: #138496;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
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
    background-color: #e6f2ff; /* light blue tint */
    color: #333; /* dark text */
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
    text-decoration: none; /* optional: removes underline */
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
    background-color: #e6f7ff; /* light blue */
}

.appointment-injection {
    background-color: #fff3cd; /* light yellow */
}

.appointment-medical_legal {
    background-color: #f0f0f0; /* light gray */
}

.appointment-post_op {
    background-color: #fce4ec; /* light pink */
}

.appointment-review_visit {
    background-color: #d4edda; /* light green */
}

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
        fetchAppointments: "{{ route('patients.appointments.byDate', ['patient' => $patient->id]) }}"
        , storeAppointment: "{{ route('patients.appointments.store', ['patient' => $patient->id]) }}"
    };
    const slotDuration = window.currentSlotDuration || 15;


    // Bind radio button listener after modal is shown
    function setupSlotChangeHandler(startTime) {
        const radios = document.querySelectorAll('.apt-slot-radio');
        radios.forEach(radio => {
            radio.addEventListener('change', function () {
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
        const appointments = data.appointments;
        const clinic = data.clinic;



        const dayName = new Date(selectedDate).toLocaleDateString('en-US', {
            weekday: 'short'
        }).toLowerCase();
        window.currentSlotDuration = parseInt(clinic[`${dayName}_interval`] || 15); // globally accessible

        const start = clinic[`${dayName}_start_am`];
        const end = clinic[`${dayName}_finish_am`];
        const interval = parseInt(clinic[`${dayName}_interval`] || 15);
        // Format YYYY-MM-DD manually to avoid UTC shift
        const [year, month, day] = selectedDate.split('-');
        document.getElementById('selected-date-display').innerText =
            `Appointments for ${day}/${month}/${year}`;

        // document.getElementById('selected-date-display').innerText =
        //     `Appointments for ${new Date(selectedDate).toLocaleDateString()}`;
        const tbody = document.getElementById('slot-body');
        tbody.innerHTML = '';

        if (!start || !end) {
            tbody.innerHTML = `<tr><td colspan="6">Clinic is closed on this day.</td></tr>`;
            return;
        }

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

            let rowClass = '';
            if (existing && existing.appointment_type?.value) {
                const key = existing.appointment_type.value.trim().toLowerCase().replace(/\s+/g, '_');
                rowClass = `appointment-${key}`;
            }

            row.className = rowClass;
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

    document.getElementById('bookAppointmentForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;
        const data = {
            appointment_type: form.appointment_type.value,
            appointment_date: form.appointment_date.value,
            start_time: form.start_time.value,
            end_time: form.end_time.value,
            patient_need: form.patient_need.value,
            appointment_note: form.appointment_note.value,
            clinic_id: selectedClinic,
            apt_slots: 1 // Default 1 slot
        };

        try {
            const response = await fetch(routes.storeAppointment, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (result.success) {
                alert('Appointment booked successfully!');
                bootstrap.Modal.getInstance(document.getElementById('bookAppointmentModal')).hide();
                loadSlotsAndAppointments(); // Refresh calendar slots
            } else {
                alert(result.message || 'Failed to book appointment.');
            }
        } catch (error) {
            console.error('Booking error:', error);
            alert('Something went wrong. Please try again.');
        }
    });

    function bookSlot(startTime) 
    {
        const endTime = addMinutesToTime(startTime, 15); // Default 15 minutes (or based on slots)
        const patientName = "{{ $patient->first_name }} {{ $patient->surname }}";

        document.getElementById('modal-patient-name').value = patientName;
        document.getElementById('modal-dob').value = "{{$patient->dob}}";
        // Set modal hidden input values
        document.getElementById('start_time').value = startTime;
        document.getElementById('end_time').value = endTime;
        document.getElementById('modal-appointment-date').value = selectedDate;

        // Optionally clear fields
        document.getElementById('appointment_type').value = '';
        document.getElementById('patient_need').value = '';
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