var FormControls = {
    init: function() {
        ! function() {
            $("#employee_id").select2({
                allowClear: true,
                placeholder: "*"
            }), $("#employee_id").on("select2:change", function() {
                e.element($(this))
            });
            $(".leaves").select2({
                allowClear: true,
                placeholder: "*"
            }), $(".leaves").on("select2:change", function() {
                e.element($(this))
            });
            $('#timepicker').timepicker({});
            $("#division_id").select2({
                    allowClear: true,
                    placeholder: "*"
                }), $("#division_id").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#purpose_permit").select2({
                    allowClear: true,
                    placeholder: "*"
                }), $("#purpose_permit").on("select2:change", function() {
                    e.element($(this))
                }),

                $("#type_leave").select2({
                    allowClear: true,
                    placeholder: "*"
                }), $("#type_leave").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#purpose_leave_array").select2({
                    allowClear: true,
                    placeholder: "choose your purpose"
                }), $("#purpose_leave_array").on("select2:change", function() {
                    e.element($(this))
                }),

                $("#type_date").select2({
                    allowClear: true,
                    placeholder: "*"
                }), $("#type_date").on("select2:change", function() {
                    e.element($(this))
                }),
                $(".date").datepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    format: "yyyy-mm-dd"
                }),

                $("#type1").on("change", function(e) {
                    e.preventDefault();
                    var option = $('option:selected', this).val();
                    if (option === 'Annual Leave') {
                        $("#row_note").hide();
                    } else if (option === 'Special Leave') {
                        $("#row_note").show();
                    } else {
                        $("#row_note").show();
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
                }
            },
        })
    }
};
jQuery(document).ready(function() {
    FormControls.init();
});

        $(document).ready(function() {
            $('#date_from1, #date_finish1').on('change', function() {
                if (($("#date_from1").val() != "") && ($("#date_finish1").val() != "")) {
                    var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
                    var firstDate = new Date($("#date_from1").val());
                    var secondDate = new Date($("#date_finish1").val());
                    var current = firstDate;
                    var daysWithOutWeekEnd = [];
                    for (var currentDate = new Date(firstDate); currentDate <= secondDate; currentDate.setDate(currentDate.getDate() + 1)) {
                        // console.log(currentDate);
                        if (currentDate.getDay() != 0 && currentDate.getDay() != 6) {
                            daysWithOutWeekEnd.push(new Date(currentDate));
                        }
                    }
                    $("#cuti").val(daysWithOutWeekEnd.length + " HARI");
                }
            });
        });



// $(".leaves").val('').trigger('change');
$(".leaves").on("change", function(e) {
    e.preventDefault();
    var option = $('option:selected', this).val();
    console.log(option);
    if (option === '') {
        $(".leaves").trigger('change').val('');
    } else if (option === 'Late Permission') {
        var aq = ajax_data("hrm/request/get_late");
        $('.next_form').html(aq);
        $("#type_leave").select2({
            allowClear: true,
            placeholder: "*"
        });
        $('#timepicker').timepicker({
            'timeFormat': 'H:i A',
        });
    } else if (option === 'Permission') {
        var prospectRes = ajax_data("hrm/request/get_permission");
        $('.next_form').html(prospectRes);
        $("#type_date").select2({
            allowClear: true,
            placeholder: "*"
        }), $("#type_date").on("select2:change", function() {
            e.element($(this))
        });
        $("#type_date").on("change", function(e) {
            e.preventDefault();
            var option = $('option:selected', this).val();
            if (option === '') {
                $("#date_finish2").val('');
            } else if (option === 'Today') {
                const date = new Date();
                const tanggal = date.getDate() + "/" + ("0" + (date.getMonth() + 1)).slice(-2) + "/" + date.getFullYear();
                $("#date_finish2").val(tanggal);
                $("#date_finish2").attr('readonly', true);
            } else if (option === 'Tomorrow') {
                const date = new Date();
                const tanggal = (date.getDate() + 1) + "/" + ("0" + (date.getMonth() + 1)).slice(-2) + "/" + date.getFullYear();
                $("#date_finish2").val(tanggal);
                $("#date_finish2").attr('readonly', true);
            }
        });

        $("#purpose_permit").select2({
            allowClear: true,
            placeholder: "*"
        }), $("#purpose_permit").on("select2:change", function() {
            e.element($(this))
        });
    } else if (option === 'Annual Leave') {
        var prospectRes = ajax_data("hrm/request/get_annual");
        $('.next_form').html(prospectRes);
        $(".date").datepicker({
            todayHighlight: !0,
            autoclose: !0,
            format: "yyyy-mm-dd"
        });
        $(document).ready(function() {
            $('#date_from1, #date_finish1').on('change', function() {
                if (($("#date_from1").val() != "") && ($("#date_finish1").val() != "")) {
                    var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
                    var firstDate = new Date($("#date_from1").val());
                    var secondDate = new Date($("#date_finish1").val());
                    var current = firstDate;
                    var daysWithOutWeekEnd = [];
                    for (var currentDate = new Date(firstDate); currentDate <= secondDate; currentDate.setDate(currentDate.getDate() + 1)) {
                        // console.log(currentDate);
                        if (currentDate.getDay() != 0 && currentDate.getDay() != 6) {
                            daysWithOutWeekEnd.push(new Date(currentDate));
                        }
                    }
                    $("#cuti").val(daysWithOutWeekEnd.length + " HARI");
                }
            });
        });
    } else if (option === "Special Leave") {
        var prospectRes = ajax_data("hrm/request/get_special");
        $('.next_form').html(prospectRes);
        $("#purpose_leave_array").select2({
            allowClear: true,
            placeholder: "choose your purpose"
        }), $("#purpose_leave_array").on("select2:change", function() {
            e.element($(this))
        });
        $("#purpose_leave_array").on("change", function(e) {
            e.preventDefault();
            var ids = $('option:selected', this).val();
            console.log(ids);
            if (ids != "") {
                getValueChance(ids);
            }
        });
        $(".date").datepicker({
            todayHighlight: !0,
            autoclose: !0,
            format: "yyyy-mm-dd"
        });
        $(document).ready(function() {
            $('#date_from1, #date_finish1').on('change', function() {
                if (($("#date_from1").val() != "") && ($("#date_finish1").val() != "")) {
                    var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
                    var firstDate = new Date($("#date_from1").val());
                    var secondDate = new Date($("#date_finish1").val());
                    var current = firstDate;
                    var daysWithOutWeekEnd = [];
                    for (var currentDate = new Date(firstDate); currentDate <= secondDate; currentDate.setDate(currentDate.getDate() + 1)) {
                        // console.log(currentDate);
                        if (currentDate.getDay() != 0 && currentDate.getDay() != 6) {
                            daysWithOutWeekEnd.push(new Date(currentDate));
                        }
                    }
                    if($(".hide_chances").val() < daysWithOutWeekEnd.length)
                    {
                        swal(
                            'Oops..',
                            'Kesempatan Cuti Hanya '+$(".chances").val(),
                            'error'
                        )
                    }else{
                        $("#cuti").val(daysWithOutWeekEnd.length + " HARI");
                    }
                }
            });
        });
    }
});


function reject_leave(ele) {
    window.location.href = main_url + "hrm/request/leave/reject/" + $(ele).data('id') + "/" + $(ele).data('type');
}

function approve_leave(ele) {
    window.location.href = main_url + "hrm/request/leave/approve/" + $(ele).data('id') + "/" + $(ele).data('type');
}

function getValueChance(ids) {
    $(function() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: main_url + 'hrm/request/value_chance',
            type: "POST",
            dataType: "json",
            data: { id: ids },
            cache: false,
            success: function(response) {
                console.log(response);
                var html = '';
                var i;
                $(".chances").val(response.data.days + " Hari");
                $(".hide_chances").val(response.data.days);
            }
        });
    });
}