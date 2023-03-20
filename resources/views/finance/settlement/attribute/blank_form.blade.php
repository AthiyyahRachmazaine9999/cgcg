<div class="blank_forms">
    <input type="hidden" class="form-control" value="{{getUserEmp($user)->id}}" name="emp_id"
        placeholder="Masukkan Nama"></input>
    <div class="form-group row row_nama">
        <label class='col-lg-3 col-form-label'>Nama</label>
        <div class="col-lg-7">
            <input type="text" value="{{user_name($user)}}" class="form-control" name="name"
                placeholder="Masukkan Nama"></input>
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Pilih Pembayaran</label>
        <div class="col-lg-7">
            {!! Form::select('mtd_cash', array('Cash' => 'Cash', 'Transfer' => 'Transfer'), '', ['id' =>
            'cash','class' => 'form-control form-control-select2 pays', 'placeholder' => '*']) !!}
        </div>
    </div>
    <div class="form-group row transfer1">
        <div class="col-lg-3">
            <input type="text" class="form-control" name="acc_bank" value="{{$emp_data->bank_acc}}"
                placeholder="Input Nama Bank"></input>
        </div>
        <div class="col-lg-3">
            <input type="text" class="form-control" value="{{$emp_data->no_bank_acc}}" name="no_acc_bank"
                placeholder="Input Nomer Rekening"></input>
        </div>
        <div class="col-lg-3">
            <input type="text" class="form-control" value="{{$emp_data->name}}" name="name_acc"
                placeholder="Input Nama Rekening"></input>
        </div>
        <div class="col-lg-3">
            <input type="text" class="form-control" name="cabang_bank" placeholder="Input Cabang Bank"></input>
        </div>
    </div>
    <div class="more_deskripsi">
        <legend class="text-uppercase font-size-sm font-weight-bold">Detail Settlement
        </legend>
        <div class="form-group row after-add-more">
            <label class='col-lg-3 col-form-label'>Tujuan</label>
            <div class="col-lg-7">
                <input type="text" name="tujuan[]" class="form-control " placeholder="Masukkan Tujuan">
            </div>
        </div>
        <div class="form-group row after-add-more">
            <label class='col-lg-3 col-form-label'>Quantity</label>
            <div class="col-lg-7">
                <input type="number" name="qty[]" class="form-control " placeholder="Masukkan Qty">
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Deskripsi</label>
            <div class="col-lg-7">
                <input type="text" name="note[]" class="form-control" placeholder="Masukkan Note">
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Nominal</label>
            <div class="col-lg-7">
                <input type="number" name="biaya[]" class="form-control" step="any" placeholder="Masukkan Biaya">
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Bukti / File</label>
            <div class="col-lg-7">
                <input type="file" name="file_set[]" class="form-control" placeholder="Upload Receipt">
            </div>
        </div>
        <div class="more_desc"></div><br>
    </div>
    <button type="button" onClick="more_desc(this)" data-type="tambah"
        class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i class="fas fa-plus"></i></b></button><br>
    <br>
    <!-- //Button -->
    <div class="text-right" style="padding-top:50px">
        {!! Form::button('Back', ['class' => 'btn btn-light btn-cancel',
        'data-method' =>'finance/settlement','type' =>
        'button','onclick'=>'cancel(this)']) !!}
        <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i>
        </button>
    </div>
</div>
</div>
<script src="{{ asset('ctrl/finance/settlement-form.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript"></script>