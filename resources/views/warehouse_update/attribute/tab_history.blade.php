<div class="tab-pane fade show" id="krm">
    <table class="table table-bordered table-striped table-hover m_outbound" style="width: 100%;">
        <thead>
            <tr>
                <th>No. DO</th>
                <th>Alamat</th>
                <th>Tanggal Kirim</th>
                <th>Created By</th>
                <th>Action</th>
            </tr>
        </thead>
        @php
        $i = 1;
        foreach ($head as $vals){
        $add = $vals->type_alamat == 'utama' ? getCustomer($vals->id_alamat)->company.' - '.
        getCustomer($vals->id_alamat)->address :
        WarehouseAddress($vals->id_alamat)->name.' - '.WarehouseAddress($vals->id_alamat)->address;
        @endphp
        <tbody>
            <tr>
                <td style="width: 20%;">
                    <a href="#" data-toggle='modal' data-target='#m_modal' onclick="detailDO_click(this)" data-no_wh_out="{{$vals->no_do}}" data-id_split="{{$vals->id_split}}" data-no="{{$vals->id_outbound}}" class="text-primary text-left">
                        {{$vals->no_do}}
                    </a>
                </td>
                <td style="width: 30%;">{{$add}}</td>
                <td style="width: 20%;">{{Carbon\Carbon::parse($vals->tgl_kirim)->format('d F Y')}}</td>
                <td style="width: 20%;">{{user_name($vals->created_by)}}</td>
                <td class="text-center" style="width: 20%;">
                    <input name="qty_kirim_asal[]" type="hidden" id="" value="{{$vals->qty_kirim}}" class="form-control" placeholder="qty_kirim" readonly>
                    <div class="list-icons">
                        <div class="list-icons-item dropdown">
                            <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a onclick="DO_cetak(this)" data-kirim_addr="{{$vals->id_alamat}}" data-no_wh_out="{{$vals->no_do}}" class="dropdown-item" data-id_wh_out="{{$vals->id_outbound}}" data-toggle="modal" data-target="#m_modal"><i class="icon-printer"></i>Cetak DO</a>
                                <a onclick="DO_Balikan(this)" data-kirim_addr="{{$vals->id_alamat}}" data-no_wh_out="{{$vals->no_do}}" class="dropdown-item" data-id_wh_out="{{$vals->id_outbound}}" data-toggle="modal" data-target="#m_modal"><i class="icon-file-upload2"></i>Upload DO Balikan</a>
                                <div class="dropdown-divider"></div>
                                <a type="button" onclick="noDO_delete(this)" data-kirim_addr="{{$vals->id_alamat}}" data-no_wh_out="{{$vals->no_do}}" class="text-danger dropdown-item" data-id_wh_out="{{$vals->id_outbound}}"><i class="text-danger icon-trash"></i>Hapus
                                    DO</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
        @php } @endphp
    </table>
    <br>
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