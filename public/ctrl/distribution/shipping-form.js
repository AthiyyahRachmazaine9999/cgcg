var FormControls = {
    init: function () {
        ! function () {
            $("#province").select2({
                allowClear: true,
                placeholder: "Pilih Provinsi"
            }), $("#city").select2({
                allowClear: true,
                placeholder: "Pilih Kota"
            }),$("#country").select2({
                allowClear: true,
                placeholder: "Pilih Kecamatan"
            });
        }(), $("#m_form").validate({
            rules: {
                province: {
                    required: !0
                },city: {
                    required: !0
                },
            }
            , errorPlacement: function (error, element) {
                if (element.parents("div").hasClass("m-radio-inline")) {
                    error.appendTo(element.parent().parent());
                }
                else {
                    error.insertAfter(element);
                }
            }
            , invalidHandler: function (e, r) {
                var i = $("#m_form_1_msg");
                i.removeClass("m--hide").show()
            }
            , submitHandler: function (e) {
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

jQuery(document).ready(function () {
    FormControls.init();
});

$("#province").on("change", function (e) {
    e.preventDefault();
    var option = $('option:selected', this).val();
    $("#city").prop("disabled", false);
    $('#city option').remove();
    $('#country option').remove();
    if (option === '') {
        $("#city").prop("disabled", true);
        $("#country").prop("disabled", true);
    }
    else {
        getKota(option);
    }
});
$("#city").on("change", function(e){
    e.preventDefault();
    var option    = $('option:selected', this).val(); 
    $("#country").prop("disabled", false);    
    $('#country option').remove();
    if(option==='')
    {
        $("#country").prop("disabled", true);
    }
    else
    {        
      getCamat(option);
    }
  });

function getKota(option) {
    $(function () {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: main_url + 'api/location/get_city',
            type: "POST",
            dataType: "json",
            data: { id: option },
            cache: false,
            success: function (response) {
                var html = '';
                var i;
                $('#city').append('<option value="" selected disabled>Pilih Kota</option>');
                for (i = 0; i < response.length; i++) {
                    $('#city').append('<option value="' + response[i].id + '">' + response[i].kota + '</option>');
                }

            }
        });
    });
}

function getCamat(option) {
    $(function() {
      $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: main_url + 'api/location/get_kecamatan',
        type: "POST",
        dataType: "json", 
        data:{id:option}, 
        cache: false,
        success: function(response) {
          var html = '';
          var i;
          $('#country').append('<option value="">Pilih Kecamatan</option>'); 
          for(i=0; i<response.length; i++){
            $('#country').append('<option value="'+response[i].id+'">'+response[i].nama+'</option>'); 
          }

        }
      });
    });
  }