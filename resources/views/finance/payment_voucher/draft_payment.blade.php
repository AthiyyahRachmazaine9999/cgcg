<br>
<div class="table-responsive">
    <table class="table table-lg">
        <thead>
            <tr>
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
            @php $i=1; foreach($pay_pays as $pay){ @endphp
            <tr>
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
                <td>{{\Carbon\Carbon::parse($pay->date_payment)->format('d-F-Y')}}</td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="list-icons-item dropdown">
                            <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i
                                    class="icon-menu7"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item" onclick="EditPayments(this)" data-id="{{$pay->id}}"
                                    data-id_pay="{{$pay->id_pay}}" data-toggle="modal" data-target="#m_modal"
                                    data-type="tambah"><i class="fas fa-pencil-alt text-primary"></i>
                                    Edit</a>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item" onclick="HapusPayment(this)" data-id="{{$pay->id}}"
                                    data-type="other" data-id_pay="{{$pay->id_pay}}"><i
                                        class="fas fa-trash text-warning"></i>
                                    Hapus</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @php } @endphp
        </tbody>
    </table>
    @if(checkPayVoucher($pay_dtl->id_pay)!='lunas')
    <button onClick="Done_payment(this)" data-id_dtl="{{$pay_dtl->id}}" data-id="{{$pay_dtl->id_pay}}"
        data-usr="finance" data-toggle="modal" data-target="#m_modal"
        class="btn bg-primary-400 btn-icon rounded-round legitRipple"><i class="fas fa-plus"></i></button>
    @endif
</div>