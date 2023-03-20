@extends('layouts.head') @section('content')
<div class="content">
    <!-- Basic layout-->
<form action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data">
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Create Employee</h5>
        </div>

        <div class="page-wrapper">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Active / In Active ?</label>
                        <div class="col-lg-7">
                            <input type="text" name="emp_status" value="Active" class="form-control"
                                placeholder="Status" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Nama</label>
                        <div class="col-lg-7">
                            <input type="text" name="emp_name" class="form-control" placeholder="Nama Karyawan">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>ID Karyawan</label>
                        <div class="col-lg-7">
                            <input type="text" name="emp_nip" id="emp_nip" class="form-control"
                                placeholder="ID Karyawan">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Account Bank</label>
                        <div class="col-lg-3">
                            <input type="text" name="bank_acc" id="" class="form-control" placeholder="Account Bank">
                        </div>
                        <div class="col-lg-3">
                            <input type="text" name="no_bank_acc" id="" class="form-control" placeholder="Account No.">
                        </div>
                        <div class="col-lg-3">
                            <input type="text" name="nama_bank_acc" id="" class="form-control"
                                placeholder="Account Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Email Kantor</label>
                        <div class="col-lg-7">
                            <input type="text" name="emp_email" class="form-control" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Email Personal</label>
                        <div class="col-lg-7">
                            <input type="text" name="email_personal" class="form-control" placeholder="Email Personal"
                                >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Nomor HP</label>
                        <div class="col-lg-7">
                            <input type="text" name="emp_phone" class="form-control" placeholder="Nomor HP dan WA"
                                >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Alamat KTP</label>
                        <div class="col-lg-7">
                            <input type="text" name="emp_address" class="form-control" placeholder="Alamat Sesuai KTP"
                                >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Alamat Domisili</label>
                        <div class="col-lg-7">
                            <input type="text" name="alamat_domisili" class="form-control"
                                placeholder="Alamat Domisili">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Gender</label>
                        <div class="col-lg-7">
                            {!! Form::select('gender', array('P' => 'Perempuan',
                            'L' =>'Laki-Laki'), '', ['id'=>'genders', 'class' => 'form-control
                            form-control-select2', 'placeholder' =>'Pilih Gender']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Status Karyawan</label>
                        <div class="col-lg-7">
                            {!! Form::select('st_emp',$status_emp,'',
                            ['id' => 'statuses', 'class' => 'form-control form-control-select2 leaves',
                            'placeholder'
                            =>'*']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Tempat Lahir</label>
                        <div class="col-lg-7">
                            <input type="text" name="emp_birthplace" class="form-control" placeholder="Tempat Lahir"
                                >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label' for="datepicker">Tanggal Lahir</label>
                        <div class="col-lg-7">
                            <input type="text" name="emp_birthdate" class="form-control emp_date"
                                placeholder="Tanggal Lahir">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Position</label>
                        <div class="col-lg-7">
                            <input type="text" name="position" id="position" class="form-control" placeholder="Posisi">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Division</label>
                        <div class="col-lg-7">
                            {!! Form::select('division_name', array('Product & IT' => 'Product & IT',
                            'Operation'=>'Operation','Sales & Marketing' =>'Sales & Marketing',
                            'Management' =>'Management', 'Warehouse & Purchase' =>'Warehouse & Purchase', 'Purchase & FAT' =>'Purchase & FAT', 'FAT'=>'FAT'), '', ['id'
                            =>'division_name', 'class' => 'form-control form-control-select2', 'placeholder' =>
                            '*']) !!}
                        </div>
                    </div>
                    @if($data->division_id==7 || $data->division_id==8)
                    <div class="form-group row emp_roles">
                        <label class='col-lg-3 col-form-label'>Role</label>
                        <div class="col-lg-7">
                            <select class="form-control form-control-select2" name="division_id" id="division_id"
                                required>
                                <option value="" id="division_id" required>-- Pilih --</option>
                                <div></div>
                                @foreach ($division as $division)
                                <option value="{{ $division->id }}">{{ $division->div_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Supervisor</label>
                        <div class="col-lg-7">
                            {!! Form::select('spv_id',$spv,'',['id' => 'spv_id', 'class' =>'form-control form-control-select2 spv_id',
                            'placeholder' => 'Pilih Supervisor']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Cabang</label>
                        <div class="col-lg-7">
                            <select class="form-control form-control-select2" name="cabang_id" id="cabang_id" required>
                                <option value="" id="cabang_id" required>-- Pilih --</option>
                                <div></div>
                                @foreach ($cabang as $cabang)
                                <option value="{{ $cabang->id }}" allowclear="true" placeholder="Select">
                                    {{ $cabang->cabang_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Tanggal Bergabung</label>
                        <div class="col-lg-7">
                            <input type="text" name="tgl_bergabung" class="form-control emp_date"
                                placeholder="Tanggal Bergabung">
                        </div>
                    </div><br>
                    <legend class="bg-light text-info-800 border-bottom-success header-elements-inline">
                        Office Assets
                    </legend>
                    <div id="assets_office" class="form-item">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Produk</label>
                            <div class="col-lg-7">
                                <input type="text" name="namaproduk[]" placeholder="Masukkan Produk"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Jumlah</label>
                            <div class="col-lg-7">
                                <input type="number" step="any" name="jumlah[]" placeholder="Masukkan Jumlah"
                                    class="form-control">
                            </div>
                        </div>
                        <div id="isian_baru"></div>
                    </div>

                    <div class="form-group row col-lg-3">
                        <button type="button" class="btn bg-primary-400 btn-icon rounded-round legitRipple"
                            id="Add_Asset" data-type="tambah_asset" onClick="addAsset(this)">
                            <b><i class="fas fa-plus"></i></b></button>
                    </div>
                </div>
        </div>
    </div>
    <!-- Office Assets-->
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
            <div class="form-group row col-lg-6">
                <span class="text-danger font-light" style="font-family: Lucida Console;">Dokumen Dalam Format
                    .PDF</span>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">KTP/Personal ID</label>
                <div class="col-lg-7">
                    <input type="file" name="doc_ktp" class="file-input form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Kartu Keluarga</label>
                <div class="col-lg-7">
                    <input type="file" name="doc_kk" class="file-input form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Akta Nikah</label>
                <div class="col-lg-7">
                    <input type="file" name="doc_aktaNikah" class="file-input form-control">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Ijazah/Transkrip Nilai</label>
                <div class="col-lg-7">
                    <input type="file" name="doc_pendidikan" class="file-input form-control">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 col-form-label">NPWP</label>
                <div class="col-lg-7">
                    <input type="file" name="doc_npwp" class="file-input form-control">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Bank Account</label>
                <div class="col-lg-7">
                    <input type="file" name="doc_bank" class="file-input form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Vaksin</label>
                <div class="col-lg-7">
                    <input type="file" name="doc_vaksin" class="file-input form-control">
                </div>
            </div>
                @if($data->division_id==7 || $data->division_id==8)
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Kontak Pekerjaan</label>
                    <div class="col-lg-7">
                        <input type="file" name="doc_contract" class="file-input form-control">
                    </div>
                </div>
                <div id="add_dokumen" class="forms_doks">
                    <div id="point_dokumen"></div>
                </div>

                <div class="form-group row col-lg-3">
                    <button type="button" class="btn bg-primary-400 btn-icon rounded-round legitRipple"
                        onClick="add_dokumen(this)"><b><i class="fas fa-plus"></i></b></button>
                </div>
                @endif
                <br>

            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                'data-method' => 'hrm/employee', 'type' => 'button','onclick'=>'cancel(this)'])
                !!}
                <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i>
                </button>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/form-employee.js?v=').rand() }}" type="text/javascript"></script>
@endsection