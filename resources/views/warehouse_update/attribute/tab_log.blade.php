<div class="tab-pane fade show" id="log">
    @if($main!=null)
    @if(count($history)!=0)
    <div class="card-body">
        <div class="card-body">
            <div class="timeline-group">
                @php foreach($history as $det) { @endphp
                <div class="timeline-item">
                    <div class="timeline-time">{{$det->created_at}}</div>
                    <div class="timeline-body">
                        <p class="timeline-title"><a href="">{!!div_name(getUserEmp($det->created_by)->division_id)!!}
                                ( {!!getUserEmp($det->created_by)->emp_name!!} )</a></p>
                        <p class="timeline-text">{!!$det->activity!!}
                            {{getProductDetail($det->sku)== null ? null : getProductDetail($det->sku)->name}}
                            {{$det->qty_terima==null ? '' : 'dengan quantity terima '.$det->qty_terima}}
                            {{$det->qty_problem==null ? '' : 'dan dengan quantity problem '.$det->qty_problem}}</p>
                    </div>
                    <!-- timeline-body -->
                </div>
                <!-- timeline-item -->
                @php } @endphp
                <br>
                <div class="col-lg-5">
                    <a href="#" data-toggle="modal" data-target="#m_modal" onclick="All_history(this)"
                        data-id="{{$main->id}}" data-type="outs" class="btn btn-primary">Lihat Semua History</a>
                </div>
                <br>
                <div class="col-lg-5">
                    <a href="#" data-toggle="modal" data-target="#m_modal2" data-id="{{$main->id}}"
                        onclick="add_note_inbound(this)" data-type="outs" class="btn btn-info">Tambah Keterangan</a>
                </div>
            </div>
        </div>
    </div>
    @endif
    @else
    <br>
    <br>
    <span class="text-danger" style="padding-left: 20px;"><b>History belum Tersedia</b></span>
    <br>
    <br>
    @endif
</div>