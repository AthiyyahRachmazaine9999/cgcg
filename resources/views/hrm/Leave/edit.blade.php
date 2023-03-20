@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Update Leave Request</h5>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form', 'files' => true ]) !!}
            <input type="hidden" name="employee_id" class="form-control" value="{{$getdata->employee_id}}">
            <input type="hidden" name="id" class="form-control" value="{{$getdata->id}}">
            <div class="form-group row">
                {!! Form::label('employee_id', 'Nama*', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!Form::text('emp_name',getUserEmp($getdata->created_by)->name,['id'=>'emp_name','class'=>'form-control',
                    'placeholder'=>'Enter Name', 'readonly']) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('type_leave', 'type Leave', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!Form::text('type_leave',$getdata->type_leave,['id'=>'purpose','class'=>'form-control','placeholder'=>'Enter
                    Type Leave','readonly']) !!}
                </div>
            </div>

            @if($getdata->type_leave=="Special Leave")
            <div class="form-group row">
                {!! Form::label('purpose', 'Tujuan Cuti', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::select('purpose',array('Melangsungkan Pernikahan' => 'Melangsungkan Pernikahan', 'Keluarga Melangsungkan Pernikahan' =>'Keluarga Melangsungkan Pernikahan','Kematian Keluarga' => 'Kematian Keluarga', 
                    'Kematian Keluarga Satu Rumah' => 'Kematian Keluarga Satu Rumah','Pembaptisan' => 'Pembaptisan',
                    'Cuti Melahirkan (Untuk Karyawan Wanita)' => 'Cuti Melahirkan (Untuk Karyawan Wanita)',
                    'Istri Melahirkan atau Keguguran' => 'Istri Melahirkan atau Keguguran',
                    'Khitanan Anak' => 'Khitanan','Ujian Akhir Kesarjanaan' => 'Ujian Akhir Kesarjanaan',
                    'Wisuda/Kelulusan' => 'Wisuda/Kelulusan',
                    'Menunaikan Ibadah Haji Pertama kali' => 'Menunaikan Ibadah Haji Pertama kali'), 
                    $getdata->purpose,['id' => 'purpose_leave_array', 'class' => 'form-control
                    form-control-select2
                    leaves','placeholder'=>'*']) !!}
                </div>
            </div>
            @elseif($getdata->type_leave=="Permission")
            <div class="form-group row">
                {!! Form::label('purpose', 'Tujuan Cuti', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::select('purpose',array('Izin Sakit' => 'Izin Sakit', 'Lainnya' =>
                    'Lainnya'),$getdata->purpose,['id' => 'purpose_permit', 'class' => 'form-control
                    form-control-select2
                    permit','placeholder'=>'*']) !!}
                </div>
            </div>
            @else
            <div class="form-group row">
                {!! Form::label('purpose', 'Tujuan Cuti', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!Form::text('purpose',$getdata->purpose,['id'=>'purpose','class'=>'form-control','placeholder'=>
                    'Enter Purpose','required']) !!}
                </div>
            </div>
            @endif
            <div class="form-group row">
                {!! Form::label('note', 'Keterangan', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::text('note',$getdata->note,['id'=>'note','class'=>'form-control',
                    'placeholder'=>'Masukkan Keterangan']) !!}

                </div>
            </div>

            <legend class="text-uppercase font-size-sm font-weight-bold">Jam dan Tanggal</legend>
            @if($getdata->type_leave == "Late Permission")
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Jam datang</label>
                <div class="col-lg-7">
                    <input type="time" name="time_finish"
                        value="{{\Carbon\Carbon::parse($getdata->time_finish)->format('H:i')}}" class="form-control"
                        id="arrived" placeholder="Masukkan Jam">
                </div>
            </div>
            @elseif ($getdata->type_leave == "Permission")
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Hari & Tanggal</label>
                <div class="col-lg-7">
                    {!! Form::select('type_date',
                    array('Today' => 'Today', 'Tomorrow' => 'Tomorrow'), '',
                    ['id' => 'type_date', 'class' => 'form-control form-control-select2 leaves',
                    'placeholder'=>'*']) !!}
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'></label>
                @if(in_array($mine->id,explode(',' , getConfig('list_hr'))))
                <div class="col-lg-7">
                    <input type="text" name="read_finish"
                        value="{{\Carbon\Carbon::parse($getdata->date_finish)->format('Y-m-d')}}" id=""
                        class="form-control date" placeholder="Enter Date">
                </div>
                @else
                <div class="col-lg-7">
                    <input type="text" name="read_finish" value="{{$getdata->date_finish}}" id="" class="form-control" placeholder="Enter Date" readonly>
                </div>
                @endif
           </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'></label>
                @php if($getdata->file_permit==null) { @endphp
                <div class="col-lg-5">
                    <input type="file" name="file_sakit" value="{{$getdata->file_permit}}"
                        class="file-input form-control">
                </div>
                @php } else { @endphp
                <div class="col-lg-5    ">
                    <input type="file" name="file_sakit" value="{{$getdata->file_pemit}}"
                        class="file-input form-control">
                </div>
                <a href="{{ asset('public/hr_permission/'.$getdata->file_permit) }}" id="btn"><i
                        class="fa fa-check"></i>
                    Sudah Terisi. Check Disini</a>
                @php } @endphp
            </div>
            @else
            <div class="form-group row">
                {!! Form::label('date_from', 'Dari Tanggal', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!
                    Form::text('date_from',$getdata->date_from,['id'=>'date_from1','class'=>'form-control
                    date','placeholder'=>'Masukkan Tanggal','required']) !!}

                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('date_finish', 'Sampai Tanggal', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!Form::text('date_finish',$getdata->date_finish,['id'=>'date_finish1','class'=>'form-control
                    date','placeholder'=>'Masukkan Tanggal','required']) !!}

                </div>
            </div>

            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Lama Cuti</label>
                <div class="col-lg-7">
                    {!! Form::text('lama_cuti',$getdata->lama_cuti,['id'=>'cuti','class'=>'form-control',
                    'placeholder'=>'' , 'readonly']) !!}
                </div>
            </div>

            @endif

            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' =>
                'hrm/request/leave', 'type'
                => 'button','onclick'=>'cancel(this)']) !!}
                <button type="submit" class="btn btn-primary">Update Leave<i class="far fa-save ml-2"></i></button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
    <!-- /basic layout -->

</div>
</div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/req_leave-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection