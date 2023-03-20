@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card" style="overflow-x:auto;">
        <div class="card-header header-elements-inline">
            <a href="{{ url('hrm/request/travel/create') }}">
                <button type="button" class="btn bg-primary" data-toggle="modal">
                  Add Request Travel
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
                        <th>Nama</th>
                        <th>Division</th>
                        <th>Destination</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <!-- <th>Updated At</th> -->
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
    <script src="{{ asset('ctrl/hr/req_travel-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection
