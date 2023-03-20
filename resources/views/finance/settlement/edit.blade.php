@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h3 class="card-title">Settlement</h3>
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

            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_formss', 'files' => true ]) !!}
            @csrf
            <div class="card-body">
                <input type="hidden" id="edit_request" value="user" name="edit_request" class="form-control">
                @if($set->no_ref==null)
                @include('finance.settlement.attribute.edit_blank_form')
                @else
                @include('finance.settlement.attribute.edit_with_cash')
                @endif
                <br>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/finance/settlement-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection