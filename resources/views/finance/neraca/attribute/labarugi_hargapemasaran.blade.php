<tr>
    <td class="font-weight-bold" colspan="2">BIAYA PEMASARAN</td>
</tr>

<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Iklan</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Gathering / Pameran</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="{{$entertain->code}}" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Entertain & Meeting Meal</td>
    <td class="text-right">{{number_format($entertain->total)}}</td>
</tr>
<!-- total  -->
<tr>
    <td class="font-weight-bold">TOTAL BIAYA PEMASARAN</td>
    <td class="text-right text-danger font-weight-bold">{{number_format($total)}}</td>
</tr>
<tr>
    <td colspan="2"></td>
</tr>
<!-- end total -->