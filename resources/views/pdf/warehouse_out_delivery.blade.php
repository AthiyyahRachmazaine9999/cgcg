<!doctype html>
<html>

@include('pdf.style')

<body>

    @include('pdf.header')
    @include('pdf.footer')
    @php
    $shipping = getCabang('3');
    @endphp
    @if($main->status_kirim == "rekap")
    <main>
        <table class="title">
            <tr>
                <td style="min-width: 70%;">
                    <span
                        style="font-weight: bold; font-size: 0.8375rem;">{{\Carbon\Carbon::parse($main->tgl_kirim)->format('d F Y')}}</span>
                </td>
                <td width="30%">
                    <h4 class="font-weight-bold" style="color: #ed400c;">DELIVERY ORDER</h4>
                    <span style="font-weight: bold; font-size: 0.8375rem;">
                        {{$look->no_do}}
                    </span><br>
                    <span style="font-weight: bold; font-size: 0.8375rem;">
                        @php 
                        $quos = getQuo($main->id_quo);
                        $condi = $quos->quo_no==null ? $quos->quo_name :
                        $quos->quo_no; 
                        @endphp 
                        {!!$condi!!}
                    </span><BR>
                    <span style="font-weight: bold; font-size: 0.8375rem;">
                        SO{!!sprintf("%06d", $main->id_quo)!!}
                    </span>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                @php
                if($type == 'utama'){
                $telp = getCustomer($add)->phone;
                $cname = getCustomer($add)->company;
                $caddr = getCustomer($add)->address;
                $phone = $telp == 'N' || $telp == null ? 'No Phone' : $telp;
                }else{
                $telp = WarehouseAddress($add)->phone;
                $cname = WarehouseAddress($add)->name;
                $caddr = WarehouseAddress($add)->address;
                $phone = $telp == null ? 'No Phone' : $telp;
                }
                @endphp
                <td width="50%" class="text-left">
                    <span style="font-weight: bold;">Alamat Pengiriman</span><br>
                    <span style="font-weight: bold;">{{$main->nama_penerima}} <br>{{$cname}}</span><br>
                    {{$caddr}}<br>
                    {{$phone}}
                </td>
            </tr>
        </table>
        <table id="customers" style="padding-top: 50px;">
            <tr class="text-center">
                <th>#</th>
                <th>Deskripsi</th>
                <th>Qty</th>
            </tr>
            @php
            $subtotal_final = 0;
            $margin = 0; $j=1;
            foreach ($product as $val){
            @endphp
            <tr>
                <td class="text-center">{{$j++}}</td>
                <td class="text-left">{!!getProductDetail($val->sku)->name!!}</td>
                <td class="text-center">{{$val->count}}</td>
            </tr>
            @php
            } @endphp
        </table>
        <table class="title" style="padding-top: 120px;">
            <tr>

                <td width="35%">
                    <span style="font-weight: bold; font-size: 0.9375rem;">Approved By</span>
                </td>

                <td width="35%">
                    <span style="font-weight: bold; font-size: 0.9375rem;">Deliver By</span>
                </td>
                <td width="30%">
                    <span style="font-weight: bold; font-size: 0.9375rem;">Received By</span>
                </td>
            </tr>
        </table>

        <table class="title" style="padding-top: 120px;">
            <tr>
                <td width="35%">
                    <span>(..........................)</span><BR>
                </td>
                <td width="35%">
                    <span>(..........................)</span><BR>
                </td>
                <td width="30%">
                    <span>(..........................)</span><BR>
                </td>
            </tr>
        </table>
    </main>
    @else
    <main>
        <table class="title">
            <tr>
                <td style="min-width: 70%;">
                    <span
                        style="font-weight: bold; font-size: 0.8375rem;">{{\Carbon\Carbon::parse($time)->format('d F Y')}}</span>
                </td>
                <td width="30%">
                    <h4 class="font-weight-bold" style="color: #ed400c;">DELIVERY ORDER</h4>
                    <span style="font-weight: bold; font-size: 0.8375rem;">
                        {{$no_do}}
                    </span><br>
                    <span style="font-weight: bold; font-size: 0.8375rem;">
                        @php getQuo($main->id_quo)->quo_no==null ? getQuo($main->id_quo)->quo_name :
                        getQuo($main->id_quo)->quo_no; @endphp {!!getQuo($main->id_quo)->quo_no!!}
                    </span><BR>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                @php
                if($type == 'utama'){
                $telp = getCustomer($add)->phone;
                $cname = getCustomer($add)->company;
                $caddr = getCustomer($add)->address;
                $phone = $telp == 'N' || $telp == null ? 'No Phone' : $telp;
                }else{
                $telp = WarehouseAddress($add)->phone;
                $cname = WarehouseAddress($add)->name;
                $caddr = WarehouseAddress($add)->address;
                $phone = $telp == null ? 'No Phone' : $telp;
                }
                @endphp
                <td width="50%" class="text-left">
                    <span style="font-weight: bold;">Alamat Pengiriman</span><br>
                    <span style="font-weight: bold;">{{$namapic}} <br>{{$cname}}</span><br>
                    {{$caddr}}<br>
                    {{$phone}}
                </td>
            </tr>
        </table>
        <table id="customers" style="padding-top: 50px;">
            <tr class="text-center">
                <th>#</th>
                <th>Deskripsi</th>
                <th>Qty</th>
            </tr>
            @php
            $subtotal_final = 0;
            $margin = 0; $j=1;
            foreach ($product as $val => $l){
            @endphp
            <tr>
                <td class="text-center">{{$j++}}</td>
                <td class="text-left">{!!getProductDetail($main->id_product[$val])->name!!}</td>
                <td class="text-center">{{$main->qty_kirim[$val]}}</td>
            </tr>
            @php
            } @endphp
        </table>
        <table class="title" style="padding-top: 120px;">
            <tr>

                <td width="35%">
                    <span style="font-weight: bold; font-size: 0.9375rem;">Approved By</span>
                </td>

                <td width="35%">
                    <span style="font-weight: bold; font-size: 0.9375rem;">Deliver By</span>
                </td>
                <td width="30%">
                    <span style="font-weight: bold; font-size: 0.9375rem;">Received By</span>
                </td>
            </tr>
        </table>

        <table class="title" style="padding-top: 120px;">
            <tr>
                <td width="35%">
                    <span>(..........................)</span><BR>
                </td>
                <td width="35%">
                    <span>(..........................)</span><BR>
                </td>
                <td width="30%">
                    <span>(..........................)</span><BR>
                </td>
            </tr>
        </table>
    </main>
    @endif
</body>

</html>