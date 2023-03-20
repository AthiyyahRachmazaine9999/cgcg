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
                        <th class="text-center" >SKU</th>
                        <th class="text-center" >Barang</th>
                        <th class="text-center" >PO</th>
                        <th class="text-center" >Sisa</th>
                        <th class="text-center" >Jenis</th>
                        <th class="text-center" >Last Order</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/inventory/inventory-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection