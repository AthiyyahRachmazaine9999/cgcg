{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="form-group row">
<label class='col-lg-3 col-form-label'>Tanggal</label>
    <div class="col-lg-8">
        {!! Form::hidden('id',$main->id,['id'=>'id','class'=>'form-control']) !!}
        {!! Form::hidden('dbs',$dbs,['id'=>'dbs','class'=>'form-control']) !!}
        <input type="date" id="end_date" class="form-control" data-column="5" name="date" placeholder="Enter Date">
    </div>
</div>
<div class="form-group row">
<label class='col-lg-3 col-form-label'>Tanggal Jatuh Tempo</label>
    <div class="col-lg-8">
        <input type="date" id="tempo" class="form-control" data-column="5" name="tempo" placeholder="Enter Date">
    </div>
</div>
<div class="form-group row">
    <label class='col-lg-3 col-form-label'>TTD By</label>
    <div class="col-lg-8">
        <select class="form-control form-control-select2" name="user" id="user">
            <div></div>
            @foreach ($user as $spv)
            <option value="{{ $spv->id }}">{{ $spv->emp_name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row">
<label class='col-lg-3 col-form-label'>Pembulatan (Masukan jumlah digit)</label>
    <div class="col-lg-8">
        {!! Form::number('digit','',['id'=>'pembulatan','class'=>'form-control','placeholder'=>'1-3 digit']) !!}
    </div>
</div>
<div class="text-right">
    <button type="submit" class="btn btn-primary">Tambah<i class="far fa-save ml-2"></i></button>
</div>

{!! Form::close() !!}