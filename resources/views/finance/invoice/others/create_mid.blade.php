{!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form', 'files' => true ]) !!}
@csrf
<input type="hidden" name="id_quo" value="{{$invoice_id->id_quo}}" class="form-control" readonly>
<input type="hidden" name="id" value="{{$invoice_id->id}}" class="form-control" readonly>
<input type="hidden" name="redirect" value="{{$redirect}}" class="form-control" readonly>
<br>
<div class="form-group row" style="padding-left:20px">
    <div class="col-lg-8 ml-10 pl-10">
        <label class="font-weight-bold">Tipe Pembayaran* </label>
        {!! Form::select('type_payment', array('parsial' => 'Parsial', 'full' => 'Full / Lunas'),
        $invoice_id->type_payment,['id' => 'typess','class' => 'form-control form-control-select2',
        'placeholder' => '*','required'])!!}
    </div>
</div>
<br>
<div class="form-group row" style="padding-left:20px">
    <div class="col-lg-8 ml-10 pl-10">
        <label class="font-weight-bold">Bentuk Pembayaran* </label>
        {!! Form::select('method_payment', array('cash' => 'Cash', 'transfer' => 'Transfer'),
        '',['id' => 'methods','class' =>
        'form-control form-control-select2',
        'placeholder' => '*','required'])!!}
    </div>
</div>
<div class="row_methods">
    <div class="form-group row" style="padding-left:20px">
        <div class="col-lg-4">
            {!! Form::select('bank_name', $bank, '',['id' => 'nama_bankk', 'class' =>
            'form-control form-control-select2']) !!}
        </div>
    </div>
</div>
<br>
<table class="table table-bordered table-striped table-hover" id="tables_payment">
    <thead>
        <tr>
            <th>Tanggal Pembayaran</th>
            <th>Nilai Pembayaran</th>
            <th>File</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr class="text-center" id="rows_mid">
            <td>
                <input name="date_payment[]" type="text" id="dates_pay" class="form-control tgl_payment dates"
                    placeholder="Masukkan Tanggal Pembayaran">
            </td>
            <td> <input name="payment_amounts[]" type="number" id="dates_pay" class="form-control amount"
                    placeholder="Masukkan Nilai Pembayaran">
            </td>
            <td class="text-left">
                <input name="files[]" type="file" id="file">
            </td>
            <td>
                <button type="button" onClick="tambah_tbl(this)" data-type="hapus_editdb_detail"
                    class="btn bg-warning-400 btn-icon rounded-round legitRipple">
                    <b><i class="fas fa-trash"></i></b></button>
            </td>
        </tr>
        <tr class="row_plus"></tr>
    </tbody>
</table>
<br>
<div class="text-left" style="padding-left:20px">
    <button type="button" onClick="tambah_tbl(this)" data-id_inv="{{$invoice_id->id}}" data-type="tambah detail"
        class="btn bg-primary-400 btn-icon rounded-round legitRipple">
        <b><i class="fas fa-plus"></i></b></button>
</div>
<br>
<div class="text-right" style="padding-right:20px">
    {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
    'data-method' =>$redirect, 'type' =>
    'button','onclick'=>'cancel(this)']) !!}
    <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i>
    </button>
</div>
<br>
{!! Form::close() !!}

<script src="{{ asset('ctrl/finance/finance_invoice-form.js?v=').rand() }}" type="text/javascript"></script>