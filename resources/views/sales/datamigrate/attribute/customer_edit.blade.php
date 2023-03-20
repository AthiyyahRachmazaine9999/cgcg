<form action="{{ route('address_add.update', $wo->id )}}" method="POST" id="edit_{{$type}}">
    @csrf
    @method('PUT')
    {!! Form::hidden('id_comp',"$ccomp->id", ['id'=>'id_comp','class' => 'form-control','placeholder' => 'Masukkan No
    SP']) !!}
    {!! Form::hidden('id_quo',"$quo->id", ['id'=>'id_quo','class' => 'form-control','placeholder' => 'Masukkan No SP'])
    !!}
    {!! Form::hidden('id_wo',"$wo->id", ['id'=>'id_quo','class' => 'form-control','placeholder' => 'Masukkan No SP'])
    !!}
    {!! Form::hidden('type',"$type", ['id'=>'type','class' => 'form-control','placeholder' => 'Masukkan No SP']) !!}
    <div class="row mt-3">
        <div class="col-md-8">
            {!! Form::label('nama_pic', 'Nama PIC',['class' =>'font-weight-bold']) !!}
            <input type="text" class="form-control col-lg-12" name="name_pic" value="{{$wo->name}}"
                placeholder="Masukkan Nama PIC"></input>
            {!! Form::label('address', 'Tambah Alamat', ['class' =>'font-weight-bold']) !!}
            <input type="text" class="form-control col-lg-12" name="address" value="{{$wo->address}}"
                placeholder="Masukkan Alamat"></input>
        </div>
    </div><br>
    <div class="modal-footer left">
        <button type="button" class="btn bg-danger-400 btn-icon rounded-round legitRipple" data-type="edit"
            onClick="removefield(this)"><i class="fas fa-window-close"></i></button>
        <button type="submit" class="btn bg-primary-400 btn-icon rounded-round legitRipple"><i
                class="fas fa-save"></i></button>
    </div>
    {!! Form::close() !!}