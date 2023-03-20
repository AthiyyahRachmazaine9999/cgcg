@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Detail Customer</h5>
        </div>
        <div class="card-body">
            <div class="form-group row">
                {!! Form::label('company', 'Perusahaan / Dinas ', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$data->company}}</div>
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('phone', 'Alamat', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$data->address}}</div>
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('phone', 'Telepon', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$data->phone}}</div>
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('fax', 'Fax', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$data->fax}}</div>
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('email', 'Email ', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$data->email}}</div>
                </div>
            </div>
            @php $i=1; foreach ($pic as $val){ @endphp
            <legend class="text-uppercase font-size-sm font-weight-bold">
                PIC Contact @php echo $i++; @endphp

                <button type="button" onclick="DeletePIC({{$val->id}},{{$data->id}})" class="btn btn-outline bg-danger border-danger text-danger-800 btn-icon ml-3"><i class="icon-trash"></i></button>
            </legend>

            <div class="form-group row">
                {!! Form::label('name', 'Nama PIC ', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$val->name}}</div>
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('jabatan', 'Jabatan ', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$val->jabatan}}</div>
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('mobile', 'Mobile ', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">

                    <div class="form-control">{{$val->mobile}}</div>
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('email_pic', 'Email PIC ', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$val->email}}</div>
                </div>
            </div>
            @php } @endphp
            <div class="form-group row">
                {!! Form::label('', '', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <button type="button" data-toggle="modal" data-target="#m_modal_pic" class="btn bg-teal-400 btn-labeled btn-labeled-left rounded-round legitRipple"><b><i class="icon-reading"></i></b> Tambah PIC</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /basic layout -->

    {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
    <div class="modal fade" id="m_modal_pic" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="exampleModalLongTitle"><span class="flaticon-share"></span>Tambah PIC</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        {!! Form::label('name', 'Nama PIC *', ['class' => 'col-lg-3 col-form-label']) !!}
                        <div class="col-lg-7">
                            {!! Form::hidden('id_customer',$data->id,['id'=>'id_company','class'=>'form-control','placeholder'=>'Masukkan Nama PIC','required']) !!}
                            {!! Form::text('name','',['id'=>'name','class'=>'form-control','placeholder'=>'Masukkan Nama PIC','required']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('jabatan', 'Jabatan *', ['class' => 'col-lg-3 col-form-label']) !!}
                        <div class="col-lg-7">
                            {!! Form::text('jabatan','',['id'=>'jabatan','class'=>'form-control','placeholder'=>'Masukan Jabatan','required']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('mobile', 'Mobile ', ['class' => 'col-lg-3 col-form-label']) !!}
                        <div class="col-lg-7">
                            {!! Form::text('mobile','',['id'=>'mobile','class'=>'form-control','placeholder'=>'Masukan Nomer HP']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('email_pic', 'Email PIC *', ['class' => 'col-lg-3 col-form-label']) !!}
                        <div class="col-lg-7">
                            {!! Form::email('email_pic','',['id'=>'email_pic','class'=>'form-control','placeholder'=>'Masukan Email','required']) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-info legitRipple">Save</button>
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/sales/customer-form.js') }}" type="text/javascript"></script>
@endsection