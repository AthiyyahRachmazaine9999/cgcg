@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Detail Vendor Shipping</h5>
        </div>
        <div class="card-body">
            <div class="form-group row">
                {!! Form::label('company', 'Perusahaan / Dinas ', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$data->company}}</div>
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
            <legend class="text-uppercase font-size-sm font-weight-bold">PIC Contact @php echo $i++; @endphp</legend>

            <div class="form-group row">
                {!! Form::label('name', 'Nama PIC ', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control" value="">{{$val->name}}</div>
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('jabatan', 'Jabatan ', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$val->position}}</div>
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('pic_phone', 'Mobile ', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">

                    <div class="form-control">{{$val->pic_phone}}</div>
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('pic_email', 'Email PIC ', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$val->pic_email}}</div>
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
                            {!! Form::hidden('company_id',$data->id,['id'=>'company_id','class'=>'form-control','placeholder'=>'Masukkan Nama PIC','required']) !!}
                            {!! Form::text('name','',['id'=>'name','class'=>'form-control','placeholder'=>'Masukkan Nama PIC','required']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('position', 'Position *', ['class' => 'col-lg-3 col-form-label']) !!}
                        <div class="col-lg-7">
                            {!! Form::text('position','',['id'=>'position','class'=>'form-control','placeholder'=>'Masukan Jabatan','required']) !!}
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
<script src="{{ asset('ctrl/distribution/shipping-form.js') }}" type="text/javascript"></script>
@endsection