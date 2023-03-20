                    <table class="table table-bordered" id="setts">
                        <thead class="success">
                            <tr>
                                <th colspan="5">
                                    <h6>{{$dtl->no_cashadv}}</h6>{{$dtl->nama_pekerjaan}}
                                </th>
                            </tr>
                            <tr class="text-center bg-teal">
                                <th>Detail Keperluan</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Attachment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; $p=1; $s=1; $l=1; $q=1; foreach($set_dtl as $set) { @endphp
                            <tr class="text-center">
                                <td>{{$set->items_for}}</td>
                                <td>{{$set->set_qty}}</td>
                                <td>{{number_format($set->set_nominal)}}</td>
                                <td class="awals">{{number_format($set->total_nominal)}}</td>
                                <td>
                                    @if($set->set_files!=null)
                                    <a href="{{ asset($set->set_files) }}"
                                        class="btn btn-outline-primary btn-sm">SHOW</a>
                                    @else
                                    <button class="btn btn-outline-primary btn-sm" disabled>SHOW</button>
                                    @endif
                                </td>
                            </tr>
                            @php } @endphp
                        </tbody>
                    </table><br>
                    <div class="text-right" style="padding-top:50px">
                        {!! Form::button('Back', ['class' => 'btn btn-light btn-cancel',
                        'data-method' =>'finance/cash_advance/'.$cash->id.'/settlement','type' =>
                        'button','onclick'=>'cancel(this)']) !!}
                    </div>