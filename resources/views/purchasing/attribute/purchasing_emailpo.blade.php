{!! Form::open(['method' => $method,'action'=>$action,'files'=>"true"]) !!}

@php if ($type=="stock purchase") { @endphp 
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">Mail ke {{$cabang->nama_perusahaan}} {{$cabang->cabang_name}}:</label>
        {!! Form::text('name',$cabang->email_cabang,['id'=>'cc_mail','class'=>'form-control']) !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">CC Email:</label>
        {!! Form::select('cc_mail[]',$getemail,null,['id'=>'cc_mail','class'=>'form-control mail-list','multiple'=>'multiple']) !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">Isi Email:</label>
        {!! Form::hidden('idpo',$idpo,['id'=>'idpo','class'=>'form-control']) !!}
        {!! Form::textarea('body','',['id'=>'body','class'=>'form-control summernote','placeholder'=>'Isikan keterangan tambahan anda']) !!}
    </div>
</div>

@php } else { @endphp 
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">Mail ke {{$vendor->vendor_name}}:</label>
        {!! Form::text('name',$vendor->email,['id'=>'cc_mail','class'=>'form-control']) !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">CC Email:</label>
        {!! Form::select('cc_mail[]',$getemail,null,['id'=>'cc_mail','class'=>'form-control mail-list','multiple'=>'multiple']) !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">Isi Email:</label>
        {!! Form::hidden('idpo',$idpo,['id'=>'idpo','class'=>'form-control']) !!}
        {!! Form::textarea('body','',['id'=>'body','class'=>'form-control summernote','placeholder'=>'Isikan keterangan tambahan anda']) !!}
    </div>
</div>
@php  } @endphp
<div id="divimage"></div>
<button type="button" class="btn btn-primary btn-icon" data-idpo="{{$idpo}}" onClick="add_attach(this)"><i class="icon-plus-circle2"></i></button>
<!-- else -->
<div class="text-right">
    <button type="submit" class="btn btn-primary">Kirim Email<i class="far fa-save ml-2"></i></button>
</div>

{!! Form::close() !!}