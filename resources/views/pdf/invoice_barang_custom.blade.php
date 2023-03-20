<table id="customers" style="padding-top: 20px;">
    <tr class="text-center">
        <th>#</th>
        <th>Deskripsi</th>
        <th>Qty</th>
        <th>Harga</th>
        <th>Total</th>
    </tr>
    @php
    $subtotal_final = 0;
    $ongkirs = 0;
    $margin = 0; $j = 1;
    foreach ($product as $val){
    $ongkirs += $val->qty*$val->det_quo_harga_ongkir;
    $check = getProductDetail($val->sku)->name;
    @endphp
    <tr>
        <td class="text-center">{{$j++}}</td>
        <td class="text-left">{{$check}}</td>
        <td class="text-center">{{$val->qty}}</td>
        <td class="text-right">{{number_format($val->price,2, ',', '.')}}</td>
        <td class="text-right">{{number_format($val->qty*$val->price)}}</td>
    </tr>
    @php
    $subtotal_final += $val->qty*$val->price;
    }
    $subtotal = $subtotal_final+($ongkirs/1.1);
    $ppn = $subtotal*(GetPPN($time,$time)/100);
    $totalf = $subtotal+$ppn;
    @endphp
    <tr>
        <td class="text-center">{{$j}}</td>
        <td class="text-left">Ongkos Kirim</td>
        <td class="text-center">1</td>
        <td class="text-right">{{number_format($ongkirs/1.1)}}</td>
        <td class="text-right">{{number_format($ongkirs/1.1)}}</td>
    </tr>
    <tfoot>
        <tr>
            <td colspan="4" class="font-weight-bold">SUBTOTAL</td>
            <td>{{number_format($subtotal,0, ',', '.')}}</td>
        </tr>
        <tr>
            <td colspan="4" class="font-weight-bold">PPN {!!GetPPN($time,$time)!!}%</td>
            <td>{{number_format($ppn,0, ',', '.')}}</td>
        </tr>
        <tr>
            <td colspan="4" class="font-weight-bold">TOTAL</td>
            <td>{{number_format(round($totalf,-$digit),0, ',', '.')}}</td>
        </tr>
    </tfoot>
</table>