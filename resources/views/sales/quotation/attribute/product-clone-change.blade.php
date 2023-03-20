
<div id="row_item_{{ $n_equ }}" class="formedit">
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" id="newsku_{{ $n_equ }}" name="newsku" value="">
            <input type="hidden" id="id_quo" name="id_quo" value="{{$id_quo}}">
            <input type="hidden" id="idpro" name="idpro" value="{{$idpro}}">
            <select name="idpro_new" class="form-control id_product" id="id_product_{{ $n_equ }}">
            </select>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-10">
            <div class="row">
                <div class="col-lg-2">
                    <div class="form-group">
                        {!! Form::number('p_qty_new','',['id'=>'p_qty','class'=>'form-control','placeholder'=>'Qty']) !!}
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        {!! Form::number('p_price_new','',['id'=>'p_price','class'=>'form-control','placeholder'=>'Harga Order']) !!}
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        {!! Form::text('note','',['id'=>'note','class'=>'form-control','placeholder'=>'Catatan']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <button type="button" onclick="remove_equ({{$n_equ}})" class="btn bg-pink-400 btn-icon rounded-round legitRipple"><i class="icon-trash-alt"></i></button>
            <button type="button" onclick="save_equ({{$n_equ}})" class="btn bg-primary-400 btn-icon rounded-round legitRipple"><i class="icon-floppy-disk"></i></button>

        </div>
    </div>
</div>