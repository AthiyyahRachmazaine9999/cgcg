<!doctype html>
<html>

<html>

@include('pdf.style')

<body>

    @include('pdf.header')
    @include('pdf.footer')
    @php
    $shipping = getCabang('3');
    @endphp
    <main>
        <table class="title">
            <tr>
                <td style="min-width: 70%;">
                    <span style="font-weight: bold; font-size: 0.8375rem;">
                        @php
                        if($main->order_at==null){
                        if($main->kirim_time==null){
                        echo $time;
                        }else{
                        echo date('d F Y', strtotime($main->kirim_time));
                        }
                        }else{
                        echo date('d F Y', strtotime($main->order_at));
                        }
                        @endphp
                    </span>
                </td>
                <td width="30%">
                    <h4 class="font-weight-bold" style="color: #ed400c;">PURCHASE ORDER</h4>
                    <span style="font-weight: bold; font-size: 0.8375rem;">{{$main->po_number}}</span><BR>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                @if($altaddress!=null)
                <td width="50%">
                    <span style="font-weight: bold;">Alamat Pengiriman</span><br>
                    <span style="font-weight: bold; color:#555">PT MITRA ERA GLOBAL</span>
                    <br>{{$altaddress->name}}<br>
                    {{$altaddress->address}}<br>
                    {!!$shipping->cabang_phone!!}
                </td>
                @else
                <td width="50%">
                    <span style="font-weight: bold;">Alamat Pengiriman</span><br>
                    <span style="font-weight: bold; color:#555">PT MITRA ERA GLOBAL</span><br>
                    {!!$shipping->cabang_address!!}<br>
                    {!!$shipping->cabang_phone!!}
                </td>
                @endif
                <td width="50%" class="text-left" style="padding-left: 30px;">
                    <span style="font-weight: bold;">{{$vend->vendor_name}}</span><br>
                    {{$vend->address}}<br>
                    @php
                    $phone = $vend->phone == "N" || $vend->phone==null ? "No Phone" : $vend->phone;
                    echo $phone;
                    @endphp
                </td>
            </tr>
        </table>
        <table id="customers" style="padding-top: 50px;">
            <thead>
                <tr class="text-center">
                <th width="10%">#</th>
                    <th width="46%">Deskripsi</th>
                    <th width="10%">Qty</th>
                    <th width="17%">Harga</th>
                    <th width="17%">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                $subtotal_final = 0;
                $margin = 0; $j=1;
                foreach ($product as $val){
                if (!isset($result[$val->sku]))
                $result[$val->sku] = $val;
                else
                $result[$val->sku]['qty'] += $val['qty'];
                }
                $result = array_values($result);
                foreach ($result as $val){
                @endphp
                <tr>
                    <td class="text-center">{{$j++}}</td>
                    @php
                    if ($main->type=="stock purchase") { @endphp
                    <td>
                        {!!getProductPo($val->id_product)->name!!}
                    </td>
                    @php } else {
                    @endphp
                    <td>
                        {!!getProductDetail($val->sku)->name!!}
                    </td>
                    @php } @endphp
                    <td class="text-center">{{$val->qty}}</td>
                    <td class="text-right">{{number_format($val->price)}}</td>
                    <td class="text-right">{{number_format($val->qty*$val->price)}}</td>
                </tr>
                @php
                $subtotal_final += $val->qty*$val->price;
                } @endphp
            </tbody>
            <tfoot>
                @php
                $vat = $subtotal_final*(GetPPN($main->created_at,$main->created_at)/100);
                if($main->isppn=="yes") { @endphp
                <tr>
                    <td colspan="4" class="font-weight-bold">Subtotal</td>
                    <td>{{number_format($subtotal_final)}}</td>
                </tr>
                <tr>
                    <td colspan="4" class="font-weight-bold">PPN {!!GetPPN($main->created_at,$main->created_at)!!}%</td>
                    <td>{{number_format($vat)}}</td>
                </tr>
                @php
                $total_akhir = $subtotal_final+$vat;
                } else { $total_akhir = $subtotal_final; }@endphp
                <tr>
                    <td colspan="4" class="font-weight-bold">TOTAL</td>
                    <td>{{number_format($total_akhir)}}</td>
                </tr>
            </tfoot>
        </table>
        <table>
            @php if($main->note_order!==null){ @endphp
            <tr>
                <i><b>Catatan :</b> {!!$main->note_order!!}</i>
            </tr>
            @php } @endphp
            <tr>
                <td>
                    <span class="font-weight-bold">Catatan Saat Pengiriman Penagihan</span><br>
                    <span>Harap melampirkan copy PO,DO,Faktur Pajak & Invoice </span><br>
                </td>
            </tr>
        </table>

        @if($main->status<>'draft')
            <table class="title">
                <tr>
                    <td style="min-width: 70%;">
                    </td>
                    <td width="30%">
                        <span style="font-weight: bold; font-size: 0.9375rem;">Approved By</span>
                    </td>
                </tr>
            </table>

            <table class="title">
                <tr>
                    <td style="min-width: 70%;">
                    </td>
                    <td width="30%">
                        @php
                        $approveby = getUserEmp($main->status_by)->emp_name;
                        $text = "Yes,It's Valid ".$main->po_number." Approve By ".$approveby;
                        @endphp
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text)) !!} ">
                    </td>
                </tr>
            </table>

            <table class="title" style="padding-top: 20px;">
                <tr>
                    <td style="min-width: 70%;">
                    </td>
                    <td width="30%">
                        <span>{!!$approveby!!}</span><BR>
                    </td>
                </tr>
            </table>
            @endif
    </main>
</body>

</html>