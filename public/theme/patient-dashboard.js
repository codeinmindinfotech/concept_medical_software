$(document).ready(function () {
  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  const patientId = $('#patientId').val();

  // Load waiting lists
  function loadWaitingLists() {
    $.get(`/patients/${patientId}/waiting-lists`, function (data) {
      $('#WaitingListsList').html(data);
    });
  }
  $('#editVisitModal, #addVisitModal').on('shown.bs.modal', function () {
    $(this).find('.select2').each(function () {
      $(this).select2({
        dropdownParent: $(this).closest('.modal'),
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: '-- Select --',
        allowClear: true
      });
    });

    flatpickr("#editVisitDate", {
      dateFormat: "Y-m-d"
    });
  
    flatpickr("#visit_date", {
      dateFormat: "Y-m-d"
    });
  });

  // Add new visit
  $(document).on('submit', '#addVisitModal form', function (e) {
    e.preventDefault();
    const form = $(this);
    const url = form.attr('action'); 
    $.ajax({
      url: url,
      type: 'POST',
      data: new FormData(this),
      contentType: false,
      processData: false,
      success: function (response) {
        if (response.success) {
          Swal.fire({
            icon: 'success',
            title: 'Saved',
            text: response.message,
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            $('#addVisitModal').modal('hide');
            form[0].reset();
            loadWaitingLists();
          });
        }
      },
      error: function (xhr) {
        handleErrors(xhr, form);
      }
    });
  });

  // Edit visit

  $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    $.get(`/patients/${patientId}/waiting-lists/${id}`, function (data) {
      $('#editVisitDate').val(data.visit_date);
      $('#editNote').val(data.consult_note);
      $('#editCategory').val(data.category_id);
      $('#editClinic').val(data.clinic_id);
      $('#editVisitModal').data('id', id);
      const modal = new bootstrap.Modal(document.getElementById('editVisitModal'));
      modal.show(); // Proper Bootstrap 5 way
    });
  });

  $('#editVisitForm').on('submit', function (e) {
    e.preventDefault();
    const id = $('#editVisitModal').data('id');
    const formData = new FormData(this);
    formData.append('_method', 'PUT');
    $.ajax({
      url: `/patients/${patientId}/waiting-lists/${id}`,
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function () {
        $('#editVisitModal').modal('hide');
        loadWaitingLists();
      },
      error: function (xhr) {
        alert('Error updating: ' + xhr.responseJSON.message);
      }
    });
  });

  // Delete visit
  $(document).on('click', '.delete-btn', function () {
    const id = $(this).data('id');
    if (!confirm('Are you sure you want to delete this visit?')) return;
    $.ajax({
      url: `/patients/${patientId}/waiting-lists/${id}`,
      method: 'DELETE',
      success: function () {
        $(`tr[data-id="${id}"]`).remove();
      },
      error: function (xhr) {
        alert('Error deleting: ' + (xhr.responseJSON?.message || 'Unknown error'));
      }
    });
  });

  // Handle validation errors
  function handleErrors(xhr, form) {
    if (xhr.status === 422) {
      let errors = xhr.responseJSON.errors;
      let firstError = null;
      $.each(errors, function (key, messages) {
        let input = form.find(`[name="${key}"]`);
        if (input.length > 0) {
          input.addClass('is-invalid');
          input.after(`<div class="text-danger">${messages[0]}</div>`);
          if (!firstError) firstError = input;
        }
      });
      if (firstError) {
        $('html, body').animate({
          scrollTop: firstError.offset().top - 100
        }, 500);
      }
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Something went wrong. Please try again.',
      });
    }
  }
});