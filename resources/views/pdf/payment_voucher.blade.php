<!doctype html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
    @page {
        margin-top: 0px;
        margin-left: 20px;
        margin-right: 20px;
        margin-bottom: 0px;
    }

    * {
        margin: 0;
        padding: 0;
    }

    body {
        margin-left: 20px;
        margin-right: 20px;
        font-size: 12px;
        line-height: 15%;
        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
    }

    .all {
        margin-left: 20px;
        margin-right: 20px;
        font-size: 12px;
        line-height: 15%;
        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
        background-color: white;
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

    .judul {
        border: 1px solid black;
        line-height: 0.5px;
        position: static;
        width: 100%;
        height: 500px;
        margin-top: 2px;
        text-align: center;
        padding-top: 15px;
    }

    .column {
        float: left;
        padding: 10px;
        height: 300px;
        /* Should be removed. Only for demonstration */
    }

    #th_kanan {
        text-align: right;
        padding-left: 50px;
    }

    #payment {
        padding-top: 20%;
        width: 70%;
        font-family: Arial, Helvetica, sans-serif;
        padding: 8px;
    }

    #payment th {
        width: 20%;
        padding-left: 15px;
        padding-top: 15px;
        padding-bottom: 12px;
        text-align: left;
    }

    #payment2 {
        /* border: 1px solid black; */
        width: 100%;
        font-family: Arial, Helvetica, sans-serif;
    }

    #paraf_tbl {
        /* border: 1px solid black; */
        width: 100%;
        text-align: center;
        font-family: Arial, Helvetica, sans-serif;
        padding-top: 10px;
    }

    #payment2 th {
        width: 20%;
        padding-left: 15px;
        padding-top: 20px;
        line-height: 2px;
        padding-bottom: 12px;
        text-align: left;
    }

    #nominal {
        border: 1px solid black;
        padding-top: 18px;
        width: 50%;
        height: 15px;
        margin: 20px;
        margin-top: 5px;
    }

    #terbilang {
        border: 1px solid black;
        padding-top: 5px;
        width: 70%;
        word-wrap: break-word;
        height: 30px;
        margin: 20px;
        margin-top: 5px;

    }

    div.text p {
        line-height: 1.3;
        text-align: up;
    }

    #nominal2 {
        border: 1px solid black;
        padding-top: 15px;
        width: 70%;
        height: 150px;
        line-height: 1.6;
        position: static;
        margin: 20px;
        margin-top: 5px;
        text-align: center;

    }

    #nominal_kedua {
        border: 1px solid black;
        padding-top: 15px;
        width: 50%;
        height: 15px;
        margin: 20px;
        margin-top: 5px;
    }

    #terbilang_kedua {
        border: 1px solid black;
        padding-top: 2px;
        width: 70%;
        word-wrap: break-word;
        height: 30px;
        margin: 20px;
        margin-top: 5px;
    }


    #nominal_kedua2 {
        border: 1px solid black;
        padding-top: 15px;
        width: 70%;
        height: 128px;
        line-height: 1.6;
        position: static;
        margin: 20px;
        margin-top: 5px;
        text-align: center;

    }


    #paraf {
        border: 1px solid black;
        margin-top: 10px;
        margin-left: 75%;
        width: 20%;
        height: 380px;
    }

    #paraf_kedua {
        border: 1px solid black;
        margin-top: 10px;
        margin-left: 75%;
        width: 20%;
        height: 380px;
    }


    #nominal tr {
        border: 1px solid black;
        padding-left: 20px;
    }

    .column {
        float: left;
        padding: 10px;
        height: 300px;
    }

    .group-right {
        width: 40%;
    }

    .group-left {
        width: 90%;
    }
    </style>
</head>

