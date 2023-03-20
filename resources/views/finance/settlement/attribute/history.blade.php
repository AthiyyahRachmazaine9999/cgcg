<table class="table table-striped table-bordered mt-3">
    @foreach($hist as $hist)
    <tbody>
        <tr>
            <td rowspan="2" scope="row" class="">
                {{\Carbon\Carbon::parse($hist->created_at)->format('d F Y H:i:s')}}</td>
            <td><em>{{$hist->activity_name}}</em></td>
        </tr>
        <tr>
            <td><em>{{getUserEmp($hist->created_by)->name}}</em></td>
        </tr>
    </tbody>
    @endforeach
</table>