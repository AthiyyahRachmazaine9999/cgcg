function AllStatus(ele) {
    var getdata = ajax_data("sales/quotation/activity", "&quo=" + $(ele).data("id"));

    $("#modalbody").html(getdata),
        $("#modaltitle").html("History Paket");
}
function NewStatus(ele) {
    var getdata = ajax_data("sales/quotation/activity_new", "&quo=" + $(ele).data("id"));

    $("#modalbody").html(getdata),
        $("#modaltitle").html("Catatan Baru");
}