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

            <form action="{{ route('invoice_up.update', $invoice_id->id )}}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="id_quo" class="form-control" name="hide_id_quo" value="{{$invoice_id->id_quo}}"
                    placeholder="Nomer SO" readonly>
                {!! Form::hidden('id',$invoice_id->id,['id'=>'id','class'=>'form-control']) !!}
                {!! Form::hidden('type',$type,['id'=>'type','class'=>'form-control']) !!}
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-check-input-styled-primary" value="pro_priceType">
                            No. Invoice</label>
                        <div class="col-lg-7">
                            <input type="text" id="no_invoice" class="form-control" name="no_invoice"
                                value="{{$invoice_id->no_invoice}}" placeholder="No. Invoice" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-check-input-styled-primary" value="">No. SO</label>
                        <div class="col-lg-7">
                            <input type="text" id="id_quo" class="form-control" name="id_quo"
                                value="SO{{sprintf('%06d', $invoice_id->id_quo)}}" placeholder="Nomer SO" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>No. NPWP</label>
                        <div class="col-lg-7">
                            <input type="text" id="npwp" class="form-control" name="npwp" value="{{$invoice_id->npwp}}"
                                placeholder="Masukkan Nomer NPWP">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Alamat NPWP</label>
                        <div class="col-lg-7">
                            <textarea id="npwp_alamat" class="form-control" name="npwp_alamat" value=""
                                placeholder="Masukkan Alamat NPWP" required>{{$invoice_id->npwp_alamat}}</textarea>
                        </div>
                    </div>
                    <!-- Payment -->
                    <div class="text-right">
                        {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                        'data-method' =>'sales/quotation/'.$invoice_id->id_quo, 'type' =>
                        'button','onclick'=>'cancel(this)']) !!}
                        <button type="submit" class="btn btn-primary">Update<i class="far fa-save ml-2"></i>
                        </button>
                    </div>
                </div>

            </form>
        </div>