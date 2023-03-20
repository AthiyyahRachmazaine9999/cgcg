<div class="table-responsive">
    <table class="table table-lg" style="width:100%">
        <thead>
            <tr>
                <th class="text-left">Product</th>
                <th class="text-center">Qty</th>
                <th>Price</th>
                <th class="text-center">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
            $subtotal_modal = $subtotal_final = $subtotal_order = 0;
            $margin = 0;
            $harga = 0;
            foreach ($product as $val){
            $cname = $val->id_product == "new" ? "<b class='text-danger'>[NEW SKU]</b>
            ".getProductReq($val->id_product_request)->req_product : "<b class='text-primary'>$val->id_product</b><br>
            ".getProductDetail($val->id_product)->name;
            $cprice = $val->id_product == "new" ? getProductReq($val->id_product_request)->req_price :
            getProductDetail($val->id_product)->price;
            $csku = $val->id_product == "new" ? "" : $val->id_product;
            $oprice = $val->id_product == "new" ? $val->det_quo_harga_req : $val->det_quo_harga_order;
            $subbs = $val->id_product == "new" ? $val->det_quo_qty * $val->det_quo_harga_req : $val->det_quo_qty
            *$val->det_quo_harga_order;
            $vendor = $val->id_vendor == "" || $val->id_vendor == null ? '': getVendor($val->id_vendor)->vendor_name;
            $nomerpo = $val->id_vendor_beli!=null && getPOdetNew($val->id_product,$val->id_quo,$val->id_vendor_beli)!=null?
            getPOdetNew($val->id_product,$val->id_quo,$val->id_vendor_beli)->po_number :
            "Stock";
            $vendor_purc = $val->id_vendor_beli == "" || $val->id_vendor_beli == null ? '':
            getVendor($val->id_vendor_beli)->vendor_name;
            @endphp
            <tr>
                <td>
                    {!!$cname!!}
                    <!-- [<a class="text-primary" onclick="showPO(this)" data-id_quo="{{$val->id_quo}}"
                        data-sku="{{$cname}}" data-po_number="{{$nomerpo}}">{!!$nomerpo!!}</a>] -->
                </td>
                <td class="text-center">@php echo $val->det_quo_qty; @endphp</td>
                <td class="text-left">
                    @php echo number_format($oprice); @endphp
                </td>
                <td class="text-center">
                    @php echo number_format($subbs); @endphp
                </td>
            </tr>
            @php
            $subtotal_final += $subbs;
            $harga = getPriceInvoice($val->id_quo==0?$invoice_id->id_quo : $val->id_quo);
            }
            @endphp
        </tbody>
    </table>
</div>
<div class="card-body">
    <div class="d-md-flex flex-md-wrap">
        <div class="pt-2 mb-4 wmin-md-400 ml-auto">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <td class="text-right" colspan="4"><strong>Subtotal</strong></td>
                            <td class="text-right">
                                @php
                                echo number_format($subtotal_final);
                                @endphp

                            </td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="4"><strong>VAT {!!$harga['VAT']!!}%</strong></td>
                            <td class="text-right">
                                @php
                                echo number_format($harga['ppn']);
                                @endphp

                            </td>
                            <td colspan="2"></td>
                        </tr>
                        @if($harga['ongkir']!=0)
                        <tr>
                            <td class="text-right" colspan="4"><strong>Ongkir</strong></td>
                            <td class="text-right">
                                @php
                                echo number_format($harga['ongkir']);
                                @endphp

                            </td>
                            <td colspan="2"></td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-right" colspan="4"><strong>INVOICE PRICE</strong></td>
                            <td class="text-right">
                                @php
                                echo number_format($harga['total']);
                                @endphp
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <legend class=""><b>Payment :</b></legend>
                <div class="table-responsive">
                    <table class="table">
                        @if($cdtl!=0)
                        <tr>
                            <td class="text-right" colspan="4"><strong>Total Payment</strong></td>
                            <td class="text-right">{{number_format(sumInvoicePaid($invoice_id->id))}}</td>
                            <td colspan="2"></td>
                        </tr>
                        @endif
                        @if($coth!=0)
                        @php foreach($inv_oth as $oth) {
                        @endphp
                        <tr>
                            <td class="text-right" colspan="4"><strong>{{$oth->des_potongan}}</strong></td>
                            <td class="text-right">{{number_format($oth->nilai_potongan)}}
                            </td>
                            <td colspan="2"></td>
                        </tr>
                        @php } @endphp
                        @endif
                        @if($invoice_id->file_ntpn_ppn!=null)
                        <tr>
                            <td class="text-right" colspan="4"><strong>NTPN PPn</strong></td>
                            <td class="text-right">{{number_format($invoice_id->potongan_ntpn_ppn)}}
                            </td>
                            <td colspan="2"></td>
                        </tr>
                        @endif
                        @if($invoice_id->file_ntpn_pph!=null)
                        <tr>
                            <td class="text-right" colspan="4"><strong>NTPN PPh</strong></td>
                            <td class="text-right">{{number_format($invoice_id->potongan_ntpn_pph)}}
                            </td>
                            <td colspan="2"></td>
                        </tr>
                        @endif
                        @if($invoice_id->file_ntpn_ppn!=null || $invoice_id->file_ntpn_pph!=null ||
                        $cdtl!=0)
                        <tr>
                            <td class="text-right" colspan="4"><strong>Amount Due</strong></td>
                            <td class="text-right">{{number_format($sisa)}}
                            </td>
                            <td colspan="2"></td>
                        </tr>
                        @endif
                    </table>
                </div>
                @if($cdtl!=0 && $invoice_id->ket_lunas==null)
                <div class="text-right mt-3">
                    <button type="button" class="btn btn-info btn-labeled btn-labeled-left"
                        onclick="Edit_detailPayment(this)" data-type="finish_payment"
                        data-id="{{$invoice_id->id}}"><b><i class="fas fa-check"></i></b>Finish</button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>