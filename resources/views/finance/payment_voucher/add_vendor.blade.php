{!! Form::open(['method' => $method, 'action' => $action, 'id' => 'm_form']) !!}
<div class="form-group row">
    {!! Form::label('company', 'Perusahaan *', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!! Form::text('vendor_name','',['id'=>'vendor_name','class'=>'form-control',
        'placeholder'=>'Masukkan Nama Perusahaan','required']) !!}
    </div>
</div>

<div class="text-right">
    <button type="submit" class="btn btn-primary">Simpan<i class="far fa-save ml-2"></i></button>
</div>

{!! Form::close() !!}
@section('script')
<script src="{{ asset('ctrl/purchasing/mail-po.js') }}" type="text/javascript"></script>
@endsection