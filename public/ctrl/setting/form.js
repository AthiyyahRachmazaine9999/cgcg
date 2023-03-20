var FormControls= {
    init:function() {
		! function() {
			
        }(), $("#m_form").validate( {
            rules: {
                terminal: {
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
					title: "I hope you know what you do",
					text: "Please be carefull",
					icon: "warning",
					button: "Yes, run it!"
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
