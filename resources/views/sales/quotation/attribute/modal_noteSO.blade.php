{!! Form::open(['class' => 'form_noteSO']) !!}
<input type="hidden" name="id_quo" value="{{$req->quo}}">
<input type="hidden" name="title" id="titles" value="{{$title}}">
<div class="form-group row">
    <div class="col-lg-12">
        <textarea type="text" class="form-control SumContent" style="font-size:12px" id="note_salesorder"
            name="note_salesorder" placeholder="Enter Note">{!!$main->note_salesorder!!}</textarea>
    </div>
</div>
<div class="text-right">
    <button type="button" class="btn btn-primary btn-saves">Save & Download<i class="far fa-save ml-2"></i></button>
</div>
{!! Form::close() !!}
<script>
$(document).ready(function() {
    $('.SumContent').summernote({
        callbacks: {
            // callback for pasting text only (no formatting)
            onPaste: function(e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData)
                    .getData('Text');
                e.preventDefault();
                bufferText = bufferText.replace(/\r?\n/g, '<br>');
                document.execCommand('insertHtml', false, bufferText);
            }
        }
    });
    $('.SumContent').css("font-family", "arial");
    $('.SumContent').css("font-size", 12);
});
</script>