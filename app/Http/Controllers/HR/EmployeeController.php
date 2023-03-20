<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\EmployeeModel;
use App\Models\HR\EmployeeAssetModel;
use App\Models\Role\Role_division;
use App\Models\Role\Role_cabang;
use App\Models\HR\EmployeeDokumen;
use App\Models\HR\EmployeeStatus;
use App\Models\HR\SpvModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mine = getUserEmp(Auth::id());
        $div=Role_division::where('id', $mine->division_id)->first();
        return view('employee.index',[
            'data'    => $mine,
            'div'     => $mine->division_id,
        ]);
    }


    public function notif(Request $request)
    {
        // dd($request);
        $mine = getUserEmp(Auth::id());
        $div=Role_division::where('id', $mine->division_id)->first();
        return view('employee.index',[
            'data'    => $mine,
            'div'     => $mine->division_id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    
        $mine     = getUserEmp(Auth::id());
        $cabang   = Role_cabang::all();
        $division = Role_division::all();
        $spv      = $this->AllSpv();
        return view('employee.create',[
            'cabang'    => $cabang,
            'data'      => $mine,
            'division'  => $division,
            'spv'       => $spv,
            'status_emp'=> $this->getStatusEmp(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->save($request, 'created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // dd($id);
        $mine  = getUserEmp(Auth::id());
        $emp   = EmployeeModel::where('id', $id)->first();
        $asset = EmployeeAssetModel::where('id_emp', $id)->get();
        $ass   = EmployeeAssetModel::where('id_emp', $id)->first();
        $dok   = EmployeeDokumen::where('id_emp', $id)->get();
        return view ('employee.show',[
            'jika'       => $ass,
            'asset'      => $asset,
            'mine'       => $mine,
            'dok'        => $dok,
            'data'       => $emp,
            'id'         => $emp->id,
            'division'   => div_name($emp->division_id),
            'cabang'     => cabang_name($emp->cabang_id),
            'spv'        => emp_name($emp->spv_id),
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeModel $employee)
    {
        $mine     = getUserEmp(Auth::id());
        $employee = EmployeeModel::where('id',$employee->id)->first();
        $asset    = EmployeeAssetModel::where('id_emp',$employee->id)->get();
        $dok      = EmployeeDokumen::where('id_emp', $employee->id)->get();
        return view('employee.edit', [
            'asset'          => $asset,
            'dok'            => $dok,
            'data'           => $mine,
            'getdata'        => $employee,
            'spv_id'         => $this->AllSpv(),
            'division_id'    => $this->AllDivision(),
            'cabang_id'      => $this->AllCabang(),
            'getEmp'         => $this->getStatusEmp(),
            'method'         => "put",
            'action'         => ['HR\EmployeeController@update',$employee->id],
        ]);
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request, $id);
        return $this->saveUpdate($request, 'update', $id)->with('success', 'Employee updated successfully');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = EmployeeModel::join('employees_asset', 'employees_asset.id_emp','=','employees.id')
        ->where('employees.id', $id)->where('employees_asset.id_emp', $id);
        $employee->delete();

        return redirect()->route('employee.index')
        ->with('success', 'Employee deleted successfully');
    }


public function save($request, $save,$id=0)
{
    //  dd($request);
     $file=EmployeeModel::where('id',$id)->first();
     if($request->file()!=null){
        $data = [
            'emp_name'       => $request->input('emp_name'),
            'emp_email'      => $request->input('emp_email'),
            'emp_status'     => 'Active',
            'emp_phone'      => $request->input('emp_phone'),
            'emp_address'    => $request->input('emp_address'),
            'emp_birthplace' => $request->input('emp_birthplace'),
            'emp_birthdate'  => $request->input('emp_birthdate'),
            'emp_nip'        => $request->input('emp_nip'),
            'position'       => $request->input('position'),
            'spv_id'         => $request->input('spv_id'),
            'gender'         => $request->input('gender'),
            'tgl_bergabung'  => $request->input('tgl_bergabung'),
            'division_id'    => $request->input('division_id')==null? 0 : $request->input('division_id'),
            'bank_acc'       => $request->input('bank_acc'),
            'no_bank_acc'    => $request->input('no_bank_acc'),
            'nama_bank_acc'  => $request->input('nama_bank_acc'),
            'tgl_resign'     => $request->input('tgl_resign'),
            'division_name'  => $request->input('division_name'),
            'cabang_id'      => $request->input('cabang_id'),
            'alamat_domisili'=> $request->input('alamat_domisili'),
            'email_personal' => $request->input('email_personal'),
            'doc_contract'   => $request->has('doc_contract') ? Storage::disk('public')->put('document_public', $request->file('doc_contract')) : null,
            'doc_ktp'        => $request->has('doc_ktp') ? Storage::disk('public')->put('document_public', $request->file('doc_ktp')) : null,
            'doc_npwp'       => $request->has('doc_npwp') ? Storage::disk('public')->put('document_public', $request->file('doc_npwp')) : null,
            'doc_bank'       => $request->has('doc_bank') ? Storage::disk('public')->put('document_public', $request->file('doc_bank')) : null,
            'doc_pendidikan' => $request->has('doc_pendidikan') ? Storage::disk('public')->put('document_public', $request->file('doc_pendidikan')) : null,
            'doc_aktaNikah'  => $request->has('doc_aktaNikah') ? Storage::disk('public')->put('document_public', $request->file('doc_aktaNikah')) : null,
            'doc_kk'         => $request->has('doc_kk') ? Storage::disk('public')->put('document_public', $request->file('doc_kk')) : null,
            'doc_vaksin'     => $request->has('doc_vaksin') ? Storage::disk('public')->put('document_public', $request->file('doc_vaksin')) : null,
            'status_employee'=> $request->st_emp,            
            $save . '_by'    => Auth::id(),
        ];
        $qry = $save == 'update' ?EmployeeModel::where('id', $id)->update($data) : EmployeeModel::create($data);
        if ($qry) {
            $namaproduk = $request->namaproduk;
            foreach ($namaproduk as $item => $v) {
                if($request->namaproduk[$item]!=null){
                $assets=[
                    'id_emp'        => $qry->id,
                    'namaproduk'    => $request->namaproduk[$item],
                    'jumlah'        => $request->jumlah[$item],
                    $save.'_by'     => Auth::id(),
                ];
                $qry2 = EmployeeAssetModel::create($assets);
                if($qry2){
                    return redirect('hrm/employee/')->with('success', 'Employee Created successfully');
                }
                }
            }
        }

        if($request->has('nama_dok_add'))
        {
            $nama_dok = $request->nama_dok_add;
            foreach($nama_dok as $doks => $q)
            {
                $sv_doks = [
                    'id_emp'         => $qry->id,
                    'nama_dokumen'   => $request->nama_dok_add[$doks],
                    'dok_emp'        => !empty($request->file('dok_emp_add')[$doks]) ? Storage::disk('public')->put('document_public', $request->file('dok_emp_add')[$doks]) : null,
                    'created_at'     => Carbon::now(),
                    'created_by'     => Auth::id(),
                ];
             $sv  = EmployeeDokumen::create($sv_doks);
            }
        }
        return redirect('hrm/employee/')->with('success', 'Employee Created successfully');
    } else{
        $data = [
            'emp_name'       => $request->input('emp_name'),
            'emp_email'      => $request->input('emp_email'),
            'emp_phone'      => $request->input('emp_phone'),
            'emp_address'    => $request->input('emp_address'),
            'emp_status'     => 'Active',
            'emp_birthplace' => $request->input('emp_birthplace'),
            'emp_birthdate'  => $request->input('emp_birthdate'),
            'gender'         => $request->input('gender'),
            'tgl_bergabung'  => $request->input('tgl_bergabung'),
            'bank_acc'       => $request->input('bank_acc'),
            'no_bank_acc'    => $request->input('no_bank_acc'),
            'nama_bank_acc'  => $request->input('nama_bank_acc'),
            'tgl_resign'     => $request->input('tgl_resign'),
            'emp_nip'        => $request->input('emp_nip'),
            'alamat_domisili'=> $request->input('alamat_domisili'),
            'email_personal' => $request->input('email_personal'),
            'position'       => $request->input('position'),
            'spv_id'         => $request->input('spv_id'),
            'division_name'  => $request->input('division_name'),
            'division_id'    => $request->input('division_id')==null? 0 : $request->input('division_id'),
            'cabang_id'      => $request->input('cabang_id'),
            'status_employee'=> $request->st_emp,
            $save . '_by'    => Auth::id(),
        ];
        $qry = $save == 'update' ?EmployeeModel::where('id', $id)->update($data) : EmployeeModel::create($data);
            if ($qry) {
            $namaproduk = $request->namaproduk;
                foreach ($namaproduk as $item => $v) {
                    if($request->namaproduk[$item]!=null){
                    $assets=[
                        'id_emp'        => $qry->id,
                        'namaproduk'    => $request->namaproduk[$item],
                        'jumlah'        => $request->jumlah[$item],
                        $save.'_by'     => Auth::id(),
                    ];
                    $qry2 = EmployeeAssetModel::create($assets);
                    }
                }
            }

        if($request->has('nama_dok_add'))
        {
            $nama_dok = $request->nama_dok_add;
            foreach($nama_dok as $doks => $q)
            {
                $sv_doks = [
                    'id_emp'         => $qry->id,
                    'nama_dokumen'   => $request->nama_dok_add[$doks],
                    'dok_emp'        => !empty($request->file('dok_emp_add')[$doks]) ? Storage::disk('public')->put('document_public', $request->file('dok_emp_add')[$doks]) : null,
                    'created_at'     => Carbon::now(),
                    'created_by'     => Auth::id(),
                ];
             $sv  = EmployeeDokumen::create($sv_doks);
            }
        }
        return redirect('hrm/employee/')->with('success', 'Employee Created successfully');
    }
}    



public function saveUpdate($request, $save,$id=0)
{
    $emp         = EmployeeModel::where('id',$id)->first();
    $file        = EmployeeModel::where('id',$id)->first();
    $emp_dokumen = EmployeeDokumen::where('id_emp', $request->id)->first();
    if($request->file()!=null){
        $data = [
            'emp_name'       => $request->input('emp_name'),
            'emp_email'      => $request->input('emp_email'),
            'emp_phone'      => $request->input('emp_phone'),
            'emp_address'    => $request->input('emp_address'),
            'emp_birthplace' => $request->input('emp_birthplace'),
            'emp_birthdate'  => $request->input('emp_birthdate'),
            'bank_acc'       => $request->input('bank_acc'),
            'no_bank_acc'    => $request->input('no_bank_acc'),
            'nama_bank_acc'  => $request->input('nama_bank_acc'),
            'emp_nip'        => $request->input('emp_nip'),
            'position'       => $request->input('position'),
            'emp_status'     => $request->input('emp_status'),
            'gender'         => $request->input('gender'),
            'division_name'  => $request->input('division_name'),
            'spv_id'         => $request->input('spv_id'),
            'tgl_resign'     => $request->input('tgl_resign'),
            'alamat_domisili'=> $request->input('alamat_domisili'),
            'tgl_bergabung'  => $request->input('tgl_bergabung'),
            'email_personal' => $request->input('email_personal'),
            'division_id'    => $request->input('division_id')== null ? $emp->division_id :$request->input('division_id'),
            'cabang_id'      => $request->input('cabang_id'),
            'doc_contract'   => $request->has('doc_contract') ? Storage::disk('public')->put('document_public', $request->file('doc_contract')) : $file->doc_contract,
            'doc_ktp'        => $request->has('doc_ktp') ? Storage::disk('public')->put('document_public', $request->file('doc_ktp')) : $file->doc_ktp,
            'doc_npwp'       => $request->has('doc_npwp') ? Storage::disk('public')->put('document_public', $request->file('doc_npwp')) : $file->doc_npwp,
            'doc_bank'       => $request->has('doc_bank') ? Storage::disk('public')->put('document_public', $request->file('doc_bank')) : $file->doc_bank,
            'doc_pendidikan' => $request->has('doc_pendidikan') ? Storage::disk('public')->put('document_public', $request->file('doc_pendidikan')) : $file->doc_pendidikan,
            'doc_aktaNikah'  => $request->has('doc_aktaNikah') ? Storage::disk('public')->put('document_public', $request->file('doc_aktaNikah')) : $file->doc_aktaNikah,
            'doc_kk'         => $request->has('doc_kk') ? Storage::disk('public')->put('document_public', $request->file('doc_kk')) : $file->doc_kk,
            'doc_vaksin'     => $request->has('doc_vaksin') ? Storage::disk('public')->put('document_public', $request->file('doc_vaksin')) : $file->doc_vaksin,
            'status_employee'=> $request->st_emp,
            $save . '_by'    => Auth::id(),
        ];
        // dd($data);
        $qry = $save == 'created' ? EmployeeModel::create($data) : EmployeeModel::where('id', $id)->update($data);
            if($request->has('namaproduk')){
                $casset = EmployeeAssetModel::where('id_emp', $request->id_emp)->get()->count();
                $count  = count($request->namaproduk);
                if ($request->has('namaproduk_add') || $count>$casset || $count<$casset)
                {
                    $namaproduk = $request->namaproduk;
                    $adds       = $request->namaproduk_add;

                    foreach ($adds as $add => $v) {
                        $assets=[
                            'id_emp'        => $request->id,
                            'namaproduk'    => $request->namaproduk[$add],
                            'jumlah'        => $request->jumlah[$add],
                            $save.'_by'     => Auth::id(),
                        ];
                    }
                    $qry2 = EmployeeAssetModel::create($assets);

                    foreach ($namaproduk as $item => $v) {
                        $up_assets=[
                            'id_emp'        => $request->id,
                            'namaproduk'    => $request->namaproduk[$item],
                            'jumlah'        => $request->jumlah[$item],
                            $save.'_by'     => Auth::id(),
                        ];
                    }
                    $qry2 = EmployeeAssetModel::where('id', $request->id_asset[$item])->update($up_assets);
            
                }else{
                    //update dan tambah data yang belum terisi
                    $namaproduk = $request->namaproduk;
                    $emp_asset = EmployeeAssetModel::where('id_emp', $id)->first();
                    foreach ($namaproduk as $item => $v) {
                    $assets=[
                        'id_emp'        => $id,
                        'namaproduk'    => $request->namaproduk[$item],
                        'jumlah'        => $request->jumlah[$item],
                        $save.'_by'     => Auth::id(),
                    ];
                    $qry2 = $emp_asset==null ? EmployeeAssetModel::create($assets) : EmployeeAssetModel::where('id',$request->id_asset[$item])->update($assets);
                    }
                }
            }

            if($request->has('nama_dok'))
            {
                // dd($request);
                $cdokks = EmployeeDokumen::where('id_emp', $request->id_emp)->get()->count();
                $counts  = count($request->nama_dok);
                if($request->has('nama_dok_add') || $counts<$cdokks)
                {
                    $dokumen     = $request->nama_dok;
                    $adds_dokumen= $request->nama_dok_add;
                    foreach($adds_dokumen as $doks=> $n)
                    {
                        $sv_doks = [
                            'id_emp'         => $request->id,
                            'nama_dokumen'   => $request->nama_dok_add[$doks],
                            'dok_emp'        => !empty($request->file('dok_emp_add')[$doks]) ? Storage::disk('public')->put('document_public', $request->file('dok_emp_add')[$doks]) : null,
                            'created_at'     => Carbon::now(),
                            'created_by'     => Auth::id(),
                        ];
                        $sv  = EmployeeDokumen::create($sv_doks);
                    }

                    foreach($dokumen as $up => $n)
                    {
                        $datas_dok = EmployeeDokumen::where([['id_emp', $request->id], ['id', $request->id_dokumen[$up]]])->first();
                        $datas =[
                            'id_emp'         => $request->id,
                            'nama_dokumen'   => $request->nama_dok[$up],
                            'dok_emp'        => !empty($request->file('dok_emp')[$up]) ? Storage::disk('public')->put('document_public', $request->file('dok_emp')[$up]) : $datas_dok->dok_emp,
                            'updated_at'     => Carbon::now(),
                            'updated_by'     => Auth::id(),
                        ];
                    }
                    $query = EmployeeDokumen::where('id', $request->id_dokumen[$up])->update($datas);
                }
                else if($emp_dokumen==null){
                    $dokumen     = $request->nama_dok;
                    foreach($dokumen as $up => $n)
                        {
                        $datas_dok = EmployeeDokumen::where([['id_emp', $request->id], ['id', $request->id_dokumen[$up]]])->first();
                        $datay =[
                            'id_emp'         => $request->id,
                            'nama_dokumen'   => $request->nama_dok[$up],
                            'dok_emp'        => !empty($request->file('dok_emp')[$up]) ? Storage::disk('public')->put('document_public', $request->file('dok_emp')[$up]) : null,
                            'created_at'     => Carbon::now(),
                            'created_by'     => Auth::id(),
                        ];
                        $ag_qry = EmployeeDokumen::create($datay);
                        }
                }else{
                    $dokumen     = $request->nama_dok;
                    foreach($dokumen as $up => $n)
                    {
                        $datas_dok = EmployeeDokumen::where([['id_emp', $request->id], ['id', $request->id_dokumen[$up]]])->first();
                        $emp_data =[
                            'id_emp'         => $request->id,
                            'nama_dokumen'   => $request->nama_dok[$up],
                            'dok_emp'        => !empty($request->file('dok_emp')[$up]) ? Storage::disk('public')->put('document_public', $request->file('dok_emp')[$up]) : $datas_dok->dok_emp,
                            'created_at'     => Carbon::now(),
                            'created_by'     => Auth::id(),
                        ];
                        $ag_qry = EmployeeDokumen::where('id', $request->id_dokumen[$up])->update($emp_data);
                        }
                }
        }else if($emp_dokumen==null && $request->has('nama_dok_add')){
            $adds     = $request->nama_dok_add;
            foreach($adds as $adds => $n)
                {
                $datay =[
                    'id_emp'         => $request->id,
                    'nama_dokumen'   => $request->nama_dok_add[$adds],
                    'dok_emp'        => !empty($request->file('dok_emp_add')[$adds]) ? Storage::disk('public')->put('document_public', $request->file('dok_emp_add')[$adds]) : null,
                    'created_at'     => Carbon::now(),
                    'created_by'     => Auth::id(),
                ];
                $ag_qry = EmployeeDokumen::create($datay);
                }
        }
        return redirect('hrm/employee/')->with('success', 'Employee Created successfully');

//Tanpa File

    } else{
            $emp=EmployeeModel::where('id',$id)->first();
            $data = [
                'emp_name'       => $request->input('emp_name'),
                'emp_email'      => $request->input('emp_email'),
                'emp_status'     => $request->input('emp_status'),
                'emp_phone'      => $request->input('emp_phone'),
                'emp_address'    => $request->input('emp_address'),
                'tgl_bergabung'  => $request->input('tgl_bergabung'),
                'bank_acc'       => $request->input('bank_acc'),
                'no_bank_acc'    => $request->input('no_bank_acc'),
                'nama_bank_acc'  => $request->input('nama_bank_acc'),
                'tgl_resign'     => $request->input('tgl_resign'),
                'gender'         => $request->input('gender'),
                'emp_birthplace' => $request->input('emp_birthplace'),
                'division_name'  => $request->input('division_name'),
                'alamat_domisili'=> $request->input('alamat_domisili'),
                'email_personal' => $request->input('email_personal'),
                'emp_birthdate'  => $request->input('emp_birthdate'),
                'emp_nip'        => $request->input('emp_nip'),
                'position'       => $request->input('position'),
                'spv_id'         => $request->input('spv_id'),
                'division_id'    => $request->input('division_id')== null ? $emp->division_id :$request->input('division_id'),
                'cabang_id'      => $request->input('cabang_id'),
                'status_employee'=> $request->st_emp,
                $save . '_by'    => Auth::id(),
            ];
            $qry = $save == 'created' ? EmployeeModel::create($data) : EmployeeModel::where('id', $id)->update($data);
            if($request->has('namaproduk'))
            {
                $casset = EmployeeAssetModel::where('id_emp', $request->id_emp)->get()->count();
                $count  = count($request->namaproduk);
                    if ($request->has('namaproduk_add') || $count>$casset || $count<$casset)
                    {
                        $namaproduk = $request->namaproduk;
                        $adds       = $request->namaproduk_add;

                        foreach ($adds as $add => $v) {
                        $assets=[
                            'id_emp'        => $request->id,
                            'namaproduk'    => $request->namaproduk[$add],
                            'jumlah'        => $request->jumlah[$add],
                            $save.'_by'     => Auth::id(),
                        ];
                        }
                        $qry2 = EmployeeAssetModel::create($assets);

                        foreach ($namaproduk as $item => $v) {
                        $up_assets=[
                            'id_emp'        => $request->id,
                            'namaproduk'    => $request->namaproduk[$item],
                            'jumlah'        => $request->jumlah[$item],
                            $save.'_by'     => Auth::id(),
                        ];
                        }
                        $qry2 = EmployeeAssetModel::where('id', $request->id_asset[$item])->update($up_assets);
                }else{
                //update dan tambah data yang belum terisi
                    $namaproduk = $request->namaproduk;
                    $emp_asset = EmployeeAssetModel::where('id_emp', $id)->first();
                        foreach ($namaproduk as $item => $v) {
                        $assets=[
                            'id_emp'        => $id,
                            'namaproduk'    => $request->namaproduk[$item],
                            'jumlah'        => $request->jumlah[$item],
                            $save.'_by'     => Auth::id(),
                        ];
                        $qry2 = $emp_asset==null ? EmployeeAssetModel::create($assets) : EmployeeAssetModel::where('id',$request->id_asset[$item])->update($assets);
                        }
                    }
            }

            if($request->has('nama_dok'))
            {
                    $cdokks = EmployeeDokumen::where('id_emp', $request->id_emp)->get()->count();
                    $counts  = count($request->nama_dok);
                    if($request->has('nama_dok_add') || $counts<$cdokks)
                    {
                        $dokumen     = $request->nama_dok;
                        $adds_dokumen= $request->nama_dok_add;
                        foreach($adds_dokumen as $doks=> $n)
                        {
                            $sv_doks = [
                                'id_emp'         => $request->id,
                                'nama_dokumen'   => $request->nama_dok_add[$doks],
                                'dok_emp'        => !empty($request->file('dok_emp_add')[$doks]) ? Storage::disk('public')->put('document_public', $request->file('dok_emp_add')[$doks]) : null,
                                'created_at'     => Carbon::now(),
                                'created_by'     => Auth::id(),
                            ];
                            $sv  = EmployeeDokumen::create($sv_doks);
                        }

                        foreach($dokumen as $up => $n)
                        {
                            $datas_dok = EmployeeDokumen::where([['id_emp', $request->id], ['id', $request->id_dokumen[$up]]])->first();
                            $datas =[
                                'id_emp'         => $request->id,
                                'nama_dokumen'   => $request->nama_dok[$up],
                                'dok_emp'        => !empty($request->file('dok_emp')[$up]) ? Storage::disk('public')->put('document_public', $request->file('dok_emp')[$up]) : $datas_dok->dok_emp,
                                'updated_at'     => Carbon::now(),
                                'updated_by'     => Auth::id(),
                            ];
                        }
                        $query = EmployeeDokumen::where('id', $request->id_dokumen[$up])->update($datas);
                    }
                    else if($emp_dokumen==null){
                        $dokumen     = $request->nama_dok;
                        foreach($dokumen as $up => $n)
                            {
                            $datas_dok = EmployeeDokumen::where([['id_emp', $request->id], ['id', $request->id_dokumen[$up]]])->first();
                            $datay =[
                                'id_emp'         => $request->id,
                                'nama_dokumen'   => $request->nama_dok[$up],
                                'dok_emp'        => !empty($request->file('dok_emp')[$up]) ? Storage::disk('public')->put('document_public', $request->file('dok_emp')[$up]) : null,
                                'created_at'     => Carbon::now(),
                                'created_by'     => Auth::id(),
                            ];
                            $ag_qry = EmployeeDokumen::create($datay);
                            }
                    }else{
                        $dokumen     = $request->nama_dok;
                        foreach($dokumen as $up => $n)
                        {
                            $datas_dok = EmployeeDokumen::where([['id_emp', $request->id], ['id', $request->id_dokumen[$up]]])->first();
                            $emp_data =[
                                'id_emp'         => $request->id,
                                'nama_dokumen'   => $request->nama_dok[$up],
                                'dok_emp'        => !empty($request->file('dok_emp')[$up]) ? Storage::disk('public')->put('document_public', $request->file('dok_emp')[$up]) : $datas_dok->dok_emp,
                                'created_at'     => Carbon::now(),
                                'created_by'     => Auth::id(),
                            ];
                            $ag_qry = EmployeeDokumen::where('id', $request->id_dokumen[$up])->update($emp_data);
                            }
                        }
            }else if($emp_dokumen==null && $request->has('nama_dok')){
            $dokumen     = $request->nama_dok;
            foreach($dokumen as $up => $n)
                {
                $datas_dok = EmployeeDokumen::where([['id_emp', $request->id], ['id', $request->id_dokumen[$up]]])->first();
                $datay =[
                    'id_emp'         => $request->id,
                    'nama_dokumen'   => $request->nama_dok[$up],
                    'dok_emp'        => !empty($request->file('dok_emp')[$up]) ? Storage::disk('public')->put('document_public', $request->file('dok_emp')[$up]) : null,
                    'created_at'     => Carbon::now(),
                    'created_by'     => Auth::id(),
                ];
                $ag_qry = EmployeeDokumen::create($datay);
                }
        }       
    }
    return redirect('hrm/employee/')->with('success', 'Employee Created successfully');
} 



    public function ajax_data(Request $request)
     {
        $mine = getUserEmp(Auth::id());
        $div=$request->session()->put('division_id', $mine->division_id);
        
        if($mine->division_id == 7 || $mine->division_id == 8 || $mine->division_id == 4 || $mine->id == 50){
            return $this->ajax_dataManagement($request);
        }
        else{
        $columns = array(
             0 => 'emp_name',
             1 => 'emp_email',
             2 => 'emp_phone',
             3 => 'emp_nip',
             4 => 'position',
             5 => 'emp_status',
             6 => 'id'
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $employee_count = EmployeeModel::where('id', $mine->id_emp);
         $totalData     = $employee_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = EmployeeModel::select('*')->where('id', $mine->id_emp)
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
         } else {
             $search        = $request->input('search')['value'];
             $posts         = EmployeeModel::where('id', $mine->id_emp)->where('emp_name', 'like', '%' . $search . '%')
                 ->orWhere('emp_address', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
             $totalFiltered = EmployeeModel::where('id', $mine->id_emp)->where('emp_name', 'like', '%' . $search . '%')
                 ->orWhere('emp_address', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->count();
         }

         $data = [];
        if (!empty($posts)) {
             foreach ($posts as $post) {
                 $data[] = [
                     'emp_status'     => $post->emp_status,
                     'emp_name'       => $post->emp_name,
                     'emp_email'      => $post->emp_email,
                     'emp_phone'      => $post->emp_phone,
                     'emp_nip'        => $post->emp_nip,
                     'position'       => $post->position,
                     'user'           => "employee",
                     'leave'      => checkLeave($post->id). " Days",
                     'created_at'     => Carbon::parse($post->created_at)->format('Y-m-d'),
                     'id'             => $post->id,
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


    public function ajax_dataManagement(Request $request)
     {
        $columns = array(
             0 => 'emp_name',
             1 => 'emp_email',
             2 => 'emp_phone',
             3 => 'emp_nip',
             4 => 'position',
             5 => 'emp_status',
             6 => 'id'
         );
 
         $limit = $request->input('length');
         $start = $request->input('start');
         $order = $columns[$request->input('order')[0]['column']];
         $dir   = $request->input('order')[0]['dir'];
 
         $employee_count    = EmployeeModel::all();
         $totalData     = $employee_count->count();
         $totalFiltered = $totalData;
 
         if (empty($request->input('search')['value'])) {
             $posts = EmployeeModel::select('*')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
         } else {
             $search        = $request->input('search')['value'];
             $posts         = EmployeeModel::where('emp_name', 'like', '%' . $search . '%')
                 ->orWhere('emp_address', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->get();
             $totalFiltered = EmployeeModel::where('emp_name', 'like', '%' . $search . '%')
                 ->orWhere('emp_address', 'like', '%' . $search . '%')
                 ->orderby($order, $dir)->offset($start)->limit($limit)->count();
         }
         
         $data = [];
        if (!empty($posts)) {
             foreach ($posts as $post) {
                 $data[] = [
                     'emp_status' => $post->emp_status,
                     'emp_name'   => $post->emp_name,
                     'emp_email'  => $post->emp_email,
                     'emp_phone'  => $post->emp_phone,
                     'emp_nip'    => $post->emp_nip,
                     'position'   => $post->position,
                     'user'       => "hrd",
                     'leave'      => checkLeave($post->id). " Days",
                     'created_at' => Carbon::parse($post->created_at)->format('Y-m-d'),
                     'id'         => $post->id,
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

     public function document_view($id)
    {
        // dd($id);
        $emp=EmployeeModel::where('id', $id)->first();
        return view ('employee.show',[
            'data'  => $emp,
        ]);
    }

     public function getDate($value)
     {
         return Carbon::parse($value)->format('d/m/Y');
     }  
     
     
     public function AllDivision()
    {

        $data = Role_division::all();
        $arr = array();
        foreach ($data as $reg) {
           
            
            $arr[$reg->id] = $reg->div_name;
        }
        return $arr;
    }



    public function getStatusEmp()
    {

        $data = EmployeeStatus::all();
        $arr = array();
        foreach ($data as $reg) {
            $arr[$reg->id] = $reg->code. ' - '.$reg->note ;
        }
        return $arr;
    }



    public function AllCabang()
    {

        $data = Role_cabang::all();
        $arr = array();
        foreach ($data as $reg) {
        
            
            $arr[$reg->id] = $reg->cabang_name;
        }
        return $arr;
    }

    public function AllSpv()
    {
        $mine = getUserEmp(Auth::id());
        $data = EmployeeModel::where('emp_status',"Active")->get();
        $arr = array();
        foreach ($data as $reg) {
        $arr[$reg->id] = $reg->emp_name;
        }
        return $arr;
    }

function office_assets(Request $request)
    {
        return view('employee.add_asset', [
        'n_equ'  => $request->n_equ,
        ]);
    }

function del_assets(Request $request)
    {
        // dd($request);
        $del = EmployeeAssetModel::findorfail($request->id);
        $del->delete();
    }

function add_dokumen(Request $request)
    {
        // dd($request);
        return view('employee.add_dokumens', [
        'n_equ'  => $request->n_equ,
        ]);
    }
}