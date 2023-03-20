var FormControls= {
    init:function() {
		! function() {
			$("#type").select2({
				allowClear: true,
				placeholder: "*"
			}), $("#type").on("select2:change", function() {
				e.element($(this))
			}),
			$("#length").select2({
				allowClear: true,
				placeholder: "*"
			}), $("#length").on("select2:change", function() {
				e.element($(this))
			}),
			$("#price_type1").select2({
				allowClear: true,
			}), $("#price_type").on("select2:change", function() {
				e.element($(this))
			}),
			$("#price_type").select2({
				allowClear: true,
				placeholder: "*"
			}), $("#price_type").on("select2:change", function() {
				e.element($(this))
			}),

			$("#weight").select2({
				allowClear: true,
				placeholder: "*"
			}), $("#weight").on("select2:change", function() {
				e.element($(this))
			}),			
			$("#status").select2({
				allowClear: true,
				placeholder: "*"
			}), $("#status").on("select2:change", function() {
				e.element($(this))
			}),
			$("#manufacturer_id").select2({
				allowClear: true,
				placeholder: "*"
			}), $("#manufacturer_id").on("select2:change", function() {
				e.element($(this))
			}),
			$("#category_id").select2({
				allowClear: true,
				placeholder: "*"
			}), $("#category_id").on("select2:change", function() {
				e.element($(this))
			}),
			$(".catalognormal").hide();
			$("#price_type").on("change", function (e) {
				e.preventDefault();
				var option = $("#price_type").val();
				if (option === 'Harga Normal') {
					$(".price").show();
					$(".catalog").hide();
				}
				else {
					$(".price").show();
					$(".catalognormal").show();
				}
			});

			$("#price_type").on("change", function (e) {
				e.preventDefault();
				var option = $("#price_type").val();
				if (option === 'Harga Normal') {
					$(".normal").show();
					$(".catalog").hide();
				}
				else {
					$(".price").show();
					$(".catalog").show();
				}
			});


			 $("#Date").datepicker({
                todayHighlight: !0, autoclose: !0, format: "yyyy-mm-dd"
		    }),
			$("#price_type").on("change", function (e) {
				e.preventDefault();
			});

			$(".form-actions").click(function() {
				if (this.value == "Y") {
					$(".m-form__btn").removeClass("d-none")
				} 
				else {
					$(".m-form__btn").addClass("d-none")
				}
			});
        }(), $("#m_form").validate( {
            rules: {
                title: {
                    required: !0
                }
            }
			, 
        })
    }
};
jQuery(document).ready(function () {
    FormControls.init();
});

function change(that) {
    if (that.value == "Status") {
        document.getElementById("price").hide;
    } else {
        document.getElementById("status").show;
    }
}

function Catalog() {
	var option = $('#price_type').val();
	if(option=== "Harga Ecatalog"){
    var harga  = $("#price").val();
    var persen = harga * (3/100);
	var hitung = parseFloat(persen)+parseFloat(harga);
    $("#catalog").val(hitung);
	}else{
	$("#catalog").val('');
	}
}
