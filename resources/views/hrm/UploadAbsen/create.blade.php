@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Absensi Page</h5>
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
                <div class="panel panel-default">
                    <div class="panel-heading" style="padding-left:10px">
                        <h3>{{ $info['status']}}</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="panel-body">
                            <table class="table table-responsive">
                                <form action="{{ route('absensi.store') }}" method="post">
                                    {{csrf_field()}}
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" placeholder="keterangan..."
                                                name="note">
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-flat btn-primary" name="time_in"
                                                {{$info['btnIn']}}>Check In</button>
                                        </td>
                                </form>
                                <form action="{{ route('absensi.update','$data_absen->id') }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <td>
                                        <button type="submit" class="btn btn-flat btn-primary" name="time_out"
                                            {{$info['btnOut']}}>Check Out</button>
                                    </td>
                                    </tr>
                                </form>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection