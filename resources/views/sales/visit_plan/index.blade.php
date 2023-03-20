@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card" style="overflow-x:auto;">
        <div class="card-header header-elements-inline">
            <h2>VISIT PLAN</h2>
            <div class="header-elements">
                <button type="button" onclick="DownloadVisit(this)" class="btn btn-primary btn-sm ml-3"><i
                        class="icon-printer mr-2"></i> Download</button>
            </div>
        </div>
        <br>
        <div class="card-body">
            @if(session()->has('success'))
            <div class="alert alert-success alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('success') }}
            </div>
            @endif
            <div class="calendar"></div>
            @csrf
        </div>
    </div>
</div>
@include('sales.quotation.attribute.modal')
@endsection
@section('script')
<script src="{{ asset('ctrl/sales/visit_plan.js?v=').rand() }}" type="text/javascript"></script>
@endsection