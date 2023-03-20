<div class="add_row_dates adding_row_{{$n_equ}}">
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'></label>
        <div class="col-lg-3">
            <input type="text" name="date_add[]" class="form-control dates" placeholder="Masukkan Tanggal">
        </div>
        <button type="button" onclick="delete_tanggal(this)" data-equ="{{$n_equ}}" data-type="delete"
            class="btn bg-danger-300 btn-icon rounded-round legitRipple"><b><i
                    class="fas fa-trash"></i></b></button><br>
    </div>
</div>
<script src="{{ asset('ctrl/hr/mass_leave-form.js?v=').rand() }}" type="text/javascript"></script>