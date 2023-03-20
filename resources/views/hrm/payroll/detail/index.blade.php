@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card" style="overflow-x:auto;">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Salary Detail in month</h6>
        </div>

        <div class="card-body" id="tabs">
            <ul class="nav nav-tabs nav-tabs-solid nav-justified rounded bg-light">
                @php
                for($i=1;$i<=12;$i++){ if($i==date('n')){$isact="active" ;}else{$isact="" ;} @endphp <li class="nav-item"><a href="#solid-rounded-bordered-tab1" class="nav-link {{$isact}}" data-toggle="tab">{{ date("M", mktime(0, 0, 0, $i, 1)) }}</a></li>
                    @php } @endphp
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="solid-rounded-bordered-tab1">
                    List ini hanya berisikan detail gaji user yang  <code>aktif dan master gaji telah terisi</code>. Silahkan click update generate, untuk menambahkan detail orang lain

                    @if(session()->has('success'))
                    <div class="alert alert-success alert-styled-left alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                        {{ session()->get('success') }}
                    </div>
                    @endif


                    @if(session()->has('error'))
                    <div class="alert alert-danger alert-styled-left alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                        {{ session()->get('error') }}
                    </div>
                    @endif

                    <table class="table m_detailtable table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Division</th>
                                <th>Position</th>
                                <th>Rek. BCA </th>
                                <th>Gross Salary</th>
                                <th>Deduction</th>
                                <th>Take Home Pay</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('sales.quotation.attribute.modal')
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/payroll-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection