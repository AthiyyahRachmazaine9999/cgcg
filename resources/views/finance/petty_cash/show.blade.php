@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <ul class="nav nav-tabs nav-tabs-solid nav-justified rounded border-0">
        <li class="nav-item"><a href="#detail" class="nav-link rounded-left active" data-toggle="tab"><i
                    class="fas fa-align-left mr-2"></i>Detail</a></li>
        <li class="nav-item"><a href="#dokumen" class="nav-link" data-toggle="tab"><i
                    class="far fa-address-card mr-2"></i>Dokumen</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="detail">
            @include('finance.petty_cash.detail.list')
        </div>
        <div class="tab-pane fade show" id="dokumen">
            @include('finance.petty_cash.dokumen.list')
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/pettycash-list.js') }}" type="text/javascript"></script>
@endsection