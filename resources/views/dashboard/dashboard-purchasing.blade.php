@extends('layouts.head')
@section('content')
<div class="content">
    <!-- count section  -->
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-body">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a href="#card-bulan" class="nav-link active"
                                    data-toggle="tab">Bulan</a></li>
                            <li class="nav-item"><a href="#card-tahun" class="nav-link" data-toggle="tab">Tahun</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="card-bulan">
                                <div class="media">
                                    <div class="media-body">
                                        <h6 class="media-title font-weight-semibold">Rp.
                                            {{ number_format($Mpr_reject, 2) }}
                                        </h6>
                                        <span class="text-danger font-weight-bold">{{$Mco_reject}} Order</span><br>
                                        <span class="text-muted">Tidak Disetujui Bulan
                                            {{\Carbon\Carbon::now()->format('F')}}</span>
                                    </div>
                                    <div class="ml-3 align-self-center">
                                        <i class="icon-cancel-square2 icon-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="card-tahun">
                                <div class="media">
                                    <div class="media-body">
                                        <h6 class="media-title font-weight-semibold">Rp.
                                            {{number_format($Ypr_reject, 2) }}
                                        </h6>
                                        <span class="text-danger font-weight-bold">{{$Yco_reject}} Order</span><br>
                                        <span class="text-muted">Tidak Disetujui Tahun
                                            {{\Carbon\Carbon::now()->format('Y')}}</span>
                                    </div>
                                    <div class="ml-3 align-self-center">
                                        <i class="icon-cancel-square2 icon-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-body">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a href="#card-appbln" class="nav-link active"
                                    data-toggle="tab">Bulan</a></li>
                            <li class="nav-item"><a href="#card-appthn" class="nav-link" data-toggle="tab">Tahun</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="card-appbln">
                                <div class="media">
                                    <div class="media-body">
                                        <h6 class="media-title font-weight-semibold">
                                            Rp. {{ number_format($Mpr_approve, 2) }}
                                        </h6>
                                        <span class="text-danger font-weight-bold">{{$Mco_approve}} Order</span><br>
                                        <span class="text-muted">Telah Disetujui Bulan
                                            {{\Carbon\Carbon::now()->format('F')}}</span>
                                    </div>
                                    <div class="ml-3 align-self-center">
                                        <i class="icon-clipboard2 icon-2x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="card-appthn">
                                <div class="media">
                                    <div class="media-body">
                                        <h6 class="media-title font-weight-semibold">Rp.
                                            {{number_format($Ypr_approve, 2) }}
                                        </h6>
                                        <span class="text-danger font-weight-bold">{{$Yco_approve}} Order</span><br>
                                        <span class="text-muted">Telah Disetujui Tahun
                                            {{\Carbon\Carbon::now()->format('Y')}}</span>
                                    </div>
                                    <div class="ml-3 align-self-center">
                                        <i class="icon-clipboard2 icon-2x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <span class="text-danger font-weight-bold">{{$PO_today}} PO</span><br>
                                <br> <span class="text-muted">Masuk Hari Ini</span>
                            </div>
                            <div class="ml-3 align-self-center">
                                <i class="icon-cloud-upload icon-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <span class="text-danger font-weight-bold">{{$POdraft_today}} PO</span><br>
                                <br><span class="text-muted">Status Masih Draft Hari Ini</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-hour-glass icon-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end count section  -->
    <!-- table section  -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header alpha-primary text-success-800 border-bottom-success header-elements-inline">
                    <h5 class="card-title">PO draft</h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(session()->has('success'))
                    <div class="alert alert-success alert-styled-left alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                        {{ session()->get('success') }}
                    </div>
                    @endif

                    <table class="table table-bordered table-striped table-hover m_datatable">
                        <thead>
                            <tr class="bg-slate-800">
                                <th class="text-center" >ID </th>
                                <th class="text-center" >SO </th>
                                <th class="text-center" >Nomer </th>
                                <th class="text-center" >Nama Paket</th>
                                <th class="text-center" >Jumlah Barang</th>
                                <th class="text-center" >Sales</th>
                                <th class="text-center" >Created By</th>
                                <th class="text-center" >Tanggal Order</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <!-- end table section  -->
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/dashboard/data-purchasing.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('js/notifikasi.js') }}" type="text/javascript"></script>
@endsection