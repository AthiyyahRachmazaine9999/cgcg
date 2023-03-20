@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Form Leave</h5>
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

            <form action="{{ route('leave.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <input type="hidden" name="employee_id" class="form-control" value="{{$usr}}">
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Nama</label>
                        <div class="col-lg-7">
                            <input type="text" name="" class="form-control" value="{{getUserEmp($usr)->name}}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-check-input-styled-primary" value="type_leave">Type
                            Leave</label>
                        <div class="col-lg-7">
                            {!! Form::select('type_leave',array('Annual Leave' => 'Annual Leave', 'Special Leave' =>
                            'Special Leave','Late Permission' => 'Late Permission', 'Permission' => 'Permission'),
                            '',
                            ['id' => 'type_leave', 'class' => 'form-control form-control-select2 leaves',
                            'placeholder'
                            =>'*']) !!}
                        </div>
                    </div>
                    <div class="next_form">
                        <!-- form pilihan -->
                    </div>
                    <br>
                    <div class="text-right">
                        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method'
                        =>'hrm/request/leave', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                        <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/req_leave-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection