{!! Form::open(['method' => $method,'action'=>$action,'files'=>"true"]) !!}
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">Mail ke {{$customer->company}}:</label>
        {!! Form::text('name',$customer->email,['id'=>'cc_mail','class'=>'form-control']) !!}
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
        <label class="font-weight-bold">Subject</label>
        {!! Form::text('subject',$penawaran,['id'=>'subject','class'=>'form-control']) !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">Isi Email:</label>
        {!! Form::hidden('idsq',$idsq,['id'=>'idsq','class'=>'form-control']) !!}
        {!! Form::textarea('body','',['id'=>'body','class'=>'form-control summernote','placeholder'=>'Isikan keterangan tambahan anda']) !!}
    </div>
</div>
<!-- else -->
<div class="text-right">
    <button type="submit" class="btn btn-primary">Kirim Email<i class="far fa-save ml-2"></i></button>
</div>

{!! Form::close() !!}