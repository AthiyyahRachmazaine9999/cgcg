@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Petty Cash</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
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

            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form', 'files' => true ]) !!}
            <div class="card-body">
                @csrf
                <input type="hidden" name="text" value="{{$type}}" class="form-control">
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Date</label>
                    <div class="col-lg-7">
                        <input type="text" name="date" placeholder="Enter Date" class="form-control dates">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Description</label>
                    <div class="col-lg-7">
                        <textarea type="text" name="description" class="form-control"
                            placeholder="Enter Description"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Code</label>
                    <div class="col-lg-7">
                        <select class="form-control codes" name="code_id" id="codes">
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Transaction</label>
                    <div class="col-lg-7">
                        {!! Form::select('type_payment',
                        array('debit' => 'Debit', 'credit' => 'Credit'), '',
                        ['id' => 'debit', 'class' => 'form-control form-control-select2',
                        'placeholder'=>'*']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">PIC</label>
                    <div class="col-lg-7">
                        {!! Form::select('pic',
                        $pic, '', ['id' => 'pic', 'class' => 'form-control form-control-select2',
                        'placeholder'=>'*']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label"></label>
                    <div class="col-lg-7">
                        <textarea type="text" name="other_pic" placeholder="Other Name PIC"
                            class="form-control"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Nominal</label>
                    <div class="col-lg-7">
                        <input type="number" name="nominal" placeholder="Enter Nominal" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Receipt</label>
                    <div class="col-lg-7">
                        <input type="file" name="receipt" class="file-input form-control">
                    </div>
                </div>
                <br>
                <div class="text-right">
                    @php
                    $cancel = $type == "new" ? 'finance/pettycash' :
                    'finance/pettycash/'.$type.'/show';
                    @endphp
                    {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                    'data-method' => $cancel, 'type' => 'button','onclick'=>'cancel(this)'])
                    !!}
                    <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i>
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/pettycash-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection