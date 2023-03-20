            <form action="{{ url('img/image')}}" method="POST" enctype="multipart/form-data">
                @csrf
			   <div class="form-group row">
					<label class="col-lg-3 col-form-label" name="pro_image">Upload Image*</label>
					<div class="col-lg-7">
						<input type="file" name="pro_image" accept="image/png, image/jpeg, image/jpg" class="form-control" required>
					</div>
				</div>
            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'product/content/category', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                <button type="submit" class="btn btn-primary">Create Product<i class="far fa-save ml-2"></i></button>
            </div>
                </form>
