$(document).ready(function () {
  function handleErrors(xhr, form) {
    if (xhr.status === 422) {
      const errors = xhr.responseJSON.errors;
      for (const field in errors) {
        const input = form.find(`[name="${field}"]`);
        input.addClass('is-invalid');
        input.next('.text-danger').remove(); // remove old errors if any
        input.after(`<div class="text-danger">${errors[field][0]}</div>`);
      }
    } else {
      Swal.fire('Error', 'Something went wrong', 'error');
    }
  }

  
    $('body').on('submit', '.validate-form', function (e) {
      e.preventDefault();
  
      const form = $(this);
      const url = form.attr('action');
      const method = form.attr('method') || 'POST';
      const formData = new FormData(this);
  
      // Clear previous errors
      form.find('.is-invalid').removeClass('is-invalid');
      form.find('.text-danger').remove();
  
      $.ajax({
        url,
        type: method,
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          Swal.fire({
            icon: 'success',
            title: 'Saved',
            text: response.message || 'Form submitted successfully!',
            timer: 2000,
            showConfirmButton: false
          });
  
          setTimeout(function () {
            if (response.redirect) {
              window.location.href = response.redirect;
            }
          }, 2000);
        },
        error: function (xhr) {
          handleErrors(xhr, form); 
        }
      });
    });
  });


  
const imageInput = document.getElementById('image');
if (imageInput) {
  imageInput.addEventListener('change', function (e) {
    let img = document.getElementById('preview-img');
    if (!img) {
      img = document.createElement('img');
      img.id = 'preview-img';
      img.className = 'img-thumbnail mt-2';
      img.style.maxHeight = '150px';
      this.after(img);
    }

    const reader = new FileReader();
    reader.onload = e => img.src = e.target.result;
    reader.readAsDataURL(this.files[0]);
  });
}

// form-validation.js

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('bookAppointmentForm');
  if (!form) return;
  form.addEventListener('submit', async function (e) {
      e.preventDefault();

      const id = document.getElementById('appointment-id').value || '';
      const patient_input = document.getElementById('appointment-patient-id');
      var patientId = '';
      if (patient_input) {
        patientId = document.getElementById('appointment-patient-id').value || '';
      } else {
        patientId = document.getElementById('patient-id').value || '';
      }
      
      const selectedClinic = document.getElementById('clinic-select')?.value || null;
      
      const data = {
          appointment_id: id,
          patient_id: patientId,
          appointment_type: form.appointment_type.value,
          appointment_date: form.appointment_date.value,
          start_time: form.start_time.value,
          end_time: form.end_time.value,
          patient_need: form.patient_need.value,
          appointment_note: form.appointment_note.value,
          clinic_id: selectedClinic,
          apt_slots: parseInt(form.querySelector('input[name="apt_slots"]:checked')?.value || 1),
      };

      try {
          const response = await fetch(form.dataset.action, {
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
              bootstrap.Modal.getInstance(document.getElementById('bookAppointmentModal')).hide();
              if (typeof loadSlotsAndAppointments === 'function') {
                  loadSlotsAndAppointments();
              }
              if (typeof refreshCalendarEvents === 'function') {
                refreshCalendarEvents();
              }
              if (typeof initCalendar === 'function') {
                initCalendar();
              }
          } else if (response.status === 422 && result.errors) {
              handleValidationErrors(result.errors, form);
          } else {
              alert(result.message || 'Operation failed.');
          }
      } catch (error) {
          console.error(error);
          alert('Something went wrong. Please try again.');
      }
  });
});

/**
* Display validation errors beside form fields
*/
function handleValidationErrors(errors, form) {
  form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
  form.querySelectorAll('.invalid-feedback, .text-danger').forEach(el => el.remove());

  for (const [field, messages] of Object.entries(errors)) {
      const input = form.querySelector(`[name="${field}"]`);
      if (input) {
          input.classList.add('is-invalid');
          const errorDiv = document.createElement('div');
          errorDiv.classList.add('invalid-feedback');
          errorDiv.textContent = messages[0];
          input.parentNode.appendChild(errorDiv);
      }
  }
}





