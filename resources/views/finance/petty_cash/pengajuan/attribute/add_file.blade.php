{!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form', 'files' => true ]) !!}
<input type="hidden" name="id" value="{{$id}}">
<div class="form-group row">
    <label class="col-lg-3 col-form-label">Attachment</label>
    <div class="col-lg-7">
        <input type="file" name="additional_file" class="file-input form-control">
    </div>
</div>
<br>
<div class="text-right">
    <button type="submit" class="btn btn-primary">Save<i class="far fa-save ml-2"></i>
    </button>
</div>
{!! Form::close() !!}