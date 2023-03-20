var GetNew = {
    init: function () {
        $("#number").on("keyup", function () {
            if ($(this).val().length == 6) {
                console.log("Its 6 characters, we are submitting..");
                var markup =
                    "<tr><td class='text-center'><input type='checkbox' name='record'></td>\
      <td><input type='hidden' name='number[]' value='" +
                    number +
                    "'>" +
                    barang +
                    "</td>";
                $("table tbody").append(markup);
                document.getElementById("number").value = "";
            } else {
                alert("error");
            }
        });
    },
};
jQuery(document).ready(function () {
    GetNew.init();
});

// function ScanBarcode(ele) {
//     var getdata = ajax_data(
//         "warehouse/warehouse_outbound/scan",
//         "&quo=" +
//             $(ele).data("quo") +
//             "&product=" +
//             $(ele).data("product") +
//             "&no_wh_out=" +
//             $(ele).data("no_wh_out")
//     );

//     $("#modalbody2").html(getdata),
//         $("#modaltitle2").html(
//             "SN Scanner - " +
//                 $(ele).data("namebarang") +
//                 " - " +
//                 $(ele).data("no_wh_out")
//         );

//     $("#m_modal2").on("hidden.bs.modal", function () {
//         $("#m_modal").modal("show");
//     });

//     var hasnumber = $("#hasnumber").val();
//     if (hasnumber == 0) {
//         var b = [];
//     } else {
//         var b = [];
//         $(".getsn tr").each(function () {
//             var currentRow = $(this);

//             var col2_value = currentRow.find("td:eq(1)").text();
//             b.push(col2_value);
//         });
//     }
//     var qty = $(ele).data("qty") - 1;
//     $("#number").keyup(delay(function (e) {
//         console.log($("#number").val());
//         var sisascan = document.getElementById("sisascan").innerHTML;
//         if (sisascan > 0) {
//             if ($(this).val().length > 6) {
//                 var number = $("#number").val();
//                 if (b.includes(number)) {
//                     alert("Sudah ada SNnya");
//                 } else {
//                     b.push(number);
//                     var markup =
//                         "<tr><td class='text-center'><input type='checkbox' name='record'></td>\
//                   <td><input type='hidden' name='number[]' value='" +
//                         number +
//                         "'>" +
//                         number +
//                         "</td></tr>";
//                     $("#sntable tbody").append(markup);
//                     var newqty = qty--;
//                     console.log(newqty);
//                     $("#sisascan").html(newqty);
//                     document.getElementById("number").value = "";
//                 }
//             } else {
//                 alert("SN tidak valid, terlalu pendek");
//             }
//         } else {
//             alert("Sudah semua SN diinput");
//         }
//     },500));
//     $("#delete-row").click(function () {
//         alert("clicked");
//         $("#sntable tbody")
//             .find('input[name="record"]')
//             .each(function () {
//                 if ($(this).is(":checked")) {
//                     $(this).parents("tr").remove();
//                 }
//             });
//     });
// }


