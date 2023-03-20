<table class="table table-striped table-hover m_popup" id="ptable">
    <thead class="thead-colored bg-teal">
        <tr class="text-center">
            <th>Date</th>
            <th>Description</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        @php foreach($getdata as $val) { @endphp
        <tr>
            <td>{{$val->date}}</td>
            <td>{{$val->description}}</td>
            <td class="text-right">{{number_format($val->nominal)}}</td>
        </tr>
        @php } @endphp
    </tbody>
</table>