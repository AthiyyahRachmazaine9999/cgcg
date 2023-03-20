<div class="tab-pane fade show" id="krm">
    <table class="table table-bordered table-striped table-hover m_outbound">
        <thead>
            <tr>
                <th>No. DO</th>
                <th>Alamat</th>
                <th>Tanggal Kirim</th>
                <th colspan="">Action</th>
            </tr>
        </thead>
        @php
        $i = 1;
        foreach ($head as $vals){
        if($vals->no_wh_out!=null){
        $exp = explode('x',$vals->kirim_addr);
        $count_addr = count($exp);
        $address = $count_addr==2 ?
        getCustomer($vals->kirim_addr)->company.' - '.getCustomer($vals->kirim_addr)->address :
        warehouse_addr($vals->kirim_addr)->name.' - '.warehouse_addr($vals->kirim_addr)->address;
        $ex = explode('/',$vals->no_wh_out);
        @endphp
        <tbody>
            <tr>
                <td>
                    <a href="#" data-toggle='modal' data-target='#m_modal' onclick="detailDO_click(this)"
                        data-no_wh_out="{{$vals->no_wh_out}}" data-no="{{empty($ex[1]) ? null : $ex[1]}}" class="text-primary text-left">
                        @if(empty($ex[1]))
                        WH/OUT/{{Carbon\Carbon::now()->format('y')}}/{{sprintf("%06d", $vals->id_wh_out)}}
                        @else
                        WH/OUT/{{Carbon\Carbon::now()->format('y')}}/{{sprintf("%06d", $vals->id_wh_out)}}/{{$ex[1]}}
                        @endif

                    </a>
                </td>
                <td class="text-center">{{$address}}</td>
                <td class="text-center">{{Carbon\Carbon::parse($vals->tanggal_kirim)->format('d-m-Y')}}
                </td>
                <td colspan="2">
                    <input name="qty_kirim_asal[]" type="hidden" id="" value="{{$vals->qty_kirim}}" class="form-control"
                        placeholder="qty_kirim" readonly>
                    <button type="button" onclick="DO_cetak(this)" data-kirim_addr="{{$vals->kirim_addr}}"
                        data-no_wh_out="{{$vals->no_wh_out}}" data-id_wh_out="{{$vals->id_wh_out}}" data-toggle="modal" data-target="#m_modal" class="btn btn-danger btn-icon"><i class="icon-printer"></i></button></button>
                </td>
            </tr>
        </tbody>
        @php } } @endphp
    </table>
    <div class="card-body">
        @if ($main->status == 'partial')

        @php $kurang = count($purchase) - count($product); @endphp
        <div class="alert alert-danger alert-styled-left alert-dismissible">
            <span class="font-weight-semibold">Maaf</span> Ada {{$kurang}} Jenis Barang yang belum diterima, hanya bisa
            kirim yang sudah diterima
        </div>
        @endif
    </div>
</div>