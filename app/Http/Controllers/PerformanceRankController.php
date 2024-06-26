<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerformanceRankModel;
use App\Models\PeriodModel;
use App\Http\Controllers\PerformanceNormController;
use App\Models\PerformanceReportModel;
use App\Models\RankCategoryModel;
use PDF;

class PerformanceRankController extends Controller
{
    //

    public function getData(Request $req){
        $check = PerformanceReportModel::where('id_period',$req->idPeriod)->get();
        if(count($check)>0){
            (new PerformanceNormController)->calculateSAW($req->idPeriod);
            $query = PerformanceRankModel::query();
            $query->leftJoin('table_officer','table_officer.id','table_performance_ranking.id_officer')
                    ->leftJoin('table_period','table_period.id','table_performance_ranking.id_period')
                    ->select('table_performance_ranking.*','table_officer.o_name as officerName','table_officer.unit as officerUnit',
                    'table_officer.position AS officerPosition','table_officer.gender as officerGender','table_officer.dob as officerDob',
                    'table_period.p_name as periodName','table_period.description as periodDesc');
            $data = $query->where('id_period',$req->idPeriod)
                ->orderBy('rank_value','DESC')->paginate(15);
            foreach($data as $d){
                $d["detail"] = PerformanceReportModel::leftJoin('table_criteria','table_criteria.id','table_performance_report.id_criteria')
                                    ->select('table_performance_report.*','table_criteria.c_name AS criteriaName','table_criteria.weight AS criteriaWeight','table_criteria.c_type AS criteriaType')
                                    ->where('table_performance_report.id_officer',$d->id_officer)
                                    ->where('table_performance_report.id_period',$d->id_period)
                                    ->get();
            }
            $category = RankCategoryModel::orderBy('min_value','DESC')->get();
            $pageName = "Ranking Performance";
            $period = PeriodModel::get();
            $selectedPeriod = PeriodModel::where('id',$req->idPeriod)->first();
            return view('pages.performance_report.rank',compact('data','pageName','period','category','selectedPeriod'));
        }else{
            return redirect('/performance_rank')->with('error','Data Laporan Performance Belum ada!');
        }
    }
    public function formRank(){
        $pageName = "Ranking Performance";
        $period = PeriodModel::get();
        return view('pages.performance_report.rank',compact('pageName','period'));
    }
    public function printPerformanceRank($period){
        $check = PerformanceReportModel::where('id_period',$period)->get();
        if(count($check)>0){
            (new PerformanceNormController)->calculateSAW($period);
            $query = PerformanceRankModel::query();
            $query->leftJoin('table_officer','table_officer.id','table_performance_ranking.id_officer')
                    ->leftJoin('table_period','table_period.id','table_performance_ranking.id_period')
                    ->select('table_performance_ranking.*','table_officer.o_name as officerName','table_officer.unit as officerUnit',
                    'table_officer.position AS officerPosition','table_officer.gender as officerGender','table_officer.dob as officerDob',
                    'table_period.p_name as periodName','table_period.description as periodDesc');
            $data = $query->where('id_period',$period)
                ->orderBy('rank_value','DESC')->get();
            foreach($data as $d){
                $d["detail"] = PerformanceReportModel::leftJoin('table_criteria','table_criteria.id','table_performance_report.id_criteria')
                                    ->select('table_performance_report.*','table_criteria.c_name AS criteriaName','table_criteria.weight AS criteriaWeight','table_criteria.c_type AS criteriaType')
                                    ->where('table_performance_report.id_officer',$d->id_officer)
                                    ->where('table_performance_report.id_period',$d->id_period)
                                    ->get();
            }
            $category = RankCategoryModel::orderBy('min_value','DESC')->get();
            $pageName = "Ranking Performance";
            $period = PeriodModel::where('id',$period)->first();
            $pdf = PDF::loadView('pages.performance_report.pdf',['data'=>$data,'category'=>$category])->setPaper('a4','landscape');
            return $pdf->stream('Performance Rank Period '.$period->p_name.'.pdf');
            // return view('pages.performance_report.pdf',compact('data','pageName','period','category'));
        }else{
            return redirect('/performance_rank')->with('error','Data Laporan Performance Belum ada!');
        }
    }
    public function printRekapRank($period){
        $check = PerformanceReportModel::where('id_period',$period)->get();
        if(count($check)>0){
            (new PerformanceNormController)->calculateSAW($period);
            $query = PerformanceRankModel::query();
            $query->leftJoin('table_officer','table_officer.id','table_performance_ranking.id_officer')
                    ->leftJoin('table_period','table_period.id','table_performance_ranking.id_period')
                    ->select('table_performance_ranking.*','table_officer.o_name as officerName','table_officer.unit as officerUnit',
                    'table_officer.position AS officerPosition','table_officer.gender as officerGender','table_officer.dob as officerDob',
                    'table_period.p_name as periodName','table_period.description as periodDesc');
            $data = $query->where('id_period',$period)
                ->orderBy('rank_value','DESC')->get();
            foreach($data as $d){
                $d["detail"] = PerformanceReportModel::leftJoin('table_criteria','table_criteria.id','table_performance_report.id_criteria')
                                    ->select('table_performance_report.*','table_criteria.c_name AS criteriaName','table_criteria.weight AS criteriaWeight','table_criteria.c_type AS criteriaType')
                                    ->where('table_performance_report.id_officer',$d->id_officer)
                                    ->where('table_performance_report.id_period',$d->id_period)
                                    ->get();
            }
            $category = RankCategoryModel::orderBy('min_value','DESC')->get();
            $periodDetail = PeriodModel::where('id',$period)->first();
            $pageName = strtoupper($periodDetail->p_name);
            
            $pdf = PDF::loadView('pages.performance_report.pdf2',['data'=>$data,'category'=>$category,'pageName'=>$pageName]);
            return $pdf->stream('Performance Rank Period '.$periodDetail->p_name.'.pdf');
            // return view('pages.performance_report.pdf2',compact('data','pageName','periodDetail','category'));
        }else{
            return redirect('/performance_rank')->with('error','Data Laporan Performance Belum ada!');
        }
    }
}
