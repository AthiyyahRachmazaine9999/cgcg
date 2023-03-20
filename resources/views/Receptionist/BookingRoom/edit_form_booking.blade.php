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

    <form action="{{ route('edit_form.saveUpdate') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="id" value="{{$main->id}}">
        <div class="card-body">
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Booking a.n</label>
                <div class="col-lg-7">
                    <input type="text" name="reserved_name" value="{{$main->reserved_name}}" placeholder="Masukkan Nama"
                        class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Room Name</label>
                <div class="col-lg-7">
                    {!! Form::select('room_name', $room,
                    $main->id_room,['id' => 'room_name', 'class' => 'form-control form-control-select2',
                    'placeholder' =>'Pilih Room']) !!}
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Jam & Tanggal</label>
                <div class="col-lg-3">
                    <input type="text" name="start_time" value="{{$main->start_time}}" placeholder="Dari Jam"
                        class="form-control time">
                </div>
                <div class="col-lg-3">
                    <input type="text" name="end_time" id="end_time" value="{{$main->end_time}}"
                        placeholder="Sampai Jam" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Tanggal</label>
                <div class="col-lg-7">
                    <input type="text" name="date" value="{{$main->date}}" placeholder="Masukkan Tanggal"
                        class="form-control dates">
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Agenda</label>
                <div class="col-lg-7">
                    <input type="text" name="agenda" value="{{$main->agenda}}" placeholder="Masukkan Agenda / Tujuan"
                        class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Catatan (optional)</label>
                <div class="col-lg-7">
                    <textarea type="text" name="note" placeholder="Masukkan Catatan"
                        class="form-control">{{$main->note}}</textarea>
                </div>
            </div>
            <br>
            <br>

            <div class="form-group row">
                <button type="button" onclick="DeleteBooking(this)" data-id="{{$main->id}}"
                    class="text-left btn btn-danger"><i class="fa fa-trash"></i></button>
                <div class="col-lg-9 text-right">
                    {!! Form::button('Cancel', ['class' => 'btn btn-light btn-cancel', 'data-method'
                    =>'receptionist/booking_room', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Update<i class="far fa-save ml-2"></i></button>
                </div>
            </div>
    </form>
</div>
@include('sales.quotation.attribute.modal')
<script src="{{ asset('ctrl/receptionist/booking-form.js?v=').rand() }}" type="text/javascript"></script>
<script src="{{ asset('ctrl/receptionist/booking-list.js?v=').rand() }}" type="text/javascript"></script>