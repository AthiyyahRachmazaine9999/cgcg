<select name="idpro_new" class="form-control id_product" id="id_product">

    <option value="">Silahkan Pilih Product</option>
    @php foreach($main as $val){
    $look = getProductDetail($val->id_product);
    @endphp
    <option value="{{$val->id}}">@php echo $look->sku.' - '.$look->name; @endphp</option>
    @php } @endphp
</select>