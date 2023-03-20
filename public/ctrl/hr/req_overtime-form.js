var FormControls = {
    init: function() {
        ! function() {
            $("#employee_id").select2({
                    allowClear: true,
                    placeholder: "*"
                }), $("#employee_id").on("select2:change", function() {
                    e.element($(this))
                }),
                $(".date").datepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    format: "yyyy-mm-dd"
                }),
                $("#division_id").select2({
                    allowClear: true,
                    placeholder: "*"
                }), $("#division_id").on("select2:change", function() {
                    e.element($(this))
                }),

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

function approve_overtime(ele) {
    window.location.href = main_url + "hrm/request/overtime/approve/" + $(ele).data('id') + "/" + $(ele).data('type');
}

function reject_overtime(ele) {
    window.location.href = main_url + "hrm/request/overtime/reject/" + $(ele).data('id');
}