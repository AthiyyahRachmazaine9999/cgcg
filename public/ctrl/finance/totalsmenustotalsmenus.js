var DatatableCash = {
    init: function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".salescost").DataTable({});
    }
};
jQuery(document).ready(function() {
    
    DatatableCash.init();
});



