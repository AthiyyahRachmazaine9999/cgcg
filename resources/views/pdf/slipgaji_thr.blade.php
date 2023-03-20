<!doctype html>
<html>

@include('pdf.style')

<body>

    @include('pdf.header')
    @include('pdf.footer')
    @php

    $ded_basic_salary = explode('males', base64_decode($check->basic_salary))[0];


    @endphp
    <main>
        <table class="title">
            <tr>
                <td width="100%">
                    <h4 class="font-weight-bold text-center" style="color: #ed400c;">
                        PAYMENT SLIP THR
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
                    <td>: {{date('Y', strtotime($check->when.'-01'))}}</td>
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
                    <td class="text-right">{{number_format($ded_basic_salary)}}</td>
                    <td class="pl-8 w-60" scope="row"><strong>BPJS</strong></td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>Allowance</strong></td>
                    <td class="text-right">-</td>
                    <td class="pl-8 w-60" scope="row"><strong>Pensions</strong></td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>BPJS</strong></td>
                    <td class="text-right">-</td>
                    <td class="pl-8 w-60" scope="row"><strong>Tax</strong></td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>Pensions</strong></td>
                    <td class="text-right">-</td>
                    <td class="pl-8 w-60" scope="row"><strong>Other</strong></td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>Tax</strong></td>
                    <td class="text-right">-</td>
                    <td class="pl-8 w-60" scope="row"><strong>Insurance</strong></td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>Insurance</strong></td>
                    <td class="text-right">-</td>
                    <td class="pl-8 w-60" scope="row"><strong>Total Deduction</strong></td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="pl-8 w-60" scope="row"><strong>Gross Salary</strong></td>
                    <td class="text-right">{{number_format($ded_basic_salary)}}</td>
                    <td class="pl-8 w-60" scope="row"><strong>Take Home Pay</strong></td>
                    <td class="text-right">{{number_format($ded_basic_salary)}}</td>
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
                    <td class="text-left">{{ucwords(Terbilang::make($ded_basic_salary)).' Rupiah'}}</td>
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
        <table class="title" style="padding-top: 10px;">
            <tr>
                <td style="min-width: 70%;">
                </td>
               
            </tr>
        </table>

        <table class="title" style="padding-top: 20px;">
            <tr>
                <td style="min-width: 70%;">
                </td>
                <td width="30%">
                    <span>Popy Zaenipurwaningsih</span><BR>
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