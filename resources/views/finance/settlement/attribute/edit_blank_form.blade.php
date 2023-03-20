<input type="hidden" id="id" value="{{$set->id}}" name="id" class="form-control">
<input type="hidden" id="id" value="{{$set->employee_id}}" name="emp_id" class="form-control">
<div class="form-group row row_nama">
    <label class='col-lg-3 col-form-label'>Nama</label>
    <div class="col-lg-7">
        <div class="form-control">{{emp_name($set->employee_id)}}</div>
    </div>
</div>
<div class="form-group row">
    <label class='col-lg-3 col-form-label'>Pilih Pembayaran</label>
    <div class="col-lg-7">
        {!! Form::select('mtd_cash', array('Cash' => 'Cash', 'Transfer' => 'Transfer'),
        $set->mtd_payment, ['id' =>
        'cash','class' => 'form-control form-control-select2 pays', 'placeholder' => '*']) !!}
    </div>
</div>
@if($set->mtd_payment=="Transfer")
<div class="form-group row transfer">
    <div class="col-lg-3">
        <input type="text" class="form-control" name="acc_bank" value="{{$set->acc_bank}}"
            placeholder="Input Nama Bank"></input>
    </div>
    <div class="col-lg-3">
        <input type="text" class="form-control" name="no_acc_bank" value="{{$set->no_acc_bank}}"
            placeholder="Input Nomer Rekening"></input>
    </div>
    <div class="col-lg-3">
        <input type="text" class="form-control" name="name_acc" value="{{$set->name_acc}}"
            placeholder="Input Nama Rekening"></input>
    </div>
    <div class="col-lg-3">
        <input type="text" class="form-control" name="cabang_bank" value="{{$set->cabang_bank}}"
            placeholder="Input Cabang Bank"></input>
    </div>
</div>
@else
<div class="form-group row transfer1">
    <div class="col-lg-3">
        <input type="text" class="form-control" name="acc_bank" value="{{$set->acc_bank}}"
            placeholder="Input Nama Bank"></input>
    </div>
    <div class="col-lg-3">
        <input type="text" class="form-control" name="no_acc_bank" value="{{$set->no_acc_bank}}"
            placeholder="Input Nomer Rekening"></input>
    </div>
    <div class="col-lg-3">
        <input type="text" class="form-control" name="name_acc" value="{{$set->name_acc}}"
            placeholder="Input Nama Rekening"></input>
    </div>
    <div class="col-lg-3">
        <input type="text" class="form-control" name="cabang_bank" value="{{$set->cabang_bank}}"
            placeholder="Input Cabang Bank"></input>
    </div>
</div>
@endif
<br>
@foreach($dtl as $dtl)
<div class="more_deskripsi" id="desk_{{$dtl->id}}">
    <legend class="text-uppercase font-size-sm font-weight-bold">Detail Settlement
    </legend>
    <input type="hidden" class="form-control" name="id_dtl[]" value="{{$dtl->id}}" placeholder="Input Nama Bank"></input>
    <div class="form-group row after-add-more">
        <label class='col-lg-3 col-form-label'>Tujuan</label>
        <div class="col-lg-7">
            <input type="text" name="tujuan[]" value="{{$dtl->tujuan}}" class="form-control "
                placeholder="Masukkan Tujuan">
        </div>
    </div>
    <div class="form-group row after-add-more">
        <label class='col-lg-3 col-form-label'>Quantity</label>
        <div class="col-lg-7">
            <input type="number" name="qty[]" value="{{$dtl->qty}}" class="form-control " placeholder="Masukkan Qty">
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Item Description</label>
        <div class="col-lg-7">
            <input type="text" name="note[]" value="{{$dtl->notes}}" class="form-control" placeholder="Masukkan Note">
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Biaya</label>
        <div class="col-lg-7">
            <input type="number" name="biaya[]" value="{{$dtl->est_biaya}}" class="form-control" step="any"
                placeholder="Masukkan Biaya">
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Receipt</label>
        <div class="col-lg-7">
            @if($dtl->file_set!=null)
            <input type="file" name="file_set[]" class="form-control" placeholder="Upload Receipt">
            <a href="{{ asset($dtl->file_set) }}" class="btn btn-primary">SHOW</a>
            @else
            <input type="file" name="file_set[]" class="form-control" placeholder="Upload Receipt">
            @endif
        </div>
    </div>
    <button type="button" onclick="Deletes_items(this)" data-id="{{$dtl->id}}" data-type="delete_settle"
        class="btn bg-danger-400 btn-icon rounded-round legitRipple"><b><i class="fas fa-trash"></i></b></button>
    <br>
</div>
@endforeach
<div class="more_desc"></div><br>
<button type="button" onClick="more_desc(this)" data-type="tambah"
    class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i class="fas fa-plus"></i></b></button><br>
<br>
<!-- //Button -->
<div class="text-right" style="padding-top:50px">
    {!! Form::button('Back', ['class' => 'btn btn-light btn-cancel',
    'data-method' =>'finance/settlement','type' =>
    'button','onclick'=>'cancel(this)']) !!}
    <button type="submit" class="btn btn-primary">Update<i class="far fa-save ml-2"></i>
    </button>
</div>
</div>
<script src="{{ asset('ctrl/finance/settlement-form.js?v=').rand() }}" type="text/javascript"></script>