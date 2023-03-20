<ul class="nav nav-tabs nav-tabs-bottom nav-justified">
    @if($type=='qty')
    <li class="nav-item"><a href="#barang" class="nav-link active" data-toggle="tab"><i class="icon-menu7 mr-2"></i> Produk</a></li>
    @endif

    @if($type=='nominal')
    <li class="nav-item"><a href="#nominal" class="nav-link active" data-toggle="tab"><i class="icon-menu7 mr-2"></i> Nominal</a></li>
    @endif

    @if($type=='termin')
    <li class="nav-item"><a href="#termin" class="nav-link active" data-toggle="tab"><i class="icon-menu7 mr-2"></i> Termin</a></li>
    @endif

</ul>

<div class="tab-content">
    @if($type=='termin')
    <div class="tab-pane fade show active" id="termin">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Masukan Termin ( dalam % )</label>
                    <input type="number" class="form-control" name="termin" max="100" placeholder="Angka Persentase">
                </div>
            </div>
        </div>
        <div class="text-right mt-3 mr-3">
            <button type="submit" d="generate" class="btn btn-primary">Generate<i class="far fa-save ml-2"></i></button>
        </div>
    </div>
    @endif
    @if($type=='qty' or $type=='sku')
    <div class="tab-pane fade show active" id="barang">
        @include('sales.invoice.invoice_tabs_barang')
        <div class="text-right mt-3 mr-3">
            <button type="submit" d="generate" formtarget="_blank" class="btn btn-primary">Generate<i class="far fa-save ml-2"></i></button>
        </div>
    </div>
    @endif
    @if($type=='nominal')
    <div class="tab-pane fade show active" id="nominal">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Masukan Nominal</label>
                    <input type="number" class="form-control" name="nominal" placeholder="Angka Nominal">
                </div>
            </div>
        </div>
        <div class="text-right mt-3 mr-3">
            <button type="submit" id="generate" formtarget="_blank" class="btn btn-primary">Generate<i class="far fa-save ml-2"></i></button>
        </div>
    </div>
    @endif

</div>