@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Invoice template -->
    <div class="card">
        <div class="card-header bg-transparent header-elements-inline">
            <h5 class="card-title"><a href="">PURCHASE ORDER</a> / {{$main->po_number}} </h5>
            <div class="header-elements">
                <button type="button" onclick="PrintFinalPO(this)" data-id="{{$main->po_number}}" class="btn btn-warning btn-sm ml-3"><i class="icon-printer mr-2"></i> Print</button>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-4">
                        <ul class="list list-unstyled mb-0">
                            <li>
                                <h6 class="font-weight-bold">Vendor Pembelian</h6>
                            </li>
                            <li>{{$vend->vendor_name}}</li>
                            <li>{{$vend->address}}</li>
                            <li>{{$vend->phone}}</li>
                            <li>
                                @php if($vend->email==null or $vend->email=='N') { @endphp
                                <a href="">No Email, Click untuk tambah</a>
                                @php }else{ @endphp
                                {{$vend->email}}
                                @php }@endphp
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="mb-4 mt-4">
                        <div class="text-sm-right">
                            @php 
                            if($main->status=='approve'){ @endphp
                            <h1 class="font-weight-bold text-success">APPROVE</h1>
                            <ul class="list list-unstyled mb-0">
                                <li>By : <span class="font-weight-semibold">{!!getUserEmp($main->status_by)->emp_name!!}</span></li>
                                <li>Request Date: <span class="font-weight-semibold">{{date('d-m-Y', strtotime($main->created_at))}}</span></li>
                                <li>Approve Date: <span class="font-weight-semibold">{{date('d-m-Y', strtotime($main->status_time))}}</span></li>
                            </ul>
                            @php }else {@endphp
                            <h1 class="font-weight-bold text-danger">PENDING</h1>
                            <ul class="list list-unstyled mb-0">
                                <li>Request Date: <span class="font-weight-semibold">{{date('d-m-Y', strtotime($main->created_at))}}</span></li>
                                <li>Referensi: <span class="font-weight-semibold"></span></li>
                            </ul>
                            @php }@endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-tabs-responsive bg-light border-top">
            <ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
                <li class="nav-item"><a href="#info" class="nav-link active" data-toggle="tab"><i class="icon-menu7 mr-2"></i> Produk</a></li>
                <li class="nav-item"><a href="#fwd" class="nav-link" data-toggle="tab"><i class="icon-truck mr-2"></i> Pengiriman</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="info">

                <div class="table-responsive">
                    <table class="table table-lg">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $subtotal_final = 0;
                            $margin = 0;
                            foreach ($product as $val){
                            $check = getProductPo($val->id_product)->product_id
                            @endphp
                            <tr>
                                <td>
                                    {!!getProductPo($val->id_product)->name!!}
                                </td>
                                <td class="text-center">@php echo $val->qty; @endphp</td>
                                <td class="text-right">@php echo number_format($val->price); @endphp</td>
                                <td class="text-right"><span class="font-weight-semibold">@php echo number_format($val->qty*$val->price); @endphp</span></td>
                            </tr>
                            @php
                            $subtotal_final += $val->qty*$val->price;
                            }
                            @endphp
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    <div class="d-md-flex flex-md-wrap">

                        <div class="pt-2 mb-3 wmin-md-400 ml-auto">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        @php
                                        $vat = $subtotal_final/10;
                                        @endphp
                                        <tr>
                                            <th>Subtotal:</th>
                                            <td class="text-right">@php echo number_format($subtotal_final); @endphp</td>
                                        </tr>
                                        <tr>
                                            <th>PPN: </th>
                                            <td class="text-right">
                                                @php
                                                echo number_format($vat);
                                                @endphp
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td class="text-right text-primary">
                                                <h5 class="font-weight-semibold">
                                                    @php
                                                    echo number_format($subtotal_final+$vat);
                                                    @endphp
                                                </h5>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            @php if($main->status=='approve'){ @endphp
                            <div class="text-right mt-3">
                                <button type="button" class="btn btn-primary btn-labeled btn-labeled-left" onclick="KirimEmail(this)" data-toggle="modal" data-target="#m_modal" data-type="{{$main->type}}" data-id="{{$main->id}}"><b><i class="icon-paperplane"></i></b> Kirim PO</button>
                            </div>
                            @php } else { @endphp
                            <button type="button" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" data-type="approve" onclick="ShowApprovalPO(this)" class="btn btn-primary btn-labeled btn-labeled-left legitRipple"><b><i class="icon-checkmark4"></i></b> Approve</button>
                            <button type="button" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" data-type="reject" onclick="ShowApprovalPO(this)" class="btn btn-danger btn-labeled btn-labeled-left legitRipple"><b><i class="icon-cancel-circle2"></i></b> Reject</button>
                            @php } @endphp
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade show" id="fwd">
                <div class="card-body">
                    <div class="row">
                        @php
                        $shipping = getCabang('3');
                        @endphp
                        <div class="col-sm-6">
                            <div class="mb-4">
                                <ul class="list list-unstyled mb-0">
                                    <li>
                                        <h6 class="font-weight-bold">Default Pengiriman</h6>
                                    </li>
                                    <li>PT MITRA ERA GLOBAL</li>
                                    <li>{!!$shipping->cabang_address!!}</li>
                                    <li>{!!$shipping->cabang_phone!!}</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-4">
                                <ul class="list list-unstyled mb-0">
                                    <li>
                                        <h6 class="font-weight-bold">Alamat Pengganti</h6>
                                    </li>
                                    <li>
                                        @php if($main->pengiriman=='dropship'){ @endphp
                                    <li>{{$alamat->name}}</li>
                                    <li>{{$alamat->address}}</li>
                                    @php } @endphp

                                    @php if($main->pengiriman=='dropship'){ @endphp
                                    <button type="button" onclick="ChangeAlamat(this)" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" data-type="update" data-target="#modal" class="btn bg-teal-400 btn-labeled btn-labeled-left mt-3"><b><i class="icon-pin-alt"></i></b> Edit Alamat</button>
                                    <button type="button" onclick="DeleteAlamat(this)" data-id="{{$main->id}}" data-target="#modal" class="btn bg-danger-400 btn-labeled btn-labeled-left mt-3"><b><i class="icon-trash"></i></b> Delete Alamat</button>
                                    @php } else{ @endphp
                                    <button type="button" onclick="ChangeAlamat(this)" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" data-type="new" data-target="#modal" class="btn bg-teal-400 btn-labeled btn-labeled-left mt-3"><b><i class="icon-pin-alt"></i></b> Tambah Alamat</button>
                                    @php } @endphp
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <span class="text-muted">Sebelum mengirim purchase order, harap memeriksa kembali kesesuain data data yang ada</span>
        </div>
    </div>
</div>
@include('sales.quotation.attribute.modal')
@endsection
@section('script')
<script src="{{ asset('ctrl/purchasing/mail-po.js') }}" type="text/javascript"></script>

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