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

            <div class="card-body">
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Tanggal</label>
                    <div class="col-lg-7">
                        <div class="form-control">
                            {{\Carbon\Carbon::parse($getdata->date)->format('d F Y')}}
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">No. Faktur</label>
                    <div class="col-lg-7">
                        <div class="form-control">
                            {{$getdata->no_faktur}}
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-check-input-styled-primary" value="">Tipe Tax</label>
                    <div class="col-lg-7">
                        <div class="form-control">
                            {{$getdata->type_tax}}
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Note</label>
                    <div class="col-lg-7">
                        <textarea type="text" name="text" placeholder="Masukkan Keterangan" class="form-control"
                            readonly>{{$getdata->text}}</textarea>
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">File</label>
                    <div class="col-lg-7">
                        @if($getdata->file!=null)
                        <a href="{{ asset($getdata->file)}}" target="_blank"
                            class="btn btn-outline-primary btn-sm">SHOW</a>
                        @else
                        <button class="btn btn-outline-primary btn-sm" disabled>SHOW</button>
                        @endif
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/tax-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection