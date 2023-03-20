@php
if($main->id_product<>"new"){
    $product = getProductDetail($main->id_product);
    $hmodal = getModalHistory($idp);
    $harga=ProHargaHist($main->id_product);
    $status=ProStatusHist($main->id_product);

    @endphp
    <div class="nav-tabs-responsive bg-light border-top">
        <ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
            <li class="nav-item"><a href="#pinfo" class="nav-link active" data-toggle="tab"><i class="icon-certificate mr-2"></i> Detail Product</a></li>
            <li class="nav-item"><a href="#pcari" class="nav-link" data-toggle="tab"><i class="icon-reading mr-2 text-primary"></i> History Cari Harga</a></li>
            <li class="nav-item"><a href="#pbeli" class="nav-link" data-toggle="tab"><i class="icon-basket mr-2 text-danger"></i> History Beli</a></li>
            <li class="nav-item"><a href="#pharga" class="nav-link" data-toggle="tab"><i class="icon-basket mr-2 text-primary"></i> History Harga</a></li>
            <li class="nav-item"><a href="#pstatus" class="nav-link" data-toggle="tab"><i class="fas fa-tags mr-2 text-primary"></i> History Status</a></li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pinfo">
            <div class="row mt-3">
                <div class="col-lg-6">
                    <h4 class="mb-0 font-weight-semibold">{{$product->name}}</h4>

                    <table class="table table-lg mt-3">
                        <tbody>
                            <tr>
                                <td class="text-left font-weight-bold">SKU</td>
                                <td>{{$product->sku}}</td>
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold">Distri</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold">Stock Gudang</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold">Description</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold">Berat</td>
                                <td>{{number_format($product->weight,2)}}</td>
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold">Dimensi</td>
                                <td>{{number_format($product->length)}}x{{number_format($product->width)}}x{{number_format($product->height)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6">
                    <div class="text-right">
                        <h2 class="mb-0 font-weight-semibold text-danger">Rp. {{number_format($live->price,2)}}</h2>
                        <span class="text-danger">*) Harga Live</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show" style="overflow-x:auto;" id="pcari">
            <div class="row mt-3">
                <table class="table table-striped table-hover m_popup" id="ptable">
                    <thead class="thead-colored bg-teal">
                        <tr class="text-center">
                            <th>Tanggal</th>
                            <th>Vendor</th>
                            <th>Stock</th>
                            <th>Catatan</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php foreach($hmodal as $val) { @endphp
                        <tr>
                            <td>{{$val->created_at}}</td>
                            <td>{!!getVendor($val->id_vendor)->vendor_name!!}</td>
                            <td>{{$val->det_quo_status_vendor}}</td>
                            <td class="text-left">{{$val->det_quo_note}}</td>
                            <td class="text-right">{{number_format($val->det_quo_harga_modal)}}</td>
                        </tr>
                        @php } @endphp
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade show" id="pbeli">
            <table class="table table-striped table-hover m_popup" id="ptable">
                <thead class="thead-colored bg-teal">
                    <tr class="text-center">
                        <th>Harga</th>
                        <th>Vendor</th>
                        <th>Catatan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="tab-pane fade show" id="pharga">
            @include('Product.Live.his_price')
        </div>
        <div class="tab-pane fade show" id="pstatus">
            @include('Product.Live.his_status')
        </div>

    </div>
    @php }else{ @endphp
    <div class="alert bg-danger text-white alert-styled-left alert-dismissible">
        <span class="font-weight-semibold">Ups, Maaf</span>
        Detail Menunggu Team Content Membuat SKU, Detail akan muncul setelah SKU telah di input
    </div>
    @php } @endphp