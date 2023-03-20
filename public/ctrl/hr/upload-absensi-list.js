var DatatableEmployee = {
    init: function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "20%", targets: [1] },
                { width: "20%", targets: [2, 3] },
                {
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [
                [4, "desc"]
            ],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            ordering: true,
            processing: true,
            serverSide: true,
            language: {
                search: '<span>Filter:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_ Per Halaman',
                paginate: {
                    'first': 'First',
                    'last': 'Last',
                    'next': '&rarr;',
                    'previous': '&larr;'
                }
            },
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: main_url + "hrm/get_absensi",
                type: 'post',
                dataType: 'json',

                data: {
                    '_token': token
                }
            },

            columns: [{
                "data": "location",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "created_at",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "time",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "status",
                render: function(data, type, row) {
                    return data;
                }

            }, {
                "data": "id",
                "className": "text-center",
                render: function(data, type, row) {
                    return '<div class="list-icons"><div class="dropdown"><a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu9"></i></a>\
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-158px, 19px, 0px); top: 0px; left: 0px; will-change: transform;">\
                    <a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Detail</a>\
                    </div ></div ></div >'
                }
            }, ]
        });
    }
};
jQuery(document).ready(function() {
    DatatableEmployee.init();
});


function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('txt').innerHTML =
        h + ":" + m + ":" + s;
    var t = setTimeout(startTime, 500);
}

function checkTime(i) {
    if (i < 10) { i = "0" + i }; // add zero in front of numbers < 10
    return i;
}


function display_ct5() {
    var monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];
    var x = new Date();
    var ampm = x.getHours() >= 12 ? ' PM' : ' AM';

    var x1 = x.getDate() + " - " + (monthNames[x.getMonth()]) + " - " + x.getFullYear();
    x1 = x1 + " // " + x.getHours() + ":" + x.getMinutes() + ":" + x.getSeconds() + " " + ampm;
    document.getElementById('ct5').innerHTML = x1;
    display_c5();
}

function display_c5() {
    var refresh = 1000; // Refresh rate in milli seconds
    mytime = setTimeout('display_ct5()', refresh)
}
display_c5()
navigator.geolocation.getCurrentPosition(showPosition);

var x = document.getElementById("lokasi");


function showPosition(position) {
    var getdata = ajax_data("hrm/get_location", "&lat=" + position.coords.latitude + "&long=" + position.coords.longitude + "&type=" + "normal");
    x.innerHTML = getdata.alamat;
    $("#address").val(getdata.alamat);
    $("#lats").val(getdata.latitude);
    $("#longs").val(getdata.longitude);
}

$("#catatan").val('');
function absensi(ele) {
    var status = $("#status_absen").val();
    var alamat = $("#address").val();
    var lat = $("#lats").val();
    var long = $("#longs").val();
    var note = $("#catatan").val();
    var getdata = ajax_data('hrm/saveAbsensi', "&last_status=" + status + "&alamat=" 
        + alamat + "&lat=" + lat + "&long=" + long + "&note=" + note);
    window.location.reload();
}


function refresh_loc(ele) {
    navigator.geolocation.getCurrentPosition(staticposition);
}

function staticposition(position) {
    var getdata = ajax_data("hrm/get_location", "&lat=" + position.coords.latitude + "&long=" + position.coords.longitude + "&type=" + "static");
    x.innerHTML = getdata.alamat;
    $("#address").val(getdata.alamat);
    $("#lats").val(getdata.latitude);
    $("#longs").val(getdata.longitude);
}