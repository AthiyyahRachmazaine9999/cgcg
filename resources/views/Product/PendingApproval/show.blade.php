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
                                        <a href="{{asset('storage/post-image/'.$app->pro_image) }}">
                                            <img src="{{asset('storage/post-image/'.$app->pro_image) }}" class="img-fluid">
                                        </a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h2>{{$app->pro_name}}</h2>
                        <h5>
                            <li class="list-inline-item">{{$man}}</li>
                        </h5>
                        <h2 class="mb-0 font-weight-semibold text-primary">
                            Rp.{{ number_format($app->pro_price, 3)}}</h2>
                        <p class="pt-1">
                            <h6>Overview</h6>
                            <p>
                                {!!$app->pro_desc!!}
                        </p>
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="pl-0 w-25" scope="row"><strong>Manufacture</strong></th>
                                    <td>{{$man}}</td>
                                </tr>
                                <tr>
                                    <th class="pl-0 w-25" scope="row"><strong>Category</strong></th>
                                    <td class="w-50">{{$cat}}</td>
                                </tr>
                                <tr>
                                    @if($app->pro_status=="Pending")
                                    <th class="pl-0 w-25" scope="row"><strong>Approval Status</strong></th>
                                    <td class="text-danger"><em>{{$app->pro_status}}</em></td>
                                    @elseif($app->pro_status=="Waiting")
                                    <th class="pl-0 w-25" scope="row"><strong>Approval Status</strong></th>
                                    <td class="text-danger"><em>{{$app->pro_status}}</em></td>
                                    @else
                                    <th class="pl-0 w-25" scope="row"><strong>Approval Status</strong></th>
                                    <td class="text-primary"><em>{{$app->pro_status}}</em></td>
                                    @endif
                                </tr>

                            </tbody>
                        </table>

                        <!-- Button -->
                        <div class="card-header text-right">
                            <a href="{{route('approval.approve', $app->pro_id)}}">
                                <button type="button" name="approve" {{$info['BtnApp']}} Style="margin-bottom:10px"
                                    class="btn btn-outline bg-indigo-400 text-indigo-400 border-indigo-400"><i
                                        class="fas fa-file"></i>APPROVE</button></a>
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
                                    <td><em>{{$app->pro_weight}} {{$we}}</em></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th scope="row" class="w-150 dark-grey-text h6">Dimensions</th>
                                    <td><em>{{$arr[0]}} X {{$arr[1]}} X {{$arr[2]}} {{$length}}</em></td>
                                </tr>
                                </tbody>
                                <tbody>
                                <tr>
                                    <th scope="row" class="w-150 dark-grey-text h6">Others</th>
                                    <td><em>{!!$app->pro_spec!!}</em></td>
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
                                            <td>{{ number_format($app->pro_price, 3) }}</td>
                                            <td>1 Month Ago</td>
                                            <td>{{$app->date_modified}}</td>
                                        </tr>
                                        <tr>
                                            <th>PT. ABC</th>
                                            <td>{{ number_format($app->pro_price, 3) }}</td>
                                            <td>2 Hours Ago</td>
                                            <td>{{$app->date_modified}}</td>
                                        </tr>
                                        <tr>
                                            <th>PT. ABC</th>
                                            <td>{{ number_format($app->pro_price, 3) }}</td>
                                            <td>2 Hours Ago</td>
                                            <td>{{$app->date_modified}}</td>
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
    $(document).ready(function () {
        $('table.display').DataTable();
    });

</script>
@endsection
