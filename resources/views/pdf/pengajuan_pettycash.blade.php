<!doctype html>
<html>

@include('pdf.style')

<body>
    @include('pdf.header')
    @include('pdf.footer')
    <main>
        <div style="padding-left:25px;">
            <h6 class="font-weight-bold text-center">Perencanaan Pengeluaran MEG {{$month}}</h6>
            <p class="font-weight-bold text-center"><b>Ref.
                    MEG-{{\Carbon\Carbon::parse($getdata->created_at)->format('d-m-Y')}}</b></p>
        </div>
        <table id="set_customers" class="text-center" style="padding-left: 20px;">
            <thead class="thead-colored bg-teal">
                <tr class="text-center">
                    <th>No.</th>
                    <th>Deskripsi</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                @php $j=1; $total = 0; foreach($detail as $dtl) {
                @endphp
                <tr class="text-center">
                    <td class="text-center" style="width: 5px;">{{$j++}}</td>
                    <td class="text-left">{{$dtl->purpose}}</td>
                    <td class="text-right" style="width: 170px;">{{number_format($dtl->nominal,2)}}</td>
                </tr>
                @php $total+=$dtl->nominal; } @endphp
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="font-weight-bold text-center"><strong>TOTAL</strong>
                    </td>
                    <td class="text-right">{{number_format($total),2}}</td>
                </tr>
            </tfoot>
        </table>


        @if($getdata->status == "Approved")
        <table class="title" style="padding-top: 20px; padding-left:15px;">
            <tr>
                <td style="text-align: left; width:100px;">
                    <span style="text-align: left;" class="text-left">
                        Menyetujui,</span><br>
                    <span style="text-align: left;" class="text-left">
                        Jakarta, {{\Carbon\Carbon::parse($getdata->app_finance_date==null ? $getdata->approve_date : $getdata->app_finance_date)->format('d F Y')}}</span><br>
                </td>
            </tr>
        </table>
        <table class="title text-left" style="padding-top: 10px; padding-left:15px;">
            <tr>
                <td style="text-align:left; width:100px;">
                    @php
                    $ajuan = user_name($getdata->app_by);
                    $text = "Approved By ".$ajuan." Pada Tanggal
                    ".\Carbon\Carbon::parse($getdata->app_finance_date==null ? $getdata->approve_date : $getdata->app_finance_date)->format('d F Y');
                    @endphp
                    <img
                        src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text)) !!} ">
                </td>
            </tr>
            <tr>
                <td style="text-align:left; width:100px;">
                    <u>{{user_name($getdata->app_finance==null ? $getdata->app_by : $getdata->app_finance)}}</u>
                </td>
            </tr>
            <tr>
                <td style="text-align:left; padding-top:-15%; line-height:10px; width:100px;">
                    Finance </td>
            </tr>
            <tr>

            </tr>
        </table>
        @endif

        @if($getdata->status == "Approval Completed")
        <table class="title" style="padding-top: 20px; padding-left:10px;">
            <tr>
                <td style="text-align: center; width:50px;">
                    <span style="text-align: center;" class="text-left">
                        Menyetujui,</span><br>
                    <span style="text-align: center;" class="text-left">
                        Jakarta, {{\Carbon\Carbon::parse($getdata->approve_date)->format('d F Y')}}</span><br>
                </td>
                <td style="text-align: center; width:50px;">
                    @if($getdata->app_finance_date!=null)
                    <span style="text-align: center;" class="text-left">
                        Menyetujui,</span><br>
                    <span style="text-align: center;" class="text-left">
                        Jakarta, {{\Carbon\Carbon::parse($getdata->app_finance_date)->format('d F Y')}}</span>
                    @endif<br>
                </td>            
            </tr>
        </table>
        <table class="title text-left" style="padding-top: 10px;">
            <tr>
                <td class="text-center" style="text-align:center; width:50px;">
                    @php
                    $ajuan = user_name($getdata->app_by);
                    $text = "Approved By ".$ajuan." Pada Tanggal
                    ".\Carbon\Carbon::parse($getdata->approve_date)->format('d F Y');
                    @endphp
                    <img
                        src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text)) !!} ">
                </td>
                <td class="text-center" style="text-align:center; width:50px;">
                    @php
                    $ajuan = user_name($getdata->app_finance);
                    $text = "Approved By ".$ajuan." Pada Tanggal
                    ".\Carbon\Carbon::parse($getdata->app_finance_date)->format('d F Y');
                    @endphp
                    @if($getdata->app_finance!=null)
                    <img
                        src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text)) !!} ">
                    @endif
                </td>            
            </tr>
            <tr>
                <td class="text-center" style="text-align:center; width:50px;">
                    <u>{{user_name($getdata->app_by)}}</u>
                </td>
                <td class="text-center" style="text-align:center; width:50px;">
                    <u>{{user_name($getdata->app_finance)}}</u>
                </td>
            </tr>
            <tr>
                <td class="text-center" style="text-align:center; padding-top:-15%; line-height:10px; width:100px;">
                    Direktur 
                </td>
                <td class="text-center" style="text-align:center; padding-top:-15%; line-height:10px; width:100px;">
                    @if($getdata->app_finance!=null)
                    Finance 
                    @endif
                </td>
            </tr>
            <tr>
            </tr>
        </table>
        @endif    
    </main>
</body>

</html>