(function () {
  "use strict";

  // LIVE FIELD VALIDATION FIX
  document.addEventListener("input", function (event) {
    let input = event.target;

    if (!input.form || !input.form.classList.contains("needs-validation")) return;

    let nextError = input.nextElementSibling;
    let hasServerError = nextError && nextError.classList.contains("text-danger");

    let isValid = input.checkValidity();

    if (isValid) {
      input.classList.remove("is-invalid");
      if (hasServerError) nextError.remove();
      input.classList.add("is-valid");
    } else {
      input.classList.remove("is-valid");
      input.classList.add("is-invalid");
    }
  });

  // HANDLE SERVER ERRORS
  function handleServerErrors(errors, form) {
    for (let field in errors) {
      let input = form.querySelector(`[name="${field}"]`);

      if (input) {
        input.classList.add("is-invalid");

        let errorDiv = document.createElement("div");
        errorDiv.classList.add("text-danger");
        errorDiv.innerText = errors[field][0];

        input.insertAdjacentElement("afterend", errorDiv);
      }
    }

    Swal.fire("Validation Error", "Please fix the highlighted fields.", "warning");
  }  

  function showGlobalLoader() {
    document.getElementById("global-loader")?.classList.remove("d-none");
  }

  function hideGlobalLoader() {
    document.getElementById("global-loader")?.classList.add("d-none");
  }

  // FORM SUBMIT HANDLER FOR needs-validation FORMS
  window.addEventListener("load", function () {
    const forms = document.getElementsByClassName("needs-validation");

    Array.prototype.forEach.call(forms, function (form) {
      form.addEventListener("submit", function (event) {
        // Clear previous server errors
        form.querySelectorAll(".is-invalid").forEach(i => i.classList.remove("is-invalid"));
        form.querySelectorAll(".text-danger").forEach(e => e.remove());

        // Bootstrap HTML5 validation
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
          form.classList.add("was-validated");
          return;
        }

        showGlobalLoader();

        // AJAX MODE
        if (form.hasAttribute("data-ajax")) {
          event.preventDefault();
          event.stopPropagation();

          form.querySelectorAll("[data-mask='phone']").forEach(phoneField => {
            phoneField.value = phoneField.value.replace(/\D/g, '');
          });

          let formData = new FormData(form);
          // saveSignature();
          // alert("after");

          fetch(form.action, {
            method: form.method || "POST",
            body: formData,
            headers: { "X-Requested-With": "XMLHttpRequest" }
          })
          .then(async response => {
            if (response.status === 422) {
              let data = await response.json();
              handleServerErrors(data.errors, form);
              return;
            }
            return response.json();
          })
          .then(data => {
            if (!data) return;

            Swal.fire({
              icon: "success",
              title: "Success",
              text: data.message || "Saved successfully",
              timer: 2000,
              showConfirmButton: false
            });

            if (data.redirect) {
              setTimeout(() => window.location.href = data.redirect, 2000);
            }
          })
          .catch(async error => {
              console.error("Fetch failed:", error);
          
              // Try to read the raw response body
              if (error.response) {
                  const text = await error.response.text();
                  console.log("Raw server response:", text);
              } else {
                  console.log("No response available. Possible network error.");
              }
          
              Swal.fire("Error", "Something went wrong", "error");
          })
          .finally(() => {
            hideGlobalLoader();
          });
        }

        form.classList.add("was-validated");
      });
    });
  });

})();