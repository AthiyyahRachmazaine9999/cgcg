@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Update Invoice</h5>
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

            <form action="{{ route('invoice_up.update', $invoice_id->id )}}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="id_quo" class="form-control" name="hide_id_quo" value="{{$invoice_id->id_quo}}"
                    placeholder="Nomer SO" readonly>
                {!! Form::hidden('id',$invoice_id->id,['id'=>'id','class'=>'form-control']) !!}
                {!! Form::hidden('finish',$invoice_id->ket_lunas,['id'=>'finish','class'=>'form-control']) !!}
                <input type="hidden" id="total_include"
                    value="{{$invoice_id->total_payment==null ? number_format($quo_mo->quo_price) : $invoice_id->total_payment}}"
                    name="total_amount_inc" class="form-control" placeholder="Masukkan Jumlah Pembayaran" readonly>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label" value="pro_priceType">
                            No. Invoice</label>
                        <div class="col-lg-7">
                            <input type="text" id="no_invoice" class="form-control" name="no_invoice"
                                value="{{$invoice_id->no_invoice}}" placeholder="No. Invoice" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label" value="">No. SO</label>
                        <div class="col-lg-7">
                            <input type="invoice_id" id="id_quo" class="form-control" name="id_quo"
                                value="SO{{sprintf('%06d', $invoice_id->id_quo)}}" placeholder="Nomer SO" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>No. NPWP</label>
                        <div class="col-lg-7">
                            <input type="text" id="npwp" class="form-control" name="npwp" value="{{$invoice_id->npwp}}"
                                placeholder="Masukkan Nomer NPWP">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Alamat NPWP</label>
                        <div class="col-lg-7">
                            <input type="text" id="npwp_alamat" class="form-control" name="npwp_alamat"
                                value="{{$invoice_id->npwp_alamat}}" placeholder="Masukkan Alamat NPWP">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Nama Bank</label>
                        <div class="col-lg-7">
                            <input type="text" id="nama_bank" value="{{$invoice_id->nama_bank}}" class="form-control"
                                name="nama_bank" placeholder="Masukkan Nama Bank">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>No. NTPN</label>
                        <div class="col-lg-7">
                            <input type="text" id="no_ntpn" value="{{$invoice_id->no_ntpn}}" name="no_ntpn"
                                class="form-control" placeholder="Masukkan Nomer NTPN">
                        </div>
                    </div>
                    <!-- Payment -->
                    <div class="form-group row">
                        <legend class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
                            <h5 class="card-title">Pembayaran</h5>
                        </legend><br>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Pembayaran Selesai ?</label>
                        <div class="col-lg-7">
                            <input type="checkbox" class="form-check" id="checkss" name="checks"
                                style="margin-top:10px;">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Tipe Pembayaran</label>
                        <div class="col-lg-7">
                            {!! Form::select('type_payment', array('parsial' => 'Parsial', 'full' => 'Full / Lunas'),
                            $invoice_id->type_payment,
                            ['id' => 'types','class' => 'form-control form-control-select2', 'placeholder' => '*',
                            'required'])
                            !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Total Pembayaran (Include)</label>
                        <div class="col-lg-7">
                            <input type="text" id="total_amount_inc"
                                value="{{$invoice_id->total_payment==null ? $quo_mo->quo_price : $invoice_id->total_payment}}"
                                name="quo_price" onChange="totalss();" onKeyup="totalss();" class="form-control totalss"
                                placeholder="Masukkan Jumlah Pembayaran" required>
                        </div>
                    </div>
                    @if($invoice_id->type_payment == "full" ||
                    $invoice_id->type_payment == "parsial" && $cdtl==0)
                    @foreach($inv as $inv)
                    <div class="payments">
                        <legend class="text-info-700">Detail Pembayaran</legend>
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Tanggal Pembayaran</label>
                            <div class="col-lg-7">
                                <input type="text" id="full_payments" value="{{$inv->date_payment}}"
                                    name="date_payment[]" class="form-control dates"
                                    placeholder="Masukkan Tanggal Pembayaran" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Nominal</label>
                            <div class="col-lg-7">
                                <input type="number" id="full_payment" value="{{$invoice_id->payment_amount}}"
                                    name="payment_amounts[]" class="form-control amounts"
                                    placeholder="Masukkan Jumlah Pembayaran">
                            </div>
                            <button type="button" onClick="next_payment(this)" data-id_inv="{{$invoice_id->id}}"
                                class="btn bg-primary-400 btn-icon rounded-round legitRipple">
                                <b><i class="fas fa-plus"></i></b></button>
                        </div>
                    </div>
                    @endforeach
                    @elseif($invoice_id->type_payment == null)
                    <div class="payments">
                        <legend class="text-info-700">Detail Pembayaran</legend>
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Tanggal Pembayaran</label>
                            <div class="col-lg-7">
                                <input type="text" id="full_payments" value="" name="date_payment[]"
                                    class="form-control dates" placeholder="Masukkan Tanggal Pembayaran" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Nominal</label>
                            <div class="col-lg-7">
                                <input type="number" id="full_payment" value="{{$invoice_id->payment_amount}}"
                                    name="payment_amounts[]" class="form-control amounts"
                                    placeholder="Masukkan Jumlah Pembayaran">
                            </div>
                            <button type="button" onClick="next_payment(this)" data-id_inv="{{$invoice_id->id}}"
                                class="btn bg-primary-400 btn-icon rounded-round legitRipple">
                                <b><i class="fas fa-plus"></i></b></button>
                        </div>
                    </div>
                    <div class="nexts"></div>
                    @endif

                    @if($invoice_id->type_payment == "parsial" || $invoice_id->type_payment == "parsial" && $cdtl!=0)
                    <legend class="text-info-700">Detail Pembayaran</legend>
                    @php $i=1; $k=1; $c=1; $n=1; $q=1; $s=1; foreach ($inv as $inv){ @endphp
                    {!! Form::hidden('inv_dtl[]',$inv->id,['id'=>'inv_dtl','class'=>'form-control']) !!}
                    <div class="row_parsial_{{$s++}}"><strong>Detail {{$q++}} </strong></div><br>
                    <div class="form-group row row_parsial_{{$n++}}">
                        <label class='col-lg-3 col-form-label'> Tanggal Pembayaran</label>
                        <div class="col-lg-7">
                            <input type="text" id="date_payment" max="{{$quo_mo->quo_price}}"
                                value="{{$inv->date_payment}}" name="date_payment[]" class="form-control dates"
                                placeholder="Masukkan Jumlah Pembayaran">
                        </div>
                    </div>
                    <div class="form-group row row_parsial_{{$c++}}">
                        <label class='col-lg-3 col-form-label'> Nominal</label>
                        <div class="col-lg-7">
                            <input type="number" id="full_payment" max="{{$quo_mo->quo_price}}"
                                value="{{$inv->payment_amount}}" name="payment_amounts[]" class="form-control amounts"
                                placeholder="Masukkan Jumlah Pembayaran">
                        </div>
                        <button type="button" onClick="remove_payment(this)" data-equ="{{$k++}}" data-type="removes"
                            data-id="{{$inv->id}}" data-id_quo="{{$inv->id_quo}}" data-id_quo_inv="{{$inv->id_quo_inv}}"
                            data-pay="{{$inv->payment_amount}}"
                            class="btn btn-outline-danger btn-icon rounded-round legitRipple">
                            <b><i class="fas fa-trash"></i></b></button>
                    </div>
                    @php } @endphp
                    <div class="nexts"></div>
                    <button type="button" onClick="next_payment(this)" data-id_inv="{{$invoice_id->id}}"
                        class="btn bg-primary-400 btn-icon rounded-round legitRipple">
                        <b><i class="fas fa-plus"></i></b></button>
                    <br>
                    @endif
                    <div class="form-group row row_selisih">
                        <label class='col-lg-3 col-form-label'>Selisih Pembayaran</label>
                        <div class="col-lg-7">
                            <input type="number" id="selisih" name="selisih" value="{{$sisa_bayar}}"
                                class="form-control minus" placeholder="Masukkan Selisih Pembayaran" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Potongan Biaya Bank</label>
                        <div class="col-lg-7">
                            <input type="number" id="pot_biaya_bank" value="{{$invoice_id->pot_biaya_bank}}"
                                name="pot_biaya_bank" class="form-control" placeholder="Masukkan Potongan Biaya Bank">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Potongan PPn</label>
                        <div class="col-lg-7">
                            <input type="number" id="potongan_ppn" value="{{$invoice_id->potongan_ppn}}"
                                name="potongan_ppn" class="form-control" placeholder="Masukkan PPn">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Potongan PPh</label>
                        <div class="col-lg-7">
                            <input type="number" id="potongan_pph" value="{{$invoice_id->potongan_pph}}"
                                name="potongan_pph" class="form-control" placeholder="Masukkan PPh">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Keterangan</label>
                        <div class="col-lg-7">
                            <textarea type="text" id="note" value="" name="note" class="form-control"
                                placeholder="Masukkan Keterangan">{{$invoice_id->note}}</textarea>
                        </div>
                    </div>
                    <div class="text-right">
                        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                        'data-method' =>'finance/invoice', 'type' =>
                        'button','onclick'=>'cancel(this)']) !!}
                        <button type="submit" class="btn btn-primary">Update<i class="far fa-save ml-2"></i>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection @section('script')
<script src="{{ asset('ctrl/finance/finance_invoice-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection