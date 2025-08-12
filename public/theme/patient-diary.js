$(document).ready(function () {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
            loadSlotsAndAppointments(); // Reload table
          }
        } else {
          Swal.fire('Error', data.message || 'Something went wrong.', 'error');
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
                loadSlotsAndAppointments(); // Reload appointments
                refreshCalendarEvents();
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
            loadSlotsAndAppointments(); // Reload appointments
            refreshCalendarEvents();
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
});
