@extends('layouts.head')
@section('content')
<!-- Horizontal form -->
<div class="content">
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Update Content</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('live.update', $live->product_id)}}" enctype="multipart/form-data" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">
        {!! Form::hidden('pro_id',$pro_id,['id'=>'pro_id','class'=>'form-control']) !!}
        {!! Form::hidden('product_id',$live->product_id,['id'=>'product_id','class'=>'form-control']) !!}
        @if($price==null ? null : $price->type_harga=="Harga Normal")
        {!! Form::hidden('hidPrice',$price->price_retail,['id'=>'hidPrice','class'=>'form-control']) !!}
        @else
        {!! Form::hidden('hidPrice',$catalog,['id'=>'hidPrice','class'=>'form-control']) !!}
        @endif
        {!! Form::hidden('hidstatus',$live->status,['id'=>'hidstatus','class'=>'form-control']) !!}
        @if($list==null ? null : $list->id_quo && $list->pro_request_id)
        {!! Form::hidden('id_quo',$list->id_quo,['id'=>'id_quo','class'=>'form-control']) !!}
        {!! Form::hidden('pro_request_id',$list->pro_request_id,['id'=>'pro_request_id','class'=>'form-control']) !!}
        @endif
        {!! Form::hidden('desc_atr',$marks,['id'=>'marks_other','class'=>'form-control']) !!}
        {!! Form::hidden('atr_id',$atr_id,['id'=>'atr_id','class'=>'form-control']) !!}
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>No. SKU</label>
                 <div class="col-lg-7">
                     <input type="text" name="sku" value="{{ $live->sku}}" class="form-control" readonly>
                </div>
            </div>
        <div class="form-group row">
            <label class= 'col-lg-3 col-form-label'>Nama Product</label>
                 <div class="col-lg-7">
                    <input type="text" name="name" value="{{$desc->name}}" class="form-control" >
                </div>
            </div>
                <div class="form-group row">
                    {!! Form::label('manufacturer_id', 'Brand Product', ['class' => 'col-lg-3
                    col-form-label']) !!}
                    <div class="col-lg-7">
                        {!! Form::select('manufacturer_id', $manufacture,
                        $live->manufacturer_id,['id' => 'manufacturer_id', 'class' => 'form-control
                        form-control-select2', 'placeholder' => '*','required']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('category_id', 'Category Product', ['class' => 'col-lg-3
                    col-form-label']) !!}
                    <div class="col-lg-7">
                        {!! Form::select('category_id', $category,
                        $toCat,['id' => 'category_id', 'class' => 'form-control
                        form-control-select2', 'placeholder' => '*','required']) !!}
                    </div>
                </div>            
            <div class="form-group row" id= "row_status">
                <label class= 'col-lg-3 col-form-label' class="switch" required>Status Product</label>
                <div class="col-lg-7">
                    {!! Form::select('status',array('1' => 'Active', '0' => 'In Active'),
                    $live->status,['id' => 'status', 'class' => 'form-control form-control-select2', 'placeholder' => '*']) !!}
                </div>
            </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label" name="pro_dimension" required>Product Dimension*</label>
                    <div class="col-lg-3">
                        <input type="text" class="form-control" name="pro_dimension" value="{{$dimensi}}"
                            placeholder="Enter Dimension Ex: PxLxT" required></input>
                    </div>
                    <div class="col-lg-3">
                        {!! Form::select('length_class_id', $length, $live->length_class_id,['id' =>
                        'length', 'class' => 'form-control form-control-select2', 'placeholder' => '*'])
                        !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Weight</label>
                    <div class="col-lg-3">
                        {!!
                        Form::text('pro_weight',round($live->weight),['id'=>'pro_weight','class'=>'form-control','placeholder'=>'Enter Weight']) !!}
                    </div>
                    <div class="col-lg-3">
                        {!! Form::select('weight_class_id', $weight, $live->weight_class_id,['id' =>
                        'weight', 'class' => 'form-control form-control-select2', 'placeholder' => '*'])
                        !!}
                    </div>
                </div>
                <div class="form-group row" id="spek">
                        <label class="col-lg-3 col-form-label">Overview</label>
                        <div class="col-lg-8">
                            <textarea type="text" class="form-control SumContent" id="overview" name="overview"
                                placeholder="Enter Spesification of Product">{!!$desc->overview!!}</textarea>
                        </div>
                </div>
                <!-- More Details -->
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Additional Information</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            @csrf
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label" name="pro_image">Update Image</label>
                    <div class="col-lg-7">
                        <input type="file" name="pro_image" value="{{$live->image}}" class="form-control">    
                        <img src="{{url('public/public/post-image/'.$live->image) }}" style="width:200px">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Date Available*</label>
                    <div class="col-lg-7">
                        <input
                            type="text"
                            id="Date"
                            class="form-control"
                            name="pro_berlaku_sampai"
                            value="{{$live->date_available}}"
                            >
                    </div>
                </div>
                <div class="form-group row" id="spek">
                    <label class="col-lg-3 col-form-label">Spesification</label>
                    <div class="col-lg-8">
                        <textarea type="text" class="form-control SumContent" id="pro_spec" name="pro_spec"
                            placeholder="Enter Spesification of Product">{!!$name_atr!!}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
                <h5 class="card-title">PRICE</h5>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @csrf

             <!-- <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Update Price ?</label>
                 <div class="col-lg-7">
                     {!! Form::select('pro_type',array('yes' => 'Edit', 'no' => 'No Edit'), '',
                     ['id' => 'type' , 'onchange' =>'change(this)' ,'name' => 'pro_type', 'class' => 'form-control form-control-select2', 'placeholder' => '*', 'required']) !!}
                 </div>
            </div>             -->
            <!-- Price -->
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-check-input-styled-primary" value="pro_priceType">Price</label>
                <div class="col-lg-7">
                    {!! Form::select('price_type',array('Harga Normal' => 'Harga Normal', 'Harga Ecatalog' => 'Harga Ecatalog'),$price_type,
                    ['id' => 'price_type', 'class' => 'form-control form-control-select2','required','readonly','placeholder' => '*']) !!}
                </div>
                    </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Modal Price</label>
                    @php
                    if ($list==null || $price==null)
                    {
                        $mod = null;
                    }else{
                        $mod = $price->price_modal;
                    }
                    @endphp
                    <div class="col-lg-7">
                        <input type="number" class="form-control" id="modal" step="any" name="price_modal" value="{{$mod}}" placeholder="Enter Modal Price">
                    </div>
                </div>
                    @php
                    if ($list==null || $price==null)
                    {
                        $types = null;
                    }else{
                        $types = $price->type_harga;
                    }
                    @endphp                
                @if($list==null ? null : $types=="Harga Normal")
                <div class="form-group row normal">
                    <label class="col-lg-3 col-form-label">Product Price*</label>
                    <div class="col-lg-7">
                    <input type="number" step="any" id="price" name="price" value="{{$live->price}}" class="form-control" onKeyup= "Catalog();">
                    </div>
                </div>
                <div class="form-group row catalognormal">
                    <label class="col-lg-3 col-form-label">E-Catalog Price</label>
                    <div class="col-lg-7">
                        <input type="number" id="catalog" step="any" class="form-control" value="" name="catalog" placeholder="Enter E-Catalog Price" readonly>
                    </div>
                </div>
                @elseif ($list==null ? null : $types=="Harga Ecatalog")
                <div class="form-group row normal">
                    <label class="col-lg-3 col-form-label">Product Price*</label>
                    <div class="col-lg-7">
                    <input type="number" step="any" id="price" name="price" value="{{$list==null ? null : $price->price_retail}}" class="form-control" onKeyup= "Catalog();">
                    </div>
                </div>
                <div class="form-group row catalog">
                    <label class="col-lg-3 col-form-label">E-Catalog Price</label>
                    <div class="col-lg-7">
                        <input type="number" id="catalog" step="any" class="form-control" value="{{$live->price}}" name="catalog" placeholder="Enter E-Catalog Price" readonly>
                    </div>
                </div>
                @else
                <div class="form-group row normal">
                    <label class="col-lg-3 col-form-label">Product Price*</label>
                    <div class="col-lg-7">
                    <input type="number" step="any" id="price" name="price" value="{{$live->price}}" class="form-control" onKeyup= "Catalog();">
                    </div>
                </div>
                <div class="form-group row catalog">
                    <label class="col-lg-3 col-form-label">E-Catalog Price</label>
                    <div class="col-lg-7">
                        <input type="number" id="catalog" step="any" class="form-control" value="{{$price==null?null:$price->catalog_price}}" name="catalog" placeholder="Enter E-Catalog Price" readonly>
                    </div>
                </div>
                @endif
                <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'product/live', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                <button type="submit" class="btn btn-primary">Update Product<i class="far fa-save ml-2"></i></button>
            </div>
                </form>
            </div>
        </div>
        </div>
@endsection
@section('script')
<script src="{{ asset('ctrl/product/live-form.js') }}" type="text/javascript"></script>
<script>
$(document).ready(function() {
$('.SumContent').summernote({
      callbacks: {
        // callback for pasting text only (no formatting)
        onPaste: function (e) {
          var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
          e.preventDefault();
          bufferText = bufferText.replace(/\r?\n/g, '<br>');
          document.execCommand('insertHtml', false, bufferText);
        }
      }
    });
});
</script>
@endsection
