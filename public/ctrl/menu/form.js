var FormControls= {
    init:function() {
		! function() {
			$("#parent_id").select2({
				allowClear: true,
				placeholder: "*"
			}), $("#parent_id").on("select2:change", function() {
				e.element($(this))
			}),
			$("#icon_id").select2({
			 	allowClear: true,
				placeholder: "--Please Select--"
		  	}).change(function(){
				$("#icon_id").html('<option value=""></option>');
			}),
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
			, errorPlacement: function(error, element) {
				if (element.parents("div").hasClass("m-radio-inline")) {
					error.appendTo( element.parent().parent() );
				}
				else {
					error.insertAfter(element);
				}
			}
            , invalidHandler:function(e, r) {
                var i=$("#m_form_1_msg");
                i.removeClass("m--hide").show()
            }
            , submitHandler:function(e) {				
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
	$("#parent_id").change(function() {
		$("#sequence_to").val(ajax_data("ajax/ui_menus", "&func=menu_sequence&parent_id=" + this.value));
	})
});

function setIcon(ele) {
	$("#icon_id").html('<option value="' + $(ele).data("id") + '">' + $(ele).data("title") + '</option>');
	$('#m_modal_icons').modal('hide');
}