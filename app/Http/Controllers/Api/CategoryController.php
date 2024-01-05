<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\Category;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
       
        $sort_by = $r->sortBy;
        $sortOrder = $r->sortOrder;
       // $per_page=10;
        $list = Category::with('children')->where('status','Active')->whereNull('category_id')->get();
        $dimensions=getThumbnailDimensions();
        $List=$list->map(function ($r) {

            unset($r->category_id);
            
        //     $r->thumbnail=[];
        //     if($r->image)
        //    $r->thumbnail=getThumbnailsFromImage($r->image);
          $r->children_count=count($r->children);
          unset($r->children);
          unset($r->deleted_at);
        //    foreach($r->children as $v) {
        //     $v->thumbnail=[];
        //     if($v->image)
        //         $v->thumbnail=getThumbnailsFromImage($v->image);
                

          
        //    }
        return $r;
       });
       
      //  return response()->json(formattedPaginatedApiResponse($list),200);
        return response()->json(['data'=>$list],200);
    }

    
    public function show($id)
    {
       
        Log::info('show for: {id}', ['id' => $id]);
        $row = Category::with('children')->where('status','Active')->findOrFail($id);
        $row->thumbnail=new \stdClass();
        unset($row->category_id);
        unset($row->deleted_at);
         if($row->image)
           $row->thumbnail=getThumbnailsFromImage($row->image);
    
          $row->children_count=count($row->children);
          foreach($row->children as $v) {
            unset($v->category_id);
            unset($v->deleted_at);
            $v->children_count=count($v->children);
            $v->thumbnail=new \stdClass();
             if($v->image)
                 $v->thumbnail=getThumbnailsFromImage($v->image);
          
        }
       
        return response()->json($row,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
