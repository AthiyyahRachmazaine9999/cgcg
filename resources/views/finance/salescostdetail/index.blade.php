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


            

            <table class="container table-bordered ">

                <thead>

                    <tr class="text-center">

                        <th >Settlement</th>

                        <th >Nominal</th>

                        
                    </tr>

                </thead>


                <tbody>
        @php
        foreach($emp as $value) { @endphp
        



        <tr>
        <td class="text-left">{{$value->emp_name}}</td>

        <td class="text-right">{{number_format($value->biaya_finance , 0 )}}</td>


        </tr>
        @php } @endphp
        </tbody>
        

                


    

            </table>

            {{ method_field('DELETE') }}

            @csrf

        </div>

    </div>

</div>

@include('sales.quotation.attribute.modal')

@endsection

@section('script')

<script src="{{ asset('ctrl/finance/totalsmenusdetails.js') }}" type="text/javascript"></script>






@endsection