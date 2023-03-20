@php
$equ = $n_equ++;
@endphp
<tr class="row_tbl_{{$equ}}">
    <td colspan="2" class="text-center">
        {!! Form::select('id_product_add[]',$product,'',['id' => 'sel_pro', 'class' => 'form-control
        form-control-select2 sel_pro','placeholder'=>'Masukkan Product']) !!}
    </td>
    <td class="text-center">
        <input name="qty_update_add[]" type="number" id="up_qty_row" class="form-control update_qty"
            placeholder="Masukkan Quantity Kirim">
    </td>
    <td>
        <input name="keterangan_add[]" type="text" id="keterangan" class="form-control up_keterangans"
            placeholder="Note">
    </td>
    <td>
        <button type="button" id="btn_tambahbarang" onclick="add_barang(this)" data-equ="{{$equ}}" data-type="hapus_row"
            class="btn bg-danger-400 btn-icon rounded-round legitRipple"><i class="fas fa-trash"></i></button>
    </td>
</tr>