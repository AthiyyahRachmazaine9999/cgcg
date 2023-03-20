<div class="nav-tabs-responsive bg-light border-top">
    <ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
        <li class="nav-item"><a href="#info" class="nav-link active" data-toggle="tab"><i class="icon-menu7 mr-2"></i> Info Utama</a></li>
        <li class="nav-item"><a href="#doc" class="nav-link" data-toggle="tab"><i class="icon-archive mr-2"></i> Kelengkapan Dokumen</a></li>
        <li class="nav-item"><a href="#invoice" class="nav-link" data-toggle="tab"><i class="icon-credit-card2 mr-2"></i> Invoicing</a></li>
    </ul>
</div>
<div class="tab-content">
    <div class="tab-pane fade show active" id="info">
        <div class="table-responsive">
            <table class="table table-lg">
                <tbody>
                    <tr>
                        <td class="text-left font-weight-bold">Nama Paket</td>
                        <td class="text-primary font-weight-bold">{{$main->quo_name}}</td>
                    </tr>
                    <tr>
                        <td class="text-left font-weight-bold">Status Eksternal</td>
                        <td class="font-weight-bold">
                            <div class="row">
                                <div class="col-lg-8">
                                    Status : {{$main->quo_eksstatus}}<br>
                                    Posisi : {{$main->quo_eksposisi}}<br>
                                    Kondisi :
                                    @php
                                    if (!empty( $main->quo_approve_status)) {
                                        echo $main->quo_ekskondisi == 'Batal' ? $main->quo_ekskondisi : ucfirst($main->quo_approve_status);
                                    }
                                    else{
                                        echo $main->quo_ekskondisi;
                                    }
                                    @endphp<br>
                                </div>
                                <div class="col-lg-4">
                                    <button type="button" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" onclick="EditStatus(this)" class="btn btn-success btn-labeled btn-labeled-left btn-sm legitRipple pull-right"><b><i class="icon-pencil5"></i></b> Ubah</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left font-weight-bold">Jangka Waktu Pelaksanaan</td>
                        <td class="text-danger font-weight-bold">{{$main->quo_deadline}}</td>
                    </tr>
                    <tr>
                        <td class="text-left font-weight-bold">Instansi</td>
                        <td>{{$cust->company}}</td>
                    </tr>
                    <tr>
                        <td class="text-left font-weight-bold">Satuan Kerja</td>
                        <td>{{$cust->company}}</td>
                    </tr>
                    <tr>
                        <td class="text-left font-weight-bold">Admin</td>
                        <td>{!! getEmp($main->id_admin)->emp_name !!}</td>
                    </tr>
                    <tr>
                        <td class="text-left font-weight-bold">Sales</td>
                        <td>{!! getEmp($main->id_sales)->emp_name !!}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane" id="doc">
        <div class="row">
            <div class="col-lg-12">
                @php
                if(Session::get('division_id')=='10' or Session::get('division_id')=='3' or Session::get('division_id')=='8'){
                @endphp
                <button type="button" data-toggle="modal" data-target="#m_modal" data-val={{$val}} data-id="{{$main->id}}" onclick="EditDocument(this)" class="btn btn-success btn-labeled btn-labeled-left btn-sm legitRipple ml-3 mt-3 mb-3"><b><i class="icon-pencil5"></i></b> Tambah / Edit Dokumen</button>
                @php } @endphp
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-lg">
                <tbody>
                    <tr>
                        <td class="text-left font-weight-bold">No. PO Customer</td>
                        <td>{{$main->quo_no}} - {{$main->quo_order_at}}</td>
                        @if($document->doc_po!=null)
                        <td>
                            <a href="{{asset('public/'.$document->doc_po)}}" class="btn btn-primary btn-labeled btn-labeled-left btn-sm legitRipple text-right"><b>
                                    <i class="fas fa-cloud-download-alt"></i></b>Download File
                            </a>
                        </td>
                        @endif
                    </tr>
                    
                    <tr>
                        <td class="text-left font-weight-bold">No. SP</td>
                        <td>{{$document->no_sp}} - {{$document->tgl_sp}}</td>
                        @if($document->doc_sp!=null)
                        <td>
                            <a href="{{asset('public/'.$document->doc_sp)}}" class="btn btn-primary btn-labeled btn-labeled-left btn-sm legitRipple text-right"><b>
                                    <i class="fas fa-cloud-download-alt"></i></b>Download File
                            </a>
                        </td>
                        @endif
                    </tr>
                    <tr>
                        <td class="text-left font-weight-bold">No. SPK</td>
                        <td>{{$document->no_spk}} - {{$document->tgl_spk}}</td>
                        @if($document->doc_spk!=null)
                        <td>
                            <a href="{{asset('public/'.$document->doc_spk)}}" class="btn btn-primary btn-labeled btn-labeled-left btn-sm legitRipple text-right"><b>
                                    <i class="fas fa-cloud-download-alt"></i></b>Download File
                            </a>
                        </td>
                        @endif
                    </tr>
                    <tr>
                        <td class="text-left font-weight-bold">No. BAST</td>
                        <td>{{$document->no_bast}} - {{$document->tgl_bast}}</td>
                        @if($document->doc_bast!=null)
                        <td>
                            <a href="{{asset('public/'.$document->doc_bast)}}" class="btn btn-primary btn-labeled btn-labeled-left btn-sm legitRipple text-right"><b>
                                    <i class="fas fa-cloud-download-alt"></i></b>Download File
                            </a>
                        </td>
                        @endif
                    </tr>
                    <tr>
                        <td class="text-left font-weight-bold">Faktur Pajak</td>
                        <td>{{$document->no_fakturpajak}} - {{$document->tgl_fakturpajak}}</td>
                        @if($document->doc_fakturpajak!=null)
                        <td>
                            <a href="{{asset('public/'.$document->doc_fakturpajak)}}" class="btn btn-primary btn-labeled btn-labeled-left btn-sm legitRipple text-right"><b>
                                    <i class="fas fa-cloud-download-alt"></i></b>Download File
                            </a>
                        </td>
                        @endif
                    </tr>
                    <tr>
                        <td class="text-left font-weight-bold">Faktur Penjualan</td>
                        <td>{{$document->no_fakturjual}} - {{$document->tgl_fakturjual}}</td>
                        @if($document->doc_fakturjual!=null)
                        <td>
                            <a href="{{asset('public/'.$document->doc_fakturjual)}}" class="btn btn-primary btn-labeled btn-labeled-left btn-sm legitRipple text-right"><b>
                                    <i class="fas fa-cloud-download-alt"></i></b>Download File
                            </a>
                        </td>
                        @endif
                    </tr>
                    <tr>
                        <td class="text-left font-weight-bold">No. DO </td>
                        <td>{!!getDoNumber($main->id)['number']!!} , Tanggal : {!!getDoNumber($main->id)['date']!!}</td>
                        @if($filedo!=null)
                        <td>
                            <a href="" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" onclick="ShowDObalikan(this)" class="btn btn-primary btn-labeled btn-labeled-left btn-sm legitRipple text-right"><b>
                                    <i class="far fa-eye"></i></b>Lihat DO Balikan
                            </a>
                        </td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane" id="invoice">
        <div class="table_inv">
        <div class="row">
            <div class="col-lg-12">
                @php
                if(Session::get('division_id')=='10' or Session::get('division_id')=='3'){
                @endphp
                    @if($invoice!=null)
                    <button type="button"
                        class="btn btn-success btn-labeled btn-labeled-left btn-sm legitRipple ml-3 mt-3 mb-3"
                        onclick="EditInvoicesSales(this)" data-idquo="{{$main->id}}"><b><i class="icon-pencil5"></i></b>
                        Tambah / Edit Data</button>
                    <button type="button"
                        class="btn btn-primary btn-labeled btn-labeled-left btn-sm legitRipple ml-3 mt-3 mb-3"
                        onclick="ConfirmPayments(this)" data-id_inv="{{$invoice!=null? $invoice->id : null}}"
                        data-idquo="{{$main->idquo}}"><b><i class="fas fa-chevron-circle-right"></i></b>Confirm</button>
                    @endif
                @php } @endphp
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-lg">
                <tbody>
                        <tr>
                            <td class="text-left font-weight-bold">No. NPWP</td>
                            <td>{{$invoice==null ? '' : $invoice->npwp}}</td>
                        </tr>
                        <tr>
                            <td class="text-left font-weight-bold">Nama NPWP</td>
                            <td>{{$invoice==null ? '' : $invoice->npwp_nama}}</td>
                        </tr>
                        <td class="text-left font-weight-bold">No. Invoice</td>
                        <td>
                            {{$invoice==null ? '' : $invoice->no_invoice}}</br>
                            {{$invoice==null ? '' : \Carbon\Carbon::parse($invoice->tgl_invoice)->format('d F Y')}}
                        </td>
                        </tr>
                        <tr>
                            <td class="text-left font-weight-bold">No. NTPN PPh</td>
                            <td>{{$invoice==null ? '' : $invoice->no_ntpn_pph}}</td>
                        </tr>
                        <tr>
                            <td class="text-left font-weight-bold">No. NTPN PPn</td>
                            <td>{{$invoice==null ? '' : $invoice->no_ntpn_ppn}}</td>
                        </tr>
                        <tr>
                            <td class="text-left font-weight-bold">Total Bayar</td>
                            <td>{{$invoice==null ? '' : number_format(sumInvoicePaid($invoice->id))}}</td>
                        </tr>
                        <tr>
                            <td class="text-left font-weight-bold">Note</td>
                            <td>@if($invoice!=null)
                                {{$invoice->ket_lunas == 'Finish' ? 'PAID' : 'UNPAID'}}
                                @else
                                ''
                                @endif
                                </td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>