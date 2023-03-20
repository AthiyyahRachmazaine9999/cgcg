@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Update Payment Voucher</h5>
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

            <form action="{{ route('payment_finance.update', $pay->id )}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                {!! Form::hidden('id',$pay->id,['id'=>'id','class'=>'form-control']) !!}
                {!! Form::hidden('id_pay',$pay_dtl->id,['id'=>'id_pay','class'=>'form-control']) !!}
                {!! Form::hidden('type',$type,['id'=>'type','class'=>'form-control']) !!}
                <input type="hidden" name="mark" id="mark" value="FromFinance" class="form-control"
                    placeholder="Masukkan vendor" readonly>

                <div class="card-body">
                    <input type="hidden" name="id_quo" value="{{$pay_dtl->id_quo}}" id="Quo_Id" class="form-control"
                        placeholder="Masukkan SO" readonly>

                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Tujuan / Keperluan</label>
                        <div class="col-lg-7">
                            <input type="text" name="tujuan" id="tujuan" value="{{$pay_dtl->tujuan}}"
                                class="form-control change_hide" placeholder="Masukkan Tujuan Payment">
                        </div>
                    </div>
                    <div class="form-group row row_invoices">
                        <label class='col-lg-3 col-form-label'>No. Kwitansi</label>
                        <div class="col-lg-7">
                            <input type="text" name="no_invoice" id="invoices" value="{{$pay_dtl->no_invoice}}"
                                class="form-control change_hide" placeholder="Masukkan Invoice/Kwitansi">
                        </div>
                    </div>
                    <div class="form-group row row_salesorder">
                        <label class='col-lg-3 col-form-label'>Sales Order</label>
                        <div class="col-lg-7">
                            {!! Form::select('no_so', $no_so, $pay_dtl->id_quo,['id' => 'no_so', 'class' =>
                            'form-control form-control-select2 select2 change_hide num_salesorder', 'placeholder' =>
                            'Pilih
                            Sales Order']) !!}
                        </div>
                    </div>
                    <div class="form-group row autovendor">
                        <label class='col-lg-3 col-form-label'>Vendor</label>
                        <div class="col-lg-7">
                            {!! Form::select('vendor_id', $vendor, $pay_dtl->id_vendor,['id' => 'vnd', 'class' =>
                            'change_hide form-control form-control-select2 vendors', 'placeholder' => 'Pilih Vendor'])
                            !!}
                        </div>
                    </div>
                    <div class="form-group row row_vendors">
                        <label class='col-lg-3 col-form-label'>Vendor</label>
                        <div class="col-lg-7">
                            {!! Form::select('sec_vnd', $vendor, $pay_dtl->id_vendor,['id' => 'sec_vnd', 'class' =>
                            'change_hide
                            form-control
                            form-control-select2', 'placeholder' => 'Pilih Vendor']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Customer</label>
                        <div class="col-lg-7">
                            {!! Form::select('id_cust', $cust, $pay_dtl->id_customer,['id' => 'cust', 'class' =>
                            'change_hide form-control form-control-select2', 'placeholder' => 'Pilih Customer']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Tanggal Invoice</label>
                        <div class="col-lg-3">
                            <input type="text" name="tgl_payment" id="tgl_payment" value="{{$pay_dtl->from_date}}"
                                class="change_hide form-control date" placeholder="Dari Tanggal">
                        </div>
                        <label class='col-lg-1 col-form-label'>s/d</label>
                        <div class="col-lg-3">
                            <input type="text" class="change_hide date form-control"
                                value="{{$pay_dtl->to_date==null ? '' : $pay_dtl->to_date}}" id="tgl_payment"
                                name="to_date" placeholder="Sampai Tanggal">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Nominal Payment</label>
                        <div class="col-lg-7">
                            <input type="number" step="any" id="ongkir" value="{{$pay_dtl->nominal}}"
                                class="change_hide form-control" name="nominal" class="form-control"
                                placeholder="Masukkan Nominal">
                        </div>
                    </div>
                    @if($pay_dtl->doc_alltop!=null || $pay_dtl->doc_inv_performa!=null || $pay_dtl->doc_lainnya!=null)
                    <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
                        <h6 class="card-title">Document</h6>
                    </div>
                    <br><br>
                    @if($pay_dtl->doc_alltop!=null)
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Dokumen TOP</label>
                        @php if($pay_dtl->doc_alltop==null) { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_alltop" value="{{$pay_dtl->doc_alltop}}"
                                class="file-input form-control">
                        </div>
                        @php } else { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_alltop" value="{{$pay_dtl->doc_alltop}}"
                                class="file-input form-control">
                        </div>
                        <a href="{{ asset($pay_dtl->doc_alltop)}}" target="_blank" id="btn"><i class="fa fa-check"></i>
                            Sudah Terisi. Check Disini</a>
                        @php } @endphp
                    </div>
                    @endif
                    @if($pay_dtl->doc_inv_performa!=null)
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Performa Invoice</label>
                        @php if($pay_dtl->doc_inv_performa==null) { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_inv_performa" value="{{$pay_dtl->doc_inv_performa}}"
                                class="file-input form-control">
                        </div>
                        @php } else { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_inv_performa" value="{{$pay_dtl->doc_inv_performa}}"
                                class="file-input form-control">
                        </div>
                        <a href="{{ asset($pay_dtl->doc_inv_performa) }}" target="_blank" id="btn"><i
                                class="fa fa-check"></i>
                            Sudah Terisi. Check Disini</a>
                        @php } @endphp
                    </div>
                    @endif
                    @if($pay_dtl->doc_lainnya!=null)
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Dokumen Lainnya</label>
                        @php if($pay_dtl->doc_lainnya==null) { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_lainnya" value="{{$pay_dtl->doc_lainnya}}"
                                class="file-input form-control">
                        </div>
                        @php } else { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_lainnya" value="{{$pay_dtl->doc_lainnya}}"
                                class="file-input form-control">
                        </div>
                        <a href="{{ asset($pay_dtl->doc_lainnya) }}" target="_blank" id="btn"><i
                                class="fa fa-check"></i>
                            Sudah Terisi. Check Disini</a>
                        @php } @endphp
                    </div>
                    @endif
                    @endif
                    <br><br>

                    <div class="text-right">
                        @php
                        $redirect = $type=="payv" ? 'payment_voucher' : 'othercost';
                        @endphp
                        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method'
                        =>'finance/'.$redirect, 'type' => 'button','onclick'=>'cancel(this)']) !!}
                        <button type="submit" class="btn btn-primary">Update<i class="far fa-save ml-2"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="m_modal" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modaltitle"><span class="flaticon-share"></span>&nbsp;&nbsp;
                    Icons</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalbody">
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/pay_voucher-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection