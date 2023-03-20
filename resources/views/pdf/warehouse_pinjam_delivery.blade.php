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
                    <span style="font-weight: bold; font-size: 0.8375rem;">{{\Carbon\Carbon::parse($main->tanggal_pinjam)->format('d F Y')}}</span>
                </td>
                <td width="30%">
                    <h4 class="font-weight-bold" style="color: #ed400c;">DELIVERY ORDER</h4>
                    <span style="font-weight: bold; font-size: 0.8375rem;">
                        {{$no_do}}
                    </span><br>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                @php
                $customer = getCustomer($main->id_customer);
                if($main->alamat == null){
                $telp  = $customer->phone;
                $cname = $customer->company;
                $caddr = $customer->address;
                $phone = $telp == 'N' || $telp == null ? 'No Phone' : $telp;
                } else{
                $telp  = $customer->phone;
                $cname = $customer->company;
                $caddr = $main->alamat;
                $phone = $telp == null ? 'No Phone' : $telp;
                }
                @endphp
                <td width="50%" class="text-left">
                    <span style="font-weight: bold;">Alamat Pengiriman</span><br>
                    <span style="font-weight: bold;">{{$main->nama_peminjam}} <br>{{$cname}}</span><br>
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
            <tr>
                <td class="text-center">1</td>
                <td class="text-left">{!!getProductDetail($main->sku)->name!!}</td>
                <td class="text-center">{{$main->qty_pinjam}}</td>
            </tr>
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