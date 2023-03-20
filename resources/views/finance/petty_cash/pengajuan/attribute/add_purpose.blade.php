<div class="part_description forms_{{$n_equ}}">
    <br>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label font-weight-bold">Description {{$n_equ}}</label>
    </div>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label">Description</label>
        <div class="col-lg-7">
            <textarea type="text" name="purpose_add[]" class="form-control" placeholder="Enter Description"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label">Nominal</label>
        <div class="col-lg-7">
            <input type="number" name="nominal_add[]" placeholder="Enter Nominal" class="form-control">
        </div>
    </div>
    <button type="button" onclick="add_purposes(this)" data-equ="{{$n_equ}}" data-type="hapus_datas"
        class="btn bg-danger-400 btn-icon rounded-round legitRipple"><b><i class="fas fa-trash"></i></b></button>

</div>