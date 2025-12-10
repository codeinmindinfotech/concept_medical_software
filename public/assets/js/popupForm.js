const PopupForm = (() => {

    function init(modalSelector, formSelector, onSuccess) {
        const $modal = $(modalSelector);
        const $form = $modal.find(formSelector);

        // Clear previous errors when modal opens
        $modal.on('show.bs.modal', function () {
            clearErrors($form);
        });

        $form.on('submit', function (e) {
            e.preventDefault();
            clearErrors($form);

            if (!this.checkValidity()) {
                e.stopPropagation();
                $form.addClass('was-validated');
                return false;
            }

            const url = $form.data('action');
            const method = $form.attr('method') || 'POST';
            const formData = $form.serialize();

            $.ajax({
                url: url,
                type: method,
                data: formData,
                success: function (response) {
                    if (!response.success && response.errors) {
                        showErrors($form, response.errors);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Please fix the errors in the form!',
                            timer: 2500,
                            showConfirmButton: false
                        });
                        return;
                    }

                    // Success notification
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message || 'Appointment booked successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Reset form and hide modal
                    $form[0].reset();
                    $form.removeClass('was-validated');
                    clearErrors($form);
                    $modal.modal('hide');

                    if (typeof onSuccess === 'function') {
                        onSuccess(response);
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong! Please try again.',
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
            });
        });
    }

    function showErrors($form, errors) {
        for (const field in errors) {
            const input = $form.find(`[name="${field}"]`);
            if (input.length) {
                input.addClass('is-invalid');
                let feedback = input.next('.invalid-feedback');
                if (!feedback.length) {
                    input.after('<div class="invalid-feedback"></div>');
                    feedback = input.next('.invalid-feedback');
                }
                feedback.text(errors[field][0]);
            }
        }
    }

    function clearErrors($form) {
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').remove();
    }

    function reset(modalSelector) {
        const $modal = $(modalSelector);
        const $form = $modal.find('form');
        $form[0].reset();
        $form.removeClass('was-validated');
        clearErrors($form);
    }

    return {
        init,
        reset
    };
})();
