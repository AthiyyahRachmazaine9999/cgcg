<form action="{{ route('set.pay_back')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="id" name="id" value="{{$set}}" id="note" placeholder="Masukkan Note" class="form-control">
    <div class="form-group row">
        {!! Form::label('note', 'Nominal', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-7">
            <input type="number" id="biaya" name="sisa_biaya" id="note" value="{{$jumlah}}" placeholder="Masukkan Biaya"
                class="form-control">
        </div>
    </div>
    <div class="form-group row">
        {!! Form::label('note', 'Tanggal Transfer', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-7">
            <input type="text" id="tgl_transfer" name="tf_payback" placeholder="Masukkan Tanggal Transfer"
                class="form-control">
        </div>
    </div>
    <div class="form-group row">
        {!! Form::label('note', 'Note', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-7">
            <input type="text" id="file" name="note_kembali" value="" id="note" placeholder="Masukkan Note"
                class="form-control">
        </div>
    </div>
    <div class="form-group row">
        {!! Form::label('file', 'Upload', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-7">
            <input type="file" id="file" name="doc_pay" value="" class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <button type="submit" id="save_set" class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i
                    class="fas fa-file"></i></b></button><br>
    </div>
</form>