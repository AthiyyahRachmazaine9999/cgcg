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
        </div>
    </div>
    <table class="table table-striped table-hover other_datatable" style="width:100%" id="ptable">
        <thead class="thead-colored bg-teal">
            <tr class="text-center">
                <th rowspan="2">id_product</th>
                <th rowspan="2">Barang</th>
                <th rowspan="2">Qty</th>
                <th rowspan="2">Stock Product</th>
                <th colspan="3">Harga Per Item</th>

            </tr>
            <tr class="text-center">
                <th>Live</th>
                <th>Order</th>
                <th>Modal</th>
            </tr>
        </thead>
        <tbody>
            @php
            $subtotal_modal = $subtotal_final = $subtotal_order = 0;
            $margin = 0;
            foreach ($product as $val){
            $cname = $val->id_product == "new" ? "<b class='text-danger'>[NEW SKU]</b> ".getProductReq($val->id_product_request)->req_product : "<b class='text-primary'>$val->id_product</b><br> ".getProductDetail($val->id_product)->name;
            $cprice = $val->id_product == "new" ? getProductReq($val->id_product_request)->req_price : getProductDetail($val->id_product)->price;
            $csku = $val->id_product == "new" ? "" : $val->id_product;
            $oprice = $val->id_product == "new" ? $val->det_quo_harga_req : $val->det_quo_harga_order;
            $vendor = $val->id_vendor == "" || $val->id_vendor == null ? '': getVendor($val->id_vendor)->vendor_name;
            $vendor_purc = $val->id_vendor_beli == "" || $val->id_vendor_beli == null ? '': getVendor($val->id_vendor_beli)->vendor_name;
            @endphp
            <tr>
                <td>{{$val->id}}</td>
                <td onclick="ShowProduct({{$val->id}})"><b>{!!$csku!!}</b><br> {!!$cname!!}</td>
                <td class="text-center">@php echo $val->det_quo_qty; @endphp</td>
                <td class="text-center">
                    <p class="font-weight-bold">{{$vendor}}</p>
                    @php echo ucfirst($val->det_quo_status_vendor);@endphp
                    <p class="text-danger font-italic">{{$val->det_quo_note}} </p>
                </td>
                <td class="text-right">{{number_format($cprice)}}</td>
                <td class="text-right">
                    @php echo number_format($oprice); @endphp
                </td>
                <td class="text-right">@php echo number_format($val->det_quo_harga_modal); @endphp</td>
            </tr>
            @php
            }
            @endphp
        </tbody>
    </table>
</div>