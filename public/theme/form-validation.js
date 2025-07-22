$(document).ready(function () {
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




