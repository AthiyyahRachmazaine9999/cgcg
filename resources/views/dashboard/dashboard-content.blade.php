@extends('layouts.head')
@section('content')
<div class="content">
    <!-- notif section  -->
    <!-- end notif section  -->
    <!-- count section  -->
    <div class="row">
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h6 class="media-title font-weight-semibold">{{$skup}} Produk</h6>
                                <span class="text-muted">Content Baru Status <em>Pending</em> Tahun {{\Carbon\Carbon::now()->format('Y')}}</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-stack2 icon-3x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h6 class="media-title font-weight-semibold">{{$Lcontent}} SKU</h6>
                                <span class="text-muted">Content Yang Telah Tayang Tahun {{\Carbon\Carbon::now()->format('Y')}}</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-cloud-upload icon-3x text-success"></i>
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
                                <h6 class="media-title font-weight-semibold">{{$active}} SKU</h6>
                                <span class="text-muted">Produk Tayang Status Inactive Tahun {{\Carbon\Carbon::now()->format('Y')}}</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-cloud-download icon-3x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h6 class="media-title font-weight-semibold">{{$Plist}} SKU</h6>
                                <span class="text-muted">Content Yang Batal Tayang Tahun {{\Carbon\Carbon::now()->format('Y')}}</span>
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
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Product Info</h6>

                    <div class="header-elements">
                        <a href="#" class="text-default"><i class="icon-cog3"></i></a>
                    </div>
                </div>

                <div class="card-body">
                    <ul class="media-list">
                        <li class="media">
                            <div class="mr-3 position-relative">
                                <h3 class="text-danger font-weight-bold">{{$Inact}}</h3>
                            </div>

                            <div class="media-body">
                                <div class="d-flex justify-content-between">
                                    <div class="media-title text-danger font-weight-bold">PRODUCT</div>
                                    <span class="font-size-sm text-muted"></span>
                                </div>
                                ACTIVE
                            </div>
                        </li>
                        <li class="media">
                            <div class="mr-3 position-relative">
                                <h3 class="text-danger font-weight-bold">{{$newlist}}</h3>
                            </div>

                            <div class="media-body">
                                <div class="d-flex justify-content-between">
                                    <div class="media-title text-danger font-weight-bold">PRODUCT</div>
                                    <span class="font-size-sm text-muted"></span>
                                </div>
                                BARU HARI INI
                            </div>
                        </li>
                        <li class="media">
                            <div class="mr-3 position-relative">
                                <h3 class="text-danger font-weight-bold">{{$appw}}</h3>
                            </div>

                            <div class="media-body">
                                <div class="d-flex justify-content-between">
                                    <div class="media-title text-danger font-weight-bold">PRODUCT</div>
                                    <span class="font-size-sm text-muted"></span>
                                </div>
                                MENUNGGU APPROVAL
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h6 class="media-title font-weight-semibold">{{$brand}} Brand</h6><br>
                                <span class="text-muted">Tersedia Saat Ini</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-dropbox icon-3x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h6 class="media-title font-weight-semibold">{{$soreq}} SKU</h6>
                                <span class="text-muted">Request Dari SO Tayang Bulan Ini</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-basket icon-3x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">List Produk Tayang</h6>
                        <a href="#" class="text-default"><i class="icon-cog3"></i></a>
                    </div>
                    <div class="col-12">
                                <!-- <h4 class="text-uppercase fw-weight-bold mb-0">Wednesday</h4>
                                <p class="text-gray fst-italic mb-0">05 December 2020</p>                     -->
                </div>
                <div class="header-elements">
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
                    <h5 class="card-title">Input Request</h5>
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
                                <th class="text-center">ID</th>
                                <th class="text-center">Nomer</th>
                                <th class="text-center">Nama Paket</th>
                                <th class="text-center">Barang</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Perkiraan Harga</th>
                                <th class="text-center">Request By</th>
                                <th class="text-center">Created At</th>
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
<script src="{{ asset('ctrl/dashboard/data-content.js') }}" type="text/javascript"></script>
@endsection