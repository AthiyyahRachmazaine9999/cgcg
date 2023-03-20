<!doctype html>
<html>

@include('pdf.style')

<body>

    @include('pdf.header')
    @include('pdf.footer')
    <main>
        <table class="title">
            <tr>
                <td style="min-width: 70%;">
                </td>
                <td width="30%">
                    <span class="font-weight-bold" style="color: #ed400c; font-size:24px">INVOICE</span><br>
                    <span class="font-weight-bold" style="color: #ed400c;">{{$number}}</span><br>
                    {{$time}}
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td width="50%" class="text-left" style="padding-left: 30px;">
                    <span style="font-weight: bold;">Alamat Invoicing</span><br>
                    <span style="font-weight: bold;">{{$cust->company}}</span><br>
                    {{$cust->address}}<br>
                    @php
                    $phone = $cust->phone == "N" || $cust->phone==null ? "No Phone" : $cust->phone;
                    echo $phone;
                    @endphp
                </td>
                <td width="50%">
                    <span style="font-weight: bold;">Alamat Pengiriman</span><br>
                    <span style="font-weight: bold; color:#555">{{$cust->company}}</span><br>
                    {{$cust->address}}<br>
                    @php
                    $phone = $cust->phone == "N" || $cust->phone==null ? "No Phone" : $cust->phone;
                    echo $phone;
                    @endphp
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td width="50%" class="text-left" style="padding-left: 30px;">
                    <span style="font-weight: bold;">Catatan</span><br>
                    <span style="padding-top: 50px;">{{$main->quo_name}}</span>
                </td>
            </tr>
        </table>
        <table id="look" style="width: 100%; padding-top: 50px;">
            <colgroup>
                <col span="1" style="width: 25%;">
                <col span="1" style="width: 25%;">
                <col span="1" style="width: 25%;">
                <col span="1" style="width: 25%;">
            </colgroup>



            <!-- Put <thead>, <tbody>, and <tr>'s here! -->
            <thead>
                <tr>
                    <th width="25%">Sales</th>
                    <th width="25%">SO. Number</th>
                    <th width="50%">PO Number</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">{!!emp_name($main->id_sales)!!}</td>
                    <td class="text-center">SO{{$title}}</td>
                    <td class="text-center">{{$main->quo_no}}</td>
                </tr>
            </tbody>
        </table>
        <table id="customers" style="padding-top: 50px;">
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
            $ongkirs += $val->det_quo_qty*$val->det_quo_harga_ongkir;
            $check = getProductDetail($val->id_product)->name;
            @endphp
            <tr>
                <td class="text-center">{{$j++}}</td>
                <td class="text-left">{{$check}}</td>
                <td class="text-center">{{$val->det_quo_qty}}</td>
                <td class="text-right">{{number_format($val->det_quo_harga_order)}}</td>
                <td class="text-right">{{number_format($val->det_quo_qty*$val->det_quo_harga_order)}}</td>
            </tr>
            @php
            $subtotal_final += $val->det_quo_qty*$val->det_quo_harga_order;
            }
            $subtotal = $subtotal_final+($ongkirs/1.1);
            $pph      = $subtotal*2/100;
            $ppn      = $subtotal/10;
            $totalf   = $subtotal+$ppn+$pph;
            @endphp
            <tfoot>
                <tr>
                    <td colspan="4" class="font-weight-bold">SUBTOTAL</td>
                    <td>{{number_format($subtotal)}}</td>
                </tr>
                <tr>
                    <td colspan="4" class="font-weight-bold">Pph 23 (2%)</td>
                    <td>{{number_format($pph)}}</td>
                </tr>
                <tr>
                    <td colspan="4" class="font-weight-bold">PPN 10%</td>
                    <td>{{number_format($ppn)}}</td>
                </tr>
                <tr>
                    <td colspan="4" class="font-weight-bold">TOTAL</td>
                    <td>{{number_format(round($totalf,-$digit))}}</td>
                </tr>
            </tfoot>
        </table>


        <table id="what" style="width: 100%;">
            <colgroup>
                <col span="1" style="width: 50%;">
                <col span="1" style="width: 50%;">
            </colgroup>
            <tbody>
                <tr>
                    <td class="text-left" style="padding-left:30px;">
                        <span style="font-weight: bold;">Payment Info</span><br>
                        <span style="font-weight: bold;">PT MITRA ERA GLOBAL</span><br>
                        <span style="font-weight: bold;">Mandiri 119 00 7777 2018</span><br>
                        <span style="font-weight: bold;">Cabang Mangga Dua</span><br>
                    </td>
                    <td class="text-center">
                        <span style="font-weight: bold;">Jakarta, {{$time}}</span><br><br><br><br><br><br>
                        <span style="margin-top: 100px;">( {!!getEmp($inv->sign_by)->emp_name!!} )</span>
                    </td>
                </tr>
            </tbody>
        </table>



    </main>
</body>

</html>