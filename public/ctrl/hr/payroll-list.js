var DatatableEmployee = {
    init: function () {
        var url = window.location.pathname.split("/").pop();
        console.log(url);
        if (url=='edit') {
            $('.gaji-detail').addClass('active');
            $('.gaji-master').removeClass('active');
            $('#detail').addClass('show active');
            $('#main').removeClass('show active');
        } 
        $("#allowdownload").attr("disabled", true);
        $("#type").select2({
            placeholder: "Pilih Jenis"
        }),
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "30%", targets: [2, 3, 4, 5] },
                {
                    targets: [0],
                    visible: false,
                    searchable: false,
                },
            ],
            order: [[0, "asc"]],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            ordering: true,
            processing: true,
            serverSide: true,
            language: {
                search: "<span>Filter:</span> _INPUT_",
                lengthMenu: "<span>Show:</span> _MENU_ Per Halaman",
                processing:
                    '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading..n.</span> ',
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
                url: main_url + "hrm/payroll/get_salary",
                type: "post",
                dataType: "json",
                data: {
                    _token: token,
                },
            },
            fnRowCallback: function (
                nRow,
                aData,
                iDisplayIndex,
                iDisplayIndexFull
            ) {
                $("td", nRow).css("cursor", "pointer");
                return nRow;
            },

            columns: [
                {
                    data: "id",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "emp_name",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "division_name",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "position",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "bank_acc",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "basic_salary",
                    className: "text-right",
                    render: $.fn.dataTable.render.number(",", ".", 0),
                },
                {
                    data: "allowance",
                    className: "text-right",
                    render: $.fn.dataTable.render.number(",", ".", 0),
                },
            ],
        });
        $(".m_datatable tbody").on("click", "tr", function () {
            var data = table.row(this).data();
            window.location.href =
                main_url + "hrm/payroll/" + data.id + "/edit";
        });

        // single person salary

        var tables = $(".personaltable").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "30%", targets: [2, 3, 4, 5] },
                {
                    targets: [0],
                    visible: false,
                    searchable: false,
                },
            ],
            order: [[0, "asc"]],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            ordering: true,
            processing: true,
            serverSide: true,
            language: {
                search: "<span>Filter:</span> _INPUT_",
                lengthMenu: "<span>Show:</span> _MENU_ Per Halaman",
                processing:
                    '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading..n.</span> ',
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
                url: main_url + "hrm/payroll/my_salary",
                type: "post",
                dataType: "json",
                data: {
                    _token: token,
                },
            },
            fnRowCallback: function (
                nRow,
                aData,
                iDisplayIndex,
                iDisplayIndexFull
            ) {
                $("td", nRow).css("cursor", "pointer");
                return nRow;
            },

            columns: [
                {
                    data: "id",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "emp_name",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "division_name",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "position",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "bank_acc",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "basic_salary",
                    className: "text-right",
                    render: $.fn.dataTable.render.number(",", ".", 0),
                },
                {
                    data: "allowance",
                    className: "text-right",
                    render: $.fn.dataTable.render.number(",", ".", 0),
                },
                {
                    data: "bpjs",
                    className: "text-right",
                    render: $.fn.dataTable.render.number(",", ".", 0),
                },
                {
                    data: "pension",
                    className: "text-right",
                    render: $.fn.dataTable.render.number(",", ".", 0),
                },
                {
                    data: "tax",
                    className: "text-right",
                    render: $.fn.dataTable.render.number(",", ".", 0),
                },
            ],
        });
        $(".personaltable tbody").on("click", "tr", function () {
            var datac = tables.row(this).data();
            window.location.href = main_url + "hrm/payroll/" + datac.id;
        });

        // per month detail salary

        var dettable = $(".m_detailtable").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "15%", targets: [5, 6, 7] },
                { width: "15%", targets: [1, 4] },
                {
                    targets: [0],
                    visible: false,
                    searchable: false,
                },
            ],
            order: [[0, "asc"]],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            ordering: true,
            processing: true,
            serverSide: true,
            language: {
                search: "<span>Filter:</span> _INPUT_",
                lengthMenu: "<span>Show:</span> _MENU_ Per Halaman",
                processing:
                    '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading..n.</span> ',
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
                url: main_url + "hrm/payroll/get_monthly",
                type: "post",
                dataType: "json",
                data: {
                    _token: token,
                },
            },
            fnRowCallback: function (
                nRow,
                aData,
                iDisplayIndex,
                iDisplayIndexFull
            ) {
                $("td", nRow).css("cursor", "pointer");
                return nRow;
            },

            columns: [
                {
                    data: "id",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "emp_name",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "division_name",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "position",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "bank_acc",
                    render: function (data, type, row) {
                        return data;
                    },
                },
                {
                    data: "gross_salary",
                    className: "text-right",
                    render: $.fn.dataTable.render.number(",", ".", 0),
                },
                {
                    data: "deduction",
                    className: "text-right",
                    render: $.fn.dataTable.render.number(",", ".", 0),
                },
                {
                    data: "thp",
                    className: "text-right",
                    render: $.fn.dataTable.render.number(",", ".", 0),
                },
            ],
        });
        $(".m_detailtable tbody").on("click", "tr", function () {
            var data = dettable.row(this).data();
            window.location.href =
                main_url + "hrm/payrolldetail/" + data.id + "/deduction";
        });

        $("#tabs").tabs({
            select: function (event, ui) {
                var data = $("#common_form").serialize();
                if (ui.index == 1) {
                    var url = "${tab2_url}" + "&" + data;
                    $("#tabs").tabs("url", 1, url); //this is new !
                }
                return true;
            },
        });
    },
};
jQuery(document).ready(function () {
    DatatableEmployee.init();
});

function MasterSalary(ele) {
    var newtemplate = ajax_data("hrm/salary/checktoken");
    $("#modalbody").html(newtemplate),
        $("#modaltitle").html("Master Token Salary");

    $("#forgot").hide();
    $("#change").hide();

    $("#frg").on("click", function (e) {
        e.preventDefault();
        $("#forgot").show();
        $("#change").hide();
        $("#logs").hide();
    });

    $("#chg").on("click", function (e) {
        e.preventDefault();
        $("#forgot").hide();
        $("#change").show();
        $("#logs").hide();
    });

    $("#norm").on("click", function (e) {
        e.preventDefault();
        $("#forgot").hide();
        $("#change").hide();
        $("#logs").show();
    });
}

function MySalary(ele) {
    var newtemplate = ajax_data(
        "hrm/salary/personaloken",
        "&id_emp=" + $(ele).data("id_emp")
    );
    $("#modalbody").html(newtemplate),
        $("#modaltitle").html("Individual Token Salary");

    $("#forgot").hide();
    $("#change").hide();

    $("#frg").on("click", function (e) {
        e.preventDefault();
        $("#forgot").show();
        $("#change").hide();
        $("#logs").hide();
    });

    $("#chg").on("click", function (e) {
        e.preventDefault();
        $("#forgot").hide();
        $("#change").show();
        $("#logs").hide();
    });

    $("#norm").on("click", function (e) {
        e.preventDefault();
        $("#forgot").hide();
        $("#change").hide();
        $("#logs").show();
    });
}

function Checkdetailgaji(ele) {
    var getdate = $("#date").val();
    var date    = new Date($("#date").val());
    var month   = date.getMonth() + 1;
    console.log(getdate);
    var result  = ajax_data(
        "hrm/salary/checkdetail",
        "&id_emp=" + $(ele).data("id_emp")+
        "&month=" + getdate
    );
    if (result == "empty") {
        swal(
            "Maaf data bulan " +
                month +
                " anda belum ada di system , silahkan request HR untuk mengisi",
            {
                icon: "warning",
            }
        );
    } else {
        $("#allowdownload").attr("disabled", false);
    }
}

function CheckMonth(ele) {
    var type    = $("#type").val();
    var getdate = $("#date_edit").val();
    var date    = new Date($("#date_edit").val());
    var month   = date.getMonth() + 1;
    var result  = ajax_data(
        "hrm/payroll/checkmonth",
        "&id_emp=" + $(ele).data("id_emp")+
        "&month=" + getdate+
        "&jenis=" + type
    );
    $("#resultcheck").html(result);
}

function CetakSlipGaji(ele) {
    
    var getdate = $("#date").val();
    var type    = $("#type").val();
    var date    = new Date($("#date").val());
    var month   = date.getMonth() + 1;
    $.ajax({
        type: "POST",
        url: main_url + "hrm/payroll/download",
        data: {
            '_token' : token,
            'id_emp' : $(ele).data("id_emp"),
            'date_hr': $(ele).data("date_hr"),
            'type'   : type,
            'date'   : getdate
        },
        xhrFields: { responseType: 'blob' },
        success: function (data) {
            var blob          = new Blob([data]);
            var link          = document.createElement('a');
                link.href     = window.URL.createObjectURL(blob);
                link.download = "SLIP GAJI - "+$(ele).data("date_hr")+".pdf";
            link.click();
        }
    })
}

function HitungBPJS() {
    var basic_salary = $("#basic_salary").val();
    var allowance    = $("#allowance").val();
    var bpjssub      = parseFloat((basic_salary * 6.24) / 100);
    var pensionsub   = parseFloat((basic_salary * 3) / 100);
    var sumgross     = 
        parseFloat(basic_salary) +
        parseFloat(allowance) +
        parseFloat(bpjssub) +
        parseFloat(pensionsub);
    $("#bpjs").val(bpjssub);
    $("#pension").val(pensionsub);
    $("#gross").html(addCommas(sumgross));
}

function HitungTotal() {
    var basic_salary = $("#basic_salary").val();
    var allowance    = $("#allowance").val();
    var bpjs         = $("#bpjs").val();
    var pension      = $("#pension").val();

    var sumgross     = 
        parseFloat(basic_salary) +
        parseFloat(allowance) +
        parseFloat(bpjs) +
        parseFloat(pension);

    $("#gross").html(addCommas(sumgross));
    
}

function TotalDeduction() {

    var ded_other     = $("#ded_other").val();
    var ded_bpjs      = $("#ded_bpjs").val();
    var ded_pension   = $("#ded_pension").val();
    var ded_tax       = $("#ded_tax").val();
    var ded_loan      = $("#ded_loan").val();
    var ded_insurance = $("#ded_insurance").val();

    var sumgross     = 
        parseFloat(ded_other) +
        parseFloat(ded_bpjs) +
        parseFloat(ded_pension) +
        parseFloat(ded_tax) +
        parseFloat(ded_insurance) +
        parseFloat(ded_loan);

    $("#dedgross").html(addCommas(sumgross));
    
}

function addCommas(nStr) {
    nStr += "";
    x = nStr.split(".");
    x1 = x[0];
    x2 = x.length > 1 ? "," + x[1] : "";
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, "$1" + "," + "$2");
    }
    return x1;
}

function ViewReport(ele) {
    var time = $("#time").val();
    console.log(time);
    var result  = ajax_data(
        "hrm/reportpayroll/viewreport",
        "&time=" + time
    );
    $("#gross").html(parseFloat(result.gross).toLocaleString('en'));
    $("#deduction").html(parseFloat(result.deduction).toLocaleString('en'));
    $("#net").html(parseFloat(result.net).toLocaleString('en'));
    $("#month").html("Periode "+result.month);
    
}
