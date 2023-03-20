<div id="row_item_{{ $n_equ }}" class="row ml-3 mr-3 formedit">
    <div class="col-lg-12 mb-3">
        <div class="row mt-3 mb-3">
            <div class="col-lg-10">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            {!! Form::hidden('id_quo_split',$id_quo,['id'=>'id_quo_split','class'=>'form-control','placeholder'=>'Qty']) !!}
                            {!! Form::hidden('id_pro_split',$idpro,['id'=>'id_pro_split','class'=>'form-control','placeholder'=>'Qty']) !!}
                            {!! Form::number('p_qty_split','',['id'=>'p_qty','class'=>'form-control','placeholder'=>'Qty']) !!}
                        </div>
                    </div>
                    <div class="col-lg-7">
                        {!! Form::select('vendor_split',$stock,null , ['id'=>'vendor_new','class' => 'form-control form-control-select2','placeholder' => '*']) !!}
                        {!! Form::select('stock_split',$stock,null, ['id'=>'stock_new','class' => 'form-control form-control-select2 stock_new','placeholder' => '*']) !!}
                        {!! Form::text('note_split','',['id'=>'note','class'=>'form-control','placeholder'=>'Keterangan tambahan']) !!}
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            {!! Form::select('bayar_split',$bayar,'', ['id'=>'bayar','class' => 'form-control form-control-select2 stock_new','placeholder' => '*']) !!}
                            {!! Form::number('p_price_split','',['id'=>'p_price','class'=>'form-control','placeholder'=>'Harga Order']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <button type="button" onclick="remove_split({{$n_equ}})" class="btn bg-pink-400 btn-icon rounded-round legitRipple"><i class="icon-trash-alt"></i></button>
                <button type="button" onclick="save_split({{$n_equ}})" class="btn bg-primary-400 btn-icon rounded-round legitRipple"><i class="icon-floppy-disk"></i></button>
            </div>
        </div>
        <span class="text-danger" style="font-style: italic;">*) Silahkan click save button lingkaran dikanan bukan yang dibawah</span>
    </div>
</div>