<form action="{{ route('note.addnote')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id_pay" value="{{$pay->id}}" id="note" placeholder="Masukkan Note" class="form-control">
    <input type="hidden" name="nominal" value="{{$dtl->nominal}}" id="nominal_real" placeholder="Masukkan Note"
        class="form-control">
    <input type="hidden" name="persen" id="hitung_persen" placeholder="Masukkan Note" class="form-control">
    <div class="form-group row">
        {!! Form::label('nominal', 'Note*', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-7">
            <input type="text" name="note_pph" value="{{$dtl->note_pph}}" id="note" placeholder="Masukkan Note"
                class="form-control">
        </div>
    </div>
    <div class="form-group row">
        {!! Form::label('note', 'Nominal', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-3">
            {!! Form::select('pilih_nominal',array('Persen' => 'Persen', 'Number' =>
            'Number'), '',['id' => 'pilih_nominal', 'class' => 'form-control form-control-select2 leaves', 'placeholder'
            =>'*']) !!}
        </div>
        <div class="col-lg-4">
            <input type="number" id="nominal_pph" name="note_nominal_pph" step="any" value="{{$dtl->note_nominal_pph}}"
                placeholder="Masukkan Nominal" class="form-control">
        </div>
    </div>
    <div class="form-group row">
        {!! Form::label('note', 'Nominal Transfer', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-7">
            <input type="text" id="nominal_kirim" name="note_transfer_pph" value="{{$dtl->note_transfer_pph}}" id="note" step="any"
                placeholder="Masukkan Nominal Transfer" class="form-control">
        </div>
    </div>
    <div class="form-group row">
        {!! Form::label('file', 'File', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-3">
            <input type="file" name="note_file_pph" id="file" class="form-input">
            @if($dtl->note_file_pph!=null)
            <a href="{{ asset($dtl->note_file_pph) }}" target="_blank"
                class="btn btn-outline-primary bg-info-400 btn-icon rounded-round legitRipple"
                style="margin-top:10px;"><i class="far fa-eye"></i> show</a>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <button type="submit" class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i
                    class="fas fa-file"></i>Save</b></button><br>
    </div>
</form>
<script src="{{ asset('ctrl/finance/pay_voucher-form.js')}}" type="text/javascript"></script>
<script src="{{ asset('ctrl/finance/pay_voucher-list.js')}}" type="text/javascript"></script>