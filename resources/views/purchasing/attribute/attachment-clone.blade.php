<div id="form_item_{{ $n_equ }}" class="row ml-3 mr-3 formedit">
    <div class="col-lg-12">
        <div class="row">
            <input type="hidden" id="idpo" name="idpo" value="{{$idpo}}">
        </div>
        <div class="row mt-3">
            <div class="col-lg-10">
                <input type='file' name='lampiran[]' class='form-control'>
            </div>
            <div class="col-lg-2">
                <button type="button" onclick="remove_attach({{$n_equ}})" class="btn bg-pink-400 btn-icon rounded-round legitRipple"><i class="icon-trash-alt"></i></button>
            </div>
        </div>
    </div>
</div>