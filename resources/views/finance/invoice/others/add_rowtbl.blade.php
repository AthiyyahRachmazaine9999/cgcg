<tr class="rows_{{$n_equ}}">
    <td> <input name="date_payment_add[]" type="text" id="dates_pay" class="form-control tgl_payment dates"
            placeholder="Masukkan Tanggal Pembayaran">
    </td>
    <td> <input name="payment_amounts_add[]" type="number" id="dates_pay" class="form-control amount"
            placeholder="Masukkan Nilai Pembayaran">
    </td>
    <td> <input name="files_add[]" type="file" id="files" class="form-control amount">
    </td>
    <td class="text-center">
        <button type="button" onClick="tambah_tbl({{$n_equ}})" data-type="hapus_detail" data-equ="{{$n_equ}}"
            class="btn bg-warning-400 btn-icon rounded-round legitRipple text-center">
            <b><i class="fas fa-trash"></i></b></button>
    </td>
</tr>
<script src="{{ asset('ctrl/finance/finance_invoice-form.js?v=').rand() }}" type="text/javascript"></script>