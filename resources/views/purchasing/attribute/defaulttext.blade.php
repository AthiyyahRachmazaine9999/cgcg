@php
$sum = 0;
foreach($price as $val){
$sum += $val->qty*$val->price;
}
if($po->isppn=="yes") {
$newfinal = $sum+($sum*(GetPPN($po->created_at,$po->created_at)/100));
}else{
$newfinal = $sum;
}
@endphp
<p style="margin-right: 0px; margin-bottom: 9px; margin-left: 0px; font-family: &quot;Lucida Grande&quot;, Helvetica, Verdana, Arial, sans-serif;">Dear <b>{{$text->vendor_name}}</b>,</p>
<p style="margin-right: 0px; margin-bottom: 9px; margin-left: 0px; font-family: &quot;Lucida Grande&quot;, Helvetica, Verdana, Arial, sans-serif;">Berikut kami sampaikan&nbsp;<strong style="font-weight: bold;">{{$po->po_number}}</strong>&nbsp;senilai&nbsp;<strong style="font-weight: bold;">Rp&nbsp;{{number_format($newfinal)}}</strong>&nbsp;dari PT. MITRA ERA GLOBAL.</p>
<p style="margin-right: 0px; margin-bottom: 9px; margin-left: 0px; font-family: &quot;Lucida Grande&quot;, Helvetica, Verdana, Arial, sans-serif;">Jika ada pertanyaan, silahkan mereply email ini</p>
<p style="margin-right: 0px; margin-bottom: 9px; margin-left: 0px; font-family: &quot;Lucida Grande&quot;, Helvetica, Verdana, Arial, sans-serif;">Terima Kasih,</p>