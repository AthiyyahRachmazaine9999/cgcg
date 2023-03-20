<div class="timeline-group">
    @php foreach($act as $det) { @endphp
    <div class="timeline-item">
        @php
        if($det->activity_id_user == '0'){
        $nameuser = "#System Auto";
        }else{
        $nameuser = div_name(getUserEmp($det->activity_id_user)->division_id)."(".getUserEmp($det->activity_id_user)->emp_name.")";
        }
        @endphp
        <div class="timeline-time">{{$det->activity_updated_date}}</div>
        <div class="timeline-body">
            <p class="timeline-title"><a href="">{!!$nameuser!!}</a></p>
            <p class="timeline-text">{!!$det->activity_name!!}</p>
        </div><!-- timeline-body -->
    </div><!-- timeline-item -->
    @php } @endphp
</div>