<div class="form-group adds_{{$n_equ}} forms_doks">
    <div class="form-group row">
        <input type="text" name="nama_dok_add[]" value="" placeholder="Nama Dokumen" class="form-control col-lg-3">
        <div class="col-lg-7">
            <input type="file" name="dok_emp_add[]" value="" class="file-input form-control">
        </div>
        <button type="button" onclick="hapus_dokumen({{$n_equ}})" data-type="hapus_dokumen_create"
            class="btn bg-danger-400 btn-icon rounded-round legitRipple"><b><i class="fas fa-trash"></i></b></button>
    </div>
</div>