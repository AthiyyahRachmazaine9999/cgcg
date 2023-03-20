@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Create Payment Voucher</h5>
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

            <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <input type="hidden" name="id_quo" id="Quo_Id" class="form-control" placeholder="Masukkan SO"
                        readonly>
                    <input type="hidden" name="vendor_id" id="vendor_id" class="form-control"
                        placeholder="Masukkan vendor" readonly>
                    <input type="hidden" name="mark" id="mark" value="FromFinance" class="form-control"
                        placeholder="Masukkan vendor" readonly>

                    <div class="form-group row row_invoices">
                        <label class='col-lg-3 col-form-label'>No. Invoice</label>
                        <div class="col-lg-7">
                            <input type="text" name="no_invoice" id="invoices" class="form-control"
                                placeholder="Masukkan No. Invoice">
                        </div>
                    </div>
                    <div class="form-group row row_p_invoices">
                        <label class='col-lg-3 col-form-label'>No. Performa Invoice</label>
                        <div class="col-lg-7">
                            <input type="text" name="no_peforma_inv" id="invoice_peforma" class="form-control"
                                placeholder="Masukkan No. Performa Invoice">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>No. Sales Order</label>
                        <div class="col-lg-7">
                            <input type="text" name="no_so" id="sales_order" class="form-control"
                                placeholder="Masukkan No. Sales Order" readonly>
                        </div>
                    </div>
                    <div class="form-group row row_do">
                        <label class='col-lg-3 col-form-label'>No. Delivery Order</label>
                        <div class="col-lg-7">
                            {!! Form::text('no_do', '', ['id' => 'no_do',
                            'class' => 'form-control form-control', 'placeholder' => 'Masukkan No. DO']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>No. Purchase Order</label>
                        <div class="col-lg-7">
                            {!! Form::select('no_po', $no_po, null,['id' => 'purchase_order', 'class' =>
                            'form-control form-control-select2', 'placeholder' => 'Pilih
                            PO']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Vendor</label>
                        <div class="col-lg-7">
                            {!! Form::select('vendor_id', $vendor, null,['id' => 'vnd', 'class' =>
                            'form-control form-control-select2 select2', 'placeholder' => 'Pilih
                            Vendor']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>No. Faktur</label>
                        <div class="col-lg-7">
                            {!! Form::text('no_faktur', '',['id' => 'no_faktur', 'class' =>
                            'form-control form-control', 'placeholder' => 'Masukkan No. Faktur']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Metode Pembayaran</label>
                        <div class="col-lg-7">
                            {!! Form::select('payment', array('top' => 'TOP', 'cbd' => 'CBD', 'net' => 'Nett'), '',
                            ['id' => 'payment','class' => 'form-control form-control-select2', 'placeholder' => '*'])
                            !!}
                        </div>
                    </div>
                    <div class="exc_cbd">
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Tanggal Invoice</label>
                            <div class="col-lg-3">
                                <input type="text" class="date form-control" id="dari_tgl" name="from_date"
                                    class="form-control" placeholder="Dari Tanggal">
                            </div>
                            <label class='col-lg-1 col-form-label'>s/d</label>
                            <div class="col-lg-3">
                                <input type="text" class="date form-control" id="sampai_tgl" name="to_date"
                                    placeholder="Sampai Tanggal">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Lama Pembayaran</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="top" name="top_date" class="form-control"
                                    placeholder="TOP" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Nominal</label>
                        <div class="col-lg-7">
                            <input type="number" step="any" id="nominal" class="form-control" name="total"
                                class="form-control" placeholder="Masukkan Nominal">
                        </div>
                    </div>

                    <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
                        <h6 class="card-title">Upload Document</h6>
                    </div>
                    <br><br>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Dokumen TOP</label>
                        <div class="col-lg-7">
                            <input type="file" id="doc_alltop" name="doc_alltop" class="file-input form-control">
                        </div>
                    </div>
                    <div class="doc_cbds">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Performa Invoice</label>
                            <div class="col-lg-7">
                                <input type="file" id="doc_performa" name="doc_inv_performa"
                                    class="file-input form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Dokumen Pemesanan</label>
                            <div class="col-lg-7">
                                <input type="file" id="doc_lainnya" name="doc_lainnya" class="file-input form-control">
                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-group row row_do">
                        <label class="col-lg-3 col-form-label">Delivery Order</label>
                        <div class="col-lg-7">
                            <input type="file" id="doc_do" name="doc_do" class="file-input form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">E-Faktur</label>
                        <div class="col-lg-7">
                            <input type="file" name="doc_efaktur" class="file-input form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Kwitansi</label>
                        <div class="col-lg-7">
                            <input type="file" name="doc_kwitansi" class="file-input form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Invoice</label>
                        <div class="col-lg-7">
                            <input type="file" name="doc_invoice" class="file-input form-control">
                        </div>
                    </div> -->
                    <br><br>
                    <!-- Document -->
                    <div class="text-right">
                        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method'
                        =>'finance/payment_voucher', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                        <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i></button>
                    </div>
                </div>
            </form>
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
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/pay_v/pay_voucher-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection