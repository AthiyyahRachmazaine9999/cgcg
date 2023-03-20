{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="row">
    {!! Form::hidden('id_quo',$quo,['id'=>'id_quo','class'=>'form-control']) !!}
    {!! Form::label('vendor_mail', 'Email Vendor') !!}
    {!! Form::select('vendor_mail',$status,$cstatus , ['id'=>'vendor_mail','class' => 'form-control form-control-select2 status','placeholder' => '*']) !!}
</div>
<div class="row">
    {!! Form::label('cc_mail', 'CC Email') !!}
    {!! Form::select('cc_mail',$empmail,null , ['id'=>'cc_mail','class' => 'form-control form-control-select2 status','placeholder' => '*']) !!}

</div>
<div class="row">
    <div class="col-lg-12">
        {!! Form::textarea('quo_notestatus','',['id'=>'quo_notestatus','class'=>'form-control','placeholder'=>'Masukan keterangan tambahan','require']) !!}


    </div>
</div>
<div class="modal-footer pt-3">
    <button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Tutup</button>
    <button type="save" class="btn bg-primary legitRipple">Kirim Email</button>
</div>
{!! Form::close() !!}