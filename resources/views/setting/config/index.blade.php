@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card">
        <div class="card-header header-elements-inline">
            <a href="{{ url('setting/config/create') }}">
                <button type="button" class="btn bg-primary" data-toggle="modal">
                    Create Config
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

            <table class="table m_datatable table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Config Name</th>
                        <th>Config Value</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
    </div>
</div>
</div>
@endsection
@section('script')
    <script src="{{ asset('ctrl/setting/config-list.js') }}" type="text/javascript"></script>
@endsection