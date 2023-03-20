<form action="{{ route('set.complete')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-header bg-light text-primary-800 border-bottom-primary header-elements-inline">
        <h6 class="card-title">Proccess Settlement</h6>
    </div><br>
    <input type="hidden" id="id" name="id" value="{{$set->id}}" id="note" placeholder="Masukkan Note"
        class="form-control">
    <div class="form-group row">
        {!! Form::label('note', 'Nominal', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-7">
            <input type="number" id="biaya" name="biaya_finance" value="{{$set->biaya_finance}}" id="note"
                placeholder="Masukkan Biaya" class="form-control">
        </div>
    </div>
    <div class="form-group row">
        {!! Form::label('note', 'Tanggal Transfer', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-7">
            <input type="text" id="tgl_transfer" name="tgl_transfer" value="{{$set->tgl_transfer}}"
                placeholder="Masukkan Tanggal Transfer" class="form-control">
        </div>
    </div>
    <div class="form-group row">
        {!! Form::label('note', 'Note', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-7">
            <input type="text" id="file" name="note" value="{{$set->notes_finance}}" id="note"
                placeholder="Masukkan Note" class="form-control">
        </div>
    </div>
    <div class="form-group row">
        {!! Form::label('file', 'Receipt', ['class' => 'col-lg-3
        col-form-label']) !!}
        @if ($set->doc_finance_settle!=null)
        <a href="{{ asset($set->doc_finance_settle)}}" class="btn btn-outline-primary btn-sm">SHOW</a>
        @endif
        <div class="col-lg-3">
            <input type="file" id="file" name="doc_finance_settle" value="" id="file" class="form-input">
        </div>
    </div>
    <div class="form-group row">
        <button type="submit" id="save_set" class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i
                    class="fas fa-file"></i>Proccess</b></button><br>
    </div>
</form>
<script src="{{ asset('ctrl/finance/settlement-form.js')}}" type="text/javascript"></script>