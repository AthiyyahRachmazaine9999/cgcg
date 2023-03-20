@extends('layouts.head') 
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h4 class="card-title">Payment Voucher</h4>
            <div class="header-elements">
                @php if($pay_dtl->id_quo<>0){ @endphp
                <a href="#" class="btn btn-success btn-sm ml-3"
                    onclick="DownloadPrecalc(this)" data-type="precalc" data-id="{{$pay_dtl->id_quo }}"><i
                        class="icon-file-excel mr-2"></i>Download Precalc</a><br>
                @php } @endphp
                        <a href="{{route('payment.download', $pay->id)}}" class="btn btn-danger btn-sm ml-3"
                    onclick="PrintPayment(this)" data-id_pay="{{$pay_dtl->id_pay}}"><i
                        class="icon-printer mr-2"></i>Print</a><br>
            </div>
        </div>

        <div class="card-body">
            <div class="form-group row">
                {!! Form::label('no_payment', 'No. Payment Voucher', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay_dtl->no_payment==null ? '--' : $pay_dtl->no_payment}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('no_payment', 'Tujuan', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control" style="height:60px;">{{$pay_dtl->tujuan}}
                        @if($pay_dtl->no_po!=null)[
                        <a href="{{ url('purchasing/order/'.$pay_dtl->no_po)}}" target="_blank" class="">
                            {{$pay_dtl->no_po}}</a> ]
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('invoice_kwitansi', 'No. Kwitansi / Invoice', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">
                        @if($pay_dtl->no_invoice == null)
                            {{$pay_dtl->performa_invoice == null ? '-' : $pay_dtl->performa_invoice}}
                        @else
                            {{$pay_dtl->no_invoice}}
                        @endif
                        </div>
                </div>
            </div>
            @if($pay->keperluan_form=="lainnya" || $pay_dtl->id_quo!=null)
            <div class="form-group row">
                {!! Form::label('invoice_kwitansi', 'No. Sales Order', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control" style="height:60px;">{{$pay_dtl->id_quo==0 ? "PO for Stock" : $pay_dtl->no_so.' - '.$quo_no.' - '.$quo_name }}
                    </div>
                </div>
            </div>
            @endif
            @if($pay_dtl->id_vendor==null)
            <div class="form-group row">
                {!! Form::label('id_customer', 'Customer', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div>
                        {{getCustomer($pay_dtl->id_customer)->company}}
                    </div>
                </div>
            </div>
            @elseif($pay_dtl->id_vendor!=null)
            <div class="form-group row">
                {!! Form::label('id_vendor', 'Vendor', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">
                        {{getVendor($pay->id_vendor)->vendor_name}}
                    </div>
                </div>
            </div>
            @endif

            @if($pay_dtl->type_payment!="cbd")
            <div class="form-group row">
                {!! Form::label('', 'Tanggal Invoice', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-3">
                    <div class="form-control">{{\Carbon\Carbon::parse($pay_dtl->from_date)->format('d-m-Y')}}</div>
                </div>
                <label class='col-lg-1 col-form-label'>s/d</label>
                <div class="col-lg-3">
                    <div class="form-control">{{\Carbon\Carbon::parse($pay_dtl->to_date)->format('d-m-Y')}}</div>
                </div>
            </div>
            @endif

            @if($pay_dtl->type_payment=="cbd" && $pay_dtl->from_date!=null)
            <div class="form-group row">
                {!! Form::label('top', 'Tanggal Invoice', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{\Carbon\Carbon::parse($pay_dtl->from_date)->format('d-m-Y')}}</div>
                </div>
            </div>
            @endif

            <div class="form-group row">
                {!! Form::label('nominal', 'Nominal', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{number_format($pay_dtl->nominal,2)}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('terbilang', 'Terbilang', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control" style="height:60px;">{{terbilang($pay_dtl->nominal)}}</div>
                </div>
            </div>

            <!--SHOW NOTE -->
            @if($pay_dtl->note_pph!=null)
            <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
                <h6 class="card-title">Additional Information</h6>
            </div> <br>
            <div class="form-group row">
                {!! Form::label('terbilang', $pay_dtl->note_pph==null ? "Tambahan" : $pay_dtl->note_pph, ['class' =>
                'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{number_format($pay_dtl->note_nominal_pph)}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('terbilang', 'Nominal Transfer', ['class' =>
                'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{number_format($pay_dtl->note_transfer_pph)}}</div>
                </div>
            </div>
            @if($pay_dtl->note_file_pph)
            <div class="form-group row">
                {!! Form::label('terbilang', 'File', ['class' =>
                'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <a href="{{ asset($pay_dtl->note_file_pph) }}" target="_blank" class="btn btn-primary">SHOW</a>
                </div>
            </div>
            @endif
            @endif
            <!--SHOW NOTE -->

            <!--BUTTON ADD NOTE -->

            @if($main->division_id==3)
            <br>
            <button onClick="add_note(this)" data-id_dtl="{{$pay_dtl->id}}" data-id="{{$pay_dtl->id_pay}}"
                class="btn btn-outline-primary" data-toggle="modal" data-target="#m_modal">Add Note<i
                    class="fas fa-pencil-alt ml-2"></i></button><br>
            @endif
            <br>
            <br>
            <br>

            <!-- ADD NOTE -->
            <div class="nav-tabs-responsive bg-light border-top">
                <ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
                    <li class="nav-item"><a href="#appr" class="nav-link active" data-toggle="tab"><i
                                class="fas fa-user-check mr-2"></i>Known By</a></li>
                    <li class="nav-item"><a href="#info" class="nav-link" data-toggle="tab"><i
                                class="fas fa-file-alt"></i> Other Documents</a></li>
                    @if($main->division_id==3 || $main->division_id==7)
                    <li class="nav-item"><a href="#payf" class="nav-link" data-toggle="tab"><i
                                class="icon-menu2 mr-2"></i>Payment By Finance</a></li>
                    <li class="nav-item"><a href="#hist" class="nav-link" data-toggle="tab"><i
                                class="fas fa-th-list"></i> History</a></li>
                    @endif
                </ul>
            </div>

            <div class="tab-content">
                <!-- APPROVE -->
                <div class="tab-pane fade show active" id="appr">
                    @if($pay_dtl->app_finance!=null)
                    <br>
                    @if($pay_dtl->app_finance!=null)
                    <div class="form-group row">
                        {!! Form::label('invoice_kwitansi', getUserEmp($pay_dtl->app_finance)->position, ['class' =>
                        'col-lg-3
                        col-form-label']) !!}
                        <div class="col-lg-7">
                            <div class="form-control">{{user_name($pay_dtl->app_finance)}}</div>
                        </div>
                    </div>
                    @endif
                    @if($pay_dtl->app_hrd!=null)
                    <div class="form-group row">
                        {!! Form::label('invoice_kwitansi', getUserEmp($pay_dtl->app_hrd)->position, ['class' =>
                        'col-lg-3
                        col-form-label']) !!}
                        <div class="col-lg-7">
                            <div class="form-control">{{user_name($pay_dtl->app_hrd)}}</div>
                        </div>
                    </div>
                    @endif
                    @if($pay_dtl->app_mng!=null)
                    <div class="form-group row">
                        {!! Form::label('invoice_kwitansi', getUserEmp($pay_dtl->app_mng)->position, ['class' =>
                        'col-lg-3
                        col-form-label']) !!}
                        <div class="col-lg-7">
                            <div class="form-control">{{user_name($pay_dtl->app_mng)}}</div>
                        </div>
                    </div>
                    @endif

                    @if($main->division_id==3 && $pay_dtl->app_hrd!=30 && $pay_dtl->app_mng==null &&
                    $pay_dtl->status!="Rejected")
                    <div class="form-group row">
                        {!! Form::label('invoice_kwitansi', 'President Director', ['class' => 'col-lg-3
                        col-form-label']) !!}
                        @if($forward==null && $pay_dtl->app_mng==null)
                        <div class="col-lg-7">
                            <button onClick="forward_appr(this)" data-id="{{$pay_dtl->id_pay}}"
                                class="btn btn-outline-primary"><i class="fas fa-chevron-circle-up"></i></button><br>
                        </div>
                        @elseif($forward!=null && $pay_dtl->app_mng==null)
                        <span class="text-danger">Waiting Approval</span>
                        @endif
                    </div>
                    @endif
                    @elseif($pay_dtl->status!="Rejected" && $pay_dtl->reject_by==null)
                    <br>
                    <div class="text-center">
                        <span class="text-danger font-weight-bold">Waiting Approval</span>
                        <br><br>
                    </div>
                    @endif
                    @if($pay_dtl->reject_by!=null && $pay_dtl->status=="Rejected")
                    <br>
                    <h5 class="text-danger font-weight-bold"><em>{{$pay_dtl->status}}</em></h5>
                    <em>by {!!getUserEmp($pay_dtl->reject_by)->emp_name!!}</em>
                    @endif
              </div>

                <!--SHOW DOKUMEN-->
                <div class="tab-pane fade show" id="info">
                    @if($pay_dtl->doc_alltop!=null || $pay_dtl->doc_inv_performa!=null || $pay_dtl->doc_lainnya!=null || count($dok)!=0)
                    <!-- <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
                        <h6 class="card-title">Show Document</h6>
                    </div> -->
                    <br><br>
                    @if($pay_dtl->doc_alltop!=null)
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Document TOP</label>
                        <div class="fallback">
                            <div class="custom-file">
                                @php if($pay_dtl->doc_alltop!=null){ @endphp
                                <a href="{{ asset($pay_dtl->doc_alltop) }}" target="_blank"
                                    class="btn btn-primary">SHOW</a>
                                @php }else{ @endphp
                                <button class="btn btn-primary" data-type="doc_alltop" data-id="{{$pay_dtl->id}}"
                                    data-doc="{{$pay_dtl->doc_alltop}}" disabled>SHOW</button>
                                @php } @endphp
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($pay_dtl->doc_inv_performa!=null)
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Performa Invoice</label>
                        <div class="fallback">
                            <div class="custom-file">
                                @php if($pay_dtl->doc_inv_performa!=null){ @endphp
                                <a href="{{ asset($pay_dtl->doc_inv_performa) }}" target="_blank"
                                    class="btn btn-primary">SHOW</a>
                                @php }else{ @endphp
                                <button class="btn btn-primary" data-type="doc_inv_performa" data-id="{{$pay_dtl->id}}"
                                    data-doc="{{$pay_dtl->doc_inv_performa}}" disabled>SHOW</button>
                                @php } @endphp
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($pay_dtl->doc_lainnya!=null)
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Dokumen Lainnya</label>
                        <div class="fallback">
                            <div class="custom-file">
                                @php if($pay_dtl->doc_lainnya!=null){ @endphp
                                <a href="{{ asset($pay_dtl->doc_lainnya) }}" target="_blank"
                                    class="btn btn-primary">SHOW</a>
                                @php }else{ @endphp
                                <button class="btn btn-primary" data-type="doc_lainnya" data-id="{{$pay_dtl->id}}"
                                    data-doc="{{$pay_dtl->doc_lainnya}}" disabled>SHOW</button>
                                @php } @endphp
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- dokumen tambahan -->
                    @if(count($dok)!=0)
                    @foreach($dok as $dok)
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">{{$dok->nama_dokumen == null ? 'Dokumen / File' : $dok->nama_dokumen}}</label>
                        <div class="fallback">
                            <div class="custom-file">
                                @php if($dok->file_upload!=null){ @endphp
                                <a href="{{ asset($dok->file_upload) }}" target="_blank"
                                    class="btn btn-primary">SHOW</a>
                                @php }else{ @endphp
                                <button class="btn btn-primary" data-type="file_upload" data-id="{{$pay_dtl->id}}"
                                    data-doc="{{$dok->file_upload}}" disabled>SHOW</button>
                                @php } @endphp
                            </div>
                        </div>
                    </div>
                    <!-- dokumen tambahan -->
                    @endforeach
                    @endif
                    @endif
                    <br>
                    <button onClick="add_files(this)" data-id_dtl="{{$pay_dtl->id}}" data-id="{{$pay_dtl->id_pay}}"
                        class="btn btn-outline-primary" data-toggle="modal" data-target="#m_modal">Add File<i
                            class="fas fa-pencil-alt ml-2"></i></button><br>
                    <br>
                </div>
                <!--SHOW DOKUMEN-->



                <!-- PAYMENT BY FINANCE -->
                <div class="tab-pane fade show" id="payf">
                    @if($main->division_id==3)
                    @if(count($pay_pays)!=0)
                    @include('finance.payment_voucher.draft_payment')
                    <br>
                    @elseif(count($pay_pays)==0 && $pay_dtl->status=="Completed")
                    <br>
                    <button onClick="Done_payment(this)" data-id_dtl="{{$pay_dtl->id}}" data-id="{{$pay_dtl->id_pay}}"
                        data-usr="finance" class="btn btn-primary" data-toggle="modal" data-target="#m_modal">Create
                        Payment</button>
                    @else
                    <br>
                    <div class="text-center">
                        <span class="text-danger font-weight-bold">Belum Ada Data Yang Masuk</span>
                        <br>
                    </div>
                    @endif
                    @endif
                </div>

                <!-- PAYMENT BY FINANCE -->
                <br>
                <div class="tab-pane fade show" id="hist">
                    @if(count($hist)!=0)
                    @include('finance.payment_voucher.history')
                    @else
                    <div class="text-center">
                        <span class="text-center"><strong>Sorry, No Data Available</strong></span>
                    </div>
                    @endif
                </div>
                <br><br>
                <div class="text-right">
                    <br>
                    @php
                    $redirect = $type=="payv" ? 'payment_voucher' : 'othercost';
                    @endphp
                    {!! Form::button('Back', ['class' => 'btn btn-light btn-cancel', 'data-method'
                    =>'finance/'.$redirect, 'type' => 'button','onclick'=>'cancel(this)']) !!}

                    @if($main->division_id==3)
                    @if($pay_dtl->status=="Pending")
                    <button onClick="Approve_payment(this)" data-id_dtl="{{$pay_dtl->id}}"
                        data-id="{{$pay_dtl->id_pay}}" data-usr="finance" class="btn btn-primary">Approve<i
                            class="far fa-save ml-2"></i></button>
                    <button onClick="Reject_payment(this)" data-id_dtl="{{$pay_dtl->id}}" data-id="{{$pay_dtl->id_pay}}"
                        data-usr="finance" class="btn btn-danger">Reject<i class="fas fa-file ml-2"></i></button>
                    @endif
                    @endif

                    @if(in_array($main->id,explode(',',getConfig('app_finance'))))
                    @if($pay_dtl->status=="Approved" || $forward!=null)
                    <button onClick="Approve_payment(this)" data-id_dtl="{{$pay_dtl->id}}"
                        data-id="{{$pay_dtl->id_pay}}" data-usr="hr" class="btn btn-primary">Approve<i
                            class="far fa-save ml-2"></i></button>
                    <button onClick="Reject_payment(this)" data-id_dtl="{{$pay_dtl->id}}" data-id="{{$pay_dtl->id_pay}}"
                        data-usr="hr" class="btn btn-danger">Reject<i class="fas fa-file ml-2"></i></button>
                    @endif
                    @endif

                    @if($forward!=null || $pay_dtl->id==258)
                    @if(in_array($main->id,explode(',',getConfig('direksi'))))
                    @if($pay_dtl->app_finance !=null && $pay_dtl->app_mng==null)
                    <button onClick="Approve_payment(this)" data-id_dtl="{{$pay_dtl->id}}"
                        data-id="{{$pay_dtl->id_pay}}" data-usr="khusus" class="btn btn-primary">Approve<i
                            class="far fa-save ml-2"></i></button>
                    <button onClick="Reject_payment(this)" data-id_dtl="{{$pay_dtl->id}}" data-id="{{$pay_dtl->id_pay}}"
                        data-usr="khusus" class="btn btn-danger">Reject<i class="fas fa-file ml-2"></i></button>
                    @endif
                    @endif
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- /basic layout -->
</div>
@include('sales.quotation.attribute.modal')
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/pay_voucher-form.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/finance/pay_voucher-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection