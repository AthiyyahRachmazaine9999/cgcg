var DatatableVoucher = {
    init: function() {
        $("#statusss").select2({
            allowClear: true,
            placeholder: "Pilih Status"
        });
        $(".dates_pay").datepicker({
            autoclose: !0,
            format: "yyyy/mm/dd"
        });

        $("#typess").select2({
            allowClear: true,
            placeholder: "Pilih Type Filter"
        });

        $("#id_customer").select2({
            allowClear: true,
            placeholder: "Cari Customer",
            ajax: {
                url: main_url + 'sales/find_customer',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.company,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $("#vendor").select2({
            allowClear: true,
            placeholder: "Cari Vendor",
            ajax: {
                url: main_url + 'finance/payment_voucher/find_vendor',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.vendor_name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $(".payment").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "25%", targets: [1] },
                { width: "15%", targets: [3] },
                {
                    "visible": false,
                    "searchable": false
                },
            ],
            className: 'select-checkbox',
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            order: [
                [0, "desc"]
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
                url: main_url + "finance/get_payment",
                type: 'post',
                dataType: 'json',

                data: {
                    '_token': token
                }
            },
            columns: [{
                    "data": "id",
                    "className": "text-center",
                    render: function(data, type, row) {
                        return '<input type="checkbox" name="check" class"Check" data-id="' + data + '" id="myCheck" class="center">';
                    }
                }, {
                    "data": "tujuan",
                    render: function(data, type, row) {
                        if (row.no_payment == "" || row.no_payment == null) {
                            var no = "Not printed yet";
                            var colour = "warning";
                        } else {
                            var no = row.no_payment;
                            var colour = "info";
                        }

                        if (row.id_quo != null) {
                            if (row.no_so == "---") {
                                var nos = "";
                            } else {
                                var nos = " [" + row.no_so + "]";
                            }
                        } else {
                            var nos = '';
                        }

                        return data + nos + '<br>\
                    <br> <strong>' + row.id_vendor + '</strong><br> ' + '<br><span class="badge badge-flat border-' + colour + ' text-' + colour + '-600 d-block">' + no + '</span>';
                    }
                }, {
                    "data": "no_invoice",
                    "className": "text-center",
                    render: function(data, type, row) {
                        if (data == null) {
                            var data = row.performa_invoice == null ? 'No. Performa Invoice' : row.performa_invoice;
                        } else {
                            var data = data;
                        }

                        if (row.no_efaktur == null) {
                            var now = "-";
                        } else {
                            var now = row.no_efaktur;
                        }

                        return '<span class="badge badge-flat border-info text-info-600 d-block">' + data + '</span>' +
                            '<br><br>' + now;
                    }
                }, {
                    "data": "nominal",
                    "className": "text-right",
                    render: function(data, type, row) {
                        var datas = data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                        if (row.note_pph != null) {
                            var pph = row.note_pph;
                            var no_pph = row.note_nominal_pph.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            var add_pph = pph + ' = ' + no_pph;
                            var show_pph = '<br><br><br><span class="font-italic text-indigo">' + add_pph + '</span>';
                        } else {
                            var show_pph = '';
                        }



                        if (row.file != null) {
                            var file = '<br><span class="font-italic text-primary">Sudah Ada Lampiran</span>';
                        } else {
                            var file = "";
                        }

                        return '<span class="text-right">' + datas + '</span>' + show_pph + file;
                    }
                }, {
                    "data": "status",
                    render: function(data, type, row) {
                        if (row.vendors == "direksi") {
                            if (data == "Reject" || data == "Rejected") {
                                var color = "badge badge-danger border-danger d-block";
                                var text = data;
                            } else if (data == "Pending") {
                                var color = "badge badge-flat border-warning text-warning-600 d-block";
                                var text = data;
                            } else if (data == "Approved" && row.app_finance != null) {
                                var color = "badge badge-flat border-success text-success-600 d-block";
                                var text = "Approved";
                            } else if (data = "Pending Director" && row.app_mng == null) {
                                var color = "badge badge-flat border-warning text-warning-600 d-block";
                                var text = "Waiting Approval By Director";
                            } else if (row.status == "Completed" && row.app_mng != null) {
                                var color = "badge badge-flat border-primary text-primary-600 d-block";
                                var text = "Approval Completed";
                            } else if (data == "Done Payment") {
                                var color = "badge badge-primary border-primary text-white-600 d-block";
                                var text = data;
                            }
                        } else {
                            if (data == "Reject" || data == "Rejected") {
                                var color = "badge badge-danger border-danger d-block";
                                var text = data;
                            } else if (data == "Pending") {
                                var color = "badge badge-flat border-warning text-warning-600 d-block";
                                var text = data;
                            } else if (data == "Approved" && row.app_finance != null) {
                                var color = "badge badge-flat border-success text-success-600 d-block";
                                var text = "Approved";
                            } else if (data == "Completed" && row.app_hr != null || data == "Completed") {
                                var color = "badge badge-flat border-primary text-primary-600 d-block";
                                var text = "Approval Completed";
                            } else if (data == "Done Payment") {
                                var color = "badge badge-primary border-primary text-white-600 d-block";
                                var text = data;
                            }
                        }

                        if (row.note_pay != null) {
                            var warna = "badge badge-info border-info text-white-600 d-block d-block text-center";
                            var isi = row.note_pay;
                        } else {
                            var warna = '';
                            var isi = '';
                        }

                        return '<span class="' + color + 'd-block text-center">' + text + '</span><br><br>\
                    <span class="' + warna + '">' + isi + '</span>';
                    }
                },
                {
                    "data": "created_at",
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    "data": "id",
                    "className": "text-center",
                    render: function(data, type, row) {
                        if (row.user == "finance") {
                            if (row.status == "Pending") {
                                var edit = '<a href="' + current_url + '' + data + '/payv/edit_finance" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>';
                                var done = '';
                                var del = '<a href="' + current_url + '' + data + '/delete" class="dropdown-item"><i class="fas fa-trash-alt text-danger"></i> Delete</a>';
                                var show = '<a href="' + current_url + '' + data + '/payv/show_finance" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>'
                                var reject = '';
                            } else if (row.status == "Approved") {
                                var edit = '<a href="' + current_url + '' + data + '/payv/edit_finance" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>';
                                var done = '';
                                var del = '';
                                var show = '<a href="' + current_url + '' + data + '/payv/show_finance" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>'
                                var reject = '<a href="' + 'payment_voucher/reject_payment/' + data + '/' + data + '/finance" class="dropdown-item"><i class="fas fa-calendar-times text-warning"></i> Reject</a>'
                            } else if (row.status == "Completed") {
                                var edit = "";
                                var done = "";
                                var del = "";
                                var show = '<a href="' + current_url + '' + data + '/payv/show_finance" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>'
                                var reject = '<a href="' + 'payment_voucher/reject_payment/' + data + '/' + data + '/finance" class="dropdown-item"><i class="fas fa-calendar-times text-warning"></i> Reject</a>'
                            } else {
                                var edit = "";
                                var done = "";
                                var del = "";
                                var show = '<a href="' + current_url + '' + data + '/payv/show_finance" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>'
                                var reject = "";
                            }
                        } else {
                            var edit = "";
                            var done = "";
                            var del = "";
                            var show = '<a href="' + current_url + '' + data + '/payv/show_finance" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                            var reject = "";
                        }
                        return '<div class="list-icons"><div class="dropdown"><a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu9"></i></a>\
                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-158px, 19px, 0px); top: 0px; left: 0px; will-change: transform;">' +
                            edit + show + done + del + reject + '</div ></div ></div >'
                    }
                },
            ]
        });
        $('#PrintPayment').on('click', function() {
            var ele = [];
            $("input:checkbox[name=check]:checked").each(function() {
                var click = ele.push($(this).data('id'));
                if (ele.length > 2) {
                    window.location.href = main_url + "finance/download/payment_check/" + "more2";
                } else {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: main_url + "finance/download/payment_check_double/" + ele,
                        method: 'POST',
                        data: ele,
                        success: function(response) {
                            if (response.info) {
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    type: "POST",
                                    url: main_url + "finance/download/download_double/" + ele,
                                    data: ele,
                                    xhrFields: { responseType: 'blob' },
                                    success: function(data, response) {
                                        var blob = new Blob([data]);
                                        var link = document.createElement('a');
                                        link.href = window.URL.createObjectURL(blob);
                                        link.download = "Payment Voucher.pdf";
                                        link.click();
                                        if (response == "success") {
                                            window.location.reload();
                                        }
                                    }

                                })
                            }
                        }
                    });
                }
            });
        });

        $("#filter").on('click', function(ele) {
            if ($("#typess").val() == "Normal" || $("#typess").val() == '') {
                var type = $("#typess").val();
                var status = $('#statusss').val();
                var customer = $('#id_customer').val();
                var vendor = $('#vendor').val();
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();

                if (type == '') { var ty = 'kosong' } else { var ty = type }
                if (status == '') { var sta = 'kosong' } else { var sta = status }
                if (customer == '' || customer == null) { var cust = 'kosong' } else { var cust = customer }
                if (vendor == '' || vendor == null) { var vend = 'kosong' } else { var vend = vendor }
                if (start_date == '') { var sd = 'kosong' } else { var sd = start_date }
                if (end_date == '') { var ed = 'kosong' } else { var ed = end_date }

                console.log(ty);
                console.log(sta);
                console.log(cust);
                console.log(vend);
                console.log(sd);
                console.log(ed);

                var urls = main_url + "finance/payment_voucher/filterData/" +
                    ty + "/" + sta + "/" + cust + "/" + vend + "/" + sd + "/" + ed;
                table.ajax.url(urls).load();
            }
        });


        //export
        $("#exports").on('click', function(ele) {
            var type = $("#typess").val();
            var status = $('#statusss').val();
            var customer = $('#id_customer').val();
            var vendor = $('#vendor').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            if (type == '') { var ty = 'kosong' } else { var ty = type }
            if (status == '') { var sta = 'kosong' } else { var sta = status }
            if (customer == '' || customer == null) { var cust = 'kosong' } else { var cust = customer }
            if (vendor == '' || vendor == null) { var vend = 'kosong' } else { var vend = vendor }
            if (start_date == '') { var sd = 'kosong' } else { var sd = start_date }
            if (end_date == '') { var ed = 'kosong' } else { var ed = end_date }


            window.location.href = main_url + "finance/payment_voucher/export_data/" +
                ty + "/" + sta + "/" + cust + "/" + vend + "/" + sd + "/" + ed;
        });

        // Reset
        $("#reset").on('click', function(ele) {
            $('#typess').val('').trigger("change");
            $('#statusss').val('').trigger("change");
            $('#id_customer').val('').trigger("change");
            $('#id_vendor').val('').trigger("change");
            $('#start_date').val('').trigger("change");
            $('#end_date').val('').trigger("change");
            table.ajax.url(main_url + "finance/get_payment").load();
        });
    }
};

