@extends('layouts.head') @section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h3 class="card-title">Settlement</h3>
            <div class="header-elements">
                <!-- <a href="{{route('cash.download', $cash->id)}}" class="btn btn-danger btn-sm ml-3"
                    onclick="PrintCashAdv(this)" data-dtl="{{$cash->id_cash}}" data-id_cash="{{$cash->id}}"><i
                        class="icon-printer mr-2"></i>Print</a> -->
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

                @if($cash->other_files!=null)
                <div class="form-group row rowfile_awal">
                    {!! Form::label('file', 'Attachment', ['class' => 'col-lg-3
                    col-form-label']) !!}
                    <div class="col-lg-3">
                        <a href="{{ asset($cash->other_files) }}" class="btn btn-primary btn-sm">SHOW</a>
                    </div>
                </div>
                @else
                <div class=" form-group row">
                    {!! Form::label('file', 'Attachment', ['class' => 'col-lg-3
                    col-form-label']) !!}
                    <div class="col-lg-7">
                        <input type="file" id="file" name="files" class="form-control">
                    </div>
                </div>
                @endif

                <div class="form-group row">
                    {!! Form::label('note', 'Note', ['class' => 'col-lg-3
                    col-form-label']) !!}
                    <div class="col-lg-7">
                        <textarea id="file" name="note" value="" id="notes" placeholder="Note" class="form-control"
                            readonly>{{$cash->note}}</textarea>
                    </div>
                </div>
                @if($capp!=null)
                <div class="card-header bg-light text-primary-800 border-primary header-elements-inline">
                    <h6 class="card-title">Known By</h6>
                </div> <br>
                @foreach($app as $apps)
                <div class="form-group row">
                    {!! Form::label('invoice_kwitansi',$apps->approval_by, ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        <div class="form-control">{{emp_name($apps->status_by)}}</div>
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
                <table class="table table-bordered" id="setts">
                    <thead class="success">
                        <tr class="text-center bg-teal">
                            <th>Tanggal Kegiatan / Pekerjaan</th>
                            <th>Nama Kegiatan / Pekerjaan</th>
                            <th>Deskripsi</th>
                            <th>Attachment</th>
                            <th>Estimasi Biaya</th>
                            <th>Approved Cost</th>
                            <th>Settlement Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; $p=1; $s=1; $l=1; $q=1; foreach($dtl as $dtl) { @endphp
                        {!! Form::hidden('id_dtl[]',$dtl->id,['id'=>'id_dtl','class'=>'form-control']) !!}
                        <input type="hidden" id="est_{{$l++}}" value="{{$dtl->est_biaya}}" name="estimasi[]"
                            class="form-control">
                        <input type="hidden" id="hasil_{{$q++}}" value="" name="hasil[]" class="form-control">
                        <tr class="text-center">
                            <td>{{$dtl->tgl_pekerjaan}}</td>
                            <td>{{$dtl->nama_pekerjaan}}</td>
                            <td>{{$dtl->deskripsi}}</td>
                            <td>
                                @if($dtl->up_files!=null)
                                <a href="{{ asset($dtl->set_files) }}" class="btn btn-outline-primary btn-sm">SHOW</a>
                                @else
                                <input type="file" id="file" name="up_files[]" class="form-control" required>
                                @endif
                            </td>
                            <td>{{number_format($dtl->est_biaya)}}
                            </td>
                            <td class="awals">{{number_format($dtl->est_biaya)}}</td>
                            <td>
                                <input type="text" id="" class="form-control amount" name="est_biaya[]"
                                    value="{{$dtl->set_nominal}}" placeholder="Enter Price">
                            </td>
                        </tr>
                        @php $total+=$dtl->est_biaya; } @endphp
                    </tbody>
                </table><br>
                <!-- <button class="btn btn-primary text-right" id="calc" class="">Calculate</button> -->
                @endif
                <br>
                <div class="card-body">
                    <div class="d-md-flex flex-md-wrap">
                        {!!Form::hidden('apps_total',$total,['id'=>'totalss','class'=>'form-control
                        app_ttl','placeholder'=>'Subtotal','readonly'])!!}
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
                                            <td>{!!Form::text('app_total',number_format($total),['id'=>'app','class'=>'form-control
                                                app_ttl','placeholder'=>'Subtotal','readonly'])!!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Total Settlement:</th>
                                            <td class="text-right">
                                            <td> {!!Form::number('hasil',$cash->total_set,['id'=>'totals','class'=>'','placeholder'=>'Subtotal','readonly'])!!}
                                            </td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Selsisih / Lebih Biaya:</th>
                                            <td class="text-right">
                                            <td> {!!Form::number('sisa',$cash->sisa_lebih,['id'=>'sisas','class'=>'','placeholder'=>'Subtotal','readonly'])!!}
                                            </td>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <!-- //Button -->
                            <div class="text-right" style="padding-top:50px">
                                {!! Form::button('Back', ['class' => 'btn btn-light btn-cancel',
                                'data-method' =>'finance/cash_advance','type' =>
                                'button','onclick'=>'cancel(this)']) !!}
                                <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
        </form>

        <!-- /basic layout -->
    </div>
    @endsection @section('script')
    <script src="{{ asset('ctrl/finance/seattlement-form.js') }}" type="text/javascript"></script>
    <script src="{{ asset('ctrl/finance/cash_adv-form.js') }}" type="text/javascript"></script>
    @endsection