<tr class="rows_{{$n_equ}}">
    <td>
        <input type="text" step="any" id="tujuan" name="des_potongan[]" class="form-control"
            placeholder="Masukkan Tujuan biaya">
    </td>
    <td>
        <input type="number" step="any" id="nilai_potongan[]" name="nilai_potongan[]" class="form-control"
            placeholder="Masukkan Nilai">
    </td>
    <td class="text-center" style="border: none">
        <button type="button" onClick="addpotongan(this)" data-count="{{$n_equ}}" data-type="delete_forms"
            class="btn bg-warning-400 btn-icon rounded-round legitRipple">
            <b><i class="fas fa-trash"></i></b></button>
    </td>
</tr>
<script src="{{ asset('ctrl/finance/finance_invoice-form.js?v=').rand() }}" type="text/javascript">
</script>