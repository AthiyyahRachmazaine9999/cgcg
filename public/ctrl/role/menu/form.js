var FormControls = {
    init: function () {
        ! function () {
            $("#user_id").select2({
                allowClear: true,
                placeholder: "*"
            }), $("#parent_id").on("select2:change", function () {
                e.element($(this))
            }),
                $('.tree-hie').fancytree({
                    checkbox: true,
                    selectMode: 3,
                    select: function (event, data) {

                        var selNodes = data.tree.getSelectedNodes();
                        var selKeys = $.map(selNodes, function (node) {
                            return node.title;
                        });
                        console.log(selKeys);
                        $("#getdata").val(selKeys);
                    }
                }),
                $("#checkall").click(function () {
                    $.ui.fancytree.getTree(".tree-hie").selectAll();
                    return false;
                }),
                $("#uncheck").click(function () {
                    $.ui.fancytree.getTree(".tree-hie").selectAll(false);
                    return false;
                })
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
