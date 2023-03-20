var FormControls = {
    init: function() {
        ! function() {
            $(".dates").datepicker({
                autoclose: !0,
                format: "yyyy-mm-dd"
            });
            $(".codes").select2({
                allowClear: true,
                placeholder: "Choose Code",
                language: {
                    noResults: function() {
                        return $("<a href='#' data-toggle='modal' data-target='#m_modal' onClick='CodeForm(this)'>Tambah Baru</a>");
                    }
                },
                ajax: {
                    url: main_url + 'finance/find_code',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.code + '-' + item.type_name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            $("#pic").select2({
                allowClear: true,
                placeholder: "Choose PIC"
            });
            $("#debit").select2({
                allowClear: true,
                placeholder: "Choose Type"
            });
            $(".form-actions").click(function() {
                if (this.value == "Y") {
                    $(".m-form__btn").removeClass("d-none")
                } else {
                    $(".m-form__btn").addClass("d-none")
                }
            });
        }(),
        $("#m_form").validate({
            rules: {
                title: {
                    required: !0
                }
            }
        });
    }
};
jQuery(document).ready(function() {
    FormControls.init();
});


function add_purposes(ele) {

    if ($(ele).data('type') == "tambah_datas") {
        var n_equ = $(".part_description").length;
        n_equ++;
        var gets = ajax_data('finance/pengajuan_pettycash/add_purpose', "&equ=" + n_equ + "&type=" + $(ele).data('type'));
        $(".form_purposes").before(gets);

    } else if ($(ele).data('type') == "hapus_datas") {
        $(".forms_" + $(ele).data('equ')).remove();
    } else if ($(ele).data('type') == "edit_hapus_datas") {
        var gets = ajax_data('finance/pengajuan_pettycash/add_purpose', "&type=" + $(ele).data('type') + "&id_dtl=" + $(ele).data('id_dtl'));
        $(".forms_" + $(ele).data('equ')).remove();
    }

}

function btn_approval(ele) {
    window.location.href = main_url + 'finance/pengajuan_pettycash/' + $(ele).data('id') + "/" + $(ele).data('type') + "/" + $(ele).data('usr') + '/approve';
}


function PrintPettyCash(ele) {
    var ajx = ajax_data("finance/pengajuan_pettycash/download_pettycash/" + $(ele).data("id") + "/print");

}