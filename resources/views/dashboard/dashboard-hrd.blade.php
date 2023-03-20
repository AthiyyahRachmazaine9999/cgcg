@extends('layouts.head')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-6">
            <div class="row">
                <!-- <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <span class="text-danger font-weight-bold">{{$tdk_setuju}} Request</span><br>
                                <span class="text-muted">Tidak Disetujui Oleh HRD Di Tahun
                                    {{\Carbon\Carbon::now()->format('Y')}}</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-cancel-square2 icon-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <span class="text-danger font-weight-bold">{{$setuju}} Request</span><br>
                                <span class="text-muted">Telah Disetujui Oleh HRD Di Tahun
                                    {{\Carbon\Carbon::now()->format('Y')}}</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-clipboard2 icon-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <span class="text-danger font-weight-bold">{{$cleave}} Request Leave</span><br>
                                <span class="text-muted">Pending, Menunggu Approval</span>
                            </div>
                            <div class="ml-3 align-self-center">
                                <i class="icon-cloud-upload icon-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <span class="text-danger font-weight-bold">{{$ctravel}} Request Overtime</span><br>
                                <span class="text-muted">Pending, Menunggu Approval</span>
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
    <!-- table section  -->
    <!-- <div class="row">
        <div class="col-lg-6">
            <div class="card card-body">
                <h6>Employees Info</h6>
                <div class="media">
                    <div class="media-body border border-light border col-lg-6">
                        <div class="border border-secondary border col-lg-12">
                            <br><i class="fa fa-check-double fa-2x text-primary"></i><br><br>
                            <p>Kehadiran Hari Ini</p>
                            <p>50 Employee</p>
                        </div> <br>
                    </div>
                    <div class="media-body">
                        <div class="border border-secondary border col-lg-12">
                            <br><i class="fa fa-clock fa-2x text-primary"></i><br><br>
                            <p>Telat Datang Hari Ini</p>
                            <p>50 Employee</p>
                        </div>
                    </div>
                </div>
                <div class="media">
                    <div class="media-body border border-light border col-lg-6">
                        <div class="border border-secondary border col-lg-12">
                            <br><i class="fa fa-check-double fa-2x text-primary"></i><br><br>
                            <p>Total Karyawan</p>
                            <p>50 Employee</p>
                        </div> <br>
                    </div>
                    <div class="media-body">
                        <div class="border border-secondary border col-lg-12">
                            <br><i class="fa fa-clock fa-2x text-primary"></i><br><br>
                            <p>Menunggu Persetujuan</p>
                            <p>50 Employee</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-body">
                <div class="media">
                    <div class="media-body">
                        <div id="charthr" class="" style="width:100%; height:100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header alpha-primary text-success-800 border-bottom-success header-elements-inline">
                    <h5 class="card-title">List Request</h5>
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

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="cash">
                            <table class="table table-bordered table-striped table-hover m_datatable">
                                <thead>
                                    <tr class="bg-slate-800">
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Tujuan</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Diajukan Oleh</th>
                                        <th class="text-center">Tanggal</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="{{ asset('ctrl/dashboard/data-charthrd.js') }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/dashboard/data-hrd.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/notifikasi.js') }}" type="text/javascript"></script>
@endsection