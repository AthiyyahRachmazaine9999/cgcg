@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Mass Leave</h5>
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

            <form action="{{ route('saveMass.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Keterangan</label>
                        <div class="col-lg-7">
                            <textarea type="text" name="note" class="form-control" placeholder="Massukkan Note"
                                value="{{$mass->note}}"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Lama Cuti</label>
                        <div class="col-lg-7">
                            <input type="text" name="days" value="{{$mass->days}}" class="form-control"
                                placeholder="Masukkan Jumlah Hari">
                        </div>
                    </div>
                    @foreach($dtl as $dtl)
                    <div class="add_row_dates">
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'></label>
                            <div class="col-lg-3">
                                <input type="text" name="date" value="{{$dtl->date_of_days}}" class="form-control dates"
                                    placeholder="Masukkan Tanggal">
                            </div>
                            <button type="button" onclick="delete_tanggal(this)" data-type="edit_hapus"
                                class="btn bg-danger-300 btn-icon rounded-round legitRipple"><b><i
                                        class="fas fa-trash"></i></b></button><br>
                        </div>
                        <div class="point_rows">
                            <!-- Tanggal  -->
                        </div>
                    </div>
                    @endforeach
                    <br>
                    <div class="text-right">
                        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method'
                        =>'hrm/mass_leave', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                        <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i></button>
                    </div>
                </div>
            </form>
            <br>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/mass_leave-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection