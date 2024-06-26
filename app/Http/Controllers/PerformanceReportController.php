<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerformanceReportModel;
use App\Models\CriteriaModel;
use App\Models\OfficerModel;
use App\Models\PeriodModel;
use Auth,DB,Validator,Session;

class PerformanceReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $period = PeriodModel::get();
        $officer = OfficerModel::get();
        $query = PerformanceReportModel::query();
        if(session('filter-performance-report')){
            $query = $this->applyFilter($query);
        }
        $data = $query->leftJoin('table_officer','table_officer.id','table_performance_report.id_officer')
                    ->leftJoin('table_criteria','table_criteria.id','table_performance_report.id_criteria')
                    ->leftJoin('table_period','table_period.id','table_performance_report.id_period')
                    ->select('table_performance_report.*','table_officer.o_name as officer',
                        'table_officer.position','table_officer.unit',
                        'table_criteria.c_name as criteria','table_period.p_name as periode')
                    ->orderBy('table_performance_report.created_at','DESC')
                    ->paginate(15);
        $pageName = "Data Laporan Performance Pegawai";
        return view('pages.performance_report.index',compact('data','pageName','period','officer'));
    }
    public function createFilter(Request $req){
        if(session('filter-performance-report')){
            Session::forget('filter-performance-report');
        }
        $filter = array();
        $filter['period']=$req->period?$req->period:NULL;
        $filter['officer']=$req->officer?$req->officer:NULL;
        // $filter['criteria'] = $req->criteria?$req->criteria:NULL;
        // $filter['key'] = $req->key?$req->key:NULL;
        if(count($filter)>0){
            session(['filter-performance-report'=>$filter]);
        }
        return redirect()->route('performance_report.index');
    }
    protected function applyFilter($query){
        $filter = session('filter-performance-report');
        if(isset($filter['period'])){
            $query->where('table_period.id',$filter['period']);
        }
        if(isset($filter['officer'])){
            $query->where('table_officer.id',$filter['officer']);
        }
        // if(isset($filter['criteria'])){
        //     $query->where('table_criteria.id',$filter['criteria']);
        // }
        // if(isset($filter['key'])){
        //     $query->where('table_officer.f_name','LIKE','%'.$filter['key'].'%');
        // }
        return $query;
    }
    public function clearFilter(){
        if(session('filter-performance-report')){
            Session::forget('filter-performance-report');
        }
        return redirect()->route('performance_report.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = OfficerModel::paginate(15);
        $criteria = CriteriaModel::get();
        $period = PeriodModel::get();
        $pageName = "Tambah Data Laporan Performance Pegawai";
        return view('pages.report_performance.index',compact('data','criteria','period','pageName'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_period'=>'required',
            'id_criteria'=>'required',
            'id_officer'=>'required',
            'perf_value'=>'required',
        ]);
        if($validator->fails()){
            return redirect()->route('performance_report.create')->with('error','Data tidak lengkap!')->withInput();
        }
        $check = PerformanceReportModel::where('id_officer',$request->id_officer)
                        ->where('id_period',$request->id_period)
                        ->where('id_criteria',$request->id_criteria)->first();
        if($check){
            return redirect()->route('performance_report.create')->with('error',"Data sudah ada!")->withInput();
        }
        try{
            DB::beginTransaction();
            $data = PerformanceReportModel::create([
                'id_period'=>$request->id_period,
                'id_officer'=>$request->id_officer,
                'id_criteria'=>$request->id_criteria,
                'perf_value'=>$request->perf_value,
                'notes'=>$request->notes??'-'
            ]);
            DB::commit();
            if($data){
                return redirect()->route('performance_report.index')->with('success','Data berhasil di tambahkan!');
            }
            DB::rollBack();
            return redirect()->route('performance_report.create')->with('error','Data gagal di tambahkan!')->withInput();
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('performance_report.create')->with('error','Terjadi kesalahan silahkan coba Lagi!')->withInput();
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
        $data = PerformanceReportModel::where('id',$id)->first();
        $officer = OfficerModel::get();
        $criteria = CriteriaModel::get();
        $period = PeriodModel::get();
        $pageName = "Ubah Data Performance Pegawai";
        return view('pages.performance_report.edit',compact('data','officer','criteria','period','pageName'));
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
        $validator = Validator::make($request->all(),[
            'id_period'=>'required',
            'id_criteria'=>'required',
            'id_officer'=>'required',
            'perf_value'=>'required',
        ]);
        if($validator->fails()){
            return redirect()->route('performance_report.edit',$id)->with('error','Data tidak lengkap!')->withInput();
        }
        $check = PerformanceReportModel::where('id_officer',$request->id_officer)
                        ->where('id_period',$request->id_period)
                        ->where('id_criteria',$request->id_criteria)
                        ->where('id','!=',$id)
                        ->first();
        if($check){
            return redirect()->route('performance_report.edit')->with('error',"Data sudah ada!")->withInput();
        }
        try{
            DB::beginTransaction();
            $data = PerformanceReportModel::where('id',$id)->update([
                'id_period'=>$request->id_period,
                'id_officer'=>$request->id_officer,
                'id_criteria'=>$request->id_criteria,
                'perf_value'=>$request->perf_value,
                'notes'=>$request->notes??'-'
            ]);
            DB::commit();
            if($data){
                return redirect()->route('performance_report.index')->with('success','Data berhasil di ubahkan!');
            }
            DB::rollBack();
            return redirect()->route('performance_report.edit',$id)->with('error','Data gagal di ubahkan!')->withInput();
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('performance_report.edit',$id)->with('error','Terjadi kesalahan silahkan coba Lagi!')->withInput();
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
        try{
            DB::beginTransaction();
            $deleted = PerformanceReportModel::where('id',$id)->delete();
            DB::commit();
            if($deleted){
                return redirect()->route('performance_report.index')->with('success','Data berhasil di hapus!');
            }
            DB::rollBack();
            return redirect()->route('performance_report.index')->with('error','Data gagali di hapus!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('performance_report.index')->with('error','Terjadi kesalahan, silahkan coba lagi, Data gagal di hapus');
        }
    }
    public function newForm($officerId,$periodId){
       
        $check = PerformanceReportModel::where('id_period',$periodId)->where('id_officer',$officerId)->get();
        $query = CriteriaModel::query();
        $query->where('id_period',$periodId);
        $query->whereRaw('(id_officer="'.$officerId.'" OR id_officer is NULL)');
        $data = $query->get();
        foreach($check as $c){
            //$query->where('id','!=',$c->id_criteria);
            foreach($data as $d){
                if($d->id==$c->id_criteria){
                    $d['id_performance'] = $c->id;
                    $d['value'] = $c->perf_value;
                    $d['notes'] = $c->notes;
                    break;
                }
            }
        }
            
        
        // dd([$officerId,$periodId]);
        // if(count($data)==0){
        //     return redirect('performance_report/create')->with('error','Data sudah di input, silahkan check di pada halaman Laporan Performance!+'.$query->toSql());
        // }
        $period = PeriodModel::where('id',$periodId)->first();
            // $data = CriteriaModel::where('id_period',$period->id)->get();
            

        $officer = OfficerModel::where('id',$officerId)->first();
        $pageName = "Add Performance Report of ".$officer->o_name." ON ".$period->p_name;
        $officerId = $officer->id;
        $periodId = $period->id;
        return view('pages.report_performance.form',compact('data','periodId','officerId','pageName'));
       
    }
    public function storeBulkCriteria(Request $req){
        $validator = Validator::make($req->all(),[
            // 'criteria.*'=>'required',
            // 'criteria'=>'required',
            // 'criteriaId.*'=>'required',
            // 'criteriaId'=>'required',
            'officerId'=>'required',
            'periodId'=>'required'
        ]);
        if(!$validator->fails()){
            $counter = 0;
            try{
                DB::beginTransaction();
                for($i=0;$i<count($req->criteria);$i++){
                    if($req->criteria[$i]!=NULL){
                        $data = [
                            'id_period'=>$req->periodId,
                                'id_criteria'=>$req->criteriaId[$i],
                                'id_officer'=>$req->officerId,
                                'notes'=>$req->notes[$i]??'-'
                        ];
                        $data['perf_value'] = $req->criteria[$i];
                        if($req->id_performance[$i]!='-'){
                            $test = PerformanceReportModel::where('id',$req->id_performance[$i])->update($data);
                        }else{
                            $test = PerformanceReportModel::create($data);
                        }
                        DB::commit();
                        if($test){
                            $counter+=1;
                        }
                    }
                }
                // if($counter==count($req->criteria)){
                    
                    return redirect('performance_report')->with('success','Performance berhasil di tambahkan!');
                // }else{
                //     return redirect()->back()->with('error','Gagal menyimpan Performance Report!')->withInput(['officerId'=>$req->officerId,'periodId'=>$req->periodId]);
                // }
            }catch(\Exception $e){
                DB::rollBack();
                return redirect()->back()->with('error','Gagal menyimpan Performance Report! '.$e->getMessage())->withInput(['officerId'=>$req->officerId,'periodId'=>$req->periodId]);
            }            
        }
        return redirect()->back()->with('error','Data tidak lengkap : '.$validator->errors())->withInput(['officerId'=>$req->officerId,'periodId'=>$req->periodId]);
    }
}
