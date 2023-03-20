@extends('layouts.head')
@section('content')
<div class="content mb-3">
    <div class="row">
        <div class="col-lg-8">
            <ul class="nav nav-tabs nav-tabs-solid nav-justified rounded border-0">
                <li class="nav-item"><a href="#detail" class="nav-link rounded-left active" data-toggle="tab"><i class="fas fa-align-left mr-2"></i>Detail</a></li>
                <li class="nav-item"><a href="#customer" class="nav-link" data-toggle="tab"><i class="far fa-address-card mr-2"></i>Customer</a></li>
                @php
                if($check){
                @endphp
                <li class="nav-item dropdown">
                    <a class="nav-link rounded-right dropdown-toggle"><i class="fas fa-cloud-download-alt mr-2"></i>Download</a>
                </li>
                @php }else{ @endphp
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link rounded-right dropdown-toggle" data-toggle="dropdown"><i class="fas fa-cloud-download-alt mr-2"></i>Download</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item" data-type="precalc" onclick="Export(this)" data-id="{{$main->id}}"><i class="fas fa-file-excel mr-2 text-success"></i>Precall (xls)</a>
                        <a href="#" class="dropdown-item" data-type="so" onclick="Export(this)" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}"><i class="fas fa-file-pdf mr-2 text-danger"></i>
                            @if($main->quo_type=='1')
                            Quotation (pdf)
                            @else
                            Sales Order (pdf)
                            @endif
                        </a>
                        @php
                        $idk = getUserEmp(Auth::user()->id)->id;
                        if($main->id_admin==$idk or $main->id_sales==$idk or $main->created_by==$idk){
                        @endphp
                        <a href="#" class="dropdown-item" data-type="invoice" onclick="KirimEmail(this)" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}"><i class="icon-paperplane mr-2 text-primary"></i>Email Quotation</a>
                        @php } @endphp
                        <a href="#" class="dropdown-item" data-type="invoice" onclick="Export(this)" data-id="{{$main->id}}" data-dbs="baru"><i class="fas fa-file-pdf mr-2 text-danger"></i>Invoice (pdf)</a>
                    </div>
                </li>
                @php } @endphp
            </ul>

            <div class="card">
                <div class="card-header alpha-primary text-success-800 border-bottom-success header-elements-inline">
                    <h5 class="card-title">SO{{sprintf("%06d", $main->id)}} [ {{$main->type_name}} ]</h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            @php
                            $idk = getUserEmp(Auth::user()->id)->id;
                            if($main->id_admin==$idk or $main->id_sales==$idk or $main->created_by==$idk){
                            @endphp
                            <a class="btn bg-danger btn-labeled btn-labeled-left rounded-round legitRipple" href="{{ url('sales/quotation/' . $main->id).'/edit' }}"><b><i class="fas fa-edit"></i></b> Edit</a>
                            @php }
                            if($main->quo_type=='2' || $main->quo_type=='3'){
                            $name = explode("-",$main->quo_no);
                            $jml_segmen = count($name);
                            $link = $main->quo_type=='2' ? "https://e-katalog.lkpp.go.id/v2/en/purchasing/paket/detail/".$name[$jml_segmen-1] : "https://siplah.blibli.com/merchant/order-detail/".$main->quo_no;
                            $text = $main->quo_type=='2' ? "LKPP" : "SipLah";
                            @endphp
                            <a type="button" target="_blank" href="{!!$link!!}" class="btn bg-teal-400 btn-labeled btn-labeled-left rounded-round"><b><i class="fas fa-external-link-alt"></i></b> VIEW {!!$text!!}</a>
                            @php } @endphp
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h2 class="mb-0 font-weight-semibold">{{$main->quo_no}}</h2>
                            Created By {!! getUserEmp($main->created_by)->emp_name!!}
                        </div>
                        <div class="col-lg-6">
                            <div class="text-right">
                                <h2 class="mb-0 font-weight-semibold text-danger">Rp. {{number_format(GetTotalAkhir($main->id))}}</h2>
                                <span class="text-danger">*) Harga Include</span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="detail">
                        @include('sales.quotation.attribute.catalog')
                    </div>

                    <div class="tab-pane fade" id="customer">
                        @include('sales.quotation.attribute.customer')
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            @include('sales.quotation.attribute.act')
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            @if (in_array(Session::get('division_id') , explode(',',getConfig('product_nomargin'))))
            @if (Session::get('division_id')=='6')
            @include('sales.quotation.attribute.product-alt')
            @else
            @include('sales.quotation.attribute.product-content')
            @endif
            @else
            @include('sales.quotation.attribute.product')
            @endif
        </div>
    </div>
</div>
@include('sales.quotation.attribute.modal')
@endsection
@section('script')
<script src="{{ asset('ctrl/sales/quotation-detail.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/sales/quotation-product.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/sales/quotation-act.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/sales/quotation-doc.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/sales/quotation-purchasing.js?v=').rand() }}" type="text/javascript"></script>

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