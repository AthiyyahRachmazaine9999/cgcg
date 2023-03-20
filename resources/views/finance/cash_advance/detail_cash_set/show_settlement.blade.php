@extends('layouts.head') @section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h3 class="card-title">Settlement</h3>
            <div class="header-elements">
            </div>
        </div>
        <form action="{{ route('settlement.updates', $cash->id )}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                {!! Form::hidden('id',$cash->id,['id'=>'id','class'=>'form-control']) !!}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-check-input-styled-primary"
                        value="pro_priceType">Nama*</label>
                    <div class="col-lg-7">
                        {!! Form::text('emp_id', emp_name($cash->emp_id),['id' => 'emps', 'class'
                        => 'form-control form-control-select2', 'placeholder' => '*', 'readonly']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('div_id', 'Divisi', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        {!! Form::text('division_id',div_name($cash->div_id),['id' => 'divs', 'class'
                        => 'form-control form-control-select2', 'placeholder' => '*', 'readonly']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Posisi</label>
                    <div class="col-lg-7">
                        <input type="text" id="posisi_edit" class="form-control" name="jabatan"
                            value="{{$cash->position}}" placeholder="Masukkan Posisi" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Tujuan</label>
                    <div class="col-lg-3">
                        <input type="text" id="posisi_edit" class="form-control" name="des_provinsi"
                            value="{{province($cash->des_provinsi)}}" placeholder="Masukkan Posisi" readonly>
                    </div>

                    <div class="col-lg-3">
                        <input type="text" id="posisi_edit" class="form-control" name="des_kota"
                            value="{{city($cash->des_kota)}}" placeholder="Masukkan Posisi" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Tanggal Berangkat*</label>
                    <div class="col-lg-7">
                        <input type="text" id="berangkat" class="form-control date" name="tgl_berangkat"
                            value="{{$cash->tgl_berangkat}}" placeholder="Tanggal Berangkat" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Tanggal Pulang*</label>
                    <div class="col-lg-7">
                        <input type="text" id="pulang" value="{{$cash->tgl_pulang}}" class="form-control date"
                            name="tgl_pulang" placeholder="Tanggal Pulang" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Tanggal Pengajuan*</label>
                    <div class="col-lg-7">
                        <input type="text" id="pulang" value="{{$cash->created_at}}" class="form-control date"
                            name="tgl_pulang" placeholder="Tanggal Pengajuan" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Estimasi Waktu</label>
                    <div class="col-lg-7">
                        <input type="text" id="est_waktu" value="{{$cash->est_waktu}}" name="est_waktu"
                            class="form-control" placeholder="Estimasi Waktu" readonly>
                    </div>
                </div>
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

                <div class="form-group row">
                    {!! Form::label('note', 'Note By Finance', ['class' => 'col-lg-3
                    col-form-label']) !!}
                    <div class="col-lg-7">
                        <textarea id="file" name="note" value="" id="notes" placeholder="Note" class="form-control"
                            readonly>{{$cash->note}}</textarea>
                    </div>
                </div>
                @if($Cset_app!=0)
                <div class="card-header bg-light text-primary-800 border-primary header-elements-inline">
                    <h6 class="card-title">Settlement Approval By</h6>
                </div> <br>
                @foreach($sets_app as $apps)
                <div class="form-group row">
                    {!! Form::label('invoice_kwitansi',$apps->approval_by, ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        <div class="form-control">{{user_name($apps->created_by)}}</div>
                    </div>
                </div>
                @endforeach
                @endif
                <br>
                <!-- <span>Berikut List Kegiatan / Pekerjaan</span> -->
                @if($dtls!=null)
                <div class="card-header bg-light text-primary-800 border-bottom-primary header-elements-inline">
                    <h6 class="card-title">Settlement Amount</h6>
                </div><br>
                <div class="form-group">
                    @php $total = 0; $p=1; $s=1; $l=1; foreach($dtl as $dtl) { @endphp
                    <table class="table table-bordered" id="setts">
                        <thead class="success">
                            <tr class="text-center bg-teal">
                                <th>Tanggal Kegiatan / Pekerjaan</th>
                                <th>Nama Kegiatan / Pekerjaan</th>
                                <th>Deskripsi</th>
                                <th>Attachment</th>
                                <th>Estimasi Biaya</th>
                                <th>Approved Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>{{$dtl->tgl_pekerjaan}}</td>
                                <td>{{$dtl->nama_pekerjaan}}</td>
                                <td>{{$dtl->deskripsi}}</td>
                                <td>
                                    @if($dtl->up_files!=null)
                                    <a href="{{ asset($dtl->up_files) }}"
                                        class="btn btn-outline-primary btn-sm">SHOW</a>
                                    @else
                                    <button type="button" class="btn btn-outline-primary btn-sm" disabled>SHOW</button>
                                    @endif
                                </td>
                                <td>{{number_format($dtl->est_biaya)}}
                                </td>
                                <td class="awals">{{number_format($dtl->est_biaya)}}</td>
                            </tr>
                            @if($Cset_cash!=0)
                            <tr>
                                <th colspan="7" class="text-center">
                                    <a class="text-primary" onClick="details_sets(this)" data-id_dtl="{{$dtl->id}}"
                                        data-id_cash="{{$dtl->id_cash}}">
                                        DETAILS </a>
                                </th>
                            </tr>
                            @endif
                        <tfoot class="detailss_{{$dtl->id}}"></tfoot>
                        </tbody>
                    </table>
                    <br><br>
                    @php $total+=$dtl->est_biaya; } @endphp
                </div>
                <br>
                @endif
                <br>
                <div class="card-body">
                    @csrf
                    <div class="d-md-flex flex-md-wrap">

                        <div class="pt-2 mb-3 wmin-md-400 ml-auto">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th>Total Estimasi Biaya:</th>
                                            <td class="text-right">
                                            <td>{{number_format($total)}}</td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Total Approved:</th>
                                            <td class="text-right">
                                            <td>{{number_format($total)}}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Settlement:</th>
                                            <td class="text-right">
                                            <td>{{number_format($cash->total_set)}}
                                            </td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Selisih / Lebih Biaya</th>
                                            <td class="text-right">
                                            <td>{{number_format($cash->sisa_lebih)}}
                                            </td>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <div class="text-right" style="padding-top:50px">
                                {!! Form::button('Back', ['class' => 'btn btn-light btn-cancel',
                                'data-method' =>'finance/cash_advance','type' =>
                                'button','onclick'=>'cancel(this)']) !!}
                                @if($mine->spv_id == $usr->id && $fs_app==null)
                                <button type="button" onClick="SettlementApp(this)" data-id="{{$cash->id}}"
                                    data-type="Supervisor" class="btn btn-primary setapp_spv">Approved</button>
                                @elseif($mine->id==10 && $fs_app==null || $mine->division_id==3 && $fs_app==null)
                                <button type="button" class="btn btn-primary setapp_finance"
                                    onClick="SettlementApp(this)" data-type="FinanceManager"
                                    data-id="{{$cash->id}}">Approved</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        </form>

        <!-- /basic layout -->
    </div>
    @endsection @section('script')
    <script src="{{ asset('ctrl/finance/settlement-form.js?v=').rand() }}" type="text/javascript"></script>
    <script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript"></script>
    @endsection