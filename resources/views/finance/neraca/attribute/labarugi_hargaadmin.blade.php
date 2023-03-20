<tr>
    <td class="font-weight-bold" colspan="2">BIAYA ADMIN & UMUM</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Gaji, Lembur & THR</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Management Fee</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Seragam/ Fasilitas Karya</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Asuransi Karyawan (BPJS)</td>
    <td class="text-right">{{number_format($bpjs[0]->total+$bpjs[1]->total)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Sewa Gedung</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="{{$kantor->code}}" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Keperluan Kantor</td>
    <td class="text-right">{{number_format($kantor->total)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="{{$atk->code}}" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya ATK</td>
    <td class="text-right">{{number_format($atk->total)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Surat-Surat & Perizinan</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya STNK,KIR,Pajak Kendaraan</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Perjalanan Dinas</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="{{$transport->code}}" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Transportasi</td>
    <td class="text-right">{{number_format($transport->total)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="{{$tla->code}}" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Telp, Listrik, Air</td>
    <td class="text-right">{{number_format($tla->total)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="{{$pengiriman->code}}" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Pengiriman</td>
    <td class="text-right">{{number_format($pengiriman->total)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="{{$webi->code}}" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Webmail Dan Internet</td>
    <td class="text-right">{{number_format($webi->total)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Penyusutan Kendaraan</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Pemeliharaan Gedung</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="{{$kservice->code}}" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Service Kendaraan</td>
    <td class="text-right">{{number_format($kservice->total)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Keamanan & Kebersihan</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Bunga Pinjaman</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Asuransi Kendaraan</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Jasa Konsultan</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Pengobatan/ Kesehatan</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Sumbangan & Iuran</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="{{$opl->code}}" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya Operational Lainnya</td>
    <td class="text-right">{{number_format($opl->total)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya PPh 21</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Biaya PPN Penjualan</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<tr onclick="showDetail(this)" data-code="" data-where="pettycash" data-toggle="modal" data-target="#m_modal">
    <td>Denda Pajak</td>
    <td class="text-right">{{number_format(0)}}</td>
</tr>
<!-- total  -->
<tr>
    <td class="font-weight-bold">TOTAL BIAYA ADMIN & UMUM</td>
    <td class="text-right text-danger font-weight-bold">{{number_format($total)}}</td>
</tr>
<tr>
    <td colspan="2"></td>
</tr>
<!-- end total -->