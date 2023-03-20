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

            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form', 'files' => true ]) !!}
            <div class="card-body">
                @csrf
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">From Date</label>
                    <div class="col-lg-7">
                        <input type="text" name="from_date" placeholder="Enter Start Date" class="form-control dates">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">End Date</label>
                    <div class="col-lg-7">
                        <input type="text" name="end_date" placeholder="Enter End Date" class="form-control dates">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Attachment</label>
                    <div class="col-lg-7">
                        <input type="file" name="receipt" class="file-input form-control">
                    </div>
                </div>
                <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Description List</legend>
                <div class="part_description">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Description</label>
                        <div class="col-lg-7">
                            <textarea type="text" name="purpose" class="form-control"
                                placeholder="Enter Description"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Nominal</label>
                        <div class="col-lg-7">
                            <input type="number" name="nominal" placeholder="Enter Nominal" class="form-control">
                        </div>
                    </div>
                    <div class="form_purposes"></div>
                </div>
                <br>
                <button type="button" onclick="add_purposes(this)" data-type="tambah_datas"
                    class="btn bg-primary-400 btn-icon rounded-round legitRipple"><b><i
                            class="fas fa-plus"></i></b></button>

                <div class="text-right">
                    {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                    'data-method' => 'finance/pengajuan_pettycash', 'type' =>
                    'button','onclick'=>'cancel(this)'])
                    !!}
                    <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i>
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/pengajuan_pettycash-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection