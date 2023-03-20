var Allfunction = {
    init: function () {
		var pending = ajax_data(
            "countpublic",
            "&id=" + $("#Content").data("id") + "&division=" + $("#Content").data("division")
        );
        $("#inidashboard").html(pending);


    },
};


var DataCard = {
    init: function() {
        var pending = ajax_data(
            "data_card",
            "&type=" + $("#dash_mng").data("type") 
        );
        $("#ini_dash_mng").html(pending);
    },
};


jQuery(document).ready(function () {
    Allfunction.init();
    DataCard.init();
});