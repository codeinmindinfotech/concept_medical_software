/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});

function initDataTable(selector, options = {}) {
    document.addEventListener('DOMContentLoaded', () => {
      const tables = typeof selector === 'string' ? document.querySelectorAll(selector) : selector;
  
      if (!tables) return;
  
      const nodes = tables instanceof NodeList ? tables : [tables];
  
      nodes.forEach(table => {
        if (!table.simpleDatatablesInitialized) {
          new simpleDatatables.DataTable(table, {
            searchable: true, // ✅ Enables the search box
            fixedHeight: false,
            perPage: 10,
            sortable: true,
            ...options
          });
          table.simpleDatatablesInitialized = true; // flag to prevent double init
        }
      });
    });
  }

  function handleErrors(xhr, form) {
    if (xhr.status === 422) {
      const errors = xhr.responseJSON.errors;
      let firstError = null;
  
      // Clean up old errors
      form.find('.is-invalid').removeClass('is-invalid');
      form.find('.text-danger').remove();
  
      $.each(errors, function (key, messages) {
        const input = form.find(`[name="${key}"]`);
        if (input.length) {
          input.addClass('is-invalid');
  
          const message = `<div class="text-danger">${messages[0]}</div>`;
  
          // Handle Select2: insert after parent .form-group or .mb-3
          if (input.hasClass('select2-hidden-accessible')) {
            const wrapper = input.closest('.mb-3, .form-group');
            if (wrapper.length) {
              wrapper.append(message);
            } else {
              input.after(message);
            }
  
          // Handle input groups (text + icon)
          } else if (input.closest('.input-group').length > 0) {
            input.closest('.input-group').after(message);
  
          // Regular input
          } else {
            input.after(message);
          }
  
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

  document.addEventListener('DOMContentLoaded', function () {
    const loginType = document.getElementById('login_type');
    const clinicField = document.getElementById('clinic_id_container');

    if (!loginType || !clinicField) {
        return;
    }

    function toggleClinicField() {
        const selected = loginType.value;
        clinicField.style.display = (selected === 'clinic' || selected === 'doctor' || selected === 'patient') ? 'block' : 'none';
    }

    loginType.addEventListener('change', toggleClinicField);
    toggleClinicField(); // Initial load
});

