<!doctype html>
<html>

@include('pdf.style')

<body>

    @include('pdf.header')
    @include('pdf.footer')
    @php
    $shipping = getCabang('3');
    $vendor = getVendor($main->vendor[0]);
    @endphp
    <main>
        <table class="title">
            <tr>
                <td style="min-width: 70%;">
                    <span style="font-weight: bold; font-size: 0.8375rem;">{{$time}}</span>
                </td>
                <td width="30%">
                    <h4 class="font-weight-bold" style="color: #ed400c;">PURCHASE ORDER</h4>
                    <span style="font-weight: bold; font-size: 0.8375rem;">#DRAFT ({{$so}})</span><BR>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td width="50%">
                    <span style="font-weight: bold;">Alamat Pengiriman</span><br>
                    <span style="font-weight: bold; color:#555">PT MITRA ERA GLOBAL</span><br>
                    {!!$shipping->cabang_address!!}<br>
                    {!!$shipping->cabang_phone!!}
                </td>
                <td width="50%" class="text-left" style="padding-left: 30px;">
                    <span style="font-weight: bold;">{!!$vendor->vendor_name!!}</span><br>
                    {!!$vendor->address!!}<br>
                    @php
                    $phone = $vendor->phone == "N" || $vendor->phone==null ? "No Phone" : $vendor->phone;
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
                $i = $subtotal = 0;
                $j=1;
                $sku = count($main->idpro);
                for($i;$i<$sku;$i++){ $getdata[$i]=getPurchasingQuo($main->idpro[$i]);
                    @endphp
                    <tr>
                        <td class="text-center">{{$j++}}</td>
                        <td class="text-left">{!!getProductDetail($getdata[$i]->id_product)->name!!}</td>
                        <td class="text-center">{{$main->p_qty[$i]}}</td>
                        <td class="text-right">{{number_format($getdata[$i]->det_quo_harga_final)}}</td>
                        <td class="text-right">{{number_format($getdata[$i]->det_quo_harga_final*$main->p_qty[$i])}}</td>
                    </tr>
                    @php
                    $subtotal += $getdata[$i]->det_quo_harga_final*$main->p_qty[$i];
                    }
                    $time_inv = $invoice == null ? '0000-00-00':$invoice->tgl_invoice;
                    $times = $quo_mo->quo_type == 1 ? $quo_mo->created_at:$quo_mo->quo_order_at;
                    $vat = $subtotal*(GetPPN($time_inv,$times)/100);
                    $subtotal_include = $subtotal+$vat;
                    @endphp
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="font-weight-bold">Subtotal</td>
                    <td>{{number_format($subtotal)}}</td>
                </tr>
                <tr>
                    <td colspan="4" class="font-weight-bold">PPN {!!GetPPN($time_inv,$times)!!}%</td>
                    <td>{{number_format($vat)}}</td>
                </tr>
                <tr>
                    <td colspan="4" class="font-weight-bold">TOTAL</td>
                    <td>{{number_format($subtotal_include)}}</td>
                </tr>
            </tfoot>
        </table>
        <table>
            <tr>
                <td>
                    <span class="font-weight-bold">Catatan Saat Pengiriman Penagihan</span><br>
                    <span>Harap melampirkan copy PO,DO,Faktur Pajak & Invoice </span><br>
                </td>
            </tr>
        </table>

    </main>
</body>

</html>