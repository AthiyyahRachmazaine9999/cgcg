<form action="{{ route('detail_settlement.save') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id_dtl" value="{{$dtl->id}}" id="id_dtl" class="form-control" readonly>
    <input type="hidden" name="detail_sets" value="detail_settlement" id=" detail_sets" class="form-control" readonly>
    <div class="form-group row col-md-12">
        <table class="table table-sm table-borderless mb-6">
            <tr>
                <td class="pl-8 w-20" scope="row"><strong>Tanggal Kegiatan</strong></td>
                <td>:</td>
                <td>{{$dtl->tgl_pekerjaan}}</td>

                <td class="pl-8 w-20" scope="row"><strong>Tanggal Pengajuan</strong></td>
                <td>:</td>
                <td>{{Carbon\carbon::parse($dtl->created_at)->format('Y-m-d')}}</td>
            <tr>
                <td class="pl-8 w-20" scope="row"><strong>Estimasi Biaya</strong></td>
                <td>:</td>
                <td>{{number_format($dtl->est_biaya)}}</td>

                <td class="pl-8 w-20" scope="row"><strong>Nama Pekerjaan</strong></td>
                <td>:</td>
                <td>{{$dtl->nama_pekerjaan}}</td>
            </tr>
            </tr>
        </table>
    </div>
    <div class="detail_pertama">
        @foreach($set as $setss)
        <div class="foreach_details_{{$setss->id}}">
            <legend></legend>
            <input type="hidden" name="id_cash_set" class="form-control id_cash_dtl" value=""
                placeholder="Masukkan Tujuan / Keperluan">
            <input type="hidden" name="id_cash_dtl[]" class="form-control" value="{{$setss->id}}"
                placeholder="Masukkan Tujuan / Keperluan">
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Detail Keperluan</label>
                <div class="col-lg-7">
                    <input type="text" name="items_for[]" class="form-control" value="{{$setss->items_for}}"
                        placeholder="Masukkan Tujuan / Keperluan" required>
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Quantity</label>
                <div class="col-lg-7">
                    <input type="number" class="form-control" id="set_qty" value="{{$setss->set_qty}}" name="set_qty[]"
                        class="form-control" onchange="Hitungs()" onkeyup="Hitungs()" placeholder="Masukkan Quantity">
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Unit Price</label>
                <div class="col-lg-7">
                    <input type="text" class="form-control" id="set_nominal" value="{{$setss->set_nominal}}"
                        name="set_nominal[]" class="form-control" onchange="Hitungs()" onkeyup="Hitungs()"
                        placeholder="Masukkan Nominal / Harga">
                </div>
            </div>
            <div class="form-group row row_do">
                <label class='col-lg-3 col-form-label'>Keterangan Lainnya</label>
                <div class="col-lg-7">
                    <input type="text" class="form-control" id="note" value="{{$setss->note}}" name="note[]"
                        class="form-control" placeholder="Masukkan Keterangan (Optional)">
                </div>
            </div>
            <div class="form-group row row_do">
                <label class="col-lg-3 col-form-label">Receipt</label>
                <div class="col-lg-7">
                    @if($setss->set_files==null)
                    <input type="file" id="set_files" name="set_files[]" class="file-input form-control">
                    @else
                    <input type="file" id="set_files" name="set_files[]" class="file-input form-control">
                    <a href="{{ asset($setss->set_files) }}" class="btn btn-outline-primary btn-sm">SHOW</a>
                    @endif
                </div>
            </div>
            <button type="button" class="sets_{{$setss->id}} btn btn-outline-danger btn-icon rounded-round legitRipple"
                data-type="hapus_detail_settlement" onClick="hapus_details(this)" data-id_set="{{$setss->id}}"
                data-id_dtl="{{$dtl->id}}"><i class="fas fa-trash"></i></button>
        </div>
    </div>
    @endforeach
    <div class="tambah_details"></div><br>
    <button type="button" class="btn btn-outline-primary btn-icon rounded-round legitRipple"
        data-type="tambah_detail_set" onClick="tambah_details(this)" data-id_dtl="{{$dtl->id}}"><i
            class="fas fa-plus"></i></button>
    <br><br>

    <!-- Document -->
    <div class="text-right">
        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method'
        =>'finance/cash_advance/'.$cash->id.'/settlement', 'type' =>
        'button','onclick'=>'cancel(this)']) !!}
        <button type="submit" class="btn btn-primary">Save<i class="far fa-save ml-2"></i></button>
    </div>
    </div>
</form>
@section('script')
<script src="{{ asset('ctrl/purchasing/po-form.js?v=').rand() }}" type="text/javascript"></script>
@if(session()->has('success'))
<script type="text/javascript">
swal({
    title: "Success",
    text: "{{ session()->get('success') }}",
    icon: "success",
});
@endif
</script>
<script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection