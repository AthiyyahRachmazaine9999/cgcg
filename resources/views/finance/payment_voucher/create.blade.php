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
            <div class="row-form_finance">
                <form action="{{ route('payment_finance.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <input type="hidden" name="mark" id="mark" value="new_form_finance" class="form-control"
                            placeholder="Masukkan vendor" readonly>
                        <input type="hidden" name="quo_no" id="quo_no" class="form-control"
                            placeholder="Masukkan quo_no" readonly>


                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Form Payment*</label>
                            <div class="col-lg-7">
                                {!! Form::select('type_voucher',array('blank' => 'Blank Form',
                                'lainnya' => 'With SO'),
                                '',['id' => 'type_voucher', 'class' =>'form-control form-control-select2',
                                'placeholder' => 'Pilih Keperluan Form']) !!}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Tujuan / Keperluan</label>
                            <div class="col-lg-7">
                                <input type="text" name="tujuan" id="tujuan" class="form-control change_hide"
                                    placeholder="Masukkan Tujuan Payment">
                            </div>
                        </div>
                        <div class="form-group row row_invoices">
                            <label class='col-lg-3 col-form-label'>No. Kwitansi</label>
                            <div class="col-lg-7">
                                <input type="text" name="no_invoice" id="invoices" class="form-control change_hide"
                                    placeholder="Masukkan Invoice/Kwitansi">
                            </div>
                        </div>
                        <div class="form-group row row_salesorder">
                            <label class='col-lg-3 col-form-label'>Sales Order</label>
                            <div class="col-lg-7">
                                {!! Form::select('no_so', $no_so, null,['id' => 'no_so', 'class' =>
                                'form-control form-control-select2 select2 change_hide num_salesorder', 'placeholder' =>
                                'Pilih
                                Sales Order']) !!}
                            </div>
                        </div>
                        <div class="form-group row autovendor">
                            <label class='col-lg-3 col-form-label'>Vendor</label>
                            <div class="col-lg-7">
                                {!! Form::select('vendor_id', $vendor, null,['id' => 'vnd', 'class' => 'change_hide
                                form-control
                                form-control-select2 vendors', 'placeholder' => 'Pilih Vendor', 'disabled']) !!}
                            </div>
                        </div>
                        <div class="form-group row row_vendors">
                            <label class='col-lg-3 col-form-label'>Vendor</label>
                            <div class="col-lg-7">
                                {!! Form::select('sec_vnd', $vendor, null,['id' => 'sec_vnd', 'class' => 'change_hide
                                form-control
                                form-control-select2', 'placeholder' => 'Pilih Vendor']) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Customer</label>
                            <div class="col-lg-7">
                                {!! Form::select('id_cust', $cust, null,['id' => 'cust', 'class' => 'change_hide
                                form-control
                                form-control-select2', 'placeholder' => 'Pilih Customer']) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Tanggal Invoice</label>
                            <div class="col-lg-3">
                                <input type="text" name="tgl_payment" id="tgl_payment"
                                    class="change_hide form-control date" placeholder="Dari Tanggal">
                            </div>
                            <label class='col-lg-1 col-form-label'>s/d</label>
                            <div class="col-lg-3">
                                <input type="text" id="due_dates" class="change_hide date form-control" id="sampai_tgl" name="to_date" placeholder="Sampai Tanggal">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Nominal Payment</label>
                            <div class="col-lg-7">
                                <input type="number" step="any" id="ongkir" class="change_hide form-control"
                                    name="nominal" class="form-control" placeholder="Masukkan Nominal">
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
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Performa Invoice</label>
                            <div class="col-lg-7">
                                <input type="file" id="doc_performa" name="doc_inv_performa"
                                    class="file-input form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Dokumen Lainnya</label>
                            <div class="col-lg-7">
                                <input type="file" id="doc_lainnya" name="doc_lainnya" class="file-input form-control">
                            </div>
                        </div>

                        <br><br>
                        <!-- Document -->
                        <div class="text-right">
                            {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method'
                            =>'finance/payment_voucher', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                            <button type="submit" class="btn btn-primary">Create<i
                                    class="far fa-save ml-2"></i></button>
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
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/pay_voucher-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection