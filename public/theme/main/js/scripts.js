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