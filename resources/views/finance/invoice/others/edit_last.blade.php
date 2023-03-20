<form action="{{ route('edit_last.update', $invoice_id->id )}}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="id_quo" value="{{$invoice_id->id_quo}}" class="form-control" readonly>
    <input type="hidden" name="id_inv" value="{{$invoice_id->id}}" class="form-control" readonly>
    <div class="table-responsive">
        <table class="table table-lg" id="table_last">
            <thead class="">
                <tr class="">
                    <th class="text-left">Biaya Lainnya</th>
                    <th class="text-center">Nominal Biaya</th>
                </tr>
            </thead>
            @if($coth!=0)
            <tbody>
                @php foreach ($inv_oth as $oth) { @endphp
                <tr class="rows_last_{{$oth->id}}">
                    <td>
                        <input type="hidden" name="id_oth[]" value="{{$oth->id}}" class="form-control" readonly>
                        <input type="text" step="any" id="tujuan" name="des_potongan[]" value="{{$oth->des_potongan}}"
                            class="form-control" placeholder="Masukkan Tujuan biaya">
                    </td>
                    <td>
                        <input type="number" step="any" id="nilai_potongan[]" value="{{$oth->nilai_potongan}}"
                            name="nilai_potongan[]" class="form-control" placeholder="Masukkan Nominal">
                    </td>
                    <td class="text-center" style="border: none">
                        <button type="button" onClick="addpotongan(this)" data-type="hapus_datas" data-id="{{$oth->id}}"
                            class="btn bg-warning-400 btn-icon rounded-round legitRipple">
                            <b><i class="fas fa-trash"></i></b></button>
                    </td>
                </tr>
                @php } @endphp
                <tr class="add_forms_edit"></tr>
            </tbody>
            @endif
        </table>
        <div class="text-left" style="padding-left:20px;">
            <button type="button" onClick="addpotongan(this)" data-type="add_forms" data-id_inv="{{$invoice_id->id}}"
                class="btn bg-primary-400 btn-icon rounded-round legitRipple">
                <b><i class="fas fa-plus"></i></b></button>
        </div>
    </div>
    <br>
    <br>
    <div class="text-right" style="padding-right:20px;">
        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
        'data-method' =>'finance/invoice/edit_invoice/'.$invoice_id->id, 'type' =>
        'button','onclick'=>'cancel(this)']) !!}
        <button type="submit" class="btn btn-primary">Update<i class="far fa-save ml-2"></i>
        </button>
    </div>
    <br>
</form>
<script src="{{ asset('ctrl/finance/finance_invoice-form.js?v=').rand() }}" type="text/javascript"></script>