<!-- Basic layout-->
<div class="page-wrapper">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card-body">

        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Nama Aktivitas</label>
            <div class="col-lg-7">
                <div class="form-control">{{$visit->aktivitas}}</div>
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Instansi</label>
            <div class="col-lg-7">
                <div class="form-control">{{$visit->id_customer}}</div>
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Meeting Point</label>
            <div class="col-lg-7">
                <div class="form-control" style="height:50px;">{{$visit->meeting_point}}</div>
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Detail Alamat</label>
            <div class="col-lg-7">
                <textarea type="text" name="for_value" style="height:120px;" 
                    class="form-control" readonly>{{$visit->detail_meeting_point}}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Jam</label>
            <div class="col-lg-3">
                <div class="form-control">{{\Carbon\Carbon::parse($visit->time_start)->format('H:i:s')}}</div>
            </div>
            <div class="col-lg-3">
                <div class="form-control">{{\Carbon\Carbon::parse($visit->time_end)->format('H:i:s')}}</div>
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Forcast Value</label>
            <div class="col-lg-7">
                <textarea type="text" name="for_value" style="height:120px;" placeholder="Masukkan Forecast Value"
                    class="form-control" readonly>{{$visit->forecast_value}}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-3 col-form-label form-check-input-styled-primary" value="type_leave">Status
                Meeting</label>
            <div class="col-lg-7">
                <div class="form-control">{{$visit->status}}</div>
            </div>
        </div>
        <legend><strong>Contact Person</strong></legend>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Nama</label>
            <div class="col-lg-7">
                <div class="form-control">{{$visit->nama_cp}}</div>
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Nomer HP</label>
            <div class="col-lg-7">
                <div class="form-control">{{$visit->nomer_hp}}</div>
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Jabatan</label>
            <div class="col-lg-7">
                <div class="form-control">{{$visit->jabatan}}</div>
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Email</label>
            <div class="col-lg-7">
                <div class="form-control">{{$visit->email}}</div>
            </div>
        </div>
        <br>
        @if($visit->advice!=null)
        <legend><strong>Masukan / Advice</strong></legend>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Advice / Suggestion</label>
            <div class="col-lg-7">
                <textarea type="text" name="for_value" style="height:120px;" placeholder="Masukkan Forecast Value"
                    class="form-control" readonly>{{$visit->advice}}</textarea>
            </div>
        </div>
        @endif
        <br>
        <div class="form-group row">
            @if($visit->status=="Open Plan" && getUserEmp($visit->created_by) === Auth::id())
            <button type="button" id="button_delete" class="text-left btn btn-danger"><i
                    class="fa fa-trash"></i></button>
            @endif

            @if(in_array($user->id,explode(',',getConfig('list_manage'))))
            <div class="col-lg-12 text-right">
                <button class="btn btn-primary" id="button_edit">Advice<i class="far fa-save ml-2"></i></button>
            </div>
            @else
            <div class="col-lg-12 text-right">
                <button class="btn btn-primary" id="button_edit">Edit<i class="far fa-save ml-2"></i></button>
            </div>
            @endif
        </div>
    </div>
    @include('sales.quotation.attribute.modal')
</div>