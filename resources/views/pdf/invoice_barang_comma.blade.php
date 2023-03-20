<table id="customers" style="padding-top: 20px;">
    <tr class="text-center">
        <th>#</th>
        <th>Description</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Total</th>
    </tr>
    @php
    $subtotal_final = 0;
    $ongkirs = 0;
    $margin = 0; $j = 1;
    foreach ($product as $val){
    $ongkirs += $val->det_quo_qty*$val->det_quo_harga_ongkir;
    $check = getProductDetail($val->id_product)->name;
    @endphp
    <tr>
        <td class="text-center">{{$j++}}</td>
        <td class="text-left">{{$check}}</td>
        <td class="text-center">{{$val->det_quo_qty}}</td>
        <td class="text-right">{{number_format($val->det_quo_harga_order,2, ',', '.')}}</td>
        <td class="text-right">{{number_format($val->det_quo_qty*$val->det_quo_harga_order,2, ',', '.')}}</td>
    </tr>
    @php
    $subtotal_final += $val->det_quo_qty*$val->det_quo_harga_order;
    }
    $fun = "1.".GetPPN($time,$time);
    $subtotal = $subtotal_final+($ongkirs/$fun);
    $ppn = $subtotal*(GetPPN($time,$time)/100);
    $pph = $subtotal*(1.5/100);
    $totalf = $subtotal+$ppn;
    @endphp
    <tr>
        <td class="text-center">{{$j}}</td>
        <td class="text-left">Ongkos Kirim</td>
        <td class="text-center">1</td>
        <td class="text-right">{{number_format(($ongkirs/$fun),2, ',', '.')}}</td>
        <td class="text-right">{{number_format(($ongkirs/$fun),2, ',', '.')}}</td>
    </tr>
    <tfoot>
        <tr>
            <td colspan="4" class="font-weight-bold">SUBTOTAL</td>
            <td>{{number_format($subtotal,2, ',', '.')}}</td>
        </tr>
        <tr>
            <td colspan="4" class="font-weight-bold">PPN {!!GetPPN($time,$time)!!}%</td>
            <td>{{number_format($ppn,2, ',', '.')}}</td>
        </tr>
        @if($main->quo_type=='8')
        <tr>
            <td colspan="4" class="font-weight-bold">PPH 1.5%</td>
            <td>{{number_format($pph,2, ',', '.')}}</td>
        </tr>
        <tr>
            <td colspan="4" class="font-weight-bold">TOTAL</td>
            <td>{{number_format(round($totalf+$pph,-$digit),2, ',', '.')}}</td>
        </tr>
        @else
        <tr>
            <td colspan="4" class="font-weight-bold">TOTAL</td>
            <td>{{number_format(round($totalf,-$digit),2, ',', '.')}}</td>
        </tr>
        @endif
    </tfoot>
</table>
@if($kondisi=='partial' && ($jenis=='termin' or $jenis=='nominal'))
<table id="notes" style="width: 100%; padding-top: 20px;">
    <colgroup>
        <col span="1" style="width: 80%;">
        <col span="1" style="width: 20%;">]
    </colgroup>

    <!-- Put <thead>, <tbody>, and <tr>'s here! -->
    <thead>
        <tr>
            <th width="80%">Description</th>
            <th width="20%">Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            @php
            $percentage = $totalf*$harga/100;
            $tnominal = $jenis=='termin' ? $harga." %" : "";
            $nominal = $jenis=='termin' ? $percentage : $harga;
            @endphp
            <td class="text-center">Pembayaran partial dengan metode {{ucfirst($jenis.' '.$tnominal)}}</td>
            <td class="text-right">{{number_format(round($nominal,-$digit),0, ',', '.')}}</td>
        </tr>
    </tbody>
</table>
@endif