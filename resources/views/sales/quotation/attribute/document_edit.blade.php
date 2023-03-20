{!! Form::open(['method' => $method,'action'=>$action, 'id' => 'formedit']) !!}
<div class="row">
    {!! Form::hidden('id_quo',$quo, ['id'=>'id_quo','class' => 'form-control','placeholder' => 'Masukkan No SP']) !!}
    {!! Form::hidden('type',"update", ['id'=>'type','class' => 'form-control','placeholder' => 'Masukkan No SP']) !!}
    {!! Form::hidden('view',"$view", ['id'=>'view','class' => 'form-control','placeholder' => 'Masukkan No SP']) !!}
    <div class="col-md-12">
        {!! Form::label('waktu_pelaksanaan', 'Jangka Waktu Pelaksanaan') !!}
        {!! Form::date('waktu_pelaksanaan', $main->waktu_pelaksanaan, ['id'=>'waktu_pelaksanaan','class' =>
        'form-control','placeholder' => 'Masukkan Batas Akhir']) !!}
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-4">
        {!! Form::label('quo_no', 'No. PO Customer') !!}
        {!! Form::text('quo_no',$utama->quo_no , ['id'=>'quo_no','class' => 'form-control','placeholder' => 'Masukkan No
        PO','readonly']) !!}
    </div>
    <div class="col-md-4">
        {!! Form::label('quo_order_at', 'Tanggal PO') !!}
        {!! Form::date('quo_order_at',$utama->quo_order_at , ['id'=>'quo_order_at','class' => 'form-control']) !!}
    </div>
    <div class="col-md-4 mt-4">
        <div class="list-icons">
            <div class="list-icons-item dropdown">
                <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i
                        class="icon-menu7"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" id="uploadpo" data-toggle="modal" data-target="#m_modal3" data-type="po"
                        data-id="{{$main->id_quo}}" data-po="{{$main->doc_po}}" onclick="upload_document(this)"
                        class="dropdown-item"><i class="far fa-save ml-2"></i>Drop
                        Input</a>
                    <div class="dropdown-divider"></div>
                    <a href="#" id="uploadpo" data-toggle="modal" data-target="#m_modal4" data-type="po"
                        data-id="{{$main->id_quo}}" data-po="{{$main->doc_po}}" onclick="classic_upload(this)"
                        class="dropdown-item"><i class="fa fa-file ml-2"></i>Classic
                        Input</a>
                </div>
            </div>
        </div>
        @if($main->doc_po!=null)
        <a href="{{asset('public/'.$main->doc_po)}}"
            class="btn btn-primary btn-labeled btn-labeled-left btn-sm legitRipple text-right"><b>
                <i class="fas fa-check ml-2"></i></b>Show File</a>
        @endif
        <a href="#" id="showpo" class="text-success"> Data Tersimpan</a>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-4">
        {!! Form::label('no_sp', 'No. SP') !!}
        {!! Form::text('no_sp',$main->no_sp , ['id'=>'no_sp','class' => 'form-control','placeholder' => 'Masukkan No
        SP']) !!}
    </div>
    <div class="col-md-4">
        {!! Form::label('tgl_sp', 'Tanggal SP') !!}
        {!! Form::date('tgl_sp',$main->tgl_sp , ['id'=>'tgl_sp','class' => 'form-control']) !!}
    </div>
    <div class="col-md-4 mt-3">
        <div class="list-icons">
            <div class="list-icons-item dropdown">
                <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i
                        class="icon-menu7"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" id="uploadsp" data-toggle="modal" data-target="#m_modal3" data-type="sp"
                        data-id="{{$main->id_quo}}" data-sp="{{$main->doc_sp}}" onclick="upload_document(this)"
                        class="dropdown-item"><i class="far fa-save ml-2"></i>Drop
                        Input</a>
                    <div class="dropdown-divider"></div>
                    <a href="#" id="uploadsp" data-toggle="modal" data-target="#m_modal4" data-type="sp"
                        data-id="{{$main->id_quo}}" data-sp="{{$main->doc_sp}}" onclick="classic_upload(this)"
                        class="dropdown-item"><i class="fa fa-file ml-2"></i>Classic
                        Input</a>
                </div>
            </div>
        </div>
        @if($main->doc_sp!=null)
        <a href="{{asset('public/'.$main->doc_sp)}}"
            class="btn btn-primary btn-labeled btn-labeled-left btn-sm legitRipple text-right"><b>
                <i class="fas fa-check ml-2"></i></b>Show File</a>
        @endif
        <a href="#" id="showsp" class="text-success"> Data Tersimpan</a>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-4">
        {!! Form::label('no_spk', 'No. SPK') !!}
        {!! Form::text('no_spk',$main->no_spk , ['id'=>'no_spk','class' => 'form-control','placeholder' => 'Masukkan No
        SPK']) !!}
    </div>
    <div class="col-md-4">
        {!! Form::label('tgl_spk', 'Tanggal SPK') !!}
        {!! Form::date('tgl_spk',$main->tgl_spk , ['id'=>'tgl_spk','class' => 'form-control']) !!}
    </div>
    <div class="col-md-4 mt-3">
        <div class="list-icons">
            <div class="list-icons-item dropdown">
                <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i
                        class="icon-menu7"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" id="uploadspk" data-toggle="modal" data-target="#m_modal3" data-type="spk"
                        data-id="{{$main->id_quo}}" data-spk="{{$main->doc_spk}}" onclick="upload_document(this)"
                        class="dropdown-item"><i class="far fa-save ml-2"></i>Drop
                        Input</a>
                    <div class="dropdown-divider"></div>
                    <a href="#" id="uploadspk" data-toggle="modal" data-target="#m_modal4" data-type="spk"
                        data-id="{{$main->id_quo}}" data-spk="{{$main->doc_spk}}" onclick="classic_upload(this)"
                        class="dropdown-item"><i class="fa fa-file ml-2"></i>Classic
                        Input</a>
                </div>
            </div>
        </div>
        @if($main->doc_spk!=null)
        <a href="{{asset('public/'.$main->doc_spk)}}"
            class="btn btn-primary btn-labeled btn-labeled-left btn-sm legitRipple text-right"><b>
                <i class="fas fa-check ml-2"></i></b>Show File</a>
        @endif
        <a href="#" id="showspk" class="text-success"> Data Tersimpan</a>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-4">
        {!! Form::label('no_bast', 'No. BAST') !!}
        {!! Form::text('no_bast',$main->no_bast , ['id'=>'no_bast','class' => 'form-control','placeholder' => 'Masukkan
        No BAST']) !!}
    </div>
    <div class="col-md-4">
        {!! Form::label('tgl_bast', 'Tanggal BAST') !!}
        {!! Form::date('tgl_bast',$main->tgl_bast , ['id'=>'tgl_bast','class' => 'form-control']) !!}
    </div>
    <div class="col-md-4 mt-3">
        <div class="list-icons">
            <div class="list-icons-item dropdown">
                <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i
                        class="icon-menu7"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" id="uploadbast" data-toggle="modal" data-target="#m_modal3" data-type="bast"
                        data-id="{{$main->id_quo}}" data-value="{{$main->doc_bast}}" onclick="upload_document(this)"
                        class="dropdown-item"><i class="far fa-save ml-2"></i>Drop
                        Input</a>
                    <div class="dropdown-divider"></div>
                    <a href="#" id="uploadbast" data-toggle="modal" data-target="#m_modal4" data-type="bast"
                        data-id="{{$main->id_quo}}" data-value="{{$main->doc_bast}}" onclick="classic_upload(this)"
                        class="dropdown-item"><i class="fa fa-file ml-2"></i>Classic
                        Input</a>
                </div>
            </div>
        </div>
        @if($main->doc_bast!=null)
        <a href="{{asset('public/'.$main->doc_bast)}}"
            class="btn btn-primary btn-labeled btn-labeled-left btn-sm legitRipple text-right"><b>
                <i class="fas fa-check ml-2"></i></b>Show File</a>
        @endif
        <a href="#" id="showbast" class="text-success"> Data Tersimpan</a>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-4">
        {!! Form::label('no_fakturpajak', 'No. Faktur Pajak') !!}
        {!! Form::text('no_fakturpajak',$main->no_fakturpajak , ['id'=>'no_fakturpajak','class' =>
        'form-control','placeholder' => 'Masukkan No Faktur Pajak']) !!}
    </div>
    <div class="col-md-4">
        {!! Form::label('tgl_fakturpajak', 'Tanggal Faktur Pajak') !!}
        {!! Form::date('tgl_fakturpajak',$main->tgl_fakturpajak , ['id'=>'tgl_fakturpajak','class' => 'form-control'])
        !!}
    </div>
    <div class="col-md-4 mt-3">
        <div class="list-icons">
            <div class="list-icons-item dropdown">
                <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i
                        class="icon-menu7"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" id="uploadfp" data-toggle="modal" data-target="#m_modal3" data-type="fakturpajak"
                        data-id="{{$main->id_quo}}" data-value="{{$main->doc_fakturpajak}}"
                        onclick="upload_document(this)" class="dropdown-item"><i class="far fa-save ml-2"></i>Drop
                        Input</a>
                    <div class="dropdown-divider"></div>
                    <a href="#" id="uploadfp" data-toggle="modal" data-target="#m_modal4" data-type="fakturpajak"
                        data-id="{{$main->id_quo}}" data-value="{{$main->doc_fakturpajak}}"
                        onclick="classic_upload(this)" class="dropdown-item"><i class="fa fa-file ml-2"></i>Classic
                        Input</a>
                </div>
            </div>
        </div>
        @if($main->doc_fakturpajak!=null)
        <a href="{{asset('public/'.$main->doc_fakturpajak)}}"
            class="btn btn-primary btn-labeled btn-labeled-left btn-sm legitRipple text-right"><b>
                <i class="fas fa-check ml-2"></i></b>Show File</a>
        @endif
        <a href="#" id="showfp" class="text-success"> Data Tersimpan</a>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-4">
        {!! Form::label('no_fakturjual', 'No. Faktur Penjualan') !!}
        {!! Form::text('no_fakturjual',$main->no_fakturjual , ['id'=>'no_fakturjual','class' =>
        'form-control','placeholder' => 'Masukkan No Faktur Penjualan']) !!}
    </div>
    <div class="col-md-4">
        {!! Form::label('tgl_fakturjual', 'Tanggal Faktur Pajak') !!}
        {!! Form::date('tgl_fakturjual',$main->tgl_fakturjual , ['id'=>'tgl_fakturjual','class' => 'form-control']) !!}
    </div>
    <div class="col-md-4 mt-3">
        <div class="list-icons">
            <div class="list-icons-item dropdown">
                <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i
                        class="icon-menu7"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" id="uploadfj" data-toggle="modal" data-target="#m_modal3" data-type="fakturjual"
                        data-id="{{$main->id_quo}}" data-value="{{$main->doc_fakturjual}}"
                        onclick="upload_document(this)" class="dropdown-item"><i class="far fa-save ml-2"></i>Drop
                        Input</a>
                    <div class="dropdown-divider"></div>
                    <a href="#" id="uploadfj" data-toggle="modal" data-target="#m_modal4" data-type="fakturjual"
                        data-id="{{$main->id_quo}}" data-value="{{$main->doc_fakturjual}}"
                        onclick="classic_upload(this)" class="dropdown-item"><i class="fa fa-file ml-2"></i>Classic
                        Input</a>
                </div>
            </div>
        </div>
        @if($main->doc_fakturjual!=null)
        <a href="{{asset('public/'.$main->doc_fakturjual)}}"
            class="btn btn-primary btn-labeled btn-labeled-left text-right"><b>
                <i class="fas fa-check ml-2"></i></b>Show File</a>
        @endif
        <a href="#" id="showfj" class="text-success"> Data Tersimpan</a>
    </div>
</div>
<div class="modal-footer mt-5 pt-3 left">
    <button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Tutup</button>
    <button type="save" class="btn bg-primary legitRipple">Simpan</button>
</div>
{!! Form::close() !!}