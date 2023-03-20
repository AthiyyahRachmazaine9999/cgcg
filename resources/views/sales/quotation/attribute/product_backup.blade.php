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
                <button type="button" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" data-access="{{ Auth::user()->id }}" onclick="EditProduct(this)" class="btn btn-danger btn-labeled btn-labeled-left btn-sm legitRipple text-right"><b>
                        <i class="icon-pencil5"></i></b> Edit</button>
            </div>
        </div>
    </div>
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
            $cname = $val->id_product == "new" ? "<b class='text-danger'>[NEW SKU]</b> ".getProductReq($val->id_product_request)->req_product : getProductDetail($val->id_product)->name;
            $cprice = $val->id_product == "new" ? getProductReq($val->id_product_request)->req_price : getProductDetail($val->id_product)->price;
            $oprice = $val->id_product == "new" ? $val->det_quo_harga_req : $val->det_quo_harga_order;
            $vendor = $val->id_vendor == "" || $val->id_vendor == null ? '': getVendor($val->id_vendor)->vendor_name;
            @endphp
            <tr>
                <td>{{$val->id}}</td>
                <td>{!!$cname!!}</td>
                <td class="text-center">
                    <p class="font-weight-bold">{{$vendor}}</p>
                    @php echo ucfirst($val->det_quo_status_vendor);@endphp
                    <p class="text-danger font-italic">{{$val->det_quo_note}} </p>
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
        <tfoot>
            @php

            $vat = $subtotal_order/10;
            $nego_ongkir = $price->ongkir_customer == null ? '-': number_format($price->ongkir_customer);
            $invoice_price = $subtotal_order+$vat;

            $finalongkir = $subtotal_ongkir-$price->ongkir_customer;


            $pph = ($subtotal_order*1.5)/100;
            $sp2d = $subtotal_order-$pph;

            $price_if = $price->price_if_type == 'percen' ? ($sp2d*$price->price_if)/100 : $price->price_if;
            $otherprice = $price->price_other+$price_if+$finalongkir;
            $submargin = $margin - $otherprice;

            $vatmodal = $subtotal_modal/10;
            $incmodal = $subtotal_modal+$vatmodal;
            $selisihp = $vat-$vatmodal;
            $restitusi = ($vatmodal*80)/100;
            $fmargin = $sp2d-$incmodal-$selisihp+$restitusi-$otherprice;

            if($subtotal_modal == 0 || $subtotal_modal == NULL){
            $fpercent = 0;
            }else{
            $ftpercent = ($fmargin/$invoice_price)*100;
            $fpercent = round($ftpercent,2)." %";
            }

            $komisia = ($fmargin*2.2)/100;
            $komisib = ($fmargin*2.8)/100;

            $finalmargin = $fmargin-$komisia-$komisib;

            if($subtotal_modal == 0 || $subtotal_modal == NULL){
            $Lpercent = 0;
            }else{
            $Ltpercent = ($finalmargin/$invoice_price)*100;
            $Lpercent = round($Ltpercent,2)." %";
            }

            @endphp
            <tr>
                <td class="text-right" colspan="5"><strong>TOTAL EXCLUDE</strong></td>
                <td class="text-right">@php echo number_format($subtotal_order); @endphp</td>
                <td class="text-right">@php echo number_format($subtotal_modal); @endphp</td>
                <td class="text-right">@php echo number_format($subtotal_ongkir); @endphp</td>
                <td class="text-right">@php echo number_format($margin); @endphp</td>
            </tr>
            <tr>
                <td class="text-right" colspan="5"><strong>VAT 10%</strong></td>
                <td class="text-right">
                    @php
                    echo number_format($vat);
                    @endphp

                </td>
                <td class="text-right" colspan="2"><strong>ONGKIR BY CUSTOMER</strong></td>
                <td class="text-right">
                    @php
                    echo $nego_ongkir ;
                    @endphp
                </td>
            </tr>
            <tr>
                <td class="text-right" colspan="5"><strong>INVOICE PRICE</strong></td>
                <td class="text-right">
                    @php
                    echo number_format($invoice_price);
                    @endphp

                </td>
                <td class="text-right" colspan="2"><strong>IF</strong></td>
                <td class="text-right">
                    @php
                    echo number_format($price_if);
                    @endphp
                </td>
            </tr>
            <tr>
                <td class="text-right" colspan="8"><strong>LAIN LAIN</strong></td>
                <td class="text-right">
                    @php
                    echo number_format($price->price_other);
                    @endphp
                </td>
            </tr>
            @php if (in_array($main->quo_type , explode(',',getConfig('typemargin')))) { @endphp
            <tr>
                <td class="text-right" colspan="8"><strong>SUBTOTAL MARGIN</strong></td>
                <td class="text-right">
                    @php
                    echo number_format($submargin);
                    @endphp
                </td>
            </tr>
            <tr>
                <td class="text-right" colspan="8"><strong>% MARGIN</strong></td>
                <td class="text-right">
                    <h5 class="text-danger">
                        <strong>
                            @php
                            if($subtotal_modal == 0 || $subtotal_modal == NULL){
                            $percent = 0;
                            }else{
                            $percent = ($submargin/$subtotal_modal)*100;
                            echo round($percent,2);
                            }
                            @endphp
                        </strong>
                    </h5>
                </td>
            </tr>
            @php } @endphp
            <tr>
                <td class="text-center bg-slate-300" colspan="9"><strong>PERHITUNGAN PERPAJAKAN</strong></td>
            </tr>
            <tr>
                <td class="text-right" colspan="5"><strong>PPH</strong></td>
                <td class="text-right">
                    @php
                    echo number_format($pph);
                    @endphp

                </td>
                <td class="text-right" colspan="2">
                    <strong>KOMISI A (2,2%)</strong>
                </td>
                <td class="text-right">
                    @php
                    echo number_format($komisia);
                    @endphp
                </td>
            </tr>
            <tr>
                <td class="text-right" colspan="5"><strong>SP2D</strong></td>
                <td class="text-right">
                    @php
                    echo number_format($sp2d);
                    @endphp

                </td>
                <td class="text-right" colspan="2">
                    <strong>KOMISI B (2,8%)</strong>
                </td>
                <td class="text-right">
                    @php
                    echo number_format($komisib);
                    @endphp
                </td>
            </tr>
            <tr>
                <td class="text-right" colspan="5"><strong>SELISIH PAJAK</strong></td>
                <td class="text-right">
                    @php
                    echo number_format($selisihp);
                    @endphp

                </td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td class="text-right" colspan="5"><strong>PPN RESTITUSI</strong></td>
                <td class="text-right">
                    @php
                    echo number_format($restitusi);
                    @endphp

                </td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td class="text-right" colspan="5"><strong>NET MARGIN</strong></td>
                <td class="text-right">
                    @php
                    echo number_format($fmargin);
                    @endphp

                </td>
                <td class="text-right" colspan="2">
                    <strong>FINAL MARGIN</strong>
                </td>
                <td class="text-right">
                    @php
                    echo number_format($finalmargin);
                    @endphp
                </td>
            </tr>
            <tr>
                <td class="text-right" colspan="5"><strong>% NET MARGIN</strong></td>
                <td class="text-right">
                    <h5 class="text-danger">
                        <strong>
                            @php
                            echo $fpercent." %";
                            @endphp
                        </strong>
                    </h5>
                </td>

                <td class="text-right" colspan="2">
                    <strong>% FINAL MARGIN</strong>
                </td>
                <td class="text-right">
                    <h5 class="text-danger">
                        <strong>
                            @php
                            echo $Lpercent;
                            @endphp
                        </strong>
                    </h5>
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="card-footer bg-white text-right">
        @php if($fpercent >= getConfig('automargin')){ @endphp
        <h5 class="text-success"><b><i class="icon-checkmark4"></i></b> Auto Approve</h5>
        @php } @endphp
        @php if($main->quo_approve_status=='approve'){ @endphp
        <h5 class="text-success"><b><i class="icon-checkmark4"></i></b> Approved</h5>
        <button type="button" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" data-type="reject" onclick="ShowApproval(this)" class="btn btn-danger btn-labeled btn-labeled-left legitRipple"><b><i class="icon-cancel-circle2"></i></b> Reject</button>
        @php }else if($main->quo_approve_status=='reject'){ @endphp
        <h5 class="text-danger"><b><i class="icon-cancel-circle2"></i></b> Rejected</h5>
        <button type="button" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" data-type="approve" onclick="ShowApproval(this)" class="btn btn-primary btn-labeled btn-labeled-left legitRipple"><b><i class="icon-checkmark4"></i></b> Approve</button>
        @php }else{ @endphp
        <h5 class="text-warning"><b><i class="icon-alert"></i></b> Waiting Approval</h5>
        @php }@endphp
    </div>
</div>