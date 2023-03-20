                    <div class="payments">
                        <div class="next_payment_{{$n_equ}}"><strong>Tambah Detail</strong>
                            <br>
                            <div class="form-group row next_payment_{{$n_equ}}">
                                <label class='col-lg-3 col-form-label'>Tanggal Pembayaran</label>
                                <div class="col-lg-7">
                                    <input type="text" id="full_payment" value="" name="date_payment[]"
                                        class="form-control dates" placeholder="Masukkan Tanggal Pembayaran" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class='col-lg-3 col-form-label'>Nominal</label>
                                <div class="col-lg-7">
                                    <input type="number" id="full_payment" value="" name="payment_amounts[]"
                                        class="form-control amounts" placeholder="Masukkan Jumlah Pembayaran">
                                </div>
                                <button type="button" onClick="remove_payment({{$n_equ}})"
                                    class="btn btn-outline-danger btn-icon rounded-round legitRipple">
                                    <b><i class="fas fa-trash"></i></b></button>
                            </div>
                        </div>
                    </div>
                    <script src="{{ asset('ctrl/finance/finance_invoice-form.js?v=').rand() }}" type="text/javascript">
                    </script>