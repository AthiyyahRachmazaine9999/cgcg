        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h4 class="card-title">Payment Voucher</h4>
            <div class="header-elements">
                <!-- <a href="{{route('payment.download', $pay->id)}}" class="btn btn-danger btn-sm ml-3"
                    onclick="PrintPayment(this)" data-id_pay="{{$pay_dtl->id_pay}}"><i
                        class="icon-printer mr-2"></i>Print</a><br> -->
            </div>
        </div>

        <div class="card-body">
            <div class="form-group row">
                {!! Form::label('no_payment', 'No. Payment Voucher', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay_dtl->no_payment=="null" ? '-' : $pay_dtl->no_payment}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('invoice_kwitansi', 'No. Invoice', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay_dtl->no_invoice==null ? "-" : $pay_dtl->no_invoice}}</div>
                </div>
            </div>
            @if($pay_dtl->type_payment=="cbd")
            <div class="form-group row">
                {!! Form::label('invoice_kwitansi', 'No. Performa Invoice', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay_dtl->performa_invoice}}</div>
                </div>
            </div>
            @endif
            <div class="form-group row">
                {!! Form::label('invoice_kwitansi', 'No. Sales Order', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay_dtl->no_so==null ? "-" : $pay_dtl->no_so}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('id_vendor', 'Vendor', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{getVendor($pay->id_vendor)->vendor_name}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('invoice_kwitansi', 'No. Purchase Order', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay_dtl->tujuan}}</div>
                </div>
            </div>
            @if($pay_dtl->type_payment!="cbd")
            <div class="form-group row">
                {!! Form::label('no_do', 'No. Delivery Order', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay_dtl->no_do}}</div>
                </div>
            </div>
            @endif
            <div class="form-group row">
                {!! Form::label('no_faktur', 'No. Faktur', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay_dtl->no_efaktur}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('tujuan', 'Metode Pembayaran', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$top}}</div>
                </div>
            </div>
            @if($pay_dtl->type_payment!="cbd" )
            <div class="form-group row">
                {!! Form::label('', 'Tanggal Pembayaran', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-3">
                    <div class="form-control">{{\Carbon\Carbon::parse($pay_dtl->from_date)->format('d-m-Y')}}</div>
                </div>
                <label class='col-lg-1 col-form-label'>s/d</label>
                <div class="col-lg-3">
                    <div class="form-control">{{\Carbon\Carbon::parse($pay_dtl->to_date)->format('d-m-Y')}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('top', 'Lama Pembayaran', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$pay->top_date}}</div>
                </div>
            </div>
            @endif
            @if($pay_dtl->type_payment=="cbd" && $pay_dtl->from_date!=null)
            <div class="form-group row">
                {!! Form::label('top', 'Tanggal Pembayaran', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{\Carbon\Carbon::parse($pay_dtl->from_date)->format('d-m-Y')}}</div>
                </div>
            </div>
            @endif
            <div class="form-group row">
                {!! Form::label('nominal', 'Nominal Pembayaran', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{number_format($pay_dtl->nominal,2)}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('terbilang', 'Terbilang', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control" style="height:50px;">{{$pay_dtl->terbilang}}</div>
                </div>
            </div>
            <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
                <h6 class="card-title">Show Document</h6>
            </div>
            <br><br>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Document TOP</label>
                <div class="fallback">
                    <div class="custom-file">
                        @php if($pay_dtl->doc_alltop!=null){ @endphp
                        <a href="{{ asset($pay_dtl->doc_alltop) }}" target="_blank" class="btn btn-primary">SHOW</a>
                        @php }else{ @endphp
                        <button class="btn btn-primary" data-type="doc_alltop" data-id="{{$pay_dtl->id}}"
                            data-doc="{{$pay_dtl->doc_alltop}}" disabled>SHOW</button>
                        @php } @endphp
                    </div>
                </div>
            </div>
            @if($top=="CBD")
            <div class="doc_cbds">
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Performa Invoice</label>
                    <div class="fallback">
                        <div class="custom-file">
                            @php if($pay_dtl->doc_inv_performa!=null){ @endphp
                            <a href="{{ asset($pay_dtl->doc_inv_performa) }}" target="_blank"
                                class="btn btn-primary">SHOW</a>
                            @php }else{ @endphp
                            <button class="btn btn-primary" data-type="doc_inv_performa" data-id="{{$pay_dtl->id}}"
                                data-doc="{{$pay_dtl->doc_inv_performa}}" disabled>SHOW</button>
                            @php } @endphp
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Dokumen Pemesanan</label>
                    <div class="fallback">
                        <div class="custom-file">
                            @php if($pay_dtl->doc_lainnya!=null){ @endphp
                            <a href="{{ asset($pay_dtl->doc_lainnya) }}" target="_blank"
                                class="btn btn-primary">SHOW</a>
                            @php }else{ @endphp
                            <button class="btn btn-primary" data-type="doc_lainnya" data-id="{{$pay_dtl->id}}"
                                data-doc="{{$pay_dtl->doc_lainnya}}" disabled>SHOW</button>
                            @php } @endphp
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!-- @if($pay_dtl->type_payment=="top")
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Delivery Order</label>
                <div class="fallback">
                    <div class="custom-file">
                        @php if($pay_dtl->doc_do!=null){ @endphp
                        <a href="{{ asset($pay_dtl->doc_do) }}" class="btn btn-primary">SHOW</a>
                        @php }else{ @endphp
                        <button class="btn btn-primary" data-type="DO" data-id="{{$pay_dtl->id}}"
                            data-doc="{{$pay_dtl->doc_do}}" disabled>SHOW</button>
                        @php } @endphp
                    </div>
                </div>
            </div>
            @endif
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">E-Faktur</label>
                <div class="fallback">
                    <div class="custom-file">
                        @php if($pay_dtl->doc_efaktur!=null){ @endphp
                        <a href="{{ asset($pay_dtl->doc_efaktur) }}" class="btn btn-primary">SHOW</a>
                        @php }else{ @endphp
                        <button class="btn btn-primary" data-type="efaktur" data-id="{{$pay_dtl->id}}"
                            data-doc="{{$pay_dtl->doc_efaktur}}" disabled>SHOW</button>
                        @php } @endphp
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Kwitansi</label>
                <div class="fallback">
                    <div class="custom-file">
                        @php if($pay_dtl->doc_kwitansi!=null){ @endphp
                        <a href="{{ asset($pay_dtl->doc_kwitansi) }}" class="btn btn-primary">SHOW</a>
                        @php }else{ @endphp
                        <button class="btn btn-primary" data-type="kwitansi" data-id="{{$pay_dtl->id}}"
                            data-doc="{{$pay_dtl->doc_kwitansi}}" disabled>SHOW</button>
                        @php } @endphp
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Invoice</label>
                <div class="fallback">
                    <div class="custom-file">
                        @php if($pay_dtl->doc_invoice!=null){ @endphp
                        <a href="{{ asset($pay_dtl->doc_invoice) }}" class="btn btn-primary">SHOW</a>
                        @php }else{ @endphp
                        <button class="btn btn-primary" data-type="invoice" data-id="{{$pay_dtl->id}}"
                            data-doc="{{$pay_dtl->doc_invoice}}" disabled>SHOW</button>
                        @php } @endphp
                    </div>
                </div>
            </div> -->

            @if($pay_dtl->app_finance!=null)
            <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
                <h6 class="card-title">Known By</h6>
            </div>
            <br><br>

            @if($pay_dtl->app_finance!=null)
            <div class="form-group row">
                {!! Form::label('invoice_kwitansi', getUserEmp($pay_dtl->app_finance)->position, ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{user_name($pay_dtl->app_finance)}}</div>
                </div>
            </div>
            @endif
            @if($pay_dtl->app_hrd!=null)
            <div class="form-group row">
                {!! Form::label('invoice_kwitansi', getUserEmp($pay_dtl->app_hrd)->position, ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{user_name($pay_dtl->app_hrd)}}</div>
                </div>
            </div>
            @endif
            @if($pay_dtl->app_mng!=null)
            <div class="form-group row">
                {!! Form::label('invoice_kwitansi', getUserEmp($pay_dtl->app_mng)->position, ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{user_name($pay_dtl->app_mng)}}</div>
                </div>
            </div>
            @endif
            @endif
            <br><br>
            <div class="text-right">
                {!! Form::button('Back', ['class' => 'btn btn-light btn-cancel', 'data-method'
                =>'purchasing/order/'.$pay_dtl->no_po, 'type' => 'button','onclick'=>'cancel(this)']) !!}
            </div>
        </div>
        @include('sales.quotation.attribute.modal')
        <script src="{{ asset('ctrl/purchasing/mail-po.js') }}" type="text/javascript"></script>