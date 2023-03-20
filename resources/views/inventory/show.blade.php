@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card">
        <div class="card-header bg-transparent header-elements-inline">
            <h5 class="card-title"><a href="{{url('warehouse/inbound')}}">INVENTORY DETAIL</a> / {!!Request::segment(3) !!} </h5>

        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-4">
                        <ul class="list list-unstyled mb-0">
                            <li>
                                <h6 class="font-weight-bold">{{$data->name}}</h6>
                            </li>
                            <li>{!!strip_tags(htmlspecialchars_decode($data->description==''?$data->overview : $data->description))!!}</li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="mb-4 mt-4">
                        <div class="text-sm-right">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-tabs-responsive bg-light border-top">
            <ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
                <li class="nav-item"><a href="#order" class="nav-link active" data-toggle="tab"><i class="icon-menu7 mr-2"></i> Order</a></li>
                <li class="nav-item"><a href="#rusak" class="nav-link" data-toggle="tab"><i class="fas fa-recycle mr-2 text-danger"></i> Rusak</a></li>
                <li class="nav-item"><a href="#pinjam" class="nav-link" data-toggle="tab"><i class="fas fa-book mr-2"></i>Pinjam Stock</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="order">

                <div class="table-responsive">
                    <table class="table table-lg">
                        <thead>
                            <tr>
                                <th class="text-center">PO Number</th>
                                <th class="text-center">SO Number</th>
                                <th class="text-center">Qty IN</th>
                                <th class="text-center">Qty Out</th>
                                <th class="text-center">Sisa</th>
                                <th class="text-center">DO</th>
                                <th class="text-center">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $s = 0 ;
                            foreach ($main as $val) {
                            $qty_out = $val->status<>'order' ? $val->qty : 0;
                                $qty_in = $val->status== 'order' ? $val->qty : 0;

                                if($val->id_purchase!=null){
                                $get = $val->status=='pinjam'? 'pinjam' : '-';
                                $nomerdo = '-';
                                $tanggal = '-';
                                }else{
                                $get = $val->status=='pinjam'? 'Pinjam' : 'SO'.sprintf("%06d", $val->id_quo);
                                $datawh = CheckDODetail($val->id_quo,Request::segment(3));
                                $nomerdo = is_null($datawh) ? '-' : $datawh->nomer;
                                $tanggal = is_null($datawh) ? '-' : $datawh->kirim;
                                }


                                @endphp
                                <tr>
                                    <td>{{$val->id_purchase}}</td>
                                    <td>@php echo $get; @endphp </td>
                                    <td class="text-right">{{number_format($qty_in)}}</td>
                                    <td class="text-right">{{number_format($qty_out)}}</td>
                                    <td class="text-right">{{number_format(QtySisa($val->id))}}</td>
                                    <td>{{$nomerdo}}</td>
                                    <td>{{$tanggal}}</td>
                                </tr>
                                @php }
                                @endphp
                        </tbody>
                    </table>
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
            <div class="tab-pane fade show" id="pinjam">
                <div class="table-responsive">
                    <table class="table table-lg">
                        <thead>
                            <tr>
                                <th>DO Number</th>
                                <th>Qty</th>
                                <th>Peminjam</th>
                                <th>Tanggal Pinjam</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $s = 0 ;
                            foreach ($pinjamdet as $vals) {
                            $no_do = "WH/OUT/PJM/".date('y',strtotime($vals->tanggal_pinjam)).'/'.$vals->id;
                            $customer = getCustomer($vals->id_customer);
                            $alamat_pinjam = $vals->alamat == null ? $customer->address : $vals->alamat;
                            @endphp
                            <tr>
                                <td>{{$no_do}}</td>
                                <td>{{$vals->qty_pinjam}}</td>
                                <td>
                                    {{$vals->nama_peminjam}}
                                    <p>{!!$customer->company!!}</p>
                                    <p>{!!$alamat_pinjam!!}</p>

                                </td>
                                <td>{{date('Y-m-d',strtotime($vals->tanggal_pinjam))}}</td>
                                <td>{{$vals->note}}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" onclick="DO_Edit(this)" data-id="{{$vals->id}}" data-toggle="modal" data-target="#m_modal"><i class="icon-pencil5"></i> Edit</a>
                                                <a class="dropdown-item" onclick="DO_Cetak(this)" data-id="{{$vals->id}}"><i class="icon-printer text-primary"></i> Print</a>
                                                <a class="dropdown-item" onclick="DO_Delete(this)"><i class="icon-trash text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @php }@endphp
                        </tbody>
                    </table>
                </div>
                <br>
                <div style="padding-left:10px; padding-bottom:10px;">
                    <button type="button" class="btn btn-primary btn-labeled btn-labeled-left legitRipple" onClick="Pinjam_Stock(this)" data-sku="{{$sku}}" data-price="{{$price}}" data-type="tambah" id="" data-toggle="modal" data-target="#m_modal">
                        <b><i class="fas fa-plus"></i></b>Pinjam Stock</button>
                </div>
                <br>
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
<script src="{{ asset('ctrl/warehouses/inventory-detail.js?v=').rand()}}" type="text/javascript"></script>

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