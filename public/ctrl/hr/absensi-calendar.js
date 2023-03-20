/* ------------------------------------------------------------------------------
 *
 *  # Fullcalendar basic options
 *
 *  Demo JS code for extra_fullcalendar_views.html and extra_fullcalendar_styling.html pages
 *
 * ---------------------------------------------------------------------------- */

// Setup module
// ------------------------------

var FullCalendarBasic = (function () {
    var myid = $("#myid").val();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    
    //
    // Setup module components
    //

    // Basic calendar
    var _componentFullCalendarBasic = function () {
        if (typeof FullCalendar == "undefined") {
            console.warn("Warning - Fullcalendar files are not loaded.");
            return;
        }

        // Add demo events
        // ------------------------------

        // Default events
        var events = [
            {
                title: "08:35",
                start: "2022-06-19",
            },
        ];

        // Initialization
        // ------------------------------

        //
        // Basic view
        //

        // Define element

        var calendarBasicViewElement = document.querySelector(
            ".fullcalendar-basic"
        );
        var today = new Date();
        var date =
            today.getFullYear() +
            "-" +
            (today.getMonth() + 1) +
            "-" +
            today.getDate();
        // Initialize
        if (calendarBasicViewElement) {
            var calendarBasicViewInit = new FullCalendar.Calendar(
                calendarBasicViewElement,
                {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    plugins: ["dayGrid", "interaction"],
                    header: {
                        left: "prev,next today",
                        center: "title",
                        right: "dayGridMonth,dayGridWeek,dayGridDay",
                    },
                    defaultDate: date,
                    editable: true,
                    events: {
                        url: main_url + "hrm/rekapabsen/get_timeabsensi",
                        method: "POST",
                        extraParams: {
                            ids: myid,
                            _token: token,
                        },
                        failure: function () {
                            alert("there was an error while fetching events!");
                        },
                    },
                }
            ).render();
        }
    };

    //
    // Return objects assigned to module
    //

    return {
        init: function () {
            _componentFullCalendarBasic();
        },
    };
})();

// Initialize module
// ------------------------------

document.addEventListener("DOMContentLoaded", function () {
    FullCalendarBasic.init();
});
