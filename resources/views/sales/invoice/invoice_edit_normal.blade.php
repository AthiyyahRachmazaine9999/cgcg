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
            <input type="date" value="{{$part->tgl_jatuhtempo}}" id="tempo" class="form-control" data-column="5" name="tempo" placeholder="Enter Date" require>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label>Sign By</label>
            <select class="form-control form-control-select2" name="user" id="user_edit" require>
                <option></option>
                @foreach ($user as $spv)
                <option value="{{ $spv->id }}" @php echo $part->sign_by == $spv->id ? 'selected="selected"' : '' ; @endphp >{{ $spv->emp_name }}</option>
                @endforeach
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
    <div class="col-lg-6">
        <div class="form-group">
            <label>Tipe Invoice</label>
            <select class="form-control form-control-select2" name="jenis" id="type">
                <option value="normal" selected>Normal</option>
            </select>
        </div>
    </div>
</div>

<div class="text-right">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary" id="textinv">@if($kondisi=='partial') Generate @else Update @endif<i class="far fa-save ml-2"></i></button>
</div>

{!! Form::close() !!}