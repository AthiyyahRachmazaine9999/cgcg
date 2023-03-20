<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Finance\TaxModel;
use Illuminate\Support\Facades\Session;
use DB;
use Carbon\Carbon;
use Storage;

class PPH_Controller extends Controller
{

    public function index()
    {
        return view('finance.tax.pph.index');
    }
    public function ajax_pph(Request $request)
     {
        // dd($request);
        $columns = array(
             0 => 'id',
             1 => 'date',
             2 => 'no_faktur',
        );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $menu_count    = TaxModel::where('type_tax', 'pph')->get();
         $totalData     = $menu_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = TaxModel::select('*')->where('type_tax', 'pph')
                 ->orderby($order, $dir)->limit($limit, $start)
                 ->get();
         } else {
             $search = $request->input('search')['value'];
             $posts  = TaxModel::where('no_faktur', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->get();
             $totalFiltered = TaxModel::where('no_faktur', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->limit($limit, $start)->count();
         }
 
         $data = [];
         if (!empty($posts)) {
             foreach ($posts as $post) {
                // dd($post);
                $data[] = [
                    'year'      => $post->year,
                    'no_faktur' => $post->no_faktur,
                    'created_at'=> Carbon::parse($post->created_at)->format('d F Y'),
                    'id'        => $post->id,
                ];
             }  
         }
 
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
     }


}