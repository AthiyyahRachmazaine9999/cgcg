@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card" id="">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Employee Details</h5>
        </div>
        <div class="card-body">
            {!! Form::hidden('tgl_join',$data->tgl_bergabung,['id'=>'tgl_join','class'=>'form-control']) !!}
            {!! Form::hidden('now',Carbon\carbon::now()->format('Y-m-d'),['id'=>'now_dates','class'=>'form-control'])
            !!}
            <div class="form-group row col-md-12">
                <table class="table table-sm table-borderless mb-6">
                    <tr>
                        <td class="pl-8 w-60" scope="row"><strong>Employee Name</strong></td>
                        <td>:</td>
                        <td>{{$data->emp_name}}</td>

                        <td class="pl-8 w-60" scope="row"><strong>Employee ID</strong></td>
                        <td>:</td>
                        <td>{{$data->emp_nip}}</td>
                    </tr>
                    <tr>
                        <td class="pl-8 w-60" scope="row"><strong>Email Kantor</strong></td>
                        <td>:</td>
                        <td>{{$data->emp_email}}</td>

                        <td class="pl-8 w-60" scope="row"><strong>Email Personal</strong></td>
                        <td>:</td>
                        <td>{{$data->email_personal}}</td>
                    </tr>
                    <tr>
                        <td class="pl-8 w-60" scope="row"><strong>Alamat KTP</strong></td>
                        <td>:</td>
                        <td class="w-50">{{$data->emp_address}}</td>

                        <td class="pl-8 w-60" scope="row"><strong>Alamat Domisili</strong></td>
                        <td>:</td>
                        <td class="w-50">{{$data->alamat_domisili}}</td>
                    </tr>
                    <tr>
                        <td class="pl-8 w-60" scope="row"><strong>Nomer HP</strong></td>
                        <td>:</td>
                        <td>{{$data->emp_phone}}</td>

                        <td class="pl-8 w-60" scope="row"><strong>Cabang</strong></td>
                        <td>:</td>
                        <td>{{$cabang}}</td>
                    </tr>
                    <tr>
                        <td class="pl-8 w-60" scope="row"><strong>Tempat lahir</strong></td>
                        <td>:</td>
                        <td>{{$data->emp_birthplace}}</td>

                        <td class="pl-8 w-60" scope="row"><strong>Tanggal Lahir</strong></td>
                        <td>:</td>
                        <td>{{Carbon\carbon::parse($data->emp_birthdate)->format('Y-m-d')}}</td>
                    </tr>
                    <tr>
                        <td class="pl-8 w-60" scope="row"><strong>Division</strong></td>
                        <td>:</td>
                        <td>{{$data->division_name}}</td>

                        <td class="pl-8 w-60" scope="row"><strong>Position</strong></td>
                        <td>:</td>
                        <td>{{$data->position}}</td>
                    </tr>
                    <tr>
                        <td class="pl-8 w-60" scope="row"><strong>Tanggal Bergabung</strong></td>
                        <td>:</td>
                        <td>{{$data->tgl_bergabung}}</td>

                        <td class="pl-8 w-60" scope="row"><strong>Lama Bergabung</strong></td>
                        <td>:</td>
                        <td>
                            <div id="count_days"></div>
                        </td>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                        <td class="pl-8 w-60" scope="row"><strong>Supervisor</strong></td>
                        <td>:</td>
                        <td>{{$spv}}</td>

                        <td class="pl-8 w-60" scope="row"><strong>Status Karyawan</strong></td>
                        <td>:</td>
                        @php
                        if($data->emp_status=='Active'){ @endphp
                        <td class="text-primary"><b>ACTIVE</b></td>
                        @php } else { @endphp
                        <td class="text-danger"><b>IN ACTIVE</b><em> Resign/Out {{$data->tgl_resign}}</em></td>
                        <td></td>
                        @php } @endphp
                    </tr>
                    <tr>
                        <td class="pl-8 w-60" scope="row"><strong>Status Karyawan</strong></td>
                        <td>:</td>
                        <td>{{$data->status_employee==null ? null : getEmpStatus($data->status_employee)->code." - ".getEmpStatus($data->status_employee)->note}}</td>
                    </tr>                
                </table>
            </div>
        </div>
    </div>
    @if($jika!=null)
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Office Assets</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        @csrf
        <div class="card-body">
            @if($jika!=null)
            @php $i=1; foreach ($asset as $assets){ @endphp
            <div class="form-group row">
                <label class="col-lg-12 col-form-label"><b>Asset Ke {{$i++}}</b></label>
                <label class="col-lg-3 col-form-label">Barang</label>
                <div class="col-lg-7">
                    <input type="text" name="namaproduk[]" value="{{$assets->namaproduk}}" placeholder="Masukkan Produk"
                        id="" class="form-control" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Jumlah</label>
                <div class="col-lg-7">
                    <input type="text" step="any" name="jumlah[]" value="{{$assets->jumlah}}"
                        placeholder="Masukkan Jumlah" id="" class="form-control" readonly>
                </div>
            </div>
            @php } @endphp
            @endif
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Detail Documents</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        @csrf
        <div class="card-body">
            @csrf
        <div class="row">
         <div class="col-lg-6">
            <div class="form-group row">
                <label class="col-lg-4 col-form-label">KTP / Personal ID</label>
                <div class="fallback">
                    <div class="custom-file">
                        @php if($data->doc_ktp!=null){ @endphp
                        <a href="{{ asset($data->doc_ktp) }}" class="btn btn-primary">SHOW</a>
                        @php }else{ @endphp
                        <button class="btn btn-primary" data-type="ktp" data-id="{{$data->id}}"
                            data-doc="{{$data->doc_ktp}}" disabled>SHOW</button>
                        @php } @endphp
                    </div>
                </div>
            </div>
            </div>
         <div class="col-lg-6">
            <div class="form-group row">
                <label class="col-lg-4 col-form-label">Kartu Keluarga</label>
                <div class="fallback">
                    <div class="custom-file">
                        @php if($data->doc_kk!=null){ @endphp
                        <a href="{{ asset($data->doc_kk) }}" class="btn btn-primary">SHOW</a>
                        @php }else{ @endphp
                        <button class="btn btn-primary" disabled>SHOW</button>
                        @php } @endphp
                    </div>
                </div>
            </div>
            </div>
         <div class="col-lg-6">
            <div class="form-group row">
                <label class="col-lg-4 col-form-label">Akta Nikah</label>
                <div class="fallback">
                    <div class="custom-file">
                        @php if($data->doc_aktaNikah!=null){ @endphp
                        <a href="{{ asset($data->doc_aktaNikah) }}" class="btn btn-primary">SHOW</a>
                        @php }else{ @endphp
                        <button class="btn btn-primary" disabled>SHOW</button>
                        @php } @endphp
                    </div>
                </div>
            </div>
            </div>
         <div class="col-lg-6">
            <div class="form-group row">
                <label class="col-lg-4 col-form-label">Ijazah/Transkrip Nilai</label>
                <div class="fallback">
                    <div class="custom-file">
                        @php if($data->doc_pendidikan!=null){ @endphp
                        <a href="{{ asset($data->doc_pendidikan) }}" class="btn btn-primary">SHOW</a>
                        @php }else{ @endphp
                        <button class="btn btn-primary" disabled>SHOW</button>
                        @php } @endphp
                    </div>
                </div>
            </div>
            </div>
         <div class="col-lg-6">
            <div class="form-group row">
                <label class="col-lg-4 col-form-label">NPWP</label>
                <div class="fallback">
                    <div class="custom-file">
                        @php if($data->doc_npwp!=null){ @endphp
                        <a href="{{ asset($data->doc_npwp) }}" class="btn btn-primary">SHOW</a>
                        @php }else{ @endphp
                        <button class="btn btn-primary" disabled>SHOW</button>
                        @php } @endphp
                    </div>
                </div>
            </div>
            </div>
         <div class="col-lg-6">
            <div class="form-group row row">
                <label class="col-lg-4 col-form-label">Bank Account</label>
                <div class="fallback">
                    <div class="custom-file">
                        @php if($data->doc_bank!=null){ @endphp
                        <a href="{{ asset($data->doc_bank) }}" class="btn btn-primary">SHOW</a>
                        @php }else{ @endphp
                        <button class="btn btn-primary" disabled>SHOW</button>
                        @php } @endphp
                    </div>
                </div>
            </div>
            </div>
         <div class="col-lg-6">
            <div class="form-group row">
                <label class="col-lg-4 col-form-label">Vaksin</label>
                <div class="fallback">
                    <div class="custom-file">
                        @php if($data->doc_vaksin!=null){ @endphp
                        <a href="{{ asset($data->doc_vaksin) }}" class="btn btn-primary">SHOW</a>
                        @php }else{ @endphp
                        <button class="btn btn-primary" disabled>SHOW</button>
                        @php } @endphp
                    </div>
                </div>
            </div>
        </div>
         <div class="col-lg-6">
            <div class="form-group row">
                <label class="col-lg-4 col-form-label">Dokumen Kontrak</label>
                <div class="fallback">
                    <div class="custom-file">
                        @php if($data->doc_contract!=null){ @endphp
                        <a href="{{ asset($data->doc_contract) }}" class="btn btn-primary">SHOW</a>
                        @php }else{ @endphp
                        <button class="btn btn-primary" disabled>SHOW</button>
                        @php } @endphp
                    </div>
                </div>
            </div>
        </div>
        @if(count($dok)!=0)
        @foreach($dok as $dok)
        <div class="col-lg-6">
            <div class="form-group row">
                <label class="col-lg-4 col-form-label">{{$dok->nama_dokumen}}</label>
                <div class="fallback">
                    <div class="custom-file">
                        @php if($dok->dok_emp!=null){ @endphp
                        <a href="{{ asset($dok->dok_emp) }}" class="btn btn-primary">SHOW</a>
                        @php }else{ @endphp
                        <button class="btn btn-primary" disabled>SHOW</button>
                        @php } @endphp
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
        </div>
    </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/form-employee.js?v=').rand() }}" type="text/javascript"></script>
@endsection