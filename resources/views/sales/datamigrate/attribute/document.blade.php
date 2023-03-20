{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="row">
    {!! Form::hidden('id_quo',$quo, ['id'=>'id_quo','class' => 'form-control','placeholder' => 'Masukkan No SP']) !!}
    {!! Form::hidden('type',"new", ['id'=>'type','class' => 'form-control','placeholder' => 'Masukkan No SP']) !!}
    <div class="col-md-12">
        {!! Form::label('waktu_pelaksanaan', 'Jangka Waktu Pelaksanaan') !!}
        {!! Form::date('waktu_pelaksanaan', '', ['id'=>'waktu_pelaksanaan','class' => 'form-control','placeholder' =>
        'Masukkan Batas Akhir']) !!}
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-6">
        {!! Form::label('no_sp', 'No. SP') !!}
        {!! Form::text('no_sp','' , ['id'=>'no_sp','class' => 'form-control','placeholder' => 'Masukkan No SP']) !!}
    </div>
    <div class="col-md-6">
        {!! Form::label('tgl_sp', 'Tanggal SP') !!}
        {!! Form::date('tgl_sp','' , ['id'=>'tgl_sp','class' => 'form-control']) !!}
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-6">
        {!! Form::label('no_spk', 'No. SPK') !!}
        {!! Form::text('no_spk','' , ['id'=>'no_spk','class' => 'form-control','placeholder' => 'Masukkan No SPK']) !!}
    </div>
    <div class="col-md-6">
        {!! Form::label('tgl_spk', 'Tanggal SPK') !!}
        {!! Form::date('tgl_spk','' , ['id'=>'tgl_spk','class' => 'form-control']) !!}
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-6">
        {!! Form::label('no_bast', 'No. BAST') !!}
        {!! Form::text('no_bast','' , ['id'=>'no_bast','class' => 'form-control','placeholder' => 'Masukkan No BAST'])
        !!}
    </div>
    <div class="col-md-6">
        {!! Form::label('tgl_bast', 'Tanggal BAST') !!}
        {!! Form::date('tgl_bast','' , ['id'=>'tgl_bast','class' => 'form-control']) !!}
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-6">
        {!! Form::label('no_fakturpajak', 'No. Faktur Pajak') !!}
        {!! Form::text('no_fakturpajak','' , ['id'=>'no_fakturpajak','class' => 'form-control','placeholder' =>
        'Masukkan No Faktur Pajak']) !!}
    </div>
    <div class="col-md-6">
        {!! Form::label('tgl_fakturpajak', 'Tanggal Faktur Pajak') !!}
        {!! Form::date('tgl_fakturpajak','' , ['id'=>'tgl_fakturpajak','class' => 'form-control']) !!}
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-6">
        {!! Form::label('no_fakturjual', 'No. Faktur Penjualan') !!}
        {!! Form::text('no_fakturjual','' , ['id'=>'no_fakturjual','class' => 'form-control','placeholder' => 'Masukkan
        No Faktur Penjualan']) !!}
    </div>
    <div class="col-md-6">
        {!! Form::label('tgl_fakturjual', 'Tanggal Faktur Pajak') !!}
        {!! Form::date('tgl_fakturjual','' , ['id'=>'tgl_fakturjual','class' => 'form-control']) !!}
    </div>
</div>
<div class="modal-footer pt-3">
    <button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Tutup</button>
    <button type="save" class="btn bg-primary legitRipple">Simpan</button>
</div>
{!! Form::close() !!}