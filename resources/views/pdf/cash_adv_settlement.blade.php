<!doctype html>
<html>
    <style>

        @page {
            margin-top: 0px;
            margin-left: 20px;
            margin-right: 20px;
            margin-bottom: 20px;
        }

        body {
            margin-top: 4.7cm;
            margin-left: 5px;
            margin-right: 20px;
            margin-bottom: 2cm;
            font-size: 11px;
            padding-right: 5px;
            line-height: 20px;
            font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            color: #555;
        }

        header {
            position: fixed;
            top: -15px;
        }

        footer {
            position: fixed;
        }



        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td,
        #customers th {
            border: 1px solid #fcfcfc;
            padding: 5px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 5px;
            padding-bottom: 5px;
            background-color: #2da9e3;
            color: white;
        }

        #customers tfoot {
            text-align: right;
            border: none;
        }

        #look {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #look td,
        #look th {
            border: 1px solid #2da9e3;
            padding: 5px;
        }

        #look tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #look tr:hover {
            background-color: #ddd;
        }

        #look th {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        #look tfoot {
            text-align: right;
            border: none;
        }
 
        /* partial */

        #notes {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #notes td,
        #notes th {
            border: 1px solid #2da9e3;
            padding: 5px;
        }

        #notes tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #notes tr:hover {
            background-color: #ddd;
        }

        #notes th {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        #notes tfoot {
            text-align: right;
            border: none;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        h7,
        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6,
        .h7 {
            margin-bottom: 0.500rem;
            font-weight: 400;
            line-height: 1;
        }

        h1,
        .h1 {
            font-size: 1.5625rem;
        }

        h2,
        .h2 {
            font-size: 1.4375rem;
        }

        h3,
        .h3 {
            font-size: 1.3125rem;
        }

        h4,
        .h4 {
            font-size: 1.1875rem;
        }

        h5,
        .h5 {
            font-size: 1.0625rem;
        }

        h6,
        .h6 {
            font-size: 0.9375rem;
        }

        h7,
        .h7 {
            font-size: 0.6375rem;
        }

        .nomer {
            font-weight: 400;
            font-size: 1.1875rem;
        }

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        .mt-3 {
            margin-top: 1.25rem !important;
        }

        hr.solid {
            border: 1px solid #e8e3e3;
        }

        .copyright {
            width: 100%;
            position: absolute;
            bottom: 70px;
            padding-top: 7px;
            padding-bottom: 7px;
            color: #0088e3;
            border-top: 1px solid #0088e3;
            border-bottom: 1px solid #0088e3;
        }

        .title {
            width: 100%;
            bottom: 70px;
            padding-top: 0px;
            padding-bottom: 7px;
        }

        #set_customers {
            font-family: Arial;
            border-collapse: collapse;
            width: 100%;
            border: 1px solid black;
            margin-right: 20px;
            padding-right: 20px;
        }

        #set_customers td,
        #set_customers th {
            border: 1px solid block;
            padding: 8px;
            margin-right: 20px;
            padding-right: 20px;
        }


        #set_customers tr:nth-child(even) {
            background-color: white;
        }

        #set_customers tr:hover {
            background-color: #ddd;
        }

        #set_customers th,
        .colour {
            padding-top: 12px;
            background-color: #ADD8E6;
            color: black;
            margin-right: 2px;
            padding-right: 2px;
        }

        #set_customerss th {
            padding-top: 12px;
            padding-bottom: 12px;
            color: white;
        }

        #set_customers tfoot {
            text-align: right;
            border: none;
        }

        /* table */
        #tables_sett {
            width: 700px;
            height: 710px;
            border: 1px solid black;
            margin-right: 2px;
        }

        #tables_sett td,
        #tables_sett th {
            border: 1px solid #fcfcfc;
            padding: 8px;
        }

        .p_text {
            font-size: 10px;
            color: black;
            text-align: center;
        }
        
    </style>
