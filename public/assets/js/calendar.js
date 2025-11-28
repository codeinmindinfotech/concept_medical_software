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

    fetch(routes.savecalendarDays, {
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": window.Laravel.csrfToken   // 🔥 FIXES 419 ERROR
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

let selectedAppointments = [];
let selectedTargetDate = null;

$('#fromDate').on("dp.change", function (e) {
    let selectedDate = e.date.format("YYYY-MM-DD");
    loadAppointmentsForDate(selectedDate);
});


const fromClinic = document.getElementById('fromClinic');
if (fromClinic) {
    fromClinic.addEventListener('change', function () {
        const selectedDate = document.getElementById('fromDate').value;
        if (selectedDate) {
            loadAppointmentsForDate(selectedDate);
        } else {
            document.getElementById('fromDateDisplay').innerHTML = `<div class="text-muted">Please select a date first.</div>`;
        }
    });

}
$('#toDate').on("dp.change", function (e) {
    let selectedDate = e.date.format("YYYY-MM-DD");
    selectedTargetDate = selectedDate;
    console.log("Target Date selected:", selectedDate);

    // Load available slots
    loadAvailableTimeSlots(selectedDate);
});

const toClinic = document.getElementById('fromClinic');
if (toClinic) {
    toClinic.addEventListener('change', function () {
        const selectedTargetDate = document.getElementById('toDate').value;
        if (selectedTargetDate) {
            loadAvailableTimeSlots(selectedTargetDate);
        } else {
            document.getElementById('timeSlotsForTarget').innerHTML = `<div class="text-muted">Please select a date first.</div>`;
        }
    });
}

function loadAppointmentsForDate(date) {
    const fromClinicSelect = document.getElementById('fromClinic');
    const displayContainer = document.getElementById('fromDateDisplay');

    displayContainer.innerHTML = `
            <div class="text-center py-3 text-muted">
                <div class="spinner-border text-primary me-2" role="status"></div>
                Loading appointments for ${date}...
            </div>
        `;

    fetch(routes.appointmentsForDate, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": window.Laravel.csrfToken
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
    fetch(routes.appointmentsAvailableSlots, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": window.Laravel.csrfToken
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

document.addEventListener("change", function (e) {
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
    fetch(routes.appointmentsMove, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": window.Laravel.csrfToken
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
                if (typeof loadSlotsAndAppointments === 'function') {
                    loadSlotsAndAppointments();
                }
                if (typeof refreshCalendarEvents === 'function') {
                    refreshCalendarEvents();
                }
                if (typeof initCalendar === 'function') {
                    initCalendar();
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

function openClinicOverviewCountModal() {
    const modalBody = document.getElementById('clinic-overview-count-body');
    modalBody.innerHTML = '<p class="text-muted">Loading data...</p>';

    fetch(routes.appointmentsClinicOverviewCounts, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.Laravel.csrfToken
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

document.getElementById('statusChangeForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const flag = document.getElementById('flag').value;
    const appointment_status = document.getElementById('appointment_status').value;
    const url = this.getAttribute('data-action');
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': window.Laravel.csrfToken,
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
                if (flag == 1) {
                    location.reload();
                } else {
                    if (typeof loadSlotsAndAppointments === 'function') {
                        loadSlotsAndAppointments();
                    }
                    if (typeof refreshCalendarEvents === 'function') {
                        refreshCalendarEvents();
                    }
                }
            } else {
                Swal.fire('Error', data.message || 'Failed to update status.', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error', 'Something went wrong.', 'error');
        });
});

function deleteAppointment(appointmentId, patientId, flag) {
    const url = routes.destroyAppointment(appointmentId, patientId);
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
                    'X-CSRF-TOKEN': window.Laravel.csrfToken,
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
                            if (typeof loadSlotsAndAppointments === 'function') {
                                loadSlotsAndAppointments();
                            }
                            if (typeof refreshCalendarEvents === 'function') {
                                refreshCalendarEvents();
                            }
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

function openStatusModal(appointmentId, patientId, status) {
    $('#appointment_id').val(appointmentId);
    $('#patient_id').val(patientId);
    $('#appointment_status').val(status);

    let finalUrl = routes.statusAppointment(appointmentId, patientId);
    $('#statusChangeForm').attr('data-action', finalUrl);

    $('#statusChangeModal').modal('show');
}