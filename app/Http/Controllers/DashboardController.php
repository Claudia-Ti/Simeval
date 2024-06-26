<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodModel;
use App\Models\OfficerModel;
use App\Models\PerformanceReportModel;
use App\Models\PerformanceRankModel;
use App\Models\MonitoringReportModel;
use App\Models\MonitoringCriteriaModel;
use Auth, Session;


class DashboardController extends Controller
{
    public function index(){
        $period = PeriodModel::get();
        $officer = OfficerModel::get();
        $pageName = "Dashboard SIMEVAL";
        return view('pages.dashboard',compact('period','officer','pageName'));
    }
    public function getDataPerOfficer($officerId){
        $period = PeriodModel::get();
        $data = array();
        $officer = OfficerModel::where('id',$officerId)->first();
        $backgroundColor = [];
        $borderColor = [];
        $labels = [];
        foreach($period as $p){
            $d = PerformanceRankModel::where('id_period',$p->id)
                            ->where('id_officer',$officerId)->first();
            $tmp = number_format(($d && $d->rank_value) ? $d->rank_value:0,2);//,"y"=>$p->p_name];
            $bgColor = 'rgba(255, 32, 78,0.2)';
            $brColor = 'rgba(255,32,78,1)';
            array_push($labels,$p->p_name);
            array_push($backgroundColor,$bgColor);
            array_push($borderColor,$brColor);
            array_push($data,$tmp);
        }
        $dataset = [
            "label"=>"Performance Rank for ".$officer->o_name,
            "data"=>$data,
            "backgroundColor"=>$backgroundColor,
            "borderColor"=>$borderColor,
            "borderWidth"=>2
        ];
        return response()->json(['dataset'=>[$dataset],'labels'=>$labels]);
    }
    public function getDataMonitoring($officerId){
        $period = PeriodModel::get();
        $data = array();
        $officer = OfficerModel::where('id',$officerId)->first();
        $backgroundColor = [];
        $borderColor = [];
        $labels = [];
        foreach($period as $p){
            $totalBobot = MonitoringCriteriaModel::where('id_period',$p->id)->sum('weight');
            $totalPencapaian = MonitoringReportModel::where('id_period',$p->id)
                            ->where('id_officer',$officerId)->sum('preference_value');
            if($totalBobot==0){
                $totalBobot = 1;
            }
            $persentase = (($totalPencapaian/$totalBobot)*1.1)*100;
            $tmp = number_format($persentase,2);//,"y"=>$p->p_name];
            $bgColor = 'rgba(0, 141, 218,0.2)';
            $brColor = 'rgba(0, 141, 218,1)';
            array_push($labels,$p->p_name);
            array_push($backgroundColor,$bgColor);
            array_push($borderColor,$brColor);
            array_push($data,$tmp);
        }
        $dataset = [
            "label"=>"Monitoring Rank for ".$officer->o_name,
            "data"=>$data,
            "backgroundColor"=>$backgroundColor,
            "borderColor"=>$borderColor,
            "borderWidth"=>2
        ];
        return response()->json(['dataset'=>[$dataset],'labels'=>$labels]);
    }
    public function checkPermission($level){
        $user = Auth::user();
        foreach($level as $l){
            if($user->level==$l){
                return true;
            }
        }
        return false;
    }
}