<body>
    @include('pdf.header')
    @include('pdf.footer')
    <main>
        <table id="set_customers" class="tables_sett">
            <thead class="">
                <tr class="text-center" style="padding:2px">
                    <th colspan="4">SETTLEMENT FORM</th>
                    <th colspan="2">No. {{$set->no_settlement}}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4">Name : {{emp_name($set->employee_id)}}
                        @if($set->no_ref!=null)
                        <br>
                        {{$set->no_ref}}
                        @endif
                        <br>
                        <br>
                        @php
                        $ajuan = emp_name($set->employee_id);
                        $text = "No. Settlement ".$set->no_settlement." Proposed By ".$ajuan;
                        @endphp
                        <div class="text-center">
                            <img
                                src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text)) !!} ">
                        </div>
                    </td>
                    <td colspan="2">Date : {{\Carbon\carbon::parse($set->created_at)->format('d F Y')}}</td>
                </tr>
                <tr>
                    <td colspan="4" rowspan="2">Department Head Signature:
                        {{$set->app_manage == null ? '' : user_name($set->app_manage)}}<br>
                        <br>
                        @if($set->app_manage != null)
                        <br>
                        @php
                        $ajuan = user_name($set->app_manage);
                        $text = "No. Settlement ".$set->no_settlement." Approved By ".$ajuan;
                        @endphp
                        <div class="text-center">
                            <img
                                src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text)) !!} ">
                        </div>
                        @endif
                    </td>
                    <td colspan="2" rowspan="1" class="" style="padding-top:50px">Date :
                        {{$set->tgl_app_manage==null ? '' : \Carbon\carbon::parse($set->tgl_transfer == null ? $set->tgl_app_manage : $set->tgl_transfer)->format('d F Y')}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border-top:hidden;"></td>
                </tr>
                <tr>
                    <td class="p_text" colspan="6" style="line-height:4px;" class="text-center"><i><b>Note : Signature is needed from Department Head to be a valid request</b></i></td>
                </tr>
                <tr>
                    <th class="colour" colspan="6" style="line-height:5px;">List Of Items For Reimbursement</thd>
                </tr>
                <tr class="text-center">
                    <td><b>Tujuan</b></td>
                    <td><b>Items</b></td>
                    <td><b>Qty</b></td>
                    <td><b>Unit Price</b></td>
                    <td><b>Total</b></td>
                    <td><b>Receipt</b></td>
                </tr>
                @php $total = 0; $total_all = 0; foreach ($dtl as $dtl) {
                $total = $dtl->qty*$dtl->est_biaya;
                @endphp
                <tr>
                    <td>{{$dtl->tujuan}}</td>
                    <td style="width: 150px;">{{$dtl->notes==null ? $dtl->tujuan : $dtl->notes}}</td>
                    <td class="text-center" style="width: 12px;">{{$dtl->qty}}</td>
                    <td class="text-right">{{number_format($dtl->est_biaya,2)}}</td>
                    <td class="text-right">{{number_format($total)}}</td>
                    <td style="width: 9px;"></td>
                </tr>
                @php $total_all+=$total; } @endphp
                @if($set->no_ref!=null && $set->sisa_biaya==null && $set->biaya_finance==null)
                <tr>
                    <td colspan="4" class="text-right" style="font-size:12px;"><b>Total Settlement</b>
                    </td>
                    <td colspan="2" class="text-center"><b>{{number_format($total_all)}}</b></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right" style="font-size:12px;"><b>Total Cash Advance</b></td>
                    <td colspan="2" class="text-center"><b>{{number_format($cash->biaya_finance)}}</b></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right" style="font-size:12px;"><b>Selisih / Refund</b></td>
                    <td colspan="2" class="text-center">
                        <b>{{$set->sisa_biaya==null ? number_format(abs($cash->biaya_finance-$total_all)) : number_format(abs($cash->biaya_finance-$total_all-$set->sisa_biaya))}}</b>
                    </td>
                </tr>
                <tr class="text-center">
                    <td colspan="6" class="text-left" style="font-size:10px;">Terbilang Settlement
                        <p class="text-center" style="font-size:12px;"><b>#{{terbilang($total_all)}}#</b></p>
                    </td>
                </tr>
                @else                
                <tr class="text-center">
                    <td colspan="4" class="text-left" style="font-size:10px;">Terbilang Settlement
                        <p class="text-center" style="font-size:12px;"><b>#{{terbilang($total_all)}}#</b></p>
                    </td>
                    <td colspan="2" class="text-center">
                        <b>{{number_format($total_all)}}</b>
                    </td>
                </tr>
                @endif
                <tr>
                    <td colspan="6" class="text-center p_text" style="line-height:5px"><i><b>Receipts Must Attached To This Liquidation Form</b></i>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border-right: none; ">
                        @if($set->app_finance!=null)
                        <p class="text-center"> {{getUserEmp($set->app_finance)->position}} </p>
                        @php
                        $ajuan = user_name($set->app_finance);
                        $text = "No. Settlement ".$set->no_settlement." Completed By ".$ajuan;
                        @endphp
                        <div class="text-center">
                            
                            <img
                                src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text)) !!} ">
                        </div>
                        <div class="text-center">
                            {{$set->app_finance==null ? '' : user_name($set->app_finance)}}
                        <br>
                        </div>
                        @endif
                    </td>
                    <td colspan="2" style="border-left: none; ">
                        @if($set->app_director!=null || $set->app_manage == 30 || $set->app_finance == 30)
                        @if($set->app_manage != $set->app_finance)
                        <p style="padding-left:35px;"> {{$set->app_director==null ? getUserEmp($set->app_manage)->position : getUserEmp($set->app_director)->position}} </p>
                        @php
                        $ajuan = $set->app_director == null ? user_name(30):user_name($set->app_director);
                        $text = "No. Settlement ".$set->no_settlement." Approved By ".$ajuan;
                        @endphp
                        <div style="padding-left:20px;">
                            
                            <img
                                src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text)) !!} ">
                        </div>
                        <div>
                            {{$set->app_director==null ? user_name(30) : user_name($set->app_director)}}
                        <br>
                        </div>
                        @endif
                        @endif
                    </td>
                    <td colspan="2">Date:
                        {{$set->app_finance==null ? '' : \Carbon\Carbon::parse($set->tgl_transfer == null ? $set->tgl_app_finance : $set->tgl_transfer)->format('d F Y')}}
                    </td>
                </tr>
            </tbody>
        </table>
    </main>
</body>

</html>