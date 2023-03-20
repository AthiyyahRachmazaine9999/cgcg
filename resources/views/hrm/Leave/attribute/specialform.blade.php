<div class="form-group row">
    <label class='col-lg-3 col-form-label'>Tujuan Cuti</label>
    <div class="col-lg-7">
        {!! Form::select('purpose_leave_array',$arrsleave, '',['id' => 'purpose_leave_array',
        'class' =>'form-control form-control-select2 leaves','placeholder'=>'*']) !!}
    </div>
</div>
<div class="form-group row">
    <label class='col-lg-3 col-form-label'>Kesempatan Cuti</label>
    <div class="col-lg-7">
        <input type="hidden" name="chance" id="" class="form-control hide_chances">
        <input type="text" name="chance" id="chances" class="form-control chances" placeholder="Hari Cuti" readonly>
    </div>
</div>
<div class="form-group row">
    <label class='col-lg-3 col-form-label'>keterangan</label>
    <div class="col-lg-7">
        <textarea type="text" name="note" class="form-control" placeholder="Masukkan Keterangan"></textarea>
    </div>
</div>
<legend class="text-uppercase font-size-sm font-weight-bold">Jam Dan Tanggal</legend>
<div class="form-group row">
    <label class='col-lg-3 col-form-label'>Dari Tanggal</label>
    <div class="col-lg-7">
        <input type="text" name="date_from" id="date_from1" class="form-control date" placeholder="Masukkan Tanggal">
    </div>
</div>

<div class="form-group row">
    <label class='col-lg-3 col-form-label'>Sampai Tanggal</label>
    <div class="col-lg-7">
        <input type="text" name="date_finish" id="date_finish1" class="form-control date" placeholder="Masukkan Tanggal">
    </div>
</div>
<div class="form-group row">
    <label class='col-lg-3 col-form-label'></label>
    <div class="col-lg-7">
        <input type="text" class="form-control" id="cuti" name="lama_cuti" class="form-control" placeholder="Lama Cuti"
            readonly>
    </div>
</div>