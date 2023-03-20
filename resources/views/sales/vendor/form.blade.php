{!! Form::open(['method' => $method, 'id' => 'm_form']) !!}
<div class="form-group row">
    {!! Form::label('company', 'Perusahaan *', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!! Form::text('vendor_name','',['id'=>'vendor_name','class'=>'form-control','placeholder'=>'Masukkan Nama Perusahaan','required']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('phone', 'Telepon', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!! Form::text('phone','',['id'=>'phone','class'=>'form-control','placeholder'=>'Masukan Nomer Telpon','required']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('fax', 'Fax', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!! Form::text('fax','',['id'=>'fax','class'=>'form-control','placeholder'=>'Masukan Nomer Fax']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('email', 'Email *', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!! Form::email('email','',['id'=>'email','class'=>'form-control','placeholder'=>'Masukan Email','required']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('province', 'Alamat *', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        <div class="row">
            <div class="col-lg-6">
                {!! Form::select('province', $province, null,['id' => 'province', 'class' => 'form-control form-control-select2', 'placeholder' => 'Pilih Provinsi','required']) !!}
            </div>
            <div class="col-lg-6">
                {!! Form::select('city', $city, null,['id' => 'city', 'class' => 'form-control form-control-select2', 'placeholder' => 'Pilih Kota','required', 'disabled']) !!}
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-6">
                {!! Form::select('country', $country, null,['id' => 'country', 'class' => 'form-control form-control-select2', 'placeholder' => 'Pilih Provinsi', 'disabled']) !!}
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                {!! Form::textarea('address','',['id'=>'address','class'=>'form-control','placeholder'=>'Masukan Alamat lengkapnya','require']) !!}
            </div>
        </div>

    </div>
</div>

<legend class="text-uppercase font-size-sm font-weight-bold">PIC Contact</legend>

<div class="form-group row">
    {!! Form::label('name', 'Nama PIC *', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!! Form::text('name','',['id'=>'name','class'=>'form-control','placeholder'=>'Masukkan Nama PIC','required']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('position', 'Jabatan *', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!! Form::text('position','',['id'=>'position','class'=>'form-control','placeholder'=>'Masukan Jabatan','required']) !!}
        {!! Form::hidden('other','yes',['id'=>'other','class'=>'form-control','placeholder'=>'Masukan Jabatan','required']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('pic_phone', 'Mobile ', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!! Form::text('pic_phone','',['id'=>'pic_phone','class'=>'form-control','placeholder'=>'Masukan Nomer HP']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('pic_email', 'Email PIC *', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!! Form::email('pic_email','',['id'=>'pic_email','class'=>'form-control','placeholder'=>'Masukan Email','required']) !!}
    </div>
</div>

<div class="text-right">
    <button type="submit" class="btn btn-primary">Simpan<i class="far fa-save ml-2"></i></button>
</div>

{!! Form::close() !!}
<!-- /basic layout -->