/**
 * Common JS for handling modal form validation and AJAX submission
 */

const PopupForm = (() => {

    /**
     * Initialize form validation and submission
     * @param {string} modalSelector - Modal ID or class
     * @param {string} formSelector - Form ID or class inside modal
     * @param {function} onSuccess - Callback after successful submit
     */
    function init(modalSelector, formSelector, onSuccess) {
        const $modal = $(modalSelector);
        const $form = $modal.find(formSelector);

        // Bootstrap validation
        $form.on('submit', function (e) {
            e.preventDefault();

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
                    if (typeof onSuccess === 'function') {
                        onSuccess(response);
                    }
                    $modal.modal('hide');
                    $form[0].reset();
                    $form.removeClass('was-validated');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert('Something went wrong! Please check the form and try again.');
                }
            });
        });
    }

    /**
     * Reset a modal form
     * @param {string} modalSelector 
     */
    function reset(modalSelector) {
        const $modal = $(modalSelector);
        const $form = $modal.find('form');
        $form[0].reset();
        $form.removeClass('was-validated');
    }

    return {
        init,
        reset
    };

})();
