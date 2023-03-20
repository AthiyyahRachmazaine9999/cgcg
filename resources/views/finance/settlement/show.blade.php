@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h3 class="card-title">Settlement</h3>
            @if($set->status!="Rejected")
            <div class="header-elements">
                <a href="{{route('settlement.print', $set->id)}}" class="btn btn-danger btn-sm ml-3"
                    onclick="PrintSets(this)" data-id="{{$set->id}}"><i class="icon-printer mr-2"></i>Print</a>
            </div>
            @endif
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

            <div class="card-body">
                @if($set->no_ref==null)
                @include('finance.settlement.attribute.show_blank_form')
                @else
                @include('finance.settlement.attribute.show_with_cash')
                @endif
                <br>
            </div>
        </div>
    </div>
</div>
@include('sales.quotation.attribute.modal')
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/finance/settlement-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection