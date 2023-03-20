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

        var calendarBasicViewElement = document.querySelector(".calendar");
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
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    plugins: ["dayGrid", "interaction", "list"],
                    header: {
                        left: "prev,next today",
                        center: "title",
                        right: "dayGridMonth,dayGridWeek,dayGridDay,listWeek",
                    },
                    dateClick: function (info) {
                        // alert('clicked ' + info.dateStr);
                        var getdata = ajax_data(
                            "sales/form",
                            "&time=" + info.dateStr
                        );
                        $("#m_modal").modal("show");
                        $("#modalbody").html(getdata);
                        $("#modaltitle").html(
                            '<div class="card-header bg-light text-info-800 header-elements-inline">\
                        <h5 class="card-title">Form Visit Plan</h5></div>'
                        );
                    },
                    select: function (info) {},
                    editable: true,
                    selectable: true,
                    events: {
                        url: main_url + "sales/visit_list",
                        method: "POST",
                        extraParams: {
                            ids: myid,
                            _token: token,
                        },
                    },
                    eventClick: function (info) {
                        var getdata = ajax_data(
                            "sales/visitplan/show_form",
                            "&id=" +
                                info.event.id +
                                "&title=" +
                                info.event.title
                        );
                        $("#m_modal").modal("show");
                        $("#modalbody").html(getdata);
                        $("#modaltitle").html(
                            '<div class="card-header bg-light text-info-800 header-elements-inline">\
                        <h5 class="card-title">Visit Plan</h5></div>'
                        );
                        $("#button_edit").click(function (ele) {
                            var ajx = ajax_data(
                                "sales/edit_form",
                                "&id=" +
                                    info.event.id +
                                    "&title=" +
                                    info.event.title
                            );
                            $("#m_modal2").modal("show");
                            $("#modalbody2").html(ajx);
                            $("#modaltitle2").html(
                                '<div class="card-header bg-light text-info-800 header-elements-inline">\
                        <h5 class="card-title">Visit Plan</h5></div>'
                            );
                        });
                        $("#button_delete").click(function (ele) {
                            window.location.href =
                                main_url + "sales/delete_form/" + info.event.id;
                        });
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
    $("body").toggleClass("sidebar-xs").removeClass("sidebar-mobile-main");
    FullCalendarBasic.init();
});

function DownloadVisit(ele) {
    window.location.href = main_url + "sales/visitplan/download";
}

var FormControls = {
    init: function () {
        !(function () {
            $(".form-actions").click(function () {
                if (this.value == "Y") {
                    $(".m-form__btn").removeClass("d-none");
                } else {
                    $(".m-form__btn").addClass("d-none");
                }
            });
        })(),
            $("#m_form").validate({
                rules: {
                    title: {
                        required: !0,
                    },
                },
            });
    },
};
jQuery(document).ready(function () {
    FormControls.init();
});
