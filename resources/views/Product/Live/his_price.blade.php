                <table class="table table-striped table-hover display m_popup" id="ptable">
                    <thead class="thead-colored bg-teal">
                        <tr class="text-center">
                            <th>Date</th>
                            <th>Price</th>
                            <th>Created By</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php foreach($harga as $val) { @endphp
                        <tr class="text-center">
                            <td>{{$val->created_at}}</td>
                            <td>{{number_format($val->harga,2)}}</td>
                            <td>{{user_name($val->created_by)}}</td>
                            <td>{{$val->status}}</td>
                        </tr>
                        @php } @endphp
                    </tbody>
                </table>
