@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Detail Dokumen</h5>
        </div>
        <br>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-lg-3">
                    <p type="text" class="">{{$dok->nama_dok}}</p>
                </div>
                <div class="col-lg-3">
                    <a href="{{asset($dok->dokumen)}}" target="_blank" class="btn btn-outline-primary">SHOW</a>
                </div>
            </div>
            <br>
            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-light btn-cancel', 'data-method'
                =>'finance/pettycash/'.$month.'-'.$year.'/show', 'type' => 'button','onclick'=>'cancel(this)']) !!}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/upload/upload_file.js?v=').rand() }}" type="text/javascript"></script>
@endsection