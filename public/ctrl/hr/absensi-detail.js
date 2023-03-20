var DatatableEmployee = {
    init: function () {
        var myid = $("#myid").val();
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $(".m_datatable").DataTable({
            autoWidth: false,
            order: [[3, "desc"]],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            ordering: true,
            processing: true,
            serverSide: true,
            language: {
                search: "<span>Filter:</span> _INPUT_",
                lengthMenu: "<span>Show:</span> _MENU_ Per Halaman",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "&rarr;",
                    previous: "&larr;",
                },
            },
            ajax: {
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                url: main_url + "hrm/rekapabsen/get_absensi/" + myid,
                type: "post",
                dataType: "json",

                data: {
                    _token: token,
                    'myid':myid
                },
            },

            columns: [
                {
                    data: "location",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "created_at",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "time",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "status",
                    render: function (data, type, row) {
                        return data;
                    },
                },
            ],
        });

        var events = [
            {
                title: "All Day Event",
                start: "2022-06-09",
            },
            {
                title: "Long Event",
                start: "2022-06-11",
                end: "2022-06-11",
            },
        ];
        // Define element
        $("#fullCalendar").fullCalendar({
            eventClick: function (event, jsEvent, view) {
                var today = new Date();
                var date =
                    today.getFullYear() +
                    "-" +
                    (today.getMonth() + 1) +
                    "-" +
                    today.getDate();
                // Initialize
                var calendarBasicViewInit = new FullCalendar.Calendar(
                    calendarBasicViewElement,
                    {
                        plugins: ["dayGrid", "interaction"],
                        header: {
                            left: "prev,next today",
                            center: "title",
                            right: "dayGridMonth,dayGridWeek,dayGridDay",
                        },
                        defaultDate: date,
                        editable: true,
                        events: events,
                        eventLimit: true,
                    }
                ).render();
            },
        });
    },
};
jQuery(document).ready(function () {
    DatatableEmployee.init();
});
