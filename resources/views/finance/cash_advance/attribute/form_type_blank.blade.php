<div class="blank_details">
    <legend class="text-uppercase font-size-sm font-weight-bold">Detail Kegiatan / Pekerjaan
    </legend>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Tanggal</label>
        <div class="col-lg-7">
            <input type="text" name="tgl_blank[]" class="form-control date" placeholder="Tanggal Kegiatan / Pekerjaan"
                required="required">
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Deskripsi</label>
        <div class="col-lg-7">
            <input type="text" name="desk_blank[]" class="form-control" placeholder="Deskripsi Kegiatan / Pekerjaan">
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Biaya</label>
        <div class="col-lg-7">
            <input type="number" name="nominals[]" class="form-control" step="any" placeholder="Estimasi Biaya">
        </div>
    </div>
    <div class="tambah_blank"></div><br>
</div>
<button type="button" onClick="add_btn_blank(this)" data-type="tambah_blank"
    class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i class="fas fa-plus"></i></b></button>
<br>
<script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript">
</script>