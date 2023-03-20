@extends('layouts.head')
@section('content')
<div class="content mb-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="media-body">
                        <h3 class="card-profile-name">{{$mine->name}}</h3>
                        <p class="card-profile-position tx-danger">{!!getEmp($mine->id)->division_name!!}</p>

                        <p class="mg-b-0">Coming Soon Detail</p>
                    </div>
                </div>
            </div>
            <input type="hidden" value="{{$mine->id}}" id="myid">
            <ul class="nav nav-tabs nav-tabs-solid nav-justified rounded border-0">
                <li class="nav-item"><a href="#calendar" class="nav-link rounded-left active" data-toggle="tab"><i class="fas fa-align-left mr-2"></i>Calendar</a></li>
                <li class="nav-item"><a href="#list" class="nav-link" data-toggle="tab"><i class="far fa-address-card mr-2"></i>List</a></li>
            </ul>

            <div class="card">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="calendar">
                        <div class="fullcalendar-basic"></div>
                    </div>

                    <div class="tab-pane fade" id="list">
                        <div class="card">
                            <div class="card-body">
                                <table class="table m_datatable table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Location</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/absensi-detail.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/hr/absensi-calendar.js?v=').rand() }}" type="text/javascript"></script>
@endsection