    <form action="{{ route('cabang.store')}}" method="POST" >
        @csrf
        {!! Form::hidden('cabang',"cabang",['id'=>'cabang','class'=>'form-control']) !!}
            <div class="card-body">
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Nama Perusahaan</label>
                 <div class="col-lg-7">
                <input type="text" name="nama_perusahaan" class="form-control" placeholder="Nama Perusahaan">
                </div>
            </div>
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Cabang</label>
                 <div class="col-lg-7">
                <input type="text" name="cabang_name" class="form-control" placeholder="Cabang">
                </div>
            </div>
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Email Cabang</label>
                 <div class="col-lg-7">
                <input type="text" name="email_cabang" class="form-control" placeholder="Email">
                </div>
            </div>
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Phone</label>
                 <div class="col-lg-7">
                <input type="text" name="cabang_phone" class="form-control" placeholder="Phone">
                </div>
            </div>
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Alamat Cabang</label>
                 <div class="col-lg-7">
                <input type="text" name="cabang_address" class="form-control" placeholder="Alamat">
                </div>
            </div>
            <div class="form-group row">
                <label class= 'col-lg-3 col-form-label'>Is Active</label>
               
                <input type="radio" name="is_active" value="Y" > YES
                
                <input type="radio" name="is_active" value="N"> NO
                </div>
            <div class="text-right">
            {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'purchasing/order/create', 'type' => 'button','onclick'=>'cancel(this)']) !!}
            <button type="submit" class="btn btn-primary">Create Cabang<i class="far fa-save ml-2"></i></button>
            </div>
        </div>
        </div>
        </div>
    </form>
