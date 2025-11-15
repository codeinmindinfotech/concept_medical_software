$(document).ready(function () {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $('#patient-id').on('change', function () {
        const selectedOption = $(this).find(':selected');
        const dob = selectedOption.data('dob') || '';
        const consultant = selectedOption.data('consultant') || '';

        $('#modal-dob').val(dob);
        $('#clinic_consultant').val(consultant);
    });
    $('#bookAppointmentModal .select2').select2({
      dropdownParent: $('#bookAppointmentModal') // Replace with modal ID if needed
    });

    $('#hospital-patient-id').on('change', function () {
        const selectedOption = $(this).find(':selected');
        const dob = selectedOption.data('dob') || '';
        const consultant = selectedOption.data('consultant') || '';

        $('#hospital-dob').val(dob);
        $('#consultant').val(consultant);
    });
    $('#manualBookingModal .select2').select2({
      dropdownParent: $('#manualBookingModal')
    });
   

  document.getElementById('manualBookingForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const flag = document.getElementById('flag').value;
    const url = this.getAttribute('data-action');
    fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Success', 'Appointment booked.', 'success');
          bootstrap.Modal.getInstance(document.getElementById('manualBookingModal')).hide();
          if (flag == 1) {
            location.reload();
          } else {
            if (typeof loadSlotsAndAppointments === 'function') {
              loadSlotsAndAppointments();
            }
          }
        } else if (data.errors) {
          console.log(typeof handleValidationErrors);

          handleValidationErrors(data.errors, form);
      } else {
          Swal.fire('Error', data.message || 'Something went hhh wrong.'+data.status, 'error');
        }
      })
      .catch(error => {
        console.error(error);
        Swal.fire('Error', 'Something went wrong.', 'error');
      });
  });

  window.deleteAppointment = function (appointmentId, patientId, flag) {
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

  document.getElementById('statusChangeForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const flag = document.getElementById('flag').value;
    const appointment_status = document.getElementById('appointment_status').value;
    const url = this.getAttribute('data-action');
    // const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
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

  window.openStatusModal = function (appointmentId, patientId, currentStatus) {
     $('#appointment_id').val(appointmentId);
      $('#patient_id').val(patientId);
      $('#appointment_status').val(currentStatus);

      let finalUrl = routes.statusAppointment(appointmentId,patientId);
      $('#statusChangeForm').attr('data-action', finalUrl);

      $('#statusChangeModal').modal('show');
  };

  // window.openClinicOverviewCountModal = function () {
  
  // }
// function openClinicOverviewCountModal() {
//     if (!selectedDate) {
//         Swal.fire('Warning', 'Please select a date first.', 'info');
//         return;
//     }

//     const modalBody = document.getElementById('clinic-overview-count-body');
//     const dateText = document.getElementById('clinic-count-date');
//     dateText.textContent = selectedDate;
//     modalBody.innerHTML = '<p class="text-muted">Loading data...</p>';

//     fetch(`{{ route('appointments.clinicOverviewCounts') }}`, {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': '{{ csrf_token() }}'
//         },
//         body: JSON.stringify({ date: selectedDate })
//     })
//     .then(res => res.text())
//     .then(html => {
//         modalBody.innerHTML = html;
//         const modal = new bootstrap.Modal(document.getElementById('clinicOverviewCountModal'));
//         modal.show();
//     })
//     .catch(err => {
//         console.error(err);
//         modalBody.innerHTML = '<p class="text-danger">Failed to load data.</p>';
//     });
// }

});
