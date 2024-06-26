<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth,Validator,DB,Session;
use App\Models\CriteriaModel;
use App\Models\PeriodModel;
use App\Models\OfficerModel;
use App\Http\Controllers\DashboardController;


class CriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $period = PeriodModel::get();
        $query = CriteriaModel::query();
        if(session('filter-criteria')){
            $query = $this->applyFilter($query);
        }
        $data = $query->leftJoin('table_period','table_period.id','table_criteria.id_period')
                        ->leftJoin('table_officer','table_officer.id','table_criteria.id_officer')
                        ->select('table_criteria.*','table_period.p_name as period','table_officer.o_name AS officerName','table_officer.position AS officerPosition','table_officer.unit as officerUnit')
                        ->orderBy('created_at','DESC')
                        ->paginate(15);
        $pageName = "Daftar Kriteria";
        return view('pages.criteria.index',compact('data','pageName','period'));
    }
    public function createFilter(Request $req){
        if(session('filter-criteria')){
            Session::forget('filter-criteria');
        }
        $filter = array();
        $filter['key'] = $req->key?$req->key:NULL;
        $filter['period'] = $req->period?$req->period:NULL;
        if(count($filter)>0){
            session(['filter-criteria'=>$filter]);
        }
        return redirect()->route('criteria.index');
    }
    protected function applyFilter($query){
        $filter = session('filter-criteria');
        if(isset($filter['key'])){
            $query->where('table_criteria.c_name','LIKE','%'.$filter['key'].'%');
        }
        if(isset($filter['period'])){
            $query->where('table_period.id',$filter['period']);
        }
        return $query;
    }
    public function clearFilter(){
        if(session('filter-criteria')){
            Session::forget('filter-criteria');
        }
        return redirect()->route('criteria.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function checkPermission($level){
    //     $user = Auth::user();
    //     foreach($level as $l){
    //         if($user->level!=$l){
    //             return false;
    //         }
    //     }
    //     return true;
    // }
    public function create()
    {
        // if(!(new DashboardController)->checkPermission(['Admin'])){
        //     return redirect('criteria')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        // }
        $pageName = "Tambah Data Kriteria";
        $period = PeriodModel::get();
        $officer = OfficerModel::get();
        return !(new DashboardController)->checkPermission(['Admin'])?redirect('criteria')->with('error','Anda tidak memiliki izin untuk askes halaman ini!'):view('pages.criteria.create',compact('pageName','period','officer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function checkWeight($period,$officer,$value,$kriteria=null){
        $query = CriteriaModel::query();
        if($kriteria!=null){
            $query->where('id','!=',$kriteria);
        }
        $weights = $query->where('id_period',$period)
        ->whereRaw('(id_officer="'.$officer.'" OR id_officer is NULL)')
                            ->sum('weight');
        $newW = $weights+$value;
        if($newW<=100){
            return ['status'=>true,'weights'=>$weights];
        }
        return ['status'=>false,'weights'=>$weights];
    }
    public function store(Request $request)
    {
        if(!(new DashboardController)->checkPermission(['Admin'])){
            return redirect('criteria')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        $validator = Validator::make($request->all(),[
            'c_name'=>'required',
            'c_type'=>'required',
            'weight'=>'required',
            'id_period'=>'required'
        ]);
        if($validator->fails()){
            return redirect()->route('criteria.create')->with('error','Data tidak lengkap!')->withInput();
        }
        try{
            $check = $this->checkWeight($request->id_period,$request->id_officer,$request->weight);
            if($check['status']){
                DB::beginTransaction();
                $data = CriteriaModel::create([
                    'c_name'=>$request->c_name,
                    'c_type'=>$request->c_type,
                    'weight'=>$request->weight,
                    'id_period'=>$request->id_period,
                    'id_officer'=>$request->id_officer
                ]);
                DB::commit();
                if($data){
                    return redirect()->route('criteria.index')->with('success','Data berhasil ditambahkan');
                }
                // DB::rollBack();
                return redirect()->route('criteria.create')->with('error','Data gagal ditambahkan!')->withInput();
            }else{
                return redirect()->route('criteria.create')->with('error','Data Bobot melebih 100, sisa bobot yang bisa di input adalah '.(100-$check['weights']))->withInput($request->all());
            }
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('criteria.create')->with('error','Terjadi kesalahan, silahkan coba lagi')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!(new DashboardController)->checkPermission(['Admin'])){
            return redirect('criteria')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        $data = CriteriaModel::where('id',$id)->first();
        if($data){
            $period = PeriodModel::get();
            $officer = OfficerModel::get();
            $pageName = "Ubah Data Criteria";
            return view('pages.criteria.edit',compact('data','period','pageName','officer'));
        }
        return redirect('criteria')->with('error','Data tidak di temukan!');
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
        if(!(new DashboardController)->checkPermission(['Admin'])){
            return redirect('criteria')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        $validator = Validator::make($request->all(),[
            'c_name'=>'required',
            'c_type'=>'required',
            'weight'=>'required',
            'id_period'=>'required',
        ]);
        if($validator->fails()){
            return redirect()->route('criteria.edit',$id)->with('error','Data tidak lengkap!')->withInput();
        }
        try{
            $check = $this->checkWeight($request->id_period,$request->officer,$request->weight,$id);
            if($check['status']){
                DB::beginTransaction();
                $update = CriteriaModel::where('id',$id)->update([
                    'c_name'=>$request->c_name,
                    'c_type'=>$request->c_type,
                    'weight'=>$request->weight,
                    'id_period'=>$request->id_period,
                    'id_officer'=>$request->id_officer
                ]);
                DB::commit();
                $data = CriteriaModel::where('id',$id)->first();
                if($update){
                    return redirect()->route('criteria.index')->with('success','Data berhasil ditambahkan')->with('data',$data);
                }
                DB::rollBack();
                return redirect()->route('criteria.edit',$id)->with('error','Data gagal ditambahkan!')->withInput();
            }else{
                return redriect()->route('criteria.edit',$id)->with('error','Data bobot melebihi 100, sisa bobot yang bisa di input adalah '.(100-$check['weight']))->withInput($request->all());
            }
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('criteria.edit',$id)->with('error','Terjadi kesalahan, silahkan coba lagi'.$e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!(new DashboardController)->checkPermission(['Admin'])){
            return redirect('criteria')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        $delete = CriteriaModel::where('id',$id)->delete();
        if($delete){
            return redirect()->route('criteria.index')->with('success','Data berhasil di hapus!');
        }
        return redirect()->route('criteria.index')->with('error','Data gagal dihapus!');
    }


    public function apiGetCriteriaByPeriod($idPeriod){
        $criteria = CriteriaModel::where('id_period',$idPeriod)->get();
        return response()->json(['status'=>'success','data'=>$criteria],200);
    }
}
