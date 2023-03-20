@extends('layouts.head')
@section('content')
<!-- Horizontal form -->
<div class="content">
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Create Product</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('listcontent.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                 {!! Form::hidden('type',$type,['id'=>'type','class'=>'form-control']) !!}
                 {!! Form::hidden('pro_id',$id,['id'=>'pro_id','class'=>'form-control']) !!}
                 {!! Form::hidden('token',$token_con,['id'=>'token','class'=>'form-control']) !!}
                 <div class="form-group row">
                    <h4 class="col-lg-10" name="token">{!!$token_con!!}</h4>
                    </div>
                <input type="hidden" class="form-control" name="id_quo" value="{{$quo}}" placeholder="Enter Part Number">
                <input type="hidden" class="form-control" name="pro_request_id" value="{{$ProReq}}">
                <input type="hidden" class="form-control" name="typequo" value="{{$typequo}}">
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Part Number</label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control" name="pro_vn" placeholder="Enter Part Number">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Nama Product</label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control" name="pro_name" value='{!!strip_tags($req->req_product)!!}' placeholder="Enter Product Name">
                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Category Product*</label>
                    <div class="col-lg-7">
                        <select class="form-control form-control-select2" name="pro_categories" id="pro_categories"
                            placeholder="Enter Category" required>
                            <option value="" name="pro_categories" id="pro_categories" required>-- Pilih --</option>
                            <div></div>
                            @foreach ($cat as $cat)
                            <option value="{{ $cat->category_id}}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Brand Product*</label>
                    <div class="col-lg-7">
                        <select class="form-control form-control-select2" name="pro_manufacture" id="manufacture"
                            placeholder="Enter Category" required>
                            <option value="" name="pro_manufacture" id="manufacture" required>-- Pilih --</option>
                            <div></div>
                            @foreach ($man as $man)
                            <option value="{{ $man->manufacturer_id}}">{{ $man->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label" name="pro_dimension">Product Dimension*</label>
                    <div class="col-lg-3">
                        <input type="text" class="form-control" name="pro_dimension"
                            placeholder="Enter Dimension Ex: PxLxT" required></input>
                    </div>
                    <div class="col-lg-3">
                        {!! Form::select('length_unit', array('Centimeter' => 'Centimeter', 'Millimeter' =>
                            'Millimeter', 'Inch' => 'Inch'), '', ['id' =>
                            'length', 'class' => 'form-control form-control-select2', 'placeholder' =>
                            '*']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Weight*</label>
                    <div class="col-lg-3">
                        <input type="number" class="form-control" name="pro_weight" placeholder="Enter Weight Product" reuired>
                    </div>
                    <div class="col-lg-3">
                         <select class="form-control form-control-select2" name="weight_class_id" id="weight"
                            placeholder="Enter Category" required>
                            <option value="" name="weight_class_id" id="weight_class_id">-- Pilih --</option>
                            @foreach ($weight as $weight)
                            <option value="{{ $weight->weight_class_id}}">{{$weight->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Overview</label>
                    <div class="col-lg-8 note-editable">
                        <textarea type="text" class="form-control SumContent" id="pro_desc" name="pro_desc"
                            placeholder="Enter Description"></textarea>
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
                <label class="col-lg-3 col-form-label" required>Upload Image*</label>
                <div class="col-lg-7">
                <input type="file" name="pro_image" class="file-input" required>
            </div>
            </div>

                <div class="form-group row">
                <label class="col-lg-3 col-form-label">Date Available*</label>
                    <div class="col-lg-7">
                        <input type="text" id="Date" class="form-control" name="pro_berlaku_sampai"
                            placeholder="Enter Available Date" required>
                    </div>
                </div>
            
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">More Spesification</label>
                    <div class="col-lg-8 note-editable">
                        <textarea type="text" class="form-control SumContent" id="pro_spec" name="pro_spec"
                            placeholder="Enter Description"></textarea>
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
                    </div>
                </div>
            </div>

            <div class="card-body">
                @csrf
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-check-input-styled-primary" value="pro_priceType">Type</label>
                        <div class="col-lg-7">
                           {!! Form::select('pro_priceType', 
                            array('Harga Normal' => 'Harga Normal', 'Harga Ecatalog' => 'Harga Ecatalog'), '',
                            ['id' => 'pro_priceType', 'class' => 'form-control form-control-select2', 'placeholder' => '*' ,'required']) !!}
                        </div>
                    </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Request Price</label>
                    @if($req->req_price==null)
                    <div class="col-lg-7">
                        <input type="text" class="form-control" value="0" readonly>
                    </div>
                    @else           
                    <div class="col-lg-7">
                        <input type="text" class="form-control" value="{{$req->req_price}}" readonly>
                    </div>
                    @endif
                </div> 

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Modal Price</label>
                    @if($req_id->det_quo_harga_modal==null)
                    <div class="col-lg-7">
                        <input type="text" name="price_modal" step="any" class="form-control" value="0">
                    </div>
                    @else
                    <div class="col-lg-7">
                        <input type="number" class="form-control" step="any" name="price_modal" value="{{$req_id->det_quo_harga_modal}}" placeholder="Enter Modal Price" >
                    </div>
                    @endif
                </div> 

                <div class="form-group row product">
                    <label class="col-lg-3 col-form-label">Product Price*</label>
                    <div class="col-lg-7">
                        <input type="number" id="proPrice" step="any" class="form-control" name="price_retail" placeholder="Enter Product Price" OnChange="Catalog();" onKeyup= "Catalog();">
                    </div>
                </div>
                <div class="form-group row ecatalog">
                    <label class="col-lg-3 col-form-label">E-Catalog Price</label>
                    <div class="col-lg-7">
                        <input type="number" id="ecatalog" step="any" class="form-control" name="catalog_price" placeholder="Enter E-Catalog Price" readonly>
                    </div>
                </div>
                <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'sales/quotation/', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                <button type="submit" class="btn btn-primary">Create Product<i class="far fa-save ml-2"></i></button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/product/listcontent-form.js') }}" type="text/javascript"></script>
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
