@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Create Menu</h5>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
            <div class="form-group row">
                {!! Form::label('title', 'Menu Name *', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::text('title','',['id'=>'rel_date','class'=>'form-control','placeholder'=>'Enter menu name','required']) !!}
                </div>
            </div>
            
            <div class="form-group row">
                {!! Form::label('sequence_to', 'Sequence', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::text('sequence_to',$seq,['id'=>'rel_date','class'=>'form-control','placeholder'=>'Enter menu name','readonly']) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('parent_id', 'Parent *', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::select('parent_id', $menu, null,['id' => 'parent_id', 'class' => 'form-control form-control-select2', 'placeholder' => '*']) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('icon_id', 'icon', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-6">
                    {!! Form::select('icon_id', $icon, null, ['id' => 'icon_id', 'class' => 'form-control','style'=>'width:55%']) !!}
                </div>
                <div class="col-lg-1">
                    <span class="input-group-append" data-toggle="modal" data-target="#m_modal_icons" style="cursor:pointer;">
                        <span class="input-group-text"><i class="icon-inbox"></i></span>
                    </span>
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('link', 'Link *', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::text('link','',['id'=>'link','class'=>'form-control','placeholder'=>'Enter link menu','required']) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('description', 'Description', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::textarea('description', null, ['id' => 'description', 'class' => 'form-control m-input', 'rows' => 2]) !!}
                </div>
            </div>

            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'setting/menu', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                <button type="submit" class="btn btn-primary">Create Menu<i class="far fa-save ml-2"></i></button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
    <!-- /basic layout -->
    <div class="modal fade" id="m_modal_icons" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"><span class="flaticon-share"></span>&nbsp;&nbsp; Icons</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs nav-tabs-bottom border-bottom-0" role="tablist">
                                <li class="nav-item m-tabs__item" style="margin-right:15px">
                                    <a class="nav-link m-tabs__link active" data-toggle="tab" href="#tab_flaticon" role="tab" style="padding-top:0;font-size:12px">
                                        Fontawesome
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_flaticon">
                            <div class="m-scrollable" data-scrollbar-shown="true" data-scrollable="true" data-height="430">
                                <div class="row">
                                    {!! implode($icons['fontawesome']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/menu/form.js') }}" type="text/javascript"></script>
@endsection