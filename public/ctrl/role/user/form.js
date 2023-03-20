var FormControls = {
    init: function () {
        ! function () {
            $("#id_emp").select2({
                allowClear: true,
                placeholder: "*"
            });
        }(), $("#m_form").validate({
            rules: {
                user_id: {
                    required: !0
                }
            }
            , errorPlacement: function (error, element) {
                if (element.parents("div").hasClass("m-radio-inline")) {
                    error.appendTo(element.parent().parent());
                }
                else {
                    error.insertAfter(element);
                }
            }
            , invalidHandler: function (e, r) {
                var i = $("#m_form_1_msg");
                i.removeClass("m--hide").show()
            }
            , submitHandler: function (e) {
                swal({
                    title: "Are you sure?",
                    text: "Save this data!",
                    icon: "warning",
                    button: "Yes, save it!"
                }).then((result) => {
                    if (result) {
                        e.submit();
                    }
                })
            }
        })
    }
};

jQuery(document).ready(function () {
    FormControls.init();
});