<body>
    @if ($type == "check")
    <main>
        <div class="judul">
            <h2 class="font-weight-bold" style="color:black;padding-bottom:30px"><b>PAYMENT VOUCHER</b></h2>
            @if($pay_dtl->note_pph!=null)
            <div style="text-align:left;padding-left:294px; color: #ff0000;">
                <p>{{$pay_dtl->note_pph}} = Rp. {{number_format($pay_dtl->note_nominal_pph)}}</p>
            </div>
            <div style="text-align:left;padding-top:15px; padding-left:220; color: #ff0000;">
                <p>Nominal Transfer = Rp. {{number_format($pay_dtl->note_transfer_pph)}}</p>
            </div>
            @endif
            <br>
            <br>
            <div class="column group-left">
                <table id="payment">
                    <tbody>
                        <tr>
                            <th>No. Date</th>
                            <td style="text-align:left;">:
                                {{$time}}</td>
                        </tr>
                        <tr>
                            <th>No.</th>
                            <td>:
                                <b>
                                    @if($pay->id_cetak!=null)
                                    {{$pay->id_cetak .'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year}}
                                    @else
                                    {{$pays.'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year}}
                                    @endif
                                </b>
                            </td>
                        </tr>
                        <tr style="" scope="row">
                            <th></th>
                            <td style="text-align:right;">
                                @if($pay_dtl->type_payment=="top")
                                <b><mark>TOP {{$pay_dtl->top_date}}</mark></b>
                                @elseif ($pay_dtl->type_payment=="cbd")
                                <b><mark>CBD {{$pay_dtl->top_date}}</mark></b>
                                @elseif($pay_dtl->type_payment=="net")
                                <b><mark>Nett {{$pay_dtl->top_date}}</mark></b>
                                @else
                                <b><mark>{{$pay_dtl->type_payment}}</mark></b>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table><br>
                <!-- Nominal -->
                <div id="nominal">
                    <p style="text-align:left; padding-left:10px;">
                        <b>Rp.
                            {{number_format($pay_dtl->nominal)}}</b>
                    </p>
                </div>
                <!-- //Terbilang -->
                <br><br>
                <p style="text-align:left; padding-left:15px;">
                    <b>Terbilang :
                    </b>
                </p>
                <div id="terbilang">
                    <div class="text">
                        <p style="text-align:left; padding-left:10px;font-size:10px">
                            <b>{{terbilang($pay_dtl->nominal)}}</b>
                        </p>
                    </div>
                </div>
                <!-- //keperluan -->
                <br><br>
                <p style="text-align:left; padding-left:16px;">
                    <b>Untuk Keperluan :
                    </b>
                </p>
                <div id="nominal2">
                    <p style="text-align:center; padding-left:10px;"><b>{{$pay_dtl->tujuan}}</b></p>
                    <p style="text-align:center; padding-left:10px; padding-top:10px;">
                        @if($pay_dtl->id_vendor==null)
                        {{getCustomer($pay_dtl->id_customer)->company}}
                        @else
                        {{getVendor($pay_dtl->id_vendor)->vendor_name}}
                        @endif
                    </p>
                    <table id="payment2">
                        <tbody>
                            <tr>
                                <th>INV/KWT :</th>
                                <td style="text-align:left;">
                                    {!!$pay_dtl->no_invoice==null ? $pay_dtl->performa_invoice : $pay_dtl->no_invoice!!}
                            </tr>
                            <tr>
                                <th>Tanggal:</th>
                                <td style="text-align:left;">
                                    {{\Carbon\Carbon::parse($pay_dtl->from_date)->format('d-m-Y')}}
                                </td>
                                <td style="text-align:left;background-color: yellow;"><b> Due Date: </b>
                                    {{$pay_dtl->to_date==null ? '-' : \Carbon\Carbon::parse($pay_dtl->to_date)->format('d-m-Y')}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="paraf" class="">
                <table id="paraf_tbl">
                    <thead>
                        <tr>
                            <th>Finance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($pay_dtl->app_hrd!=null)
                        <tr>
                            <td style="padding-top:50px; text-align:center">
                                @php
                                $approval = "Finance Manager";
                                $text = "Approval Completed By ".user_name($pay_dtl->app_hrd);
                                @endphp
                                <img
                                    src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text))!!} ">
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:30px; text-align:center">{{user_name($pay_dtl->app_hrd)}}</td>
                        </tr>
                        @else
                        <tr>
                            <td style="padding-top:170px; text-align:center">{{user_name($pay_dtl->app_hrd)}}</td>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        @if($pay_dtl->app_finance!=null)
                        <tr>
                            <td style="padding-top:50px; text-align:center">
                                @php
                                $approval = "Finance";
                                $text = "Approved By ".user_name($pay_dtl->app_finance);
                                @endphp
                                <img
                                    src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text))!!} ">
                            </td>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:30px text-align:center">Nurul Aryani</td>
                        </tr>
                        @else
                        <tr>
                            <td style="padding-top:180px text-align:center">Nurul Aryani</td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
        </div>
        <br>
        <!--======================KEDUA======================== -->
        <div class="judul">
            <h2 class="font-weight-bold" style="color:black;padding-bottom:30px"><b>PAYMENT VOUCHER</b></h2>
            @if($pay_dtl1->note_pph!=null)
            <div style="text-align:left;padding-left:294px; color: #ff0000;">
                <p>{{$pay_dtl1->note_pph}} = Rp. {{number_format($pay_dtl1->note_nominal_pph)}}</p>
            </div>
            <div style="text-align:left;padding-top:15px; padding-left:220; color: #ff0000;">
                <p>Nominal Transfer = Rp. {{number_format($pay_dtl1->note_transfer_pph)}}</p>
            </div>
            @endif
            <br>
            <br>
            <div class="column group-left">
                <table id="payment">
                    <tbody>
                        <tr>
                            <th>No. Date</th>
                            <td style="text-align:left;">:
                                {{$time}}</td>
                        </tr>
                        <tr>
                            <th>No.</th>
                            <td>:
                                <b>
                                    @if($pay1->id_cetak!=null)
                                    {{$pay1->id_cetak .'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year}}
                                    @else
                                    {{$pays.'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year}}
                                    @endif
                                </b>
                            </td>
                        </tr>
                        <tr style="" scope="row">
                            <th></th>
                            <td style="text-align:right;">
                                @if($pay_dtl1->type_payment=="top")
                                <b><mark>TOP {{$pay_dtl1->top_date}}</mark></b>
                                @elseif ($pay_dtl1->type_payment=="cbd")
                                <b><mark>CBD {{$pay_dtl1->top_date}}</mark></b>
                                @elseif($pay_dtl1->type_payment=="net")
                                <b><mark>Nett {{$pay_dtl1->top_date}}</mark></b>
                                @else
                                <b><mark>{{$pay_dtl1->type_payment}}</mark></b>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table><br>
                <!-- Nominal -->
                <div id="nominal">
                    <p style="text-align:left; padding-left:10px;">
                        <b>Rp.
                            {{number_format($pay_dtl1->nominal)}}</b>
                    </p>
                </div>
                <!-- //Terbilang -->
                <br><br>
                <p class="nama" style="text-align:left; padding-left:15px;">
                    <b>Terbilang :
                    </b>
                </p>
                <div id="terbilang">
                    <div class="text">
                        <p style="text-align:left; word-wrap: break-word; padding-left:10px;font-size:10px">
                            <b>{{terbilang($pay_dtl1->nominal)}}</b>
                        </p>
                    </div>
                </div>
                <!-- //keperluan -->
                <br><br>
                <p style="text-align:left; padding-left:16px;">
                    <b>Untuk Keperluan :
                    </b>
                </p>
                <div id="nominal2">
                    <p style="text-align:center; padding-left:10px;"><b>{{$pay_dtl1->tujuan}}</b></p>
                    <p style="text-align:center; padding-left:10px; padding-top:10px;">
                        @if($pay_dtl1->id_vendor==null)
                        {{getCustomer($pay_dtl1->id_customer)->company}}
                        @else
                        {{getVendor($pay_dtl1->id_vendor)->vendor_name}}
                        @endif
                    </p>
                    <table id="payment2">
                        <tbody>
                            <tr>
                                <th>INV/KWT :</th>
                                <td style="text-align:left;">
                                    {!!$pay_dtl1->no_invoice==null ? $pay_dtl1->performa_invoice :
                                    $pay_dtl1->no_invoice!!}
                            </tr>
                            <tr>
                                <th>Tanggal:</th>
                                <td style="text-align:left;">
                                    {{\Carbon\Carbon::parse($pay_dtl1->from_date)->format('d-m-Y')}}
                                </td>
                                <td style="text-align:left;background-color: yellow;"><b> Due Date: </b>
                                    {{$pay_dtl1->to_date==null ? '-' : \Carbon\Carbon::parse($pay_dtl1->to_date)->format('d-m-Y')}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="paraf_kedua" class="">
                <table id="paraf_tbl">
                    <thead>
                        <tr>
                            <th>Finance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($pay_dtl->app_hrd!=null)
                        <tr>
                            <td style="padding-top:50px; text-align:center">
                                @php
                                $approval = "Finance Manager";
                                $text = "Approval Completed By ".user_name($pay_dtl->app_hrd);
                                @endphp
                                <img
                                    src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text))!!} ">
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:30px; text-align:center">{{user_name($pay_dtl->app_hrd)}}</td>
                        </tr>
                        @else
                        <tr>
                            <td style="padding-top:170px; text-align:center">{{user_name($pay_dtl->app_hrd)}}</td>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        @if($pay_dtl->app_finance!=null)
                        <tr>
                            <td style="padding-top:50px; text-align:center">
                                @php
                                $approval = "Finance";
                                $text = "Approved By ".user_name($pay_dtl->app_finance);
                                @endphp
                                <img
                                    src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text))!!} ">
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:30px text-align:center">Nurul Aryani</td>
                        </tr>
                        @else
                        <tr>
                            <td style="padding-top:180px text-align:center">Nurul Aryani</td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </main>
    @else
    <main>
        <div class="judul">
            <h2 class="font-weight-bold" style="color:black;padding-bottom:30px"><b>PAYMENT VOUCHER</b></h2>
            @if($pay_dtl->note_pph!=null)
            <div style="text-align:left;padding-left:294px; color: #ff0000;">
                <p>{{$pay_dtl->note_pph}} = Rp. {{number_format($pay_dtl->note_nominal_pph)}}</p>
            </div>
            <div style="text-align:left;padding-top:15px; padding-left:220;color: #ff0000;">
                <p>Nominal Transfer = Rp. {{number_format($pay_dtl->note_transfer_pph)}}</p>
            </div>
            @endif
            <br>
            <br>
            <div class="column group-left">
                <table id="payment">
                    <tbody>
                        <tr>
                            <th>No. Date</th>
                            <td style="text-align:left;">:
                                {{$time}}</td>
                        </tr>
                        <tr>
                            <th>No.</th>
                            <td>:
                                <b>
                                    @if($pay->id_cetak!=null)
                                    {{$pay->id_cetak .'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year}}
                                    @else
                                    {{$pays->max.'/'.getRomawi($month).'/'.'MEG'.'/'.'PV'.'/'.$year}}
                                    @endif
                                </b>
                            </td>
                        </tr>
                        <tr style="" scope="row">
                            <th></th>
                            <td style="text-align:right;">
                                @if($pay_dtl->type_payment=="top")
                                <b><mark>TOP {{$pay_dtl->top_date}}</mark></b>
                                @elseif ($pay_dtl->type_payment=="cbd")
                                <b><mark>CBD {{$pay_dtl->top_date}}</mark></b>
                                @elseif($pay_dtl->type_payment=="net")
                                <b><mark>Nett {{$pay_dtl->top_date}}</mark></b>
                                @else
                                <b><mark>{{$pay_dtl->type_payment}}</mark></b>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table><br>
                <!-- Nominal -->
                <div id="nominal">
                    <p style="text-align:left; padding-left:10px;">
                        <b>Rp.
                            {{number_format($pay_dtl->nominal)}}</b>
                    </p>
                </div>
                <!-- //Terbilang -->
                <br><br>
                <p style="text-align:left; padding-left:15px;">
                    <b>Terbilang :
                    </b>
                </p>
                <div id="terbilang">
                    <div class="text">
                        <p style="text-align:left; padding-left:10px;font-size:10px">
                            <b>{{terbilang($pay_dtl->nominal)}}</b>
                        </p>
                    </div>
                </div>
                <!-- //keperluan -->
                <br><br>
                <p style="text-align:left; padding-left:16px;">
                    <b>Untuk Keperluan :
                    </b>
                </p>
                <div id="nominal2">
                    <p style="text-align:center; padding-left:10px;"><b>{{$pay_dtl->tujuan}}</b></p>
                    <p style="text-align:center; padding-left:10px; padding-top:10px;">
                        @if($pay_dtl->id_vendor==null)
                        {{getCustomer($pay_dtl->id_customer)->company}}
                        @else
                        {{getVendor($pay_dtl->id_vendor)->vendor_name}}
                        @endif
                    </p>
                    <table id="payment2">
                        <tbody>
                            <tr>
                                <th>INV/KWT :</th>
                                <td style="text-align:left;">
                                    {!!$pay_dtl->no_invoice==null ? $pay_dtl->performa_invoice : $pay_dtl->no_invoice!!}
                            </tr>
                            <tr>
                                <th>Tanggal:</th>
                                <td style="text-align:left;">
                                    {{\Carbon\Carbon::parse($pay_dtl->from_date)->format('d-m-Y')}}
                                </td>
                                <td style="text-align:left;background-color: yellow;"><b> Due Date: </b>
                                    {{$pay_dtl->to_date==null ? '-' : \Carbon\Carbon::parse($pay_dtl->to_date)->format('d-m-Y')}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="paraf" class="">
                <table id="paraf_tbl">
                    @if($pay_dtl->id==205 || $pay_dtl->id==258 || $pay_dtl->app_mng!=null || $forward!=null)
                    <thead>
                        <tr>
                            <th>Direktur</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if($pay_dtl->app_mng!=null)
                        <tr>
                            <td style="padding-top:30px; text-align:center">
                                @php
                                $approval = "Management";
                                $text = "Approval Completed By ".user_name($pay_dtl->app_mng);
                                @endphp
                                <img
                                    src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(60)->generate($text))!!} ">
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:20px; text-align:center; padding-bottom:30px;">Muchsin Habiburohman</td>
                        </tr>
                        @else
                        <tr>
                            <td style="padding-top:100px; text-align:center; padding-bottom:30px;">Muchsin Habiburohman</td>
                        </tr>
                        @endif
                        @if($pay_dtl->app_hrd!=null)
                        <tr>
                            <th>Finance</th>
                        </tr>
                        <tr>
                            <td style="padding-top:20px; text-align:center">
                                @php
                                $approval = "Finance Manager";
                                $text = "Approval Completed By ".user_name($pay_dtl->app_hrd);
                                @endphp
                                <img
                                    src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(60)->generate($text))!!} ">
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:20px; text-align:center">{{user_name($pay_dtl->app_hrd)}}</td>
                        </tr>
                        @elseif($pay_dtl->app_mng==null && $pay_dtl->app_hrd==null)
                        <tr>
                            <td style="padding-top:95spx; text-align:center">{{user_name($pay_dtl->app_hrd)}}</td>
                        </tr>
                        @else
                        <tr>
                            <th style="padding-top:95spx;">Finance</th>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        @if($pay_dtl->app_finance!=null)
                        <tr>
                            <td style="padding-top:20px; text-align:center">
                                @php
                                $approval = "Finance";
                                $text = "Approved By ".user_name($pay_dtl->app_finance);
                                @endphp
                                <img
                                    src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(60)->generate($text))!!} ">
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:20px text-align:center">Nurul Aryani</td>
                        </tr>
                        @else
                        <tr>
                            <td style="padding-top:95px text-align:center">Nurul Aryani</td>
                        </tr>
                        @endif   

                        @elseif($pay_dtl->app_mng==null)

                        <tr>
                            <th>Finance</th>
                        </tr>
                        @if($pay_dtl->app_hrd!=null)
                        <tr>
                            <td style="padding-top:50px; text-align:center">
                                @php
                                $approval = "Finance Manager";
                                $text = "Approval Completed By ".user_name($pay_dtl->app_hrd);
                                @endphp
                                <img
                                    src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text))!!} ">
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:30px; text-align:center">{{user_name($pay_dtl->app_hrd)}}</td>
                        </tr>
                        @else
                        <tr>
                            <td style="padding-top:170px; text-align:center">{{user_name($pay_dtl->app_hrd)}}</td>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        @if($pay_dtl->app_finance!=null)
                        <tr>
                            <td style="padding-top:50px; text-align:center">
                                @php
                                $approval = "Finance";
                                $text = "Approved By ".user_name($pay_dtl->app_finance);
                                @endphp
                                <img
                                    src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text))!!} ">
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:30px text-align:center">Nurul Aryani</td>
                        </tr>
                        @else
                        <tr>
                            <td style="padding-top:180px text-align:center">Nurul Aryani</td>
                        </tr>
                        @endif
                        @endif
                    </tfoot>
                </table>
            </div>
        </div>
        <!-- //paraf buat disamping-->
    </main>
    @endif
</body>

</html>