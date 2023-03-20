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

            <form action="{{ route('pettycash.saveEdit') }}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    @csrf
                    <input type="hidden" name="id" id="" class="form-control" value="{{$dtl->id}}">
                    <input type="hidden" name="month" id="" class="form-control" value="{{$dtl->month}}">
                    <input type="hidden" name="year" id="" class="form-control" value="{{$dtl->year}}">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Date</label>
                        <div class="col-lg-7">
                            <input type="text" name="date" placeholder="Enter Date"
                                value="{{\Carbon\Carbon::parse($dtl->date)->format('Y-m-d')}}"
                                class="form-control dates">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Description</label>
                        <div class="col-lg-7">
                            <textarea type="text" name="description"
                                class="form-control">{{$dtl->description}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Code</label>
                        <div class="col-lg-7">
                            {!! Form::select('code_id', $getcode, $dtl->code_id,['id' => 'code_id', 'class' =>
                            'codes form-control form-control-select2']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Transaction</label>
                        <div class="col-lg-7">
                            {!! Form::select('type_payment',
                            array('debit' => 'Debit', 'credit' => 'Credit'), $dtl->transaksi,
                            ['id' => 'debit', 'class' => 'form-control form-control-select2',
                            'placeholder'=>'*']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">PIC</label>
                        <div class="col-lg-7">
                            {!! Form::select('pic', $pic, $dtl->pic, ['id' => 'pic', 'class' => 'form-control
                            form-control-select2',
                            'placeholder'=>'*']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label"></label>
                        <div class="col-lg-7">
                            <textarea type="text" name="other_pic" placeholder="Other Name PIC"
                                class="form-control">{{$dtl->other_pic}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Nominal</label>
                        <div class="col-lg-7">
                            <input type="number" name="nominal" value="{{$dtl->nominal}}" placeholder="Enter Nominal"
                                class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Receipt</label>
                        @if($dtl->receipt!=null)
                        <div class="col-lg-7">
                            <input type="file" name="receipt" value="{{$dtl->receipt}}" class="file-input form-control">
                            <a href="{{ asset($dtl->receipt)}}" style="margin-top:10px;" target="_blank" id="btn"
                                class="btn btn-primary">SHOW</a>
                        </div>
                        @else
                        <div class="col-lg-7">
                            <input type="file" name="receipt" class="file-input form-control">
                        </div>
                        @endif
                    </div>
                    <div class="text-right">
                        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                        'data-method' => 'finance/pettycash/'.$dtl->month.'-'.$dtl->year.'/show', 'type' =>
                        'button','onclick'=>'cancel(this)'])
                        !!}
                        <button type="submit" class="btn btn-primary">Update<i class="far fa-save ml-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/pettycash-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection