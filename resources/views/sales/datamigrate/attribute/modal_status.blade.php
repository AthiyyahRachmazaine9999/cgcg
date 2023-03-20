{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="row">
    {!! Form::hidden('id_quo',$quo,['id'=>'id_quo','class'=>'form-control']) !!}
    <div class="col-md-4">
        {!! Form::label('quo_eksstatus', 'Status') !!}
        {!! Form::select('quo_eksstatus',$status,$cstatus , ['id'=>'quo_eksstatus','class' => 'form-control form-control-select2 status','placeholder' => '*']) !!}
    </div>
    <div class="col-md-4">
        {!! Form::label('quo_eksposisi', 'Posisi') !!}
        {!! Form::select('quo_eksposisi',$posisi,$cposisi , ['id'=>'quo_eksposisi','class' => 'form-control form-control-select2 status','placeholder' => '*']) !!}
    </div>
    <div class="col-md-4">
        {!! Form::label('quo_ekskondisi', 'Kondisi') !!}
        {!! Form::select('quo_ekskondisi',$kondisi,$ckondisi , ['id'=>'quo_ekskondisi','class' => 'form-control form-control-select2 status','placeholder' => '*']) !!}
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        {!! Form::textarea('quo_notestatus','',['id'=>'quo_notestatus','class'=>'form-control','placeholder'=>'Masukan keterangan tambahan','require']) !!}


    </div>
</div>
<div class="modal-footer pt-3">
    <button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Tutup</button>
    <button type="save" class="btn bg-primary legitRipple">Simpan</button>
</div>
{!! Form::close() !!}