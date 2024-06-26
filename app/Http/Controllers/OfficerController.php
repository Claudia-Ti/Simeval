<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OfficerModel;
use App\Models\PeriodModel;
use App\Models\PerformanceRankModel;
use App\Models\PerformanceReportModel;
use App\Models\MonitoringReportModel;
use App\Http\Controllers\DashboardController;

use Auth,DB,Validator,Session;

class OfficerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $query = OfficerModel::query();
        $filter = session('filter-officer');
        if($filter){
            $query->where('o_name','LIKE','%'.$filter.'%');
        }
        $data = $query->orderBy('created_at','DESC')->paginate(15);
        $pageName = "Daftar Pegawai";
        return view('pages.officer.index',compact('data','pageName'));
    }
    public function search(Request $req){
        if(session('filter-officer')){
            Session::forget('filter-officer');
        }
        $filter = $req->name??NULL;
        if($filter!=NULL){
            session(['filter-officer'=>$filter]);
        }
        return redirect('officer');
    }
    public function clearFilter(){
        if(session('filter-officer')){
            Session::forget('filter-officer');
        }
        return redirect('officer');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function create()
    {
        if(!(new DashboardController)->checkPermission(['Admin'])){
            return redirect('officer')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        $pageName = "Tambah data kasat";
        return view('pages.officer.create',compact('pageName'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!(new DashboardController)->checkPermission(['Admin'])){
            return redirect('officer')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        $validator = Validator::make($request->all(),[
            'o_name'=>'required',
            'position'=>'required',
            'unit'=>'required',
            'gender'=>'required',
            'dob'=>'required'
        ]);
        if($validator->fails()){
            return redirect()->route('officer.create')->with('error','Data tidak lengkap!')->withInput();
        }
        try{
            DB::beginTransaction();
            $data = OfficerModel::create([
                'o_name'=>$request->o_name,
                'position'=>$request->position,
                'unit'=>$request->unit,
                'gender'=>$request->gender,
                'dob'=>date('Y-m-d',strtotime($request->dob))
            ]);
            DB::commit();
            if($data){
                return redirect()->route('officer.index')->with('success','Data berhasil di tambahkan!');
            }
            DB::rollBack();
            return redirect()->route('officer.create')->with('error','Data gagal di tambahkan!')->withInput();
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('officer.create')->with('error','Terjadi kesalahan, silahkan coba lagi!')->withInput();
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
        $data = OfficerModel::where('id',$id)->first();
        $period = PeriodModel::get();
        $performanceRank = PeformanceRankModel::where('id_officer',$id)->get();
        $performanceReport = PerformanceReportModel::where('id_officer',$id)->get();
        $mon_report = MonitoringReportModel::where('id_officer',$id)->get();
        $pageName = "Detail Pegawai : ".$data->o_name;
        return view('pages.officer.detail',compact('data','period','performanceRank','performanceReport','mon_report','pageName'));
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
            return redirect('officer')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        $data = OfficerModel::where('id',$id)->first();
        if($data){
            $pageName = "Ubah data pegawai : ".$data->o_name;
            return view('pages.officer.edit',compact('data','pageName'));
        }
        return redirect('officer')->with('error','Data tidak ditemukan!');
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
            return redirect('officer')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        $validator = Validator::make($request->all(),[
            'o_name'=>'required',
            'position'=>'required',
            'unit'=>'required',
            'gender'=>'required',
            'dob'=>'required'
        ]);
        if($validator->fails()){
            return redirect()->route('officer.edit',$id)->with('error','Data tidak lengkap!')->withInput();
        }
        try{
            DB::beginTransaction();
            $updated = OfficerModel::where('id',$id)->update([
                'o_name'=>$request->o_name,
                'position'=>$request->position,
                'unit'=>$request->unit,
                'gender'=>$request->gender,
                'dob'=>date('Y-m-d',strtotime($request->dob))
            ]);
            DB::commit();
            if($updated){
                return redirect()->route('officer.index')->with('success','Data berhasil di ubahkan!');
            }
            DB::rollBack();
            return redirect()->route('officer.edit',$id)->with('error','Data gagal di ubahkan!')->withInput();
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('officer.edit',$id)->with('error','Terjadi kesalahan, silahkan coba lagi!')->withInput();
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
            return redirect('officer')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        try{
            DB::beginTransaction();
            $deleted = OfficerModel::where('id',$id)->delete();
            DB::commit();
            if($deleted){
                return redirect()->route('officer.index')->with('success','Data berhasil di hapus');
            }
            DB::rollBack();
            return redirect()->route('officer.index')->with('error','Data gagal di hapus!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('officer.index')->with('error','Data gagal di hapus, terjadi kesalahan silahkan coba lagi!');
        }
    }
}
