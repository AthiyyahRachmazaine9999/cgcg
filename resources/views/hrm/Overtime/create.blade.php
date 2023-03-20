@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Create Overtime Request</h5>
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

            <form action="{{ route('overtime.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Employee Name</label>
                        <div class="col-lg-7">
                            <select class="form-control form-control-select2" name="employee_id" id="employee_id"
                                required>
                                <option value="" id="employee_id">-- Pilih --</option>
                                <div></div>
                                @foreach ($employee as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->emp_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Purpose of Overtime</label>
                        <div class="col-lg-7">
                            <input type="text" name="purpose" class="form-control" placeholder="Enter Purpose" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Date</label>
                        <div class="col-lg-7">
                            <input type="text" name="date" class="form-control date" placeholder="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Overtime From</label>
                        <div class="col-lg-7">
                            <input type="time" name="overtime_from" class="form-control"
                                placeholder="Masukkan waktu Overtime" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Overtime Finish</label>
                        <div class="col-lg-7">
                            <input type="time" name="overtime_finish" class="form-control" required>
                        </div>
                    </div>
                    <div class="text-right">
                        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method'
                        =>'hrm/request/overtime', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                        <button type="submit" class="btn btn-primary">Create Request<i
                                class="far fa-save ml-2"></i></button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/req_overtime-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection