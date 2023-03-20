var FormControls = {
    init: function() {
        ! function() {
            $("#room_name").select2({
                allowClear: true,
                placeholder: "Pilih Room"
            }), $("#room_name").on("select2:change", function() {
                e.element($(this))
            });
            $('.time').timepicker({});
            $('#end_time').timepicker({});
            $(".dates").datepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    format: "yyyy-mm-dd"
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
                }
            },
        })
    }
};
jQuery(document).ready(function() {
    FormControls.init();
});

function DeleteBooking(ele)
{
    var getdata = ajax_data("receptionist/delete_data", "&id=" + $(ele).data("id"));    
    window.location.replace(main_url+"receptionist/booking_room");
}
