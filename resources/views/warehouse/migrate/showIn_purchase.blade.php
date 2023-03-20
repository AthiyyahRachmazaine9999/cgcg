@extends('layouts.head')
@section('content')
<div class="content">
    {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
    {!! Form::hidden('id_quo',$main->id_quo,['id'=>'id_quo','class'=>'form-control']) !!}
    {!! Form::hidden('id_po',$main->id,['id'=>'id_po','class'=>'form-control']) !!}
    {!! Form::hidden('idwh',$idwh,['id'=>'idwh','class'=>'form-control']) !!}
    {!! Form::hidden('id_vendor',$main->id_vendor,['id'=>'id_vendor','class'=>'form-control']) !!}
    {!! Form::hidden('type',$main->type,['id'=>'type','class'=>'form-control']) !!}
    <div class="card">
        <div class="card-header bg-transparent header-elements-inline">
            <h5 class="card-title"><a href="{{url('warehouse/inbound')}}">WAREHOUSE INBOUND</a> / {{$main->po_number}} </h5>
            <div class="header-elements">
                <button type="button" onclick="PrintWHIN(this)" data-id="{{$main->po_number}}" class="btn btn-warning btn-sm ml-3"><i class="icon-printer mr-2"></i> Print</button>
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
                                <a href="#" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id_vendor}}" onclick="EditVendor(this)">No Email, Click untuk tambah</a>
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
                            @php }else {
                                $gstatus = $method=='put' ? getWarehouse("id_purchase",$main->id)->status : $main->status;
                            @endphp
                            <h1 class="font-weight-bold text-danger">{{strtoupper($gstatus)}}</h1>
                            <ul class="list list-unstyled mb-0">
                                <li>Purchasing Order: <span class="font-weight-semibold">{{date('d-m-Y', strtotime($main->created_at))}}</span></li>
                                <li>Referensi: <span class="font-weight-semibold">SO{!!sprintf("%06d", getQuo($main->id_quo)->id)!!} </span></li>
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
                <li class="nav-item"><a href="#terima" class="nav-link" data-toggle="tab"><i class="icon-cart-add2 mr-2 text-success"></i> Penerimaan</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="info">

                <div class="table-responsive">
                    <table class="table table-lg">
                        <thead>
                            <tr>
                                <th>Terima</th>
                                <th>Barang</th>
                                <th>Qty</th>
                                <th>Type Sesuai</th>
                                <th>Qty Sesuai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = 1;
                            foreach ($product as $val){
                            $check = getProductPo($val->id_product)->name
                            @endphp
                            <tr>
                                <td class="text-center">
                                    @php
                                    if(is_null(getWarehouseDet("id_product",$val->id_product))){
                                    $attr = $statusv = $qtyv = ""; 
                                    $statusb = $qtyb = "checked";
                                    $qtyr = $val->qty;
                                    }else{
                                    $attr    = 'checked';
                                    $gets    = getWarehouseDet("id_product",$val->id_product);
                                    $statusv = $gets->status_note;
                                    $statusb = $gets->status_check == "yes" ? "checked" : "";
                                    $qtyb    = $gets->qty_check    == "yes" ? "checked" : "";
                                    $qtyv    = $gets->qty_note;
                                    $qtyr    = $gets->qty;

                                    }
                                    @endphp
                                    <input name="terima[]" type="checkbox" value="{{$val->id_product}}" class="form-check-input-switchery" {!!$attr!!} data-fouc>
                                </td>
                                <td>
                                    <input name="id_product[]" type="hidden" value="{{$val->id_product}}" class="form-control">
                                    {!!getProductPo($val->id_product)->name!!}
                                </td>
                                <td class="text-center">
                                    <input name="qty[]" type="number" value="{{$qtyr}}" class="form-control" placeholder="Qty">
                                </td>
                                <td class="text-center">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">
                                                <input name="type_sesuai[]" value="{{$val->id_product}}" type="checkbox" data-off-color="danger" data-on-text="Yes" data-off-text="No" class="form-check-input-switch" {!!$statusb!!}>
                                            </span>
                                        </span>
                                        <input name="type_error[]" type="text" value="{{$statusv}}" class="form-control" placeholder="Catatan jika tidak sesuai">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">
                                                <input name="qty_sesuai[]" value="{{$val->id_product}}" type="checkbox" data-off-color="danger" data-on-text="Yes" data-off-text="No" class="form-check-input-switch" {!!$qtyb!!}> </span>
                                        </span>
                                        <input name="qty_error[]" type="text" value="{{$qtyv}}" class="form-control" placeholder="Catatan jika tidak sesuai">
                                    </div>
                                </td>
                            </tr>
                            @php } @endphp
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    <div class="d-md-flex flex-md-wrap">

                        <div class="pt-2 mb-3 wmin-md-400 ml-auto">
                            <button type="submit" class="btn btn-primary btn-labeled btn-labeled-left legitRipple">
                                <b><i class="icon-download10"></i></b> @php echo $method=='put' ? 'Update Terima':'Terima'; @endphp
                            </button>
                            <button type="button" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" data-type="reject" onclick="ShowApprovalPO(this)" class="btn btn-danger btn-labeled btn-labeled-left legitRipple"><b><i class="icon-arrow-up16"></i></b> Return</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade show" id="terima">
                <div class="card-body">
                    <div class="row">
                        @php
                        $shipping = getCabang('3');
                        @endphp
                        <div class="col-sm-6">
                            <div class="mb-4">
                                <ul class="list list-unstyled mb-0">
                                    <li>
                                        <h6 class="font-weight-bold">Default Penerimaan</h6>
                                    </li>
                                    <li>PT MITRA ERA GLOBAL</li>
                                    <li>{!!$shipping->cabang_address!!}</li>
                                    <li>{!!$shipping->cabang_phone!!}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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