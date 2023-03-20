@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card" style="overflow-x:auto;">
        <div class="card-header header-elements-inline">
            <a href="{{ url('finance/code_accounting/create') }}">
                <button type="button" class="btn bg-primary" data-toggle="modal">
                    Create Code
                </button>
            </a>
        </div>

        <div class="card-body">
            @if(session()->has('success'))
            <div class="alert alert-success alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('success') }}
            </div>
            @endif
            @if(session()->has('info'))
            <div class="alert alert-info alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('info') }}
            </div>
            @endif

            <table class="table m_datatable table-bordered table-striped table-hover">
                <thead>
                    <tr class="text-center bg-slate-800">
                        <th>ID</th>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Created at</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
            {{ method_field('DELETE') }}
            @csrf
        </div>
    </div>
</div>
@include('sales.quotation.attribute.modal')
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/code-list.js') }}" type="text/javascript"></script>
@endsection