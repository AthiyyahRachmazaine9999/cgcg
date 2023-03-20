{!! Form::open(['action' => $action, 'method' => $method, 'files' => 'true']) !!}
<input type="hidden" name="id" value="{{$main->id}}" class="form-control" placeholder='Masukkan Tanggal Pembayaran'
    readonly>
<input type="hidden" name="id_po" value="{{$main->id_po}}" class="form-control"
    placeholder='Masukkan Tanggal Pembayaran' readonly>
<input type="hidden" name="id_quo" value="{{$main->id_quo}}" class="form-control"
    placeholder='Masukkan Tanggal Pembayaran' readonly>

<div class="form-group">
    {!! Form::label('p_sub', 'Tanggal Pembayaran', ['class' => 'col-form-label']) !!}
    <input type="text" name="date_payment" value="{{\Carbon\Carbon::parse($main->date_payment)->format('Y/m/d')}}"
        id='edit_tanggal' class="form-control" placeholder='Masukkan Tanggal Pembayaran'>
</div>

<div class="form-group">
    {!! Form::label('pays', 'Nominal', ['class' => 'col-form-label']) !!}
    <input type="number" name="pays" id='pays' value="{{$main->pays}}" class="form-control"
        placeholder='Masukkan Nominal'>
</div>

<div class="form-group">
    {!! Form::label('bank_name', 'Nama Bank', ['class' => 'col-form-label']) !!}
    {!! Form::select('bank_name', $bank, $main->bank_name,['id' => 'nama_bankk', 'class' =>
    'form-control form-control-select2']) !!}
</div>


<div class="form-group">
    {!! Form::label('note', 'Note', ['class' => 'col-form-label']) !!}
    {!! Form::text('note',$main->note,['id'=>'note','class'=>'form-control','placeholder'=>'Masukkan Note']) !!}
</div>

<div class="form-group">
    {!! Form::label('file_upload', 'Pembayaran', ['class' => 'col-form-label']) !!}
    @if($main->doc_pay!=null)
    <input type="file" name="files" id='files' class="form-control">
    <a href="{{ asset($main->doc_pay) }}" class="btn btn-primary">SHOW</a>
    @else
    <input type="file" name="files" id='files' class="form-control">
    @endif
</div>

<div class="form-group">
    {!! Form::label('file_upload', 'Dokumen', ['class' => 'col-form-label']) !!}
    @if($main->doc_other!=null)
    <input type="file" name="doc_other" id='file' class="form-control">
    <a href="{{ asset($main->doc_other) }}" class="btn btn-primary">SHOW</a>
    @else
    <input type="file" name="doc_other" id='file' class="form-control">
    @endif
</div>


<br>
<div class="text-right" style="padding-right:20px">
    <button type="submit" class="btn btn-primary btn-labeled btn-labeled-left legitRipple saveKirim">
        <b><i class="fas fa-file"></i></b> Update
    </button>
</div>
<br>
{!! Form::close() !!}
@section('script')
<script src="{{ asset('ctrl/purchasing/mail-po.js') }}" type="text/javascript"></script>
@endsection