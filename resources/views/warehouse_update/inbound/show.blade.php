@extends('layouts.head')
@section('content')
<div class="content">
    {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
    {!! Form::hidden('id_quo',$main->id_quo,['id'=>'id_quo','class'=>'form-control']) !!}
    {!! Form::hidden('id_po',$main->id,['id'=>'id_po','class'=>'form-control']) !!}
    {!! Form::hidden('idwh',$idwh,['id'=>'idwh','class'=>'form-control']) !!}
    {!! Form::hidden('id_vendor',$main->id_vendor,['id'=>'id_vendor','class'=>'form-control']) !!}
    {!! Form::hidden('type_po',$main->type,['id'=>'type','class'=>'form-control']) !!}
    <div class="card">
        <div class="card-header bg-transparent header-elements-inline">
            <h5 class="card-title"><a href="{{url('warehouse/inbound')}}">WAREHOUSE INBOUND</a> / {{$main->po_number}}
            </h5>
            <div class="header-elements">
                @if($wh_in!=null)
                <ul class="nav nav-left rounded border-0">
                    <li class="nav-item dropdown text-primary">
                        <a class="nav-link rounded-right dropdown-toggle" class="btn btn-primary"
                            data-toggle="dropdown"><i class="fas fa-cloud-download-alt mr-2"></i>Upload SN</a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" onclick="DownloadExcel_inbound(this)" data-id="{{$idwh}}"
                                data-id_quo="{{$main->id_quo}}" data-type="format"><i class="fas fa-file-alt"></i>
                                Download Excel</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" onclick="DownloadExcel_inbound(this)" data-id_po="{{$main->id}}"
                                data-id="{{$idwh}}" data-id_quo="{{$main->id_quo}}" data-type="upload"
                                data-dismiss="modal" data-toggle="modal" data-target="#m_modal2"><i
                                    class="fas fa-arrow-alt-circle-down text-primary"></i>Upload
                                SN</a>
                        </div>
                    </li>
                </ul>
                @endif
                <button type="button" onclick="PrintFinalPO(this)" data-id="{{$main->po_number}}"
                    class="btn btn-warning btn-sm ml-3"><i class="icon-printer mr-2"></i> Print Copy PO</button>
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
                                <a href="#" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id_vendor}}"
                                    onclick="EditVendor(this)">No Email, Click untuk tambah</a>
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
                                <li>By : <span
                                        class="font-weight-semibold">{!!getUserEmp($main->status_by)->emp_name!!}</span>
                                </li>
                                <li>Request Date: <span
                                        class="font-weight-semibold">{{date('d-m-Y', strtotime($main->created_at))}}</span>
                                </li>
                                <li>Approve Date: <span
                                        class="font-weight-semibold">{{date('d-m-Y', strtotime($main->status_time))}}</span>
                                </li>
                                <li>Referensi: <span class="font-weight-semibold">SO{!!sprintf("%06d",
                                        getQuo($main->id_quo)->id)!!} </span></li>
                                <li>Nomer Paket: <span class="font-weight-semibold">{!!sprintf("%06d",
                                        getQuo($main->id_quo)->quo_no)!!} </span></li>
                            </ul>
                            @php }else {
                            $gstatus = $method=='put' ? getWarehouse("id_purchase",$main->id)->status : $main->status;
                            @endphp
                            <h1 class="font-weight-bold text-danger">{{strtoupper($gstatus)}}</h1>
                            <ul class="list list-unstyled mb-0">
                                <li>Purchasing Order: <span
                                        class="font-weight-semibold">{{date('d-m-Y', strtotime($main->created_at))}}</span>
                                </li>
                                <li>Referensi: <span class="font-weight-semibold">SO{!!sprintf("%06d",
                                        getQuo($main->id_quo)->id)!!} </span></li>
                            </ul>
                            @php }@endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-tabs-responsive bg-light border-top">
            <ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
                <li class="nav-item"><a href="#info" class="nav-link active" data-toggle="tab"><i
                            class="icon-menu7 mr-2"></i> Produk</a></li>
                <li class="nav-item"><a href="#terima" class="nav-link" data-toggle="tab"><i
                            class="icon-cart-add2 mr-2 text-success"></i> Penerimaan</a></li>
                <li class="nav-item"><a href="#history" class="nav-link" data-toggle="tab"><i
                            class="icon-menu2 mr-2 text-primary"></i> History Inbound</a></li>
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
                                <th>Qty Terima</th>
                                <th>Qty Problem</th>
                                <th>Note Problem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = 1;
                            $j = 1;
                            $r = 1;
                            foreach ($product as $val){
                            
                            @endphp
                            <tr>
                                <td class="text-center">
                                    @php
                                    if(is_null(CheckWhInbound("sku",$val->sku, $val->id_po, $val->id_quo, $val->qty))){
                                    $attr = "";
                                    $return = "no";
                                    $qtyr = 0;
                                    $qty_problem = "";
                                    $note_problem = "";
                                    $scan = 0;
                                    }else{
                                    $gets = CheckWhInbound("sku",$val->sku, $val->id_po, $val->no_ref==null ?
                                    $val->id_quo : $val->no_ref, $val->qty);
                                    $statusv = $gets->status_note;
                                    $attr = 'checked';
                                    $qty_problem = $gets->qty_problem;
                                    $return = $gets->qty_problem == null ? "" : "checked";
                                    $qtyb = $gets->qty_check == "yes" ? "checked" : "";
                                    $qtyv = $gets->qty_note;
                                    $qtyr = $gets->qty_terima;
                                    $note_problem = $gets->note_problem;
                                    $scan = CheckSNin($val->id_quo, $val->sku);                                    
                                    }
                                    @endphp
                                    <input name="quos[]" type="hidden" value="{{$val->no_ref==null ? $val->id_quo : $val->no_ref}}" class="form-control">
                                    <input name="terima[]" type="checkbox" value="{{$val->sku.$j++}}"
                                        class="form-check-input-switchery" {!!$attr!!} data-fouc>
                                </td>
                                <td>
                                    <span
                                        class="font-weight-bold">{{"SO".sprintf("%06d", $val->no_ref==null ? $val->id_quo : $val->no_ref)}}</span><br>
                                    <input name="id_dum[]" type="hidden" value="{{$val->sku.$r++}}"
                                        class="form-control">
                                    <input name="id_product[]" type="hidden" value="{{$val->sku}}" class="form-control">
                                    {!!getProductDetail($val->sku)->name!!}
                                    @if($method=='put')
                                    <p onclick="ScanBarcode(this)" data-dismiss="modal" data-toggle="modal"
                                        data-target="#m_modal" data-qty="{{$qtyr-$scan}}" data-scan="{{$scan}}"
                                        data-namebarang="{!!getProductDetail($val->sku)->name!!}"
                                        data-quo="{{$val->id_quo}}" data-type="inbound" data-id_inbound="{{$idwh}}"
                                        data-product="{{$val->sku}}"><i class="icon-barcode2 icon-2x"></i>
                                    </p>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <input name="qty_po[]" type="text" value="{{$val->qty}}" class="form-control"
                                        placeholder="Quantity PO" readonly>
                                </td>
                                <td class="text-center">
                                    <input name="qty_terima[]" type="number" value="{{$qtyr==0 ? '' : $qtyr}}" class="form-control"
                                        placeholder="Qty Terima">
                                </td>
                                <!-- <td class="text-center">
                                    <input name="return[]" type="checkbox" value="{{$val->sku}}"
                                        class="form-check-input-switchery" {!!$return!!} data-fouc>
                                </td> -->
                                <td class="text-center">
                                    <input name="qty_problem[]" type="number" value="{{$qty_problem}}"
                                        class="form-control" placeholder="Qty Problem">
                                </td>
                                <td class="text-center">
                                    <input name="note_problem[]" type="text" value="{{$note_problem}}"
                                        class="form-control" placeholder="Note Problem">
                                </td>
                            </tr>
                            @php } @endphp
                        </tbody>
                    </table>
                </div>
                @php if($main->status=='approve'){ @endphp
                <div class="card-body">
                    <div class="d-md-flex flex-md-wrap">

                        <div class="pt-2 mb-3 wmin-md-400 ml-auto">
                            <button type="submit" class="btn btn-primary btn-labeled btn-labeled-left legitRipple">
                                <b><i class="icon-download10"></i></b> @php echo $method=='put' ? 'Update
                                Terima':'Terima'; @endphp
                            </button>
                            <button type="button" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}"
                                data-type="reject" onclick="ShowApprovalPO(this)"
                                class="btn btn-danger btn-labeled btn-labeled-left legitRipple"><b><i
                                        class="icon-arrow-up16"></i></b> Return</button>
                        </div>
                    </div>
                </div>
                @php } @endphp
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
            <div class="tab-pane fade show" id="history">
                @if($wh_in!=null)
                @if(count($history)!=0)
                <div class="card-body">
                    <!-- <div class="card-header">
                        <h6 class="card-title"><span class="font-weight-semibold">History</span> Activity</h6>
                    </div> -->

                    <div class="card-body">
                        <div class="timeline-group">
                            @php foreach($history as $det) { @endphp
                            <div class="timeline-item">
                                <div class="timeline-time">{{$det->created_at}}</div>
                                <div class="timeline-body">
                                    <p class="timeline-title"><a
                                            href="">{!!div_name(getUserEmp($det->created_by)->division_id)!!}
                                            ( {!!getUserEmp($det->created_by)->emp_name!!} )</a></p>
                                    <p class="timeline-text">{!!$det->activity!!}
                                        {{getProductDetail($det->sku)== null ? null : getProductDetail($det->sku)->name}}
                                        {{$det->qty_terima==null ? '' : 'dengan quantity terima '.$det->qty_terima}}
                                        {{$det->qty_problem==null ? '' : 'dan dengan quantity
                                        problem '.$det->qty_problem}}</p>
                                </div><!-- timeline-body -->
                            </div><!-- timeline-item -->
                            @php } @endphp
                            <br>
                            <div class="col-lg-5">
                                <a href="#" data-toggle="modal" data-target="#m_modal" data-id="{{$wh_in->id}}"
                                    onclick="All_history(this)" class="btn btn-primary">Lihat Semua History</a>
                            </div>
                            <br>
                            <div class="col-lg-5">
                                <a href="#" data-toggle="modal" data-target="#m_modal2" data-id="{{$wh_in->id}}"
                                    onclick="add_note_inbound(this)" class="btn btn-info">Tambah Keterangan</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @else
                <br>
                <br>
                <span class="text-danger" style="padding-left: 20px;"><b>History belum Tersedia</b></span>
                <br>
                <br>
                @endif
            </div>
        </div>
        <div class="card-footer">
            <span class="text-muted">Sebelum melakukan pengiriman, harap memeriksa kembali kesesuain data data yang
                ada</span>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@include('sales.quotation.attribute.modal')
@endsection
@section('script')
<script src="{{ asset('ctrl/warehouses/wh-detail.js') }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/purchasing/mail-po.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/warehouses/wh-scan.js?v=').rand() }}" type="text/javascript"></script>

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