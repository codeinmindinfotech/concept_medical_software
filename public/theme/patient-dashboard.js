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
    ['#procedure_date', '#admission_date', '#discharge_date', '#editVisitDate', '#visit_date', '#recall_date'].forEach(selector => {
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
      const $html = $('<div>').html(data); // Wrap to safely parse
  
      const $newContainer = $html.find('#WaitingListContainer');
      const $rows = $newContainer.find('#WaitingTable tbody tr');
      const validRows = $rows.filter(function () {
        return $(this).find('td').length === $('#WaitingTable thead th').length;
      });
  
      // Replace content only if valid rows exist
      if (validRows.length > 0) {
        $('#WaitingListContainer').html($newContainer.html());
        dataTableInstance = initDataTableGeneric('#WaitingTable', 5);
      } else {
        console.warn('Skipping DataTable init: No valid rows found.');
        $('#WaitingListContainer').html($newContainer.html()); // You can still show "No data found" message
      }
    });
  };
  

  let dataTableInstanceNote;
  const loadFeeNoteLists = () => {
    $.get(window.routes.note_index, function (data) {
      const $html = $('<div>').html(data);
      const $newContainer = $html.find('#FeeNoteListContainer');
      const $rows = $newContainer.find('#FeeNoteTable tbody tr');
      const validRows = $rows.filter(function () {
        return $(this).find('td').length === $('#FeeNoteTable thead th').length;
      });
  
      if (validRows.length > 0) {
        $('#FeeNoteListContainer').html($newContainer.html());
        dataTableInstanceNote = initDataTableGeneric('#FeeNoteTable', 7);
      } else {
        console.warn('Skipping FeeNote DataTable init: No valid rows found.');
        $('#FeeNoteListContainer').html($newContainer.html());
      }
    });
  };
  
  let dataTableInstanceRecall;
  const loadRecallTable = () => {
    $.get(window.routes.recall_index, function (data) {
      const $html = $('<div>').html(data);
      const $newContainer = $html.find('#RecallListContainer');
      const $rows = $newContainer.find('#RecallTable tbody tr');
      const validRows = $rows.filter(function () {
        return $(this).find('td').length === $('#RecallTable thead th').length;
      });
  
      if (validRows.length > 0) {
        $('#RecallListContainer').html($newContainer.html());
        dataTableInstanceRecall = initDataTableGeneric('#RecallTable', 4);
      } else {
        console.warn('Skipping Recall DataTable init: No valid rows found.');
        $('#RecallListContainer').html($newContainer.html());
      }
    });
  };
  

  if ($('#WaitingTable tbody tr').length > 0) {
    loadWaitingLists();
  }
  if ($('#FeeNoteTable tbody tr').length > 0) {
    loadFeeNoteLists();
  }
  if ($('#RecallTable tbody tr').length > 0) {
    loadRecallTable();
  }
  


  $('#editVisitModal, #addVisitModal, #feeNoteModal, #recallModal ').on('shown.bs.modal', function () {
    initModalFields(this);
  });

   
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

  $(document).on('click', '#addRecallBtn', function () {
    clearRecallForm();
    $('#recallModalLabel').text('Add Recall');
    new bootstrap.Modal(document.getElementById('recallModal')).show();
  });
  
  $(document).on('click', '.editRecallBtn', function () {
    const recall = $(this).data('recall'); // Ensure data-recall attribute is passed
    $('#recallModalLabel').text('Edit Recall');
    $('#recallModal').one('shown.bs.modal', () => fillRecallForm(recall));
    new bootstrap.Modal(document.getElementById('recallModal')).show();
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
 
  
  $('#patient_picture_input').on('change', function () {
    const form = $('#uploadPatientPictureForm')[0];
    const formData = new FormData(form);
  
    const file = this.files[0];
    if (!file) return;
  
    const reader = new FileReader();
    reader.onload = function (e) {
      $('#patient_picture_preview').attr('src', e.target.result);
    };
    reader.readAsDataURL(file);
  
    $.ajax({
      url: form.action, 
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success(response) {
        Swal.fire({
          icon: 'success',
          title: 'Updated',
          text: response.message || 'Picture uploaded successfully!',
          timer: 1500,
          showConfirmButton: false
        });
  
        if (response.image_url) {
          $('#patient_picture_preview').attr('src', response.image_url);
        }
      },
      error(xhr) {
        handleErrors(xhr, $(form));
      }
    });
  });
  
  
  // === Recall Modal Handlers === //

function clearRecallForm() {
  $('#recallForm')[0].reset();
  $('#recall_id').val('');
  $('#recallForm .is-invalid').removeClass('is-invalid');
  $('#recallForm .text-danger').remove();
  $('#recallForm .select2').val(null).trigger('change');
}

function fillRecallForm(recall) {
  $('#recallForm .is-invalid, #recallForm .text-danger').remove();
  $('#recall_id').val(recall.id);
  $('#recall_interval').val(recall.recall_interval).trigger('change');
  $('#recall_date').val(recall.recall_date);
  $('#status_id').val(recall.status_id).trigger('change');
  $('#note').val(recall.note);
}



$('#recallForm').on('submit', function (e) {
  e.preventDefault();
  const form = $(this);
  const id = $('#recall_id').val();
  const url = id
    ? window.routes.recall_update.replace('__ID__', id)
    : window.routes.recall_store;
  const method = id ? 'PUT' : 'POST';
 
  $.ajax({
    url,
    type: method,
    data: form.serialize(),
    success() {
      $('#recallModal').modal('hide');
      Swal.fire({
        icon: 'success',
        title: 'Saved',
        text: 'Recall saved successfully!',
        timer: 2000,
        showConfirmButton: false
      });
      loadRecallTable(); // you need to define this loader for recalls
    },
    error(xhr) {
      handleErrors(xhr, form);
    }
  });
});

$(document).on('click', '.deleteRecall', function () {
  const recallId = $(this).data('id');
  const url = window.routes.recall_destroy.replace('__ID__', recallId);

  Swal.fire({
    title: 'Are you sure?',
    text: 'This recall will be permanently deleted.',
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
          $(`tr[data-id="${recallId}"]`).remove();
          Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: 'The recall has been deleted.',
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

function calculateRecallDate(interval) {
  const today = new Date();
  let recallDate = new Date(today); // clone today

  switch (interval) {
    case 'Today':
      // already today
      break;
    case '6 weeks':
      recallDate.setDate(today.getDate() + 42); // 6 weeks = 42 days
      break;
    case '2 months':
      recallDate.setMonth(today.getMonth() + 2);
      break;
    case '3 months':
      recallDate.setMonth(today.getMonth() + 3);
      break;
    case '6 months':
      recallDate.setMonth(today.getMonth() + 6);
      break;
    case '1 year':
      recallDate.setFullYear(today.getFullYear() + 1);
      break;
    default:
      return '';
  }

  return recallDate.toISOString().split('T')[0]; // format YYYY-MM-DD
}

$(document).on('change', '#recall_interval', function () {
  const interval = $(this).val();
  const date = calculateRecallDate(interval);
  if (date) {
    $('#recall_date').val(date);
  } else {
    $('#recall_date').val('');
  }
});

 
});
