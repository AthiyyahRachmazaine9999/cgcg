<div class="tab-pane fade show" id="track">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-nowrap">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Resi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-active table-border-double">
                        <td colspan="3" class="font-weight-bold">Default Pengiriman</td>
                    </tr>
                    <tr>
                        <td>
                            <span class="font-weight-bold">{{$cust->company}}</span>
                            <br>{{$cust->address}} {{$cust->phone}}
                        </td>
                        <td>
                            @php
                            $check = getWarehouseResi('id_address',$cust->id);
                            $det_main = $check == null ? '': $check;
                            $resi = $check == null ? '': $det_main->resi;
                            if($det_main==null) {
                            @endphp
                            <select class="form-control id_kurir" name="id_kurir" id="kurir_cust"></select> @php
                            }else{
                            @endphp
                            <select class="form-control id_kurir" name="id_kurir" id="kurir_cust">
                                <option value="{{$det_main->id_forwarder}}">{!!getForwarder($det_main->id_forwarder)->company!!}</option>
                            </select>
                            @php } @endphp
                            <br>
                            <div class="input-group">
                                {!! Form::text('resi',$resi,['id'=>'resi_cust','class'=>'form-control','placeholder'=>'Masukan Nomer Resi Pengiriman','require']) !!}
                                <span class="input-group-append">
                                    <button class="btn btn-light" type="button" onclick="saveresi(this)" data-idwo="{{$main->id}}" data-type="main" data-alamat="{{$cust->id}}">save</button>
                                </span>
                            </div>
                        </td>
                        <td class="text-center">

                            <div class="list-icons">
                                <div class="list-icons-item dropdown">
                                    <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#" class="dropdown-item" onclick="Tracking(this)" data-id="{{$main->id}}" data-alamat="{{$cust->id}}" data-type="tambah"><i class="icon-pin-alt"></i> Track Resi</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item" onclick="finishkirim(this)" data-idwo="{{$main->id}}" data-type="main" data-alamat="{{$cust->id}}x" data-toggle="modal" data-target="#m_modal"><i class="icon-check text-success"></i> Selesai</a>
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>

                    <tr class="table-active table-border-double">
                        <td colspan="3" class="font-weight-bold">Alamat Pengiriman Lain / Baru</td>
                    </tr>
                    @php
                    if($main->pengiriman=='multiple'){
                    foreach($alamat as $alt){
                    @endphp
                    <tr>
                        <td>
                            <span class="font-weight-bold">{{$alt->name}}</span>
                            <br>{{$alt->address}}
                        </td>
                        <td>
                            @php
                            $check_alt = getWarehouseResi('id_address',$alt->id);
                            $det_alt   = $check_alt == null ? '': $check_alt;
                            $resi_alt  = $check_alt == null ? '': $det_alt->resi;
                            if($det_alt==null) {
                            @endphp
                            <select class="form-control id_kurir" name="id_kurir" id="kurir_{{$alt->id}}"></select>
                            @php
                            }else{
                            @endphp
                            <select class="form-control id_kurir" name="id_kurir" id="kurir_{{$alt->id}}">
                                <option value="{{$det_alt->id_forwarder}}">{!!getForwarder($det_alt->id_forwarder)->company!!}</option>
                            </select>
                            <br>
                            @php } @endphp
                            <div class="input-group">
                                <input type="text" id="resi_{{$alt->id}}" value="{{$resi_alt}}" class="form-control form-item" placeholder='Masukan Nomer Resi Pengiriman'>
                                <span class="input-group-append">
                                    <button class="btn btn-light" onclick="saveresi(this)" data-type="other" data-idwo="{{$main->id}}" data-alamat="{{$alt->id}}" type="button">save</button>
                                </span>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="list-icons-item dropdown">
                                    <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#" class="dropdown-item" onclick="Tracking(this)" data-id="{{$main->id}}" data-alamat="{{$alt->id}}" data-type="tambah"><i class="icon-pin-alt"></i> Track Resi</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item" onclick="finishkirim(this)" data-type="other" data-idwo="{{$main->id}}" data-alamat="{{$alt->id}}" data-toggle="modal" data-target="#m_modal"><i class="icon-check text-success"></i> Selesai</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @php }} @endphp
                </tbody>
            </table>
        </div>
    </div>
</div>