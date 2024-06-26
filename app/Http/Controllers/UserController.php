<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator,DB,Hash,Auth,Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = User::query();
        $query = $this->applyFilter($query);
        $data = $query->orderBy('created_at','DESC')->paginate(15);
        $pageName = "Daftar Pengguna";
        return view('pages.user.index',compact('data','pageName'));
    }
    public function createFilter(Request $req){
        if(session('filter-user')){
            Session::forget('filter-user');
        }
        $filter = array();
        $filter['key'] = $req->key??NULL;
        $filter['level'] = $req->level??NULL;
        if(count($filter)>0){
            session(['filter-user'=>$filter]);
        }
        return redirect()->route('user.index');
    }
    protected function applyFilter($query){
        $filter = session('filter-user');
        if($filter){
            if(isset($filter['key'])){
                $query->where('users.name','LIKE','%'.$filter['key'].'%');
            }
            if(isset($filter['level'])){
                $query->where('users.level',$filter['level']);
            }
        }
        return $query;
    }
    public function clearFilter(){
        if(session('filter-user')){
            Session::forget('filter-user');
        }
        return redirect()->route('user.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageName = "Tambah Data Pengguna";
        return view('pages.user.create',compact('pageName'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name'=>'required',
            'email'=>'required',
            'level'=>'required',
            'password'=>'required'
        ]);
        // if($validator->fails()){
        //     return redirect()->route('user.create')->with('error','Data tidak lengkap!')->withInput($request->all());
        // }
        $check = User::where('email',$request->email)->first();
        if($check){
            return redirect()->route('user.create')->with('error','Pengguna dengan email '.$request->email.' sudah ada!')->withInput($request->all());
        }
        try{
            DB::beginTransaction();
            $data = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
                'level'=>$request->level
            ]);
            DB::commit();
            if($data){
                return redirect()->route('user.index')->with('success','Data berhasil ditambahkan!');
            }
            return redirect()->route('user.create')->with('error','Data gagal di tambahkan')->withInput($request->all());
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('user.create')->with('error','Terjadi kesalahan! : '.$e->getMessage())->withInput($request->all());
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
        $data = User::where('id',$id)->first();
        if($data){
            $pageName = "Ubah Data Pengguna";
            return view('pages.user.edit',compact('data','pageName'));
        }
        return redirect()->route('user.index')->with('error','Data tidak ditemukan!');
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
        $validate = $request->validate([
            'name'=>'required',
            'email'=>'required',
            'level'=>'required'
        ]);
        // if($validate->fails()){
        //     return redirect()->route('user.edit',$id)->with('error','Data tidak lengkap!')->withInput($request->all());
        // }
        $check = User::where('email',$request->email)->where('id','!=',$id)->first();
        if($check){
            return redirect()->route('user.edit',$id)->with('error','Data pengguna dengan email : '.$request->email.' sudah ada!')->withInput($request->all());
        }
        try{
            DB::beginTransaction();
            $update = User::where('id',$id)->update([
                'name'=>$request->name,
                'email'=>$request->email,
                'level'=>$request->level
            ]);
            DB::commit();
            if($update){
                return redirect()->route('user.index')->with('success','Data berhasil di ubah!');
            }
            return redirect()->route('user.edit',$id)->with('error',"Data gagal di tambahkan!")->withInput($request->all());
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('user.edit',$id)->with('error','Terjadi kesalahan! : '.$e->getMessage())->withInput($request->all());
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
            $deleted = User::where('id',$id)->delete();
            DB::commit();
            if($deleted){
                return redirect()->route('user.index')->with('success','Data berhasil di hapus!');
            }
            return redirect()->route('user.index')->with('error','Data gagal di hapus!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('user.index')->with('error','Terjadi kesalahan! : '.$e->getMessage());
        }
    }
    public function resetPassword(Request $req,$id){
        try{
            DB::beginTransaction();
            $user = User::where('id',$id)->first();
            $reset = User::where('id',$id)->update([
                'password'=>Hash::make('1234567890')
            ]);
            DB::commit();
            if($reset){
                return redirect()->route('user.index')->with('success',"Kata sandi pengguna : ".$user->name." berhasil di reset");
            }
            return redirect()->route('user.index')->with('error','Kata sandi pengguna : '.$user->name.' gagal di reset!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('user.index')->with('error',"Terjadi kesalahan : ".$e->getMessage());
        }
    }

    public function profile(){
        $user = Auth::user();
        $pageName = "Profile Pengguna";
        return view('pages.user.profile',compact('user','pageName'));
    }
    public function updateProfile(Request $req){
        try{
            $user = Auth::user();
            DB::beginTransaction();
            $data = User::where('id',$user->id)->update([
                'name'=>$req->name
            ]);
            DB::commit();
            if($data){
                return redirect()->route('user.profile')->with('success','Profile berhasil di ganti');
            }
            return redirect()->route('user.profile')->with('error','Gagal mengubah profile!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('user.profile')->with('error','Terjadi kesalahan, Gagal Mengubah profile!');
        }
    }
    public function updatePassword(Request $req){
        $req->validate([
            'old_pwd'=>'required',
            'new_pwd'=>'required|confirmed'
        ]);
        try{
            $user = Auth::user();
            if(!Hash::check($req->old_pwd,$user->password)){
                return redirect()->route('user.profile')->with('error','Kata sandi lama salah!');
            }
            DB::beginTransaction();
            $update = User::where('id',$user->id)->update([
                "password"=>Hash::make($req->new_pwd)
            ]);
            DB::commit();
            if($update){
                return redirect()->route('user.profile')->with('success','Kata sandi berhasil di ganti!');
            }
            return redirect()->route('user.profile')->with('error','Gagal mengganti Kata Sandi!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('user.profile')->with('error','Terjadi kesalahan!, Gagal mengganti kata sandi : '.$e->getMessage());
        }
    }
}
