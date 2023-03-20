@extends('layouts.head')
@section('content')
<div class="content">
    <!-- count section  -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-body bg-primary-600 has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <span class="text-white">Total Penjualan Masih Aktif Tahun
                            {{\Carbon\Carbon::now()->format('Y')}}</span><br>
                        <span class="text-white">Ada {{$akt_pktyear}} Sales Order / Paket</span><br>
                        <br>
                        <h3 class="text-white font-weight-bold">Rp. {{number_format($akt_year->sm,2)}}</h3>
                    </div>
                    <div class="ml-3 align-self-center">
                        <i class="fa fa-bell fa-3x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-body">
                <div class="media">
                    <div class="media-body">
                        <span class="">Total Closing Penjualan Tahun
                            {{\Carbon\Carbon::now()->format('Y')}}</span><br>
                        <span class="">Ada {{$close_pktyear}} Sales Order / Paket</span><br>
                        <br>
                        <h3 class="text-danger font-weight-bold">Rp. {{number_format($close_priceyear,2)}}</h3>
                    </div>
                    <div class="ml-3 align-self-center">
                        <i class="fa fa-check-double fa-3x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h6 class="media-title font-weight-semibold">{{ number_format($cprice, 2) }}</h6>
                                <span class="text-danger font-weight-bold">{{$cancel}} Order</span><br>
                                <span class="text-muted">Order Batal Tahun {{\Carbon\Carbon::now()->format('Y')}}</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-cancel-square2 icon-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h6 class="media-title font-weight-semibold">{{number_format($SumOr,2)}}</h6>
                                <span class="text-danger font-weight-bold">{{$orMonth}} Order</span><br>
                                <span class="text-muted">Order Masuk Bulan {{\Carbon\Carbon::now()->format('F')}}</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-clipboard2 icon-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <!-- <h6 class="media-title font-weight-semibold">9,000,000,000</h6> -->
                                <h6 class="text-success font-weight-bold">{{$app}} Order</h6>
                                <span class="text-muted">Telah Disetujui Tahun
                                    {{\Carbon\Carbon::now()->format('Y')}}</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-hour-glass icon-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h6 class="text-danger font-weight-bold">{{$rej}} Order</h6>
                                <span class="text-muted">Tidak Disetujui Tahun
                                    {{\Carbon\Carbon::now()->format('Y')}}</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="fa fa-ban fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="col-lg-6">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Request New SKU</a></h6><br>
                    <div class="header-elements">
                        <a href="#" class="text-default"><i class="icon-cog3"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="media-list">
                        @php foreach($salesAct as $det) { @endphp
                        <li class="media">
                            <div class="mr-3 position-relative">
                                <h3 class="text-danger font-weight-bold">
                                    {{\Carbon\Carbon::parse($det->created_at)->format('d-m-Y')}}</h3>
                            </div>

                            <div class="media-body">
                                <div class="d-flex justify-content-between">
                                    <div class="media-title text-danger font-weight-bold">{{$det->quo_name}}</div>
                                </div>
                                <span
                                    class="font-size-sm text-muted">{{getProductReq($det->id_product_request)->req_product}}</span>
                                <br>
                            </div>
                        </li>
                        @php } @endphp
                    </ul>
                </div>
            </div>
        </div>
    </div> -->
    <!-- end count section  -->
    <!-- table section  -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header alpha-primary text-success-800 border-bottom-success header-elements-inline">
                    <h5 class="card-title">Recent Order & RFQ</h5>
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
                                <th class="text-center" rowspan="2">ID </th>
                                <th class="text-center" rowspan="2">Type</th>
                                <th class="text-center" rowspan="2">Nama Paket</th>
                                <th class="text-center" colspan="2">PIC</th>
                                <th class="text-center" colspan="2">Tanggal</th>
                                <th class="text-center" rowspan="2">Status</th>
                                <th class="text-center" rowspan="2">Total</th>
                            </tr>
                            <tr class="bg-slate-800">
                                <th class="text-center">Admin</th>
                                <th class="text-center">Sales</th>
                                <th class="text-center">Order</th>
                                <th class="text-center">Update</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end table section  -->
@endsection
@section('script')
<script src="{{ asset('ctrl/dashboard/data-sales.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/notifikasi.js') }}" type="text/javascript"></script>
@endsection