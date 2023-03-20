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
            ".calendar"
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
                    plugins: ["dayGrid", "interaction", 'list'],
                    header: {
                        left: "prev,next today",
                        center: "title",
                        right: "dayGridMonth,dayGridWeek,dayGridDay,listWeek",
                    },
                    dateClick: function(info) {
                        // alert('clicked ' + info.dateStr);
                        var getdata = ajax_data('receptionist/form_booking', '&time='+info.dateStr);
                        $('#m_modal').modal("show");
                        $('#modalbody').html(getdata);
                        $('#modaltitle').html('<div class="card-header bg-light text-info-800 header-elements-inline">\
                        <h5 class="card-title">Form Booking</h5>\</div>');
                        },
                        select: function(info) {
                        // alert('selected ' + info.startStr + ' to ' + info.endStr);
                        },
                    // defaultDate: date,
                    editable: true,
                    selectable : true,
                    events: {
                        url: main_url + "receptionist/room_list",
                        method: "POST",
                        extraParams: {
                            ids: myid,
                            _token: token,
                        },
                    },
                    eventClick: function (info) {
                        var getdata = ajax_data('receptionist/Editform_booking', '&id='+info.event.id + "&title="+info.event.title);
                        $('#m_modal').modal("show");
                        $('#modalbody').html(getdata);
                        $('#modaltitle').html('<div class="card-header bg-light text-info-800 header-elements-inline">\
                        <h5 class="card-title">Update Form Booking</h5>\</div>');
                        // alert(info.event.title);
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
    $('body').toggleClass('sidebar-xs').removeClass('sidebar-mobile-main');
    FullCalendarBasic.init();
});
