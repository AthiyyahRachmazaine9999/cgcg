function EditDocument(ele) {
    if($(ele).data('lvl')=="migrate")
    {
        var getdata = ajax_data(
            "migration/backup/document_migrate",
            "&quo=" + $(ele).data("id")
        );
        }else{
        var getdata = ajax_data(
            "sales/quotation/document",
            "&quo=" + $(ele).data("id")
        );
    }
    $("#modalbody").html(getdata),
    $("#modaltitle").html("Edit Data Document");
    document.getElementById('showpo').style.visibility = 'hidden';    
    document.getElementById('showsp').style.visibility = 'hidden';    
    document.getElementById('showspk').style.visibility = 'hidden';    
    document.getElementById('showbast').style.visibility = 'hidden';    
    document.getElementById('showfp').style.visibility = 'hidden';    
    document.getElementById('showfj').style.visibility = 'hidden'; 
}

function upload_document(ele) {
$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });       
    var getdata1 = ajax_data("sales/quotation/document_upload",
    "&quo=" + $(ele).data("id") + 
    "&type=" + $(ele).data("type"));
    var val =$(ele).data("type");
    $("#modaltitle3").html(getdata1);
    myDropzone = new Dropzone('div#imageUpload', {
        autoProcessQueue: false,
        uploadMultiple: false,
        maxFiles: 1,
        acceptedFiles: ".pdf",
        maxFilesize: 8, // MB
        addRemoveLinks: true,
        dictDefaultMessage: "Drag Your File Here",
        dictRemoveFile: 'Remove',
        dictFileTooBig: 'File is bigger than 8MB',
        clickable: true,
        url: 'saveFile',
        init: function (file) {
        var myDropzone = this;
        $("#UploaderBtn").click(function (e) {
            e.preventDefault();
            if ( $("form[name='demoform']").serialize() ) {
                myDropzone.processQueue();
            }
            return false;
        });

        this.on('sending', function (file, xhr, formData) {
            var data =$("form[name='demoform']").serializeArray();
            $.each(data, function (key, el) {
                formData.append(el.name, el.value);
            });
            console.log(formData);
            swal( 'Success!',
            'File Uploded Successfully!',
            'success').then(function(){ 
                $('#m_modal3').modal('toggle');
                $('#m_modal').css('overflow-y', 'auto');
                if(val=="sp"){
                    document.getElementById('showsp').style.visibility = 'visible';    
                    $("#uploadsp").prop("disabled", true);
                }else if(val=="po"){
                    document.getElementById('showpo').style.visibility = 'visible';    
                    $("#uploadpo").prop("disabled", true);
                }else if(val=="spk"){
                    document.getElementById('showspk').style.visibility = 'visible';    
                    $("#uploadspk").prop("disabled", true);
                }else if(val=="bast"){
                    document.getElementById('showbast').style.visibility = 'visible';    
                    $("#uploadbast").prop("disabled", true);
                }else if (val=="fakturpajak"){
                    document.getElementById('showfp').style.visibility = 'visible';    
                    $("#uploadfp").prop("disabled", true);
                }else{
                    document.getElementById('showfj').style.visibility = 'visible';    
                    $("#uploadfj").prop("disabled", true);
                }
            });
        });

    },
});
}

function classic_upload(ele)
{    
    var ajx = ajax_data("sales/quotation/classic_upload",
    "&quo=" + $(ele).data("id") + 
    "&type=" + $(ele).data("type"));
    var val =$(ele).data("type");
    $("#modalbody4").html(ajx);
    $("#modaltitle4").html("Upload File");
        $("#ClassicBtn").click(function (e) {
        var data = new FormData($("form[name='demoform']")[0]);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: main_url + 'sales/quotation/saveFileClassic',
            type: "POST",
            dataType: "json",
            data: data,
            processData: false,
            contentType: false,
            success: function(data, response) {
                // console.log(response, data);
                if(response)
                {
                $('#m_modal4').modal('toggle');
                $('#m_modal').css('overflow-y', 'auto');
                if(val=="sp"){
                    document.getElementById('showsp').style.visibility = 'visible';    
                    $("#uploadsp").prop("disabled", true);
                }else if(val=="po"){
                    document.getElementById('showpo').style.visibility = 'visible';    
                    $("#uploadpo").prop("disabled", true);
                }else if(val=="spk"){
                    document.getElementById('showspk').style.visibility = 'visible';    
                    $("#uploadspk").prop("disabled", true);
                }else if(val=="bast"){
                    document.getElementById('showbast').style.visibility = 'visible';    
                    $("#uploadbast").prop("disabled", true);
                }else if (val=="fakturpajak"){
                    document.getElementById('showfp').style.visibility = 'visible';    
                    $("#uploadfp").prop("disabled", true);
                }else{
                    document.getElementById('showfj').style.visibility = 'visible';    
                    $("#uploadfj").prop("disabled", true);
                }                
            }
            }

        })
    });
}




function up_Migrate_doc(ele) {
$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });       
    var getdata1 = ajax_data("migration/backup/document_upload",
    "&quo=" + $(ele).data("id") + 
    "&type=" + $(ele).data("type"));
    var val =$(ele).data("type");
    $("#modaltitle3").html(getdata1);
    myDropzone = new Dropzone('div#imageUpload', {
        autoProcessQueue: false,
        uploadMultiple: false,
        maxFiles: 1,
        acceptedFiles: ".pdf",
        maxFilesize: 8, // MB
        addRemoveLinks: true,
        dictDefaultMessage: "Drag Your File Here",
        dictRemoveFile: 'Remove',
        dictFileTooBig: 'File is bigger than 8MB',
        clickable: true,
        url: 'saveFile',
        init: function (file) {
        var myDropzone = this;
        $("#UploaderBtn").click(function (e) {
            e.preventDefault();
            if ( $("form[name='demoform']").serialize() ) {
                myDropzone.processQueue();
            }
            return false;
        });

        this.on('sending', function (file, xhr, formData) {
            var data =$("form[name='demoform']").serializeArray();
            $.each(data, function (key, el) {
                formData.append(el.name, el.value);
            });
            console.log(formData);
            swal( 'Success!',
            'File Uploded Successfully!',
            'success').then(function(){ 
                $('#m_modal3').modal('toggle');
                if(val=="sp"){
                    document.getElementById('showsp').style.visibility = 'visible';    
                    $("#uploadsp").prop("disabled", true);
                }else if(val=="po"){
                    document.getElementById('showpo').style.visibility = 'visible';    
                    $("#uploadpo").prop("disabled", true);
                }else if(val=="spk"){
                    document.getElementById('showspk').style.visibility = 'visible';    
                    $("#uploadspk").prop("disabled", true);
                }else if(val=="bast"){
                    document.getElementById('showbast').style.visibility = 'visible';    
                    $("#uploadbast").prop("disabled", true);
                }else if (val=="fakturpajak"){
                    document.getElementById('showfp').style.visibility = 'visible';    
                    $("#uploadfp").prop("disabled", true);
                }else{
                    document.getElementById('showfj').style.visibility = 'visible';    
                    $("#uploadfj").prop("disabled", true);
                }
            });
        });

    },
});
}

// var data= document.getElementbyId('Check');
// data.show();

