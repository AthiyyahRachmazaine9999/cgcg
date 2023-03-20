<table class="table table-bordered table-striped table-hover datatable_vendor">
    <thead class="bg-slate-800 text-center">
        <tr>
            <input type="hidden" value="{{$id_vendor}}" class="id_vendor">
            <th>Nomer PO</th>
            <th>Tipe Pembayaran</th>
            <th>Total Harga</th>
            <th>Tanggal PO</th>
        </tr>
    </thead>
</table>
@section('script')
<script src="{{ asset('ctrl/sales/vendor-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection