@extends('layouts.head')
@section('content')
<div class="content">
    <!-- count section  -->
    <div class="row">
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h6 class="media-title font-weight-semibold">{{ number_format($cprice1, 2) }}</h6>
                                <span class="text-danger font-weight-bold">{{$cancel1}} Order</span><br>
                                <span class="text-muted">Order Batal Tahun {{\Carbon\Carbon::now()->format('Y')}}</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-cancel-square2 icon-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h6 class="media-title font-weight-semibold">{{number_format($SumOr1,2)}}</h6>
                                <span class="text-danger font-weight-bold">{{$orMonth1}} Order</span><br>
                                <span class="text-muted">Order Masuk Bulan Ini</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-clipboard2 icon-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h6 class="text-danger font-weight-bold">{{$rfq1}} Order</h6>
                                <span class="text-muted">RFQ to team Bulan Ini</span>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <!-- <h6 class="media-title font-weight-semibold">9,000,000,000</h6> -->
                                <span class="text-success font-weight-bold">{{$app1}} Order</span><br>
                                <span class="text-muted">Telah Disetujui Tahun {{\Carbon\Carbon::now()->format('Y')}}</span><br><br>
                                <span class="text-danger font-weight-bold">{{$rej1}} Order</span><br>
                                <span class="text-muted">Tidak Disetujui Tahun {{\Carbon\Carbon::now()->format('Y')}}</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-hour-glass icon-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Latest Update</a></h6><br>
                    <div class="header-elements">
                        <a href="#" class="text-default"><i class="icon-cog3"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="media-list">
                        @php foreach($salesAct1 as $det) { @endphp
                        <li class="media">
                            <div class="mr-3 position-relative">
                                <h3 class="text-danger font-weight-bold">{{\Carbon\Carbon::parse($det->created_at)->format('d/m/Y')}}</h3>
                            </div>

                            <div class="media-body">
                                <div class="d-flex justify-content-between">
                                    <div class="media-title text-danger font-weight-bold">{{$det->quo_name}}</div>
                                </div>
                                <span class="font-size-sm text-muted">{!!div_name(getUserEmp($det->activity_id_user)->division_id)!!} ( {!!getUserEmp($det->activity_id_user)->emp_name!!} )</span>
                                <br>{{$det->activity_name}} 
                            </div>
                        </li>
                        @php } @endphp
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- end count section  -->
    <!-- table section  -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header alpha-primary text-success-800 border-bottom-success header-elements-inline">
                    <h5 class="card-title">Recent Product Request</h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(session()->has('success'))
                    <div class="alert alert-success alert-styled-left alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                        {{ session()->get('success') }}
                    </div>
                    @endif


                    <table class="table table-bordered table-striped table-hover m_datatable">
                        <thead>
                            <tr class="bg-slate-800">
                                <th class="text-center" >ID </th>
                                <th class="text-center" >Nomer </th>
                                <th class="text-center" >Nama Paket</th>
                                <th class="text-center" >Sales</th>
                                <th class="text-center" >Created At</th>
                                <th class="text-center" >Status Eksternal</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <!-- end table section  -->
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/dashboard/data-admin.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/notifikasi.js') }}" type="text/javascript"></script>
@endsection