<style>
    .dropzone {
        height: 250px;
        width: 90%;
        border: dashed 1px red;
    }
</style>
{!! Form::open(['action'=>$action, 'name'=>'demoform', 'id' => 'modalbody3']) !!}
@csrf
<div class="row mb-3 ml-2">
    <p class="text-danger"><i><b>File hanya bertipe .pdf</b></i></p>
    <!-- <h3>Upload File</h3> -->
    {!! Form::hidden('id_wh_out',$id_wh_out,['id'=>'id_wh_out','class' => 'form-control']) !!}
    {!! Form::hidden('no_do',$no_do,['id'=>'no_do','class' => 'form-control']) !!}
    {!! Form::hidden('type',$type, ['id'=>'tipe','class' => 'dz-default dz-message dropzonedragArea','name' => 'type']) !!}
</div>
<div id="imageUpload" class="dropzone text-left" name="file"></div>
{!! Form::close() !!}
@if($main!==null)
<p class="text-danger"><i><b>Untuk Mengganti file , silahkan pilih file atau drag ke form diatas</b></i></p>
<div class="row">
    <div class="col-lg-4">
        <p class="text-danger mt-3">DO Balikan Sudah diupload</p>
    </div>
    <div class="col-lg-4">
        <a href="{{asset('public/'.$main->do_balik_doc)}}" target="_blank" class="btn btn-primary btn-labeled btn-labeled-left btn-sm legitRipple text-right mt-3"><b>
                <i class="fas fa-cloud-download-alt"></i></b>Lihat File</a>
    </div>

</div>
</a>
@endif
<div class="row">
    <div class="form-group">
        <div class="modal-footer mt-5 pt-3 text-right">
            <button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Tutup</button>
            <button type="button" id="UploaderBtn" class="btn bg-danger legitRipple">Simpan</button>
        </div>
    </div>
</div>