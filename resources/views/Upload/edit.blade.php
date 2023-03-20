@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Edit Dokumen</h5>
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

            <form action="{{ route('saveUpdate.update', $data->id )}}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" placeholder="Nama Dokumen" class="form-control"
                        value="{{$data->id}}">
                    <div class="uploads_files">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Nama Dokumen</label>
                            <div class="col-lg-7">
                                <input type="text" name="doc_name" value="{{$data->doc_name}}"
                                    placeholder="Nama Dokumen" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Upload</label>
                            <div class="col-lg-7">
                                <input type="file" name="files" class="file-input form-control">
                            </div>
                            @if($data->files!=null)
                            <a href="{{asset($data->files)}}" target="_blank" class="btn btn-outline-primary">SHOW</a>
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="text-right">
                        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                        'data-method' => 'upload/file', 'type' => 'button','onclick'=>'cancel(this)'])
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
<script src="{{ asset('ctrl/upload/upload_file.js?v=').rand() }}" type="text/javascript"></script>
@endsection