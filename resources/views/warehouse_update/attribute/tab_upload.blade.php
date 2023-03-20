{!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form', 'files' => 'true']) !!}

{{ csrf_field() }}

<label>Pilih file Excel</label>
<input type="hidden" id="" name="id_quo" value="{{$out->id_quo}}">
<input type="hidden" id="" name="id_outbound" value="{{$out->id}}">
<input type="hidden" id="" name="id_split" value="{{$id_split}}">
<div class="form-group">
    <input type="file" id="eksport_file" name="file" required="required">
</div>

<div class="">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Import</button>
</div>
</div>
{!! Form::close() !!}