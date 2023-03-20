@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card">

        <div class="card-body">
            @if(session()->has('success'))
            <div class="alert alert-success alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('success') }}
            </div>
            @endif


            <table class="table table-bordered table-striped table-hover m_datatable">
                <thead>
                    <tr class="bg-slate-800">
                        <th class="text-center">ID</th>
                        <th class="text-center">No. PO</th>
                        <th class="text-center">No. SO</th>
                        <th class="text-center">Vendor</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Position</th>
                        <th class="text-center">Tanggal Order</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/warehouses/warehouse-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection