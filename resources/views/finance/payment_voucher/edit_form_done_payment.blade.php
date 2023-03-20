{!! Form::open(['action' => $action, 'method' => $method, 'files' => 'true']) !!}
<input type="hidden" name="id_quo" value="{{$pay_dtl->id_quo}}" class="form-control"
    placeholder='Masukkan Tanggal Pembayaran' readonly>
<input type="hidden" name="id_pay" value="{{$pay_dtl->id_pay}}" class="form-control"
    placeholder='Masukkan Tanggal Pembayaran' readonly>
<input type="hidden" name="id" value="{{$payment->id}}" class="form-control" placeholder='Masukkan Tanggal Pembayaran'
    readonly>
<div class="form-group">
    {!! Form::label('status', 'Status Pembayaran', ['class' => 'col-form-label']) !!}
    {!! Form::select('status',array('parsial' => 'Parsial', 'lunas' => 'Lunas'),
    $payment->status,['id' => 'status_pay', 'class' =>'form-control form-control-select2',
    'placeholder' => 'Pilih Status Pembayaran']) !!}
</div>

<div class="form-group">
    {!! Form::label('p_sub', 'Tanggal Pembayaran', ['class' => 'col-form-label']) !!}
    <input type="text" name="date_payment" id='tgl_payment'
        value="{{\Carbon\Carbon::parse($payment->date_payment)->format('Y/m/d')}}" class="form-control"
        placeholder='Masukkan Tanggal Pembayaran'>
</div>

<div class="form-group">
    {!! Form::label('pays', 'Nominal', ['class' => 'col-form-label']) !!}
    <input type="number" name="pays" id='pays' value="{{$payment->pays}}" class="form-control"
        placeholder='Masukkan Nominal'>
</div>

<div class="form-group">
    {!! Form::label('bank_name', 'Nama Bank', ['class' => 'col-form-label']) !!}
    {!! Form::select('bank_name', $bank, $payment->bank_name,['id' => 'nama_bankk', 'class' => 'form-control
    form-control-select2']) !!}
</div>


<div class="form-group">
    {!! Form::label('note', 'Note', ['class' => 'col-form-label']) !!}
    {!! Form::text('note',$payment->note,['id'=>'note','class'=>'form-control','placeholder'=>'Masukkan Note']) !!}
</div>

<div class="form-group">
    {!! Form::label('file_upload', 'Pembayaran', ['class' => 'col-form-label']) !!}
    @if($payment->doc_pay!=null)
    <input type="file" name="files" id='files' class="form-control">
    <a href="{{ asset($payment->doc_pay) }}" class="btn btn-primary">SHOW</a>
    @else
    <input type="file" name="files" id='files' class="form-control">
    @endif
</div>

<div class="form-group">
    {!! Form::label('file_upload', 'Dokumen', ['class' => 'col-form-label']) !!}
    @if($payment->doc_other!=null)
    <input type="file" name="doc_other" id='file' class="form-control">
    <a href="{{ asset($payment->doc_other) }}" class="btn btn-primary">SHOW</a>
    @else
    <input type="file" name="doc_other" id='file' class="form-control">
    @endif
</div>

<br>
<div class="text-right" style="padding-right:20px">
    <button type="submit" class="btn btn-primary btn-labeled btn-labeled-left legitRipple saveKirim">
        <b><i class="fas fa-file"></i></b> Save
    </button>
</div>
<br>
{!! Form::close() !!}
@section('script')
<script src="{{ asset('ctrl/purchasing/mail-po.js') }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/finance/pay_voucher-list.js')}}" type="text/javascript"></script>
@endsection