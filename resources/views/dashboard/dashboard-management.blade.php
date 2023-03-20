@extends('layouts.head')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12" id="dash_mng" data-type="mng">
            <div class="row" id="ini_dash_mng">
            </div>
        </div>
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h6 class="card-title">Sales Info Tahun {{\Carbon\Carbon::now()->format('Y')}}</h6>

                            <div class="header-elements">
                                <a href="#" class="text-default"><i class="icon-cog3"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <span class="">Total Sales Order</span>
                                    <h3 class="media-title font-weight-bold text-danger">Rp.
                                        {{number_format($sum_year)}}
                                    </h3>
                                </div>
                            </div> <br>
                            <div class="media-body">
                                <span class="">Total Sales Order Masih Negosiasi</span>
                                <h3 class="media-title font-weight-bold text-danger">Rp.
                                    {{number_format($sum_nego)}}
                                </h3>
                            </div><br>
                            <div class="media-body">
                                <span class="">Total Sales Order Batal</span>
                                <h3 class="media-title font-weight-bold text-danger">Rp.
                                    {{number_format($sum_batal)}}
                                </h3>
                            </div> <br>
                            <div class="media-body">
                                <span class="">Total Invoicing Sales Order</span>
                                <h3 class="media-title font-weight-bold text-danger">Rp.
                                    {{number_format($sum_yclose)}}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h6 class="card-title">Sales Info Bulan {{\Carbon\Carbon::now()->format('F')}}</h6>

                            <div class="header-elements">
                                <a href="#" class="text-default"><i class="icon-cog3"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <span class="">Total Sales Order</span>
                                    <h3 class="media-title font-weight-bold text-danger">Rp.
                                        {{number_format($sum_month)}}
                                    </h3>
                                </div>
                            </div> <br>
                            <div class="media-body">
                                <span class="">Total Sales Order Masih Negosiasi</span>
                                <h3 class="media-title font-weight-bold text-danger">Rp.
                                    {{number_format($sum_mnego)}}
                                </h3>
                            </div> <br>
                            <div class="media-body">
                                <span class="">Total Sales Order Batal</span>
                                <h3 class="media-title font-weight-bold text-danger">Rp.
                                    {{number_format($sum_mbatal)}}
                                </h3>
                            </div> <br>
                            <div class="media-body">
                                <span class="">Total Closing Sales Order</span>
                                <h3 class="media-title font-weight-bold text-danger">Rp.
                                    {{number_format($sum_mclose)}}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title" align="center">Info Penjualan Per-Sales</h4>
                    <canvas id="mataChart" class="chartjs" width="20%" height="10%"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title" align="center">Info Sales Order / Paket</h4>
                    <canvas id="so_so" class="chartjs" width="40%" height="20%"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title" align="center">Info Penjualan Per-Bulan</h4>
                    <canvas id="so" class="chartjs" width="40%" height="20%"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endsection
    @section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script src="{{ asset('ctrl/dashboard/data-chart.js') }}" type="text/javascript"></script>
    <script src="{{ asset('ctrl/dashboard/notifikasi.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>
    @endsection