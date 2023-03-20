@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h3 class="card-title">Cash Advance</h3>
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

            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form', 'files' => true ]) !!}
            @csrf
            <div class="card-body">
                <input type="hidden" id="name" name="emp_id" value="{{$user}}">

                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Form Cash*</label>
                    <div class="col-lg-7">
                        {!! Form::select('type_cash',array('dinas' => 'Perjalanan Dinas', 'blank' => 'Lainnya'),
                        '',['id' => 'type_cash', 'class' =>'form-control form-control-select2 type_cash',
                        'placeholder' => 'Pilih Keperluan Form']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Nama</label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control" id="name" name="nama_emp" value="{{$name}}" readonly>
                    </div>
                </div>

                <div class="form-group row row_tujuan">
                    <label class='col-lg-3 col-form-label'>Tujuan</label>
                    <div class="col-lg-7">
                        <input type="text" id="des_tujuan" class="form-control" name="des_tujuan" placeholder="Tujuan">
                    </div>
                </div>

                <div class="row_tujuanLuar">
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Tujuan</label>
                        <div class="col-lg-3">
                            {!! Form::select('des_provinsi', $province, null,['id' => 'province', 'class' =>
                            'form-control form-control-select2', 'placeholder' => 'Pilih
                            Provinsi','required']) !!}
                        </div>

                        <div class="col-lg-3">
                            {!! Form::select('des_kota', $city, null,['id' => 'city', 'class' =>
                            'form-control form-control-select2', 'placeholder' => 'Pilih Kota','required',
                            'disabled']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Tanggal Berangkat*</label>
                        <div class="col-lg-7">
                            <input type="text" id="berangkat" class="form-control date" name="tgl_berangkat"
                                placeholder="Tanggal Berangkat" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Tanggal Pulang*</label>
                        <div class="col-lg-7">
                            <input type="text" id="pulang" class="form-control date" name="tgl_pulang"
                                placeholder="Tanggal Pulang" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Estimasi Waktu</label>
                        <div class="col-lg-7">
                            <input type="text" id="est_waktu" name="est_waktu" class="form-control"
                                placeholder="Estimasi Waktu" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Pilih Pembayaran</label>
                    <div class="col-lg-7">
                        {!! Form::select('mtd_cash', array('Cash' => 'Cash', 'Transfer' => 'Transfer'), '', ['id' =>
                        'cash',
                        'class' => 'form-control form-control-select2', 'placeholder' => '*']) !!}
                    </div>
                </div>
                <div class="form-group row transfer1">
                    <div class="col-lg-3">
                        <input type="text" class="form-control" name="bank" value="{{$emp_data->bank_acc}}"
                            placeholder="Input Nama Bank"></input>
                    </div>
                    <div class="col-lg-3">
                        <input type="text" class="form-control" value="{{$emp_data->no_bank_acc}}" name="no_rek"
                            placeholder="Input Nomer Rekening"></input>
                    </div>
                    <div class="col-lg-3">
                        <input type="text" class="form-control" value="{{$emp_data->name}}" name="Nama_rek"
                            placeholder="Input Nama Rekening"></input>
                    </div>
                    <div class="col-lg-3">
                        <input type="text" class="form-control" name="cabang" placeholder="Input Cabang Bank"></input>
                    </div>
                </div>
                <br>
                <!-- /////////////////////////////////////////////////////// KP/////////////////////////                     -->
                <div class="row_tujuanLuar">
                    @include('finance.cash_advance.attribute.form_type_dinas')
                </div>

                <div class="row_tujuan">
                    @include('finance.cash_advance.attribute.form_type_blank')
                </div>
                <br>
                <br>
                <div class="text-right">
                    {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                    'data-method' =>'finance/cash_advance', 'type' =>
                    'button','onclick'=>'cancel(this)']) !!}
                    <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i>
                    </button>
                </div>
            </div>

            </form>
        </div>
    </div>
</div>
@endsection @section('script')
<script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection