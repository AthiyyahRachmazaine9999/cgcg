@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card" style="overflow-x:auto;">
        <div class="card-header">
            <a href="{{ url('product/content/listcontent/create') }}">
                <button type="button" class="btn_creates btn bg-primary" data-toggle="modal">
                    Create Product
                </button>
            </a>
            <!-- <button type="button" style="margin-left:20px" class="btn bg-primary" data-target="#importExcel"
                data-toggle="modal">
                Import Product
            </button> -->
            <div class="btn-group">
                <button type="button" class="btn_links btn btn-outline-primary btn-icon dropdown-toggle"
                    data-toggle="dropdown">
                    <i class="icon-link"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" data-target="#importExcel" data-toggle="modal"><i class="icon-menu7"></i>
                        Mass Import Product</a>
                    <a onClick="export_formats(this)" class="dropdown-item"><i class="icon-mail5"></i>Export
                        Format</a>
                    <a class="dropdown-item" data-target="#import_zip" data-toggle="modal"><i
                            class="icon-screen-full"></i>Zip Image</a>
                </div>
            </div>
        </div>
        <!-- Import Excel -->
        <div class="excel_import modal fade" id="importExcel" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" class="modal_import_excel" action="{{ route('listcontent.import')}}"
                    enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
                        </div>
                        <div class="modal-body">

                            {{ csrf_field() }}

                            <label>Pilih file Excel</label>
                            <div class="form-group">
                                <input type="file" id="eksport_file" name="file" required="required">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="submit_excel btn btn-primary">Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="import_zip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ route('importzip.import')}}" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Import Zip</h5>
                        </div>
                        <div class="modal-body">

                            {{ csrf_field() }}

                            <label>Pilih file ZIP</label>
                            <div class="form-group">
                                <input type="file" name="file" required="required">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Import ZIP</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="text_title card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Import Excel</h5>
            <div class="header-elements">
            </div>
        </div>
        <div class="card-body">
            @if(session()->has('success'))
            <div class="alert alert-success alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('success') }}
            </div>
            @endif
            <div class="excel_show">
            <table class="table m_datatable table-bordered table-striped table-hover">
                <thead>

                    <!-- //tambahkan kode untuk tanda + show atau button show -->
                    <tr>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Product Name</th>
                        <th>Available date</th>
                        <th>Price</th>
                        <th>Status Active</th>
                        <th>Status Tayang</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
    {{ method_field('DELETE') }}
    @csrf
        </div>
    </div>
    </div>
</div>
@endsection
@section ('script')
<script src="{{ asset('ctrl/product/listcontent.js') }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/product/listcontent-form.js') }}" type="text/javascript"></script>
@endsection
<!--  -->
