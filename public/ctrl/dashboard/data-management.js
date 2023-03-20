var Allfunction = {
    init: function() {
        var pending = ajax_data(
            "data_card", "&type="+("#dash_mng").data("type")
        );
        console.log(pending);
        $("#ini_dash_mng").html(pending);
    },
};

jQuery(document).ready(function() {
    Allfunction.init();
});