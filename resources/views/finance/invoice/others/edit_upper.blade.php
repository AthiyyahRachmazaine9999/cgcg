<form action="{{ route('edit_ups.update', $invoice_id->id )}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="id_quo" value="{{$invoice_id->id_quo}}" class="form-control" readonly>
    <input type="hidden" name="id_inv" value="{{$invoice_id->id}}" class="form-control" readonly>

    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>No. NPWP</label>
        <div class="col-lg-7">
            <input type="text" id="npwp" class="form-control" name="npwp" value="{{$invoice_id->npwp}}"
                placeholder="Masukkan Nomer NPWP">
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Nama NPWP</label>
        <div class="col-lg-7">
            <input type="text" id="npwp" class="form-control" name="npwp_nama" value="{{$invoice_id->npwp_nama}}"
                placeholder="Masukkan Nama NPWP">
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>No. NTPN PPh</label>
        <div class="col-lg-3">
            <input type="text" id="no_ntpn_pph" class="form-control" name="no_ntpn_pph"
                value="{{$invoice_id->no_ntpn_pph}}" placeholder="Nomer NTPN PPh">
        </div>
        <div class="col-lg-3">
            <input type="number" id="potongan_ntpn_ppn" class="form-control" name="potongan_ntpn_pph"
                value="{{$invoice_id->potongan_ntpn_pph}}" placeholder="Nominal NTPN PPh">
        </div>
        <div class="col-lg-3">
            <input type="file" id="file" class="form-control" name="file_ntpn_pph">
        </div>
    </div>
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>No. NTPN PPn</label>
        <div class="col-lg-3">
            <input type="text" id="no_ntpn_ppn" class="form-control" name="no_ntpn_ppn"
                value="{{$invoice_id->no_ntpn_ppn}}" placeholder="Nomer NTPN PPn">
        </div>
        <div class="col-lg-3">
            <input type="number" id="potongan_ntpn_ppn" class="form-control" name="potongan_ntpn_ppn"
                value="{{$invoice_id->potongan_ntpn_ppn}}" placeholder="Nominal NTPN PPn">
        </div>
        <div class="col-lg-3">
            <input type="file" id="file" class="form-control" name="file_ntpn_ppn">
        </div>
    </div>
    <div class="text-left">
        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
        'data-method' =>'finance/invoice/edit_invoice/'.$invoice_id->id, 'type' =>
        'button','onclick'=>'cancel(this)']) !!}
        <button type="submit" class="btn btn-primary">Update<i class="far fa-save ml-2"></i>
        </button>
    </div>
</form>