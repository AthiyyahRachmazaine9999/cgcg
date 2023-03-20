@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card" style="overflow-x:auto;">
        @if($data->division_id==7 || $data->division_id==8)
        <div class="card-header header-elements-inline">
            <a href="{{ url('hrm/employee/create') }}">
                <button type="button" class="btn bg-primary" data-toggle="modal">
                    Add Employee
                </button>
            </a>
        </div>
        @endif

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
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Employee ID</th>
                        <th>Position</th>
                        <th>Leave</th>                        
                        <th>Status</th>
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
<script src="{{ asset('ctrl/hr/employee-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection