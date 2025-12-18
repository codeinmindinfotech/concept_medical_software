$(document).ready(function () {
  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  $('#qty, #charge_gross, #reduction_percent, #charge_net, #vat_rate_percent').on('input', calculateLineTotal);

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
 
  
  // $('#patient_picture_input').on('change', function () {
  //   const form = $('#uploadPatientPictureForm')[0];
  //   const formData = new FormData(form);
  
  //   const file = this.files[0];
  //   if (!file) return;
  
  //   const reader = new FileReader();
  //   reader.onload = function (e) {
  //     $('#patient_picture_preview').attr('src', e.target.result);
  //   };
  //   reader.readAsDataURL(file);
  
  //   $.ajax({
  //     url: form.action, 
  //     method: 'POST',
  //     data: formData,
  //     processData: false,
  //     contentType: false,
  //     success(response) {
  //       Swal.fire({
  //         icon: 'success',
  //         title: 'Updated',
  //         text: response.message || 'Picture uploaded successfully!',
  //         timer: 1500,
  //         showConfirmButton: false
  //       });
  //       if (response.image_url) {
  //         $('#patient_picture_preview').attr('src', response.image_url);
  //       }
  //     },
  //     error(xhr) {
  //       handleServerErrors(xhr, $(form));
  //     }
  //   });
  // });

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
