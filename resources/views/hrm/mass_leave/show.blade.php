@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Mass Leave</h5>
        </div>

        <div class="page-wrapper">
            <div class="card-body">
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Keterangan</label>
                    <div class="col-lg-7">
                        <textarea type="text" name="note" class="form-control" readonly>{{$mass->note}}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Cuti Bersama</label>
                    <div class="col-lg-7">
                        <input type="text" name="days" class="form-control" value="{{$mass->days.' Hari'}}" readonly>
                    </div>
                </div>
                <legend class="font-weight-bold">Detail Tanggal</legend>
                <div class="form-group row">
                    @foreach($dtl as $dtl)
                    <div class="col-lg-3">
                        <input type="text" name="date" class="form-control"
                            value="{{\Carbon\Carbon::parse($dtl->date_of_days)->format('d F Y').' ; '}}" readonly>
                    </div>
                    @endforeach
                </div>
                <br>
                <div class="text-right">
                    {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method'
                    =>'hrm/mass_leave', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/mass_leave-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection