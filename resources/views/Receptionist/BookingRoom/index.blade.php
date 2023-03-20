@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card" style="overflow-x:auto;">
        <div class="card-header">
            <h2>Booking Meeting Room</h2>
            <p><strong>Note : </strong></p>
            <table>
                <tr>
                    @php foreach ($for as $for) { @endphp
                    @if($for->id==1)
                    <td><i class="fa fa-check-square" aria-hidden="true" style="color:#1dc3f4"></i></td>
                    @elseif($for->id==2)
                    <td><i class="fa fa-check-square" aria-hidden="true" style="color:#3897b3"></i></td>
                    @elseif($for->id==3)
                    <td><i class="fa fa-check-square" aria-hidden="true" style="color:#0dca90"></i></td>
                    @elseif($for->id==4)
                    <td><i class="fa fa-check-square" aria-hidden="true" style="color:#f36616"></i></td>
                    @else
                    <td><i class="fa fa-check-square" aria-hidden="true" style="color:#c80756"></i></td>
                    @endif
                    <td>{{$for->room_name}}</td>
                    @php } @endphp
                </tr>
            </table>
        </div>
        <br>
        <div class="card-body">
            @if(session()->has('success'))
            <div class="alert alert-success alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('success') }}
            </div>
            @endif

            <div class="calendar"></div>
            @csrf
        </div>

    </div>
</div>
@include('sales.quotation.attribute.modal')
@endsection
@section('script')
<script src="{{ asset('ctrl/receptionist/booking-list.js') }}" type="text/javascript"></script>
@endsection