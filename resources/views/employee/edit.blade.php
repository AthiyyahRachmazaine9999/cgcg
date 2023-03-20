@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
<form action="{{ route('employee.update', $getdata->id)}}" method="POST" enctype="multipart/form-data">
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Update Employee</h5>
        </div>
        <div class="card-body">
                @csrf @method('PUT')
                {!! Form::hidden('id',$getdata->id,['id'=>'id','class'=>'form-control']) !!}
                @if($getdata->emp_status=="In Active")
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Active / In Active ?</label>
                    <div class="col-lg-7">
                        {!! Form::select('emp_status', array('Active' => 'Active', 'In Active' =>
                        'In Active'),
                        $getdata->emp_status,['id' => 'emp_status', 'class' => 'form-control form-control-select2
                        emp_status',
                        'placeholder' => '*'])
                        !!}
                    </div>
                </div>
                <div class="form-group row row_resign2">
                    <label class='col-lg-3 col-form-label'>Tanggal Resign</label>
                    <div class="col-lg-7">
                        {!!
                        Form::text('tgl_resign',$getdata->tgl_resign,['id'=>'tgl_resign','class'=>'form-control
                        emp_date',
                        'placeholder'=>'Masukkan Tanggal Resign']) !!}
                    </div>
                </div>
                @else
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Active / In Active ?</label>
                    <div class="col-lg-7">
                        {!! Form::select('emp_status', array('Active' => 'Aktif', 'In Active' =>
                        'Tidak Aktif'),
                        $getdata->emp_status,['id' => 'emp_status', 'class' => 'form-control form-control-select2
                        emp_status',
                        'placeholder' => '*'])
                        !!}
                    </div>
                </div>
                <div class="form-group row row_resign">
                    <label class='col-lg-3 col-form-label'>Tanggal Resign</label>
                    <div class="col-lg-7">
                        {!!
                        Form::text('tgl_resign',$getdata->tgl_resign,['id'=>'tgl_resign','class'=>'form-control
                        emp_date',
                        'placeholder'=>'Masukkan Tanggal Resign']) !!}
                    </div>
                </div>
                @endif
                <div class="form-group row">
                    {!! Form::label('emp_name', 'Nama *', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        {!!
                        Form::text('emp_name',$getdata->emp_name,['id'=>'emp_name','class'=>'form-control',
                        'placeholder'=>'Masukkan Nama','required']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('emp_nip', 'ID Karyawan', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        {!!
                        Form::text('emp_nip',$getdata->emp_nip,['id'=>'emp_nip','class'=>'form-control',
                        'placeholder'=>'Masukkan ID Karyawan']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('bank', 'Account Bank', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-3">
                        {!!
                        Form::text('bank_acc',$getdata->bank_acc,['id'=>'bank_acc','class'=>'form-control',
                        'placeholder'=>'Masukkan Account Bank']) !!}
                    </div>
                    <div class="col-lg-3">
                        {!!
                        Form::text('no_bank_acc',$getdata->no_bank_acc,['id'=>'no_bank_acc','class'=>'form-control',
                        'placeholder'=>'Masukkan No. Account']) !!}
                    </div>
                    <div class="col-lg-3">
                        {!!
                        Form::text('nama_bank_acc',$getdata->nama_bank_acc,['id'=>'nama_bank_acc','class'=>'form-control',
                        'placeholder'=>'Masukkan Nama Account']) !!}
                    </div>
               </div>

                <div class="form-group row">
                    {!! Form::label('emp_email', 'Email Kantor', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        {!!
                        Form::text('emp_email',$getdata->emp_email,['id'=>'emp_email','class'=>'form-control',
                        'placeholder'=>'Masukkan Email']) !!}

                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('emp_email', 'Email Personal', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        {!!
                        Form::text('email_personal',$getdata->email_personal,['id'=>'email_personal','class'=>'form-control',
                        'placeholder'=>'Masukkan Email Personal']) !!}

                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('emp_phone', 'Nomor HP', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        {!!
                        Form::text('emp_phone',$getdata->emp_phone,['id'=>'emp_phone','class'=>'form-control',
                        'placeholder'=>'Masukkan Nomor HP dan WA']) !!}

                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('emp_address', 'Alamat KTP', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        {!!
                        Form::text('emp_address',$getdata->emp_address,['id'=>'emp_address','class'=>'form-control',
                        'placeholder'=>'Masukkan Alamat Sesuai KTP']) !!}

                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('alamat_domisili', 'Alamat Domisili', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        {!!
                        Form::text('alamat_domisili',$getdata->alamat_domisili,['id'=>'alamat_domisili','class'=>'form-control',
                        'placeholder'=>'Masukkan Alamat']) !!}

                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Gender</label>
                    <div class="col-lg-7">
                        {!! Form::select('gender', array('P' => 'Perempuan',
                        'L' =>'Laki-Laki'), $getdata->gender, ['id'=>'genders', 'class' => 'form-control
                        form-control-select2', 'placeholder' =>'Pilih Gender','']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Status Karyawan</label>
                    <div class="col-lg-7">
                        {!! Form::select('st_emp',$getEmp, $getdata->status_employee, ['id'=>'statuses', 'class' =>
                        'form-control form-control-select2', 'placeholder' =>'Pilih Status','']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('emp_birthplace', 'Tempat Lahir', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        {!!
                        Form::text('emp_birthplace',$getdata->emp_birthplace,['id'=>'emp_birthplace','class'=>'form-control',
                        'placeholder'=>'Masukkan Tempat Lahir']) !!}

                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('emp_birthdate', 'Tanggal Lahir', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        {!!
                        Form::text('emp_birthdate',Carbon\Carbon::parse($getdata->emp_birthdate)->format('Y-m-d'),['id'=>'emp_birthdate','class'=>'form-control
                        emp_date',
                        'placeholder'=>'Masukkan Tanggal Lahir']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('position', 'Position', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        {!!
                        Form::text('position',$getdata->position,['id'=>'position','class'=>'form-control',
                        'placeholder'=>'Masukkan Posisi']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('division_name', 'Division', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        {!! Form::select('division_name', array('Product & IT' => 'Product & IT',
                        'Operation'=>'Operation','Sales & Marketing' =>'Sales & Marketing',
                        'Management' =>'Management', 'Warehouse & Purchase' =>'Warehouse & Purchase', 'Purchase & FAT' =>'Purchase & FAT', 'FAT'=>'FAT'), $getdata->division_name, ['id' =>
                        'division_id', 'class' => 'form-control form-control-select2' , 'placeholder' =>'Pilih Divisi']) !!}
                    </div>
                </div>
                @if($data->division_id==7 || $data->division_id==8)
                <div class="form-group row emp_roles">
                    <label class='col-lg-3 col-form-label'>Role</label>
                    <div class="col-lg-7">
                        {!! Form::select('division_id', $division_id, $getdata->division_id,['id' => 'division_id',
                        'class' => 'form-control form-control-select2 division_id']) !!}
                    </div>
                </div>
                @endif
                <div class="form-group row">
                    {!! Form::label('spv_id', 'Supervisor', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        {!! Form::select('spv_id',$spv_id,$getdata->spv_id,['id' => 'spv_id', 'class' => 'form-control form-control-select2', 'placeholder' => 'Pilih Supervisor']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('cabang_id', 'Cabang', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        {!! Form::select('cabang_id', $cabang_id, $getdata->cabang_id,['id' => 'cabang_id', 'class' =>
                        'form-control
                        form-control-select2', 'placeholder' => '*']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('tgl_bergabung', 'Tanggal Bergabung', ['class' => 'col-lg-3 col-form-label']) !!}
                    <div class="col-lg-7">
                        {!!Form::text('tgl_bergabung',$getdata->tgl_bergabung,['id'=>'tgl_bergabung','class'=>'form-control
                        emp_date',
                        'placeholder'=>'Masukkan Tanggal Bergabung']) !!}
                    </div>
                </div><br>
                <legend class="bg-light text-info-800 border-bottom-success header-elements-inline">
                    Office Assets
                </legend>
                @php $i=1; $a=1; $y=1; $l=1; foreach ($asset as $assets){ @endphp
                {!! Form::hidden('id_emp[]',$assets->id_emp,['id'=>'id_emp','class'=>'form-control']) !!}
                {!! Form::hidden('id_asset[]',$assets->id,['id'=>'id_asset','class'=>'form-control']) !!}
                <div id="assets_office" class="form-item row_{{$a++}}">
                    <div class="form-group row">
                        <label class="col-lg-12 col-form-label"><b>Asset Ke {{$i++}}</b></label>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Produk</label>
                        <div class="col-lg-7">
                            <input type="text" name="namaproduk[]" value="{{$assets->namaproduk}}"
                                placeholder="Masukkan Produk" id="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Jumlah</label>
                        <div class="col-lg-7">
                            <input type="number" step="any" name="jumlah[]" value="{{$assets->jumlah}}"
                                placeholder="Masukkan Jumlah" id="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row col-lg-3">
                        <button type="button" class="btn bg-danger-400 btn-icon rounded-round legitRipple"
                            id="remove_asset_edit" data-type="remove_asset_edit1" data-id="{{$assets->id}}"
                            onclick="removeAsset(this)">
                            <b><i class="fas fa-trash"></i></b></button>
                    </div>
                </div>
                @php } @endphp
                <div id="isian_baru"></div>
                <div class="form-group row col-lg-3">
                    <button type="button" class="btn bg-primary-400 btn-icon rounded-round legitRipple" id="Add_Asset"
                        data-type="tambah_asset" onClick="addAsset(this)">
                        <b><i class="fas fa-plus"></i></b></button>
                </div>
        </div>
    </div>
    <!-- Office Assets -->
    <!-- Documents -->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Documents</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @csrf
            <div class="form-group row col-sm-12">
                <span class="text-danger font-light" style="font-family: Lucida Console;">Dokumen Dalam Format
                    .PDF</span>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">KTP/Personal ID</label>
                @php if($getdata->doc_ktp==null) { @endphp
                <div class="col-lg-3">
                    <input type="file" name="doc_ktp" value="{{$getdata->doc_ktp}}" class="file-input form-control">
                </div>
                @php } else { @endphp
                <div class="col-lg-3">
                    <input type="file" name="doc_ktp" value="{{$getdata->doc_ktp}}" class="file-input form-control">
                </div>
                <a href="{{ asset($getdata->doc_ktp) }}" id="btn"><i class="fa fa-check"></i>
                    Sudah Terisi. Check Disini</a>
                @php } @endphp
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Kartu Keluarga</label>
                @php if($getdata->doc_kk==null) { @endphp
                <div class="col-lg-3">
                    <input type="file" name="doc_kk" value="{{$getdata->doc_kk}}" class="file-input form-control">
                </div>
                @php } else { @endphp
                <div class="col-lg-3">
                    <input type="file" name="doc_kk" value="{{$getdata->doc_kk}}" class="file-input form-control">
                </div>
                <a href="{{ asset($getdata->doc_kk) }}" id="btn"><i class="fa fa-check"></i>
                    Sudah Terisi. Check Disini</a>
                @php } @endphp
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Akta Nikah</label>
                @php if($getdata->doc_aktaNikah==null) { @endphp
                <div class="col-lg-3">
                    <input type="file" name="doc_aktaNikah" value="{{$getdata->doc_aktaNikah}}"
                        class="file-input form-control">
                </div>
                @php } else { @endphp
                <div class="col-lg-3">
                    <input type="file" name="doc_aktaNikah" value="{{$getdata->doc_aktaNikah}}"
                        class="file-input form-control">
                </div>
                <a href="{{ asset($getdata->doc_aktaNikah) }}" id="btn"><i class="fa fa-check"></i>
                    Sudah Terisi. Check Disini</a>
                @php } @endphp
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Ijazah/Transkrip Nilai</label>
                @php if($getdata->doc_pendidikan==null) { @endphp
                <div class="col-lg-3">
                    <input type="file" name="doc_pendidikan" value="{{$getdata->doc_pendidikan}}"
                        class="file-input form-control">
                </div>
                @php } else { @endphp
                <div class="col-lg-3">
                    <input type="file" name="doc_pendidikan" value="{{$getdata->doc_pendidikan}}"
                        class="file-input form-control">
                </div>
                <a href="{{ asset($getdata->doc_pendidikan) }}" id="btn"><i class="fa fa-check"></i>
                    Sudah Terisi. Check Disini</a>
                @php } @endphp
            </div>

            <div class="form-group row">
                <label class="col-lg-3 col-form-label">NPWP</label>
                @php if($getdata->doc_npwp==null) { @endphp
                <div class="col-lg-3">
                    <input type="file" name="doc_npwp" value="{{$getdata->doc_npwp}}" class="file-input form-control">
                </div>
                @php } else { @endphp
                <div class="col-lg-3">
                    <input type="file" name="doc_npwp" value="{{$getdata->doc_pendidikan}}"
                        class="file-input form-control">
                </div>
                <a href="{{ asset($getdata->doc_npwp) }}" id="btn"><i class="fa fa-check"></i>
                    Sudah Terisi. Check Disini</a>
                @php } @endphp
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Bank Account</label>
                @php if($getdata->doc_bank==null) { @endphp
                <div class="col-lg-3">
                    <input type="file" name="doc_bank" value="{{$getdata->doc_bank}}" class="file-input form-control">
                </div>
                @php } else { @endphp
                <div class="col-lg-3">
                    <input type="file" name="doc_bank" value="{{$getdata->doc_bank}}" class="file-input form-control">
                </div>
                <a href="{{ asset($getdata->doc_bank) }}" id="btn"><i class="fa fa-check"></i>
                    Sudah Terisi. Check Disini</a>
                @php } @endphp
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Vaksin</label>
                @php if($getdata->doc_vaksin==null) { @endphp
                <div class="col-lg-3">
                    <input type="file" name="doc_vaksin" value="{{$getdata->doc_vaksin}}"
                        class="file-input form-control">
                </div>
                @php } else { @endphp
                <div class="col-lg-3">
                    <input type="file" name="doc_vaksin" value="{{$getdata->doc_vaksin}}"
                        class="file-input form-control">
                </div>
                <a href="{{ asset($getdata->doc_vaksin) }}" id="btn">
                    <i class="fa fa-check"></i>Sudah Terisi. Check Disini</a>
                @php } @endphp
            </div>
                @if($data->division_id==7 || $data->division_id==8)
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Dokumen Kontrak</label>
                    @php if($getdata->doc_contract==null) { @endphp
                    <div class="col-lg-3">
                        <input type="file" name="doc_contract" value="{{$getdata->doc_contract}}"
                            class="file-input form-control">
                    </div>
                    @php } else { @endphp
                    <div class="col-lg-3">
                        <input type="file" name="doc_contract" value="{{$getdata->doc_contract}}"
                            class="file-input form-control">
                    </div>
                    <a href="{{ asset($getdata->doc_contract) }}" id="btn">
                        <i class="fa fa-check"></i>Sudah Terisi. Check Disini</a>
                    @php } @endphp
                </div>

                @if(count($dok)!=0)
                @foreach($dok as $doks)
                {!! Form::hidden('id_dokumen[]',$doks->id,['id'=>'id','class'=>'form-control']) !!}
                <div id="add_dokumen" class="forms_doks">
                    <div class="form-group row">
                        <input type="text" name="nama_dok[]" value="{{$doks->nama_dokumen}}"
                            class="form-control col-lg-3">
                        @php if($doks->dok_emp==null) { @endphp
                        <div class="col-lg-3">
                            <input type="file" name="dok_emp[]" value="{{$doks->dok_emp}}"
                                class="file-input form-control">
                        </div>
                        @php } else { @endphp
                        <div class="col-lg-3">
                            <input type="file" name="dok_emp[]" value="{{$doks->dok_emp}}"
                                class="file-input form-control">
                        </div>
                        <a href="{{ asset($doks->dok_emp) }}" id="btn">
                            <i class="fa fa-check"></i>Sudah Terisi. Check Disini</a>
                        @php } @endphp
                    </div>
                </div>
                @endforeach
                @endif
                <div id="point_dokumen"></div>
                <button type="button" onclick="add_dokumen(this)" data-type="tambah_dokumen"
                    class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i
                            class="fas fa-plus"></i></b></button>
                @endif
                <br>
                <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                'data-method' => 'hrm/employee', 'type' => 'button','onclick'=>'cancel(this)'])
                !!}
                <button type="submit" class="btn btn-primary">Update<i class="far fa-save ml-2"></i>
                </button>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/form-employee.js?v=').rand() }}" type=" text/javascript"></script>
@endsection