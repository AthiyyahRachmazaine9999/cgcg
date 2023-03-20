    <div class="form-group row_{{$n_equ}}">
        <legend class="text-uppercase font-size-sm font-weight-bold">Detail Kegiatan / Pekerjaan
        </legend>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Tanggal</label>
            <div class="col-lg-7">
                <input type="date" name="tgl_pekerjaan[]" class="form-control date1" value=""
                    placeholder="Tanggal Kegiatan / Pekerjaan" required="required">
            </div>
        </div>
        <div class="form-group row after-add-more">
            <label class='col-lg-3 col-form-label'>Nama Pekerjaan</label>
            <div class="col-lg-7">
                <input type="text" name="nama_pekerjaan[]" value="" class="form-control"
                    placeholder="Nama Kegiatan / Pekerjaan" required>
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Deskripsi</label>
            <div class="col-lg-7">
                <input type="text" name="deskripsi[]" value="" class="form-control"
                    placeholder="Deskripsi Kegiatan / Pekerjaan" required>
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Estimasi Biaya</label>
            <div class="col-lg-7">
                <input type="number" name="est_biaya[]" value="" class="form-control" step="any"
                    placeholder="Estimasi Biaya Kegiatan / Pekerjaan" required>
            </div>
        </div>
        <div class="form-group row col-lg-3">
            <button type="button" class="btn btn-danger btn-icon rounded-round legitRipple" id="Add_Asset"
                data-type="remove_asset_create" onclick="removeAsset({{$n_equ}})">
                <b><i class=" fas fa-trash"></i></b></button>
        </div>
    </div>
    @section('script')
    <script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript"></script>
    @endsection