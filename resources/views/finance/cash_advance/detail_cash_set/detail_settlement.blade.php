                            <tr class="text-center">
                                <th>Tujuan</th>
                                <th>Quantity</th>
                                <th colspan="2">Unit Price</th>
                                <th>Total Price</th>
                                <th>Receipt</th>
                            </tr>
                            @foreach($cash_set as $sets => $q)
                            <tr class="text-center">
                                <td>{{$cash_set[$sets]->items_for}}</td>
                                <td>{{$cash_set[$sets]->set_qty}}</td>
                                <td colspan="2">{{$cash_set[$sets]->set_nominal}}</td>
                                <td>{{number_format($cash_set[$sets]->total_nominal)}}
                                <td>
                                    @if($cash_set[$sets]->set_files!=null || $cash_set[$sets]->set_files!='')
                                    <a href="{{ asset($cash_set[$sets]->set_files) }}"
                                        class="btn btn-outline-primary btn-sm">SHOW</a>
                                    @else
                                    <button type="button" class="btn btn-outline-primary btn-sm" disabled>SHOW</button>
                                    @endif
                                </td>
                                </td>
                            </tr>
                            @endforeach