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
                    <span class="font-weight-bold" style="color: #ed400c; font-size:24px">INVOICE</span><br>
                    <span class="font-weight-bold" style="color: #ed400c;">{{$number}}</span><br>
                    {{$time}}
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td width="50%" class="text-left" style="padding-left: 30px;">
                    <span style="font-weight: bold;">Bill to :</span><br>
                    <span style="font-weight: bold;">{{$cust->company}}</span><br>
                    {{$cust->address}}<br>
                </td>
                <td width="50%">
                    <span style="font-weight: bold;">Ship to:</span><br>
                    <span style="font-weight: bold; color:#555">{{$cust->company}}</span><br>
                    {{$cust->address}}<br>
                    @php
                    $phone = $cust->phone == "N" || $cust->phone==null ? "No Phone" : $cust->phone;
                    echo $phone;
                    @endphp
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td width="50%" class="text-left" style="padding-left: 30px;">
                    <span style="font-weight: bold;">Notes</span><br>
                    <span style="padding-top: 50px;">{{$main->quo_name}}</span>
                </td>
            </tr>
        </table>
        <table id="look" style="width: 100%; padding-top: 50px;">
            <colgroup>
                <col span="1" style="width: 20%;">
                <col span="1" style="width: 10%;">
                <col span="1" style="width: 20%;">
                <col span="1" style="width: 10%;">
                <col span="1" style="width: 20%;">
                <col span="1" style="width: 20%;">
            </colgroup>



            <!-- Put <thead>, <tbody>, and <tr>'s here! -->
            <thead>
                <tr>
                    <th width="20%">Sales</th>
                    <th width="10%">SO Number</th>
                    <th width="20%">PO Number</th>
                    <th width="10%">Phone Number</th>
                    <th width="20%">DO Number</th>
                    <th width="20%">Due Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                    <td class="text-center">{!!emp_name($main->id_sales)!!}</td>
                    <td class="text-center">SO{{$title}}</td>
                    <td class="text-center">{{$main->quo_no}}</td>
                    <td class="text-center">
                        @php
                        $phone = $cust->phone == "N" || $cust->phone==null ? "No Phone" : $cust->phone;
                        echo $phone;
                        @endphp
                    </td>
                    <td class="text-center">{{$do}}</td>
                    <td class="text-center">{{$tempo}}</td>
                </tr>
            </tbody>
        </table>
        @if($kondisi=='partial' && $jenis=='qty')
        @include('pdf.invoice_barang_custom');
        @else
        @if($kondisi=='partial' && ($jenis=='termin' or $jenis=='nominal'))
        @include('pdf.invoice_barang_comma');
        @else
        @include('pdf.invoice_barang_normal');
        @endif
        @endif

        <table id="what" style="width: 100%;">
            <colgroup>
                <col span="1" style="width: 50%;">
                <col span="1" style="width: 50%;">
            </colgroup>
            <tbody>
                <tr>
                    <td class="text-left" style="padding-left:30px;">
                        <span style="font-weight: bold;">Payment Info</span><br>
                        <span style="font-weight: bold;">PT MITRA ERA GLOBAL</span><br>
                        <span style="font-weight: bold;">Mandiri 119 00 7777 2018</span><br>
                        <span style="font-weight: bold;">Cabang Mangga Dua</span><br>
                    </td>
                    <td class="text-center">
                        <span style="font-weight: bold;">Jakarta, {{$time}}</span><br><br><br><br><br><br>
                        <span style="margin-top: 100px;">( {!!getEmp($inv->sign_by)->emp_name!!} )</span>
                    </td>
                </tr>
                <tr>
                    <td class="text-left" style="padding-left:30px; padding-top:50px">
                        Barang yang sudah dibeli tidak dapat ditukar / dikembalikan
                    </td>
                </tr>
            </tbody>
        </table>
    </main>
</body>

</html>