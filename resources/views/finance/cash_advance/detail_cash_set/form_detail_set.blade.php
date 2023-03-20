                    <div class="next_detail_{{$n_equ}}">
                        <legend><strong>Tambah Detail</strong></legend>
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Detail Keperluan</label>
                            <div class="col-lg-7">
                                <input type="text" name="items_for[]" class="form-control"
                                    placeholder="Masukkan Tujuan / Keperluan" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Quantity</label>
                            <div class="col-lg-7">
                                <input type="number" class="form-control" id="set_qty" name="set_qty[]"
                                    class="form-control" placeholder="Masukkan Quantity" onchange="Hitungs()"
                                    onkeyup="Hitungs()">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class='col-lg-3 col-form-label'>Unit Price</label>
                            <div class="col-lg-7">
                                <input type="number" class="form-control" id="set_nominal" name="set_nominal[]"
                                    class="form-control" placeholder="Masukkan Nominal / Harga" onchange="Hitungs()"
                                    onkeyup="Hitungs()">
                            </div>
                        </div>
                        <div class="form-group row row_do">
                            <label class='col-lg-3 col-form-label'>Keterangan Lainnya</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="note" name="note[]" class="form-control"
                                    placeholder="Masukkan Keterangan (Optional)">
                            </div>
                        </div>
                        <div class="form-group row row_do">
                            <label class="col-lg-3 col-form-label">Receipt</label>
                            <div class="col-lg-7">
                                <input type="file" id="set_files" name="set_files[]" class="file-input form-control">
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-danger btn-icon rounded-round legitRipple"
                            data-type="hapus_detail_set" onClick="hapus_details({{$n_equ}})"
                            data-id_dtl="{{$dtl->id}}"><i class="fas fa-trash"></i></button>
                        <br>
                    </div>
                    <script src="{{ asset('ctrl/purchasing/po-form.js?v=').rand() }}" type="text/javascript"></script>
                    <script src="{{ asset('ctrl/finance/cash_adv-form.js?v=').rand() }}" type="text/javascript">
                    </script>