{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">Perusahaan / Pihak Penerima</label>
        {!! Form::text('name',$names,['id'=>'name','class'=>'form-control','placeholder'=>'Masukan nama / perusahaan penerima']) !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">Alamat Lengkap</label>
        {!! Form::hidden('idwo',$idwo,['id'=>'idwo','class'=>'form-control']) !!}
        {!! Form::hidden('idadd',$idadd,['id'=>'idadd','class'=>'form-control']) !!}
        {!! Form::textarea('address',$addre,['id'=>'address','class'=>'form-control','placeholder'=>'Isikan alamat tujuan pengganti']) !!}
    </div>
</div>
<div class="text-right">
    <button type="submit" class="btn btn-primary">Tambah<i class="far fa-save ml-2"></i></button>
</div>

{!! Form::close() !!}