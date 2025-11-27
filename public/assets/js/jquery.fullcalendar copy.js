document.addEventListener('DOMContentLoaded', function() {

    // --- Initialize Calendar ---
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        editable: false,
        height: 'auto',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        // Load appointments dynamically
        events: function(fetchInfo, successCallback, failureCallback) {
            $.ajax({
                url: window.calendarEventUrl, // set in Blade via <script>window.calendarEventUrl = '{{ route(...) }}'</script>
                type: "GET",
                data: {
                    start: fetchInfo.startStr,
                    end: fetchInfo.endStr,
                    clinic_id: $("#clinic_id").val(),
                    patient_id: $("#patient_id").val(),
                },
                success: function(res) {
                    successCallback(res);
                },
                error: function(err) {
                    failureCallback(err);
                }
            });
        },

        // Click on empty date → add new appointment
        dateClick: function(info) {
            openAddAppointmentModal(info.dateStr);
        },

        // Click on event → edit existing appointment
        eventClick: function(info) {
            openEditAppointmentModal(info.event);
        }
    });

    calendar.render();

    // Refresh calendar events when clinic or patient changes
    $("#clinic_id, #patient_id").on("change", function() {
        calendar.refetchEvents();
    });

    // --- Functions to handle modals ---
    function openEditAppointmentModal(event) {
        $("#appointment-id").val(event.id);
        $("#modal-appointment-date").val(event.startStr);
        $("#modal-patient-name").val(event.extendedProps.patient_name);
        $("#start_time").val(event.extendedProps.start_time);
        $("#end_time").val(event.extendedProps.end_time);
        $("#appointment_note").val(event.extendedProps.note);

        // Show modal depending on clinic type
        if (event.extendedProps.clinic_type === 'hospital') {
            $("#manualBookingModal").modal("show");
        } else {
            $("#bookAppointmentModal").modal("show");
        }
    }

    function openAddAppointmentModal(date) {
        $("#modal-appointment-date").val(date);

        let clinicId = $("#appointment-clinic-id").val() || $("#hospital-clinic-id").val();

        if (!clinicId) {
            Swal.fire("Warning", "Please select a clinic first.", "warning");
            return;
        }

        // Load available slots for selected clinic/hospital
        $.ajax({
            url: window.slotUrl, // set in Blade
            type: "GET",
            data: {
                clinic_id: clinicId,
                date: date
            },
            success: function(res) {
                $("#slot-table-container").html(res.html);

                // Decide which modal to open based on clinic type
                let clinicType = $("#appointment-clinic-id").find(':selected').data('type') || 'clinic';
                if (clinicType === 'hospital') {
                    $("#manualBookingModal").modal("show");
                } else {
                    $("#bookAppointmentModal").modal("show");
                }
            },
            error: function(err) {
                console.error(err);
                Swal.fire("Error", "Failed to load available slots.", "error");
            }
        });
    }

    // --- Submit appointment forms ---
    $("#bookAppointmentForm, #manualBookingForm").on("submit", function(e) {
        e.preventDefault();

        let url = $("#appointment-id").val() ? window.updateAppointmentUrl : window.saveAppointmentUrl;

        $.ajax({
            url: url,
            type: "POST",
            data: $(this).serialize(),
            success: function(res) {
                $(".modal").modal("hide");

                // Refresh calendar events
                var calendarInstance = FullCalendar.getCalendar(calendarEl);
                if (calendarInstance) {
                    calendarInstance.refetchEvents();
                }

                Swal.fire("Success", "Appointment saved successfully!", "success");
            },
            error: function(err) {
                console.error(err);
                Swal.fire("Error", "Failed to save appointment!", "error");
            }
        });
    });

});
