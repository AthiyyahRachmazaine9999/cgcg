<tfoot>
    @php

    $nego_ongkir = $price->ongkir_customer == null ? '-': number_format($price->ongkir_customer);
    if($main->quo_type == 1){
    $time_inv = $main->created_at;
    }else{
    $time_inv = $invoice == null ? '0000-00-00':$invoice->tgl_invoice;
    }
    $times = $main->quo_type == 1 ? $main->created_at:$main->quo_order_at;
    $vat = $subtotal_order*(GetPPN($time_inv,$times)/100);
    $invoice_price = $subtotal_order+$vat;

    $finalongkir = $subtotal_ongkir-$price->ongkir_customer;


    $pph = ($subtotal_order*1.5)/100;
    $sp2d = $subtotal_order-$pph;

    $price_if = $price->price_if_type == 'percen' ? ($sp2d*$price->price_if)/100 : $price->price_if;
    $cof = $margin*1/100;
    $otherprice = $price->price_other+$price_if+$finalongkir+$cof;
    $submargin = $margin - $otherprice;
    $margin_dimuka = $sp2d-$subtotal_modal-$otherprice;

    $vatmodal = $subtotal_modal*(GetPPN($time_inv,$times)/100);
    $incmodal = $subtotal_modal+$vatmodal;
    $selisihp = $vat-$vatmodal;
    $restitusi = ($vatmodal*80)/100;
    $fmargin = $sp2d-$incmodal-$selisihp+$restitusi-$otherprice;


    $sp_margin = $sp2d-$incmodal+$restitusi;
    $sp_margin_b2b = $invoice_price-$incmodal-$selisihp-$otherprice


    @endphp
    <tr>
        <td class="text-right" colspan="5"><strong>TOTAL EXCLUDE</strong></td>
        <td class="text-right">@php echo number_format($subtotal_order); @endphp</td>
        <td class="text-right">@php echo number_format($subtotal_modal); @endphp</td>
        <td class="text-right">@php echo number_format($subtotal_ongkir); @endphp</td>
        <td class="text-right">@php echo number_format($margin); @endphp</td>
    </tr>
    <tr>
        <td class="text-right" colspan="5"><strong>VAT {!!GetPPN($time_inv,$times)!!}%</strong></td>
        <td class="text-right">
            @php
            echo number_format($vat);
            @endphp

        </td>
        <td class="text-right">
            @php
            echo number_format($vatmodal);
            @endphp

        </td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td class="text-right" colspan="5"><strong>INVOICE PRICE</strong></td>
        <td class="text-right">
            @php
            echo number_format($invoice_price);
            @endphp

        </td>
        <td class="text-right">
            @php
            echo number_format($incmodal);
            @endphp

        </td>
        <td class="text-right text-danger"><strong>GROSS MARGIN</strong></td>
        <td class="text-right">
            <h5 class="text-danger">
                <strong>
                    @php
                    $grossm = ($margin/$invoice_price)*100;
                    echo round($grossm,2).' %';
                    @endphp
                </strong>
            </h5>
        </td>
    </tr>

    <tr>
        <td class="text-center bg-slate-300" colspan="9"><strong>PROJECT COSTING</strong></td>
    </tr>

    <tr>
        <td class="text-right" colspan="8"><strong>ONGKIR BY CUSTOMER</strong></td>
        <td class="text-right">
            @php
            echo $nego_ongkir ;
            @endphp
        </td>
    </tr>
    <tr>
        <td class="text-right" colspan="8"><strong>IF</strong></td>
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
    <tr>
        <td class="text-right" colspan="8"><strong>COF</strong></td>
        <td class="text-right">
            @php
            echo number_format($cof);
            @endphp
        </td>
    </tr>
    <tr>
        <td class="text-right" colspan="8"><strong>TOTAL BIAYA</strong></td>
        <td class="text-right">
            @php
            echo number_format($otherprice);
            @endphp
        </td>
    </tr>
    <tr>
        <td class="text-center bg-slate-300" colspan="9"><strong>PERHITUNGAN PERPAJAKAN</strong></td>
    </tr>
    @if($main->quo_type=='5')
    <tr>
        <td class="text-right" colspan="8"><strong>SELISIH PAJAK</strong></td>
        <td class="text-right">
            @php
            echo number_format($selisihp);
            @endphp

        </td>
    </tr>

    <tr>
        <td class="text-right" colspan="8"><strong>SUBTOTAL MARGIN</strong></td>
        <td class="text-right">
            @php
            echo number_format($sp_margin_b2b);
            @endphp

        </td>
    </tr>
    <tr>
        <td class="text-right text-danger" colspan="8"><strong>SP MARGIN</strong></td>
        <td class="text-right">
            <h5 class="text-danger">
                <strong>
                    @php
                    echo round(($sp_margin_b2b/$invoice_price)*100,2).' %';
                    @endphp
                </strong>
            </h5>
        </td>
    </tr>
    @else
    <tr>
        <td class="text-right" colspan="8"><strong>PPH</strong></td>
        <td class="text-right">
            @php
            echo number_format($pph);
            @endphp

        </td>
    </tr>
    <tr>
        <td class="text-right" colspan="8"><strong>SP2D</strong></td>
        <td class="text-right">
            @php
            echo number_format($sp2d);
            @endphp

        </td>
    </tr>
    <tr>
        <td class="text-right" colspan="8"><strong>PPN RESTITUSI</strong></td>
        <td class="text-right">
            @php
            echo number_format($restitusi);
            @endphp

        </td>
    </tr>
    <tr>
        <td class="text-right" colspan="8"><strong>MARGIN DIMUKA</strong></td>
        <td class="text-right">
            @php
            echo number_format($margin_dimuka);
            @endphp

        </td>
    </tr>
    <tr>
        <td class="text-right" colspan="8"><strong>SUBTOTAL MARGIN</strong></td>
        <td class="text-right">
            @php
            echo number_format($sp_margin);
            @endphp

        </td>
    </tr>
    <tr>
        <td class="text-right text-danger" colspan="8"><strong>SP MARGIN</strong></td>
        <td class="text-right">
            <h5 class="text-danger">
                <strong>
                    @php
                    echo round(($sp_margin/$invoice_price)*100,2).' %';
                    @endphp
                </strong>
            </h5>
        </td>
    </tr>
    @endif

    @if($main->quo_type<>'5')
        <tr>
            <td class="text-center bg-slate-300" colspan="9"><strong>MARGIN AKHIR</strong></td>
        </tr>
        <tr>
            <td class="text-right" colspan="8"><strong>NET MARGIN</strong></td>
            <td class="text-right">
                @php
                $net_margin = $sp_margin-$otherprice;
                echo number_format($net_margin);
                @endphp

            </td>
        </tr>
        <tr>
            <td class="text-right text-danger" colspan="8"><strong>% NET MARGIN</strong></td>
            <td class="text-right">
                <h5 class="text-danger">
                    <strong>
                        @php
                        echo round((($net_margin)/$invoice_price)*100,2). " %";
                        @endphp
                    </strong>
                </h5>
            </td>
        </tr>

        @endif
        <tr>
            <td class="text-center bg-slate-300" colspan="9"><strong>POTONGAN KOMISI</strong></td>
        </tr>
        <tr>
            <td class="text-right" colspan="8"><strong>KOMISI A (2,2%)</strong></td>
            <td class="text-right">
                @php
                if($main->quo_type<>'5'){
                    $komisia = ($net_margin*2.2)/100;
                    $komisib = ($net_margin*2.6)/100;

                    }else{
                    $komisia = ($sp_margin_b2b*2.2)/100;
                    $komisib = ($sp_margin_b2b*2.6)/100;
                    }
                    echo number_format($komisia);
                    @endphp
            </td>
        </tr>
        <tr>
            <td class="text-right" colspan="8"><strong>KOMISI B (2,6%)</strong></td>
            <td class="text-right">
                @php
                echo number_format($komisib);
                @endphp
            </td>
        </tr>
        <tr>
            <td class="text-right " colspan="8"><strong>FINAL MARGIN</strong></td>
            <td class="text-right">
                @php
                if($main->quo_type<>'5'){
                    $final_newmargin = $net_margin-$komisia-$komisib;
                    }else{
                    $final_newmargin = $sp_margin_b2b-$komisia-$komisib;
                    }
                    echo number_format($final_newmargin);
                    @endphp
            </td>
        </tr>
        <tr>
            <td class="text-right text-danger" colspan="8"><strong>% FINAL MARGIN</strong></td>
            <td class="text-right">
                <h5 class="text-danger">
                    <strong>
                        @php
                        echo round(($final_newmargin/$invoice_price)*100,2).' %';
                        @endphp
                    </strong>
                </h5>
            </td>
        </tr>
