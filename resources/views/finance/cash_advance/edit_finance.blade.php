@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Cash Advance Update</h5>
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

            <form action="{{ route('finance.update', $cash->id )}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @php foreach ($dtl1 as $dtl) { @endphp
                {!! Form::hidden('id_dtl[]',$dtl->id,['id'=>'id_dtl','class'=>'form-control']) !!}
                @php } @endphp
                {!! Form::hidden('id',$cash->id,['id'=>'id','class'=>'form-control']) !!}
                {!! Form::hidden('div_id',$cash->div_id,['id'=>'divs','class'=>'form-control']) !!}
                <input type="hidden" id="name" name="emp_id" value="{{$cash->emp_id}}">
                <input type="hidden" id="type_edit" name="type_edit" value="finance">
                <input type="hidden" id="type" name="type_cash" class="type_cash" value="{{$cash->type_cash}}">
                <div class="card-body">
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Form Cash*</label>
                        <div class="col-lg-7">
                            <input type="text" id="" class="form-control"
                                value="{{$cash->type_cash== 'dinas' ? 'Perjalanan Dinas' : 'Lainnya'}}" name="type"
                                placeholder="Nama" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-check-input-styled-primary"
                            value="pro_priceType">Nama*</label>
                        <div class="col-lg-7">
                            <input type="text" id="nama" class="form-control" value="{{emp_name($cash->emp_id)}}"
                                name="nama" placeholder="Nama" readonly>
                        </div>
                    </div>

                    <div class="form-group row row_tujuan">
                        <label class='col-lg-3 col-form-label'>Tujuan</label>
                        <div class="col-lg-7">
                            <input type="text" id="des_tujuan" class="form-control" value="{{$cash->des_tujuan}}"
                                name="des_tujuan" placeholder="Tujuan">
                        </div>
                    </div>

                    <div class="row_tujuanLuar">
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Tujuan</label>
                            <div class="col-lg-3">
                                {!! Form::select('des_provinsi', $province, $cash->des_provinsi,['id' => 'province',
                                'class'
                                =>
                                'form-control form-control-select2']) !!}
                            </div>

                            <div class="col-lg-3">
                                {!! Form::select('des_kota', $city, $cash->des_kota,['id' => 'city', 'class' =>
                                'form-control form-control-select2']) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Tanggal Berangkat*</label>
                            <div class="col-lg-7">
                                <input type="text" id="berangkat" class="form-control date" name="tgl_berangkat"
                                    value="{{$cash->tgl_berangkat}}" placeholder="Tanggal Berangkat">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Tanggal Pulang*</label>
                            <div class="col-lg-7">
                                <input type="text" id="pulang" value="{{$cash->tgl_pulang}}" class="form-control date"
                                    name="tgl_pulang" placeholder="Tanggal Pulang">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Estimasi Waktu</label>
                            <div class="col-lg-7">
                                <input type="text" id="est_waktu" value="{{$cash->est_waktu}}" name="est_waktu"
                                    class="form-control" placeholder="Estimasi Waktu" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Pilih Pembayaran</label>
                        <div class="col-lg-7">
                            {!! Form::select('mtd_cash', array('Cash' => 'Cash', 'Transfer' =>
                            'Transfer'),$cash->mtd_cash, ['id' => 'cash',
                            'class' => 'form-control form-control-select2']) !!}
                        </div>
                    </div>
                    @if($cash->mtd_cash=="Transfer")
                    <div class="form-group row transfer">
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="bank" value="{{$cash->rek_bank}}"
                                placeholder="Input Nama Bank"></input>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="no_rek" value="{{$cash->no_rek}}"
                                placeholder="Input Nomer Rekening"></input>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="Nama_rek" value="{{$cash->nama_rek}}"
                                placeholder="Input Nama Rekening"></input>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="cabang" value="{{$cash->cabang_rek}}"
                                placeholder="Input Cabang Bank"></input>
                        </div>
                    </div>
                    @else
                    <div class="form-group row transfer1">
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="bank" value="{{$cash->rek_bank}}"
                                placeholder="Input Nama Bank"></input>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="no_rek" value="{{$cash->no_rek}}"
                                placeholder="Input Nomer Rekening"></input>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="Nama_rek" value="{{$cash->nama_rek}}"
                                placeholder="Input Nama Rekening"></input>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="cabang" value="{{$cash->cabang_rek}}"
                                placeholder="Input Cabang Bank"></input>
                        </div>
                    </div>
                    @endif
                    <br>
                    <!-- /////////////////////////////////////////////////////// KP/////////////////////////-->

                    @if($cash->type_cash=="dinas")
                    @include('finance.cash_advance.attribute.edit_dinas')
                    @else
                    @include('finance.cash_advance.attribute.edit_blank')
                    @endif
                    <div class="text-right">
                        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                        'data-method' =>'finance/cash_advance', 'type' =>
                        'button','onclick'=>'cancel(this)']) !!}
                        <button type="submit" class="btn btn-primary">Update<i class="far fa-save ml-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection