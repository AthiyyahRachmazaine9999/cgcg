<div class="card" style="overflow-x:auto;">
    <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
        <h5 class="card-title">
            <a href="{{ url('finance/pettycash') }}">
                <i class="fas fa-arrow-left text-info-800"></i> </a>
            Petty Cash {{$id}}
        </h5>
        <div class="header-elements">
            <a href="{{ url('finance/pettycash/dokumen/'.$id.'/create') }}">
                <button type="button" class="btn bg-primary" data-toggle="modal">
                    Create Document
                </button>
            </a>
        </div>
    </div>

    <div class="card-body">
        <input type="hidden" name="id" id="id" value="{{$id}}" class="form-control id">
        <table class="table tbl_dokumen table-bordered table-striped table-hover">
            <thead class="thead-colored bg-dark bg-gradient">
                <tr>
                    <th>Created By</th>
                    <th>Nama Dokumen</th>
                    <th>File</th>
                    <th>Created At</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
        </table>
        {{ method_field('DELETE') }}
        @csrf
    </div>
</div>
@section('script')
<script src="{{ asset('ctrl/finance/pettycash-list.js') }}" type="text/javascript"></script>
@endsection