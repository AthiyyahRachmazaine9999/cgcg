    {!! Form::open(['action'=>$action, 'name'=>'demoform', 'id' => 'modalbody3']) !!}
    @csrf
<div class="row">
    <!-- <h3>Upload File</h3> -->
        {!! Form::hidden('id_quo',$quo,['id'=>'id_quo','class' => 'form-control','name' => 'id_quo']) !!}
        {!! Form::hidden('type',$type, ['id'=>'tipe','class' => 'dz-default dz-message dropzonedragArea','name' => 'type']) !!}
 </div>
 <div id="imageUpload" class="dropzone text-left" name="file"></div>
{!! Form::close() !!}
            <div class="row">
                <div class="form-group">
                <div class="modal-footer mt-5 pt-3 text-right">
                    <button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Tutup</button>
                    <button type="button" id="UploaderBtn" class="btn bg-primary legitRipple">Simpan</button>
                </div>
            </div>
        </div>
