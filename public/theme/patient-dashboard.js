function handleErrors(xhr, form) {
  if (xhr.status === 422) {
    const errors = xhr.responseJSON.errors;
    let firstError = null;

    $.each(errors, function (key, messages) {
      const input = form.find(`[name="${key}"]`);
      if (input.length) {
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
      text: 'Something went wrong. Please try again.'
    });
  }
}

$(document).ready(function () {
  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  const patientId = $('#patientId').val();

  function routeUrl(template, id) {
    return template.replace('__ID__', id);
  }

  const initSelect2 = (container) => {
    container.find('.select2').each(function () {
      $(this).select2({
        dropdownParent: $(this).closest('.modal'),
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: '-- Select --',
        allowClear: true
      });
    });
  };

  const initFlatpickr = () => {
    ['#procedure_date', '#admission_date', '#discharge_date', '#editVisitDate', '#visit_date'].forEach(selector => {
      if ($(selector).length) flatpickr(selector, { dateFormat: "Y-m-d" });
    });
  };

  const initModalFields = (modalSelector) => {
    const $modal = $(modalSelector);
    initSelect2($modal);
    initFlatpickr();
  };

  const initDataTableGeneric = (tableId, unsortableColumnIndex) => {
    if ($.fn.DataTable.isDataTable(tableId)) {
      $(tableId).DataTable().destroy();
    }
    return $(tableId).DataTable({
      paging: true,
      searching: true,
      ordering: true,
      info: true,
      lengthChange: true,
      pageLength: 10,
      columnDefs: [
        { targets: unsortableColumnIndex, orderable: false }
      ]
    });
  };

  let dataTableInstance;
  const loadWaitingLists = () => {
    $.get(window.routes.index, function (data) {
      $('[data-pagination-container]').html($(data).find('[data-pagination-container]').html());
      dataTableInstance = initDataTableGeneric('#WaitingTable', 4);
    });
  };

  let dataTableInstanceNote;
  const loadFeeNoteLists = () => {

    $.get(window.routes.note_index, function (data) {
      $('#FeeNoteListContainer').html($(data).find('#FeeNoteListContainer').html());
      dataTableInstanceNote = initDataTableGeneric('#FeeNoteTable', 6);
    });
  };

  $('#editVisitModal, #addVisitModal, #feeNoteModal').on('shown.bs.modal', function () {
    initModalFields(this);
  });

   loadWaitingLists();
   loadFeeNoteLists();

  $(document).on('submit', '#addVisitModal form', function (e) {
    e.preventDefault();
    const form = $(this);
    $.ajax({
      url: form.attr('action'),
      type: 'POST',
      data: new FormData(this),
      contentType: false,
      processData: false,
      success(response) {
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
      error(xhr) {
        handleErrors(xhr, form);
      }
    });
  });

  $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const fetchUrl = routeUrl(window.routes.show, id);
    const updateUrl = routeUrl(window.routes.update, id);

    $.get(fetchUrl, function (data) {
      $('#editVisitDate').val(data.visit_date);
      $('#editNote').val(data.consult_note);
      $('#editCategory').val(data.category_id);
      $('#editClinic').val(data.clinic_id);
      $('#editVisitForm').attr('action', updateUrl);
      new bootstrap.Modal(document.getElementById('editVisitModal')).show();
    });
  });

  $('#editVisitForm').on('submit', function (e) {
    e.preventDefault();
    const form = $(this);
    const url = form.attr('action');
    const formData = new FormData(this);
    formData.append('_method', 'PUT');

    $.ajax({
      url: url,
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: () => {
        $('#editVisitModal').modal('hide');
        loadWaitingLists();
      },
      error(xhr) {
        alert('Error updating: ' + (xhr.responseJSON?.message || 'Unknown error'));
      }
    });
  });

  $(document).on('click', '.delete-btn', function () {
    const id = $(this).data('id');
    const url = routeUrl(window.routes.destroy, id);

    Swal.fire({
      title: 'Are you sure?',
      text: 'This visit will be permanently deleted.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then(result => {
      if (result.isConfirmed) {
        $.ajax({
          url,
          method: 'DELETE',
          success: () => {
            $(`tr[data-id="${id}"]`).remove();
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'The visit has been deleted.',
              timer: 1500,
              showConfirmButton: false
            });
          },
          error(xhr) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: xhr.responseJSON?.message || 'Something went wrong while deleting.'
            });
          }
        });
      }
    });
  });

  $(document).on('click', '#addFeeNoteBtn', function () {
    clearFeeNoteForm();
    $('#feeNoteModalLabel').text('Add Fee Note');
    new bootstrap.Modal(document.getElementById('feeNoteModal')).show();
  });

  $(document).on('click', '.editFeeNoteBtn', function () {
    const feeNote = $(this).data('note');
    $('#feeNoteModalLabel').text('Edit Fee Note');
    $('#feeNoteModal').one('shown.bs.modal', () => fillFeeNoteForm(feeNote));
    new bootstrap.Modal(document.getElementById('feeNoteModal')).show();
  });

  $('#qty, #charge_gross, #reduction_percent, #charge_net, #vat_rate_percent').on('input', calculateLineTotal);

    // Delete fee note
    $(document).on('click', '.deleteFeeNote', function () {
      const noteId = $(this).data('id');
      const url = window.routes.note_destroy.replace('__ID__', noteId);
  
      Swal.fire({
        title: 'Are you sure?',
        text: 'This fee note will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: url,
            method: 'DELETE',
            success: function () {
              $(`tr[data-id="${noteId}"]`).remove();
              Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: 'The fee note has been deleted.',
                timer: 1500,
                showConfirmButton: false
              });
            },
            error: function (xhr) {
              Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: xhr.responseJSON?.message || 'Something went wrong!'
              });
            }
          });
        }
      });
    });

  $('#feeForm').on('submit', function (e) {
    e.preventDefault();
    const form = $(this);
    const id = $('#fee_note_id').val();
    const url = id
      ? window.routes.note_update.replace('__ID__', id)
      : window.routes.note_create.replace('__ID__');
    const method = id ? 'PUT' : 'POST';

    $.ajax({
      url,
      type: method,
      data: form.serialize(),
      success() {
        $('#feeNoteModal').modal('hide');
        Swal.fire({
          icon: 'success',
          title: 'Saved',
          text: 'Fee Note saved successfully!',
          timer: 2000,
          showConfirmButton: false
        });
        loadFeeNoteLists();
      },
      error(xhr) {
        handleErrors(xhr, form);
      }
    });
  });

  function clearFeeNoteForm() {
    $('#feeForm')[0].reset();
    $('#fee_note_id').val('');
    $('#feeForm .is-invalid').removeClass('is-invalid');
    $('#feeForm .text-danger').remove();
    $('#feeForm .select2').val(null).trigger('change');
  }

  function fillFeeNoteForm(note) {
    $('#feeForm .is-invalid, #feeForm .text-danger').remove();
    $('#fee_note_id').val(note.id);
    $('#procedure_date').val(note.procedure_date);
    $('#admission_date').val(note.admission_date);
    $('#discharge_date').val(note.discharge_date);
    $('#charge_gross').val(note.charge_gross);
    $('#reduction_percent').val(note.reduction_percent);
    $('#charge_net').val(note.charge_net);
    $('#vat_rate_percent').val(note.vat_rate_percent);
    $('#line_total').val(note.line_total);
    $('#comment').val(note.comment);
    $('#qty').val(note.qty);

    $('#clinic_id, #consultant_id, #narrative, #chargecode_id').each(function () {

      const fieldId = $(this).attr('id');
      const value = note.chargecode_id;

      if (fieldId === 'chargecode_id' && value) {
        $(this).val(value).trigger('change.select2'); // Updates visible selection
        setTimeout(() => handleChargeCodeChange(false), 0);
      } else {
        $('#' + fieldId).val(note[fieldId]).trigger('change');
      }
    });

  }

  function handleChargeCodeChange(updateAll = true) {
    const selectedOption = $('#chargecode_id').find('option:selected');
    const chargeDataStr = selectedOption.attr('data-code');
  
    if (!chargeDataStr) {
      if (updateAll) {
        $('#qty, #charge_gross, #reduction_percent, #description, #charge_net, #vat_rate_percent, #line_total').val('');
      } else {
        $('#description').val('');
      }
      return;
    }
  
    const chargeData = JSON.parse(chargeDataStr);
    $('#description').val(chargeData.description);
  
    if (updateAll) {
      $('#qty').val(1);
      $('#charge_gross').val(chargeData.price);
      $('#reduction_percent').val(0);
      $('#vat_rate_percent').val(chargeData.vatrate);
      calculateLineTotal();
    }
  }
  
  // Default usage when user changes it manually
  $('#chargecode_id').on('change', function () {
    handleChargeCodeChange(true); // Allow full update on manual change
  });
  

  function calculateLineTotal() {
    const qty = parseFloat($('#qty').val()) || 0;
    const gross = parseFloat($('#charge_gross').val()) || 0;
    const reduction = parseFloat($('#reduction_percent').val()) || 0;
    const vat = parseFloat($('#vat_rate_percent').val()) || 0;

    const net = gross - (gross * (reduction / 100));
    const totalBeforeVat = qty * net;
    const total = totalBeforeVat + (totalBeforeVat * (vat / 100));

    $('#charge_net').val(net.toFixed(2));
    $('#line_total').val(total.toFixed(2));
  }


 
});
