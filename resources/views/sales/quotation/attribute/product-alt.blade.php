@php
$count = $hitung = $i = 0;
foreach ($product as $val){
$count = $val->id_vendor_beli == "" || $val->id_vendor_beli == null ? $count++ : $i++;
$hitung++;
}
if(Session::get('division_id') == '6' && $hitung = $count){
$allow = "yes";
}else{
$allow = "no";
}

@endphp
<div class="card">
    <div class="card-header alpha-primary text-success-800 border-bottom-success header-elements-inline">
        <h5 class="card-title">Product Detail </h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-10">

                Pada list product ini menggunakan harga <code>exclude</code> agar diperhatikan dengan seksama
            </div>
            <div class="col-lg-2">
                <button type="button" data-id="{{$main->id}}" data-access="{{ Auth::user()->id }}" onclick="EditNewProduct(this)" class="btn btn-danger btn-labeled btn-labeled-left btn-sm legitRipple text-right"><b>
                        <i class="icon-pencil5"></i></b> Edit</button>
            </div>
        </div>
    </div>
    <div id="editform">
        <div class="table-responsive">
            <table class="table table-striped table-hover other_datatable">
                <thead class="thead-colored bg-teal">
                    <tr class="text-center">
                        <th rowspan="2">id_product</th>
                        <th rowspan="2">Barang</th>
                        <th rowspan="2">Qty</th>
                        <th rowspan="2">Qty Order</th>
                        <th colspan="2">Stock</th>
                        <th colspan="3">Harga Per Item</th>
                        <th rowspan="2">Margin Satuan</th>
                        <th rowspan="2">Action</th>
                    </tr>
                    <tr class="text-center">
                        <th>Product</th>
                        <th>Purchasing</th>
                        <th>Order</th>
                        <th>Modal Product</th>
                        <th>Modal Purchasing</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $subtotal_modal = $subtotal_final = $subtotal_order = $subtotal_margin = 0;
                    $margin = 0;
                    foreach ($purchase as $val){
                    ProductToPurchase($val->id_quo_pro);
                    $cname = $val->id_product == "new" ? "<b class='text-danger'>[NEW SKU]</b> ".getProductReq($val->id_product_request)->req_product : "<b class='text-primary'>$val->id_product</b><br> ".getProductDetail($val->id_product)->name;
                    $cprice = $val->id_product == "new" ? getProductReq($val->id_product_request)->req_price : getProductDetail($val->id_product)->price;
                    $oprice = $val->id_product == "new" ? $val->det_quo_harga_req : $val->det_quo_harga_order;
                    $vendor = $val->id_vendor == "" || $val->id_vendor == null ? '': getVendor($val->id_vendor)->vendor_name;
                    $vendor_purc = $val->id_vendor_beli == "" || $val->id_vendor_beli == null ? '': getVendor($val->id_vendor_beli)->vendor_name;
                    @endphp
                    <tr>
                        <td>{{$val->id}}</td>
                        <td onclick="ShowProduct({{$val->id}})">{!!$cname!!}</td>
                        <td class="text-center">@php echo $val->det_quo_qty; @endphp</td>
                        <td class="text-center">@php echo $val->det_quo_qty_beli; @endphp</td>
                        <td class="text-center">
                            @php
                            if($main->quo_type>1){
                            $cstock = StockCheck($val->id_product,$main->id);
                            if($cstock['condition']=='yes'){
                            $cmodal = $cstock['price'];
                            @endphp
                            <p class="font-weight-bold" onclick="ShowVendor({{$val->id_vendor}})">{{$cstock['vendor']}}</p>
                            Ready
                            <p class="text-danger font-italic">Sisa {{$cstock['sisa']}} Unit</p>
                            @php }else{@endphp

                            <p class="font-weight-bold" onclick="ShowVendor({{$val->id_vendor}})">{{$vendor}}</p>
                            @php echo ucfirst($val->det_quo_status_vendor);@endphp
                            <p class="text-danger font-italic">{{$val->det_quo_note}}</p>
                            @php }@endphp


                            @php }else{@endphp
                            <p class="font-weight-bold" onclick="ShowVendor({{$val->id_vendor}})">{{$vendor}}</p>
                            @php echo ucfirst($val->det_quo_status_vendor);@endphp
                            <p class="text-danger font-italic">{{$val->det_quo_note}} ini </p>

                            @php } @endphp
                        </td>
                        <td class="text-center">
                            <p class="font-weight-bold">{{$vendor_purc}}</p>
                            @php echo ucfirst($val->det_quo_status_beli);@endphp
                            <p class="text-danger font-italic">{{$val->det_quo_note_beli}} </p>
                        </td>
                        <td class="text-right">
                            @php echo number_format($oprice); @endphp
                        </td>
                        <td class="text-right">@php echo number_format($val->det_quo_harga_modal); @endphp</td>
                        @php
                        $harga_final = $val->det_quo_harga_final;
                        if($val->det_quo_harga_final == '0' || $val->det_quo_harga_final == NULL){
                        $margin_satuan = 0;
                        $persen_satuan = 0;
                        }else{

                        $margin_satuan = $oprice-$val->det_quo_harga_final;
                        $persen_satuan = $oprice == 0 ? 0 : ($margin_satuan/$oprice)*100;
                        }
                        @endphp
                        <td class="text-right">@php echo number_format($harga_final, 2); @endphp</td>
                        <td class="text-right">
                            @php
                            echo number_format($margin_satuan, 2);
                            echo '<br>';
                            echo '<p class="text-danger">('.round($persen_satuan,2).' %)</p>';
                            @endphp
                        </td>
                        <td class="text-right highlight">
                            @php
                            $nomerpo = getPOdetNew($val->id_product,$val->id_quo,$val->id_vendor_beli);
                            $get = getPO($val->id_quo);
                            if($nomerpo==null){
                            @endphp
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a data-id="{{$main->id}}" data-idpro="{{$val->id_vendor_beli}}" data-toggle="modal" data-target="#m_modal" onclick="ConfirmBeli(this)" class="dropdown-item"><i class="icon-checkmark4 text-primary"></i> Ajukan PO</a>
                                        <a data-id="{{$main->id}}" data-sku="{{$val->id_product}}" onclick="UseStock(this)" class="dropdown-item"><i class="icon-bag text-danger"></i> Use Stock</a>
                                    </div>
                                </div>
                            </div>
                            @php } else { @endphp
                            <div class="btn-group">
                                <button type="button" class="btn btn-danger btn-sm btn-labeled btn-labeled-left legitRipple dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><b><i class="icon-libreoffice"></i></b>{{$nomerpo->po_number}}</button>
                                <div class="dropdown-menu dropdown-menu-right" x-placement="top-end" style="position: absolute; transform: translate3d(-45px, -183px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" onclick="ChangeVendor(this)" data-nomerpo="{{$nomerpo->po_number}}"><i class="icon-menu7"></i> Ganti Vendor</a>
                                    <a data-id="{{$main->id}}" data-sku="{{$val->id_product}}" onclick="UseStock(this)" class="dropdown-item"><i class="icon-bag text-danger"></i> Use Stock</a>
                                    <a class="dropdown-item" onclick="CancelPO(this)" data-nomerpo="{{$nomerpo->po_number}}"><i class="icon-screen-full"></i> Cancel</a>
                                </div>
                            </div>
                            @php } @endphp

                        </td>
                    </tr>
                    @php
                    $subtotal_order += $oprice*$val->det_quo_qty;
                    $subtotal_modal += $val->det_quo_harga_modal*$val->det_quo_qty;
                    $subtotal_final += $val->det_quo_harga_final*$val->det_quo_qty_beli;
                    $subtotal_margin += $margin_satuan*$val->det_quo_qty_beli;
                    }
                    @endphp
                </tbody>
                <tfoot>
                    @php
                    $time_inv = $invoice == null ? '0000-00-00':$invoice->tgl_invoice;
                    $vat = $subtotal_order*(GetPPN($time_inv,$main->quo_order_at)/100);
                    $invoice_price = $subtotal_order+$vat;

                    $vat_final = $subtotal_final*(GetPPN($time_inv,$main->quo_order_at)/100);
                    $final_price = $subtotal_final+$vat_final;

                    @endphp
                    <tr>
                        <td class="text-right" colspan="6"><strong>TOTAL EXCLUDE</strong></td>
                        <td class="text-right">@php echo number_format($subtotal_order); @endphp</td>
                        <td class="text-right">@php echo number_format($subtotal_modal); @endphp</td>
                        <td class="text-right">@php echo number_format($subtotal_final , 2); @endphp</td>
                        <td class="text-right">@php echo number_format($subtotal_margin , 2); @endphp</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="6"><strong>VAT {!!GetPPN($time_inv,$main->quo_order_at)!!}%</strong></td>
                        <td class="text-right">
                            @php
                            echo number_format($vat);
                            @endphp
                        </td>
                        <td class="text-right" colspan="2">
                            @php
                            echo number_format($vat_final);
                            @endphp
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="6"><strong>PRICE</strong></td>
                        <td class="text-right">
                            @php
                            echo number_format($invoice_price);
                            @endphp

                        </td>
                        <td class="text-right" colspan="2">
                            @php
                            echo number_format($final_price , 2);
                            @endphp
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>