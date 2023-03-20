@extends('layouts.head')
@section('content')
<div class="content mb-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    {!! Form::open(['method' => $method,'action'=>$action]) !!}
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Tanggal Awal</label>
                        <div class="col-lg-8">
                            <input type="date" id="end_date" class="form-control" data-column="5" name="start" placeholder="Enter Date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Tanggal Akhir</label>
                        <div class="col-lg-8">
                            <input type="date" id="tempo" class="form-control" data-column="5" name="end" placeholder="Enter Date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Jenis</label>
                        <div class="col-lg-8">
                        {!! Form::select('jenis',array(
                            'in' => 'in',
                            'out' => 'out',
                            'address' => 'address',
                            ), '',['id' => 'jenis', 'class' => 'form-control form-control-select2
                            leaves','placeholder'=>'*']) !!}
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Generate<i class="far fa-save ml-2"></i></button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/absensi-download.js?v=').rand() }}" type="text/javascript"></script>
@endsection