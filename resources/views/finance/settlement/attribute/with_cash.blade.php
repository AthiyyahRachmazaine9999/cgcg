    <!-- Basic layout-->
    <input type="hidden" id="app_spv" value="{{$cash->app_spv}}" name="app_spv" class="form-control">
    <input type="hidden" id="id_cash" value="{{$cash->id}}" name="id_cash" class="form-control">
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>No. Cash Advance</label>
        <div class="col-lg-7">
            <div class="form-control">{{$cash->no_cashadv}}</div>
        </div>
    </div>
    <div class="form-group row">
        {!! Form::label('nama_emp', 'Nama', ['class' => 'col-lg-3 col-form-label']) !!}
        <div class="col-lg-7">
            <div class="form-control">{{emp_name($cash->emp_id)}}</div>
        </div>
    </div>

    @if($cash->type_cash=="dinas")
    <div class="form-group row">
        <label class='col-lg-3 col-form-label'>Tujuan</label>
        <div class="col-lg-3">
            <div class="form-control">{{province($cash->des_provinsi)}}</div>
        </div>

        <div class="col-lg-3">
            <div class="form-control">{{city($cash->des_kota)}}</div>
        </div>
    </div>
    @else
    <div class="form-group row">
        {!! Form::label('tujuan', 'tujuan', ['class' => 'col-lg-3 col-form-label']) !!}
        <div class="col-lg-7">
            <div class="form-control">{{$cash->des_tujuan}}</div>
        </div>
    </div>
    @endif
    <div class="form-group row">
        {!! Form::label('est_waktu', 'Total Estimasi Biaya', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-7">
            <div class="form-control">{{number_format($total_biaya,2)}}</div>
        </div>
    </div>

    @if($cash->tgl_transfer)
    <div class="form-group row">
        {!! Form::label('tgl_transfer', 'Tanggal Transaksi', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-7">
            <div class="form-control">{{\Carbon\Carbon::parse($cash->tgl_transfer)->format('d F Y')}}</div>
        </div>
    </div>
    @endif

    @if($cash->mtd_cash=="Transfer")
    <div class="form-group row">
        {!! Form::label('mtd_cash', 'Pembayaran', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-7">
            <div class="form-control"><b>{{$cash->mtd_cash}}</b></div>
            <div class="form-control">{{$cash->rek_bank}} - {{$cash->no_rek}} - {{$cash->nama_rek}} -
                {{$cash->cabang_rek}}</div>
        </div>
    </div>
    @else
    <div class="form-group row">
        {!! Form::label('mtd_cash', 'Pembayaran', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-7">
            <div class="form-control"><b>{{$cash->mtd_cash}}</b></div>
        </div>
    </div>
    @endif
    <br>

    <div class="more_deskripsi">
        @php $i=1; $b=1; $z=1; $l=1; foreach ($cash_dtl as $dtl){ @endphp
        <legend class="text-uppercase font-size-sm font-weight-bold">Detail
        </legend>
        <div class="form-group row after-add-more">
            <label class='col-lg-3 col-form-label'>Tujuan</label>
            <div class="col-lg-7">
                <input type="text" name="tujuan[]"
                    value="{{$cash->type_cash=='dinas' ? $dtl->nama_pekerjaan : $dtl->deskripsi}}" class="form-control"
                    placeholder="Masukkan Tujuan" required="required">
            </div>
        </div>
        <div class="form-group row after-add-more">
            <label class='col-lg-3 col-form-label'>Quantity</label>
            <div class="col-lg-7">
                <input type="text" name="qty[]" value="1" class="form-control " placeholder="Masukkan Qty">
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Item Description</label>
            <div class="col-lg-7">
                <input type="text" name="note[]" class="form-control" placeholder="Masukkan Note">
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Nominal</label>
            <div class="col-lg-7">
                <input type="number" name="biaya[]" value="{{$dtl->est_biaya}}" class="form-control" step="any"
                    placeholder="Masukkan Biaya">
            </div>
        </div>
        <div class="form-group row">
            <label class='col-lg-3 col-form-label'>Receipt</label>
            <div class="col-lg-7">
                <input type="file" name="file_set[]" class="form-control" placeholder="Upload Receipt">
            </div>
        </div>
        @php } @endphp
        <div class="more_desc"></div><br>
    </div>
    <button type="button" onClick="more_desc(this)" data-type="tambah"
        class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i class="fas fa-plus"></i></b></button><br>
    <br>

    <!-- //Button -->
    <div class="text-right" style="padding-top:50px">
        {!! Form::button('Back', ['class' => 'btn btn-light btn-cancel',
        'data-method' =>'finance/settlement','type' =>
        'button','onclick'=>'cancel(this)']) !!}
        <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i>
        </button>
    </div>
    <!-- /basic layout -->
    </div>
    <script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript"></script>
    <script src="{{ asset('ctrl/finance/settlement-form.js?v=').rand() }}" type="text/javascript"></script>