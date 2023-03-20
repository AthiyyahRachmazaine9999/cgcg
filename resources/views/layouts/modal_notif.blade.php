@if($type=="unpaid")
<table class="table table-bordered table-striped table-hover m_datatableunpaid">
    <thead>
        <tr class="bg-slate-800">
            <th class="text-center">ID</th>
            <th class="text-center">No SO</th>
            <th class="text-center">No. Invoice </th>
            <th class="text-center">Tanggal Invoice</th>
            <th class="text-center">Created By</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
</table>

@else
<table class="table table-bordered table-striped table-hover m_datatablepending">
    <thead>
        <tr class="bg-slate-800">
            <th class="text-center">ID</th>
            <th class="text-center">Nomer </th>
            <th class="text-center">Nama Paket</th>
            <th class="text-center">Customer</th>
            <th class="text-center">Sales</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
</table>
@endif
<script src="{{ asset('js/ajax_notif.js?v=').rand() }}" type="text/javascript"></script>