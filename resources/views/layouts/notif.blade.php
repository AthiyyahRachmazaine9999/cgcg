<!-- notif section  -->
<div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card_modif card-body bg-danger-400 has-bg-image">
            <a onclick="DetailAlert(this)" data-toggle="modal" data-target="#m_modal" class="text-white" data-id_user="{{Auth::id()}}" data-type="pendingrfq" data-div="{{Session::get('division_id')}}">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{$main['pendingrfq']['jumlah']}}</h3>
                        <span class="text-uppercase font-size-xs">{{$main['pendingrfq']['comment']}}</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-folder-open3 icon-3x opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card_modif card-body bg-danger-400 has-bg-image">
            <a onclick="DetailAlert(this)" data-toggle="modal" data-target="#m_modal" class="text-white" data-id_user="{{Auth::id()}}" data-type="waitmodal" data-div="{{Session::get('division_id')}}">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{$main['waitmodal']['jumlah']}}</h3>
                        <span class="text-uppercase font-size-xs">{{$main['waitmodal']['comment']}}</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-stack2 icon-3x opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card_modif card-body bg-danger-400 has-bg-image">
            <a onclick="DetailAlert(this)" data-toggle="modal" data-target="#m_modal" class="text-white" data-id_user="{{Auth::id()}}" data-type="nego" data-div="{{Session::get('division_id')}}">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{$main['negosiasi']['jumlah']}}</h3>
                        <span class="text-uppercase font-size-xs">{{$main['negosiasi']['comment']}}</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-bubbles6 icon-3x opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card_modif card-body bg-danger-400 has-bg-image">
            <a onclick="DetailAlert(this)" data-toggle="modal" data-target="#m_modal" class="text-white" data-id_user="{{Auth::id()}}" data-type="nodoc" data-div="{{Session::get('division_id')}}">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{$main['nodoc']['jumlah']}}</h3>
                        <span class="text-uppercase font-size-xs">{{$main['nodoc']['comment']}}</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-file-pdf icon-3x opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card_modif card-body bg-danger-400 has-bg-image">
            <a onclick="DetailAlert(this)" data-toggle="modal" data-target="#m_modal" class="text-white" data-id_user="{{Auth::id()}}" data-type="waitout" data-div="{{Session::get('division_id')}}">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{$main['waitout']['jumlah']}}</h3>
                        <span class="text-uppercase font-size-xs">{{$main['waitout']['comment']}}</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-truck icon-3x opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card_modif card-body bg-danger-400 has-bg-image">
            <a onclick="DetailAlert(this)" data-toggle="modal" data-target="#m_modal" class="text-white" data-id_user="{{Auth::id()}}" data-type="unpaid" data-div="{{Session::get('division_id')}}">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{$main['unpaid']['jumlah']}}</h3>
                        <span class="text-uppercase font-size-xs">{{$main['unpaid']['comment']}}</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-cash4 icon-3x opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@include('sales.quotation.attribute.modal')
<script src="{{ asset('js/ajax_notif.js?v=').rand() }}" type="text/javascript"></script>