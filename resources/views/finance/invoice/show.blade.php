@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Detail Invoice</h5>
        </div>
        <input type="hidden" id="id_quo" class="form-control" name="hide_id_quo" value="{{$inv->id_quo}}"
            placeholder="Nomer SO" readonly>
        {!! Form::hidden('id',$inv->id,['id'=>'id','class'=>'form-control']) !!}
        {!! Form::hidden('finish',$inv->ket_lunas,['id'=>'finish','class'=>'form-control']) !!}
        <input type="hidden" id="total_include" value="{{$quo->quo_price}}" name="total_amount_inc" class="form-control"
            placeholder="Masukkan Jumlah Pembayaran" readonly>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-check-input-styled-primary" value="pro_priceType">
                    No. Invoice</label>
                <div class="col-lg-7">
                    <input type="text" id="no_invoice" class="form-control" name="no_invoice"
                        value="{{$inv->no_invoice}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-check-input-styled-primary" value="">No. SO</label>
                <div class="col-lg-7">
                    <input type="invoice_id" id="id_quo" class="form-control" name="id_quo"
                        value="SO{{sprintf('%06d', $inv->id_quo)}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>No. NPWP</label>
                <div class="col-lg-7">
                    <input type="text" id="npwp" class="form-control" name="npwp" value="{{$inv->npwp}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Alamat NPWP</label>
                <div class="col-lg-7">
                    <input type="text" id="npwp_alamat" class="form-control" name="npwp_alamat"
                        value="{{$inv->npwp_alamat}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Nama Bank</label>
                <div class="col-lg-7">
                    <input type="text" id="nama_bank" value="{{$inv->nama_bank}}" class="form-control" name="nama_bank"
                        readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>No. NTPN</label>
                <div class="col-lg-7">
                    <input type="text" id="no_ntpn" value="{{$inv->no_ntpn}}" name="no_ntpn" class="form-control"
                        readonly>
                </div>
            </div>
            <!-- Payment -->
            <div class="form-group row">
                <legend class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
                    <h5 class="card-title">Pembayaran</h5>
                </legend><br>
            </div>

            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Pembayaran</label>
                <div class="col-lg-7">
                    {!! Form::text('type_payment',$inv->type_payment=="parsial" ? "Secara Parsial":
                    "Full / Lunas",['class' =>
                    'form-control
                    form-control','readonly'])
                    !!}
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Total Pembayaran (Include)</label>
                <div class="col-lg-7">
                    <input type="text" id="total_amount_inc" value="{{number_format($quo->quo_price)}}"
                        name="total_amount_inc" class="form-control" readonly>
                </div>
            </div>
            <legend class="text-info-700">Detail Pembayaran</legend>
            <table class="table table-bordered" id="setts">
                @php $i=1; $k=1; $c=1; $n=1; $q=1; $s=1; foreach ($inv_dtl as $dtl){ @endphp
                <div class="row_parsial_{{$s++}}"><strong>Detail {{$q++}} </strong></div><br>
                <div class="form-group row row_parsial_{{$n++}}">
                    <label class='col-lg-3 col-form-label'> Tanggal Pembayaran</label>
                    <div class="col-lg-7">
                        <input type="text" id="date_payment" value="{{$dtl->date_payment}}" name="date_payment[]"
                            class="form-control dates" readonly>
                    </div>
                </div>
                <div class="form-group row row_parsial_{{$c++}}">
                    <label class='col-lg-3 col-form-label'> Nominal</label>
                    <div class="col-lg-7">
                        <input type="text" id="full_payment" value="{{number_format($inv->payment_amount)}}"
                            name="payment_amounts[]" class="form-control amounts" readonly>
                    </div>
                </div>
                @php } @endphp
                <div class="form-group row row_selisih">
                    <label class='col-lg-3 col-form-label'>Selisih Pembayaran</label>
                    <div class="col-lg-7">
                        <input type="text" id="selisih" name="selisih" value="{{number_format($inv->selisih_payment)}}"
                            class="form-control selisihs" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Potongan Biaya Bank</label>
                    <div class="col-lg-7">
                        <input type="text" id="pot_biaya_bank" value="{{$inv->pot_biaya_bank}}" name="pot_biaya_bank"
                            class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Potongan PPn</label>
                    <div class="col-lg-7">
                        <input type="text" id="potongan_ppn" value="{{$inv->potongan_ppn}}" name="potongan_ppn"
                            class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Potongan PPh</label>
                    <div class="col-lg-7">
                        <input type="text" id="potongan_pph" value="{{$inv->potongan_pph}}" name="potongan_pph"
                            class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Keterangan</label>
                    <div class="col-lg-7">
                        <div type="text" id="note" value="" name="note" class="form-control" readonly>{{$inv->note}}
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    {!! Form::button('Back', ['class' => 'btn btn-light btn-cancel',
                    'data-method' =>'finance/invoice', 'type' =>
                    'button','onclick'=>'cancel(this)']) !!}
                </div>
        </div>
    </div>
</div>
</div>
@endsection @section('script')
<script src="{{ asset('ctrl/finance/finance_invoice-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection