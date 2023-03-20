@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Detail Request Leave</h5>
        </div>
        <div class="card-body">
            @if(session()->has('success'))
            <div class="alert alert-success alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('success') }}
            </div>
            @endif
            <div class="card-body">
                <div class="form-group row">
                    {!! Form::label('employee_id', 'Nama', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        <div class="form-control">{{$employee}}</div>
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('type_leave', 'type leave', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        <div class="form-control">{{$data->type_leave}}</div>
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('purpose', 'Tujuan Cuti ', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        <div class="form-control">{{$data->purpose}}</div>
                    </div>
                </div>
                @if($data->note!=null)
                <div class="form-group row">
                    {!! Form::label('note', 'Keterangan', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        <div class="form-control">{{$data->note}}</div>
                    </div>
                </div>
                @endif
                <legend class="text-uppercase font-size-sm font-weight-bold">Date and Time</legend>
                @if($data->type_leave == "Late Permission")
                @if($data->time_from!=null)
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Dari Jam</label>
                    <div class="col-lg-7">
                        <div class="form-control">{{ \Carbon\Carbon::parse($data->time_from)->format('H:i A')}}</div>
                    </div>
                </div>
                @endif
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Jam Datang</label>
                    <div class="col-lg-7">
                        <div class="form-control">{{ \Carbon\Carbon::parse($data->time_finish)->format('H:i')}}</div>
                    </div>
                </div>
                @elseif ($data->type_leave == "Permission")
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Tanggal</label>
                    <div class="col-lg-7">
                        <div class="form-control">{{$data->date_finish==null ? \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') : \Carbon\Carbon::parse($data->date_finish)->format('d/m/Y')}}</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>File Upload</label>
                    @php if($data->file_permit==null) { @endphp
                    <div class="col-lg-5">
                        <button id="btn" class="btn btn-primary" disabled><i class="fa fa-check"></i>Show</button>
                    </div>
                    @php } else { @endphp
                    <div class="col-lg-5">
                        <a href="{{ asset('public/public/hr_permission/'.$data->file_permit) }}" id="btn"
                            class="btn btn-primary">Show</a>
                    </div>
                    @php } @endphp
                </div>

                @else
                <div class="form-group row">
                    {!! Form::label('date_from', 'Dari Tanggal', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        <div class="form-control">{{ \Carbon\Carbon::parse($data->date_from)->format('d/m/Y')}}</div>
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('date_finish', 'Sampai Tanggal', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        <div class="form-control">{{ \Carbon\Carbon::parse($data->date_finish)->format('d/m/Y')}}</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Lama Cuti</label>
                    <div class="col-lg-7">
                        <div class="form-control">{{$data->lama_cuti}}</div>
                    </div>
                </div>
                @endif

                @if($data->app_hr !=null || $data->app_spv !=null)
                <legend class="text-uppercase font-size-sm font-weight-bold">Known By</legend>
                @if($data->app_spv !=null)
                <div class=" form-group row">
                    {!! Form::label('file', 'Lead / Supervisor', ['class' => 'col-lg-3
                    col-form-label']) !!}
                    <div class="col-lg-7">
                        <div class="form-control">{{user_name($data->app_spv)}}</div>
                    </div>
                </div>
                @endif
                @if($data->app_hr !=null)
                <div class=" form-group row">
                    {!! Form::label('file', 'HR', ['class' => 'col-lg-3
                    col-form-label']) !!}
                    <div class="col-lg-7">
                        <div class="form-control">{{user_name($data->app_hr)}}</div>
                    </div>
                </div>
                @endif
                @endif

                <br>
                <div class="text-right">
                    {!! Form::button('Cancel', ['class' => 'btn btn-light btn-cancel', 'data-method'
                    =>'hrm/request/leave', 'type' => 'button','onclick'=>'cancel(this)']) !!}

                    @if($mine->id == $emp->spv_id)
                    @if($data->status=="Pending")
                    <button type="button" onclick="Approve_leave(this)" data-id="{{$data->id}}"
                        data-type="SPV" class="btn btn-primary"><i class="fas fa-file"></i>APPROVE</button>
                    <button type="button" onclick="Reject_leave(this)" data-id="{{$data->id}}"
                        data-type="SPV" class="btn btn-danger"><i class="fas fa-file"></i>REJECT</button>
                    @endif
                    @endif


                    @if($data->app_spv!=null && $data->status=="Approved")
                    @if ($mine->division_id == 7 ||
                    $mine->id==2 || $mine->id==50)
                    <button type="button" onclick="Approve_leave(this)" data-id="{{$data->id}}"
                        data-type="HRD" class="btn btn-primary"><i class="fas fa-file"></i>APPROVE</button>

                    <button type="button" onclick="Reject_leave(this)" data-id="{{$data->id}}"
                        data-type="HRD" class="btn btn-danger"><i class="fas fa-file"></i>REJECT</button>
                    @endif
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')
<script src="{{ asset('ctrl/hr/req_Leave-form.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/hr/req_leave-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection