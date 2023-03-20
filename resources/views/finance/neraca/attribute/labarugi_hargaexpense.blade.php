<tr>
    <td class="font-weight-bold" colspan="2">Other Expenses</td>
</tr>
<tr onclick="showDetail(this)" data-code="{{$bank->code}}" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Adm Bank & Buku Cek/Giro</td>
    <td class="text-right">{{number_format($bank->total)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Pajak Jasa Giro</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Materai</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Selisi Pembayaran A/R</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Selisi Pembayaran A/R</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<!-- total  -->
<tr>
    <td class="font-weight-bold">Total Other Expenses</td>
    <td class="text-right text-danger font-weight-bold">{{number_format($total)}}</td>
</tr>
<tr>
    <td colspan="2"></td>
</tr>
<!-- end total -->
