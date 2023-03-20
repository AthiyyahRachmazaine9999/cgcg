<div class="form-group row">
    <label class='col-lg-3 col-form-label'>Tujuan Cuti</label>
    <div class="col-lg-7">
        {!! Form::select('purpose_permit',array('Izin Sakit' => 'Izin Sakit','Lainnya' => 'Lainnya'),'',['id' =>
        'purpose_permit', 'class' => 'form-control
        form-control-select2 permit','placeholder'=>'*']) !!}
    </div>
</div>
<div class="form-group row">
    <label class='col-lg-3 col-form-label'>Keterangan</label>
    <div class="col-lg-7">
        <textarea type="text" name="note" class="form-control" placeholder="Masukkan Keterangan" required></textarea>
    </div>
</div>
<legend class="text-uppercase font-size-sm font-weight-bold">Jam Dan Tanggal</legend>
<div class="form-group row">
    <label class='col-lg-3 col-form-label'>Pilih Hari</label>
    <div class="col-lg-7">
        {!! Form::select('type_date',
        array('Today' => 'Hari Ini', 'Tomorrow' => 'Besok'), '',
        ['id' => 'type_date', 'class' => 'form-control form-control-select2 leaves',
        'placeholder'=>'*']) !!}
    </div>
</div>
<div class="form-group row">
    <label class='col-lg-3 col-form-label'></label>
    <div class="col-lg-7">
        <input type="text" name="read_finish" id="date_finish2" class="form-control" placeholder="Masukkan Tanggal" readonly>
    </div>
</div>

<div class="form-group row">
    <label class='col-lg-3 col-form-label'></label>
    <div class="col-lg-7">
        <input type="file" name="file_sakit" id="file_sakit" class="file-input">
    </div>
</div>