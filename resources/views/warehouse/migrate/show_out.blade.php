@extends('layouts.head')
@section('content')
<div class="content">
    {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_out']) !!}
     <div class="card">
        <div class="card-header bg-transparent header-elements-inline">
            <h5 class="card-title"><a href="{{url('warehouse/outbound')}}">WAREHOUSE OUTBOUND</a> - {{$main->no_wh}} </h5>
            <div class="header-elements">
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-4">
                        <ul class="list list-unstyled mb-0">
                            <li>
                                <h6 class="font-weight-bold">Tujuan Pengiriman Utama</h6>
                            </li>
                            <li>{{$cust->company}}</li>
                            <li>{{$cust->address}}</li>
                            <li>{{$cust->phone}}</li>
                            <li>
                                @php if($cust->email==null or $cust->email=='N') { @endphp
                                <a href="#" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id_vendor}}" onclick="EditVendor(this)">No Email, Click untuk tambah</a>
                                @php }else{ @endphp
                                {{$cust->email}}
                                @php }@endphp
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="mb-4 mt-4">
                        <div class="text-sm-right">
                            @php $skirim = $main->status_kirim=="all" ? "SUDAH DIKIRIM" : "DIKIRIM SEBAGIAN" @endphp
                            <h1 class="font-weight-bold text-danger">{{$skirim}}</h1>
                            <ul class="list list-unstyled mb-0">
                                <li>Referensi: <span class="font-weight-semibold">{{$main->ref}} </span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-tabs-responsive bg-light border-top">
            <ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
                <li class="nav-item"><a href="#info" class="nav-link active" data-toggle="tab"><i class="icon-menu7 mr-2"></i> Produk</a></li>
                <li class="nav-item"><a href="#fwd" class="nav-link" data-toggle="tab"><i class="icon-truck mr-2 text-primary"></i> Pengiriman</a></li>
                <li class="nav-item"><a href="#track" class="nav-link" data-toggle="tab"><i class="fas fa-flag-checkered text-danger"></i> Tracking</a></li>
            </ul>
        </div>
        <div class="tab-content">
            @include('warehouse.migrate.attribute.tab_barang')
            @include('warehouse.migrate.attribute.tab_alamat')
            @include('warehouse.migrate.attribute.tab_resi')
        </div>
        <div class="card-footer">
            <span class="text-muted">Sebelum melakukan pengiriman, harap memeriksa kembali kesesuain data data yang ada</span>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@include('sales.quotation.attribute.modal')
@endsection
@section('script')
<script src="{{ asset('ctrl/warehouse/wh-detail.js') }}" type="text/javascript"></script>

@if(session()->has('success'))
<script type="text/javascript">
    swal({
        title: "Success",
        text: "{{ session()->get('success') }}",
        icon: "success",
    });
</script>
@endif
@endsection