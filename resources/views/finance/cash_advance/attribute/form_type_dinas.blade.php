<div class="kegiatans">
    <legend class="text-uppercase font-size-sm font-weight-bold">Detail Kegiatan / Pekerjaan
    </legend>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Tanggal</label>
        <div class="col-lg-7">
            <input type="text" name="tgl_pekerjaan[]" class="form-control date"
                placeholder="Tanggal Kegiatan / Pekerjaan" required="required">
        </div>
    </div>
    <div class="form-group row after-add-more">
        <label class='col-lg-3 col-form-label'>Nama Pekerjaan</label>
        <div class="col-lg-7">
            <input type="text" name="nama_pekerjaan[]" class="form-control " placeholder="Nama Kegiatan / Pekerjaan"
                required="required">
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Deskripsi</label>
        <div class="col-lg-7">
            <input type="text" name="deskripsi[]" class="form-control" placeholder="Deskripsi Kegiatan / Pekerjaan">
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Estimasi Biaya</label>
        <div class="col-lg-7">
            <input type="number" name="est_biaya[]" class="form-control" step="any"
                placeholder="Estimasi Biaya Kegiatan / Pekerjaan" required="required">
        </div>
    </div>
    <div class="tambah1"></div><br>
</div>
<button type="button" onClick="add_btn(this)" data-type="tambah"
    class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i class="fas fa-plus"></i></b></button>
<br>
<script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript">
</script>