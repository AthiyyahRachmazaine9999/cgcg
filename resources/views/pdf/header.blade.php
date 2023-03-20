@php
$maina     = getAddress('1');
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
</header>