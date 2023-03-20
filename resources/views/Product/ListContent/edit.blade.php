@extends('layouts.head') @section('content')
<!-- Horizontal form -->
<div class="content">
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Update Product</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="reload"></a>
                    <a class="list-icons-item" data-action="remove"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form
                action="{{ route('listcontent.update', $list->pro_id )}}"
                method= "POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                 {!! Form::hidden('pro_id',$list->pro_id,['id'=>'pro_id','class'=>'form-control']) !!}
                 {!! Form::hidden('pro_name',$list->pro_name,['id'=>'pro_name','class'=>'form-control']) !!}
                 {!! Form::hidden('id_quo',$id_quo,['id'=>'id_quo','class'=>'form-control']) !!}
                 <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Part Number</label>
                    <div class="col-lg-7">
                        <input
                            type="text"
                            class="form-control"
                            name="pro_vn"
                            value="{{$list->pro_vn}}"
                            >
                    </div>
                </div>

            <div class="form-group row">
                {!! Form::label('pro_name', 'Product Name', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::text('pro_name',$list->pro_name,['id'=>'pro_name','class'=>'form-control','placeholder'=>'Enter menu name','required']) !!}
                    
                </div>
            </div>
                <div class="form-group row">
                    {!! Form::label('pro_categories', 'Category Product', ['class' => 'col-lg-3
                    col-form-label']) !!}
                    <div class="col-lg-7">
                        {!! Form::select('pro_categories', $pro_categories, $list->pro_categories,['id'
                        => 'pro_categories', 'class' => 'form-control form-control-select2',
                        'placeholder' => '*','required']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('pro_manufacture', 'Brand Product*', ['class' => 'col-lg-3
                    col-form-label']) !!}
                    <div class="col-lg-7">
                        {!! Form::select('pro_manufacture', $pro_manufacture,
                        $list->pro_manufacture,['id' => 'manufacture', 'class' => 'form-control
                        form-control-select2', 'placeholder' => '*','required']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label" name="pro_dimension">Product Dimension*</label>
                    <div class="col-lg-3">
                        <input
                            type="text"
                            class="form-control"
                            name="pro_dimension"
                            value="{{$list->pro_dimesion}}"
                            placeholder="Enter Product Dimension"
                            required></input>
                    </div>
                        <div class="col-lg-3">
                            {!! Form::select('length_unit',
                            array('Centimeter' => 'Centimeter', 'Millimeter' => 'Millimeter', 'Inch' => 'Inch', 'Others' => 'Others'),
                            $list->length_unit,['id' => 'length', 'class' => 'form-control form-control-select2', 'placeholder' => '*']) !!}
                        </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Weight</label>
                    <div class="col-lg-3">
                        {!!
                        Form::text('pro_weight',$list->pro_weight,['id'=>'pro_weight','class'=>'form-control','placeholder'=>'Enter Weight']) !!}
                    </div>

                    <div class="col-lg-3">
                        {!! Form::select('weight_class_id', $pro_weight, $list->weight_class_id,['id' =>
                        'weight', 'class' => 'form-control form-control-select2', 'placeholder' => '*'])
                        !!}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Overview</label>
                    <div class="col-lg-7 note-editable">
                        <textarea
                            class="form-control SumContent"
                            id="pro_desc"
                            name="pro_desc"
                            value="{{$list->pro_desc}}"
                            >{!!$list->pro_desc!!}</textarea>
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
                        <a class="list-icons-item" data-action="reload"></a>
                        <a class="list-icons-item" data-action="remove"></a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @csrf
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label" name="pro_image">Update Image</label>
                    <div class="col-lg-7">
						<input type="file" name="pro_image" value="{{$list->pro_image}}" class="form-control">
                            <img src="{{asset('storage/post-image/'.$list->pro_image) }}" style="width:200px">
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
                            value="{{$list->pro_berlaku_sampai}}"
                            >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">More Spesification</label>
                    <div class="col-lg-7 note-editable">
                        <textarea
                            class="form-control SumContent"
                            id="pro_spec"
                            name="pro_spec"
                            value="{{$list->pro_spec}}"
                            >{!!$list->pro_spec!!}</textarea>
                    </div>
                </div>
            </div>
            <!-- Price -->
        </div>
        <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
                <h5 class="card-title">PRICE</h5>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                        <a class="list-icons-item" data-action="reload"></a>
                        <a class="list-icons-item" data-action="remove"></a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @csrf
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-check-input-styled-primary" value="pro_priceType">Type*</label>
                        <div class="col-lg-7">
                           {!! Form::select('pro_priceType', 
                            array('Harga Normal' => 'Harga Normal', 'Harga Ecatalog' => 'Harga Ecatalog'), '',
                            ['id' => 'pro_priceType', 'class' => 'form-control form-control-select2', 'placeholder' => '*','required']) !!}
                        </div>
                    </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Modal Price</label>
                    <div class="col-lg-7">
                        <input
                            step="any"
                            type="number"
                            class="form-control"
                            name="price_modal"
                            value="{{$price->price_modal}}"
                            >
                    </div>
                </div>
                @if($type==null)
                <div class="form-group row product">
                    <label class="col-lg-3 col-form-label">Product Price*</label>
                    <div class="col-lg-7">
                        <input
                            type="number" id="proPrice"
                            class="form-control"    
                            name="price_retail"
                            value="{{$price->price_retail}}" step="any" OnChange="Catalog();" onKeyup= "Catalog();"
                            required>
                    </div>
                </div>
                <div class="form-group row ecatalog">
                    <label class="col-lg-3 col-form-label">E-Catalog Price</label>
                    <div class="col-lg-7">
                        <input type="number" id="ecatalog" step="any" class="form-control" value="{{$price->catalog_price}}" name="catalog_price" placeholder="Enter E-Catalog Price" readonly required>
                    </div>
                </div>
                @else
                <div class="form-group row product">
                    <label class="col-lg-3 col-form-label">Product Price*</label>
                    <div class="col-lg-7">
                        <input
                            type="number" id="proPrice"
                            class="form-control"    
                            name="price_retail"
                            value="{{$price->price_retail}}" step="any" OnChange="Catalog();" onKeyup= "Catalog();"
                            required>
                    </div>
                </div>
                <div class="form-group row ecatalog">
                    <label class="col-lg-3 col-form-label">E-Catalog Price</label>
                    <div class="col-lg-7">
                        <input type="number" id="ecatalog" step="any" class="form-control" value="{{$price->catalog_price}}" name="catalog_price" placeholder="Enter E-Catalog Price" readonly required>
                    </div>
                </div>
                @endif
                <div class="text-right">
                    {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                    'data-method' => 'product/content/listcontent', 'type' =>
                    'button','onclick'=>'cancel(this)']) !!}
                    <button type="submit" class="btn btn-primary">Update Product<i class="icon-paperplane ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection @section('script')
<script src="{{ asset('ctrl/product/listcontent-form.js') }}"type="text/javascript"></script>
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