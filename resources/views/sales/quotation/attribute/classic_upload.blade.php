{!! Form::open(['action'=>$action, 'name'=>'demoform', 'id' => 'modalbody3', 'files' => 'true']) !!}
@csrf
<div class="row">
    <!-- <h3>Upload File</h3> -->
    {!! Form::hidden('id_quo',$quo,['id'=>'id_quo','class' => 'form-control','name' => 'id_quo']) !!}
    {!! Form::hidden('type',$type, ['id'=>'tipe','class' => 'dz-default dz-message dropzonedragArea','name' =>
    'type']) !!}
    {!! Form::hidden('form','classic',['id'=>'id_quo','class' => 'form-control','name' => 'form']) !!}
</div>
<br>
<input type="file" id="filess" class="form-control" name="file">
<br>
{!! Form::close() !!}

    <div class="form-group">
        <div class="modal-footer mt-2">
            <button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Tutup</button>
            <button type="button" class="btn bg-primary legitRipple" id="ClassicBtn">Simpan</button>
        </div>
    </div>
