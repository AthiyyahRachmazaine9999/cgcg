@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Detail Request Travel</h5>
        </div>
        <div class="card-body">
            @if(session()->has('success'))
            <div class="alert alert-success alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('success') }}
            </div>
            @endif
            <div class="card-body">
                <!-- @if($fappr==null && $mine->division_id== 8 && $data->status=="Pending" || $fappr==null &&
                $mine->division_id== 7 && $data->status=="Pending")
                <span class="text-danger font-weight-bold"><em>Menunggu Approval dari Finance Terlebih
                        Dahulu</em></span> <br><br>
                @endif -->
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
                    {!! Form::label('destination', 'Destination', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-3">
                        <div class="form-control">{{$des_provinsi}}</div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-control">{{$des_kota}}</div>
                    </div>

                </div>

                <div class="form-group row">
                    {!! Form::label('purpose', 'Purpose', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        <div class="form-control">{{$data->purpose}}</div>
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('departure_transport', 'Departure Transport', ['class' => 'col-lg-3
                    col-form-label']) !!}
                    <div class="col-lg-7">
                        <div class="form-control">{{$data->departure_transport}}</div>
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('return_transport', 'Return Transport', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        <div class="form-control">{{$data->return_transport}}</div>
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('akomodasi', 'Acommodation', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        <div class="form-control">{{$data->akomodasi}}</div>
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('note', 'Keterangan', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        <div class="form-control">{{$data->keterangan}}</div>
                    </div>
                </div>

                <legend class="text-uppercase font-size-sm font-weight-bold">Date, Time, and Cost</legend>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Date Departure</label>
                    <div class="col-lg-7">
                        <div class="form-control">{{ \Carbon\Carbon::parse($data->date_departure)->format('d/m/Y')}}
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Time Departure</label>
                    <div class="col-lg-7">
                        <div class="form-control">{{ $data->time_departure}}</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Return Date</label>
                    <div class="col-lg-7">
                        <div class="form-control">{{\Carbon\Carbon::parse($data->date_return)->format('d/m/Y')}}</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Estimation Expenses</label>
                    <div class="col-lg-7">
                        <div class="form-control">{{$data->est_biaya}}</div>
                    </div>
                </div>

                @if($fappr!=null)
                <legend class="text-uppercase font-size-sm font-weight-bold">Known By</legend>
                @foreach ($approval as $app)
                <div class="form-group row">
                    {!! Form::label('employee_id', $app->approval_by, ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        <div class="form-control">{{emp_name($app->status_by)}}</div>
                    </div>
                </div>
                @endforeach
                @endif
                <br>
                <div class="text-right">
                    {!! Form::button('Back', ['class' => 'btn btn-light btn-cancel', 'data-method'
                    =>'hrm/request/travel', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                    @if($mine->division_id==3 && $data->status!="Submitted")
                    <button type="submit" name="approve" {{$info['BtnApp']}} onClick="Approve_travel(this)"
                        data-id="{{$data->id}}" data-div="Finance" data-emp="{{$mine->id}}" class="btn btn-primary"><i
                            class="fas fa-file"></i>APPROVE</button>
                    <button type="submit" name="reject" {{$info['BtnApp2']}} data-div="Finance" data-emp="{{$mine->id}}"
                        class="btn btn-danger" onClick="Reject_travel(this)" data-id="{{$data->id}}"><i
                            class="fas fa-file"></i>REJECT</button>

                    @elseif ($mine->division_id == 8 && $data->status=="Pending" &&
                    $data->status!="Submitted" || $mine->division_id == 2 && $data->status=="Pending" &&
                    $data->status!="Submitted")
                    <button type="submit" name="approve" {{$info['BtnApp']}} onClick="Approve_travel(this)"
                        data-id="{{$data->id}}" data-div="HRD" data-emp="{{$mine->id}}" class="btn btn-primary"><i
                            class="fas fa-file"></i>APPROVE</button>
                    <button type="submit" name="reject" {{$info['BtnApp2']}} data-div="HRD" class="btn btn-danger"
                        onClick="Reject_travel(this)" data-emp="{{$mine->id}}" data-id="{{$data->id}}"><i
                            class="fas fa-file"></i>REJECT</button>
                    @elseif($data->status="submitted")
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- /basic layout -->
    <div class="modal fade" id="modalApproval" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="exampleModalLabel"><span class="flaticon-share"></span>Approval
                        Segment
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::hidden('id',$data->id,['id'=>'form_edit','class'=>'form-control']) !!}
                    {!! Form::hidden('spv',$join->spv_id,['id'=>'form_edit','class'=>'form-control']) !!}
                    <div class="form-group row">
                        {!! Form::label('approval','Yang Menyetujui*', ['class' => 'col-lg-3 col-form-label']) !!}
                        <div class="col-lg-7">
                            {!! Form::select('approval', array('Supervisor' => 'Supervisor', 'Finance' =>
                            'Finance', 'HRD' => 'HRD', 'Management' => 'Management'), '', ['id' =>
                            'choose_one', 'class' => 'form-control form-control-select2',
                            'placeholder' =>'*']) !!}
                        </div>
                    </div>
                    <div class="form-group row row_spv">
                        {!! Form::label('Supervisor','', ['class' => 'col-lg-3 col-form-label']) !!}
                        <div class="col-lg-7">
                            {!! Form::text('Supervisor',emp_name($join->spv_id),['id' =>
                            'spv', 'class' => 'form-control form-control-select2',
                            'placeholder' =>'*']) !!}
                        </div>
                    </div>
                    <div class="form-group row row_Finance">
                        {!! Form::label('Finance','', ['class' => 'col-lg-3 col-form-label']) !!}
                        <div class="col-lg-7">
                            {!! Form::select('Finance',$emp_finance,'',['id' =>
                            'app_finance', 'class' => 'form-control form-control-select2 approval',
                            'placeholder' =>'*']) !!}
                        </div>
                    </div>
                    <div class="form-group row row_HRD">
                        {!! Form::label('HRD','', ['class' => 'col-lg-3 col-form-label']) !!}
                        <div class="col-lg-7">
                            {!! Form::select('HRD',$emp_HRD,'',['id' =>
                            'app_hrd', 'class' => 'form-control form-control-select2 approval',
                            'placeholder' => '*']) !!}
                        </div>
                    </div>
                    <div class="form-group row row_Manage">
                        {!! Form::label('Management','', ['class' => 'col-lg-3 col-form-label']) !!}
                        <div class="col-lg-7">
                            {!! Form::select('Management',$emp_Manage,'',['id' =>
                            'app_manage', 'class' => 'form-control
                            form-control-select2 approval',
                            'placeholder' => '*']) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-info legitRipple" id="app_travel">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script src="{{ asset('ctrl/hr/req_travel-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection