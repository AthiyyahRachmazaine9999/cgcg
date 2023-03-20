@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Presence Page</h5>
        </div>

        <div class="page-wrapper">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="container">
                <div class="panel panel-default text-center">
                    <div class="panel-heading text-center" style="padding-left:10px">
                        <button type="button" style="width:50px" onClick="refresh_loc(this)"
                            class="btn btn-outline-primary get_lokasi" data-type="static" id="lokasis" name="time"><i
                                class="fas fa-circle-notch"></i></button>
                        <h3>
                            <p id="ct5"></p>
                        </h3>
                        <h6>
                            <p id="lokasi" class="txt_location"></p>
                        </h6>
                        <div class="col-lg-7">
                            <textarea type="text" style="margin-left:200px;" name="note" id="catatan"
                                class="form-control text-center" placeholder="Masukkan Note"></textarea>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <div class="text-center">
                            <input type="hidden" name="alamats" id="address" class="form-control">
                            <input type="hidden" name="lats" id="lats" class="form-control">
                            <input type="hidden" name="longs" id="longs" class="form-control">
                            <input type="hidden" name="status" id="status_absen" class="form-control"
                                value="{{$data_absen==null ? 'check-in' : $data_absen->status}}">
                            <button type="button" onclick="absensi(this)" style="width:200px"
                                class="text-center btn btn-flat btn-primary" name="time">{{$view}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="overflow-x:auto;">
        <div class="card-body">
            @if(session()->has('success'))
            <div class="alert alert-success alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('success') }}
            </div>
            @endif

            <table class="table m_datatable table-bordered table-striped table-hover">
                <thead>

                    <!-- //tambahkan kode untuk tanda + show atau button show -->
                    <tr>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
            {{ method_field('DELETE') }}
            @csrf
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/upload-absensi-list.js') }}" type="text/javascript"></script>
@endsection
<!--  -->