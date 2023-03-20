<div class="card">
    <div class="card-header">
        <h6 class="card-title"><span class="font-weight-semibold">History</span> Activity</h6>
    </div>

    <div class="card-body">
        <div class="timeline-group">
            @php foreach($act as $det) { @endphp
            <div class="timeline-item">
                <div class="timeline-time">{{$det->activity_updated_date}}</div>
                <div class="timeline-body">
                    <p class="timeline-title"><a href="">{!!div_name(getUserEmp($det->activity_id_user)->division_id)!!} ( {!!getUserEmp($det->activity_id_user)->emp_name!!} )</a></p>
                    <p class="timeline-text">{!!$det->activity_name!!}</p>
                </div><!-- timeline-body -->
            </div><!-- timeline-item -->
            @php } @endphp
            <a href="#" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" onclick="AllStatus(this)" class="btn btn-danger btn-block">Lihat Semua Activity</a>
            <a href="#" data-toggle="modal" data-target="#m_modal" data-id="{{$main->id}}" onclick="NewStatus(this)" class="btn btn-primary btn-block">Tambah Keterangan</a>
        </div>
    </div>
</div>