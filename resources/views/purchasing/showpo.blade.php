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
                                <li>Send Date: <span class="font-weight-semibold">{{date('d-m-Y', strtotime($main->kirim_time))}}</span></li>
                            </ul>
                            @php }else {@endphp
                            <h1 class="font-weight-bold text-danger">PENDING</h1>
                            <ul class="list list-unstyled mb-0">
                                <li>Request Date: <span class="font-weight-semibold">{{date('d-m-Y', strtotime($main->created_at))}}</span></li>
                                <li>Referensi: <span class="font-weight-semibold"></span></li>
                            </ul>
                            @php }@endphp
                            <div id="new_date">
                                @if($main->order_at==null)
                                @else
                                Costum PO Date : {{date('Y-m-d', strtotime($main->order_at))}}
                                @endif
                            </div>
                            <button type="button" onclick="changedate(this)" class="btn btn-primary btn-sm mt-3" id="cdate"><i class="icon-calendar2 mr-2"></i> Ganti Tanggal</button>
                            <div class="row mt-3" id="dates">
                                {!! Form::label('date', 'Ganti Tanggal PO', ['class' => 'col-form-label col-lg-5']) !!}
                                <div class="col-lg-6">
                                    <input type="date" id="end_date" class="form-control" data-column="5" name="date" placeholder="Enter Date">
                                </div>
                                <button type="button" onclick="save_date(this)" data-id="{{$main->po_number}}" class="col-lg-1 btn bg-warning-400 btn-icon legitRipple"><i class="icon-floppy-disk"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-tabs-responsive bg-light border-top">
            <ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
                <li class="nav-item"><a href="#info" class="nav-link active" data-toggle="tab"><i class="icon-menu7 mr-2"></i> Produk</a></li>
                <li class="nav-item"><a href="#fwd" class="nav-link" data-toggle="tab"><i class="icon-truck mr-2"></i> Pengiriman</a></li>
                <li class="nav-item"><a href="#payv" class="nav-link" data-toggle="tab"><i class="icon-menu2 mr-2"></i>Claim Payment Voucher</a></li>
                @if($usr->division_id == 3 || $usr->id==10)
                <li class="nav-item"><a href="#dtl_vendor" class="nav-link" data-toggle="tab"><i class="fas fa-columns mr-2"></i>
                        Payment Detail</a></li>
                @endif
                <li class="nav-item"><a href="#log" class="nav-link" data-toggle="tab"><i class="fa fa-history mr-2 text-success"></i>Log Activity</a></li>
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
                                    <p class="text-danger font-weight-bold">{{$val->sku}}</p>
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
                        @php $text_extra = $main->note_order==null ? "": $main->note_order;@endphp
                        <i><b>Catatan :</b> {!!$text_extra!!}</i>

                        <div class="pt-2 mb-3 wmin-md-400 ml-auto">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        @php
                                        $vat = $subtotal_final*(GetPPN($main->created_at,$main->created_at)/100);
                                        if($main->isppn=="yes") { @endphp
                                        <tr>
                                            <th>Subtotal</th>
                                            <td class="text-right">@php echo number_format($subtotal_final); @endphp</td>
                                        </tr>
                                        <tr>
                                            <th>PPN {!!GetPPN($main->created_at,$main->created_at)!!}%</th>
                                            <td class="text-right">
                                                @php
                                                echo number_format($vat);
                                                @endphp
                                            </td>
                                        </tr>
                                        @php
                                        $total_akhir = $subtotal_final+$vat;
                                        } else { $total_akhir = $subtotal_final; }@endphp
                                        <tr>
                                            <th>Total</th>
                                            <td class="text-right text-primary">
                                                <h5 class="font-weight-semibold">
                                                    @php
                                                    echo number_format($total_akhir);
                                                    @endphp
                                                </h5>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @php
                            if($main->isppn=='yes'){
                            $isppn = "yes";
                            $iscolor = "danger";
                            $istext = "Non PPN";
                            }else{
                            $isppn = "no";
                            $iscolor = "success";
                            $istext = "With PPN";
                            }
                            @endphp

                            <div class="text-right mt-3">
                                <button type="button" class="btn btn-{{$iscolor}} btn-labeled btn-labeled-left" onclick="ChangePPN(this)" data-id="{{$main->id}}" data-isppn="{{$isppn}}"><b><i class="icon-percent"></i></b> {{$istext}}</button>
                                @php $msg = $main->note_order==null ? "Tambah Catatan":"Edit Catatan"; @endphp
                                <button type="button" class="btn btn-warning btn-labeled btn-labeled-left" onclick="AddNote(this)" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}"><b><i class="icon-comment-discussion"></i></b> {{$msg}}</button>
                                @php if($main->status=='approve'){ @endphp
                                <button type="button" class="btn btn-primary btn-labeled btn-labeled-left" onclick="KirimEmail(this)" data-toggle="modal" data-target="#m_modal" data-type="{{$main->type}}" data-id="{{$main->id}}"><b><i class="icon-paperplane"></i></b> Kirim PO</button>
                                @php } else { @endphp
                                <button type="button" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" data-type="approve" onclick="ShowApprovalPO(this)" class="btn btn-primary btn-labeled btn-labeled-left legitRipple"><b><i class="icon-checkmark4"></i></b> Approve</button>
                                <button type="button" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" data-type="reject" onclick="ShowApprovalPO(this)" class="btn btn-danger btn-labeled btn-labeled-left legitRipple"><b><i class="icon-cancel-circle2"></i></b> Reject</button>
                                @php } @endphp
                            </div>
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

            <div class="tab-pane fade show" id="payv">
                <div class="card-body" id="pay_voucher">
                    @if(count($pay_dtl)!=0)
                    <div class="table-responsive">
                        <table class="table table-lg">
                            <thead>
                                <tr>
                                    <th>Tujuan</th>
                                    <th>No. Payment Voucher</th>
                                    <th>No. Invoice</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th colspan="2" clas="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1; foreach($pay_dtl as $paydtl){ @endphp
                                <tr>
                                    <td>{{$paydtl->tujuan}}</td>
                                    <td>{{$paydtl->no_payment==null?'-' : $paydtl->no_payment}}</td>
                                    @if($paydtl->no_invoice!=null)
                                    <td>{{$paydtl->no_invoice}}</td>
                                    @else
                                    <td>{{$paydtl->performa_invoice}}</td>
                                    @endif
                                    @if($paydtl->status=="Approved" || $paydtl->status=="Completed" ||
                                    $paydtl->status=="Done Payment")
                                    <td><span class="badge badge-flat border-primary text-primary-600 d-block d-block text-center">{{$paydtl->status}}</span>
                                    </td>
                                    @else
                                    <td><span class="badge badge-flat border-danger text-danger-600 d-block d-block text-center">{{$paydtl->status}}</span>
                                    </td>
                                    @endif
                                    <td>{{Carbon\Carbon::parse($paydtl->created_at)->format('d F Y')}}</td>
                                    <td>
                                        <button type="button" class="btn bg-primary-400 btn-icon rounded-round legitRipple" onClick="Payv_form(this)" data-type="Edit" data-id_po="{{$main->po_number}}" data-id_pay="{{$paydtl->id_pay}}"><b>
                                                <i class="fas fa-edit"></i></b></button>
                                        <button type="button" class="btn bg-pink-400 btn-icon rounded-round legitRipple" onClick="Payv_form(this)" data-toggle="modal" data-target="#m_modal" data-type="show" data-id="{{$paydtl->id_pay}}">
                                            <b><i class="fas fa-eye"></i></b></button>

                                        @if($paydtl->status=='Approved' || $paydtl->status=="Completed")
                                        @if($usr->division_id == 3 || $usr->id==10)
                                        <button type="button" class="btn bg-warning-400 btn-icon rounded-round legitRipple" onclick="paymentFinance(this)" data-id="{{$main->po_number}}" data-id_pay="{{$paydtl->id_pay}}" data-toggle="modal" data-target="#m_modal"><b><i class="fas fa fa-money-bill-wave-alt"></i></b></button>
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                                @php } @endphp
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <button onClick="Payv_form(this)" data-id_po="{{$main->po_number}}" data-type="Create" class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i class="fas fa-plus"></i></b></button><br><br>
                </div>
            </div>


            <div class="tab-pane fade show" id="dtl_vendor">
                @php if (count($payvendor)!=0) { @endphp
                <div class="table-responsive">
                    <table class="table table-lg">
                        <thead>
                            <tr>
                                <th>No Payment</th>
                                <th>Nominal</th>
                                <th>Bank</th>
                                <th>Note</th>
                                <th>Pembayaran</th>
                                <th>Dokumen</th>
                                <th>Status</th>
                                <th>tanggal Pembayaran</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i=1; foreach($payvendor as $pay){ @endphp
                            <tr>
                                <td>{{$paydtl==null?'-' : $paydtl->no_payment}}</td>
                                <td>{{$pay->pays==null? '-' : number_format($pay->pays)}}</td>
                                <td>{{$pay->bank_name==null? '-' : $pay->bank_name.' - '.$pay->no_rek}}</td>
                                <td>{{$pay->note==null ? '---' : $pay->note}}</td>
                                @php if($pay->doc_pay!=null) { @endphp
                                <td>
                                    <a href="{{ asset($pay->doc_pay) }}" class="btn btn-outline-primary btn-sm">SHOW</a>
                                </td>
                                @php } else { @endphp
                                <td>
                                    <button class="btn btn-primary" disabled>SHOW</button>
                                </td>
                                @php } @endphp
                                @php if($pay->doc_other!=null) { @endphp
                                <td>
                                    <a href="{{asset($pay->doc_other)}}" class="btn btn-primary">SHOW</a>
                                </td>
                                @php } else { @endphp
                                <td>
                                    <button class="btn btn-primary" disabled>SHOW</button>
                                </td>
                                @php } @endphp
                                @php if($pay->status=="parsial") { @endphp
                                <td>
                                    <p class="text-danger">Parsial</p>
                                </td>
                                @php } else if ($pay->status=="lunas") { @endphp
                                <td>
                                    <p class="text-primary">Lunas</p>
                                </td>
                                @php } else { @endphp
                                <td>--</td>
                                @php } @endphp
                                <td>{{\Carbon\Carbon::parse($pay->date_payment)->format('d F Y')}}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="list-icons-item dropdown">
                                            <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="#" class="dropdown-item" onclick="EditPays(this)" data-id="{{$pay->id}}" data-id_po="{{$pay->id_po}}" data-toggle="modal" data-target="#m_modal" data-id_quo="{{$pay->id_quo}}" data-type="tambah"><i class="fas fa-pencil-alt text-primary"></i>
                                                    Edit</a>
                                                <div class="dropdown-divider"></div>
                                                <a href="#" class="dropdown-item" onclick="HapusPays(this)" data-type="other" data-id="{{$pay->id}}" data-id_po="{{$pay->id_po}}" data-id_quo="{{$pay->id_quo}}"><i class="fas fa-trash text-warning"></i>
                                                    Hapus</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @php } @endphp
                        </tbody>
                    </table>
                </div>
                @php } else { @endphp
                <br>
                <div class="text-center">
                    <span class="text-danger font-weight-bold">Belum Ada Data Yang Masuk</span>
                    <br><br>
                </div>
                @php } @endphp
            </div>

            <div class="tab-pane fade show" id="log">
                @include('purchasing.attribute.activity_log')
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
<script src="{{ asset('ctrl/purchasing/mail-po.js?v=').rand() }}" type="text/javascript"></script>
<script>
    $('#dates').hide();
</script>
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