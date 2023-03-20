@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Seach -->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h3 class="card-title">Product Page</h3>
            <div class="header-elements">
            </div>
        </div>
        <div class="card-header">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5 mb-4 mb-md-0 ml-0">
                        <div class="mdb-lightbox">
                            <div class="row product-gallery">
                                <div class="col-12 mb-0">
                                    <figure class="view overlay rounded main-img">
                                    
                                    <a href="{{asset('storage/post-image/'.$live->image) }}">
                                    <img src="{{asset('storage/post-image/'.$live->image) }}" class="img-fluid">
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h2>{{$desc->name}}</h2>
                        <h5>
                            <li class="list-inline-item">{{$live->model}}</li>
                        </h5>
                         <h2 class="mb-0 font-weight-semibold text-primary">
                          Rp.{{ number_format($live->price,2) }}</h2> <br>

                        <h5 class="mb-0 font-weight">
                          Rp. {{ number_format($hitung,2) }}</h5>
                        <p class="pt-1">
                            <h6>Overview</h6>
                            <p>
                                {!!$desc->pro_overview!!}
                            </p>
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="pl-0 w-25" scope="row"><strong>Manufacture</strong></th>
                                    <td>{{$man}}</td>
                                </tr>
                                <tr>
                                    <th class="pl-0 w-25" scope="row"><strong>No. SKU</strong></th>
                                    <td>{{$live->sku}}</td>
                                </tr>
                                <tr>
                                    <th class="pl-0 w-25" scope="row"><strong>Category</strong></th>
                                    <td>{{$cat}}</td>
                                </tr>

                            </tbody>
                        </table>

                        <!-- Button -->
                        <div class="card-header text-right">
                            <a href="#">
                                <button type="button" name="approve" style="margin-bottom:10px"
                                    class="btn btn-outline bg-indigo-400 text-indigo-400 border-indigo-400"><i
                                        class="fas fa-file"></i>Lihat Di LKPP</button></a>
                            <div class="text-right"><a href="#">
                                    <button type="button" name="approve"
                                        class="btn btn-outline bg-indigo-400 text-indigo-400 border-indigo-400"><i
                                            class="fas fa-file"></i>Lihat Retail</button></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
                            <li class="nav-item"><a href="#description" class="nav-link active" data-toggle="tab"><i
                                        class="icon-menu7 mr-2"></i>Descriptions</a></li>
                            <li class="nav-item"><a href="#" class="nav-link" data-toggle="tab"><i
                                        class="icon-menu7 mr-2"></i>History Pembelian</a></li>
                            <li class="nav-item"><a href="#historypr" class="nav-link" data-toggle="tab"><i
                                        class="icon-menu7 mr-2"></i>History Harga</a></li>

                        </ul>
                        <!-- SPESIFIKASI/DESKRIPSI -->
                        <div id="tabsContent" class="tab-content">
                        <div id="description" class="tab-pane fade active show" style="overflow-x:auto;">
                            <h5>Additional Information</h5>
                            <table class="table table-striped table-bordered mt-3">
                                <thead>
                                <tr>
                                    <th scope="row" class="w-150 dark-grey-text h6">Weight</th>
                                    <td><em>{{$live->weight}} {{$we}}</em></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th scope="row" class="w-150 dark-grey-text h6">Dimensions</th>
                                    <td><em>{{round($live->length)}} X {{round($live->width)}} X {{round($live->height)}} {{$length}}</em></td>
                                </tr>
                                </tbody>
                                <tbody>
                                <tr>
                                    <th scope="row" class="w-150 dark-grey-text h6">Others</th>
                                    <td><em>{!!$text!!}</em></td>
                                </tr>
                                </tbody>
                            </table>
                            </div>

                            <!-- HISTORY PEMBELIAN -->
                            <div id="historypb" class="tab-pane fade" style="overflow-x:auto;">
                                <table id="dtBasicExample1"
                                    class="table m_datatable table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama Perusahaan</th>
                                            <th>Price</th>
                                            <th>Date Updated</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    <tbody>
                                        <tr>
                                            <th>PT. ABC</th>
                                            <td>{{ number_format($live->price, 3) }}</td>
                                            <td>1 Month Ago</td>
                                            <td>{{$live->date_modified}}</td>
                                        </tr>
                                        <tr>
                                            <th>PT. ABC</th>
                                            <td>{{ number_format($live->price, 3) }}</td>
                                            <td>2 Hours Ago</td>
                                            <td>{{$live->date_modified}}</td>
                                        </tr>
                                        <tr>
                                            <th>PT. ABC</th>
                                            <td>{{ number_format($live->price, 3) }}</td>
                                            <td>2 Hours Ago</td>
                                            <td>{{$live->date_modified}}</td>
                                        </tr>
                                    </tbody>
                                    </thead>
                                </table>
                            </div>

                            <!-- HISTORY PRICE -->
                            <div id="historypr" class="tab-pane fade" style="overflow-x:auto;">
                                @include('Product.Live.his_price')
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
<script src="{{ asset('ctrl/sales/quotation-detail.js') }}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('table.display').DataTable();
} );
</script>
@endsection
