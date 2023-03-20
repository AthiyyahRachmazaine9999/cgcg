                <!-- /////////////////////////////////////////////////////// KP/////////////////////////                     -->
                <div class="more_deskripsi forms_{{$n_equ}}">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Detail Kegiatan / Pekerjaan
                    </legend>
                    <div class="form-group row after-add-more">
                        <label class='col-lg-3 col-form-label'>Tujuan</label>
                        <div class="col-lg-7">
                            <input type="text" name="tujuan_add[]" class="form-control " placeholder="Masukkan Tujuan">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Quantity</label>
                        <div class="col-lg-7">
                            <input type="number" name="qty_add[]" class="form-control" placeholder="Masukkan Quantity">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Item Description</label>
                        <div class="col-lg-7">
                            <input type="text" name="note_add[]" class="form-control " placeholder="Masukkan Note">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Biaya</label>
                        <div class="col-lg-7">
                            <input type="number" name="biaya_add[]" class="form-control" step="any"
                                placeholder="Masukkan Biaya">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Receipt</label>
                        <div class="col-lg-7">
                            <input type="file" name="file_set_add[]" class="form-input" placeholder="Upload Receipt">
                        </div>
                    </div>
                    <button type="button" onClick="more_desc(this)" data-type="delete" data-equ="{{$n_equ}}"
                        class="btn bg-danger btn-icon rounded-round legitRipple"><b><i
                                class="fas fa-trash"></i></b></button><br>
                </div>
                <script src="{{ asset('ctrl/finance/settlement-form.js?v=').rand() }}" type="text/javascript"></script>