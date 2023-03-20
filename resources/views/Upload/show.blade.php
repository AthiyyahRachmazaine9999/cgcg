@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h3 class="card-title">Detail</h3>
        </div>
        <br>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead class="font-weight-bold">
                    <tr>
                        <td>Nama Dokumen</td>
                        <td>Dokumen</td>
                        <td>Created at</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                    @php foreach($main as $main) {
                    @endphp
                    <tr>
                        <td>{{$main->doc_name}}</td>
                        <td>
                            @if($main->files!=null)
                            <a href="{{asset($main->files)}}" target="_blank" class="btn btn-outline-primary">SHOW</a>
                            @else
                            <button class="btn btn-outline-primary" disabled></button>
                            @endif
                        </td>
                        <td>{{\Carbon\Carbon::parse($main->created_at)->format('d F Y')}}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="list-icons-item dropdown">
                                    <a href="#" class="list-icons-item dropdown-toggle caret-0"
                                        data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a onclick="edit_file(this)" data-id="{{$main->id}}"
                                            class="text-primary dropdown-item"><i
                                                class="text-primary fa fa-pencil-alt"></i>Edit</a>
                                        <div class=" dropdown-divider">
                                        </div>
                                        <a onclick="delete_file(this)" data-id="{{$main->id}}"
                                            class="text-danger dropdown-item"><i
                                                class="text-danger icon-trash"></i>Hapus</a><br>

                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @php } @endphp
                </tbody>
            </table>
            <br><br><br>
            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-light btn-cancel', 'data-method'
                =>'upload/file', 'type' => 'button','onclick'=>'cancel(this)']) !!}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/upload/upload_file.js?v=').rand() }}" type="text/javascript"></script>
@endsection