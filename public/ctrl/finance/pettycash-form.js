var FormControls = {
    init: function() {
        ! function() {
            $(".dates").datepicker({
                autoclose: !0,
                format: "yyyy-mm-dd",
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

function CodeForm(ele) {
    $(".codes").select2("close");
    var prospectRes = ajax_data("finance/new_code")

    $("#modalbody").html(prospectRes),
        $("#modaltitle").html("Tambah Code")

}