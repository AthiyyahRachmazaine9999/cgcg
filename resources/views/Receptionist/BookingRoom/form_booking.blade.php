<!-- Basic layout-->
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

    <form action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <input type="hidden" name="date" class="form-control" value="{{$date}}">
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Booking a.n</label>
                <div class="col-lg-7">
                    <input type="text" name="reserved_name" placeholder="Masukkan Nama" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Room Name</label>
                <div class="col-lg-7">
                    {!! Form::select('room_name', $room,
                    '',['id' => 'room_name', 'class' => 'form-control form-control-select2',
                    'placeholder' =>'Pilih Room']) !!}
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Jam & Tanggal</label>
                <div class="col-lg-3">
                    <input type="text" name="start_time" placeholder="Dari Jam" class="form-control time">
                </div>
                <div class="col-lg-3">
                    <input type="text" name="end_time" placeholder="Sampai Jam" class="form-control time">
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Tanggal</label>
                <div class="col-lg-7">
                    <input type="text" name="date" value="{{$date}}" placeholder="Masukkan Tanggal"
                        class="form-control dates">
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Agenda</label>
                <div class="col-lg-7">
                    <input type="text" name="agenda" placeholder="Masukkan Agenda / Tujuan" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Catatan (optional)</label>
                <div class="col-lg-7">
                    <textarea type="text" name="note" placeholder="Masukkan Catatan" class="form-control"></textarea>
                </div>
            </div>
            <br>
            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method'
                =>'receptionist/booking_room', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                <button type="submit" class="btn btn-primary">Save<i class="far fa-save ml-2"></i></button>
            </div>
        </div>
    </form>
</div>
@include('sales.quotation.attribute.modal')
<script src="{{ asset('ctrl/receptionist/booking-form.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/receptionist/booking-list.js?v=').rand() }}" type="text/javascript"></script>