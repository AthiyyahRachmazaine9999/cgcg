@if($exist=="new")
<div class="alert alert-danger alert-styled-left alert-dismissible">
    Your Salary data not found, please contact HR for more info
</div>
@else
{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="mb-3">
    @if($awal=="baru")
    <button type="button" id="norm" class="btn btn-success">Set Master<i class="fas fa-key ml-2"></i></button>
    @else
    <button type="button" id="frg" class="btn btn-danger">Forgot<i class="fas fa-exclamation-triangle ml-2"></i></button>
    <button type="button" id="chg" class="btn btn-warning">Change<i class="fas fa-minus-square ml-2"></i></button>
    <button type="button" id="norm" class="btn btn-primary">Confirm<i class="fas fa-key ml-2"></i></button>
    @endif
</div>
@if($awal=="baru")
<div class="form-group row" id="new">
    <label class='col-lg-3 col-form-label font-weight-bold'>Password</label>
    <div class="col-lg-9">
        <input name="password_new" type="password" class="form-control" placeholder="Set Master Password">
    </div>
</div>
@else
<div class="form-group row" id="logs">
    <label class='col-lg-3 col-form-label font-weight-bold'>Password</label>
    <div class="col-lg-9">
        <input name="password_confirm" type="password" class="form-control" placeholder="Masukan Master Password">
    </div>
</div>
<div id="forgot">
    <div class="form-group row">
        <label class='col-lg-3 col-form-label font-weight-bold'>Confirm Token</label>
        <div class="col-lg-9">
            <div class="form-control">{!! base64_encode(Auth::user()->email) !!}</div>
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label font-weight-bold'>Token generate</label>
        <div class="col-lg-9">
            <input name="password_reset" type="password" class="form-control" placeholder="Masukan Confirm Token">
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-12 col-form-label font-weight-bold'>Silahkan Copy confirm token pada kolom token generate</label>
    </div>
</div>
<div id="change">
    <div class="form-group row">
        <label class='col-lg-3 col-form-label font-weight-bold'>Password Lama</label>
        <div class="col-lg-9">
            <input name="password_lama" type="password" class="form-control" placeholder="Masukan Master Password">
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label font-weight-bold'>Password Baru</label>
        <div class="col-lg-9">
            <input name="password_baru" type="password" class="form-control" placeholder="Masukan Master Password">
        </div>
    </div>
</div>
@endif
<div class="text-right">
    <button type="submit" class="btn btn-primary">Process<i class="far fa-save ml-2"></i></button>
</div>

{!! Form::close() !!}
@endif