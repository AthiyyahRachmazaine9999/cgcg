<div class="form-group row">
    {!! Form::label('vendor_name', 'Perusahaan / Vendor ', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        <div class="form-control">{{$data->vendor_name}}</div>
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
<legend class="text-uppercase font-size-sm font-weight-bold">PIC Contact</legend>
<div class="form-group row">
    {!! Form::label('name', 'Nama PIC ', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        <div class="form-control">{{$pic->name}}</div>
    </div>
</div>

<div class="form-group row">
    {!! Form::label('position', 'Position ', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        <div class="form-control">{{$pic->position}}</div>
    </div>
</div>

<div class="form-group row">
    {!! Form::label('pic_phone', 'Mobile ', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">

        <div class="form-control">{{$pic->pic_phone}}</div>
    </div>
</div>

<div class="form-group row">
    {!! Form::label('pic_email', 'Email PIC ', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        <div class="form-control">{{$pic->pic_email}}</div>
    </div>
</div>
<div class="modal-footer">
    <a type="button" class="btn btn-link legitRipple" data-dismiss="modal">Close</button>
    <a href="{{url('/')}}/sales/vendor/{{$ids}}/edit" class="btn bg-danger legitRipple">Edit Vendor</button>
</div>