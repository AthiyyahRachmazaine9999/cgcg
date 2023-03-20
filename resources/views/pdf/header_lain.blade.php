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
                        <td class="title" colspan="4" style="padding-top: 30px;">
                            <img src="storage/kop_meg_small.png" style="width:70%; max-width:100px;">
                        </td>
                        <td class="text-left" style="padding-left: 20px;">
                            <h6 class="font-weight-bold">PT MITRA ERA GLOBAL</h6>

                            <strong>{!!$maina->place!!}</strong><br>
                            {!!$maina->address!!}<br style="margin-top: 50px;">
                            <strong>{!!$warehouse->place!!}</strong><br>
                            {!!$warehouse->address!!}<br>
                            {!!$maina->telp!!}<br>
                        </td>
                        <td class="title" colspan="4" style="padding-top: 30px;padding-left: 20px;">
                            <img src="storage/maleseround.png" style="width:70%; max-width:100px;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <hr class="solid">
</header>

