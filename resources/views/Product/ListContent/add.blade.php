@extends('layouts.head')
@section('content')
<div class="content">
    <div class="mb-3 border-top-1 border-top-primary">
        <div class="page-header page-header-light">
            <div class="page-header-content">
                <div class="page-title">
                    <h2>
                        <span class="font-weight-semibold">Detail</span> - Page
                        <small class="text-muted">List Product</small>
                    </h2>
                </div>

                <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                    <div class="d-flex">
                        <div class="breadcrumb">
                            <a href="{{ url('product/content/listcontent') }}" class="breadcrumb-item"><i
                                    class="icon-home2 mr-2"></i> Back</a>

                        </div>

                        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                    </div>

                    <div class="header-elements d-none">
                        <div class="breadcrumb justify-content-center">
                            <h3><span class="badge badge-info">{{$list->pro_status}}</span>
                            </h3>

                        </div>
                    </div>
                </div>
            </div>
            <!--Section: Block Content-->

            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div id="mdb-lightbox-ui"></div>
                    <div class="mdb-lightbox">
                        <div class="row product-gallery mx-5">
                            <div class="col-12 mb-0">
                                <figure class="view overlay rounded z-depth-1 main-img">
                                    <a href="{{ Storage::url($list->pro_image) }}" data-size="710x823">
                                        <img src="{{Storage::url($list->pro_image) }}" class="img-fluid z-depth-1">
                                    </a>
                                </figure>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="col-md-6">

                    <h2>{{$list->pro_name}}</h2>
                    <h3>
                        <li class="list-inline-item">{{$list->pro_sku}}</li>

                    </h3>
                    <h2 class="mb-0 font-weight-semibold text-primary">Rp.{{ number_format($price->price_retail, 3) }}
                    </h2>
                    <p class="pt-1">
                        <h6>Overview</h6>Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, sapiente
                        illo. Sit
                        error voluptas repellat rerum quidem, soluta enim perferendis voluptates laboriosam. Distinctio,
                        officia quis dolore quos sapiente tempore alias.
                    </p>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="pl-0 w-25" scope="row"><strong>Produsen</strong></th>
                                    <td>PT. ABC Indonesia</td>
                                </tr>
                                <tr>
                                    <th class="pl-0 w-25" scope="row"><strong>Stock</strong></th>
                                    <td>24 Unit</td>
                                </tr>
                                <tr>
                                    <th class="pl-0 w-25" scope="row"><strong>Delivery</strong></th>
                                    <td>Jakarta, Indonesia</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Button -->

                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
                        <li class="nav-item"><a href="#overview" class="nav-link active" data-toggle="tab"><i
                                    class="fa fa-list-ul">More Details</i></a></li>
                        <li class="nav-item"><a href="#historypb" class="nav-link" data-toggle="tab"><i
                                    class="fa fa-credit-card">History Pembelian</i></a></li>
                        <li class="nav-item"><a href="#historypr" class="nav-link" data-toggle="tab"><i
                                    class="fa fa-sticky-note">History Harga</i></a></li>
                    </ul>
                    <!-- SPESIFIKASI/DESKRIPSI -->
                    <div id="tabsContent" class="tab-content">
                        <div id="overview" class="tab-pane fade active show">

                            <div class="text-left border-bottom" style="padding-left:20px">
                                <h4>Other Spesifications</h4>
                            </div>
                            <dl class="row" style=" padding: 25px 50px 75px 100px">
                                <dt class="col-sm-3">Product Terkait</dt>
                                <dd class="col-sm-9"><a href="#">{{$list->tag}}</a></dd>
                                <dt class="col-sm-3">Date Available</dt>
                                <dd class="col-sm-9">{{$list->date_available}}</dd>
                                <dt class="col-sm-3">Meta Title</dt>
                                <dd class="col-sm-9">{{$list->meta_title}}</dd>
                                <dt class="col-sm-3">Warranty</dt>
                                <dd class="col-sm-9">1 Year</dd>
                                <dt class="col-sm-3">Dimensi (T x P x L)</dt>
                                <dd class="col-sm-9">159 x 435 x 380 mm</dd>
                            </dl>



                        </div>
                        <!-- HISTORY PRICE -->
                        <div id="historypb" class="tab-pane fade" style="overflow-x:auto;">
                            <table id="dtBasicExample1"
                                class="table m_datatable table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Perusahaan</th>
                                        <th>Date</th>
                                        <th>Date Updated</th>
                                        <th>Keterangan</th>
                                    </tr>
                                <tbody>
                                    <tr>
                                        <th>PT. ABC</th>
                                        <td>{{ number_format($price->price_retail, 3) }}</td>
                                        <td>1 Month Ago</td>
                                        <td>{{$list->date_modified}}</td>
                                    </tr>
                                    <tr>
                                        <th>PT. ABC</th>
                                        <td>{{ number_format($price->price_retail, 3) }}</td>
                                        <td>2 Hours Ago</td>
                                        <td>{{$list->date_modified}}</td>
                                    </tr>
                                    <tr>
                                        <th>PT. ABC</th>
                                        <td>{{ number_format($price->price_retail, 3) }}</td>
                                        <td>2 Hours Ago</td>
                                        <td>{{$list->date_modified}}</td>
                                    </tr>
                                </tbody>
                                </thead>
                            </table>
                        </div>

                        <!-- HISTORY PRICE -->
                        <div id="historypr" class="tab-pane fade" style="overflow-x:auto;">
                            <table id="dtBasicExample"
                                class="table m_datatable table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Date Updated</th>
                                        <th>Keterangan</th>
                                    </tr>
                                <tbody>
                                    <tr>
                                        <th>{{$list->pro_name}}</th>
                                        <td>New Category</td>
                                        <td>{{ number_format($price->price_retail, 3) }}</td>
                                        <td>1 Month Ago</td>
                                        <td>{{$list->date_modified}}</td>
                                    </tr>
                                    <th>{{$list->pro_name}}</th>
                                    <td>New Category</td>
                                    <td>{{ number_format($price->price_retail, 3) }}</td>
                                    <td>1 Month Ago</td>
                                    <td>{{$list->date_modified}}</td>
                                    </tr>
                                    </tr>
                                    <th>{{$list->pro_name}}</th>
                                    <td>New Category</td>
                                    <td>{{ number_format($price->price_retail, 3) }}</td>
                                    <td>1 Month Ago</td>
                                    <td>{{$list->date_modified}}</td>
                                    </tr>
                                </tbody>
                                </thead>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        $('#dtBasicExample').DataTable();
        $('.dataTables_length').addClass('bs-select');
        $('#dtBasicExample1').DataTable();
        $('.dataTables_length').addClass('bs-select');
    });

</script>
@endsection
