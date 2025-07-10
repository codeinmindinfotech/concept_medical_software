$(document).ready(function () {
    $('body').on('submit', '.validate-form', function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');
        let method = form.attr('method') || 'POST';
        let formData = new FormData(this);

        // Clear previous errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.text-danger').remove();

        $.ajax({
            url: url,
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

                    // Scroll to the first error
                    if (firstError) {
                        $('html, body').animate({
                            scrollTop: firstError.offset().top - 100
                        }, 500);
                    }
                } else {
                    // Other server error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please try again.',
                    });
                }
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


