<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
    }

        public function getNotif(Request $request)
    {
        //dapatkan data yang mana belum ada settlement
        $cas  = CashAdvance::select(DB::raw('(created_at + interval 7 day) as due_date'),'created_at', 'no_cashadv', 'created_by', 'type_cash')
                ->where([['created_by', $request->id], ['status', 'completed']])->get();
        
        $result = DB::table('finance_cash_adv')->whereIn('no_cashadv', function($q){
            $q->select('no_ref')->from('finance_settlement');
        })->get();  
        if (count($cas) > 0) {
            foreach ($cas as $cash ) {
                $absen  = FinanceSettlementModel::where([['no_ref', $cash->no_cashadv], ['created_by', $request->id],['status', '!=', 'Rejected']])->first();
                $arr[] = array(
                        'no_ref'    => $absen==null ? "belum" : $absen->no_settlement,
                        'no_cashadv'=> $cash->no_cashadv,
                        'created_at'=> $cash->created_at,    
                        'batas'     => $cash->due_date,
                    );
                    $result = $arr;
            }
        } else {
            $result = null;
        }
        
        return response()->json([
            'success'   => true,
            'datas'     => $result,
        ]);
        
    }
    

}
