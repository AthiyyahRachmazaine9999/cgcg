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
                    <h4 class="font-weight-bold" style="color: #ed400c;">
                        @if ($main->quo_type=='1')
                        SALES QUOTATION
                        @else
                        ORDER # SO{{$title}}
                        @endif
                    </h4>
                    <span style="font-weight: bold; font-size: 0.8375rem;">SQ/MEG/{{$title}}</span>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td width="50%">
                    <span style="font-weight: bold;">Alamat Pengiriman</span><br>
                    <span style="font-weight: bold; color:#555">{{$cust->company}}</span><br>
                    {{$cust->address}}<br>
                    @php
                    $phone = $cust->phone == "N" || $cust->phone==null ? "No Phone" : $cust->phone;
                    echo $phone;
                    @endphp
                </td>
                <td width="50%" class="text-left" style="padding-left: 30px;">
                    <span style="font-weight: bold;">Alamat Invoicing</span><br>
                    <span style="font-weight: bold;">{{$cust->company}}</span><br>
                    {{$cust->address}}<br>
                    @php
                    $phone = $cust->phone == "N" || $cust->phone==null ? "No Phone" : $cust->phone;
                    echo $phone;
                    @endphp
                </td>
            </tr>
        </table>
        <table style="padding-top: 50px;">
            <tr>
                <td style="min-width: 70%;">
                    <span style="font-weight: bold;">Nama Paket / Pekerjaan</span><br>
                    {{$main->quo_name}}<br>
                    {{$main->quo_no}}
                </td>
                <td width="50%" class="text-left">
                    <span style="font-weight: bold;">Nama Sales</span><br>
                    {!!emp_name($main->id_sales)!!}<br>
                </td>
            </tr>
        </table>
        <table id="customers" style="padding-top: 50px;">
            @php
            $ongkirs = 0;
            foreach ($product as $vals){
            $ongkirs += $vals->det_quo_qty*$vals->det_quo_harga_ongkir;
            }
            @endphp
            <tr class="text-center">
                <th>#</th>
                <th>Deskripsi</th>
                <th>Qty</th>
                <th>Stock</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
            @php
            $subtotal_final = 0;
            $margin = 0; $j=1;
            foreach ($product as $val){
            $check = getProductDetail($val->id_product)->name;
            @endphp
            <tr>
                <td class="text-center">{{$j++}}</td>
                <td class="text-left">{{$check}}</td>
                <td class="text-center">{{$val->det_quo_qty}}</td>
                <td class="text-center">{{ucfirst($val->det_quo_status_vendor)}}</td>
                <td class="text-right">{{number_format($val->det_quo_harga_order)}}</td>
                <td class="text-right">{{number_format($val->det_quo_qty*$val->det_quo_harga_order)}}</td>
            </tr>
            @php
            $subtotal_final += $val->det_quo_qty*$val->det_quo_harga_order;
            } @endphp
            <tfoot>
                @php 
                $ppn = $subtotal_final*(GetPPN($main->created_at,$main->created_at)/100);
                @endphp
                <tr>
                    <td colspan="5" class="font-weight-bold">Subtotal</td>
                    <td>{{number_format($subtotal_final)}}</td>
                </tr>
                <tr>
                    <td colspan="5" class="font-weight-bold">PPN {!!GetPPN($main->created_at,$main->created_at)!!}%</td>
                    <td>{{number_format($ppn)}}</td>
                </tr>
                <tr>
                    <td colspan="5" class="font-weight-bold">TOTAL</td>
                    <td>{{number_format($subtotal_final+$ppn)}}</td>
                </tr>
                @php if($ongkirs>0){ @endphp
                <tr>
                    <td colspan="5" class="font-weight-bold">ONGKIR</td>
                    <td>{{number_format($ongkirs)}}</td>
                </tr>
                <tr>
                    <td colspan="5" class="font-weight-bold">TOTAL KESELURUHAN</td>
                    <td>{{number_format($ongkirs+$subtotal_final+$subtotal_final/10)}}</td>
                </tr>
                @php } @endphp
            </tfoot>
        </table>
        @if($main->note_salesorder!=null)
        <br>
        <p style="line-height:-2px;"><b><em>Note :</em></b></p><br>
        <span>{!!$main->note_salesorder!!}</span>
        @endif
    </main>
</body>

</html>