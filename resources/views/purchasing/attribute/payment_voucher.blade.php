                <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_quo" value="{{$main->id_quo}}" id="Quo_Id" class="form-control"
                        placeholder="Masukkan SO" readonly>
                    <input type="hidden" name="vendor_id" value="{{$main->id_vendor}}" id="vendor_id"
                        class="form-control" readonly>
                    <input type="hidden" name="tujuan" class="form-control" value="Payment {{$main->po_number}}"
                        placeholder="Masukkan Tujuan / Keperluan" readonly>


                    <div class="form-group row row_invoices">
                        <label class='col-lg-3 col-form-label'>No. Invoice</label>
                        <div class="col-lg-7">
                            <input type="text" name="no_invoice" id="invoice_no" class="form-control num_edit"
                                placeholder="Masukkan No. Invoice">
                        </div>
                    </div>
                    <div class="form-group row row_p_invoices">
                        <label class='col-lg-3 col-form-label'>No. Performa Invoice</label>
                        <div class="col-lg-7">
                            <input type="text" name="no_peforma_inv" id="p_inv" class="form-control"
                                placeholder="Masukkan No. Performa Invoice">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>No. Sales Order</label>
                        <div class="col-lg-7">
                            <input type="text" name="no_so" id="sales_order" value='{{$id_quo}}' class="form-control"
                                placeholder="Masukkan No. Sales Order">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>No. Purchase Order</label>
                        <div class="col-lg-7">
                            {!! Form::text('tujuan_po', $main->po_number,['id' => 'purchase_order', 'class' =>
                            'form-control form-control', 'placeholder' => 'Masukkan No. PO']) !!}
                            {!! Form::hidden('no_po', $main->po_number,['id' => 'purchase_order', 'class' =>
                            'form-control form-control', 'placeholder' => 'Masukkan No. PO']) !!}
                        </div>
                    </div>
                    <div class="form-group row row_do">
                        <label class='col-lg-3 col-form-label'>No. Delivery Order</label>
                        <div class="col-lg-7">
                            {!! Form::text('no_do', '', ['id' => 'no_do',
                            'class' => 'form-control form-control', 'placeholder' => 'Masukkan No. DO']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>No. Faktur</label>
                        <div class="col-lg-7">
                            {!! Form::text('no_faktur', '',['id' => 'no_faktur', 'class' =>
                            'form-control form-control', 'placeholder' => 'Masukkan No. Faktur']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Metode Pembayaran</label>
                        <div class="col-lg-7">
                            {!! Form::select('payment', array('top' => 'TOP', 'cbd' => 'CBD', 'net' => 'Nett',
                            'Cek Mundur' => 'Cek Mundur', 'Giro Mundur' => 'Giro Mundur'),
                            $product1==null ? null : $product1->det_quo_type_beli, ['id' => 'payments',
                            'class' => 'form-control form-control-select2 payments m_payments']) !!}
                        </div>
                    </div>
                        <div class="form-group row tgl_payments">
                            <label class='col-lg-3 col-form-label'>Tanggal Pembayaran</label>
                            <div class="col-lg-3">
                                <input type="text" class="date form-control" id="dari_tgl" name="from_date"
                                    class="form-control" placeholder="Masukkan Tanggal">
                            </div>
                            <label class='col-lg-1 col-form-label exc_cbd'>s/d</label>
                            <div class="col-lg-3">
                                <input type="text" class="date form-control exc_cbd" id="sampai_tgl" name="to_date"
                                    placeholder="Masukkan Tanggal">
                            </div>
                        </div>
                    <div class="exc_cbd">
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Lama Pembayaran</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="top" name="top_date" class="form-control"
                                    placeholder="TOP" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Nominal Pambayaran</label>
                        <div class="col-lg-7">
                            <input type="number" id="nominal" value="{{round($main->price)}}" class="form-control"
                                name="total" class="form-control" placeholder="Masukkan Nominal">
                        </div>
                    </div>

                    <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
                        <h6 class="card-title">Upload Document</h6>
                    </div>
                    <br><br>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Dokumen TOP</label>
                        <div class="col-lg-7">
                            <input type="file" id="doc_alltop" name="doc_alltop" class="file-input form-control">
                        </div>
                    </div>
                    <div class="doc_cbds">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Performa Invoice</label>
                            <div class="col-lg-7">
                                <input type="file" id="doc_performa" name="doc_inv_performa"
                                    class="file-input form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Dokumen Pemesanan</label>
                            <div class="col-lg-7">
                                <input type="file" id="doc_lainnya" name="doc_lainnya" class="file-input form-control">
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <!-- Document -->
                    <div class="text-right">
                        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method'
                        =>'purchasing/order/'.$main->po_number, 'type' => 'button','onclick'=>'cancel(this)']) !!}
                        <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i></button>
                    </div>
                    </div>
                </form>
                @section('script')
                <script src="{{ asset('ctrl/purchasing/po-form.js') }}" type="text/javascript"></script>
                <script src="{{ asset('ctrl/purchasing/mail-po.js') }}" type="text/javascript"></script>
                @endsection