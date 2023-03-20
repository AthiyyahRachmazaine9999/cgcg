var DatatableEmployee = {
    init: function () {
        $("#jenis").select2({
            allowClear: true,
            placeholder: "Jenis data download"
        })
    },
};
jQuery(document).ready(function () {
    DatatableEmployee.init();
});
