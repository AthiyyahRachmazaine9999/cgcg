var FormControls = {
    init: function() {
        ! function() {
            $("#pro_categories").select2({
                    allowClear: true,
                    placeholder: "Select Category"
                }), $("#pro_categories").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#manufacture").select2({
                    allowClear: true,
                    placeholder: "Select Brand"
                }), $("#manufacture").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#weight").select2({
                    allowClear: true,
                    placeholder: "Select Unit Weight"
                }), $("#weight").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#length").select2({
                    allowClear: true,
                    placeholder: "Select Unit Length"
                }), $("#weight").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#pro_active").select2({
                    allowClear: true,
                    placeholder: "*"
                }), $("#weight").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#pro_priceType").select2({
                    allowClear: true,
                    placeholder: "Select Type"
                }),
                $("#Date").datepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    format: "yyyy-mm-dd"
                }),
                $(".ecatalog").hide();
            $("#pro_priceType").on("change", function(e) {
                e.preventDefault();
                var option = $('option:selected', this).val();
                if (option === '') {
                    $(".ecatalog").hide();
                    $(".product").hide();
                    $(".ecatalog").val('');
                } else if (option === 'Harga Ecatalog') {
                    $(".ecatalog").show();
                    $(".product").show();
                    $(".ecatalog").val('');
                } else {
                    $(".ecatalog").hide();
                    $(".product").show();
                    $(".ecatalog").val('');
                }
            });
            $(".form-actions").click(function() {
                if (this.value == "Y") {
                    $(".m-form__btn").removeClass("d-none")
                } else {
                    $(".m-form__btn").addClass("d-none")
                }
            });
        }(), $("#m_form").validate({
            rules: {
                title: {
                    required: !0
                },
            },
            errorPlacement: function(error, element) {
                if (element.parents("div").hasClass("m-radio-inline")) {
                    error.appendTo(element.parent().parent());
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(e, r) {
                var i = $("#m_form_1_msg");
                i.removeClass("m--hide").show()
            },
            submitHandler: function(e) {
                swal({
                    title: "Are you sure?",
                    text: "Save this data!",
                    icon: "warning",
                    button: "Yes, save it!"
                }).then((result) => {
                    if (result) {
                        e.submit();
                    }
                })
            }
        })
    }
};
jQuery(document).ready(function() {
    FormControls.init();
});

function Catalog() {
    var option = $('#pro_priceType').val();
    if (option === "Harga Ecatalog") {
        var harga = $("#proPrice").val();
        var persen = harga * (3 / 100);
        var hitung = parseFloat(persen) + parseFloat(harga);
        $("#ecatalog").val(hitung);
    } else {
        $("#ecatalog").val('');
    }
}

$('.text_title').hide();
$(".submit_excel").click(function(e) {
    e.preventDefault();

    var data = $('.modal_import_excel').serialize();
    var file = document.getElementById("eksport_file").files;
    var formData = new FormData($(".modal_import_excel")[0]);
    formData.append('file', file[0]);

    var url = main_url + 'product/content/listcontent/imports';
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data, response) {
            $(".excel_show").html(data.view);
            $('.excel_import').modal('toggle');
            $('.btn_links').hide();
            $('.btn_creates').hide();
            $('.text_title').show();
        }
    });
});