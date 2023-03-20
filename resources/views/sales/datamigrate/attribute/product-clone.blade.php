<div id="row_item_{{ $n_equ }}" class="row ml-3 mr-3 formedit">
    <div class="col-lg-12 mb-3">
        <div class="row">
            <div class="col-lg-12">
                <input type="hidden" id="newsku_{{ $n_equ }}" name="newsku" value="">
                <input type="hidden" id="id_quo" name="id_quo" value="{{$id_quo}}">
                <select name="idpro_new" class="form-control id_product" id="id_product_{{ $n_equ }}">
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-10">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            {!! Form::number('p_qty_new','',['id'=>'p_qty','class'=>'form-control','placeholder'=>'Qty']) !!}
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="form-group">
                            {!! Form::number('p_price_new','',['id'=>'p_price','class'=>'form-control','placeholder'=>'Harga Order']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <button type="button" onclick="remove_equ({{$n_equ}})" class="btn bg-pink-400 btn-icon rounded-round legitRipple"><i class="icon-trash-alt"></i></button>
                <button type="button" onclick="save_new({{$n_equ}})" class="btn bg-primary-400 btn-icon rounded-round legitRipple"><i class="icon-floppy-disk"></i></button>
            </div>
        </div>
        <span class="text-danger" style="font-style: italic;">*) Untuk Penambahan Barang silahkan click save button lingkaran dikanan bukan yang dibawah</span>
    </div>
</div>