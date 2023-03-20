@php $i=1; $a=1; $y=1; $l=1; foreach ($dtl1 as $dtl){ @endphp
<div id="add_kegiatan" class="form-item1 row_{{$dtl->id}}">
    <legend class="text-uppercase font-size-sm font-weight-bold">Detail Kegiatan / Pekerjaan
    </legend>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Tanggal</label>
        <div class="col-lg-7">
            <input type="text" name="tgl_pekerjaan[]" class="form-control date" value="{{$dtl->tgl_pekerjaan}}"
                placeholder="Tanggal Kegiatan / Pekerjaan" required="required">
        </div>
    </div>
    <div class="form-group row after-add-more">
        <label class='col-lg-3 col-form-label'>Nama Pekerjaan</label>
        <div class="col-lg-7">
            <input type="text" name="nama_pekerjaan[]" value="{{$dtl->nama_pekerjaan}}" class="form-control "
                placeholder="Nama Kegiatan / Pekerjaan" required>
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Deskripsi</label>
        <div class="col-lg-7">
            <input type="text" name="deskripsi[]" value="{{$dtl->deskripsi}}" class="form-control"
                placeholder="Deskripsi Kegiatan / Pekerjaan">
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Estimasi Biaya</label>
        <div class="col-lg-7">
            <input type="number" name="est_biaya[]" value="{{$dtl->est_biaya}}" class="form-control" step="any"
                placeholder="Estimasi Biaya Kegiatan / Pekerjaan" required>
        </div>
    </div>
    <div class="form-group row col-lg-3">
        <button type="button" class="btn bg-danger-400 btn-icon rounded-round legitRipple" id="removes"
            data-equ="{{$y++}}" data-type="remove_asset_edit1" data-id="{{$dtl->id}}" onclick="removeAsset(this)">
            <b><i class="fas fa-trash"></i></b></button>
    </div>
</div>

@php } @endphp
<div id="tambah_act"></div><br>
<!-- Button -->
<button type="button" onClick="addKegiatan(this)" data-type="tambah"
    class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i class="fas fa-plus"></i></b></button>
<script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript"></script>