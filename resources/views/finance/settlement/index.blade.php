@extends('layouts.head')

@section('content')

<div class="content">

    <div class="card" style="overflow-x:auto;">

        <div class="card-header header-elements-inline">

            <a href="{{ url('finance/settlement/create') }}">

                <button type="button" class="btn bg-primary" data-toggle="modal">

                    Add Settlement

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

                    <tr class="text-center">

                        <th>Name</th>

                        <th>No. Settlement</th>

                        <th>Nominal</th>

                        <th class="text-left">Status</th>

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

@include('sales.quotation.attribute.modal')

@endsection

@section('script')

<script src="{{ asset('ctrl/finance/settlement-list.js') }}" type="text/javascript"></script>






@endsection