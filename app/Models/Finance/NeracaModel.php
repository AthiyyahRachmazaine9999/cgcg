<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Finance\PettyCashModel;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class NeracaModel extends Model
{
    use HasFactory;

    public static function hitTotal($now,$id){
        return PettyCashModel::select(DB::raw('SUM(nominal) as total'),'code_id as code')
        ->where('code_id', $id)
        ->whereYear('created_at', Carbon::parse($now)->format('Y'))->first();
    }

    public static function detailTotal($now,$id){
        return PettyCashModel::where('code_id', $id)
        ->whereYear('created_at', Carbon::parse($now)->format('Y'))->get();
    }
}
