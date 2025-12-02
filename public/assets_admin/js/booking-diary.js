(function () {
  "use strict";

  // LIVE FIELD VALIDATION FIX
  document.addEventListener("input", function (event) {
    let input = event.target;

    if (!input.form || !input.form.classList.contains("needs-validation")) return;

    let nextError = input.nextElementSibling;
    let hasServerError = nextError && nextError.classList.contains("text-danger");

    let isValid = input.checkValidity();

    if (isValid) {
      input.classList.remove("is-invalid");
      if (hasServerError) nextError.remove();
      input.classList.add("is-valid");
    } else {
      input.classList.remove("is-valid");
      input.classList.add("is-invalid");
    }
  });

  // HANDLE SERVER ERRORS
  function handleServerErrors(errors, form) {
    for (let field in errors) {
      let input = form.querySelector(`[name="${field}"]`);

      if (input) {
        input.classList.add("is-invalid");

        let errorDiv = document.createElement("div");
        errorDiv.classList.add("text-danger");
        errorDiv.innerText = errors[field][0];

        input.insertAdjacentElement("afterend", errorDiv);
      }
    }

    Swal.fire("Validation Error", "Please fix the highlighted fields.", "warning");
  }

  // FORM SUBMIT HANDLER FOR needs-validation FORMS
  window.addEventListener("load", function () {
    const forms = document.getElementsByClassName("needs-validation");

    // BOOK APPOINTMENT FORM SUBMISSION (compatible)
    const bookForm = document.getElementById('bookAppointmentForm');
    if (bookForm) {
      bookForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Clear previous errors
        bookForm.querySelectorAll(".is-invalid").forEach(i => i.classList.remove("is-invalid"));
        bookForm.querySelectorAll(".text-danger").forEach(e => e.remove());

        const flag = document.getElementById('flag')?.value || '';
        const id = document.getElementById('appointment-id')?.value || '';
        const patient_input = document.getElementById('appointment-patient-id');
        const patientId = patient_input ? patient_input.value : (document.getElementById('patient-id')?.value || '');
        const selectedClinic = document.getElementById('appointment-clinic-id')?.value ||
          document.getElementById('clinic-select')?.value || null;

        const data = {
          appointment_id: id,
          patient_id: patientId,
          appointment_type: bookForm.appointment_type.value,
          appointment_date: bookForm.appointment_date.value,
          start_time: bookForm.start_time.value,
          end_time: bookForm.end_time.value,
          patient_need: bookForm.patient_need.value,
          appointment_note: bookForm.appointment_note.value,
          clinic_id: selectedClinic,
          apt_slots: parseInt(bookForm.querySelector('input[name="apt_slots"]:checked')?.value || 1)
        };

        try {
          const response = await fetch(bookForm.dataset.action, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
          });

          const result = await response.json();

          if (response.ok && result.success) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: id ? 'Appointment updated successfully!' : 'Appointment booked successfully!',
              timer: 2000,
              showConfirmButton: false
            });

            bootstrap.Modal.getInstance(document.getElementById('bookAppointmentModal'))?.hide();

            if (flag == 1) {
              location.reload();
            } else {
              if (typeof loadSlotsAndAppointments === 'function') loadSlotsAndAppointments();
              if (typeof refreshCalendarEvents === 'function') refreshCalendarEvents();
              if (typeof initCalendar === 'function') initCalendar();
            }

          } else if (response.status === 422 && result.errors) {
            handleServerErrors(result.errors, bookForm);
          } else {
            Swal.fire("Error", result.message || 'Operation failed.', "warning");
          }
        } catch (error) {
          console.error(error);
          Swal.fire("Error", 'Something went wrong. Please try again.', "warning");
        }
      });
    }



  });


})();

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