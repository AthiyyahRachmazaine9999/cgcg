@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card">
        <div class="card-header bg-transparent header-elements-inline">
            <h5 class="card-title"><a href="{{url('warehouse/outbound')}}">PROFORMA INVOICE</a> - SO{{sprintf("%06d", $main->id)}} </h5>
            <div class="header-elements">
            </div>
        </div>
        <div class="nav-tabs-responsive bg-light border-top">
            <ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
                <li class="nav-item"><a href="#info" class="nav-link active" data-toggle="tab"><i class="icon-menu7 mr-2"></i> Form</a></li>
                <li class="nav-item"><a href="#history" class="nav-link" data-toggle="tab"><i class="icon-archive mr-2"></i> History</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="info">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-4">
                                <ul class="list list-unstyled mb-0">
                                    <li>
                                        <h6 class="font-weight-bold">{{$cust->company}}</h6>
                                    </li>
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
                                    <h1 class="font-weight-bold text-danger">{{$main->quo_no}}</h1>
                                    <h5 class="font-weight-bold">Sales : {!! getEmp($main->id_sales)->emp_name !!}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::open(['method' => $method,'action'=>$action]) !!}
                    <div class="row">
                        {!! Form::hidden('id',$main->id,['id'=>'id','class'=>'form-control']) !!}
                        {!! Form::hidden('dbs',$dbs,['id'=>'dbs','class'=>'form-control']) !!}
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" id="end_date" class="form-control" data-column="5" name="date" placeholder="Enter Date" require>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tanggal Jatuh Tempo</label>
                                <input type="date" id="tempo" class="form-control" data-column="5" name="tempo" placeholder="Enter Date" require>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Sign By</label>
                                <select class="form-control form-control-select2" name="user" id="user" require>
                                    <option></option>
                                    @foreach ($user as $spv)
                                    <option value="{{ $spv->id }}">{{ $spv->emp_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Pembulatan (Masukan jumlah digit)</label>
                                {!! Form::number('digit','',['id'=>'pembulatan','class'=>'form-control','placeholder'=>'1-3 digit']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tipe Invoice</label>
                                <select class="form-control form-control-select2" name="jenis" id="type">
                                    <option value="normal" selected>Normal</option>
                                    <option value="partial">Partial</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6" id="partial">
                            <div class="form-group">
                                <label>Jenis Partial</label>
                                <select class="form-control form-control-select2" name="partial" id="part">
                                    <option></option>
                                    <option value="termin">Termin (%)</option>
                                    <option value="nominal">Nominal</option>
                                    <option value="qty">Qty / Jenis Barang</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary" id="textinv">@if($kondisi=='partial') Generate @else Update @endif<i class="far fa-save ml-2"></i></button>
                    </div>

                    <div id="tabspartial"></div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="tab-pane fade" id="history">
                <div class="table-responsive">
                    <table class="table table-lg">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nomer Invoice</th>
                                <th>Type</th>
                                <th>Nominal</th>
                                <th>Generate By</th>
                                <th>Tanggal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($kondisi=='partial')
                            @php $i=1; foreach($history as $val){ @endphp
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$val->no_invoice}}</td>
                                <td>
                                    @php
                                    if($kondisi=='normal'){
                                    echo ucfirst($kondisi);
                                    }else{
                                    echo ucfirst($val->partial);
                                    }
                                    @endphp

                                </td>
                                <td>{{$val->payment_amount}}</td>
                                <td>{!! getEmp($val->created_by)->emp_name !!}</td>
                                <td>{{$val->created_at}}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="list-icons-item dropdown">
                                            <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="#" class="dropdown-item" onclick="EditInvoice(this)" data-toggle="modal" data-target="#m_modal" data-id="{{$val->id}}" data-kondisi="{{$kondisi}}" data-type="{{$dbs}}" data-noinvoice="{{$val->no_invoice}}" data-target="#modal"><i class="icon-pencil"></i> Edit</a>
                                                <a href="#" class="dropdown-item" onclick="CetakInvoice(this)" data-id="{{$val->id}}" data-kondisi="{{$kondisi}}" data-noinvoice="{{$val->no_invoice}}"><i class="icon-printer"></i> Cetak Invoice</a>
                                                <div class="dropdown-divider"></div>
                                                <a href="#" class="dropdown-item" onclick="DeleteInvoice(this)" data-id="{{$val->id}}" data-kondisi="{{$kondisi}}" data-noinvoice="{{$val->no_invoice}}"><i class="icon-trash text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @php }@endphp
                            @else
                            @if($history!=null)
                            <tr>
                                <td>1</td>
                                <td>{{$history->no_invoice}}</td>
                                <td>Normal</td>
                                <td>-</td>
                                <td>{!! getEmp($history->created_by)->emp_name !!}</td>
                                <td>{{$history->created_at}}</td>
                                <td class="text-center">
                                    <button type="button" onclick="CetakInvoice(this)" data-id="{{$history->id}}" data-kondisi="{{$kondisi}}" data-noinvoice="{{$history->no_invoice}}" class="btn btn-outline bg-primary border-primary text-primary-800 btn-icon"><i class="icon-printer"></i></button>
                                </td>
                            </tr>
                            @endif
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer mt-3">
            <span class="text-muted">Sebelum mencetak invoice, harap memeriksa kembali kesesuain data data yang ada</span>
        </div>
    </div>
</div>

@include('sales.quotation.attribute.modal')
@endsection
@section('script')
<script src="{{ asset('ctrl/sales/quotation-invoice.js?v=').rand() }}" type="text/javascript"></script>

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