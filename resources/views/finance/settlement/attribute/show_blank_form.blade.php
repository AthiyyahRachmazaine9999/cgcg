    <div class="form-group row">
        <label class="col-lg-3 col-form-label form-check-input-styled-primary" value="pro_priceType">No. Settlement*</label>
        <div class="col-lg-7">
            {!! Form::text('no_settlement', $set->no_settlement,['id' => 'emps', 'class'
            => 'form-control form-control-select2', 'placeholder' => '*', 'readonly']) !!}
        </div>
    </div>  
<div class="form-group row">
    <label class="col-lg-3 col-form-label form-check-input-styled-primary" value="pro_priceType">Nama*</label>
    <div class="col-lg-7">
        {!! Form::text('emp_id', emp_name($set->employee_id),['id' => 'emps', 'class'
        => 'form-control form-control-select2', 'placeholder' => '*', 'readonly']) !!}
    </div>
</div>
@if($set->mtd_payment=="Transfer")
<div class="form-group row">
    {!! Form::label('mtd_cash', 'Pembayaran', ['class' => 'col-lg-3
    col-form-label']) !!}
    <div class="col-lg-7">
        <div class="form-control"><b>{{$set->mtd_payment}}</b></div>
        <div class="form-control">{{$set->acc_bank}} - {{$set->no_acc_bank}} - {{$set->name_acc}} -
            {{$set->cabang_bank}}</div>
    </div>
</div>
@else
<div class="form-group row">
    {!! Form::label('mtd_cash', 'Pembayaran', ['class' => 'col-lg-3
    col-form-label']) !!}
    <div class="col-lg-7">
        <div class="form-control"><b>{{$set->mtd_payment}}</b></div>
    </div>
</div>
@endif

@if($set->doc_pay_back!=null)
@if($set->tf_payback)
<div class="form-group row">
    <label class="col-lg-3 col-form-label form-check-input-styled-primary" value="pro_priceType">Tanggal
        Transfer</label>
    <div class="col-lg-7">
        <div class="form-control">{{\Carbon\Carbon::parse($set->tf_payback)->format('d F Y')}}</div>
    </div>
</div>
@endif
<div class="form-group row">
    <label class="col-lg-3 col-form-label form-check-input-styled-primary" value="pro_priceType">Pay Back
        Receipt</label>
    <div class="col-lg-7">
        <a href="{{ asset($set->doc_pay_back) }}" target="_blank" class="btn btn-primary">SHOW</a>
    </div>
</div>
@endif
<br>
@if($set->status=="Completed" && $set->app_finance!=null && $set->sisa_biaya==null)
<div class="pros_finance">
    <div class="card-header bg-light text-primary-800 border-bottom-primary header-elements-inline">
        <h6 class="card-title">Processed By Finance</h6>
    </div><br>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label" value="">Note</label>
        <div class="col-lg-7">
            {!! Form::text('Notes',$set->notes_finance==null ? 'Complete' : $set->notes_finance ,['id' => 'note',
            'class' => 'form-control','readonly']) !!}
        </div>
    </div>

    @if($set->tgl_transfer!=null)
    <div class="form-group row">
        {!! Form::label('note', 'Tanggal Transfer', ['class' => 'col-lg-3
        col-form-label']) !!}
        <div class="col-lg-7">
            <div class="form-control">{{\Carbon\Carbon::parse($set->tgl_transfer)->format('d F Y')}}</div>
        </div>
    </div>
    @endif
    <div class="form-group row">
        <label class="col-lg-3 col-form-label" value="">Receipt</label>
        <div class="col-lg-7">
            @if ($set->doc_finance_settle!=null)
            <a href="{{ asset($set->doc_finance_settle)}}" target="_blank" class="btn btn-outline-primary btn-sm">SHOW</a>
            @else
            <button class="btn btn-outline-primary btn-sm" disabled>SHOW</button>
            @endif
        </div>
    </div>
    @if($mine->division_id==3)
    <div class="form-group row">
        <button type="button" onClick="edit_set(this)" data-id="{{$set->id}}"
            class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i
                    class="fas fa-pencil-alt"></i></b></button><br>
    </div>
    @endif
</div>
@endif
<br>
@if($mine->division_id==3 && $set->status=="Approved" && $set->app_finance!=null)
<div class="pros_finance">
    <div class="form-group row">
        <button type="button" onClick="all_done(this)" data-id="{{$set->id}}"
            class="btn bg-info-400 btn-icon rounded-round legitRipple"><b><i class="fas fa-check"></i>All
                Done</b></button><br>
    </div>
    <div class="form-group row">
        <button type="button" onClick="edit_set(this)" data-id="{{$set->id}}"
            class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i
                    class="fas fa-file"></i>Process</b></button><br>
    </div>
</div>
@endif
<br>
<!-- <span>Berikut List Kegiatan / Pekerjaan</span> -->
@if($cdtl!=null)
<div class="nav-tabs-responsive bg-light border-top">
    <ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
        <li class="nav-item"><a href="#desk" class="nav-link active" data-toggle="tab"><i class="icon-menu7 mr-2"></i>
                Settlement Amount</a></li>
        <li class="nav-item"><a href="#his" class="nav-link" data-toggle="tab"><i class="fab fa-audible"></i>
                History</a></li>
    </ul>
</div>

<div class="tab-content">
    <div class="tab-pane fade show active" id="desk">
    <br>
    <div class="this_place">
        <table class="table table-bordered" id="setts">
            <thead class="success">
                <tr class="text-center bg-teal">
                    <th>Description</th>
                    <th>File</th>
                    <th>Qty</th>
                    <th>Nominal</th>
                    <th>Note</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; $p=1; foreach($dtls as $dtl) {
                $subtotal = $dtl->est_biaya*$dtl->qty;
                @endphp
                {!! Form::hidden('id_dtl[]',$dtl->id,['id'=>'id_dtl','class'=>'form-control']) !!}
                <tr class="text-center">
                    <td>{{$dtl->tujuan}}</td>
                    <td>
                        @if($dtl->file_set!=null)
                        <a href="{{ asset($dtl->file_set) }}" target="_blank" class="btn btn-outline-primary btn-sm">SHOW</a>
                        @else
                        <button class="btn btn-outline-primary btn-sm" disabled>SHOW</button>
                        @endif
                    </td>
                    <td>{{$dtl->qty}}</td>
                    <td class="text-right">{{number_format($dtl->est_biaya)}}
                    </td>
                    <td>{{$dtl->notes}}</td>
                    <td class="text-right">{{number_format($subtotal)}}</td>
                </tr>
                @php $total+=$subtotal; } @endphp
            </tbody>
        </table><br>
        @endif
        @if(getUserEmp($set->created_by)->id==getUserEmp(Auth::id())->id &&
        $set->sisa_biaya==null && $set->biaya_finance>$total)
        <button type="button" onClick="add_note(this)" data-toggle="modal" data-target="#m_modal" data-type="fill"
            data-id="{{$set->id}}" class="btn bg-primary-400 btn-icon rounded-round legitRipple">Pay Back
            Settle</button><br>
        @endif
        <br>
        <div class="card-body">
            <div class="d-md-flex flex-md-wrap">
                {!!Form::hidden('apps_total',$total,['id'=>'totalss','class'=>'form-control
                app_ttl','placeholder'=>'Subtotal','readonly'])!!}
                <div class="pt-2 mb-3 wmin-md-400 ml-auto">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Total Settlement :</th>
                                    <td class="text-right">
                                    <td>{{number_format($total)}}</td>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Proccesed By Finance :</th>
                                    <td class="text-right">
                                    <td> {{number_format($set->biaya_finance)}}
                                    </td>
                                    </td>
                                </tr>
                                <tr>
                                    @php $a=0; $a= abs($set->biaya_finance - $total) @endphp
                                    <th>{{number_format($a)==0 ? 'Status Settlement' : 'Cost'}}</th>
                                    <td class="text-right">
                                    <td> {{number_format($a)==0 ? 'DONE' : number_format($a)}}
                                    </td>
                                    </td>
                                </tr>
                                @if($set->sisa_biaya!=null && $set->sisa_biaya==$a)
                                <tr>
                                    <th>Status Pay Back:</th>
                                    <td class="text-right">
                                    <td class="text-primary"> DONE
                                    </td>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <div class="text-right">
            {!! Form::button('Back', ['class' => 'btn btn-light btn-cancel',
            'data-method' =>'finance/settlement','type' =>
            'button','onclick'=>'cancel(this)']) !!}

            @if($set->status=="Pending" && getUserEmp($set->created_by)->id==getUserEmp(Auth::id())->id)
            <button id="approval" name="ajuan" onClick="set_app(this)" data-user="ajukans" data-type="ajuan"
                data-id="{{$set->id}}" class="btn btn-primary"><i class="fas fa-file"></i>Ajukan</button>
            @endif

            @if(getUserEmp($set->created_by)!=getUserEmp(Auth::id()) && $emp->spv_id==$mine->id || $mine->id==1)
            @if($set->status=="Need Approval")
            <button id="approval" name="approval" onClick="set_app(this)" data-user="spv" data-type="approval"
                data-id="{{$set->id}}" data-user="spv" class="btn btn-primary"><i class="fas fa-file"></i>Approve</button>
            <button id="rem_sub" name="approve" onClick="set_app(this)" data-type="reject" data-id="{{$set->id}}"
                data-user="spv" class="btn btn-danger"><i class="fas fa-file"></i>Reject</button>
            @endif
            @endif

            @if(in_array($mine->id,explode(',',getConfig('app_finance'))) || in_array($mine->id,explode(',',getConfig('ajaxmng'))) )
            @if($set->status=="Approved" && $set->app_finance==null)
            <button id="approval" name="approval" onClick="set_app(this)" data-user="mng" data-type="approval"
                data-id="{{$set->id}}" data-user="mng" class="btn btn-primary"><i class="fas fa-file"></i>Approve</button>
            <button id="rem_sub" name="approve" onClick="set_app(this)" data-type="reject" data-id="{{$set->id}}"
                data-user="mng" class="btn btn-danger"><i class="fas fa-file"></i>Reject</button>
            @endif
            @endif
        </div>

        <!-- /basic layout -->
    </div>
    </div>
    <div class="tab-pane fade show" id="his">
        @include('finance.settlement.attribute.history')
    </div>
</div>
<script src="{{ asset('ctrl/finance/settlement-form.js')}}" type="text/javascript"></script>
<script src="{{ asset('ctrl/finance/cash_adv-form.js')}}" type="text/javascript"></script>