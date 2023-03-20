<table class="table table-bordered">
    <thead class="success">
        <tr class="text-center bg-teal">
            <th>Tanggal Kegiatan / Pekerjaan</th>
            <th>Nama Kegiatan / Pekerjaan</th>
            <th>Deskripsi</th>
            <th>Estimasi Biaya</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; foreach($dtl as $dtl) {
        @endphp
        <tr class="text-center">
            <td>{{$dtl->tgl_pekerjaan}}</td>
            <td>{{$dtl->nama_pekerjaan}}</td>
            <td>{{$dtl->deskripsi}}</td>
            <td class="text-right">{{number_format($dtl->est_biaya)}}</td>
        </tr>
        @php $total+=$dtl->est_biaya; } @endphp
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-center"><b>TOTAL</b></td>
            <td class="text-right">{{number_format($total)}}</td>
        </tr>
        @if($cash->app_finance!=null && $cash->biaya_finance!=null)
        <tr>
            <td colspan="3" class="text-center"><b>TOTAL PROCESSED</b></td>
            <td class="text-right">{{number_format($cash->biaya_finance)}}</td>
        </tr>
        @endif
    </tfoot>
</table>