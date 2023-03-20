var FormControls = {
    init: function() {
        ! function() {
            $("#employee_id").select2({
                allowClear: true,
                placeholder: "*"
            }), $("#employee_id").on("select2:change", function() {
                e.element($(this))
            });

            $(".dates").datepicker({
                todayHighlight: !0,
                autoclose: !0,
                format: "yyyy-mm-dd"
            });

            $(".form-actions").click(function() {
                if (this.value == "Y") {
                    $(".m-form__btn").removeClass("d-none")
                } else {
                    $(".m-form__btn").addClass("d-none")
                }
            });
        }(), $("#m_form").validate({
            rules: {
                title: {
                    required: !0
                }
            },
        })
    }
};
jQuery(document).ready(function() {
    FormControls.init();
});

function more_tanggal(ele) {
    var n_equ = $(".add_row_dates").length;
    n_equ++;
    var getdata = ajax_data('hrm/mass_leave/get_more', "&n_equ=" + n_equ);
    $(".point_rows").before(getdata);
}

function delete_tanggal(ele) {
    var equ = $(ele).data('equ');
    $(".adding_row_" + equ).remove();
}