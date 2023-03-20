                <table class="table table-striped table-hover m_popup" id="ptable">
                    <thead class="thead-colored bg-teal">
                        <tr class="text-center">
                            <th>Date</th>
                            <th>Product Status</th>
                            <th>Created By</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php foreach($status as $val) { @endphp
                        <tr>
                            <td>{{$val->created_at}}</td>
                            <td>{{$val->status}}</td>
                            <td>{{user_name($val->created_by)}}</td>
                            <td>{{$val->status_App}}</td>
                        </tr>
                        @php } @endphp
                    </tbody>
                </table>
