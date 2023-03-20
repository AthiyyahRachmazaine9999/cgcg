<table class="table table-striped table-hover only_datatable" id="ptable">
    <thead class="thead-colored bg-teal">
        <tr class="text-center">
            <th>Nomer DO</th>
            <th>Upload Date</th>
            <th>File</th>
        </tr>
    </thead>
    <tbody>
        @php
        foreach ($filedo as $val){
        @endphp
        <tr>
            <td>{{$val->no_do}}</td>
            <td class="text-center">{{$val->created_at}}</td>
            <td class="text-center">
                <a target="_blank" href="{{asset('public/'.$val->do_balik_doc)}}" class="btn btn-primary btn-labeled btn-labeled-left btn-sm legitRipple text-right"><b>
                        <i class="fas fa-cloud-download-alt"></i></b>Download File
                </a>
            </td>
        </tr>
        @php } @endphp
    </tbody>
</table>