function ScanBarcode(ele) {
 var type = $(ele).data('type');
    if(type == "inbound")
    {
    var getdata = ajax_data(
        "warehouse/warehouse_outbound/scan_inbound",
        "&quo=" +
            $(ele).data("quo") +
            "&product=" +
            $(ele).data("product") +
            "&id_inbound=" +
            $(ele).data("id_inbound")+
            "&id_po=" +
            $(ele).data("po")
    );

    $("#modalbody").html(getdata),
        $("#modaltitle").html(            
            "SN Scanner - " +
                $(ele).data("namebarang")
);
    
    
    $("#m_modal2").on("hidden.bs.modal", function () {
        $("#m_modal").modal("show");
    });

    var hasnumber = $("#hasnumber").val();
    if (hasnumber == 0) {
        var b = [];
    } else {
        var b = [];
        $(".getsn tr").each(function () {
            var currentRow = $(this);

            var col2_value = currentRow.find("td:eq(1)").text();
            b.push(col2_value);
        });
    }

    var qty = $(ele).data('qty') - $(ele).data("scan") - 1;
    $("#number_in").keyup(delay(function (e) {
        console.log($("#number_in").val());
        var sisascan = document.getElementById("sisascan").innerHTML;
        if (sisascan > 0) {
            if ($(this).val().length >= 6) {
                var number = $("#number_in").val();
                if (b.includes(number)) {
                    alert("Sudah ada SNnya");
                } else {
                    b.push(number);
                    var markup =
                        "<tr><td class='text-center'><input type='checkbox' name='record'></td>\
                            <td><input type='hidden' name='number[]' value='" +
                        number + "'>" + number + "</td></tr>";
                    $("#sntable tbody").append(markup);
                    var newqty = qty--;
                    console.log(newqty);
                    $("#sisascan").html(newqty);
                    document.getElementById("number_in").value = "";
                }
            } else {
                alert("SN tidak valid, terlalu pendek");
            }
        } else {
            alert("Sudah semua SN diinput");
        }
    },500));
    $("#delete-row").click(function () {
        alert("clicked");
        $("#sntable tbody")
            .find('input[name="record"]')
            .each(function () {
                if ($(this).is(":checked")) {
                    $(this).parents("tr").remove();
                }
            });
    });


    }else{

    var getdata = ajax_data(
        "warehouse/warehouse_outbound/scan",
        "&quo=" +
            $(ele).data("quo") +
            "&product=" +
            $(ele).data("product") +
            "&no_wh_out=" +
            $(ele).data("no_wh_out") +
            "&id_out=" +
            $(ele).data("id_outbound") +
            "&id_out_det=" +
            $(ele).data("id_out_det") 
    );

    $("#modalbody2").html(getdata),
        $("#modaltitle2").html(
            "SN Scanner - " +
                $(ele).data("namebarang") +
                " - " +
                $(ele).data("no_wh_out")
        );

    $("#m_modal2").on("hidden.bs.modal", function () {
        $("#m_modal").modal("show");
    });

    var hasnumber = $("#hasnumber").val();
    if (hasnumber == 0) {
        var b = [];
    } else {
        var b = [];
        $(".getsn tr").each(function () {
            var currentRow = $(this);

        var col2_value = currentRow.find("td:eq(1)").text();
            b.push(col2_value);
        });
    }


    var qty = $(ele).data('qty') - $(ele).data("scan") + 1;
    $("#number").keyup(delay(function (e) {
        var sisascan = document.getElementById("sisascan").innerHTML;
        if (sisascan > 0) {
            if ($(this).val().length > 6) {
                var number = $("#number").val();                
                if (b.includes(number)) {
                  $.ajax({
                    type: "POST",
                    url: main_url + "warehouse/warehouse_outbound/searchSN",
                    data: {
                        no_wh   : $(ele).data("no_wh_out"),
                        quo     : $(ele).data("quo"),
                        product : $(ele).data("product"),
                        s_num   : number,
                        '_token': token
                    },
                    success: function (data, response) {
                        console.log(data, response)
                        b.push(number);
                        var markup =
                            "<tr><td style='width: 5%;'><input type='checkbox' class='scan_checked' name='record' data-number='" + number + "' id='select_box' checked></td>\
                                <td><input type='hidden' class='number_check' name='number[]' value='" + number + "'>" + number + "</td><tr>";
                        
                                
                        $("#sntable tbody").append(markup, $("#item_sn_" + number).hide());

                        $("#sisascan").html($("#ini_sisascan").val());
                        document.getElementById("number").value = "";
                    }
                })

                } else {
                    alert("Serial Number Tidak Ada di Database");
                }
            } else {
                alert("SN tidak valid, terlalu pendek");
            }
        } else {
            alert("Sudah semua SN diinput");
        }
    },500));
    $("#delete-row").click(function () {
        alert("clicked");
        $("#sntable tbody")
            .find('input[name="record"]')
            .each(function () {
                if ($(this).is(":checked")) {
                    $(this).parents("tr").remove();
                }
            });
    });
}
}



function delay(callback, ms) {
    var timer = 0;
    return function() {
      var context = this, args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        callback.apply(context, args);
      }, ms || 0);
    };
  }

function saveSN(ele) {
    var datas = $('div :input').serializeArray();
    // console.log(datas);
    var sisascan = document.getElementById("sisascan").innerHTML;
    var histogram = {};

    for ( var n = 0; n <datas.length; n++ )
    {
    if ( !histogram[datas[n].name] )
        histogram[datas[n].name] = 1;
    else
        histogram[datas[n].name] ++;
    }

    if(histogram['number[]'] > sisascan)
    {
       alert("Serial Number Melebihi Sisa Scan");
    }else{
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "POST",
        url: main_url + "warehouse/warehouse_outbound/savesn",
        data: datas,
        success: function (result) {
            if (result == "oke") {
                $("#m_modal2").modal("toggle");
                $("#m_modal").modal("show");
                swal({
                    title: "Success",
                    text: "Berhasil save",
                    icon: "success",
                    timer: 2000,
                });
            } else {
                swal({
                    title: "Oops",
                    text: "Gagal simpan serial number",
                    icon: "warning",
                });
            }
        },
    });
    }
}


