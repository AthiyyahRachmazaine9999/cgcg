                <!-- /////////////////////////////////////////////////////// KP/////////////////////////                     -->
                <div class="kegiatans form_{{$n_equ}}">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Detail Kegiatan / Pekerjaan
                    </legend>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Tanggal</label>
                        <div class="col-lg-7">
                            <input type="text" name="tgl_pekerjaan_add[]" class="form-control tanggal_pekerjaan"
                                placeholder="Tanggal Kegiatan / Pekerjaan" required="required">
                        </div>
                    </div>
                    <div class="form-group row after-add-more">
                        <label class='col-lg-3 col-form-label'>Nama Pekerjaan</label>
                        <div class="col-lg-7">
                            <input type="text" name="nama_pekerjaan_add[]" class="form-control "
                                placeholder="Nama Kegiatan / Pekerjaan" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Deskripsi</label>
                        <div class="col-lg-7">
                            <input type="text" name="deskripsi_add[]" class="form-control"
                                placeholder="Deskripsi Kegiatan / Pekerjaan">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Estimasi Biaya</label>
                        <div class="col-lg-7">
                            <input type="number" name="est_biaya_add[]" class="form-control" step="any"
                                placeholder="Estimasi Biaya Kegiatan / Pekerjaan" required="required">
                        </div>
                    </div>
                    <button type="button" onClick="add_btn(this)" data-type="delete" data-equ="{{$n_equ}}"
                        class="btn bg-danger btn-icon rounded-round legitRipple"><b><i
                                class="fas fa-trash"></i></b></button><br>
                </div>
                <script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript"></script>