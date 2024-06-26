<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonitoringCriteriaModel;
use App\Models\MonitoringReportModel;
use App\Models\OfficerModel;
use App\Models\PeriodModel;
use App\Models\RankCategoryModel;
use Auth,DB,Validator,Session;
use PDF;

class MonitoringReportController extends Controller
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
        $query = MonitoringReportModel::query();
        $query = $this->applyFilter($query);
      
        $data = $query->leftJoin('table_officer','table_officer.id','table_monitoring_report.id_officer')
                            ->leftJoin('table_monitoring_criteria','table_monitoring_criteria.id','table_monitoring_report.id_mon_crit')
                            ->leftJoin('table_period','table_period.id','table_monitoring_report.id_period')
                            ->select('table_monitoring_report.*','table_officer.o_name as officer_name',
                            'table_officer.position','table_officer.unit','table_period.p_name as period',
                            'table_monitoring_criteria.mc_name as mon_criteria')
                            ->orderBy('table_monitoring_report.created_at','DESC')
                            ->paginate(15);
        $pageName = "Data Laporang Monitoring Per Pegawai";
        return view('pages.monitoring_report.index',compact('data','pageName','officer','period'));
    }
    protected function applyFilter($query){
        $filter = session('filter-monitoring-report');
            if($filter){
            if(isset($filter['period'])){
                $query->where('table_period.id',$filter['period']);
            }
            if(isset($filter['officer'])){
                $query->where('table_officer.id',$filter['officer']);
            }
            // if(isset($filter['criteria'])){
            //     $query->where('table_monitoring_criteria.id',$filter['criteria']);
            // }
            // if(isset($filter['key'])){
            //     $query->where('table_officer.f_name','LIKE','%'.$filter['key'].'%');
            // }
        }
        return $query;
    }
    public function clearFilter(){
        if(session('filter-monitoring-report')){
            Session::forget('filter-monitoring-report');
        }
        return redirect()->route('monitoring_report.index');
    }
    public function filterReport(Request $req){
        if(session('filter-monitoring-report')){
            Session::forget("filter-monitoring-report");
        }
        $filter = array();
        $filter['period'] = $req->period?$req->period:NULL;
        $filter['officer'] = $req->officer?$req->officer:NULL;
        // $filter['criteria'] = $req->criteria?$req->criteria:NULL;
        // $filter['key'] = $req->key?$req->key:NULL;
        if(count($filter)>0){
            session(['filter-monitoring-report'=>$filter]);
        }
        return redirect()->route('monitoring_report.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = OfficerModel::paginate(15);
        $mon_criteria = MonitoringCriteriaModel::get();
        $period = PeriodModel::get();
        $pageName = "Tambah data laporan monitoring";
        return view('pages.report_monitoring.index',compact('data','mon_criteria','pageName','period'));
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
            'id_officer'=>'required',
            'id_mon_crit'=>'required',
            'id_period'=>'required',
            'mon_value'=>'required'
        ]);
        if($validator->fails()){
            return redirect()->route('monitoring_report.create')->with('error','Data tidak lengkap!')->withInput();
        }
        $check = MonitoringReportModel::where('id_officer',$request->id_officer)
                    ->where('id_period',$request->id_period)
                    ->where('id_mon_crit',$request->id_mon_crit)->first();
        if($check){
            return redirect()->route('monitoring_report.create')->with('error','Data Laporan sudah di isi, silahkan ke data table untuk mengubah data!')->withInput();
        }
        try{
            DB::beginTransaction();
            $data = MonitoringReportModel::create([
                'id_officer'=>$request->id_officer,
                'id_mon_crit'=>$request->id_mon_crit,
                'id_period'=>$request->id_period,
                'mon_value'=>$request->mon_value
            ]);
            DB::commit();
            if($data){
                return redirect()->route('monitoring_report.index')->with('success','Data berhasil di tambahkan!');
            }
            DB::rollBack();
            return redirect()->route('monitoring_report.create')->with('error','Data gagal di tambahkan!')->withInput();
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('monitoring_report.create')->with('error','Terjadi kesalahan, silahkan coba lagi! : '.$e->getMessage())->withInput();
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
        $data = MonitoringReportModel::where('id',$id)->first();
        $officer = OfficerModel::get();
        $mon_criteria = MonitoringCriteriaModel::get();
        $period = PeriodModel::get();
        $pageName = "Ubah data Laporan Monitoring";
        return view('pages.monitoring_report.edit',compact('data','officer','mon_criteria','pageName','period'));
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
            'id_officer'=>'required',
            'id_mon_crit'=>'required',
            'id_period'=>'required',
            'mon_value'=>'required'
        ]);
        if($validator->fails()){
            return redirect()->route('monitoring_report.edit',$id)->with('error','Data tidak lengkap!')->withInput();
        }
        $check = MonitoringReportModel::where('id_officer',$request->id_officer)
                    ->where('id_period',$request->periode)
                    ->where('id_mon_crit',$request->id_mon_crit)
                    ->where('id','!=',$id)
                    ->first();
        if($check){
            return redirect()->route('monitoring_report.edit',$id)->with('error','Data Laporan sudah di isi, silahkan ke data table untuk mengubah data!')->withInput();
        }
        try{
            DB::beginTransaction();
            $updated = MonitoringReportModel::where('id',$id)->update([
                'id_officer'=>$request->id_officer,
                'id_mon_crit'=>$request->id_mon_crit,
                'id_period'=>$request->id_period,
                'mon_value'=>$request->mon_value,
                'notes'=>$request->notes
            ]);
            DB::commit();

            if($updated){
                return redirect()->route('monitoring_report.index')->with('success','Data berhasil di ubah!');
            }
            DB::rollBack();
            return redirect()->route('monitoring_report/'.$id.'/edit')->with('error','Data gagal di ubahkan!')->withInput();
        }catch(\Exception $e){
            DB::rollBack();
            return redriect()->route('monitoring_report/'.$id.'/edit')->with('error','Terjadi kesalahan, silahkan coba lagi!')->withInput();
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
            $deleted = MonitoringReportModel::where('id',$id)->delete();
            DB::commit();
            if($deleted){
                return redirect()->route('monitoring_report.index')->with('success','Data berhasil di hapus!');
            }
            DB::rollBack();
            return redirect()->route('monitoring_report.index')->with('error','Data gagal di hapus!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('monitoring_report.index')->with('error','Data gagal di hapus, terjadi kesalahan silahkan coba lagi!');
        }
    }
    public function formProgress(){
        $period = PeriodModel::get();
        $pageName = "Data Progress Monitoring Kinerja";
        return view('pages.monitoring_report.progress',compact('period','pageName'));
    }

    public function calculateWorkload(Request $req){
        $check = MonitoringReportModel::where('id_period',$req->idPeriod)->get();
        if(count($check)>0){
            MonitoringReportModel::where('id_period',$req->idPeriod)->update([
                'preference_value'=>0
            ]);
            $criteria = MonitoringCriteriaModel::where('id_period',$req->idPeriod)->get();
            $paginateMax = count($criteria);
            foreach($criteria as $c){
                $mon = MonitoringReportModel::where('id_period',$req->idPeriod)
                                ->where('id_mon_crit',$c->id)->get();
                foreach($mon as $m){
                    $preferenceValue = ($m->mon_value/100) * $c->weight;
                    $m->preference_value = $preferenceValue;
                    $m->save();
                }
            }
            $category = RankCategoryModel::orderBy('min_value','DESC')->get();
            $data = MonitoringReportModel::leftJoin('table_officer','table_officer.id','table_monitoring_report.id_officer')
                                ->leftJoin('table_period','table_period.id','table_monitoring_report.id_period')
                                ->leftJoin('table_monitoring_criteria','table_monitoring_criteria.id','table_monitoring_report.id_mon_crit')
                                ->select('table_monitoring_report.*','table_officer.o_name AS officerName','table_officer.position AS officerPosition',
                                    'table_officer.unit AS officerUnit','table_period.p_name AS periodName','table_period.description AS periodDescription',
                                    'table_monitoring_criteria.mc_name AS criteriaName','table_monitoring_criteria.weight AS criteriaWeight')
                                ->orderBy('table_monitoring_report.id_officer','ASC')->get();//->paginate($paginateMax * 3);
            $pageName = "Data Progress dari Monitoring Kinerja";
            $period = PeriodModel::get();
            $selectedPeriod = PeriodModel::where('id',$req->idPeriod)->first();
            return view('pages.monitoring_report.progress',compact('data','pageName','period','category','selectedPeriod'));
        }
        return redirect('monitoring_progress/progress_form')->with('error','Data Monitoring Report Belum Ada!');
    }
    public function printProgress($period){
        $check = MonitoringReportModel::where('id_period',$period)->get();
        if(count($check)>0){
            MonitoringReportModel::where('id_period',$period)->update([
                'preference_value'=>0
            ]);
            $officer = OfficerModel::get();
            foreach($officer as $of){
                $criteria = MonitoringCriteriaModel::where('id_period',$period)->whereRaw('(id_officer="'.$of->id.'" OR id_officer is NULL)')->get();
                $paginateMax = count($criteria);
                foreach($criteria as $c){
                    $mon = MonitoringReportModel::where('id_period',$period)
                                    ->where('id_mon_crit',$c->id)->get();
                    foreach($mon as $m){
                        $preferenceValue = ($m->mon_value/100) * $c->weight;
                        $m->preference_value = $preferenceValue;
                        $m->save();
                    }
                }
            }
            $category = RankCategoryModel::orderBy('min_value','DESC')->get();
            $data = MonitoringReportModel::leftJoin('table_officer','table_officer.id','table_monitoring_report.id_officer')
                                ->leftJoin('table_period','table_period.id','table_monitoring_report.id_period')
                                ->leftJoin('table_monitoring_criteria','table_monitoring_criteria.id','table_monitoring_report.id_mon_crit')
                                ->select('table_monitoring_report.*','table_officer.o_name AS officerName','table_officer.position AS officerPosition',
                                    'table_officer.unit AS officerUnit','table_period.p_name AS periodName','table_period.description AS periodDescription',
                                    'table_monitoring_criteria.mc_name AS criteriaName','table_monitoring_criteria.weight AS criteriaWeight')
                                ->orderBy('table_monitoring_report.id_officer','ASC')->get();
            $period = PeriodModel::where('id',$period)->first();
            // return view('pages.monitoring_report.progress',compact('data','pageName','period','category'));
            $pdf = PDF::loadView('pages.monitoring_report.pdf',['data'=>$data,'category'=>$category])->setPaper('a4','landscape');
            return $pdf->stream('Monitoring Progress Period '.$period->p_name.'.pdf');
        }
        return redirect('monitoring_progress/progress_form')->with('error','Data Monitoring Report Belum Ada!');
    }
    public function printRekapProgress($period){
        $check = MonitoringReportModel::where('id_period',$period)->get();
        if(count($check)>0){
            MonitoringReportModel::where('id_period',$period)->update([
                'preference_value'=>0
            ]);
           
            $officer = OfficerModel::get();
            foreach($officer as $of){
                $criteria = MonitoringCriteriaModel::where('id_period',$period)
                        ->whereRaw('(id_officer="'.$of->id.'" OR id_officer is NULL)')
                        ->get();
                $paginateMax = count($criteria);
                foreach($criteria as $c){
                    $mon = MonitoringReportModel::where('id_period',$period)
                                    ->where('id_officer',$of->id)
                                    ->where('id_mon_crit',$c->id)->get();
                    foreach($mon as $m){
                        $preferenceValue = ($m->mon_value/100) * $c->weight;
                        $m->preference_value = $preferenceValue;
                        $m->save();
                    }
                }
            }
            
            $data = [];
            foreach($officer as $of){
                $tmp = [];
                $tmp["officerName"] = $of->o_name;
                $tmp["officerPosition"] = $of->position;
                $tmp["officerUnit"] = $of->unit;
                $tmp["totalPencapaian"] = MonitoringReportModel::where('id_period',$period)
                                            ->where('id_officer',$of->id)
                                            ->sum('preference_value');
                $tmp["totalBobot"] = MonitoringCriteriaModel::where('id_period',$period)
                                            ->where('id_officer',$of->id)
                                            ->sum('weight');
                $tmp["notes"] = MonitoringReportModel::leftJoin('table_monitoring_criteria','table_monitoring_criteria.id','table_monitoring_report.id_mon_crit')
                                    ->select('table_monitoring_report.notes AS notes','table_monitoring_criteria.mc_name AS kriteria')
                                    ->where('table_monitoring_report.id_period',$period)
                                    ->where('table_monitoring_report.id_officer',$of->id)
                                    ->where('notes','!=','')
                                    ->get();
                array_push($data,$tmp);
            }
            // dd($data);
            $category = RankCategoryModel::orderBy('min_value','DESC')->get();
            
            $periodDetail = PeriodModel::where('id',$period)->first();
            $pageName = strtoupper($periodDetail->p_name);
            // return view('pages.monitoring_report.pdf2',compact('data','pageName','category'));
            $pdf = PDF::loadView('pages.monitoring_report.pdf2',['data'=>$data,'category'=>$category,'pageName'=>$pageName]);
            return $pdf->stream('Monitoring Progress Period '.$periodDetail->p_name.'.pdf');
        }
        return redirect('monitoring_progress/progress_form')->with('error','Data Monitoring Report Belum Ada!');
    }
    public function newForm($officerId,$periodId){
       
        $check = MonitoringReportModel::where('id_period',$periodId)->where('id_officer',$officerId)->get();
        $query = MonitoringCriteriaModel::query();
        $query->where('id_period',$periodId);
        $query->whereRaw('(id_officer="'.$officerId.'" OR id_officer is NULL)');
        $data = $query->get();
        foreach($check as $c){
            // $query->where('id','!=',$c->id_mon_crit);
            foreach($data as $d){
                if($d->id==$c->id_mon_crit){
                    $d["id_monitoring"] = $c->id;
                    $d["value"] = $c->mon_value;
                    $d["notes"] = $c->notes;
                    break;
                }
            }
        }
            
       
        // dd([$officerId,$periodId]);
        // if(count($data)==0){
        //     return redirect('monitoring_report/create')->with('error','Data sudah di input, silahkan check di pada halaman Laporan Monitoring!');
        // }
        $period = PeriodModel::where('id',$periodId)->first();
            // $data = CriteriaModel::where('id_period',$period->id)->get();
            

        $officer = OfficerModel::where('id',$officerId)->first();
        $pageName = "Add Monitoring Report of ".$officer->o_name." ON ".$period->p_name;
        $officerId = $officer->id;
        $periodId = $period->id;
        return view('pages.report_monitoring.form',compact('data','periodId','officerId','pageName'));
       
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
                // dd($req->notes);
                for($i=0;$i<count($req->criteria);$i++){
                    if($req->criteria[$i]!=NULL){
                        $data = [
                            'id_period'=>$req->periodId,
                            'id_mon_crit'=>$req->criteriaId[$i],
                            'id_officer'=>$req->officerId,
                            'notes'=>$req->notes[$i]??''
                        ];
                        $data['mon_value'] = $req->criteria[$i];
                        if($req->id_monitoring[$i]!="-"){
                            $test = MonitoringReportModel::where('id',$req->id_monitoring[$i])->update($data);
                        }else{
                            $test = MonitoringReportModel::create($data);
                        }
                        DB::commit();
                        if($test){
                            $counter+=1;
                        }
                    }
                }
                // if($counter==count($req->criteria)){
                    
                    return redirect('monitoring_report')->with('success','Monitoring Report berhasil di tambahkan!');
                // }else{
                //     return redirect()->back()->with('error','Gagal menyimpan Performance Report!')->withInput(['officerId'=>$req->officerId,'periodId'=>$req->periodId]);
                // }
            }catch(\Exception $e){
                DB::rollBack();
                return redirect()->back()->with('error','Gagal menyimpan monitoring Report! '.$e->getMessage())->withInput(['officerId'=>$req->officerId,'periodId'=>$req->periodId]);
            }            
        }
        return redirect()->back()->with('error','Data tidak lengkap : '.$validator->errors())->withInput(['officerId'=>$req->officerId,'periodId'=>$req->periodId]);
    }
}
