var DatatableCash = {
    init: function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".salescostdetail").DataTable({});
    }
};
jQuery(document).ready(function() {
    
    DatatableCash.init();
});





function set_Complete(ele) {
    var getdata = ajax_data('finance/settlement/complete_sets', "&id=" + $(ele).data('id'));
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Detail")
}
