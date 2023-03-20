<!-- Basic layout-->
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

    <form action="{{ route('visitplan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <input type="hidden" name="date" class="form-control" value="{{$date}}">
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Nama Aktivitas</label>
                <div class="col-lg-7">
                    <input type="text" name="aktivitas" placeholder="Masukkan Aktivitas" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Instansi</label>
                <div class="col-lg-7">
                    <select class="form-control" name="id_customer" id="cust">
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Meeting Point</label>
                <div class="col-lg-7">
                    <select class="form-control" name="meeting_point" id="lokasi"></select>
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Detail Alamat</label>
                <div class="col-lg-7">
                    <textarea type="text" name="detail" style="height:120px;"
                        placeholder="Masukkan Detail Alamat" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Jam</label>
                <div class="col-lg-3">
                    <input type="text" name="start_time" placeholder="Dari Jam" class="form-control time">
                </div>
                <div class="col-lg-3">
                    <input type="text" name="end_time" placeholder="Sampai Jam" class="form-control time">
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Forecast Value</label>
                <div class="col-lg-7">
                    <textarea type="text" name="for_value" style="height:120px;" placeholder="Masukkan Forecast Value"
                        class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-check-input-styled-primary" value="type_leave">Status
                    Meeting</label>
                <div class="col-lg-7">
                    {!! Form::select('status',array('Open Plan' => 'Open Plan', 'Introduction' =>
                    'Introduction'),'',['id' => 'status', 'class' => 'form-control form-control-select2 leaves',
                    'placeholder'=>'Pilih Status Meeting']) !!}
                </div>
            </div>
            <legend><strong>Contact Person</strong></legend>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Nama</label>
                <div class="col-lg-7">
                    <input type="text" name="nama_cp" placeholder="Masukkan Nama" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Nomer HP</label>
                <div class="col-lg-7">
                    <input type="number" name="nomer_hp" placeholder="Masukkan Nomer HP" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Jabatan</label>
                <div class="col-lg-7">
                    <input type="text" name="jabatan" placeholder="Masukkan Jabatan" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Email</label>
                <div class="col-lg-7">
                    <input type="email" name="email" placeholder="Masukkan Email" class="form-control">
                </div>
            </div>

            <br>
            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method'
                =>'sales/visitplan', 'type' => 'button','onClick'=>'cancel(this)']) !!}
                <button type="submit" class="btn btn-primary">Save<i class="far fa-save ml-2"></i></button>
            </div>
        </div>
    </form>
</div>
@include('sales.quotation.attribute.modal')
<script src="{{ asset('ctrl/sales/visitplan_form.js?v=').rand() }}" type="text/javascript"></script>