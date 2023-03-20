@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h3 class="card-title">Cash Advance</h3>
            @if($cash->no_cashadv!=null)
            <div class="header-elements">
                <a href="{{route('cash.download', $cash->id)}}" class="btn btn-danger btn-sm ml-3"
                    onclick="PrintCashAdv(this)" data-dtl="{{$cash->id_cash}}" data-id_cash="{{$cash->id}}"><i
                        class="icon-printer mr-2"></i>Print</a>
            </div>
            @endif
        </div>

        <div class="card-body">
            <input type="hidden" id="app_spv" value="{{$cash->app_spv}}" name="app_spv" class="form-control">
            <div class="form-group row">
                <h3>{{$cash->no_cashadv}}</h3>
            </div>
            <div class="form-group row">
                {!! Form::label('nama_emp', 'Nama', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{emp_name($cash->emp_id)}}</div>
                </div>
            </div>

            @if($cash->type_cash=="dinas")
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Tujuan</label>
                <div class="col-lg-3">
                    <div class="form-control">{{province($cash->des_provinsi)}}</div>
                </div>

                <div class="col-lg-3">
                    <div class="form-control">{{city($cash->des_kota)}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('tgl_berangkat', 'Tanggal Berangkat ', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{Carbon\Carbon::parse($cash->tgl_berangkat)->format('d F Y')}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('tgl_pulang', 'Tanggal Pulang ', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{Carbon\Carbon::parse($cash->tgl_pulang)->format('d F Y')}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('est_waktu', 'Estimasi Waktu', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$cash->est_waktu}}</div>
                </div>
            </div>
            @else
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Tujuan</label>
                <div class="col-lg-7">
                    <div class="form-control" style="height:70px;">{{$cash->des_tujuan}}</div>
                </div>
            </div>
            @endif
            @if($cash->mtd_cash=="Transfer")
            <div class="form-group row">
                {!! Form::label('mtd_cash', 'Pembayaran', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control"><b>{{$cash->mtd_cash}}</b></div>
                    <div class="form-control">{{$cash->rek_bank}} - {{$cash->no_rek}} - {{$cash->nama_rek}} -
                        {{$cash->cabang_rek}}</div>
                </div>
            </div>
            @else
            <div class="form-group row">
                {!! Form::label('mtd_cash', 'Pembayaran', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control"><b>{{$cash->mtd_cash}}</b></div>
                </div>
            </div>
            @endif

            @if($cash->status=="Completed" || $cash->status=="Approved" && $cash->app_finance!=null)
            @if($cash->file_cash!=null)
            <div class="card-header bg-light text-primary-800 border-primary header-elements-inline">
                <h6 class="card-title">Processed By Finance</h6>
            </div><br>
            <div class=" form-group row">
                {!! Form::label('file', 'Attachment', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <a href="{{ asset($cash->file_cash) }}" class="btn btn-primary btn-sm">SHOW</a>
                </div>
            </div>
            @endif

            @if($cash->tgl_transfer != null)
            <div class=" form-group row">
                {!! Form::label('tgl', 'Tanggal', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <input type="text" class="form-control"
                        value="{{\Carbon\Carbon::parse($cash->tgl_transfer)->format('d F Y')}}">
                </div>
            </div>
            @endif
            
            @if($cash->note!=null)
            <div class=" form-group row">
                {!! Form::label('file', 'Note', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <textarea id="file" name="note" value="" id="notes" placeholder="Masukkan Note" class="form-control"
                        readonly>{{$cash->note}}</textarea>
                </div>
            </div>
            @endif
            @endif
            <br>
            <div class="nav-tabs-responsive bg-light border-top">
                <ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
                    <li class="nav-item"><a href="#desk" class="nav-link active" data-toggle="tab"><i
                                class="icon-menu7 mr-2"></i> Deskripsi</a></li>
                    <li class="nav-item"><a href="#his" class="nav-link" data-toggle="tab"><i
                                class="fas fa-file-download"></i> History</a></li>
                </ul>
            </div>


            <div class="tab-content">
                <div class="tab-pane fade show active" id="desk">
                    <br>
                    @if($dtls!=null)
                    @if($cash->type_cash=="dinas")
                    @include('finance.cash_advance.attribute.show_dinas')
                    @else
                    @include('finance.cash_advance.attribute.show_blank')
                    @endif
                    @endif
                    <br><br>
                    <!-- //Button -->
                    <div class="text-right" style="padding-top:50px">
                        {!! Form::button('Back', ['class' => 'btn btn-light btn-cancel',
                        'data-method' =>'finance/cash_advance','type' =>
                        'button','onclick'=>'cancel(this)']) !!}


                        @if(getUserEmp($cash->created_by)->id==getUserEmp(Auth::id())->id && $cash->status=="Pending")
                        <button id="ajukan_sub" name="approve" onClick="Ajukan_app(this)" data-id="{{$cash->id}}"
                            class="btn btn-primary"><i class="fas fa-file"></i>Ajukan</button>
                        @endif


                        @if(getUserEmp($cash->created_by)->id!=getUserEmp(Auth::id())->id &&
                        $emp->spv_id==$mine->id)
                        @if($cash->status=="Need Approval")
                        <button id="approval" name="approval" onClick="Appr_app(this)" data-type="approval"
                            data-id="{{$cash->id}}" class="btn btn-primary"><i class="fas fa-file"></i>Approve</button>
                        <button id="rem_sub" name="approve" onClick="Appr_app(this)" data-type="reject"
                            data-id="{{$cash->id}}" class="btn btn-danger"><i class="fas fa-file"></i>Reject</button>
                        @endif
                      @endif


                        @if(in_array($mine->id,explode(',',getConfig('app_finance'))))
                        @if($cash->status=="Approved" && $cash->app_hr==null)
                        <button id="approval" name="approval" onClick="hr_appr(this)" data-type="approval"
                            data-id="{{$cash->id}}" class="btn btn-primary"><i class="fas fa-file"></i>Approve</button>
                        <button id="rem_sub" name="approve" onClick="hr_appr(this)" data-type="reject"
                            data-id="{{$cash->id}}" class="btn btn-danger"><i class="fas fa-file"></i>Reject</button>
                        @endif
                        @endif


                        @if($cash->status=="Approved" && $cash->app_hr!=null)
                        @if($mine->division_id==3)
                        <button name="finance" onClick="get_Complete(this)" data-type="process" data-id="{{$cash->id}}"
                            data-toggle="modal" data-target="#m_modal" class="btn btn-primary"><i
                                class="fas fa-file"></i>Proccess</button>
                        @endif
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade show" id="his">
                    @include('finance.cash_advance.attribute.history')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /basic layout -->
</div>
@include('sales.quotation.attribute.modal')
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/finance/cash_adv-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection