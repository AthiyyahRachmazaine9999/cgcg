@extends('layouts.head') @section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Create Travel Request</h5>
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

            <form action="{{ route('travel.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Employee Name</label>
                        <div class="col-lg-7">
                            <select class="form-control form-control-select2" name="employee_id" id="employee_id"
                                required="required">
                                <option value="" id="employee_id">-- Pilih --</option>
                                <div></div>
                                @foreach ($employee as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->emp_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Division</label>
                        <div class="col-lg-7">
                            <select class="form-control form-control-select2" name="division_id" id="div"
                                required="required">
                                <option value="" id="div">-- Pilih --</option>
                                <div></div>
                                @foreach ($division as $division)
                                <option value="{{ $division->id }}">{{ $division->div_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Destination</label>
                        <div class="col-lg-3">
                            {!! Form::select('des_provinsi', $province, null,['id' => 'province', 'class' =>
                            'form-control form-control-select2', 'placeholder' => 'Pilih
                            Provinsi','required']) !!}
                        </div>

                        <div class="col-lg-3">
                            {!! Form::select('des_kota', $city, null,['id' => 'city', 'class' => 'form-control
                            form-control-select2', 'placeholder' => 'Pilih Kota','required', 'disabled'])
                            !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Purpose</label>
                        <div class="col-lg-7">
                            <input type="text" name="purpose" class="form-control" placeholder="Enter Your Purpose"
                                required="required">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Departure Transport</label>
                        <div class="col-lg-7">
                            {!! Form::select('departure_transport', array('Flight' => 'Flight', 'Train' =>
                            'Train', 'Own Car' => 'Own Car', 'Others' => 'Others'), '', ['id' =>
                            'transport', 'class' => 'form-control form-control-select2', 'placeholder' =>
                            '*']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Return Transport</label>
                        <div class="col-lg-7">
                            {!! Form::select('return_transport', array('Flight' => 'Flight', 'Train' =>
                            'Train', 'Own Car' => 'Own Car', 'Others' => 'Others'), '', ['id' =>
                            'Rtransport', 'class' => 'form-control form-control-select2', 'placeholder' =>
                            '*']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Accomodation</label>
                        <div class="col-lg-7">
                            {!! Form::select('akomodasi', array('Hotel' => 'Hotel', 'Apartment' =>
                            'Apartment', 'Mess' => 'Mess', 'Relatives Home' => 'Relatives Home', 'Others' =>
                            'Others'), '', ['id' => 'akomodasi', 'class' => 'form-control
                            form-control-select2', 'placeholder' => '*']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Note</label>
                        <div class="col-lg-7">
                            <textarea type="text" name="keterangan" class="form-control"
                                placeholder="Enter Your Note"></textarea>
                        </div>
                    </div>

                    <legend class="text-uppercase font-size-sm font-weight-bold">Date, Time, and Cost Travel</legend>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Date Departure</label>
                        <div class="col-lg-7">
                            <input type="date" name="date_departure" class="form-control" placeholder=""
                                required="required">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Time departure</label>
                        <div class="col-lg-7">
                            {!! Form::select('time_departure',array('Early' => 'Early', 'Noon' => 'Noon',
                            'Last' => 'Last'), '', ['id' => 'Dtime', 'class' => 'form-control
                            form-control-select2', 'placeholder' => '*']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Date Return</label>
                        <div class="col-lg-7">
                            <input type="date" name="date_return" class="form-control" placeholder=""
                                required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Estimation Expenses</label>
                        <div class="col-lg-7">
                            <input type="number" name="est_biaya" class="form-control" placeholder="Estimation Expenses"
                                required="required">
                        </div>
                    </div>

                    <div class="text-right">
                        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                        'data-method' =>'hrm/request/travel', 'type' =>
                        'button','onclick'=>'cancel(this)']) !!}
                        <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection @section('script')
<script src="{{ asset('ctrl/hr/req_travel-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection