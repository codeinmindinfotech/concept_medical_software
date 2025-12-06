// Ensure this file runs after appConfig is defined
const statusAppointment = window.appConfig.statusAppointment || null;
const fetchAppointmentRoute = window.appConfig.fetchAppointmentRoute || null;

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
