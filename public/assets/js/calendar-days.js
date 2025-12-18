// Only run if the page actually contains the calendar modal
document.addEventListener("DOMContentLoaded", function () {

    // ---- SELECT CLINIC ----
    window.selectClinic = function(name, id) {
        const nameInput = document.getElementById('selectedClinic');
        const idInput = document.getElementById('selectedClinicId');
        if (!nameInput || !idInput) return;

        nameInput.value = name;
        idInput.value = id;
    }

    // ---- GENERATE DATES ----
    window.generateDates = function() {
        const startDateInput = document.getElementById('startDate')?.value;
        const repeatType = document.querySelector('input[name="repeatType"]:checked')?.value;
        const repeatCount = parseInt(document.getElementById('repeatCount')?.value);
        const clinicName = document.getElementById('selectedClinic')?.value;

        if (!startDateInput || !clinicName) {
            Swal.fire("Error", "Please select a clinic and start date.", "error");
            return;
        }

        const startDate = new Date(startDateInput);
        const tbody = document.querySelector('#dateTable tbody');
        if (!tbody) return;

        tbody.innerHTML = '';

        for (let i = 0; i < repeatCount; i++) {
            const date = new Date(startDate);
            date.setDate(startDate.getDate() + i * (repeatType === 'weekly' ? 7 : 14));

            const formattedDate = date.toISOString().split('T')[0];
            tbody.innerHTML += `
                <tr>
                    <td>${formattedDate}</td>
                    <td>${clinicName}</td>
                    <td><input type="checkbox" value="${formattedDate}" checked></td>
                </tr>`;
        }
    }

    // ---- SAVE CALENDAR DAYS ----
    window.saveCalendarDays = function(url) {
        const clinicId = document.getElementById('selectedClinicId')?.value;
        const checkboxes = document.querySelectorAll('#dateTable tbody input[type="checkbox"]:checked');

        const dates = Array.from(checkboxes).map(cb => cb.value);

        if (!clinicId || dates.length === 0) {
            Swal.fire("Error", "Select a clinic and at least one date.", "error");
            return;
        }
        if (!url) {
            console.error("No calendarDays URL found!",url);
            return;
        }
        fetch(url, {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ clinic_id: clinicId, dates })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire("Success", data.message, "success");

                if ($('#calendar').fullCalendar) {
                    $('#calendar').fullCalendar('refetchEvents');
                    renderCalendarDaysDots();
                }

                $('#setCalendarDaysModal').modal('hide');
                document.querySelector('#dateTable tbody').innerHTML = '';
                document.getElementById('startDate').value = '';
            }
        })
        .catch(err => console.error(err));
    }

    // ---- RENDER DOTS ----
    window.renderCalendarDaysDots = function() {
        const url = (window.calendarConfig && window.calendarConfig.calendarDays) ||
                (window.appConfig && window.appConfig.calendarDays);

    if (!url) {
        console.error("No calendarDays URL found!",url);
        return;
    }
        $.get(url, function(days) {
            // Remove previous dots
            $("td.fc-day-top").find(".appointment-dot-container").remove();
    
            // Group colors by date
            const groupedDays = {};
            days.forEach(d => {
                if (!groupedDays[d.date]) groupedDays[d.date] = [];
                groupedDays[d.date].push(d.color);
            });
    
            // Add dots and borders
            Object.keys(groupedDays).forEach(date => {
                const colors = groupedDays[date];
    
                // Get the top cell with date number
                const cell = $("td.fc-day-top[data-date='" + date + "']");
                if (!cell.length) return;
    
                // Create a container for dots
                const dotContainer = $("<div>").addClass("appointment-dot-container");
                dotContainer.css({
                    "display": "flex"
                    , "gap": "2px"
                    , "justify-content": "flex-end"
                    , "position": "relative"
                    , "top": "2px"
                    , "right": "2px"
                });
    
                // Add a dot for each color
                colors.forEach(color => {
                    const dot = $("<div>").css({
                        "width": "8px"
                        , "height": "8px"
                        , "border-radius": "50%"
                        , "background-color": color
                        , "border": "1px solid #fff"
                    });
                    dotContainer.append(dot);
                });
    
                // Make sure the cell is relative for absolute positioning
                cell.css("position", "relative");
                cell.append(dotContainer);
    
            });
        });
    }
});
