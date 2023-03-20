<!-- Invoice template -->
<div class="" style="overflow-x:auto;overflow-y:auto;">
    <form action="{{ route('saveImport.save')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-header text-danger-800" style="width:140%">
            <h6 class="card-title" class="text-danger">Please Check This Following Import Data Before Upload</h6>
        </div>
        <div class="card-body">
            {!! Form::hidden('file',$file,['id'=>'value_files','class'=>'form-control']) !!}
            <table class="table table-sm table-borderless mb-0">
                <thead>
                    <th>No. </th>
                    <th>Part Number</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Dimensi</th>
                    <th>Satuan Panjang</th>
                    <th>Berat</th>
                    <th>Satuan Berat</th>
                    <th>Available Date</th>
                    <th>Overview</th>
                    <th>Spesifikasi</th>
                    <th>Type Harga</th>
                    <th>Harga Modal</th>
                    <th>Harga Produk</th>
                    <th>Harga catalog</th>
                    <th>Image</th>
                </thead>
                <tbody>
                    @php
                    $row = 1;
                    $no = 1;
                    foreach($sheet as $sh => $n) { @endphp
                    @php if ($row != $sh) { @endphp
                    <tr>
                        <td>{{$no++}}</td>
                        <td>{{$sheet[$sh]["A"]}}</td>
                        <td>{{$sheet[$sh]["B"]}}</td>
                        @php if(LikeCatID($sheet[$sh]["C"])==null) { @endphp
                        <td class="text-danger">{{$sheet[$sh]["C"]}}</td>
                        @php } else { @endphp
                        <td>{{$sheet[$sh]["C"]}}</td>
                        @php } @endphp
                        <td>{{$sheet[$sh]["D"]}}</td>
                        <td>{{$sheet[$sh]["E"]}}</td>
                        <td>{{$sheet[$sh]["F"]}}</td>
                        <td>{{$sheet[$sh]["G"]}}</td>
                        <td>{{$sheet[$sh]["H"]}}</td>
                        <td>{{$sheet[$sh]["I"]}}</td>
                        <td>{{$sheet[$sh]["J"]}}</td>
                        <td>{{$sheet[$sh]["K"]}}</td>
                        <td>{{$sheet[$sh]["L"]}}</td>
                        <td>{{$sheet[$sh]["M"]}}</td>
                        <td>{{$sheet[$sh]["N"]}}</td>
                        <td>{{$sheet[$sh]["O"]}}</td>
                        <td>{{$sheet[$sh]["P"]}}</td>
                        @php } @endphp
                    </tr>
                    @php } @endphp
                </tbody>
            </table>
            <br><br>
            <div class="text-left">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                'data-method' =>'product/content/listcontent', 'type' =>
                'button','onclick'=>'cancel(this)']) !!}
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
            <br><br>
        </div>
    </form>
</div>
</div>
@include('sales.quotation.attribute.modal')
@section('script')
<script src="{{ asset('ctrl/sales/quotation-detail.js') }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/product/listcontent-form.js') }}" type="text/javascript"></script>
@endsection