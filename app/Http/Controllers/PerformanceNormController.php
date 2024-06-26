<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CriteriaModel;
use App\Models\OfficerModel;
use App\Models\PerformanceRankModel;
use App\Models\PerformanceReportModel;
use App\Models\PeriodModel;
use DB;

class PerformanceNormController extends Controller
{
    //

    public function calculateSAW($idPeriod){
        $officer = OfficerModel::get();
        foreach($officer as $of){
            $criteria = CriteriaModel::get();//where('id_period',$idPeriod)->whereRaw('(id_officer="'.$of->id.'" OR id_officer is NULL)')->get();
            foreach($criteria as $c){
                $performance = PerformanceReportModel::where('id_criteria',$c->id)
                                    ->where('id_period',$idPeriod)
                                    // ->where('id_officer',$of->id)
                                    ->get();
                $divider = 1;
                $isMax = true;
                if($c->c_type=="Benefit"){
                    $max = PerformanceReportModel::where('id_criteria',$c->id)
                                    // ->where('id_officer',$of->id)
                                    ->where('id_period',$idPeriod)->max('perf_value');
                    $divider = $max;
                    $isMax = true;
                }else{
                    $min = PerformanceReportModel::where('id_criteria',$c->id)
                                    // ->where('id_officer',$of->id)
                                    ->where('id_period',$idPeriod)->min('perf_value');
                                // dd($min);
                    $divider = $min;
                    $isMax = false;
                }
                foreach($performance as $p){
                    $normValue = $p->perf_value/$divider;
                    $preferenceValue = $normValue * $c->weight;
                    // dd($preferenceValue);
                    $p->norm_value = $normValue;
                    //if($isMax){
                        $p->preference_value= $preferenceValue;
                    //}else{
                    //   $p->preference_value = -$preferenceValue;
                    //}
                    
                    $p->save();
                }
            }
        }
        $this->createPerformanceRank($idPeriod);
    }
    public function createPerformanceRank($idPeriod){
        $officer = OfficerModel::get();
        try{
            DB::beginTransaction();
            PerformanceRankModel::where('id_period',$idPeriod)->delete();
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
        }
        foreach($officer as $of){
            $rank_value = PerformanceReportModel::where('id_officer',$of->id)
                                    ->where('id_period',$idPeriod)->sum('preference_value');
            $rank = PerformanceRankModel::create([
                'id_officer'=>$of->id,
                'id_period'=>$idPeriod,
                'rank_value'=>$rank_value
            ]);
        }
    }
    
}
