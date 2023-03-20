@extends('layouts.head')
@section('content')
<div class="content">
<div class="card" style="overflow-x:auto;">
        <div class="card-header header-elements-inline">
<!--             <span class=""><code>Halaman Ini Sebagai History</code></span>
 -->        </div>

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
                        <tr>
                            <th>Model</th>
                            <th>Nama Produk</th>
                            <th>Type Update</th>
                            <th>Price</th>
                            <th>Status Active</th>
                            <th>Expired Date</th>
                            <th>Created By</th>
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
@section ('script')
<script src="{{ asset('ctrl/product/pendingApp-list.js') }}" type="text/javascript"></script>
@endsection
<!--  -->