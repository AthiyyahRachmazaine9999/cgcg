@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card" style="overflow-x:auto;">
        <div class="card-header header-elements-inline">
            <button type="button" class="btn bg-primary" data-toggle="modal" data-target="#m_modal" data-id_emp="{{Auth::id()}}" onclick="MySalary(this)">
                <i class="fab fa-expeditedssl mr-2"></i> View Salary
            </button>
        </div>

        <div class="card-body">
            @if(session()->has('success'))
            <div class="alert alert-success alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('success') }}
            </div>
            @endif

            
            @if(session()->has('error'))
            <div class="alert alert-danger alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('error') }}
            </div>
            @endif

            <table class="table personaltable table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Division</th>
                        <th>Position</th>
                        <th>Rek. BCA </th>
                        <th>Basic Salary</th>
                        <th>Allowance</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    @include('sales.quotation.attribute.modal')
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/payroll-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection