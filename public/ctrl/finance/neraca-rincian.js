jQuery(document).ready(function () {
    var aktiva = ajax_data(
        "finance/neraca/detail",
        "&year=" + $("#years").val()+"&type=" + "aktiva"
    );
    var hutang = ajax_data(
        "finance/neraca/detail",
        "&year=" + $("#years").val()+"&type=" + "hutang"
    );
    var labarugitahan = ajax_data(
        "finance/neraca/detail",
        "&year=" + $("#years").val()+"&type=" + "labarugitahan"
    );
    // console.log(hargapokok);
    $(aktiva).insertAfter("#aktiva");
    $(hutang).insertAfter("#hutang");
    $(labarugitahan).insertAfter("#labarugitahan");
    
});

function showDetail(ele) {
    var getdata = ajax_data(
        "finance/neraca/rincian",
        "&code=" + $(ele).data('code')+"&where=" +$(ele).data('where')
    );
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Detail");

        $("#ptable").DataTable({
            autoWidth: false,
            dom      : '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            ordering : true,
            language: {
                search: '<span>Filter:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_ Per Halaman',
                paginate: {
                    'first': 'First',
                    'last': 'Last',
                    'next': '&rarr;',
                    'previous': '&larr;'
                }
            },
            columnDefs: [
                { width: "25%", targets: [0,2] },
            ]
        });
}
