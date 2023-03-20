    {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_out', 'class' => 'show_outs']) !!}
<legend class="text-center font-weight-bold text-danger">Nama Penerima Berikut Wajib Di isi</legend>
    <table class="table table-bordered table-striped table-hover m_outbound">
        <tbody>
            <tr>
                <td>
                    {!! Form::hidden('id_quo',$request->id_quo,['id'=>'id_quo','class'=>'form-control'])
                    !!}
                    {!! Form::hidden('no_wh_out',$request->resi,['id'=>'resi','class'=>'form-control'])
                    !!}
                    {!! Form::hidden('id_wo',$request->id_wo,['id'=>'id_wh_out','class'=>'form-control'])
                    !!}
                    {!! Form::hidden('alamat',$request->alamat,['id'=>'id_wh_out','class'=>'form-control'])
                    !!}
                </td>
                <td colspan="5" style="text-align:left; color:red;">
                    <b> Nama Penerima* </b>
                </td>
                <td>
                    <input name="nama_penerima" type="text" id="nama_penerima" class="form-control"
                        placeholder="Masukkan Nama Penerima" required>
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <div class="text-right" style="padding-right:20px">
        <button type="submit" class="btn btn-primary btn-labeled btn-labeled-left legitRipple saveKirim">
            <b><i class="fas fa-truck"></i></b> Cetak DO
        </button>
    </div>
    <br>
    {!! Form::close() !!}
    @section('script')
    <script src="{{ asset('ctrl/warehouse/wh-detail.js') }}" type="text/javascript"></script>
    @endsection