{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="row">
    {!! Form::hidden('id',$main->id,['id'=>'id','class'=>'form-control']) !!}
    {!! Form::hidden('idinv',$part->id,['id'=>'idinv','class'=>'form-control']) !!}
    {!! Form::hidden('dbs',$dbs,['id'=>'dbs','class'=>'form-control']) !!}
    <div class="col-lg-6">
        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" value="{{$part->tgl_invoice}}" id="end_date" class="form-control" data-column="5" name="date" placeholder="Enter Date" require>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label>Tanggal Jatuh Tempo</label>
            <input type="date" id="tempo" value="{{$part->tgl_jatuhtempo}}" class="form-control" data-column="5" name="tempo" placeholder="Enter Date" require>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label>Sign By</label>
            <select class="form-control form-control-select2" name="user" id="user_edit" require>
                @php
                foreach ($user as $spv){
                $selected = $spv->id == $part->sign_by ? "selected": "";
                @endphp
                <option value="{{ $spv->id }}" {{$selected}}>{{ $spv->emp_name }}</option>
                @php } @endphp
            </select>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label>Pembulatan (Masukan jumlah digit)</label>
            {!! Form::number('digit',$part->digit,['id'=>'pembulatan','class'=>'form-control','placeholder'=>'1-3 digit']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        @if($part->termin=='termin')
        <div class="form-group">
            <label>Masukan Termin ( dalam % )</label>
            <input type="number" value="{{$part->number}}" class="form-control" name="termin" max="100" placeholder="Angka Persentase">
        </div>
        @else
        <div class="form-group">
            <label>Masukan Nominal</label>
            <input type="number" value="{{$part->number}}"  class="form-control" name="nominal" placeholder="Angka Nominal">
        </div>
        @endif

    </div>
</div>

<div class="text-right">
    <button type="submit" class="btn btn-primary" id="textinv">UPDATE<i class="far fa-save ml-2"></i></button>
</div>

{!! Form::close() !!}