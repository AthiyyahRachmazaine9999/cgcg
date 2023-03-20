@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card" style="overflow-x:auto;">
        <div class="card-header">
            <a href="{{ route('file.create') }}">
                <button type="button" class="btn bg-primary" data-toggle="modal">
                    Upload file
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

                    <!-- //tambahkan kode untuk tanda + show atau button show -->
                    <tr>
                        <th>Nama</th>
                        <th>Created At</th>
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
<script src="{{ asset('ctrl/upload/upload_file.js') }}" type="text/javascript"></script>
@endsection
<!--  -->