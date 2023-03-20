    $("#type_settlement").select2({
        allowClear: true,
        placeholder: "Pilih Keperluan Form"
    });
    $("#tgl_transfer").datepicker({
        todayHighlight: !0,
        autoclose: !0,
        format: "yyyy-mm-dd"
    });


    $(".rows_cash").hide();
    $(".cashss").val('').trigger("change");
    $("#type_settlement").on("change", function(e) {
        e.preventDefault();
        var option = $('option:selected', this).val();
        if (option == "with_cash") {
            $(".rows_cash").show();
            $(".cashss").on("change", function(e) {
                e.preventDefault();
                var option = $('option:selected', this).val();
                console.log(option);
                $("#vnd").prop("disabled", false);
                $('#vnd option').remove();
                if (option === '') {
                    $("#vnd").prop("disabled", true);
                } else {
                    getCash(option);
                }
            });

            function getCash(option) {
                var getdata = ajax_data('finance/settlement/get_cash', "&id=" + option);
                $(".rows_cash").show();
                $(".cash_post").html(getdata);
            }
        } else {
            var getdata = ajax_data('finance/settlement/blank_form');
            $(".rows_cash").hide();
            $(".cash_post").html(getdata);
            $(".cashss").val('').trigger("change");
        }
    })

    $("#cash_adv").select2({
        placeholder: "Pilih Cash Advance",
        allowClear: true,
    });


    function more_desc(ele) {
        if ($(ele).data('type') == "delete") {
            var a = $(ele).data('equ');
            $(".forms_" + $(ele).data('equ')).remove();
        } else {
            var n_equ = $(".more_deskripsi").length;
            n_equ++;
            console.log(n_equ);
            var getdata = ajax_data('finance/settlement/add_kegiatan', "&n_equ=" + n_equ +
                "&type=" + $(ele).data('type'));
            $(".more_desc").before(getdata);
        }
    }



        function Deletes_items(ele) {
        var equ = $(ele).data('equ');
        if ($(ele).data('type') == "delete_settle_cash") {
            var getdata = ajax_data('finance/settlement/delete_items', "&id=" + $(ele).data('id'))
            $('#desk_cash_' + $(ele).data('id')).remove();
        } else {
            var getdata = ajax_data('finance/settlement/delete_items', "&id=" + $(ele).data('id'))
            $('#desk_' + $(ele).data('id')).remove();
        }
    }



    function getValuessss(option) {
        $(function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: main_url + 'finance/settlement/getValue',
                type: "POST",
                dataType: "json",
                data: { id: option },
                cache: false,
                success: function(data, response) {
                    // $('.auto_city').append('<option value="" selected>Pilih Kota</option>');
                    // for (i = 0; i < response.data.cash.des_kota.length; i++) {
                    //     $('.auto_city').append('<option value="' + response.data.cash.des_kota[i].id + '">' + response.data.cash.des_kota[i].des_kota + '</option>');
                    // }
                    // $("#text_emp").val(response.data.cash.nama_emp);
                    // $(".auto_prov").val(response.data.prov).trigger("change");
                    // $(".tgl_berangkats").val(response.data.tgl_berangkat);
                    // $(".tgl_pulangs").val(response.data.tgl_pulang);
                    // $("#est_waktu").val(response.data.cash.est_waktu);
                    // if (response.data.cash.mtd_cash == "Cash") {
                    //     $(".pays").val(response.data.cash.mtd_cash).trigger("change");
                    // } else {
                    //     $(".row transfer1").show();
                    //     $(".pays").val(response.data.cash.mtd_cash).trigger("change");
                    //     $(".banks").val(response.data.cash.rek_bank);
                    //     $(".reks").val(response.data.cash.no_rek);
                    //     $(".nama_reks").val(response.data.cash.nama_rek);
                    //     $(".cbg_reks").val(response.data.cash.cabang_rek);
                    // }
                    // $("#quo_no").val(response.data.quo_no);
                    // if ($("#type_voucher").val() == "lainnya") {
                    //     $("#cust").val(response.data.cust).trigger("change");
                    // }
                }
            });
        });
    }

    function set_app(ele) {
        if ($(ele).data('type') == "approval") {
            window.location.href = main_url + 'finance/settlement/' + $(ele).data('id') + "/" + $(ele).data('user') + '/approve';
        } else if ($(ele).data('type') == "ajuan") {
            window.location.href = main_url + 'finance/settlement/' + $(ele).data('id') + '/ajukan';
        } else {
            window.location.href = main_url + 'finance/settlement/' + $(ele).data('id') + '/' + $(ele).data('user') + '/reject';
        }
    }


    var images = [$("#files")];
    var data = new FormData();

    images.forEach(function(images, i) {
        data.append('image_' + i, images);
    });

    $.ajax({
        url: 'PHP/posts.php',
        type: 'post',
        data: data,
        contentType: false,
        processData: false,
        success: function(data) {
            console.log(data);
            location.reload();
        }
    });


    function Completed(ele) {
        var getdata = ajax_data('finance/settlement/process', "&id=" + $(ele).data('id'));
        $("#modalbody2").html(getdata),
            $("#modaltitle2").html("Proccess")

    }

    function PrintSets(ele) {
        var getdata = ajax_data("finance/settlement/print_sets/" + $(ele).data("id"));
    }

    function edit_set(ele) {
        var getdata = ajax_data("finance/settlement/edit_set", "&id=" + $(ele).data("id"));
        $(".pros_finance").html(getdata);
        $("#tgl_transfer").datepicker({
            todayHighlight: !0,
            autoclose: !0,
            format: "yyyy-mm-dd"
        });
    }



    $(document).ready(function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#save_sets').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var datas = $("#set_form").serialize();
            $.ajax({
                type: 'POST',
                url: "finance/settlement/saveFormFinance",
                data: formData + datas,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    this.reset();
                    alert('File has been uploaded successfully');
                    console.log(data);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
    });
    $('.edit').hide();

    function changenom(ele) {
        $('.awals').hide();
        $('.edit').show();

    }

    $('.rowfile_edit').hide();

    function ch_setts(ele) {
        $('.rowfile_edit').show();
        $('.rowfile_awal').hide();
    }

    function savech_setts(ele) {
        var id = $(ele).data('id');
        var file = $('#filee_set').val();
        $(function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: main_url + 'finance/settlement/set_update',
                type: "POST",
                dataType: "json",
                data: { id: id },
                success: function(response) {
                    $("#emp").val(response.id).trigger('change');
                    $("#divisi").val(response.division_name);
                    $("#divisi_edit").val(response.division_name);
                    $("#divisi2").val(response.division_id);
                    $("#div_hide").val(response.division_id);
                    $("#posisi").val(response.position);
                    $("#posisi_edit").val(response.position);
                }
            });
        });
    }

    function submit_seattlement(ele) {
        var note =
            $(function() {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: main_url + 'api/location/get_city',
                    type: "POST",
                    dataType: "json",
                    data: { id: option },
                    cache: false,
                    success: function(response) {
                        var html = '';
                        var i;
                        $('#city').append('<option value="" selected disabled>Pilih Kota</option>');
                        for (i = 0; i < response.length; i++) {
                            $('#city').append('<option value="' + response[i].id + '">' + response[i].kota + '</option>');
                        }

                    }
                });
            });
    }




    function details_sets(ele) {
        console.log(ele);
        var gets = ajax_data('finance/cash_advance/details_settlement', "&id_dtl=" + $(ele).data('id_dtl') + "&id_cash=" + $(ele).data('id_cash'));
        $(".detailss_" + $(ele).data("id_dtl")).html(gets);
    }

    function SettlementApp(ele) {
        var types = $(ele).data('type');
        if (types == "Supervisor") {
            window.location.href = main_url + 'finance/cash_advance/' + $(ele).data('id') + '/' + $(ele).data('type') + '/settlements_approval';
            $(".setapp_spv").remove();
        } else {
            window.location.href = main_url + 'finance/cash_advance/' + $(ele).data('id') + '/' + $(ele).data('type') + '/settlements_approval';
            $(".setapp_finance").remove();
        }
    }

    function add_note(ele) {
        var getdata = ajax_data('finance/settlement/add_note', "&id=" + $(ele).data('id'));
        $("#modalbody").html(getdata),
            $("#modaltitle").html("Detail")
        $("#tgl_transfer").datepicker({
            todayHighlight: !0,
            autoclose: !0,
            format: "yyyy-mm-dd"
        });
    }

    function all_done(ele) {
        window.location.href = main_url + 'finance/settlement/' + $(ele).data('id') + '/all_done';
    }