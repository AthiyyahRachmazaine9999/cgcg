            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form', 'files' => true ]) !!}
            @csrf
            <input type="hidden" id="id" name="id" value="{{$cash->id}}" id="note" placeholder="Masukkan Note"
                class="form-control">
            <div class="form-group row">
                {!! Form::label('note', 'Nominal', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <input type="number" id="nominal" name="nominal" placeholder="Masukkan Nominal"
                        class="form-control">
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('note', 'Tanggal', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <input type="text" id="trf" name="tgl_transfer" placeholder="Masukkan Tanggal Transaksi" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('note', 'Note', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <input type="text" id="file" name="note" value="" id="note" placeholder="Masukkan Note"
                        class="form-control">
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('file', 'Receipt', ['class' => 'col-lg-3
                col-form-label']) !!}
                <div class="col-lg-7">
                    <input type="file" id="file" name="file_cash" value="" id="file" class="form-input">
                </div>
            </div>
            <div class="form-group row">
                <button type="submit" class="btn btn-primary">Save<i class="far fa-save ml-2"></i>
                </button>
            </div>
            {!! Form::close() !!}