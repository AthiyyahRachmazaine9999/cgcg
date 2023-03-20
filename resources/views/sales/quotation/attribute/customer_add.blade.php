{!! Form::open(['method' => $method,'action'=>$action, 'id' => 'tambah']) !!}
@csrf
{!! Form::hidden('id_comp',"$ccomp->id", ['id'=>'id_comp','class' => 'form-control','placeholder' => 'Masukkan No SP'])
!!}
{!! Form::hidden('id_quo',"$quo->id", ['id'=>'id_quo','class' => 'form-control','placeholder' => 'Masukkan No SP']) !!}
{!! Form::hidden('type',"$type", ['id'=>'type','class' => 'form-control','placeholder' => 'Masukkan No SP']) !!}
<div class="row mt-3">
    <div class="col-md-12">
        {!! Form::label('nama_pic', 'Nama PIC',['class' =>'font-weight-bold']) !!}
        {!! Form::text('nama_pic','',['id'=>'nama_pic','class' => 'form-control col-lg-12',
        'placeholder' => 'Masukkan Nama PIC']) !!}
        <br>
        {!! Form::label('address', 'Tambah Alamat', ['class' =>'font-weight-bold']) !!}
        {!! Form::text('address','',['id'=>'address','class' => 'form-control col-lg-12',
        'placeholder' => 'Masukkan Alamat Baru']) !!}
    </div>
</div> <br>
<div class="modal-footer left">
    <button type="button" class="btn bg-danger-400 btn-icon rounded-round legitRipple" data-type="tambah"
        onClick="removefield(this)"><i class="fas fa-window-close"></i></button>
    <button type="save" class="btn bg-primary-400 btn-icon rounded-round legitRipple"><i
            class="fas fa-save"></i></button>
</div>
{!! Form::close() !!}