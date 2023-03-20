@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card" style="overflow-x:auto;">
        <div class="card-body">
            @if(session()->has('success'))
            <div class="alert alert-success alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('success') }}
            </div>
            @endif

            <table class="table m_datatables table-bordered table-striped table-hover">
                <thead>
                    <tr class="text-center bg-slate-800">
                        <th>Tujuan</th>
                        <th>Invoice / Kwitansi</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
            {{ method_field('DELETE') }}
            @csrf
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/othercost-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection