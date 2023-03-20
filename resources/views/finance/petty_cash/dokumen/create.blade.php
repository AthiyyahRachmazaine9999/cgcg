@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Petty Cash</h5>
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

            <form action="{{ route('petty_cash.upload') }}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    @csrf
                    <input type="hidden" name="month" placeholder="Nama Dokumen" value="{{$month}}"
                        class="form-control">
                    <input type="hidden" name="year" placeholder="Nama Dokumen" value="{{$year}}" class="form-control">
                    <div class="uploads_files">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Nama Dokumen</label>
                            <div class="col-lg-7">
                                <input type="text" name="doc_name[]" placeholder="Nama Dokumen" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Dokumen</label>
                            <div class="col-lg-7">
                                <input type="file" name="files[]" class="file-input form-control" required>
                            </div>
                        </div>
                        <div class="tambah_uploads"></div>

                        <!-- <div class="form-group row">
                            <button type="button" onClick="new_uploads()"
                                class="btn bg-primary-400 btn-icon rounded-round legitRipple">
                                <b><i class="fas fa-plus"></i></b></button>
                        </div> -->
                    </div>
                    <br>
                    <div class="text-right">
                        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                        'data-method' => 'finance/pettycash/'.$month.'-'.$year.'/show', 'type' =>
                        'button','onclick'=>'cancel(this)'])
                        !!}
                        <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/pettycash-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection