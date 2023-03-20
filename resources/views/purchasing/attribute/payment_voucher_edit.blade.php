            <form action="{{ route('payment.update', $pay_dtl->id_pay )}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                {!! Form::hidden('id_pay',$pay_dtl->id,['id'=>'id_pay','class'=>'form-control']) !!}
                <div class="card-body">
                    <input type="hidden" name="id_quo" value="{{$pay_dtl->id_quo}}" id="Quo_Id" class="form-control"
                        placeholder="Masukkan SO" readonly>
                    <input type="hidden" name="vendor_id" value="{{$pay_dtl->id_vendor}}" id="vendor_id"
                        class="form-control" placeholder="Masukkan SO" readonly>
                    <input type="hidden" name="tujuan" value="{{$pay_dtl->tujuan}}" class="form-control"
                        placeholder="Masukkan Tujuan / Keperluan">

                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>No. Payment Voucher</label>
                        <div class="col-lg-7">
                            <input type="text" name="no_payment" value="{{$pay_dtl->no_payment}}"
                                class="no_pay form-control" placeholder="Masukkan INV/KWT" readonly>
                        </div>
                    </div>
                    <div class="form-group row row_invoices edit_inv">
                        <label class='col-lg-3 col-form-label'>No. Invoice</label>
                        <div class="col-lg-7">
                            <input type="text" name="no_invoice" id="invoice_no" value="{{$pay_dtl->no_invoice}}"
                                class="form-control no_inv num_edit" placeholder="Masukkan No. Invoice">
                        </div>
                    </div>
                    <div class="form-group row row_p_invoices">
                        <label class='col-lg-3 col-form-label'>No. Performa Invoice</label>
                        <div class="col-lg-7">
                            <input type="text" name="no_peforma_inv" id="p_invoice"
                                value="{{$pay_dtl->performa_invoice}}" class="form-control no_inv"
                                placeholder="Masukkan No. Invoice">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>No. Sales Order</label>
                        <div class="col-lg-7">
                            <input type="text" name="no_so" id="sales_order" value="{{$pay_dtl->no_so}}"
                                class="form-control s_order" placeholder="Masukkan No. Sales Order">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>No. Purchase Order</label>
                        <div class="col-lg-7">
                            {!! Form::text('tujuan_po',$pay_dtl->tujuan,['id' => 'purchase_order', 'class' =>
                            'form-control form-control']) !!}
                            {!! Form::hidden('no_po',$pay_dtl->no_po,['id' => 'purchase_order', 'class' =>
                            'form-control form-control']) !!}
                        </div>
                    </div>
                    @if($pay_dtl->type_payment!="cbd")
                    <div class="form-group row row_do">
                        <label class='col-lg-3 col-form-label'>No. Delivery Order</label>
                        <div class="col-lg-7">
                            {!! Form::text('no_do', $pay_dtl->no_do,['id' => 'no_do', 'class' =>
                            'form-control form-control ndo', 'placeholder' => 'No. DO']) !!}
                        </div>
                    </div>
                    @else
                    <div class="form-group row row_do1">
                        <label class='col-lg-3 col-form-label'>No. Delivery Order</label>
                        <div class="col-lg-7">
                            {!! Form::text('no_do', $pay_dtl->no_do,['id' => 'no_do', 'class' =>
                            'form-control form-control ndo', 'placeholder' => 'Masukkan No DO']) !!}
                        </div>
                    </div>
                    @endif
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>No. Faktur</label>
                        <div class="col-lg-7">
                            {!! Form::text('no_faktur', $pay_dtl->no_efaktur,['id' => 'no_faktur', 'class' =>
                            'form-control form-control', 'placeholder' => 'Masukkan No. Faktur']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Metode Pembayaran</label>
                        <div class="col-lg-7">
                            {!! Form::select('payment', array('top' => 'TOP', 'cbd' => 'CBD', 'net' => 'Nett',
                            'Cek Mundur' => 'Cek Mundur', 'Giro Mundur' => 'Giro Mundur'),
                            $pay_dtl->type_payment ,
                            ['id' =>'payment','class' => 'payments form-control form-control-select2 ed_payments']) !!}
                        </div>
                    </div>
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Tanggal Pembayaran</label>
                            <div class="col-lg-3">
                                <input type="text" class="date form-control" id="dari_tgl"
                                    value="{{$pay_dtl->from_date}}" name="from_date" class="form-control dop"
                                    placeholder="Masukkan Tanggal">
                            </div>
                            <label class='col-lg-1 col-form-label exc_cbd'>s/d</label>
                            <div class="col-lg-3">
                                <input type="text" class="date form-control exc_cbd" value="{{$pay_dtl->to_date}}"
                                    id="sampai_tgl" name="to_date" placeholder="Masukkan Tanggal">
                            </div>
                        </div>
                    <div class="exc_cbd">
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Lama Pembayaran</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="top" value="{{$pay_dtl->top_date}}"
                                    name="top_date" class="form-control pay_top" placeholder="Masukkan TOP" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Nominal Pembayaran</label>
                        <div class="col-lg-7">
                            <input type="number" step="any" class="form-control" id="nominal"
                                value="{{$pay_dtl->nominal}}" name="total" class="nom form-control"
                                placeholder="Masukkan Nominal">
                        </div>
                    </div>

                    <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
                        <h6 class="card-title">Upload Document</h6>
                    </div>
                    <br><br>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Document TOP</label>
                        @php if($pay_dtl->doc_alltop==null) { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_alltop" value="{{$pay_dtl->doc_alltop}}"
                                class="file-input form-control">
                        </div>
                        @php } else { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_alltop" value="{{$pay_dtl->doc_alltop}}"
                                class="file-input form-control">
                        </div>
                        <a href="{{ asset($pay_dtl->doc_alltop) }}" target="_blank" id="btn"><i class="fa fa-check"></i>
                            Sudah Terisi. Check Disini</a>
                        @php } @endphp
                    </div>

                                        <div class="doc_cbds">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Performa Invoice</label>
                            @php if($pay_dtl->doc_inv_performa==null) { @endphp
                            <div class="col-lg-6">
                                <input type="file" name="doc_inv_performa" value="{{$pay_dtl->doc_inv_performa}}"
                                    class="file-input form-control">
                            </div>
                            @php } else { @endphp
                            <div class="col-lg-6">
                                <input type="file" name="doc_inv_performa" value="{{$pay_dtl->doc_inv_performa}}"
                                    class="file-input form-control">
                            </div>
                            <a href="{{ asset($pay_dtl->doc_inv_performa) }}" target="_blank" id="btn"><i
                                    class="fa fa-check"></i>
                                Sudah Terisi. Check Disini</a>
                            @php } @endphp
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Dokumen Pemesanan</label>
                            @php if($pay_dtl->doc_lainnya==null) { @endphp
                            <div class="col-lg-6">
                                <input type="file" name="doc_lainnya" value="{{$pay_dtl->doc_lainnya}}"
                                    class="file-input form-control">
                            </div>
                            @php } else { @endphp
                            <div class="col-lg-6">
                                <input type="file" name="doc_lainnya" value="{{$pay_dtl->doc_lainnya}}"
                                    class="file-input form-control">
                            </div>
                            <a href="{{ asset($pay_dtl->doc_lainnya) }}" target="_blank" id="btn"><i
                                    class="fa fa-check"></i>
                                Sudah Terisi. Check Disini</a>
                            @php } @endphp
                        </div>
                    </div>

                    <!-- @if($pay_dtl->type_payment!="cbd")
                    <div class="form-group row row_do">
                        <label class="col-lg-3 col-form-label">Delivery Order</label>
                        @php if($pay_dtl->doc_do==null) { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_do" value="{{$pay_dtl->doc_do}}"
                                class="file-input form-control">
                        </div>
                        @php } else { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_do" value="{{$pay_dtl->doc_do}}"
                                class="file-input form-control">
                        </div>
                        <a href="{{ asset($pay_dtl->doc_do) }}" id="btn"><i class="fa fa-check"></i>
                            Sudah Terisi. Check Disini</a>
                        @php } @endphp
                    </div>
                    @else
                    <div class="form-group row row_do1">
                        <label class="col-lg-3 col-form-label">Delivery Order</label>
                        @php if($pay_dtl->doc_do==null) { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_do" value="{{$pay_dtl->doc_do}}"
                                class="file-input form-control">
                        </div>
                        @php } else { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_do" value="{{$pay_dtl->doc_do}}"
                                class="file-input form-control">
                        </div>
                        <a href="{{ asset($pay_dtl->doc_do) }}" id="btn"><i class="fa fa-check"></i>
                            Sudah Terisi. Check Disini</a>
                        @php } @endphp
                    </div>
                    @endif
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">E-Faktur</label>
                        @php if($pay_dtl->doc_efaktur==null) { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_efaktur" value="{{$pay_dtl->doc_efaktur}}"
                                class="file-input form-control">
                        </div>
                        @php } else { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_efaktur" value="{{$pay_dtl->doc_efaktur}}"
                                class="file-input form-control">
                        </div>
                        <a href="{{ asset($pay_dtl->doc_efaktur) }}" id="btn"><i class="fa fa-check"></i>
                            Sudah Terisi. Check Disini</a>
                        @php } @endphp
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Kwitansi</label>
                        @php if($pay_dtl->doc_kwitansi==null) { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_kwitansi" value="{{$pay_dtl->doc_kwitansi}}"
                                class="file-input form-control">
                        </div>
                        @php } else { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_kwitansi" value="{{$pay_dtl->doc_kwitansi}}"
                                class="file-input form-control">
                        </div>
                        <a href="{{ asset($pay_dtl->doc_kwitansi) }}" id="btn"><i class="fa fa-check"></i>
                            Sudah Terisi. Check Disini</a>
                        @php } @endphp
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Invoice</label>
                        @php if($pay_dtl->doc_invoice==null) { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_invoice" value="{{$pay_dtl->doc_invoice}}"
                                class="file-input form-control">
                        </div>
                        @php } else { @endphp
                        <div class="col-lg-6">
                            <input type="file" name="doc_invoice" value="{{$pay_dtl->doc_invoice}}"
                                class="file-input form-control">
                        </div>
                        <a href="{{ asset($pay_dtl->doc_invoice) }}" id="btn"><i class="fa fa-check"></i>
                            Sudah Terisi. Check Disini</a>
                        @php } @endphp
                    </div> -->
                    <br><br>

                    <div class="text-right">
                        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method'
                        =>'purchasing/order/'.$pay_dtl->no_po, 'type' => 'button','onclick'=>'cancel(this)']) !!}
                        <button type="submit" class="btn btn-primary">Update<i class="far fa-save ml-2"></i></button>
                    </div>
                </div>
            </form>