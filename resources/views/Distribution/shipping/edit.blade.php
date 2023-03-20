@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Update Data</h5>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
            <div class="form-group row">
                {!! Form::label('company', 'Perusahaan / Vendor *', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::text('company',$data->company,['id'=>'company','class'=>'form-control','placeholder'=>'Masukkan Nama Perusahaan / Dinas','required']) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('phone', 'Telepon', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::text('phone',$data->phone,['id'=>'phone','class'=>'form-control','placeholder'=>'Masukan Nomer Telpon','required']) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('fax', 'Fax', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::text('fax',$data->fax,['id'=>'fax','class'=>'form-control','placeholder'=>'Masukan Nomer Fax']) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('email', 'Email *', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::email('email',$data->email,['id'=>'email','class'=>'form-control','placeholder'=>'Masukan Email','required']) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('province', 'Alamat *', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-lg-6">
                            {!! Form::select('province', $province, $data->province,['id' => 'province', 'class' => 'form-control form-control-select2', 'placeholder' => 'Pilih Provinsi','required']) !!}
                        </div>
                        <div class="col-lg-6">
                            {!! Form::select('city', $city, $data->city,['id' => 'city', 'class' => 'form-control form-control-select2', 'placeholder' => 'Pilih Kota','required', 'disabled']) !!}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            {!! Form::select('country', $country, $data->country,['id' => 'country', 'class' => 'form-control form-control-select2', 'placeholder' => 'Pilih Provinsi', 'disabled']) !!}
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-12">
                            {!! Form::textarea('address',$data->address,['id'=>'address','class'=>'form-control','placeholder'=>'Masukan Alamat lengkapnya','require']) !!}
                        </div>
                    </div>

                </div>
            </div>

            <legend class="text-uppercase font-size-sm font-weight-bold">PIC Contact</legend>

            <div class="form-group row">
                {!! Form::label('name', 'Nama PIC *', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::text('name',$pic->name,['id'=>'name','class'=>'form-control','placeholder'=>'Masukkan Nama PIC','required']) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('position', 'Position *', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::text('position',$pic->position,['id'=>'position','class'=>'form-control','placeholder'=>'Masukan Jabatan','required']) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('pic_phone', 'PIC phone ', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::text('pic_phone',$pic->pic_phone,['id'=>'pic_phone','class'=>'form-control','placeholder'=>'Masukan Nomer HP']) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('pic_email', 'Email PIC *', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::email('pic_email',$pic->pic_email,['id'=>'pic_email','class'=>'form-control','placeholder'=>'Masukan Email','required']) !!}
                </div>
            </div>
            
            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'distribution/shipping', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                <button type="submit" class="btn btn-primary">Update Vendor<i class="far fa-save ml-2"></i></button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
    <!-- /basic layout -->
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/distribution/shipping-form.js') }}" type="text/javascript"></script>
@endsection