</tfoot>

</table>
<div class="card-footer bg-white text-right">
    @php if($grossm >= getConfig('automargin')){ @endphp
    <h5 class="text-success"><b><i class="icon-checkmark4"></i></b> Auto Approve</h5>
    @php } else{ @endphp
    <h5 class="text-warning"><b><i class="icon-alert"></i></b> Waiting Approval</h5>
    @php }@endphp
    @php if($main->quo_approve_status=='approve'){ @endphp
    <h5 class="text-success"><b><i class="icon-checkmark4"></i></b> Approved</h5>
    <button type="button" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" data-type="reject" onclick="ShowApproval(this)" class="btn btn-danger btn-labeled btn-labeled-left legitRipple"><b><i class="icon-cancel-circle2"></i></b> Reject</button>
    @php }else if($main->quo_approve_status=='reject'){ @endphp
    <h5 class="text-danger"><b><i class="icon-cancel-circle2"></i></b> Rejected</h5>
    <button type="button" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" data-type="approve" onclick="ShowApproval(this)" class="btn btn-primary btn-labeled btn-labeled-left legitRipple"><b><i class="icon-checkmark4"></i></b> Approve</button>
    @php }else if($main->quo_approve_status==null){@endphp
    <button type="button" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" data-type="reject" onclick="ShowApproval(this)" class="btn btn-danger btn-labeled btn-labeled-left legitRipple"><b><i class="icon-cancel-circle2"></i></b> Reject</button>
    <button type="button" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" data-type="approve" onclick="ShowApproval(this)" class="btn btn-primary btn-labeled btn-labeled-left legitRipple"><b><i class="icon-checkmark4"></i></b> Approve</button>
    @php }@endphp
</div>