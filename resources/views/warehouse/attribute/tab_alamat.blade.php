<div class="tab-pane fade show" id="fwd">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-nowrap">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Alamat</th>
<!--                         <th>Action</th>
 -->                    </tr>
                </thead>
                <tbody>
                    <tr class="table-active table-border-double">
                        <td colspan="3" class="font-weight-bold">Default Pengiriman</td>
                        <td class="text-right">
                            <span class="progress-meter" id="today-progress" data-progress="30"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>{{$cust->company}}</td>
                        <td>{{$cust->address}} {{$cust->phone}}</td>
<!--                         <td class="text-center">
                            <button type="button" class="btn btn-danger btn-icon" onclick="CetakDO(this)" data-id="{{$main->id}}"  data-alamat="{{$cust->id}}" data-type="utama"><i class="icon-printer"></i></button>
                        </td>
 -->                    </tr>

                    <tr class="table-active table-border-double">
                        <td colspan="3" class="font-weight-bold">Alamat Pengiriman Lain / Baru</td>
                        <td class="text-right">
                            <span class="progress-meter" id="today-progress" data-progress="30"></span>
                        </td>
                    </tr>
                    @php
                    if($main->pengiriman=='multiple'){
                    foreach($alamat as $alt){
                    @endphp
                    <tr>
                        <td>{{$alt->name}}</td>
                        <td>{{$alt->address}}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="list-icons-item dropdown">
                                    <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#" class="dropdown-item" onclick="ChangeAlamat(this)" data-toggle="modal" data-detail="{{$alt->id}}" data-target="#m_modal" data-id="{{$main->id}}" data-type="update" data-target="#modal"><i class="icon-pin-alt"></i> Edit</a>
                                        <!-- <a href="#" class="dropdown-item" onclick="CetakDO(this)" data-id="{{$main->id}}" data-alamat="{{$alt->id}}" data-type="tambah" ><i class="icon-printer"></i> Cetak DO</a> -->
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item" onclick="DeleteAlamat(this)" data-name="{{$alt->name}}" data-id="{{$main->id}}" data-detail="{{$alt->id}}" data-target="#modal"><i class="icon-trash text-danger"></i> Delete</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @php }} @endphp
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-4">
                    <ul class="list list-unstyled mb-0">
                    <button type="button" onclick="ChangeAlamat(this)" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id_quo}}" data-detail="" data-type="new" data-target="#modal" class="btn bg-primary-400 btn-labeled btn-labeled-left mt-3"><b><i class="icon-plus-circle2"></i></b> Tambah</button>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>