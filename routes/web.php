<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\MonitoringCriteriaController;
use App\Http\Controllers\MonitoringReportController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\PerformanceReportController;
use App\Http\Controllers\PerformanceNormController;
use App\Http\Controllers\PerformanceRankController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RankCategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Auth::routes();
Route::group(['middleware'=>['auth']],function(){


    Route::resource('/criteria',CriteriaController::class);
    Route::post('/criteria_filter',[CriteriaController::class,'createFilter']);
    Route::get('/criteria_clear',[CriteriaController::class,'clearFilter']);

    Route::resource('/monitoring_criteria',MonitoringCriteriaController::class);
    Route::post('/monitoring_criteria_filter',[MonitoringCriteriaController::class,'createFilter']);
    Route::get('/monitoring_criteria_clear',[MonitoringCriteriaController::class,'clearFilter']);

    Route::resource('/monitoring_report',MonitoringReportController::class);
    Route::get('/monitoring_progress/progress_form',[MonitoringReportController::class,'formProgress']);
    Route::post('/monitoring_progress/progress',[MonitoringReportController::class,'calculateWorkload']);
    Route::get('/monitoring_progress_print/{period}',[MonitoringReportController::class,'printProgress']);
    Route::get('/monitoring_progress_rekap_print/{period}',[MonitoringReportController::class,'printRekapProgress']);
    
    Route::get('/monitoring_report_form/{officerId}/{periodId}',[MonitoringReportController::class,'newForm']);
    Route::post('/monitoring_report_store',[MonitoringReportController::class,'storeBulkCriteria'])->name('monitoringStoreBulk');
    Route::post('/monitoring_report_filter',[MonitoringReportController::class,'filterReport']);
    Route::get('/monitoring_report_clear',[MonitoringReportController::class,'clearFilter']);

    Route::resource('/officer',OfficerController::class);
    Route::post('/officer_search',[OfficerController::class,'search']);
    Route::get('/officer_clear',[OfficerController::class,'clearFilter']);

    Route::resource('/performance_report',PerformanceReportController::class);
    Route::get('/performance_report_form/{officerId}/{periodId}',[PerformanceReportController::class,'newForm']);
    Route::post('/performance_report_store2',[PerformanceReportController::class,'storeBulkCriteria'])->name('performanceStoreBulk');
    Route::post('/performance_report_filter',[PerformanceReportController::class,'createFilter']);
    Route::get('/performance_report_clear',[PerformanceReportController::class,'clearFilter']);

    Route::get('/performance_rank',[PerformanceRankController::class,'formRank']);
    Route::post('/performance_rank',[PerformanceRankController::class,'getData']);
    Route::get('/performance_rank_print/{period}',[PerformanceRankController::class,'printPerformanceRank']);
    Route::get('/performance_rank_print_rekap/{period}',[PerformanceRankController::class,'printRekapRank']);
    
    Route::resource('/period',PeriodController::class);
    Route::post('/period_search',[PeriodController::class,'search']);
    Route::get('/period_clear',[PeriodController::class,'clearFilter']);

    Route::prefix('user')->group(function(){
        Route::get('/',[UserController::class,'index'])->name('user.index');
        Route::get('/create',[UserController::class,'create'])->name('user.create');
        Route::post('/',[UserController::class,'store'])->name('user.store');
        Route::get('/{id}/edit',[UserController::class,'edit'])->name('user.edit');
        Route::put('/{id}',[UserController::class,'update'])->name('user.update');
        Route::delete('/destroy/{id}',[UserController::class,'destroy'])->name('user.destroy');
        Route::post('/reset_password/{id}',[UserController::class,'resetPassword']);
        Route::get('/profile',[UserController::class,'profile'])->name('user.profile');
        Route::post('/update_password',[UserController::class,'updatePassword'])->name("user.update.password");
        Route::post('/update_profile',[UserController::class,'updateProfile'])->name('user.update.profile');
    });
    Route::post('/user_filter',[UserController::class,'createFilter']);
    Route::get('/user_clear',[UserController::class,'clearFilter']);
    Route::resource('/rank_category',RankCategoryController::class);

    Route::get('/',[DashboardController::class,'index']);

    Route::get('/get_data_rank/{officerId}',[DashboardController::class,'getDataPerOfficer']);
    Route::get('/get_data_monitoring/{officerId}',[DashboardController::class,'getDataMonitoring']);
});
Route::get('/login',[LoginController::class,'login_view'])->name('login');
Route::post('login-post', [LoginController::class, 'loginValidation'])->name('login.post');
Route::get('logout-post', [LoginController::class, 'logout'])->name('logout.post');

// Route::get('/', function () {
//     $user = Auth::user();
//     if($user){
//         return view('temporary',['name'=>'testing','pageName'=>'Testing Page','tipe'=>'Admin']);
//     }else {
//         return redirect()->route('login');
//     }
// });
