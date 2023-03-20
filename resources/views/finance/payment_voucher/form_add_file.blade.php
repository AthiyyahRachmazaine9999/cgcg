<form action="{{ route('file.addfile')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id_pay" value="{{$pay->id}}" id="note" placeholder="Masukkan Note" class="form-control">
    <div class="form-group row">
        {!! Form::label('file', 'Nama Dokumen', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-7">
            <input type="text" name="nama_dokumen" id="nama_dokumen" placeholder="Masukkan Nama Dokumen"
                class="form-control">
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('file', 'Upload File', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-5">
            <input type="file" name="file" id="file" class="form-input">
        </div>
    </div>
    <div class="form-group row">
        <button type="submit" class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i
                    class="fas fa-file"></i>Create</b></button><br>
    </div>
</form>
<script src="{{ asset('ctrl/finance/settlement-form.js')}}" type="text/javascript"></script>