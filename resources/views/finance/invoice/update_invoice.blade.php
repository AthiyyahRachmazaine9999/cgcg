@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Invoice template -->
    <div class="card">
        <div class="card-header bg-transparent header-elements-inline">
            <h5 class="card-title"><a href="">INVOICE PAYMENT </a>- {{$quo_mo->quo_no}}
                [{{'SO'.sprintf("%06d",$invoice_id->id_quo)}}]</h5>
            <div class="header-elements">
                @if($invoice_id->type_payment!=null)
                <button type="button" onclick="PrintInvoicing(this)" data-id="{{$invoice_id->id}}"
                    data-no_inv="{{$invoice_id->no_invoice}}" class="btn btn-warning btn-sm ml-3"><i
                        class="icon-printer mr-2"></i> Print</button>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-9">
                    <div class="mb-4">
                        <div class="uppers">
                            <h2 class="text-dark font-weight-bold">{{$invoice_id->no_invoice}}</h2>
                            <ul class="list list-unstyled mb-0">
                                <li>{{getCustomer($quo_mo->id_customer)->company}}</li>
                                <li>{{getCustomer($quo_mo->id_customer)->address}}</li>
                            </ul>
                            <br>
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td scope="row"><strong>No. DO</strong></td>
                                        <td>:
                                            @php 
                                            if($wh_out!==null){
                                                echo $wh_out->no_do == null ? 'WH/OUT'.\Carbon\Carbon::parse($outs->created_at)->format('y').'/'.$outs->id_wh_out : $wh_out->no_do;
                                            }else{
                                                echo "<p class='text-danger'>Tidak ada nomer DO, paket ini bermasalah</p>";
                                            }
                                            @endphp 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row"><strong>No. NPWP</strong></td>
                                        <td>: {{$invoice_id->npwp}}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row"><strong>Nama NPWP</strong></td>
                                        <td>: {{$invoice_id->npwp_nama}}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row"><strong>No. NTPN PPh</strong></td>
                                        <td>:
                                            @if($invoice_id->file_ntpn_pph!=null)
                                            <a href="{{ asset($invoice_id->file_ntpn_pph) }}"><strong>
                                                    {{$invoice_id->no_ntpn_pph}} </strong>
                                            </a>
                                            @else
                                            {{$invoice_id->no_ntpn_pph}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row"><strong>No. NTPN PPn</strong></td>
                                        <td>:
                                            @if($invoice_id->file_ntpn_ppn!=null)
                                            <a href="{{ asset($invoice_id->file_ntpn_ppn) }}"><strong>
                                                    {{$invoice_id->no_ntpn_ppn}} </strong>
                                            </a>
                                            @else
                                            {{$invoice_id->no_ntpn_ppn}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button type="button" onclick="Edit_detailPayment(this)"
                                                data-type="edit_upper" data-no_inv="{{$invoice_id->no_invoice}}"
                                                data-id="{{$invoice_id->id}}"
                                                class="btn btn-primary btn-icon text-center"><i
                                                    class="fas fa-pencil-alt mr-2"></i>Edit</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="mb-6 mt-6">
                        <div class="text-sm-right">
                            @php
                            if($invoice_id->type_payment=="parsial")
                            {
                            if($invoice_id->ket_lunas=="Finish")
                            {
                            $stats = 'PARSIAL - LUNAS';
                            }else{
                            $stats = 'PARSIAL';
                            }
                            }else if($invoice_id->type_payment=="full")
                            {
                            $stats = 'FULL - LUNAS';
                            }else{
                            $stats = 'Need Update';
                            }
                            @endphp
                            @if($stats == 'PARSIAL' || $stats == 'Need Update')
                            <h2 class="text-danger font-weight-bold">{{$stats}}</h2>
                            <ul class="list list-unstyled mb-0">
                                <li>Invoice Date : <span
                                        class="font-weight-semibold">{!!\Carbon\carbon::parse($invoice_id->tgl_invoice)->format('d
                                        F Y')!!}</span>
                                </li>
                                @if($invoice_id->tgl_jatuhtempo)
                                <li>Due Date : <span
                                        class="font-weight-semibold">{!!\Carbon\carbon::parse($invoice_id->tgl_jatuhtempo)->format('d
                                        F Y')!!}</span>
                                </li>
                                @endif
                                <li>Latest Update : <span
                                        class="font-weight-semibold">{!!\Carbon\carbon::parse($invoice_id->created_at)->format('d
                                        F Y')!!}</span>
                                </li>
                            </ul>
                            @else
                            <h2 class="text-primary font-weight-bold">{{$stats}}</h2>
                            <ul class="list list-unstyled mb-0">
                                <li>Latest Update : <span
                                        class="font-weight-semibold">{!!\Carbon\carbon::parse($invoice_id->created_at)->format('d-m-Y')!!}</span>
                                </li>
                            </ul>
                            @endif
                            <div id="new_date">
                            </div>
                            <div class="row mt-3" id="dates">
                                <div class="col-lg-6">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-tabs-responsive bg-light border-top">
            <ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
                <li class="nav-item"><a href="#product" class="nav-link active" data-toggle="tab"><i
                            class="fas fa-table mr-2"></i>Product Detail</a></li>
                <li class="nav-item"><a href="#info" class="nav-link" data-toggle="tab"><i
                            class="icon-menu7 mr-2"></i>Detail Pembayaran</a></li>
                <li class="nav-item"><a href="#ptg" class="nav-link" data-toggle="tab"><i
                            class="icon-stack2 mr-2"></i>Biaya Lainnya</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="product">
                @include('finance.invoice.product_detail')
            </div>
            <div class="tab-pane fade show" id="info">
                <div class="edit_dtl">
                    <div class="">
                        @if($cdtl!=0)
                        <div class="table-responsive">
                            <table class="table table-lg">
                                <thead class="">
                                    <tr class="">
                                        <th class="text-left">Bentuk Pembayaran</th>
                                        <th class="text-center">Tanggal Pembayaran</th>
                                        <th class="text-center">Created At</th>
                                        <th class="text-center">Subtotal</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $subtotal= 0;
                                    foreach($inv as $inv) {
                                    @endphp
                                    <tr>
                                        @if($payment->method_payment == "transfer")
                                        <td class="text-left">
                                            Transfer <br>
                                            <span
                                                class="text-muted"><i>{{$payment->bank_name.' - '.$payment->bank_no}}</i></span>
                                        </td>
                                        @else
                                        <td class="text-left">Cash</td>
                                        @endif
                                        <td class="text-center">
                                            {{\Carbon\carbon::parse($inv->date_payment)->format('d F Y')}}</td>
                                        <td class="text-center">
                                            {{\Carbon\carbon::parse($inv->created_at)->format('d F Y')}}</td>
                                        <td class="text-center">
                                            {{number_format(getTotalInvDetail($inv->id_dtl_payment))}}</td>
                                        <td class="text-center">
                                            <div class="list-icons">
                                                <div class="list-icons-item dropdown">
                                                    <a href="#" class="list-icons-item dropdown-toggle caret-0"
                                                        data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" onclick="Edit_detailPayment(this)"
                                                            data-type="edit_pembayaran" data-id="{{$invoice_id->id}}"
                                                            data-id_pay="{{$inv->id_dtl_payment}}"><i
                                                                class="fas fa-pencil-alt"></i>Edit</a>
                                                        <a class="dropdown-item" onclick="Edit_detailPayment(this)"
                                                            data-type="detail_pembayaran" data-toggle="modal"
                                                            data-target="#m_modal" data-id="{{$invoice_id->id}}"
                                                            data-id_pay="{{$inv->id_dtl_payment}}"><i
                                                                class="fas fa-eye"></i>Detail</a>
                                                        <a class="dropdown-item" onclick="Edit_detailPayment(this)"
                                                            data-type="hapus_pembayaran" data-id="{{$invoice_id->id}}"
                                                            data-id_pay="{{$inv->id_dtl_payment}}"><i
                                                                class="fas fa-trash text-danger"></i>Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                    $subtotal += $inv->payment_amount;
                                    } @endphp
                                </tbody>
                            </table>
                        </div>
                        <br>
                        @endif
                        @if($invoice_id->ket_lunas!="Finish")
                        <div class="text-left" style="padding-left:20px;padding-top:20px;">
                            <button type="button" onclick="Edit_detailPayment(this)" data-count="kosong"
                                data-type="tambah_pembayaran" data-id="{{$invoice_id->id}}"
                                class="btn bg-primary-400 btn-icon rounded-round legitRipple">
                                <b><i class="fas fa-plus"></i></b></button>
                        </div>
                        <br>
                        @endif
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show" id="ptg">
                <div class="edit_potongans">
                    <div class="card-body">
                        <div class="table-responsive">
                            @if($coth!=0)
                            <table class="table">
                                <thead class="">
                                    <tr class="">
                                        <th class="text-left">Biaya Lainnya</th>
                                        <th class="text-center">Nominal Biaya</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inv_oth as $oth)
                                    <tr>
                                        <td>{{$oth->des_potongan}}</td>
                                        <td class="text-center">{{number_format($oth->nilai_potongan)}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            @if($invoice_id->ket_lunas!="Finish")
                            <div class="text-left">
                                <button type="button" onclick="Edit_detailPayment(this)" data-count="kosong"
                                    data-type="tambah_potongan" data-id="{{$invoice_id->id}}"
                                    class="btn bg-primary-400 btn-icon rounded-round legitRipple">
                                    <b><i class="fas fa-plus"></i></b></button>
                            </div>
                            @else
                            <div class="text-center">
                                <span class="text-danger font-weight-bold">Tidak Ada Data Yang Masuk</span>
                                <br>
                            </div>
                            @endif
                            <br><br>
                            @endif
                            <!-- tabs -->
                        </div>
                        @if($coth!=0)
                        <br>
                        <br>
                        <div class="text-right mt-3">
                            <button type="button" class="btn btn-primary btn-labeled btn-labeled-left"
                                onclick="Edit_detailPayment(this)" data-type="edit_potongan"
                                data-id="{{$invoice_id->id}}"><b><i class="fas fa-pencil-alt"></i></b>Edit</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
            </div>
        </div>
    </div>
</div>
@include('sales.quotation.attribute.modal')
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/finance_invoice-form.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/sales/quotation-invoice.js?v=').rand() }}" type="text/javascript"></script>
@if(session()->has('success'))
<script type="text/javascript">
swal({
    title: "Success",
    text: "{{ session()->get('success') }}",
    icon: "success",
});
</script>
@endif
@endsection