$(".dates").datepicker({
    todayHighlight: !0,
    autoclose: !0,
    format: "yyyy-mm-dd"
});

$(".type_tax").select2({
        allowClear: true,
        placeholder: "*"
}), $(".type_tax").on("select2:change", function() {
        e.element($(this))
});
