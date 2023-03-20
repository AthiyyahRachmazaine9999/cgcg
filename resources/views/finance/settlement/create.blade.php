@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h3 class="card-title">Settlement</h3>
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
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label class='col-lg-3 col-form-label'>Form Settlement*</label>
                    <div class="col-lg-7">
                        {!! Form::select('type_settlement',array('with_cash' => 'With Cash Advance',
                        'lainnya' => 'Blank Form'),
                        '',['id' => 'type_settlement', 'class' =>'form-control form-control-select2 types',
                        'placeholder' => 'Pilih Settlement Form']) !!}
                    </div>
                </div>
                <div class="form-group row rows_cash">
                    <label class='col-lg-3 col-form-label'>No. Cash Advance</label>
                    <div class="col-lg-7">
                        {!! Form::select('id_cash', $cashs, null,['id' => 'cash_adv', 'class' =>
                        'form-control form-control-select2 select2 cashss',
                        'placeholder' =>'Pilih Cash Advance']) !!}
                    </div>
                </div>
                <div class="cash_post">
                    <div class="blank_form">
                        @include('finance.settlement.attribute.blank_form')
                    </div>
                </div>
                <br>
                </form>
                @include('sales.quotation.attribute.modal')
            </div>
        </div>
    </div>
    @endsection
    @section('script')
    <script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript"></script>
    <script src="{{ asset('ctrl/finance/settlement-form.js?v=').rand() }}" type="text/javascript"></script>
    @endsection