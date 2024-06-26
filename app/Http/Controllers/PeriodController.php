<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodModel;
use Auth,DB,Validator,Session;
use App\Http\Controllers\DashboardController;
class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = PeriodModel::query();
        $filter = session('filter-period');
        if($filter){
            $query->where('p_name','LIKE','%'.$filter.'%');
        }
        $data = $query->orderBy('created_at','DESC')->paginate(15);
        $pageName = "Data Periode";
        return view('pages.period.index',compact('data','pageName'));
    }
    public function search(Request $req){
        if(session('filter-period')){
            Session::forget('filter-period');
        }
        $filter = $req->name??NULL;
        if($filter!=NULL){
            session(['filter-period'=>$filter]);
        }
        return redirect('period');
    }
    public function clearFilter(){
        if(session('filter-period')){
            Session::forget('filter-period');
        }
        return redirect('period');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function create()
    {
        if(!(new DashboardController)->checkPermission(['Admin'])){
            return redirect('period')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        $pageName = "Tambah Data Periode";
        return view('pages.period.create',compact('pageName'));
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
            return redirect('period')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        $validator = Validator::make($request->all(),[
            'p_name'=>'required'
        ]);
        if($validator->fails()){
            return redirect()->route('period.index')->with('error','Data tidak lengkap!');
        }
        try{
            DB::beginTransaction();
            $data = PeriodModel::create([
                'p_name'=>$request->p_name,
                'description'=>$request->description??'-'
            ]);
            DB::commit();
            if($data){
                return redirect()->route('period.index')->with('success','Data berhasil ditambahkan');
            }
            DB::rollBack();
            return redirect()->route('period.create')->with('error','Data gagal di tambahkan!')->withInput();
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('period.create')->with('error','Terjadi kesalahan, silahkan coba lagi!')->withInput();
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
            return redirect('period')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        $data = PeriodModel::where('id',$id)->first();
        if($data){
            $pageName = "Ubah Data Periode";
            return view('pages.period.edit',compact('data','pageName'));
        }
        return redirect('period')->with('error','Data tidak ditemukan!');
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
            return redirect('period')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        $validator = Validator::make($request->all(),[
            'p_name'=>'required'
        ]);
        if($validator->fails()){
            return redirect()->route('period.edit',$id)->with('error','Data tidak lengkap!');
        }
        try{
            DB::beginTransaction();
            $updated = PeriodModel::where('id',$id)->update([
                'p_name'=>$request->p_name,
                'description'=>$request->description??'-'
            ]);
            DB::commit();
            if($updated){
                return redirect()->route('period.index')->with('success','Data berhasil ubahkan');
            }
            DB::rollBack();
            return redirect()->route('period.edit',$id)->with('error','Data gagal di ubahkan!')->withInput();
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('period.edit',$id)->with('error','Terjadi kesalahan, silahkan coba lagi!')->withInput();
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
            return redirect('period')->with('error','Anda tidak memiliki izin untuk askes halaman ini!');
        }
        try{
            DB::beginTransaction();
            $deleted = PeriodModel::where('id',$id)->delete();
            DB::commit();
            if($deleted){
                return redirect()->route('period.index')->with('success','Data berhasil dihapus!');
            }
            DB::rollBack();
            return redirect()->route('period.index')->with('error','Data gagal di hapus!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('period.index')->with('error','Terjadi kesalahan, silahkan coba lagi!');
        }
    }
}
