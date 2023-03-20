var FormControls = {
    init: function () {
        !(function () {
            $("#status").select2({
                allowClear: true,
                placeholder: "Pilih Status",
            });
            $("#cust").select2({
                allowClear: true,
                placeholder: "Plih Instansi",
                tags:true,
                ajax: {
                    url: main_url + "sales/find_customer",
                    dataType: "json",
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.company,
                                    id: item.company,
                                };
                            }),
                        };
                    },
                    cache: true,
                },
            });

            $("#lokasi").select2({
                allowClear: true,
                placeholder: "Pilih Lokasi",
                tags:true,
                ajax: {
                    url: main_url + "sales/find_lokasi",
                    dataType: "json",
                    delay: 250,
                    tags:true,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.display_name,
                                    id: item.display_name,
                                };
                            }),
                        };
                    },
                    cache: true,
                },
            });
            $(".time").timepicker({});
            $("#end_time").timepicker({});
            $(".date").datepicker({
                todayHighlight: !0,
                autoclose: !0,
                format: "yyyy-mm-dd",
            });
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

function DeleteBooking(ele) {
    var getdata = ajax_data(
        "receptionist/delete_data",
        "&id=" + $(ele).data("id")
    );
    window.location.replace(main_url + "sales/visit");
}

function CustomerForm(ele) {
    $("#cust").select2("close");
    var prospectRes = ajax_data("sales/new_customer");

    $("#modalbody").html(prospectRes),
        $("#modaltitle").html("Tambah Data Customer");

    $("#province").select2({
        allowClear: true,
        dropdownParent: $("#m_modal"),
        placeholder: "Pilih Provinsi",
    }),
        $("#city").select2({
            allowClear: true,
            placeholder: "Pilih Kota",
        }),
        $("#country").select2({
            allowClear: true,
            placeholder: "Pilih Kecamatan",
        });

    $("#province").on("change", function (e) {
        e.preventDefault();
        var option = $("option:selected", this).val();
        $("#city").prop("disabled", false);
        $("#city option").remove();
        $("#country option").remove();
        if (option === "") {
            $("#city").prop("disabled", true);
            $("#country").prop("disabled", true);
        } else {
            getKota(option);
        }
    });
    $("#city").on("change", function (e) {
        e.preventDefault();
        var option = $("option:selected", this).val();
        $("#country").prop("disabled", false);
        $("#country option").remove();
        if (option === "") {
            $("#country").prop("disabled", true);
        } else {
            getCamat(option);
        }
    });

    function getKota(option) {
        $(function () {
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                url: main_url + "api/location/get_city",
                type: "POST",
                dataType: "json",
                data: { id: option },
                cache: false,
                success: function (response) {
                    var html = "";
                    var i;
                    $("#city").append(
                        '<option value="" selected disabled>Pilih Kota</option>'
                    );
                    for (i = 0; i < response.length; i++) {
                        $("#city").append(
                            '<option value="' +
                                response[i].id +
                                '">' +
                                response[i].kota +
                                "</option>"
                        );
                    }
                },
            });
        });
    }

    function getCamat(option) {
        $(function () {
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                url: main_url + "api/location/get_kecamatan",
                type: "POST",
                dataType: "json",
                data: { id: option },
                cache: false,
                success: function (response) {
                    var html = "";
                    var i;
                    $("#country").append(
                        '<option value="">Pilih Kecamatan</option>'
                    );
                    for (i = 0; i < response.length; i++) {
                        $("#country").append(
                            '<option value="' +
                                response[i].id +
                                '">' +
                                response[i].nama +
                                "</option>"
                        );
                    }
                },
            });
        });
    }
}
