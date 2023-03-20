jQuery(document).ready(function () {
    var hargapokok = ajax_data(
        "finance/neraca/labarugi/detail",
        "&year=" + $("#years").val()+"&type=" + "hargapokok"
    );
    var hargapemasaran = ajax_data(
        "finance/neraca/labarugi/detail",
        "&year=" + $("#years").val()+"&type=" + "hargapemasaran"
    );
    var hargaadmin = ajax_data(
        "finance/neraca/labarugi/detail",
        "&year=" + $("#years").val()+"&type=" + "hargaadmin"
    );
    var hargaincome = ajax_data(
        "finance/neraca/labarugi/detail",
        "&year=" + $("#years").val()+"&type=" + "hargaincome"
    );
    var hargaexpense = ajax_data(
        "finance/neraca/labarugi/detail",
        "&year=" + $("#years").val()+"&type=" + "hargaexpense"
    );
    // console.log(hargapokok);
    $(hargapokok).insertAfter("#hargapokok");
    $(hargapemasaran).insertAfter("#hargapemasaran");
    $(hargaadmin).insertAfter("#hargaadmin");
    $(hargaincome).insertAfter("#hargaincome");
    $(hargaexpense).insertAfter("#hargaexpense");

    $('#totalpokok').val();
    
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
