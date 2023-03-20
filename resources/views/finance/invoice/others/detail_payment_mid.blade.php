<div class="tab-pane fade show" id="krm">
    <div class="form-group row">
        <div class="col-lg-8 ml-10 pl-10">
            <label class="font-weight-bold">Tipe Pembayaran* </label>
            <div class="form-control">{{$invoice_id->type_payment}}</div>
        </div>
    </div>
    <br>
    <div class="form-group row">
        <div class="col-lg-8 ml-10 pl-10">
            <label class="font-weight-bold">Bentuk Pembayaran* </label>
            <div class="form-control">{{$pay->method_payment}}
                @if($pay->method_payment!="cash")
                {{$pay->bank_name.' - '.$pay->bank_no}}
                @endif
            </div>
        </div>
    </div>

    <table class="table table-bordered table-striped table-hover m_outbound" style="width: 100%;">
        <thead>
            <tr>
                <th>Tanggal Pembayaran</th>
                <th>Nilai Pembayaran</th>
                <th>File</th>
            </tr>
        </thead>
        @php
        $i = 1;
        foreach ($detail as $vals){
        @endphp
        <tbody>
            <tr class="text-center">
                <td>
                    <input type="text" id="dates_pay"
                        value="{{\Carbon\Carbon::parse($vals->date_payment)->format('d F Y')}}" class="form-control"
                        readonly>
                </td>
                <td> <input type="text" value="{{number_format($vals->payment_amount)}}" class="form-control" readonly>
                </td>
                <td class="text-left">
                    @if($vals->file_invoice!=null)
                    <br>
                    <a href="{{ asset($vals->file_invoice)}}" target="_blank"
                        class="btn btn-outline-primary text-primary">SHOW</a>
                    @else
                    <button target="_blank" class="btn btn-outline-primary text-primary" disabled>SHOW</a>
                        @endif
                </td>
            </tr>
        </tbody>
        @php } @endphp
    </table>
    <br>
</div>