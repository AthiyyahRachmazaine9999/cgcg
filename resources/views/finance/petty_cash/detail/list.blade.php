<div class="card" style="overflow-x:auto;">
    <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
        <h5 class="card-title">
            <a href="{{ url('finance/pettycash') }}">
                <i class="fas fa-arrow-left text-info-800"></i> </a>
            Petty Cash {{$id}}
        </h5>
        <div class="header-elements">
            <a href="{{ url('finance/pettycash/'.$month.'-'.$year.'/create') }}">
                <button type="button" class="btn bg-primary" data-toggle="modal">
                    Create
                </button>
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session()->has('success'))
        <div class="alert alert-success alert-styled-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
            {{ session()->get('success') }}
        </div>
        @endif

        <input type="hidden" name="id" id="month" value="{{$id}}" class="form-control id">
        <table class="table tbl_dtl table-bordered table-striped table-hover">
            <thead class="thead-colored bg-dark bg-gradient">
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>PIC</th>
                    <th>Nominal</th>
                    <th>Created by</th>
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