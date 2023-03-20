<div class="table-responsive">
    {!! Form::hidden('id_quo',"$main->id", ['id'=>'id_quo','class' => 'form-control','placeholder' => 'Masukkan No SP'])
    !!}
    {!! Form::hidden('id_pic',"$main->id", ['id'=>'id_quo','class' => 'form-control','placeholder' => 'Masukkan No SP'])
    !!}
    <table class="table table-lg" id="table_cust">
        <tbody>
            <tr class="table-active">
                <th colspan="3" class="text-danger">Utama</th>
            </tr>
            <tr>
                <td class="text-left font-weight-bold">Nama Perusahaan / Instansi</td>
                <td colspan="3" class="text-primary font-weight-bold">{{$cust->company}}</td>
            </tr>
            <tr>
                <td class="text-left font-weight-bold">Telpon</td>
                <td colspan="3">{{$cust->phone}}</td>
            </tr>
            <tr>
                <td class="text-left font-weight-bold">Email</td>
                <td colspan="3">{{$cust->email}}</td>
            </tr>
            <tr>
                <td class="text-left font-weight-bold">Alamat</td>
                <td colspan="">{{$cust->address}}</td>
                <td>
                    <button type="button" class="btn bg-primary-400 btn-icon rounded-round legitRipple"
                        data-id_quo="{{$main->id}}" id="plus_btn" data-type="tambah" data-id="{{$cust->id}}"
                        onClick="AddressComToWH(this)">
                        <b><i class="fas fa-plus"></i></b></button>
                </td>
            </tr>
            @php $i=2; foreach($wo as $wwo){ @endphp
            @if($wwo->address!=null)
            <tr>
                <td class="text-left font-weight-bold">Alamat {{$i++}}</td>
                <td>{{$wwo->address}}</td>
                <td class="col-lg-2">
                    <button type="button" class="btn bg-primary-400 btn-icon rounded-round legitRipple"
                        data-id_quo="{{$main->id}}" id="plus_btn" data-type="edit" data-id="{{$wwo->id}}"
                        onClick="AddressComToWH(this)" id="ed_btn">
                        <b><i class="fas fa-pencil-alt"></i></b></button>
                    <button type="button" class="btn bg-danger-400 btn-icon rounded-round legitRipple"
                        data-id_quo="{{$main->id}}" id="plus_btn" data-type="remove" data-id="{{$wwo->id}}"
                        onClick="removeBtn(this)" id="rem_btn">
                        <b><i class="fas fa-trash"></i></b></button>
                </td>
            </tr>
            @endif
            @php } @endphp
            <tr>
                <td></td>
                <td colspan="3" id="letaksini"></td>
            </tr>
            @php $i=1; foreach($cust_pic as $cpic){ @endphp
            <tr class="table-active">
                <th colspan="3" class="text-danger">PIC {{$i++}}</th>
            </tr>
            <tr>
                <td class="text-left font-weight-bold">Nama</td>
                <td colspan="3">{{$cpic->name}}</td>
            </tr>
            <tr>
                <td class="text-left font-weight-bold">Jabatan</td>
                <td colspan="3">{{$cpic->jabatan}}</td>
            </tr>
            <tr>
                <td class="text-left font-weight-bold">Mobile</td>
                <td colspan="3">{{$cpic->mobile}}</td>
            </tr>
            <tr>
                <td class="text-left font-weight-bold">Email</td>
                <td colspan="3">{{$cpic->email}}</td>
            </tr>
            <tr>
                @if($cpic->address_pic==null)
                <td class="text-left font-weight-bold">Alamat</td>
                <td colspan="3">
                    <button type="button" class="btn bg-primary-400 btn-icon rounded-round legitRipple"
                        data-id_quo="{{$main->id}}" id="ed_alpic" data-id_quo="{{$main->id}}" id="plus_btn"
                        data-idpic="{{$cpic->id}}" data-type="tambah_pic" data-id="{{$cpic->id_customer}}"
                        onClick="AddressPic(this)">
                        <b><i class="fas fa-plus"></i></b></button>
                </td>
            <tr>
                <td></td>
                <td colspan="3" id="nextedit"></td>
            </tr>
            @else
            <td class="text-left font-weight-bold">Alamat</td>
            <td colspan="">{{$cpic->address_pic}}</td>
            <td>
                <button type="button" id="ed_alpic" class="btn bg-primary-400 btn-icon rounded-round legitRipple"
                    data-id_quo="{{$main->id}}" id="plus_btn" data-idpic="{{$cpic->id}}" data-type="edit_pic"
                    data-id="{{$cpic->id_customer}}" onClick="AddressPic(this)">
                    <b><i class="fas fa-pencil-alt"></i></b></button>
            </td>
            <tr>
                <td></td>
                <td colspan="3" id="nextedit"></td>
            </tr>
            @endif
            </tr>
            @php } @endphp
        </tbody>
    </table>
    @php
    $idk = getUserEmp(Auth::user()->id)->id;
    if($main->id_emp==$idk or $main->id_sales==$idk){
    @endphp
    <div class="modal-footer">
        <a href="{{ url('sales/customer/' . $cust->id).'/edit' }}" class="btn bg-danger legitRipple"><i
                class="fas fa-edit mr-2"></i>Edit Customer</a>
    </div>
    @php } @endphp
</div>