@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Detail Request Overtime</h5>
        </div><br>
        <div class="card-body">
            @if($data->status=="Reject")
            <div class="form-group row col-lg-7">
                <h4 class="text-danger font-bold"><em>Rejected</em></h4>
            </div>
            @endif
            <div class="form-group row">
                {!! Form::label('employee_id', 'Employee Name ', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$employee}}</div>
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('division_id', 'Division', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$division}}</div>
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('date', 'Date', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{ \Carbon\Carbon::parse($data->date)->format('d/m/Y')}}</div>
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('purpose', 'Purpose Leave ', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$data->purpose}}</div>
                </div>
            </div>

            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Overtime From</label>
                <div class="col-lg-7">
                    <div class="form-control">{{\Carbon\Carbon::parse($data->overtime_from)->format('H:i:s')}}</div>
                </div>
            </div>


            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Overtime Finish</label>
                <div class="col-lg-7">
                    <div class="form-control">{{\Carbon\Carbon::parse($data->overtime_finish)->format('H:i:s')}}</div>
                </div>
            </div>

            @if($ov!=null)
            <legend class="text-uppercase font-size-sm font-weight-bold">Approval By</legend>
            @foreach ($overtime as $over)
            <div class="form-group row">
                {!! Form::label('', $over->approval_by, ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{emp_name($over->status_by)}}</div>
                </div>
            </div>
            @endforeach
            @endif
            <br>

            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-light btn-cancel', 'data-method'
                =>'hrm/request/overtime', 'type' => 'button','onclick'=>'cancel(this)']) !!}

                @if($data->approve_spv!=null)
                @if(in_array($mine->id,explode(',',getConfig('list_hr'))))
                <button type="submit" name="approve" {{$info['BtnApp']}} onClick="approve_overtime(this)"
                    data-id="{{$data->id}}" data-type="HRD" class="btn btn-primary"><i
                        class="fas fa-file"></i>APPROVE</button></a>
                <button type="submit" name="reject" {{$info['BtnApp2']}} onClick="reject_overtime(this)"
                    data-id="{{$data->id}}" data-type="HRD" class="btn btn-danger"><i
                        class="fas fa-file"></i>REJECT</button></a>
                @endif
                @endif

                @if($data->status=="Pending")
                @if($mine->id==$emp->spv_id)
                <button type="submit" name="approve" {{$info['BtnApp']}} onClick="approve_overtime(this)"
                    data-id="{{$data->id}}" data-type="SPV" class="btn btn-primary"><i
                        class="fas fa-file"></i>APPROVE</button></a>
                <button type="submit" name="reject" {{$info['BtnApp2']}} onClick="reject_overtime(this)"
                    data-id="{{$data->id}}" data-type="SPV" class="btn btn-danger"><i
                        class="fas fa-file"></i>REJECT</button></a>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>



@endsection
@section('script')
<script src="{{ asset('ctrl/hr/req_overtime-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection