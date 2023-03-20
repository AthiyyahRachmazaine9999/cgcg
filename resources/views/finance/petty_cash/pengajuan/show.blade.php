@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Pengajuan Petty Cash</h5>
            <div class="header-elements">
                <a href="{{route('pettycash.download', $getdata->id)}}" class="btn btn-danger btn-sm ml-3 text-light"
                    onclick="PrintPettyCash(this)" data-id="{{$getdata->id}}"><i
                        class="icon-printer mr-2"></i>Print</a><br>
            </div>
        </div>


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

            <input type="hidden" name="id" value="{{$getdata->id}}">
            <div class="card-body">

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">From Date</label>
                    <div class="col-lg-7">
                        <div class="form-control">{{\Carbon\Carbon::parse($getdata->start_date)->format('Y-m-d')}}</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">End Date</label>
                    <div class="col-lg-7">
                        <div class="form-control">{{\Carbon\Carbon::parse($getdata->end_date)->format('Y-m-d')}}</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Receipt / File</label>
                    <div class="col-lg-7">
                        @if($getdata->receipt!=null)
                        <a href="{{ asset($getdata->receipt) }}" class="btn btn-outline-primary btn-sm">SHOW</a>
                        @else
                        <button class="btn btn-outline-primary btn-sm" disabled>SHOW</button>
                        @endif
                    </div>
                </div>

                @if($getdata->additional_file!=null)
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Additional File</label>
                    <div class="col-lg-7">
                        <a href="{{ asset($getdata->additional_file) }}" target="_blank"
                            class="btn btn-outline-primary btn-sm">SHOW</a>
                    </div>
                </div>
                @endif
                <br>
                <button onclick="Add_files(this)" data-id="{{$getdata->id}}" class="btn btn-primary btn-sm"
                    data-toggle="modal" data-target="#m_modal"><i
                        class="fas fa-pencil-alt"></i>{{$getdata->additional_file==null ? 'Add' : 'Edit'}}
                    File</button>
                <br><br>
                <div class="nav-tabs-responsive bg-light border-top">
                    <ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
                        <li class="nav-item"><a href="#desk" class="nav-link active" data-toggle="tab"><i
                                    class="icon-menu7 mr-2"></i>Description</a></li>
                        <li class="nav-item"><a href="#his" class="nav-link" data-toggle="tab"><i
                                    class="fab fa-audible"></i>History</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="desk">
                        <table class="table table-bordered">
                            <thead class="success">
                                <tr class="text-center bg-teal">
                                    <th>Description</th>
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $total = 0;
                                foreach($get_dtl as $dtl)
                                {
                                @endphp
                                <tr>
                                    <td class="text-left">{{$dtl->purpose}}</td>
                                    <td class="text-right">{{number_format($dtl->nominal)}}</td>
                                </tr>
                                @php $total+=$dtl->nominal; } @endphp
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="1" class="text-center"><b>TOTAL</b></td>
                                    <td class="text-right">{{number_format($total)}}</td>
                                </tr>
                            </tfoot>
                        </table>
                        <br>
                        <br>
                        <div class="text-right">
                            {!! Form::button('Cancel', ['class' => 'btn btn-light btn-cancel',
                            'data-method' => 'finance/pengajuan_pettycash', 'type' =>
                            'button','onclick'=>'cancel(this)'])
                            !!}

                            @if($getdata->status=="Pending" && $main->division_id==3)
                            <button onClick="btn_approval(this)" data-id="{{$getdata->id}}" data-type="need_approval"
                                data-usr="finance" class="btn btn-primary">Need Approval <i
                                    class="fas fa-scroll"></i></button>
                            @endif


                            @if($getdata->status=="Need Approval" &&
                            in_array($main->id,explode(',',getConfig('app_finance'))) )
                            <button onClick="btn_approval(this)" data-id="{{$getdata->id}}" data-type="approve"
                                data-usr="finance_mng" class="btn btn-primary">Approve<i
                                    class="fas fa-calendar-check ml-2"></i></button>
                            <button onClick="btn_approval(this)" data-id="{{$getdata->id}}" data-type="reject"
                                data-usr="finance_mng" class="btn btn-danger">Reject<i
                                    class="fas fa-calendar-times ml-2"></i></button>
                            @endif

                            @if($getdata->status=="Approved" && $getdata->app_finance!=null &&
                            in_array($main->id,explode(',',getConfig('direksi'))) )
                            <button onClick="btn_approval(this)" data-id="{{$getdata->id}}" data-type="approve"
                                data-usr="khusus" class="btn btn-primary">Approve<i
                                    class="fas fa-calendar-check ml-2"></i></button>
                            <button onClick="btn_approval(this)" data-id="{{$getdata->id}}" data-type="reject"
                                data-usr="khusus" class="btn btn-danger">Reject<i
                                    class="fas fa-calendar-times ml-2"></i></button>
                            @endif

                        </div>
                    </div>
                    <div class="tab-pane fade show" id="his">
                        @if(count($hist)!=0)
                        @include('finance.petty_cash.pengajuan.attribute.history')
                        @else
                        <br><br>
                        <span class=""><strong>Belum Ada Data Masuk</strong></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('sales.quotation.attribute.modal')
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/pengajuan_pettycash-form.js') }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/finance/pengajuan_pettycash-list.js') }}" type="text/javascript"></script>
@endsection