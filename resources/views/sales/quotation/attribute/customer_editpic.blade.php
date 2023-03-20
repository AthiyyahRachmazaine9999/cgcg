<form action="{{ route('address_add.update', $fpic->id )}}" method="POST" id="edit_pic">
    @csrf
    @method('PUT')
    @php $i=1; foreach($pic as $cpic){ @endphp
    {!! Form::hidden('id_quo',"$quo->id", ['id'=>'id_quo','class' => 'form-control','placeholder' => 'Masukkan No
    SP']) !!}
    {!! Form::hidden('type',$type, ['id'=>'type','class' => 'form-control','placeholder' => 'Masukkan No SP']) !!}
    {!! Form::hidden('idcust',$cpic->id_customer, ['id'=>'type','class' => 'form-control','placeholder' =>
    'Masukkan No SP']) !!}
    {!! Form::hidden('id',$cpic->id, ['id'=>'type','class' => 'form-control','placeholder' => 'Masukkan No SP'])
    !!}
    <div class="row m t-3">
        <div class="col-md-12">
            {!! Form::label('address', 'Edit Alamat',['class' =>'font-weight-bold'])!!}
            {!! Form::text('address',$cpic->address_pic,['id'=>'address','class' => 'form-control
            col-lg-12','placeholder' => 'Masukkan Alamat Baru']) !!}
        </div>
    </div><br>
    <div class="modal-footer left">
        <button type="button" class="btn bg-danger-400 btn-icon rounded-round legitRipple" data-type="edit_pic"
            onClick="removefield(this)"><i class="fas fa-window-close"></i></button>
        <button type="save" class="btn bg-primary-400 btn-icon rounded-round legitRipple"><i
                class="fas fa-save"></i></button>
    </div>
    @php } @endphp
    {!! Form::close() !!}