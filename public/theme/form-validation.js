$(document).ready(function () {
    $("form.validate-form").validate({
        rules: {
            name: {
                required: true,
                minlength: 3
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            }
        },
        messages: {
            name: "Please enter at least 3 characters",
            email: "Please enter a valid email",
            password: "Password must be at least 6 characters"
        }
    });
});
