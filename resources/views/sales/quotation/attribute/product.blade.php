<div class="card">
    <div class="card-header alpha-primary text-success-800 border-bottom-success header-elements-inline">
        <h5 class="card-title">Product Detail</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-10">

                Pada list product ini menggunakan harga <code>Exclude</code> agar diperhatikan dengan seksama
            </div>
            <div class="col-lg-2">

                <button type="button" data-id="{{$main->id}}" data-access="{{ Auth::user()->id }}" onclick="EditNewProduct(this)" class="btn btn-danger btn-labeled btn-labeled-left btn-sm legitRipple text-right"><b>
                        <i class="icon-pencil5"></i></b> Edit</button>

            </div>
        </div>
    </div>

    <div id="editform">
        <table class="table table-striped table-hover m_datatable" id="ptable">
            <thead class="thead-colored bg-teal">
                <tr class="text-center">
                    <th rowspan="2">id_product</th>
                    <th rowspan="2">Barang</th>
                    <th rowspan="2">Status Stock</th>
                    <th rowspan="2">Qty</th>
                    <th colspan="5">Harga Per Item</th>
                </tr>
                <tr class="text-center">
                    <th>Live</th>
                    <th>Order</th>
                    <th>Modal</th>
                    <th>Ongkir</th>
                    <th>Margin</th>
                </tr>
            </thead>
            <tbody>
                @php
                $subtotal_modal = $subtotal_ongkir = $subtotal_order = 0;
                $margin = 0;
                foreach ($product as $val){
                $cname = $val->id_product == "new" ? "<b class='text-danger'>[NEW SKU]</b> ".getProductReq($val->id_product_request)->req_product : "<b class='text-primary'>$val->id_product</b><br> ".getProductDetail($val->id_product)->name;
                $cprice = $val->id_product == "new" ? getProductReq($val->id_product_request)->req_price : getProductDetail($val->id_product)->price;
                $oprice = $val->id_product == "new" ? $val->det_quo_harga_req : $val->det_quo_harga_order;
                $vendor = $val->id_vendor == "" || $val->id_vendor == null ? '': getVendor($val->id_vendor)->vendor_name;
                @endphp
                <tr>
                    <td>{{$val->id}}</td>
                    <td onclick="ShowProduct({{$val->id}})">{!!$cname!!}</td>
                    <td class="text-center">
                        @php
                        if($main->quo_type>0){
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
                        <p class="text-danger font-italic">{{$val->det_quo_note}} </p>

                        @php } @endphp
                    </td>
                    <td class="text-center">@php echo $val->det_quo_qty; @endphp</td>
                    <td class="text-right">{{number_format($cprice)}}</td>
                    <td class="text-right">
                        @php echo number_format($oprice); @endphp
                    </td>
                    <td class="text-right">@php echo number_format($val->det_quo_harga_modal); @endphp</td>
                    <td class="text-right">@php echo number_format($val->det_quo_harga_ongkir); @endphp</td>
                    <td class="text-right">
                        @php
                        if($val->det_quo_harga_modal == '0' || $val->det_quo_harga_modal == NULL){
                        $one_margin = 0;
                        }else{
                        $one_margin = ($oprice*$val->det_quo_qty) - ($val->det_quo_harga_modal*$val->det_quo_qty);
                        }


                        echo number_format($one_margin); @endphp

                    </td>
                </tr>
                @php
                $subtotal_order += $oprice*$val->det_quo_qty;
                $subtotal_modal += $val->det_quo_harga_modal*$val->det_quo_qty;
                $subtotal_ongkir += $val->det_quo_harga_ongkir*$val->det_quo_qty;
                $margin += $one_margin;
                }
                @endphp
            </tbody>
            @php 
            if(getUserEmp(Auth::id())->division_id=='7' or Auth::id() == '13'){
            @endphp

            @include('sales.quotation.attribute.product-allow')
            @php }else{ @endphp

                
            @include('sales.quotation.attribute.product-allownot')

            @php } @endphp

    </div>
</div>