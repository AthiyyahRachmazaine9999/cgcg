<!doctype html>
<html>

<head>
    @include('pdf.style')
</head>

<body>
    @include('pdf.header')
    @include('pdf.footer')
    <main>
        <table class="table table-sm table-borderless mb-0" style="width:100%;padding-top:45px;">
            <tr>
                <th class="pl-0 w-25 text-left">Nama Sales</th>
                <td>: {{emp_name($quo_mo->id_sales)}}</td>
                <th class="pl-0 w-25 text-left">PO Number</th>
                <td>: {{$quo_mo->quo_no}}</td>
            </tr>
            <tr>
                <th class="pl-0 w-25 text-left">Bulan Tagihan</th>
                <td>: {{\Carbon\Carbon::parse($inv->tgl_invoice)->format('d-F-Y')}}</td>
                <th class="pl-0 w-25 text-left" style="50px;">Nama Instansi</th>
                <td>: {{getCustomer($quo_mo->id_customer)->company}}</td>
            </tr>
            <tr>
                <th class="pl-0 w-25 text-left">Nama Satuan Kerja</th>
                <td>: {{getCustomer($quo_mo->id_customer)->company}}</td>
                <th class="pl-0 w-25 text-left">Tanggal PO</th>
                <td>: {{\Carbon\Carbon::parse($quo_mo->quo_order_at)->format('d-F-Y')}}</td>
            </tr>
            <tr>
                <th class="pl-0 w-25 text-left">Ket POC</th>
                <td>: {{getQuoType($quo_mo->quo_type)->type_name}} </td>
                <th class="pl-0 w-25 text-left">Invoice Client Date</th>
                <td>: {{\Carbon\Carbon::parse($inv->tgl_invoice)->format('d-F-Y')}}</td>
            </tr>
            <tr>
                <th class="pl-0 w-25 text-left">Associate</th>
                <td>: {{getUserEmp(17)->emp_name}}</td>
                <th class="pl-0 w-25 text-left">Invoice Client No</th>
                <td>: {{$inv->no_invoice}}</td>
            </tr>
            <tr>
                <th class="pl-0 w-25 text-left">Admin</th>
                <td>: {{user_name($inv->created_by)}}</td>
                <th class="pl-0 w-25 text-left">E-Faktur Date</th>
                <td>: {{\Carbon\Carbon::parse($quo_doc->tgl_fakturpajak)->format('d-F-Y')}}</td>
            </tr>
            <tr>
                <th class="pl-0 w-25 text-left">Seller DPP Price</th>
                <td>: {{number_format($dpp)}}</td>
                <th class="pl-0 w-25 text-left">E-Faktur No</th>
                <td>: {{$quo_doc->no_fakturpajak==null ? "-" : $quo_doc->no_fakturpajak}} </td>
            </tr>
            <tr>
                <th class="pl-0 w-25 text-left">Seller VAT</th>
                <td>: {!!$vats!!}%</td>
                <th class="pl-0 w-25 text-left">Due Date</th>
                <td>:
                    {{$inv->tgl_jatuhtempo==null ? '-' : \Carbon\Carbon::parse($inv->tgl_jatuhtempo)->format('d-F-Y')}}
                </td>
            </tr>
            <tr>
                <th class="pl-0 w-25 text-left">PPh</th>
                @php if ($pph!=null) { @endphp
                <td>: {{number_format($pph->nilai_potongan,2)}}</td>
                @php } else { @endphp
                <td>: Non PPh</td>
                @php } @endphp

                <th class="pl-0 w-25 text-left">No. NTPN PPn</th>
                @php if ($inv->no_ntpn_ppn!=null) { @endphp
                <td>: {{$inv->no_ntpn_ppn}}</td>
                @php } else { @endphp
                <td>: - </td>
                @php } @endphp
            </tr>
            <tr>
                <th class="pl-0 w-25 text-left">DO Date</th>
                <td>: {{$dos==null ? null : \Carbon\Carbon::parse($dos->tgl_kirim)->format('d-F-Y')}}</td>

                <th class="pl-0 w-25 text-left">No. NTPN PPh</th>
                @php if ($inv->no_ntpn_pph!=null) { @endphp
                <td>: {{$inv->no_ntpn_pph}}</td>
                @php } else { @endphp
                <td>: - </td>
                @php } @endphp
            </tr>
            <tr>
                <th class="pl-0 w-25 text-left">DO No. </th>
                <td>: {{$dos==null ? null : $dos->no_do}}</td>
                <th class="pl-0 w-25 text-left">Status Payment </th>
                <td>: <strong><em>{{$inv->type_payment=="parsial" ? "Parsial" : "Full"}}
                            {{$inv->ket_lunas=="Finish" ? "Lunas" : ''}}
                            {{$inv->tgl_lunas!=null ? \Carbon\Carbon::parse($inv->tgl_lunas)->format('d F Y') : ''}}</em></strong>
                </td>
            </tr>
            @if($coth!=0)
            @php foreach ($inv_oth as $oth) { @endphp
            <tr>
                <th class="pl-0 w-25 text-left">{{$oth==null ? null : $oth->des_potongan}}</th>
                <td>: {{$oth->nilai_potongan}}</td>
            </tr>
            @php } @endphp
            @endif
        </table>
        <br>
        <table id="set_customers" style="padding-left: 5px;">
            <tbody>
                <tr>
                    <td colspan="2"><strong>Total Yang Harus Di Bayar</strong></td>
                    <td class="text-right">
                        {{$inv->total_payment==null ? number_format($sumtotal) : number_format($inv->total_payment)}}
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><em>Client Payment :</em></td>
                </tr>
                @php
                $i = 1;
                foreach ($payment as $pays)
                {
                @endphp
                <tr>
                    <td colspan="3"><strong>
                            @if($pays->method_payment=='transfer')
                            {{$pays->bank_name.' - '.$pays->bank_no}}
                            @else
                            Cash
                            @endif
                        </strong></td>
                </tr>
                @php
                $j = 1;
                $allPayment = getAllInvPayment($pays->id);
                foreach ($allPayment as $arr)
                {
                @endphp
                <tr>
                    <td>{{$j++}}.</td>
                    <td>{{\Carbon\Carbon::parse($arr->date_payment)->format('d F Y')}}</td>
                    <td class="text-right">{{number_format($arr->payment_amount)}}</td>
                </tr>
                @php } @endphp
                @php } @endphp
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="font-weight-bold text-center"><b>Subtotal</b>
                    </td>
                    <td class="text-right">{{number_format($dtl)}}</td>
                </tr>
                @if($coth!=0)
                @php foreach ($other as $oth)
                {
                @endphp
                <tr>
                    <td colspan="2" class="font-weight-bold text-center">
                        <b>{{$oth==null ? 'Biaya Lain' : $oth->des_potongan}}</b>
                    </td>
                    <td class="text-right">{{number_format($oth->nilai_potongan)}}</td>
                </tr>
                @php } @endphp
                @endif
                <tr>
                    <td colspan="2" class="font-weight-bold text-center">
                        <b>TOTAL</b>
                    </td>
                    <td class="text-right">{{number_format($alltotal)}}</td>
                </tr>
                <tr>
                    <td colspan="2" class="font-weight-bold text-center">
                        <b>Selisih Bayar</b>
                    </td>
                    <td class="text-right"> {{number_format($sisa)}}
                    </td>
                </tr>
            </tfoot>
        </table>
    </main>
</body>

</html>