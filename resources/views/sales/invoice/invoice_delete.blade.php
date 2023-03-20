{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="form-group row">
    <div class="col-lg-12">
        {!! Form::hidden('id_quo',$main->id_quo,['id'=>'id_quo','class'=>'form-control']) !!}
        {!! Form::hidden('idinv',$main->id,['id'=>'idinv','class'=>'form-control']) !!}
        {!! Form::hidden('kondisi',$kondisi,['id'=>'kondisi','class'=>'form-control']) !!}
        {!! Form::textarea('delete_note','',['id'=>'delete_note','class'=>'form-control','placeholder'=>'Isikan keterangan tambahan anda','required'=>'yes']) !!}
    </div>
</div>
<div class="alert alert-danger alert-styled-left alert-dismissible">
    <span class="font-weight-semibold">Alert!</span> Menghapus invoice akan berefek kepada alur lanjutan, jika masalah hanya ketidaksesuaian data silahkan edit
</div>
<div class="text-right">
    <button type="submit" class="btn btn-danger">Delete<i class="fas fa-trash ml-2"></i></button>
</div>

{!! Form::close() !!}