function saveSNInbound(ele) {
    var datas = $('#m_form_in').serializeArray();
    console.log(datas);
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "POST",
        url: main_url + "warehouse/warehouse_inbound/savesn",
        data: datas,
        success: function (result) {
            if (result == "oke") {
                $("#m_modal").modal("toggle");
                swal({
                    title: "Success",
                    text: "Berhasil save",
                    icon: "success",
                    timer: 2000,
                });
            } else {
                swal({
                    title: "Oops",
                    text: "Gagal simpan serial number",
                    icon: "warning",
                });
            }
        },
    });
}



function DeleteSN(ele) {
    var type = $(ele).data("type");
    if(type == "inbound")
    {
        deleteSN_inbound(ele);
    }else{
        deleteSN_outbound(ele);
    }
}


function deleteSN_inbound(ele)
{
    swal({
        title: "Delete SN ?",
        text: "Setelah di delete, serial number tidak bisa di kembalikan",
        icon: "warning",
        button: {
            cancel: {
                text: "Cancel",
                value: null,
                visible: false,
                className: "",
                closeModal: true,
            },
            confirm: {
                text: "OK",
                value: true,
                visible: true,
                className: "",
                closeModal: true,
            },
        },
    }).then((willDelete) => {
        if (willDelete) {
            var getdata = ajax_data(
                "warehouse/warehouse_outbound/deletesn",
                "&idsn=" + $(ele).data("id") +
                "&type=" + $(ele).data("type") 
            );
            if (getdata == "oke") {
                $("#m_modal").modal("toggle");
                swal("Nice, nomer sn berhasil di hapus", {
                    icon: "success",
                    timer: 2000,
                });
            } else {
                swal("Ups, ada yang salah, error:" + getdata, {
                    icon: "warning",
                });
            }
        } else {
            swal("Delete dibatalkan");
        }
    });
}


function deleteSN_outbound(ele)
{
    swal({
        title: "Yakin ?",
        text: "Serial number masih di pilih lagi, jika tidak melebihi quantity kirim",
        icon: "warning",
        button: {
            cancel: {
                text: "Cancel",
                value: null,
                visible: false,
                className: "",
                closeModal: true,
            },
            confirm: {
                text: "OK",
                value: true,
                visible: true,
                className: "",
                closeModal: true,
            },
        },
    }).then((willDelete) => {
        if (willDelete) {
            var getdata = ajax_data(
                "warehouse/warehouse_outbound/deletesn",
                "&idsn=" + $(ele).data("id") +
                "&type=" + $(ele).data("type") 
            );
            if (getdata == "oke") {
                $("#m_modal").modal("toggle");
                swal("Nice, nomer sn berhasil di hapus", {
                    icon: "success",
                    timer: 2000,
                });
            } else {
                swal("Ups, ada yang salah, error:" + getdata, {
                    icon: "warning",
                });
            }
        } else {
            swal("Delete dibatalkan");
        }
    });
}



function DeleteSN(ele) {
    swal({
        title: "Delete SN ?",
        text: "Setelah di delete, serial number tidak bisa di kembalikan",
        icon: "warning",
        button: {
            cancel: {
                text: "Cancel",
                value: null,
                visible: false,
                className: "",
                closeModal: true,
            },
            confirm: {
                text: "OK",
                value: true,
                visible: true,
                className: "",
                closeModal: true,
            },
        },
    }).then((willDelete) => {
        if (willDelete) {
            var getdata = ajax_data(
                "warehouse/warehouse_outbound/deletesn",
                "&idsn=" + ele
            );
            if (getdata == "oke") {
                $("#m_modal").modal("toggle");
                swal("Nice, nomer sn berhasil di hapus", {
                    icon: "success",
                    timer: 2000,
                });
            } else {
                swal("Ups, ada yang salah, error:" + getdata, {
                    icon: "warning",
                });
            }
        } else {
            swal("Delete dibatalkan");
        }
    });
}

function DownloadSN(ele) {
    // var getdata = ajax_data(
    //     "warehouse/warehouse_outbound/downloadsn",
    //     "&id=" + $(ele).data("id")
    // );
    $.ajax({
        type: "POST",
        url: main_url + "warehouse/warehouse_outbound/downloadsn",
        data: {
            id:$(ele).data("id"),
            '_token': token
        },
        xhrFields: { responseType: 'blob' },
        success: function (data) {
            var blob = new Blob([data]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "MEG - "+$(ele).data("id")+".xlsx";
            link.click();
        }
    })
}