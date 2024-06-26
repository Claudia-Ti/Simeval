<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonitoringCriteriaModel;
use App\Models\PeriodModel;
use App\Models\OfficerModel;
use Auth,DB,Validator,Session;
use App\Http\Controllers\DashboardController;

class MonitoringCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $period = PeriodModel::get();
        $query = MonitoringCriteriaModel::query();
        $query = $this->applyFilter($query);
        
        $data = $query->leftJoin('table_period','table_period.id','table_monitoring_criteria.id_period')
                    ->leftJoin('table_officer','table_officer.id','table_monitoring_criteria.id_officer')
                    ->select('table_monitoring_criteria.*','table_period.p_name as period_name','table_officer.o_name as officerName','table_officer.position as officerPosition','table_officer.unit as officerUnit')
                    ->orderBy('table_monitoring_criteria.id_period','DESC')->paginate(15);
        $pageName = "Daftar Kriteria Untuk Monitoring";
        return view('pages.monitoring_criteria/index',compact('data','pageName','period'));
    }
    public function createFilter(Request $req){
        if(session('filter-monitoring-criteria')){
            Session::forget('filter-monitoring-criteria');
        }
        $filter = array();
        $filter['key'] = $req->key?$req->key:NULL;
        $filter['period'] = $req->period?$req->period:NULL;
        if(count($filter)>0){
            session(['filter-monitoring-criteria'=>$filter]);
        }
        return redirect()->route('monitoring_criteria.index');
    }
    protected function applyFilter($query){
        $filter = session('filter-monitoring-criteria');
        if($filter){
            if(isset($filter['key'])){
                $query->where('table_monitoring_criteria.mc_name','LIKE','%'.$filter['key'].'%');
            }
            if(isset($filter['period'])){
                $query->where('table_period.id',$filter['period']);
            }
        }
        return $query;
    }
    public function clearFilter(){
        if(session('filter-monitoring-criteria')){
            Session::forget('filter-monitoring-criteria');
        }
        return redirect()->route('monitoring_criteria.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     
    public function create()
    {
        if(!(new DashboardController)->checkPermission(['Admin'])){
            return redirect('monitoring_criteria')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        $pageName = "Tambah data Kriteria Monitoring";
        $period = PeriodModel::get();
        $officer = OfficerModel::get();
        return view('pages.monitoring_criteria/create',compact('pageName','period','officer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function checkWeight($period,$officer,$value,$kriteria=null){
        $query = MonitoringCriteriaModel::query();
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
            return redirect('monitoring_criteria')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        $validator = Validator::make($request->all(),[
            'mc_name'=>'required',
            'id_period'=>'required',
            'weight'=>'required',
        ]);
        if($validator->fails()){
            return redirect()->route('monitoring_criteria.create')->with('error','Data tidak lengkap!')->withInput();
        }
        try{
            $check = $this->checkWeight($request->id_period,$request->id_officer,$request->weight);
            if($check['status']){
                DB::beginTransaction();
                $data = MonitoringCriteriaModel::create([
                    'mc_name'=>$request->mc_name,
                    'description'=>$request->description??'-',
                    'id_period'=>$request->id_period,
                    'weight'=>$request->weight,
                    'id_officer'=>$request->id_officer
                ]);
                DB::commit();
                if($data){
                    return redirect()->route('monitoring_criteria.index')->with('success','Data berhasil di tambahkan');
                }
                DB::rollBack();
                return redirect()->route('monitoring_criteria/create')->with('error','Data Gagal di tambahkan!')->withInput();
            }
            return redirect()->route('monitoring_criteria.create')->with('error','Data bobot melebihi 100, sisa bobot yang bisa di tambahkan adalah '.(100-$check["weights"]))->withInput($request->all());
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('monitorin_criteria/create')->with('error','Terjadi kesalahan, silahkan coba lagi!')->withInput();
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
            return redirect('monitoring_criteria')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        $data = MonitoringCriteriaModel::where('id',$id)->first();
        if($data){
            $pageName = "Ubah data Kriteria Monitoring : ".$data->mc_name;
            $period = PeriodModel::get();
            $officer = OfficerModel::get();
            return view('pages.monitoring_criteria.edit',compact('data','pageName','period','officer'));
        }
        return redirect('monitoring_criteria')->with('error','Data tidak ditemukan!');
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
            return redirect('monitoring_criteria')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        $validator = Validator::make($request->all(),[
            'mc_name'=>'required',
            'id_period'=>'required',
            'weight'=>'required',
        ]);
        if($validator->fails()){
            return redirect()->route('monitoring_criteria.edit',$id)->with('error','Data tidak lengkap!')->withInput();
        }
        try{
            $check = $this->checkWeight($request->id_period,$request->id_officer,$request->weight,$id);
            if($check['status']){
                DB::beginTransaction();
                $updated = MonitoringCriteriaModel::where('id',$id)->update([
                    'mc_name'=>$request->mc_name,
                    'description'=>$request->description??'-',
                    'id_period'=>$request->id_period,
                    'weight'=>$request->weight,
                    'id_officer'=>$request->id_officer
                ]);
                DB::commit();
                $data = MonitoringCriteriaModel::where('id',$id)->first();
                if($updated){
                    return redirect()->route('monitoring_criteria.index')->with('success','Data berhasil di ubah!');
                }
                DB::rollBack();
                return redirect()->route('monitoring_criteria/'.$id.'/edit')->with('error','Data gagal di ubah!')->withInput();
            }
            return redirect()->route('monitoring_criteria/'.$id.'/$edit')->with('error','Data bobot melebihi 100, sisa bobot yang bisa ditambahkan adalah '.(100-$check['weights']))->withInput($request->all());
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('monitoring_criteria/'.$id.'edit')->with('error','Terjadi kesalahan, silahkan coba lagi!')->withInput();
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
            return redirect('monitoring_criteria')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        try{
            DB::beginTransaction();
            $deleted = MonitoringCriteriaModel::where('id',$id)->delete();
            DB::commit();
            if($deleted){
                return redirect()->route('monitoring_criteria.index')->with('success','Data berhasil di hapus!');
            }
            DB::rollBack();
            return redirect()->route('monitoring_criteria.index')->with('error','Data gagal di hapus!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('monitoring_criteria.index')->with('error','Data gagal di hapus!, Terjadi kesalahan');
        }
    }
    public function apiGetMonCriteriaByPeriod($idPeriod){
        $data = MonitoringCriteriaModel::where('id_period',$idPeriod)->get();
        return response()->json(['status'=>'success','data'=>$data],200);
    }
}
