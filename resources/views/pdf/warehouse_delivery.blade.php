<!doctype html>
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
                    <span style="font-weight: bold; font-size: 0.8375rem;">{{\Carbon\Carbon::parse($time)->format('d F Y')}}</span>
                </td>
                <td width="30%">
                    <h4 class="font-weight-bold" style="color: #ed400c;">DELIVERY ORDER</h4>
                    <span style="font-weight: bold; font-size: 0.8375rem;">
                        @if($ex_resi==null)
                        WH/OUT/{{\Carbon\Carbon::now()->format('y')}}/{{sprintf("%06d", $main->id)}}
                        @else
                        WH/OUT/{{\Carbon\Carbon::now()->format('y')}}/{{sprintf("%06d", $main->id)}}/{{$ex_resi}}
                        @endif

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
                $cname = $add->company;
                $caddr = $add->address;
                }else{
                $cname = $add->name;
                $caddr = $add->address;
                }
                @endphp
                <td width="50%" class="text-left">
                    <span style="font-weight: bold;">Alamat Pengiriman</span><br>
                    <span style="font-weight: bold;">{{$namapic}} <br>{{$cname}}</span><br>
                    {{$caddr}}<br>
                    @php
                    $phone = $add->phone == "N" || $add->phone==null ? "No Phone" : $add->phone;
                    echo $phone;
                    @endphp
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
            $check = getProductQuo($val->id_product)->id_product
            @endphp
            <tr>
                <td class="text-center">{{$j++}}</td>
                <td class="text-left">{!!getProductDetail($check)->name!!}</td>
                <td class="text-center">{{$val->qty_kirim}}</td>
            </tr>
            @php
            $subtotal_final += $val->qty*$val->price;
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
</body>

</html>