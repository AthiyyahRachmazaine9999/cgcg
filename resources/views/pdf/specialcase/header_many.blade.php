@php
$maina = getAddress('1');
$warehouse = getAddress('2');
@endphp
<header>
    <table>
        <tr>
            <td colspan="6">
                <table>
                    <tr>
                        <td class="title" style="padding-top: 45px;">
                            <img src="storage/kop_meg.png" style="width:100%; max-width:110px;">
                        </td>
                        <td class="text-left" style="padding-left: 10px;">
                            <h6 class="font-weight-bold" style="margin-bottom: -1px;">PT MITRA ERA GLOBAL</h6>
                            <strong style="margin-top: -50px;">{!!$maina->place!!}</strong><br>
                            {!!$maina->address!!}<br style="margin-top: 50px;">
                            <hr class="solid" style="margin-bottom: -1px;">
                            <strong>{!!$warehouse->place!!}</strong><br>
                            {!!$warehouse->address!!}<br>
                            {!!$maina->telp!!}<br>
                        </td>
                        <td class="title" style="padding-top: 35px;padding-left: 70px;">
                            <img src="storage/maleserkanan.png" style="width:100%; max-width:175px;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <hr class="solid">

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
                <th>#</th>
                <th>Deskripsi</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
</header>