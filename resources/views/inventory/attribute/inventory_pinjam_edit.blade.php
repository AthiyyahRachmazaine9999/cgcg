<form action="{{ route('pinjam.update') }}" method="POST">
    @csrf
    <div class="card-body">
        {!! Form::hidden('id',$pinjamdet->id,['id'=>'id','class'=>'form-control'])!!}
        {!! Form::hidden('sku',$data,['id'=>'sku','class'=>'form-control'])!!}
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Qty Tersedia</label>
            <div class="col-lg-7">
                <input type="text" name="qty_tersedia" value="{{$qty_sisa}}" class="form-control" placeholder="Qty Asli" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Qty Pinjam</label>
            <div class="col-lg-7">
                <input type="number" name="qty" value="{{$pinjamdet->qty_pinjam}}" class="form-control" placeholder="Qty Pinjam">
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Tanggal Pinjam</label>
            <div class="col-lg-7">
                <input type="date" id="start_date" value="{{date('Y-m-d',strtotime($pinjamdet->tanggal_pinjam))}}" class="form-control load" data-column="5" name="date" placeholder="Enter Date">
            </div>
        </div>

        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Nama Peminjam / Penerima</label>
            <div class="col-lg-7">
                <input type="text" name="nama_penerima" value="{{$pinjamdet->nama_peminjam}}" class="form-control" placeholder="Nama Peminjam / Penerima">
            </div>
        </div>

        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Dinas / Customer</label>
            <div class="col-lg-7">
                <select class="form-control" name="id_customer" id="id_customer">
                    <option val="bar" selected="selected">{!!getCustomer($pinjamdet->id_customer)->company!!}</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Alamat Lain</label>
            <div class="col-lg-7">
                <textarea type="text" name="alamat_lain" class="form-control" placeholder="Alamat Lain"></textarea>
            </div>
        </div>


        <div class="form-group row" id="row_note">
            <label class='col-lg-3 col-form-label'>Note</label>
            <div class="col-lg-7">
                <textarea type="text" name="note" class="form-control" placeholder="Note">{{$pinjamdet->note}}</textarea>
            </div>
        </div>
        <div class="text-right">
            {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method'
            =>'warehouse/inventory/'.$data, 'type' => 'button','onclick'=>'cancel(this)']) !!}
            <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i></button>
        </div>
    </div>
</form>