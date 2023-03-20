@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card" style="overflow-x:auto;">
        <div class="card-header header-elements-inline">
            <a href="{{ url('finance/payment_voucher/create') }}">
                <button type="button" class="btn bg-primary" data-toggle="modal">
                    New Payment
                </button>
            </a>
            <div class="header-elements">
                <a href="#" class="btn btn-danger btn-sm ml-3" id="PrintPayment" data-id_pay=""><i
                        class="icon-printer mr-2 text-left"></i>Print</a><br>

            </div>
        </div>
        <div class="card-body">
            @if(session()->has('success'))
            <div class="alert alert-success alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('success') }}
            </div>
            @elseif (session()->has('error'))
            <div class="alert alert-danger alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('error') }}
            </div>
            @endif

            <table class="table m_datatable table-bordered table-striped table-hover">
                <thead>
                    <tr class="text-center">
                        <th>Checked</th>
                        <th>Tujuan</th>
                        <th>No. Payment Voucher</th>
                        <th>Vendor</th>
                        <th>No. Invoice</th>
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
<script src="{{ asset('ctrl/finance/pay_voucher-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection