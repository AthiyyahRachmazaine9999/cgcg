@extends('layouts.head') @section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h4 class="card-title">Payment Voucher</h4>
            <div class="header-elements">
                <button type="button" class="btn btn-danger btn-sm ml-3" onclick="PrintPayment(this)" data-id_pay="{{$pay_dtl->id_pay}}" data-toggle="modal" data-target="#m_modal"><i class="icon-printer mr-2"></i>Print</button><br>
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
                {!! Form::label('invoice_kwitansi', 'No. Invoice', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay_dtl->no_invoice==null ? "-" : $pay_dtl->no_invoice}}</div>
                </div>
            </div>
            @if($pay_dtl->type_payment=="cbd")
            <div class="form-group row">
                {!! Form::label('invoice_kwitansi', 'No. Performa Invoice', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay_dtl->performa_invoice}}</div>
                </div>
            </div>
            @endif
            <div class="form-group row">
                {!! Form::label('invoice_kwitansi', 'No. Sales Order', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay_dtl->no_so}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('id_vendor', 'Vendor', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{getVendor($pay->id_vendor)->vendor_name}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('invoice_kwitansi', 'No. Purchase Order', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay_dtl->no_po}}</div>
                </div>
            </div>
            @if($pay_dtl->type_payment!="cbd")
            <div class="form-group row">
                {!! Form::label('no_do', 'No. Delivery Order', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay_dtl->no_do}}</div>
                </div>
            </div>
            @endif
            <div class="form-group row">
                {!! Form::label('no_faktur', 'No. Faktur', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay_dtl->no_efaktur}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('tujuan', 'Tujuan / Keperluan', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay_dtl->tujuan}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('tujuan', 'Metode Pembayaran', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$top}}</div>
                </div>
            </div>
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
            <div class="form-group row">
                {!! Form::label('top', 'Lama Pembayaran', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay->top_date}}</div>
                </div>
            </div>
            @endif
            <div class="form-group row">
                {!! Form::label('nominal', 'Nominal Pembayaran', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{number_format($pay_dtl->nominal,2)}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('terbilang', 'Terbilang', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control" style="height:50px;">{{terbilang($pay_dtl->nominal)}}</div>
                </div>
            </div>


            @if($pay_dtl->nominal_payment!=null)
            <legend><b>Payment By Finance</b></legend>
            <div class="form-group row">
                {!! Form::label('terbilang', 'Nominal Payment', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{number_format($pay_dtl->nominal_payment,2)}}</div>
                </div>
            </div>
            @endif
            @if($pay_dtl->tgl_payment!=null)
            <div class="form-group row">
                {!! Form::label('terbilang', 'Tanggal Payment', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{\Carbon\Carbon::parse($pay_dtl->tgl_payment)->format('d F Y')}}</div>
                </div>
            </div>
            @endif
            @if($pay_dtl->note_payment!=null)
            <div class="form-group row">
                {!! Form::label('terbilang', 'Note Payment', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay_dtl->note_payment}}</div>
                </div>
            </div>
            @endif

            @if($pay_dtl->file_payment!=null)
            <div class="form-group row">
                {!! Form::label('terbilang', 'File Payment', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <a href="{{ asset($pay_dtl->file_payment) }}" target="_blank" class="btn btn-primary">SHOW</a>
                </div>
            </div>
            @endif


            <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
                <h6 class="card-title">Show Document</h6>
            </div>
            <br><br>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Document TOP</label>
                <div class="fallback">
                    <div class="custom-file">
                        @php if($pay_dtl->doc_alltop!=null){ @endphp
                        <a href="{{ asset($pay_dtl->doc_alltop) }}" target="_blank" class="btn btn-primary">SHOW</a>
                        @php }else{ @endphp
                        <button class="btn btn-primary" data-type="doc_alltop" data-id="{{$pay_dtl->id}}"
                            data-doc="{{$pay_dtl->doc_alltop}}" disabled>SHOW</button>
                        @php } @endphp
                    </div>
                </div>
            </div>
            @if($top=="CBD")
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
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Dokumen Pemesanan</label>
                <div class="fallback">
                    <div class="custom-file">
                        @php if($pay_dtl->doc_lainnya!=null){ @endphp
                        <a href="{{ asset($pay_dtl->doc_lainnya) }}" target="_blank" class="btn btn-primary">SHOW</a>
                        @php }else{ @endphp
                        <button class="btn btn-primary" data-type="doc_lainnya" data-id="{{$pay_dtl->id}}"
                            data-doc="{{$pay_dtl->doc_lainnya}}" disabled>SHOW</button>
                        @php } @endphp
                    </div>
                </div>
            </div>
            @endif
            @if($pay_dtl->app_finance!=null)
            <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
                <h6 class="card-title">Known By</h6>
            </div>
            <br><br>

            @if($pay_dtl->app_finance!=null)
            <div class="form-group row">
                {!! Form::label('invoice_kwitansi', 'Finance', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{user_name($pay_dtl->app_finance)}}</div>
                </div>
            </div>
            @endif
            @if($pay_dtl->app_hrd!=null)
            <div class="form-group row">
                {!! Form::label('invoice_kwitansi', 'HRD', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{user_name($pay_dtl->app_hrd)}}</div>
                </div>
            </div>
            @endif
            @endif
            <br><br>
            <div class="text-right">
                {!! Form::button('Back', ['class' => 'btn btn-light btn-cancel', 'data-method'
                =>'finance/payment_voucher', 'type' => 'button','onclick'=>'cancel(this)']) !!}

                @if($main->division_id==3)
                @if($pay_dtl->status=="Pending")
                <button onClick="Approve_payment(this)" data-id_dtl="{{$pay_dtl->id}}" data-id="{{$pay_dtl->id_pay}}"
                    data-usr="finance" class="btn btn-primary">Approve<i class="far fa-save ml-2"></i></button>
                <button onClick="Reject_payment(this)" data-id_dtl="{{$pay_dtl->id}}" data-id="{{$pay_dtl->id_pay}}"
                    data-usr="finance" class="btn btn-danger">Reject<i class="fas fa-file ml-2"></i></button>
                @elseif($pay_dtl->status=="Completed")
                <button onClick="Done_payment(this)" data-id_dtl="{{$pay_dtl->id}}" data-id="{{$pay_dtl->id_pay}}"
                    data-usr="finance" class="btn btn-primary" data-toggle="modal" data-target="#m_modal">Done Payment<i
                        class="fas fa-check ml-2"></i></button>
                @endif
                @endif

                @if ($main->id==2 && $pay_dtl->status=="Approved" || $main->id==50 &&
                $pay_dtl->status=="Approved")
                <button onClick="Approve_payment(this)" data-id_dtl="{{$pay_dtl->id}}" data-id="{{$pay_dtl->id_pay}}"
                    data-usr="hr" class="btn btn-primary">Approve<i class="far fa-save ml-2"></i></button>
                <button onClick="Reject_payment(this)" data-id_dtl="{{$pay_dtl->id}}" data-id="{{$pay_dtl->id_pay}}"
                    data-usr="hr" class="btn btn-danger">Reject<i class="fas fa-file ml-2"></i></button>
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
<script src="{{ asset('ctrl/finance/pay_v/pay_voucher-form.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/finance/pay_voucher-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection