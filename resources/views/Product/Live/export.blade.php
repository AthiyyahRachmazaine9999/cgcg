<table>
    <thead>
        <tr>
            <th>Model</th>
            <th>No.SKU</th>
            <th>Price</th>
            <th>Image</th>
            <th>Weight</th>
            <th>Weight Class</th>
            <th>Date Available</th>
            <th>Status</th>
            <th>Overview</th>
            <th>Spesification</th>
            <th>Length</th>
            <th>Width</th>
            <th>Height</th>
            <th>Length Class</th>
            <th>Date Added</th>
        </tr>
    </thead>
    <tbody>
        @foreach($live as $lives)
        <tr>
            <td>{{$lives->model}}</td>
            <td>{{$lives->sku}}</td>
            <td>{{number_format($lives->price)}}</td>
            <td>{{$lives->image}}</td>
            <td>{{$lives->weight}}</td>
            <td>{{Weight($lives->weight_class_id)}}</td>
            <td>{{$lives->date_available}}</td>
            <td>{{$lives->status==1 ? "Active" : "In Active"}}</td>
            <td>{!!strip_tags($lives->overview,'')!!}</td>
            <td>{!!strip_tags($lives->text,'')!!}</td>
            <td>{{round($lives->length)}}</td>
            <td>{{round($lives->width)}}</td>
            <td>{{round($lives->height)}}</td>
            <td>{{LengthUnitID($lives->length_class_id)->title}}</td>
            <td>{{$lives->date_added}}</td>

        </tr>
        @endforeach
    </tbody>
</table>