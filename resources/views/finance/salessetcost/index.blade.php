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


            

            <table class="table salescost table-bordered table-striped table-hover ">

                <thead>

                    <tr class="text-center">



                        <th >Name</th>

                        <th >Settlement</th>

                        <th >DetailTotal</th>

                        
                        
                    </tr>

                </thead>


                <tbody>


                @php
                foreach($emp as $value) { @endphp



                <td class="text-left">{{$value->emp_name}}</td>

                <td class="text-right">{{number_format(costsetsettlementmenu($value->id) , 0 )}}</td>

                <td class="text-right">

                <a class="btn btn-primary ms-2"href="{{ url('/finance/settlement/' . $value->id . '/setmenutotaldetails') }}" >DetailSet</a>
                
               </td>

                

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

<script src="{{ asset('ctrl/finance/totalsmenustotalsmenus.js') }}" type="text/javascript"></script>






@endsection