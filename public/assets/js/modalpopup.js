// Ensure this file runs after appConfig is defined
const statusAppointment = window.appConfig.statusAppointment || null;
const fetchAppointmentRoute = window.appConfig.fetchAppointmentRoute || null;
const storeHospitalAppointment = window.appConfig.storeHospitalAppointment || null;

//move appoitnments
const appointmentsForDate = window.appConfig.appointmentsForDate || null;
const appointmentsAvailableSlots = window.appConfig.appointmentsAvailableSlots || null;
const appointmentsMove = window.appConfig.appointmentsMove || null;
const csrfToken = window.appConfig.csrfToken || null;

//Set calendar Days
const calendarDays = window.appConfig.calendarDays || null;
const savecalendarDayUrl = window.appConfig.savecalendarDays || null;

// send whats app
const whatsappSend = window.appConfig.whatsappSend || null;
const destroyAppointment = window.appConfig.destroyAppointment || null;

// create letter
const patientDocumentCreateUrl = window.appConfig.patientDocumentCreateUrl || null;
 

/**
 * Fetch appointment data for Edit modal
 * @param {number|string} appointmentId
 */
async function fetchAppointmentData(appointmentId) {
    if (!fetchAppointmentRoute) {
        console.error('fetchAppointmentRoute is not defined!');
        return;
    }

    try {
        const url = fetchAppointmentRoute.replace('__ID__', appointmentId);

        const res = await fetch(url);
        if (!res.ok) throw new Error('Network response was not ok');

        const data = await res.json();
        console.log(data);
        openEditModal(data);       

    } catch (err) {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to fetch appointment data.',
            timer: 2500,
            showConfirmButton: false
        });
    }
}


/**
 * Fetch appointment data for Edit modal
 * @param {number|string} appointmentId
 */
async function fetchHospitalAppointmentData(appointmentId) {
    if (!fetchAppointmentRoute) {
        console.error('fetchAppointmentRoute is not defined!');
        return;
    }

    try {
        const url = fetchAppointmentRoute.replace('__ID__', appointmentId);

        const res = await fetch(url);
        if (!res.ok) throw new Error('Network response was not ok');

        const data = await res.json();
        openHospitalModal(data);
    } catch (err) {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to fetch appointment data.',
            timer: 2500,
            showConfirmButton: false
        });
    }
}

/**
 * Open Edit Appointment modal
 * @param {object} data - appointment data
 */
function openEditModal(data) {
    const modalEl = document.getElementById('bookAppointmentModal');
    const modal = new bootstrap.Modal(modalEl);

    document.getElementById('appointment-id').value = data.id || '';
    document.getElementById('modal-appointment-date').value = data.date || '';
    document.getElementById('start_time').value = data.start_time || '';
    document.getElementById('end_time').value = data.end_time || '';
    document.getElementById('appointment_note').value = data.appointment_note || '';
    document.getElementById('patient_need').value = data.patient_need || '';
    document.getElementById('appointment_type').value = data.appointment_type || '';
    document.getElementById('appointment-clinic-id').value = data.clinic_id || '';
    document.getElementById('modal-appointment-date').value = data.appointment_date || '';
    document.getElementById('sms_sent').checked = data.sms_sent == 1 || data.sms_sent === true;
    // Set patient select2 value
    $('#patient-id').val(data.patient_id || '').trigger('change');
    $('#patient-id').on('change', function () {
        const selectedOption = $(this).find(':selected');
        const dob = selectedOption.data('dob') || '';
        const consultant = selectedOption.data('consultant') || '';

        $('#modal-dob').val(dob);
        $('#clinic_consultant').val(consultant);
    });

    // Set DOB & Consultant
    const patientOption = $('#patient-id').find(`option[value="${data.patient_id}"]`);
    $('#modal-dob').val(patientOption.data('dob') || '');
    $('#clinic_consultant').val(patientOption.data('consultant') || '');

    // Setup the Create Letter button
    setupCreateLetterBtn();

    modal.show();
}

function openHospitalModal(data) {
    const modalEl = document.getElementById('manualBookingModal'); // your hospital modal
    const modal = new bootstrap.Modal(modalEl);

    // Set form values
    document.getElementById('hospital-appointment-id').value = data.id || '';
    document.getElementById('hospital_appointment_date').value = data.appointment_date || '';
    document.getElementById('hospital_start_time').value = data.start_time || '';
    document.getElementById('admission_date').value = data.admission_date || '';
    document.getElementById('admission_time').value = data.admission_time || '';
    document.getElementById('notes').value = data.appointment_note || '';
    document.getElementById('operation_duration').value = data.operation_duration || '';
    document.getElementById('ward').value = data.ward || '';
    document.getElementById('allergy').value = data.allergy || '';
    document.getElementById('hospital-clinic-id').value = data.clinic_id || '';
    document.getElementById('procedure_id').value = data.procedure_id || '';
    document.getElementById('hospital_sms_sent').checked = data.sms_sent == 1 || data.sms_sent === true;

    // Set patient select2
    $('#hospital-patient-id').val(data.patient_id || '').trigger('change');

    // Set consultant & DOB from patient data
    const selectedOption = $('#hospital-patient-id').find(`option[value="${data.patient_id}"]`);
    $('#hospital-dob').val(selectedOption.data('dob') || '');
    $('#consultant').val(selectedOption.data('consultant') || '');
    let finalUrl = storeHospitalAppointment;
    $('#manualBookingForm').attr('data-action', finalUrl);
    modal.show();
}

/**
 * Open Status Change modal
 * @param {number|string} appointmentId
 * @param {number|string} patientId
 * @param {string} currentStatus
 */
function openStatusModal(appointmentId, patientId, currentStatus) {
    if (!statusAppointment) {
        console.error('statusAppointment function not defined!');
        return;
    }

    $('#appointment_id').val(appointmentId);
    $('#patient_id').val(patientId);
    $('#appointment_status').val(currentStatus);

    // Generate form action dynamically
    const finalUrl = statusAppointment(appointmentId, patientId);
    $('#statusChangeForm').attr('data-action', finalUrl);

    // Show the modal
    $('#statusChangeModal').modal('show');
}

function openMoveAppointmentModal(event) {

    console.log(event);
    // Open modal
    const modal = new bootstrap.Modal(document.getElementById('moveAppointmentModal'));
    modal.show();

    // Clear first (your function)
    clearSelections();

    //-----------------------------------------------------
    // PREFILL "FROM" (LEFT SIDE)
    //-----------------------------------------------------
    const appointmentId = event.id; 

    // appointmentId = [event.id];   // store selected appointment ID
    const eventDate = event.start;       // FullCalendar gives moment object
    const formattedDate = eventDate.format("YYYY-MM-DD");

    // From Clinic
    document.getElementById('fromClinic').value = event.clinic_id;

    // From Date
    document.getElementById('fromDate').value = formattedDate;

    // Display info
    loadAppointmentsForDate(formattedDate, appointmentId);
   
    //-----------------------------------------------------
    // PREFILL "TO" (RIGHT SIDE)
    //-----------------------------------------------------

    // Same clinic initially
    document.getElementById('toClinic').value = event.clinic_id;

    // Same date initially
    document.getElementById('toDate').value = formattedDate;

    // Save target date globally
    selectedTargetDate = formattedDate;

    //-----------------------------------------------------
    // LOAD SLOTS IMMEDIATELY
    //-----------------------------------------------------
    loadAvailableTimeSlots(formattedDate);
}

function loadAppointmentsForDate(date, appointmentId) {
    const fromClinicSelect = document.getElementById('fromClinic');
    const displayContainer = document.getElementById('fromDateDisplay');

    displayContainer.innerHTML = `
            <div class="text-center py-3 text-muted">
                <div class="spinner-border text-primary me-2" role="status"></div>
                Loading appointments for ${date}...
            </div>
        `;

    fetch(appointmentsForDate, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify({
            date: date,
            clinic_id: fromClinicSelect.value,
            appointment_id: appointmentId,
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
    fetch(appointmentsAvailableSlots, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken
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
                    btn.addEventListener('click', function () {
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

function clearSelections() {
    document.getElementById('fromClinic').value = '';
    document.getElementById('fromDate').value = '';
    document.getElementById('toClinic').value = '';
    document.getElementById('toDate').value = '';
    document.getElementById('moveReason').value = '';
    document.getElementById('fromDateDisplay').innerHTML = '<div class="text-muted">No appointments selected</div>';
    document.getElementById('timeSlotsForTarget').innerHTML = '<div class="text-muted">Please select clinic and date</div>';
}

function setupCreateLetterBtn() {
    const $patientSelect = $('#patient-id'); // the <select>
    const $appointmentType = $('#appointment_type'); // appointment type <select>
    const createBtn = document.getElementById('create-letter-btn');

    // Initialize Select2 if not already
    if (!$patientSelect.hasClass("select2-hidden-accessible")) {
        $patientSelect.select2({ dropdownParent: $('#bookAppointmentModal') });
    }

    const updateCreateBtn = () => {
        const patientId = $patientSelect.val();
        const aptType = $appointmentType.val();
        if (patientId && aptType) {
            const url = new URL(patientDocumentCreateUrl.replace('__PATIENT_ID__', patientId));
            url.searchParams.set('appointment_type', aptType);
            createBtn.href = url.toString();
            createBtn.classList.remove('disabled');
        } else {
            createBtn.href = '#';
            createBtn.classList.add('disabled');
        }
    };
    
    // Run when patient or appointment type changes
    $patientSelect.on('change.createLetter', updateCreateBtn);
    $appointmentType.on('change', updateCreateBtn);
    
    // Run immediately if pre-selected
    updateCreateBtn();

    // Add click handler to include appointment type
    createBtn.addEventListener('click', function (e) {
        const aptType = $appointmentType.val();
        if (!aptType) {
            e.preventDefault();
            alert('Please select appointment type first');
            return;
        }

        // Append appointment_type as query param
        const url = new URL(this.href);
        url.searchParams.set('appointment_type', aptType);
        this.href = url.toString();
    });
}
// Submit the appointment move request
function submitMoveAppointment() {
    const reason = document.getElementById('moveReason').value;
    if (!reason.trim()) {
        Swal.fire("Error", "Please provide a reason for moving the appointment.", "warning");
        return;
    }

    // Fetch all checked appointments
    const checkedBoxes = document.querySelectorAll('input[name="appointment_ids[]"]:checked');
    const selectedAppointments = Array.from(checkedBoxes).map(cb => parseInt(cb.value));


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
    fetch(appointmentsMove, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken
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
                // Refresh FullCalendar events
                if ($('#calendar').fullCalendar) {
                    $('#calendar').fullCalendar('refetchEvents');
                }
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

// Handle target date change
const toDate = document.getElementById('toDate');
if (toDate) {
    $('#toDate').on("dp.change", function (e) {
        let selectedDate = e.date.format("YYYY-MM-DD");
        selectedTargetDate = selectedDate;
        console.log("Target Date selected:", selectedDate);

        // Load available slots for the selected TO date and TO clinic
        loadAvailableTimeSlots(selectedDate);
    });
}

// Handle target clinic change
const toClinic = document.getElementById('toClinic'); // <-- corrected from 'fromClinic'
if (toClinic) {
    toClinic.addEventListener('change', function () {
        const selectedTargetDateValue = document.getElementById('toDate').value;
        if (selectedTargetDateValue) {
            loadAvailableTimeSlots(selectedTargetDateValue);
        } else {
            document.getElementById('timeSlotsForTarget').innerHTML = `<div class="text-muted">Please select a date first.</div>`;
        }
    });
}
function openWhatsAppModal({ appointmentId, patientName, patientPhone, appointmentTime }) {
    if (!appointmentId || !patientPhone) {
        Swal.fire("Error", "Appointment ID or phone number missing!", "error");

        console.error("Appointment ID or phone number missing!");
        return;
    }

    window.currentAppointmentId = appointmentId;
    window.currentPhoneNumber = patientPhone;

    const defaultMessage = `Hello ${patientName}, I wanted to confirm your appointment for ${appointmentTime}.`;
    $('#customMessage').val(defaultMessage);

    const whatsAppModalEl = document.getElementById('whatsAppModal');
    if (whatsAppModalEl) {
        const modal = new bootstrap.Modal(whatsAppModalEl);
        modal.show();
    }
}

// Send WhatsApp message via AJAX
function sendWhatsAppMessage() {
    // alert("sendWhatsAppMessage");
    const message = $('#customMessage').val();

    if (!currentPhoneNumber || !message.trim()) {
        alert('Phone number or message missing!');
        return;
    }

    $.ajax({
        url: whatsappSend, // define route below
        type: "POST",
        data: {
            _token: csrfToken,
            phone: currentPhoneNumber,
            message: message,
            appointment_id: currentAppointmentId
        },
        beforeSend: function() {
            $('#whatsAppModal .btn-success').prop('disabled', true).text('Sending...');
        },
        success: function(response) {
            $('#whatsAppModal').modal('hide');
            Swal.fire("Success", "WhatsApp message sent successfully!",'success');
        },
        error: function(xhr) {
            Swal.fire("Error", "Failed to send message. Check console for details.",'error');
            console.error(xhr.responseText);
        },
        complete: function() {
            $('#whatsAppModal .btn-success').prop('disabled', false).text('Send Message');
        }
    });
}

function deleteAppointment(appointmentId, patientId, flag) {
    const url = destroyAppointment(appointmentId, patientId);
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
                    'X-CSRF-TOKEN': csrfToken,
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
                        if (flag == 1) {
                            location.reload();
                        } else {
                            appointmentManager.loadAppointments();
                        }
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
};

