<div class="timeline-group">
    @php foreach($act as $det) { @endphp
    <div class="timeline-item">
        <div class="timeline-time">{{$det->created_at}}</div>
        <div class="timeline-body">
            <p class="timeline-title"><a href="">{!!div_name(getUserEmp($det->created_by)->division_id)!!}
                    ( {!!getUserEmp($det->created_by)->emp_name!!} )</a></p>
            <p class="timeline-text">{!!$det->activity!!}
                {{getProductDetail($det->sku)== null ? null : getProductDetail($det->sku)->name}}
                {{$det->qty_terima==null ? '' : 'dengan quantity terima '.$det->qty_terima}} 
                {{$det->qty_problem==null ? '' : 'dan dengan quantity problem '.$det->qty_problem}}</p>
         
        </div><!-- timeline-body -->
    </div><!-- timeline-item -->
    @php } @endphp
</div>