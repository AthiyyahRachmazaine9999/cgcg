<tr>
    <td class="font-weight-bold" colspan="2">HARGA POKOK PENJUALAN</td>
</tr>
<tr>
    <td>Persediaan Awal Tahun</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Pembelian Lokal</td>
    <td class="text-right">{{number_format($pembelian)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Persediaan Akhir Tahun</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Pembelian Lokal Non PPN</td>
    <td class="text-right">{{number_format($nonppn)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="{{$sosialisasi->code}}" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Instansi/ Sosialisasi</td>
    <td class="text-right">{{number_format($sosialisasi->total)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Transport Project</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Packing Barang</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<!-- total  -->
<tr>
    <td class="font-weight-bold">TOTAL HARGA POKOK PENJUALAN</td>
    <td class="text-right text-danger font-weight-bold">{{number_format($total)}}</td>
</tr>
<tr>
    <td colspan="2"></td>
</tr>
<input type="hidden" id="totalpokok" value="{{$total}}">
<!-- end total -->