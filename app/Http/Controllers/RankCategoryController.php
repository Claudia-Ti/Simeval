<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RankCategoryModel;
use Validator,DB;

class RankCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = RankCategoryModel::orderBy('min_value','DESC')->paginate(15);
        $pageName = "Rank Category";
        return view('pages.rank_category.index',compact('pageName','data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageName = "Add Rank Category";
        return view('pages.rank_category.create',compact('pageName'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            "name"=>'required',
            "min_value"=>'required'
        ]);
        if(!$validate->fails()){
            try{
                $data = RankCategoryModel::create([
                    'name'=>$request->name,
                    'initial'=>$request->initial??'-',
                    'min_value'=>$request->min_value
                ]);
                if($data){
                    return redirect('rank_category')->with('success','Data successfully Added!');
                }
                
            }catch(\Exception $e){
                return redirect('rank_category/create')->with('error','Data failed to be added : '.$e->getMessage())->withInput($request->all());
            }
        }
        return redirect('rank_category/create')->with('error','Data Validator Error : '.$validate->errors())->withInput($request->all());
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
        $data = RankCategoryModel::where('id',$id)->first();
        if($data){
            $pageName = "Edit Data Rank Category";
            return view('pages.rank_category.edit',compact('data','pageName'));
        }
        return redirect('rank_category')->with('error','Data Not Found!');
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
        $validate = Validator::make($request->all(),[
            'name'=>'required',
            'min_value'=>'required'
        ]);
        if(!$validate->fails()){
            try{
                DB::beginTransaction();
                $update = RankCategoryModel::where('id',$id)->update([
                    'name'=>$request->name,
                    'min_value'=>$request->min_value,
                    'initial'=>$request->initial??'-'
                ]);
                DB::commit();
                if($update){
                    return redirect('rank_category')->with('success','Data successfully updated!');
                }
            }catch(\Exception $e){
                DB::rollBack();
                return redirect('rank_category/'.$id.'/edit')->with('error','Data Error : '.$e->getMessage())->withInput($request->all());
            }
        }
        return redirect('rank_category/'.$id.'/edit')->with('error','Validation Error : '.$validate->errors())->withInput($request->all());
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
            $delete = RankCategoryModel::where('id',$id)->delete();
            DB::commit();
            if($delete){
                return redirect('rank_category')->with('success','Data successfully Deleted!');
            }
        }catch(\Exception $e){
            DB::rollBack();
            return redirect('rank_cateogyr')->with('error','Data failed to be deleted!');
        }
    }
}