jQuery(document).ready(function() {
    $("body").toggleClass("sidebar-xs").removeClass("sidebar-mobile-main");
    DatatableVoucher.init();
});




function Done_payment(ele) {
    var getdata = ajax_data('finance/payment_voucher/done_payment', "&id=" + $(ele).data('id'));
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Detail")
    $("#tgl_payment").datepicker({
        autoclose: !0,
        format: "yyyy/mm/dd"
    });
    $("#nama_bankk").select2({
        allowClear: true,
        placeholder: "Pilih Nama Bank"
    });
    $("#status_pay").select2({
        allowClear: true,
        placeholder: "Pilih Tipe"
    });
}

function add_note(ele) {
    var getdata = ajax_data('finance/payment_voucher/add_note', "&id=" + $(ele).data('id'));
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Additional Information")
    $("#pilih_nominal").select2({
        allowClear: true,
        placeholder: "Pilih Type"
    });
    $("#pilih_nominal").on("change", function(e) {
        e.preventDefault();
        var option = $('option:selected', this).val();
        if (option === 'Number') {
            $('#nominal_pph').on('keyup change', function() {
                var harga = $("#nominal_pph").val();
                var real = $("#nominal_real").val();

                var hitung_num = parseFloat(real) - parseFloat(harga);
                $("#nominal_kirim").val(hitung_num);
            });
        } else if (option === "Persen") {
            $('#nominal_pph').on('keyup change', function() {
                var harga = $("#nominal_pph").val();
                var real = $("#nominal_real").val();

                var persen = parseFloat(real) * (parseFloat(harga) / 100)
                var hitung_sen = parseFloat(real) - parseFloat(persen);
                $("#nominal_kirim").val(hitung_sen);
                $("#hitung_persen").val(persen);
            });
        }
    });
}


function add_files(ele) {
    var getdata = ajax_data('finance/payment_voucher/add_files', "&id=" + $(ele).data('id'));
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Additional File")
}

function ListPayment(ele) {
    var getdata = ajax_data('finance/othercost/indexOther');
    $(".Biaya_lain").html(getdata);
}