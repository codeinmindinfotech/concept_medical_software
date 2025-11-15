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

  // FORM SUBMIT
  window.addEventListener("load", function () {
    var forms = document.getElementsByClassName("needs-validation");

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

        // AJAX MODE
        if (form.hasAttribute("data-ajax")) {
          event.preventDefault();
          event.stopPropagation();

          form.querySelectorAll("[data-mask='phone']").forEach(phoneField => {
              phoneField.value = phoneField.value.replace(/\D/g, '');
          });
          let formData = new FormData(form);

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

              // SUCCESS - SWEETALERT
              Swal.fire({
                icon: "success",
                title: "Success",
                text: data.message || "Saved successfully",
                timer: 2000,
                showConfirmButton: false
              });

              if (data.redirect) {
                setTimeout(() => {
                  window.location.href = data.redirect;
                }, 2000);
              }
            })
            .catch(error => {
              console.error("Error:", error);

              // ERROR SWEETALERT
              Swal.fire("Error", "Something went wrong", "error");
            });
        }

        form.classList.add("was-validated");
      });
    });

    // SHOW SERVER ERRORS
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

      // Optionally show Swal for server validation failure
      Swal.fire("Validation Error", "Please fix the highlighted fields.", "warning");
    }
  });

//   document.addEventListener("DOMContentLoaded", () => {
//     const form = document.getElementById("bookAppointmentForm");
//     if (!form) return;

//     // Add needs-validation + data-ajax to activate global validation
//     form.classList.add("needs-validation");
//     form.setAttribute("data-ajax", "true");

//     form.addEventListener("submit", async function (event) {
//         // The global validator will stop submission if invalid
//         if (form.checkValidity() === false) return;

//         event.preventDefault();
//         event.stopPropagation();

//         // -------------------------------
//         // Collect Form Data
//         // -------------------------------
//         const flag = document.getElementById("flag")?.value || "";
//         const id = document.getElementById("appointment-id")?.value || "";

//         const patient_input = document.getElementById("appointment-patient-id");
//         let patientId = patient_input
//             ? patient_input.value
//             : document.getElementById("patient-id")?.value || "";

//         const selectedClinic =
//             document.getElementById("appointment-clinic-id")?.value ||
//             document.getElementById("clinic-select")?.value ||
//             null;

//         const data = {
//             appointment_id: id,
//             patient_id: patientId,
//             appointment_type: form.appointment_type.value,
//             appointment_date: form.appointment_date.value,
//             start_time: form.start_time.value,
//             end_time: form.end_time.value,
//             patient_need: form.patient_need.value,
//             appointment_note: form.appointment_note.value,
//             clinic_id: selectedClinic,
//             apt_slots: parseInt(
//                 form.querySelector('input[name="apt_slots"]:checked')?.value || 1
//             ),
//         };

//         try {
//             const response = await fetch(form.dataset.action, {
//                 method: "POST",
//                 headers: {
//                     Accept: "application/json",
//                     "Content-Type": "application/json",
//                     "X-CSRF-TOKEN": document
//                         .querySelector('meta[name="csrf-token"]')
//                         .getAttribute("content"),
//                 },
//                 body: JSON.stringify(data),
//             });

//             const result = await response.json();

//             // -------------------------------
//             // Laravel Validation Errors (422)
//             // -------------------------------
//             if (response.status === 422 && result.errors) {
//                 // Use your NEW global server-error handler
//                 handleServerErrors(result.errors, form);
//                 return;
//             }

//             // -------------------------------
//             // Other errors
//             // -------------------------------
//             if (!response.ok || !result.success) {
//                 Swal.fire("Error", result.message || "Operation failed.", "warning");
//                 return;
//             }

//             // -------------------------------
//             // SUCCESS
//             // -------------------------------
//             Swal.fire({
//                 icon: "success",
//                 title: id
//                     ? "Appointment updated successfully!"
//                     : "Appointment booked successfully!",
//                 timer: 2000,
//                 showConfirmButton: false,
//             });

//             // Hide modal
//             bootstrap.Modal.getInstance(
//                 document.getElementById("bookAppointmentModal")
//             ).hide();

//             // Reload or update calendar
//             if (flag == 1) {
//                 location.reload();
//             } else {
//                 if (typeof loadSlotsAndAppointments === "function")
//                     loadSlotsAndAppointments();
//                 if (typeof refreshCalendarEvents === "function")
//                     refreshCalendarEvents();
//                 if (typeof initCalendar === "function") initCalendar();
//             }
//         } catch (error) {
//             console.error(error);
//             Swal.fire("Error", "Something went wrong. Please try again.", "warning");
//         }
//     });
// });

})();