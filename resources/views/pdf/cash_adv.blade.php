<!doctype html>
<html>

@include('pdf.style')

<body>
    @include('pdf.header')
    @include('pdf.footer')
    <main>
        <div style="padding-top:10px">
            <h4 class="font-weight-bold">CASH ADVANCE</h4>
            <p class="font-weight-bold"><b>{{$main->no_cashadv}}</b></p>
            <!-- <span class="text-right" style="font-weight: bold; font-size: 0.8375rem;">{{$time}}</span> -->
        </div>
        <br>
        <table class="table table-sm table-borderless mb-0" style="width:100%">
            <tbody>
                <tr>
                    <th class="pl-0 w-25 text-left" scope="row">Nama
                    </th>
                    <td>: {{emp_name($main->emp_id)}}</td>
                    <th class="pl-0 w-25 text-left" scope="row">Tanggal Diajukan
                    </th>
                    <td>: {{Carbon\Carbon::parse($main->tgl_pengajuan)->format('d F Y')}}</td>
                </tr>
                <tr style="font-size:90%">
                    <th class="pl-0 w-25 text-left" scope="row">Divisi
                    </th>
                    <td>: {{div_name(getEmp($main->emp_id)->division_id)}}</td>
                    <th class="pl-0 w-25 text-left" scope="row">Tanggal Berangkat
                    </th>
                    <td>: {{Carbon\Carbon::parse($main->tgl_berangkat)->format('d F Y')}}</td>
                </tr>
                <tr style="font-size:90%">
                    <th class="pl-0 w-25 text-left" scope="row" style="">Jabatan
                    </th>
                    <td>: {{getEmp($main->emp_id)->position}}</td>
                    <th class="pl-0 w-25 text-left" scope="row">Tanggal Pulang
                    </th>
                    <td>: {{Carbon\Carbon::parse($main->tgl_pulang)->format('d F Y')}}</td>
                </tr>
                <tr style="font-size:90%">
                    <th class="pl-0 w-25 text-left" scope="row" style="">Tujuan
                    </th>
                    <td>: {{city($main->des_kota).', '.province($main->des_provinsi)}}</td>
                    <th class="pl-0 w-25 text-left" scope="row"> Estimasi Waktu
                    </th>
                    <td>: {{$main->est_waktu}}</td>
                </tr>
            </tbody>
        </table>
        <table id="set_customers" style="padding-left: 5px; padding-top: 40px;">
            <thead class="thead-colored bg-teal">
                <tr class="text-center">
                    <th>No.</th>
                    <th>Tanggal Kegiatan / Pekerjaan</th>
                    <th>Nama Kegiatan / Pekerjaan</th>
                    <th>Deskripsi</th>
                    <th>Biaya</th>
                </tr>
            </thead>
            <tbody>
                @php $j=1; $total = 0; foreach($dtl as $dtl) { @endphp $hitung=
                $total+=$dtl->est_biaya;
                <tr class="text-center">
                    <td style="width: 5%;" class="text-center">{{$j++}}</td>
                    <td style="width: 15%;">{{\Carbon\Carbon::parse($dtl->tgl_pekerjaan)->format('d F Y')}}</td>
                    <td style="width: 35%;" class="text-left">{{$dtl->nama_pekerjaan}}</td>
                    <td style="width: 35%;" class="text-left">{{$dtl->deskripsi}}</td>
                    <td style="width: 10%;" class="text-right">{{number_format($dtl->est_biaya)}}</td>
                </tr>
                @php $total+=$dtl->est_biaya; } @endphp
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="font-weight-bold text-center"><b>TOTAL</b>
                    </td>
                    <td class="text-right">{{number_format($total)}}</td>
                </tr>
            </tfoot>
        </table>
        @if($main->mtd_cash=="Transfer")
        <br>
        <p class="small" style="text-align: left; font-size: 10px; line-height: 1.6;">
            <em>* Pembayaran Secara {{$main->mtd_cash}}</em>
        </p>
        <table border="1" cellpadding="0" cellspacing="0" width="400px">
            <tbody>
                <tr>
                    <td colspan="1" style="width: 200px; padding-left: 2px;">Bank</td>
                    <td colspan="2" style="width: 300px; padding-left: 2px;">{{$main->rek_bank}}</td>
                </tr>
                <tr>
                    <td colspan="1" style="width: 200px; padding-left: 2px;">Cabang</td>
                    <td colspan="2" style="width: 300px; padding-left: 2px;">{{$main->cabang_rek}}</td>
                </tr>
                <tr>
                    <td colspan="1" style="width: 200px; padding-left: 2px;">No. Rekening</td>
                    <td colspan="2" style="width: 300px; padding-left: 2px;">{{$main->no_rek}}</td>
                </tr>
                <tr>
                    <td colspan="1" style="width: 200px; padding-left: 2px;">Nama Rekening</td>
                    <td colspan="2" style="width: 300px; padding-left: 2px;">{{$main->nama_rek}}</td>
                </tr>
            </tbody>
        </table>
        <!-- <p class="small" style="text-align: left; font-size: 80%; line-height: 1.6; padding-top: 10px">
            <em>* Pembayaran Secara
                {{$main->mtd_cash}}</em><br>
            ___BANK
            {{$main->rek_bank}}<br>
            No. Rekening
            : {{$main->no_rek}}<br>
            Nama Rekening
            : {{$main->nama_rek}}<br>
            Cabang BANK
            : {{$main->cabang_rek}}<br>
        </p> -->
        @if($main->status != 'Pending')
        <table class="title" style="padding-top: 50px;">
            <tr>
                <td style="text-align: center; width:100px;">
                    <span text-align: left;" class="text-left">
                        Diajukan Oleh</span>
                </td>
                <td style="text-align:center; width:100px;">
                    <span>Disetujui Oleh</span>
                </td>
                <td style="text-align:center; width:100px;">
                    <span>Diketahui Oleh</span>
                </td>
            </tr>
        </table>
        <table class="title text-center" style="padding-top: 10px;">
            <tr>
                <td style="text-align: center; width:100px;">
                    @php
                    $ajuan = emp_name($main->emp_id);
                    $text = "Proposed By ".$ajuan." Pada Tanggal
                    ".\Carbon\Carbon::parse($main->tgl_pengajuan)->format('d-F-Y');
                    @endphp
                    <img
                        src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text)) !!} ">
                </td>
                @if($main->app_spv!=null)
                <td style="text-align: center; width:100px;">
                    @php
                    $approval = "Management";
                    $text = "Approved By ".user_name($main->app_spv);
                    @endphp
                    <img
                        src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text)) !!} ">
                </td>
                <td style="text-align: center; width:100px;">
                    @if($main->app_hr!=null)
                    @php
                    $apps = "Finance Manager";
                    $text = "Completed By ".user_name($main->app_hr);
                    @endphp
                    <img
                        src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text)) !!} ">
                    @endif
                </td>
                @else
                <td style="text-align: center; width:100px;">
                </td>
                <td style="text-align: center; width:100px;">
                </td>
                @endif
            </tr>
            <tr style="padding-top:3px;">
                <td style="text-align: center; width:100px;">
                    {{emp_name($main->emp_id)}}<br>
                </td>
                <td style="text-align: center; width:100px;">
                    @if($main->app_spv!=null)
                    {{user_name($main->app_spv)}}<br>
                    @endif
                </td>
                <td style="text-align: center; width:100px;">
                    @if($main->app_hr!=null)
                    {{user_name($main->app_hr)}}<br>
                    @endif
                </td>
            </tr>
            <!--             <tr style="padding-top:3px;">
                <td style="text-align: center; width:100px;">
                    {{$main->nama_emp}}<br>
                </td>
                <td style="text-align: center; width:100px;"">
                    MANAGEMENT<br>
                </td>
                <td style=" text-align: center; width:100px;"">
                    FINANCE<br>
                </td>
            </tr> -->
        </table>
        @endif
        @else
        <p class="small" style="font-size: 11.5px; line-height: 1;">
            <em>* Pembayaran Secara
                {{$main->mtd_cash}}</em><br>
        </p>
        <br>
        <br>
        @if($main->status != 'Pending')
        <table class="title">
            <tr>
                <td style="text-align: center; width:100px;">
                    <span text-align: left;" class="text-left">
                        Diajukan Oleh</span>
                </td>
                <td style="text-align:center; width:100px;">
                    <span>Disetujui Oleh</span>
                </td>
                <td style="text-align:center; width:100px;">
                    <span>Diketahui Oleh</span>
                </td>
            </tr>
        </table>
        <table class="title" style="padding-top: 10px;">
            <tr>
                <td style="text-align: center; width:100px;">
                    @php
                    $ajuan = emp_name($main->emp_id);
                    $text = "Proposed By ".$ajuan." Pada Tanggal
                    ".\Carbon\Carbon::parse($main->tgl_pengajuan)->format('d-F-Y');
                    @endphp
                    <img
                        src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text)) !!} ">
                </td>
                @if($main->app_spv!=null)
                <td style="text-align: center; width:100px;">
                    @php
                    $approval = "Management";
                    $text = "Approved By ".user_name($main->app_spv);
                    @endphp
                    <img
                        src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text)) !!} ">
                </td>
                <td style="text-align: center; width:100px;">
                    @if($main->app_hr!=null)
                    @php
                    $apps = "Finance Manager";
                    $text = "Completed By ".user_name($main->app_hr);
                    @endphp
                    <img
                        src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text)) !!} ">
                    @endif
                </td>
                @else
                <td style="text-align: center; width:100px;">
                </td>
                <td style="text-align: center; width:100px;">
                </td>
                @endif
            </tr>
            <tr style="padding-top:3px;">
                <td style="text-align: center; width:100px;">
                    {{emp_name($main->emp_id)}}<br>
                </td>
                <td style="text-align: center; width:100px;">
                    @if($main->app_spv!=null)
                    {{user_name($main->app_spv)}}<br>
                    @endif
                </td>
                <td style="text-align: center; width:100px;">
                    @if($main->app_hr!=null)
                    {{user_name($main->app_hr)}}<br>
                    @endif
                </td>
            </tr>
            <!--             <tr style="padding-top:3px;">
                <td width="20%" style="padding-left: 20px; font-size: 90%;">
                    {{$main->nama_emp}}<br>
                </td>
                <td width="20%" style="text-align:left; padding-left: 40px;">
                    MANAGEMENT<br>
                </td>
                <td width="20%" style="text-align:right; padding-right:72px">
                    FINANCE<br>
                </td>
            </tr> -->
        </table>
        @endif
        @endif
    </main>
</body>

</html>