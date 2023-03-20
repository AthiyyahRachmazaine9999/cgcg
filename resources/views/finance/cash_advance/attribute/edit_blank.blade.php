@php $i=1; $b=1; $z=1; $l=1; foreach ($dtl1 as $dtl){ @endphp
<div id="add_kegiatan" class="form-itemBlank rowBlank_{{$b++}}">
    <legend class="text-uppercase font-size-sm font-weight-bold">Detail Kegiatan / Pekerjaan
    </legend>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Tanggal</label>
        <div class="col-lg-7">
            <input type="text" name="tgl_blank[]" class="form-control date" value="{{$dtl->tgl_pekerjaan}}"
                placeholder="Tanggal Kegiatan / Pekerjaan" required="required">
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Deskripsi</label>
        <div class="col-lg-7">
            <input type="text" name="desk_blank[]" value="{{$dtl->deskripsi}}" class="form-control"
                placeholder="Deskripsi Kegiatan / Pekerjaan">
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Estimasi Biaya</label>
        <div class="col-lg-7">
            <input type="number" name="nominals[]" value="{{$dtl->est_biaya}}" class="form-control" step="any"
                placeholder="Estimasi Biaya Kegiatan / Pekerjaan" required>
        </div>
    </div>
    <div class="form-group row col-lg-3">
        <button type="button" class="btn bg-danger-400 btn-icon rounded-round legitRipple" id="removesBlank"
            data-equ="{{$i++}}" data-type="remove_asset_edit1" data-id="{{$dtl->id}}" onclick="removeAssetBlank(this)">
            <b><i class="fas fa-trash"></i></b></button>
    </div>
</div>
@php } @endphp
<div id="tambahBlank_act"></div><br>
<!-- Button -->
<button type="button" onClick="addKegiatanBlank(this)" data-type="tambah"
    class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i class="fas fa-plus"></i></b></button>
<script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript"></script>