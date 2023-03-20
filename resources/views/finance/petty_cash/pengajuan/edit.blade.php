@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Pengajuan Petty Cash</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>

        @if(session()->has('success'))
        <div class="alert alert-success alert-styled-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
            {{ session()->get('success') }}
        </div>
        @endif

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

            <form action="{{ route('pengajuan-petty_cash.update', $getdata->id)}}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="{{$getdata->id}}">
                <div class="card-body">
                    @csrf
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">From Date</label>
                        <div class="col-lg-7">
                            <input type="text" name="from_date" value="{{\Carbon\Carbon::parse($getdata->start_date)->format('Y-m-d')}}" placeholder="Enter Start Date" class="form-control dates">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">End Date</label>
                        <div class="col-lg-7">
                            <input type="text" name="end_date" value="{{\Carbon\Carbon::parse($getdata->end_date)->format('Y-m-d')}}" placeholder="Enter End Date" class="form-control dates">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Attachment</label>
                        <div class="col-lg-7">
                            <input type="file" name="receipt" class="file-input form-control">
                            @if($getdata->receipt!=null)
                            <a href="{{ asset($getdata->receipt)}}" style="margin-top:10px;" target="_blank" id="btn" class="btn btn-primary">SHOW</a>
                            @endif
                        </div>
                    </div>
                    <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Description List</legend>
                    @php
                    $i = 1;
                    $j = 1;
                    $l = 1;
                    foreach($get_dtl as $dtl)
                    {
                    @endphp
                    <input type="hidden" name="id_dtl[]" value="{{$dtl->id}}">
                    <div class="part_description forms_{{$l++}}">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-bold">Description {{$i++}}</label>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Description</label>
                            <div class="col-lg-7">
                                <textarea type="text" name="purpose[]" class="form-control" placeholder="Enter Description">{{$dtl->purpose}}</textarea>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" onclick="add_purposes(this)" data-type="edit_hapus_datas" data-id_dtl="{{$dtl->id}}" data-equ="{{$j++}}" class="btn bg-danger-400 btn-icon rounded-round legitRipple"><b><i class="fas fa-trash"></i></b></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Nominal</label>
                            <div class="col-lg-7">
                                <input type="number" name="nominal[]" value="{{$dtl->nominal}}" placeholder="Enter Nominal" class="form-control">
                            </div>
                        </div>
                        <button type="button" onclick="add_purposes(this)" data-type="edit_hapus_datas" data-id_dtl="{{$dtl->id}}" data-equ="{{$j++}}" class="btn bg-danger-400 btn-icon rounded-round legitRipple"><b><i class="fas fa-trash"></i></b></button>
                    </div>
                    @php
                    }
                    @endphp
                    <div class="form_purposes"></div>
                    <br>
                    <button type="button" onclick="add_purposes(this)" data-type="tambah_datas" class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i class="fas fa-plus"></i></b></button>

                    <div class="text-right">
                        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                        'data-method' => 'finance/pengajuan_pettycash', 'type' =>
                        'button','onclick'=>'cancel(this)'])
                        !!}
                        <button type="submit" class="btn btn-primary">Update<i class="far fa-save ml-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/pengajuan_pettycash-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection