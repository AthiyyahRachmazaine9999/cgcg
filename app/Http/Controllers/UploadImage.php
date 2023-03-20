<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadImage extends Controller
{
    //
        public function index(){
        return view('Product.ListContent.photo');
    }

    public function store(Request $request){
        if($request->has('pro_image')){
            $new = $request->file('pro_image');
            $newName= time().'-'.$new->getClientOriginalName();
            $request->pro_image->storeAs('public/COBA',$newName);
        } else{
            $newName=null;
        }
        return view('Product.ListContent.photo1', [
            'new_name' => $newName,
        ]);
    }

}
