    <div class="form-group row_{{$n_equ}} form-item">
        <div class="form-group row>
        <label class=" col-lg-3 col-form-label"><b>Tambah Asset Ke {{$n_equ}}</b></label>
        </div>
        <div class="form-group row">
            <label class="col-lg-3 col-form-label">Produk</label>
            <div class="col-lg-7">
                <input type="text" name="namaproduk_add[]" placeholder="Masukkan Produk" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-3 col-form-label">Jumlah</label>
            <div class="col-lg-7">
                <input type="number" name="jumlah_add[]" placeholder="Masukkan Jumlah" class="form-control">
            </div>
        </div>
        <div class="form-group row col-lg-3">
            <button type="button" class="btn bg-danger-400 btn-icon rounded-round legitRipple" id="Add_Asset"
                data-type="remove_asset_create" onclick="removeAsset({{$n_equ}})">
                <b><i class=" fas fa-trash"></i></b></button>
        </div>
    </div>