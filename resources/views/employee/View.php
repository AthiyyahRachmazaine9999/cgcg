@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
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

    <form action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
            <div class="card-body">
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Nama</label>
                 <div class="col-lg-7">
                <input type="text" name="emp_name" class="form-control" placeholder="Nama" required>
                </div>
            </div>
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Email</label>
                 <div class="col-lg-7">
                <input type="text" name="emp_email" class="form-control" placeholder="Email" required>
                </div>
            </div>
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Phone</label>
                 <div class="col-lg-7">
                <input type="text" name="emp_phone" class="form-control" placeholder="Nomor HP" required>
                </div>
            </div>
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Alamat</label>
                 <div class="col-lg-7">
                <input type="text" name="emp_address" class="form-control" placeholder="Alamat" required>
                </div>
            </div>
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Tempat Lahir</label>
                 <div class="col-lg-7">
                <input type="text" name="emp_birthplace" class="form-control" placeholder="Tempat Lahir" required>
                </div>
            </div>
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label' for="datepicker">Tanggal Lahir</label>
                 <div class="col-lg-7">
                <input type="date" name="emp_birthdate"  id="datepicker" class="form-control" placeholder="dd/mm/yyyy" required>
                </div>
            </div>
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label' >NIP</label>
                 <div class="col-lg-7" >
                <input type="text" name="emp_nip" id="emp_nip" class="form-control" placeholder="NIP" required>
                </div>
            </div> 
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label' >Position</label>
                 <div class="col-lg-7" >
                <input type="text" name="position" id="position" class="form-control" placeholder="Posisi" required>
                </div>
            </div> 
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Supervisor</label>
                 <div class="col-lg-7">
                 <select class="form-control form-control-select2" name="spv_id" id="spv_id" >
                <option value="" name="spv_id" id="spv_id">-- Pilih --</option>
                <div></div>
                @foreach ($spv as $spv)
                    <option value="{{ $spv->id }}">{{ $spv->emp_name }}</option>
                @endforeach
                </select>
                </select>
            </div>
            </div>
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Division</label>
                 <div class="col-lg-7">
                 <select class="form-control form-control-select2" name="division_id" id="division_id" required>
                <option value="" id="division_id">-- Pilih --</option>
                <div></div>
                @foreach ($division as $division)
                    <option value="{{ $division->id }}">{{ $division->div_name }}</option>
                @endforeach
                </select>
            </div>
            </div>
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Cabang</label>
                 <div class="col-lg-7">
                 <select class="form-control form-control-select2" name="cabang_id" id="cabang_id" required>
                 <option value="">-- Pilih --</option>
                <div></div>
                    @foreach ($cabang as $cabang)
                    <option value="{{ $cabang->id }}" allowClear="true" placeholder="Select">{{ $cabang->cabang_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
          
        </div>

    </div>
    </div>
        <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Upload Documents</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            @csrf
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">KTP/Personal ID</label>
               <div class="fallback">
                    <div class="custom-file">
                        <input type="file" name="doc_ktp" class="file-input">
                        <!-- <label class="custom-file-label" for="dropzoneBasicUpload">Choose file</label> -->
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Kartu Keluarga</label>
               <div class="fallback">
                    <div class="file">
                        <input type="file" name="doc_kk" class="form-control" id="doc_kk">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Akta Nikah</label>
               <div class="fallback">
                    <div class="file">
                        <input type="file" name="doc_aktaNikah" class="form-control" id="">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Bank Account</label>
               <div class="fallback">
                    <div class="file">
                        <input type="file" name="doc_bank" class="form-control" id="">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">NPWP</label>
               <div class="fallback">
                    <div class="file">
                        <input type="file" name="doc_npwp" class="form-control" id="">
                    </div>
                </div>
            </div>
            <div class="text-right">
            {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'hrm/employee', 'type' => 'button','onclick'=>'cancel(this)']) !!}
            <button type="submit" class="btn btn-primary">Create Employee<i class="far fa-save ml-2"></i></button>
            </div>
        </div>
    </div>
</div>
</div>
    </form>
@endsection
@section('script')
    <script src="{{ asset('ctrl/hr/form-employee.js') }}" type="text/javascript"></script>
    <script src="{{ asset('ctrl/hr/upload-employee.js') }}" type="text/javascript"></script>
@endsection

