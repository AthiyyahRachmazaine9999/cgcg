<div class="form-group row">

    <label class='col-lg-3 col-form-label'>Tujuan Cuti</label>

    <div class="col-lg-7">

        <input type="text" name="purpose" class="form-control" placeholder="Masukkan Tujuan Cuti">

    </div>

</div>

<div class="form-group row">

    <label class='col-lg-3 col-form-label'>Keterangan</label>

    <div class="col-lg-7">

        <textarea type="text" name="note" class="form-control" placeholder="Masukkan Keterangan"></textarea>

    </div>

</div>

<legend class="text-uppercase font-size-sm font-weight-bold">Jam Dan Tanggal</legend>

<div class="form-group row row_times">

    <label class='col-lg-3 col-form-label'>Jam Pulang</label>

    <div class="col-lg-7">

        <input type="text" name="time_finish" class="form-control" id="timepicker" placeholder="Masukkan Jam">

    </div>

</div>

<script src="{{ asset('ctrl/hr/req_leave-form.js?v=').rand() }}" type="text/javascript"></script>