@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Tax</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>


        <div class="page-wrapper">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form', 'files' => true]) !!}
            <div class="card-body">
                @csrf
                <input type="hidden" name="ids" value="{{$getdata->id}}" class="form-control">
                <input type="hidden" name="redirect" value="{{$type}}" class="form-control">
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Tanggal</label>
                    <div class="col-lg-7">
                        <input type="text" name="date"
                            value="{{\Carbon\Carbon::parse($getdata->date)->format('Y-m-d')}}"
                            placeholder="Masukkan Tanggal" class="form-control dates">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">No. Faktur</label>
                    <div class="col-lg-7">
                        <input type="text" name="no_faktur" value="{{$getdata->no_faktur}}" class="form-control"
                            placeholder="Masukkan No Faktur">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-check-input-styled-primary" value="">Tipe Tax</label>
                    <div class="col-lg-7">
                        {!! Form::select('type_tax',array('ppn' => 'PPN', 'pph' =>
                        'PPH'),$getdata->type_tax,['id' => 'type_tax', 'class' => 'form-control form-control-select2
                        type_tax']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Note</label>
                    <div class="col-lg-7">
                        <textarea type="text" name="text" placeholder="Masukkan Keterangan"
                            class="form-control">{{$getdata->text}}</textarea>
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Upload File</label>
                    <div class="col-lg-7">
                        <input type="file" name="file" class="form-input">
                        <br>
                        <a href="{{ asset($getdata->file) }}" target="_blank" class="btn btn-primary btn-sm">SHOW</a>
                    </div>
                </div>
                <br>

                <div class="text-right">
                    @php
                    if($type=="pph")
                    {
                    $link = 'finance/tax_pph';
                    }else{
                    $link = 'finance/tax';
                    }
                    @endphp
                    {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                    'data-method' => $link, 'type' =>
                    'button','onclick'=>'cancel(this)'])
                    !!}
                    <button type="submit" class="btn btn-primary">Update<i class="far fa-save ml-2"></i>
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/tax-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection