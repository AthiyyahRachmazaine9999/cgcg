@extends('layouts.head')
@section('content')
<div class="content">
    <!-- count section  -->
    <div class="row">
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <!-- <h6 class="media-title font-weight-semibold">Total {{ number_format($Cprice, 2) }}</h6> -->
                                <span class="text-danger font-weight-bold">{{$Pcanc}} PO</span><br>
                                <span class="text-muted">Tidak Disetujui Tahun {{\Carbon\Carbon::now()->format('Y')}}</span>
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
                                <!-- <h6 class="media-title font-weight-semibold"> Total {{number_format($Capp,2)}}</h6> -->
                                <span class="text-danger font-weight-bold">{{$Papp}} PO</span><br>
                                <span class="text-muted">Telah Disetujui Tahun {{\Carbon\Carbon::now()->format('Y')}}</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-clipboard2 icon-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <!-- <h6 class="media-title font-weight-semibold">Total {{number_format($PWhwait,2)}}</h6> -->
                                <span class="text-danger font-weight-bold">{{$Whwait}} PO</span><br>
                                <span class="text-muted">Menunggu Konfirmasi Dari Purchase Tahun {{\Carbon\Carbon::now()->format('Y')}}</span>
                            </div>
                            <div class="ml-3 align-self-center">
                                <i class="icon-cloud-upload icon-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <!-- <h6 class="media-title font-weight-semibold">{{number_format($Pord,2)}}</h6> -->
                                <span class="text-danger font-weight-bold">{{$WhIn}} PO Barang</span><br>
                                <span class="text-muted">Yang Sudah Diterima Bulan Ini</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-hour-glass icon-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">List Barang</a></h6><br>
                    <div class="header-elements">
                        <a href="#" class="text-default"><i class="icon-cog3"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="media-list">
                    </ul>
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
                    <h5 class="card-title">Proccess Order</h5>
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
                                <th class="text-center" >Nomer PO </th>
                                <th class="text-center" >Nomer SO</th>
                                <th class="text-center" >Vendor</th>
                                <th class="text-center" >Status</th>
                                <th class="text-center" >Position</th>
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
<script src="{{ asset('ctrl/dashboard/data-warehouse.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/notifikasi.js') }}" type="text/javascript"></script>
@endsection