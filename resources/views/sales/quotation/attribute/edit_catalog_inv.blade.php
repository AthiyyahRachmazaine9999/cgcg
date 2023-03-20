<form action="{{ route('edit_ups.update', $invoice->id )}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="id_quo" value="{{$invoice->id_quo}}" class="form-control" readonly>
    <input type="hidden" name="id_inv" value="{{$invoice->id}}" class="form-control" readonly>
    <input type="hidden" name="type_read" value="sales" class="form-control" readonly>
    <table class="table table-lg">
        <tbody>
            <tr>
                <td class="text-left font-weight-bold">No. NPWP</td>
                <td colspan="2"> <input type="text" id="npwp" class="form-control" name="npwp"
                        value="{{$invoice->npwp}}" placeholder="Masukkan Nomer NPWP">
                </td>
            </tr>
            <tr>
                <td class="text-left font-weight-bold">Nama NPWP</td>
                <td colspan="2"> <input type="text" id="npwp" class="form-control" name="npwp_nama"
                        value="{{$invoice->npwp_nama}}" placeholder="Masukkan Nama NPWP">
                </td>
            </tr>
            <tr>
                <td class="text-left font-weight-bold">No NTPN PPh</td>
                <td> <input type="text" id="no_ntpn_pph" class="form-control" name="no_ntpn_pph"
                        value="{{$invoice->no_ntpn_pph}}" placeholder="Nomer NTPN PPh">
                </td>
                <td>
                    <input type="number" id="potongan_ntpn_ppn" class="form-control" name="potongan_ntpn_pph"
                        value="{{$invoice->potongan_ntpn_pph}}" placeholder="Nominal NTPN PPh">
                </td>
            </tr>
            <tr>
                <td class="text-left font-weight-bold">No NTPN PPn</td>
                <td>
                    <input type="text" id="no_ntpn_ppn" class="form-control" name="no_ntpn_ppn"
                        value="{{$invoice->no_ntpn_ppn}}" placeholder="Nomer NTPN PPn">
                </td>
                <td>
                    <input type="number" id="potongan_ntpn_ppn" class="form-control" name="potongan_ntpn_ppn"
                        value="{{$invoice->potongan_ntpn_ppn}}" placeholder="Nominal NTPN PPn">
                </td>
            </tr>
            <tr>
                <td class="text-left font-weight-bold">Upload NTPN PPh</td>
                @if($invoice->file_ntpn_pph==null)
                <td colspan="2">
                    <input type="file" id="file" class="form-control" name="file_ntpn_pph">
                </td>
                @else
                <td><input type="file" id="file" class="form-control" name="file_ntpn_pph"></td>
                <td><a href="{{ asset($invoice->file_ntpn_pph) }}" class="btn btn-outline-primary btn-sm">SHOW</a></td>
                @endif
            </tr>
            <tr>
                <td class="text-left font-weight-bold">Upload NTPN PPn</td>
                @if($invoice->file_ntpn_ppn==null)
                <td colspan="2">
                    <input type="file" id="file" class="form-control" name="file_ntpn_ppn">
                </td>
                @else
                <td><input type="file" id="file" class="form-control" name="file_ntpn_ppn"></td>
                <td><a href="{{ asset($invoice->file_ntpn_ppn) }}" traget="_blank"
                        class="btn btn-outline-primary btn-sm">SHOW</a></td>
                @endif
            </tr>
            <tr>
                <td colspan="3">
                    {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                    'data-method' =>'sales/quotation/'.$invoice->id_quo, 'type' =>
                    'button','onclick'=>'cancel(this)']) !!}
                    <button type="submit" class="btn btn-primary">Update<i class="far fa-save ml-2"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</form>