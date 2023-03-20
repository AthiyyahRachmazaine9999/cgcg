@extends('layouts.head')
@section('content')
<div class="container">
    <!-- @if($cash!=0)
    <div class="row justify-content">
        <div class="col-md-6 text-left">
            <div class="card-body text-left">
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-file"></i> {{$cash}} Cash Advance Masuk Hari Ini
                </div>
            </div>
        </div>
    </div>
    @endif -->
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-body bg-danger-400 has-bg-image">
                        <div class="media">
                            <div class="media-body">
                                <h6>{{$cash}} Cash Advance</h6>
                                <span class="text-white">Telah Di input Hari ini dengan Status <i>Pending</i>
                                </span><br>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-stack2 icon-3x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-body bg-danger-400 has-bg-image">
                        <div class="media">
                            <div class="media-body">
                                <h6>{{$set}} Settlement</h6>
                                <span class="text-white">Telah Di input Hari ini dengan Status <i>Pending</i></span><br>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-cloud-upload icon-3x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header alpha-primary text-success-800 border-bottom-success header-elements-inline">
                    <h5 class="card-title">List Info Finance</h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>
                <div class="nav-tabs-responsive bg-light border-top">
                    <ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
                        <li class="nav-item"><a href="#cash" class="nav-link active" data-toggle="tab"><i
                                    class="icon-menu7 mr-2"></i>Cash Advance</a></li>
                        <li class="nav-item"><a href="#settle" class="nav-link" data-toggle="tab"><i
                                    class="icon-stack2 mr-2"></i>Settlement</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    @if(session()->has('success'))
                    <div class="alert alert-success alert-styled-left alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                        {{ session()->get('success') }}
                    </div>
                    @endif

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="cash">
                            <table class="table table-bordered table-striped table-hover m_datatable">
                                <thead>
                                    <tr class="bg-slate-800">
                                        <th class="text-center">ID</th>
                                        <th class="text-center">No. Cash Advance</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Created By</th>
                                        <th class="text-center">Created At</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <!-- Tab Settlement -->
                        <div class="tab-pane fade show" id="settle">
                            <table class="table table-bordered table-striped table-hover m_datatablesettle">
                                <thead>
                                    <tr class="bg-slate-800">
                                        <th class="text-center">ID</th>
                                        <th class="text-center">No. Settlement</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Created By</th>
                                        <th class="text-center">Created At</th>
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
@endsection
@section('script')
<script src="{{ asset('ctrl/dashboard/data-finance.js') }}" type="text/javascript"></script>
@endsection