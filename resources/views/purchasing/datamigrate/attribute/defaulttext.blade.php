@php 
$final = $po->price+($po->price/10);
$sum   = 0;
foreach($price as $val){
    $sum += $val->qty*$val->price;
}
$newfinal = $sum+($sum/10);
@endphp
<p style="margin-right: 0px; margin-bottom: 9px; margin-left: 0px; font-family: &quot;Lucida Grande&quot;, Helvetica, Verdana, Arial, sans-serif;">Dear <b>{{$text->vendor_name}}</b>,</p>
<p style="margin-right: 0px; margin-bottom: 9px; margin-left: 0px; font-family: &quot;Lucida Grande&quot;, Helvetica, Verdana, Arial, sans-serif;">Please find in attachment a&nbsp;<strong style="font-weight: bold;">purchase order confirmation {{$po->po_number}}</strong>&nbsp;amounting&nbsp;<strong style="font-weight: bold;">Rp&nbsp;{{number_format($newfinal)}}</strong>&nbsp;from PT. MITRA ERA GLOBAL.</p>
<p style="margin-right: 0px; margin-bottom: 9px; margin-left: 0px; font-family: &quot;Lucida Grande&quot;, Helvetica, Verdana, Arial, sans-serif;">You can reply to this email if you have any questions.</p>
<p style="margin-right: 0px; margin-bottom: 9px; margin-left: 0px; font-family: &quot;Lucida Grande&quot;, Helvetica, Verdana, Arial, sans-serif;">Thank you,</p>