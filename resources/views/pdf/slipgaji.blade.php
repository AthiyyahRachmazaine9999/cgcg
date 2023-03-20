<!doctype html>
<html>

@include('pdf.style')

<body>

    @include('pdf.header')
    @include('pdf.footer')
    @php
    $basic_salary  = explode('males', base64_decode($data->basic_salary))[0];
    $allowance     = explode('males', base64_decode($data->allowance))[0];
    $pension       = explode('males', base64_decode($data->pension))[0];
    $bpjs          = explode('males', base64_decode($data->bpjs))[0];

    $ded_basic_salary = explode('males', base64_decode($check->basic_salary))[0];
    $ded_allowance    = explode('males', base64_decode($check->allowance))[0];
    $ded_other        = explode('males', base64_decode($check->ded_other))[0];
    $ded_tax          = explode('males', base64_decode($check->ded_tax))[0];
    $ded_insurance    = explode('males', base64_decode($check->ded_insurance))[0];
    $overtime         = explode('males', base64_decode($check->overtime))[0];
    $ded_bpjs         = explode('males', base64_decode($check->ded_bpjs))[0];
    $ded_pension      = explode('males', base64_decode($check->ded_pension))[0];

    $use_bpjs    = $ded_bpjs  == $bpjs ? $bpjs : $ded_bpjs;
    $use_pension = $ded_pension  == $pension ? $pension : $ded_pension;

    $use_basic_salary = $ded_basic_salary  == $basic_salary ? $basic_salary : $ded_basic_salary;
    $use_allowance    = $ded_allowance  == $allowance ? $allowance : $ded_allowance;

    $gross     = $use_basic_salary+$use_allowance+$use_pension+$use_bpjs+$ded_tax+$overtime+(float)$ded_insurance;
    $gross_ded = (float)$use_bpjs+(float)$use_pension+(float)$ded_tax+(float)$ded_other+(float)$ded_insurance;
    $thp       = $gross-$gross_ded;


    @endphp
    <main>
        <table class="title">
            <tr>
                <td width="100%">
                    <h4 class="font-weight-bold text-center" style="color: #ed400c;">
                        PAYMENT SLIP
                    </h4>
                </td>
            </tr>
        </table>
        <table id="slipheader" style="width: 100%;">
            <colgroup>
                <col span="1" style="width: 50%;">
                <col span="1" style="width: 50%;">
            </colgroup>
            <tbody>
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>Employee Name</strong></td>
                    <td>: {{$data->emp_name}}</td>
                    <td class="pl-8 w-60" scope="row"><strong>Salary Month of</strong></td>
                    <td>: {{date('F Y', strtotime($check->when.'-01'))}}</td>
                </tr>
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>Employee ID</strong></td>
                    <td>: {{$data->emp_nip}}</td>
                    <td class="pl-8 w-60" scope="row"><strong>Position</strong></td>
                    <td>: {{$data->position}}</td>
                </tr>
                <tr>
                    @php
                    $mystatus = getEmpStatus($data->status_employee);
                    @endphp
                    <td class="pl-8 w-60" scope="row"><strong>Marital Status</strong></td>
                    <td>: {{$mystatus->code.' - '.$mystatus->note}} </td>
                    <td class="pl-8 w-60" scope="row"><strong>Department</strong></td>
                    <td>: {{$data->division_name}}</td>
                </tr>
            </tbody>
        </table>
        <table id="slip" style="width: 100%; padding-top: 30px;">
            <colgroup>
                <col span="1" style="width: 50%;">
                <col span="1" style="width: 50%;">
            </colgroup>
            <tbody>
                <!-- detail gaji  -->
                <tr>
                    <td colspan="2" class="pl-8 w-60" scope="row"><strong>SALARY DETAIL</strong></td>
                    <td colspan="2" class="pl-8 w-60" scope="row"><strong>DEDUCTION</strong></td>
                </tr>
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>Basic Salary</strong></td>
                    <td class="text-right">{{number_format($use_basic_salary)}}</td>
                    <td class="pl-8 w-60" scope="row"><strong>BPJS</strong></td>
                    <td class="text-right">{{number_format($use_bpjs)}}</td>
                </tr>
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>Allowance</strong></td>
                    <td class="text-right">{{number_format($use_allowance)}}</td>
                    <td class="pl-8 w-60" scope="row"><strong>Pensions</strong></td>
                    <td class="text-right">{{number_format($use_pension)}}</td>
                </tr>
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>BPJS</strong></td>
                    <td class="text-right">{{number_format($use_bpjs)}}</td>
                    <td class="pl-8 w-60" scope="row"><strong>Tax</strong></td>
                    <td class="text-right">{{number_format((float)$ded_tax)}}</td>
                </tr>
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>Pensions</strong></td>
                    <td class="text-right">{{number_format($use_pension)}}</td>
                    <td class="pl-8 w-60" scope="row"><strong>Other</strong></td>
                    <td class="text-right">{{number_format((float)$ded_other)}}</td>
                </tr>
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>Tax</strong></td>
                    <td class="text-right">{{number_format((float)$ded_tax)}}</td>
                    <td class="pl-8 w-60" scope="row"><strong>Insurance</strong></td>
                    <td class="text-right">{{number_format((float)$ded_insurance)}}</td>
                </tr>
                @if($check->overtime!==null or $overtime!==0)
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>Overtime</strong></td>
                    <td class="text-right">{{number_format($overtime)}}</td>
                    <td class="pl-8 w-60" scope="row"><strong></strong></td>
                    <td class="text-right"></td>
                </tr>
                @endif
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>Insurance</strong></td>
                    <td class="text-right">{{number_format((float)$ded_insurance)}}</td>
                    <td class="pl-8 w-60" scope="row"><strong>Total Deduction</strong></td>
                    <td class="text-right">{{number_format($gross_ded)}}</td>
                </tr>
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>Gross Salary</strong></td>
                    <td class="text-right">{{number_format($gross)}}</td>
                    <td class="pl-8 w-60" scope="row"><strong>Take Home Pay</strong></td>
                    <td class="text-right">{{number_format($thp)}}</td>
                </tr>

            </tbody>
        </table>
        <table id="slip" style="width: 100%; padding-top: 20px;">
            <colgroup>
                <col span="1" style="width: 100%;">
            </colgroup>
            <tbody>
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>Say</strong></td>
                </tr>
                <tr>
                    <td class="text-left">{{ucwords(Terbilang::make($thp)).' Rupiah'}}</td>
                </tr>
            </tbody>
        </table>
        <table class="title">
            <tr>
                <td style="min-width: 70%;">
                </td>
                <td width="30%">
                    Authorized By
                    
                </td>
            </tr>
        </table>
        <table class="title" style="padding-top: 3px;">
            <tr>
                <td style="min-width: 70%;">
                </td>
                <td width="30%">
                    @php
                    $text = "Yes,It's Valid Payment Slip ".date('F Y', strtotime($check->when.'-01'))." ".$data->emp_name." authorized by Atikah Nur Utami";
                    @endphp
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($text)) !!} ">
                </td>
            </tr>
        </table>

        <table class="title" style="padding-top: 5px;">
            <tr>
                <td style="min-width: 70%;">
                </td>
                <td width="30%">
                    <span>Atikah Nur Utami</span><BR>
                    <span>HR & GA Support</span>
                </td>
            </tr>
        </table>
        <table class="title">
            <tr>
                <td width="100%">
                    *) This document is computer generated and does not require signature
                </td>
            </tr>
        </table>
        <table class="title">
            <tr>
                <td width="100%">
                    <p class="font-weight-bold text-center">Generated on {!!\Carbon\Carbon::now()->format('d-m-Y');!!}</p> 
                </td>
            </tr>
        </table>
    </main>
</body>

</html>