<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use File;
use \Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class PermissionController extends Controller
{
     public function __construct(){
        $this->dashboard_url=\URL::to('/admin');
        $this->index_url=route('permissions.index');
        $this->module='Permission';
        $this->view_folder='permissions';
        $this->storage_folder=$this->view_folder;
        $this->form_image_field_name='';
        $this->is_multiple_upload=false;
        $this->parent_field_name='';
        $this->image_model_name='';
        $this->has_upload=false;
        $this->pagination_count=100;
		 $this->columns_with_select_field='null';
         $this->table_columns=[['column'=>'label','label'=>'Name','sortable'=>'Yes']];
     }
      public function buildFilter(Request $r,$query){
        $get=$r->all();
        if(count($get)>0 && $r->isMethod('get'))
       { 
           foreach($get as $key=>$value)
            {
                if(strpos($key,'start')!==FALSE){
                    $field_name=explode('_',$key);
                   
                    $x=array_shift($field_name);
                   $field_name=implode('_',$field_name);
                   
                    $query=$query->whereDate($field_name,'>=',\Carbon\Carbon::parse($value));
                }
                elseif(strpos($key,'end')!==FALSE){
                    $field_name=explode('_',$key);
                    $x=array_shift($field_name);
                    $field_name=implode('_',$field_name);
                    $query=$query->whereDate($field_name,'<=',\Carbon\Carbon::parse($value));
                }
                else{
                    $query=$query->where($key,$value);
                }
            }
       }
       return $query;
    }
    public function index(Request $request)
    {
       
        $searchable_fields=[['name'=>'name','label'=>'Name']];
        $filterable_fields=[['name'=>'created_at','label'=>'Date','type'=>'date']];
        $table_columns=$this->table_columns;
        if ($request->ajax())
         {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $search_by = $request->get('search_by');
           
            $query = $request->get('query');
           
            $search_val = str_replace(" ", "%", $query);
            if(empty($search_by))
               $search_by='name';
            $list = Permission::when(!empty($search_val),function($query) use($search_val,$search_by){
                         return $query->where($search_by, 'like', '%'.$search_val.'%');
                      })
                      ->when(!empty($sort_by),function($query) use($sort_by,$sort_type){
                        return $query->orderBy($sort_by, $sort_type);
                     })->paginate($this->pagination_count);
            $data=[
                'table_columns'=> $table_columns,
                'list'=>$list,
                'sort_by'=> $sort_by,
                'sort_type'=> $sort_type,
                'storage_folder'=>$this->storage_folder,
                 'plural_lowercase'=>'permissions',
                 'module'=>$this->module,
                  'has_image'=>$this->has_upload,
             'is_multiple'=>$this->is_multiple_upload,
             'image_field_name'=> $this->form_image_field_name,
             'storage_folder'=>$this->storage_folder,
             ];
          return view('admin.'.$this->view_folder.'.page',with($data));
        }
    else{
        $query=Permission::query();
        $query=$this->buildFilter($request,$query);
        $list=$query->paginate($this->pagination_count);
        $view_data=[ 
            'list'=>$list,
            'dashboard_url'=>$this->dashboard_url,
            'index_url'=>$this->index_url,
            'title'=>'All Permissions',
            'module'=>$this->module,
            'searchable_fields'=>$searchable_fields,
            'filterable_fields'=>$filterable_fields,
             'storage_folder'=>$this->storage_folder,
               'table_columns'=> $table_columns,
                'plural_lowercase'=>'permissions',
                 'has_image'=>$this->has_upload,
             'is_multiple'=>$this->is_multiple_upload,
             'image_field_name'=> $this->form_image_field_name,
             'storage_folder'=>$this->storage_folder,
            ];
         return view('admin.'.$this->view_folder.'.index',$view_data);
    }
    }
	
    public function getList($model){
        $model_class =  "app\models".'\\'.$model; 
        $lists=$model_class::get(['id','name']);
        $list=[];
        foreach($lists as $list){
          $ar=(object) ['id'=>$list->id,'name'=>$list->name];
          array_push($list,$ar);
        }
        return  $list;
    }
 
     public function create()
    {
            $data=[['placeholder'=>'Enter Permission Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']];
      
			if(count($this->columns_with_select_field)>0){
				foreach($this->columns_with_select_field as $t){
$input=['name'=>$t['field_name'],'label'=>$t['label'],'type'=>'select','multiple'=>$t['multiple'],'custom_key_for_option'=>'name','options'=>$this->getOptions($t['label']),'event'=>['name'=>'onChange','function'=>isset($t['onChange'])?'javascript:void(0)':$t['onChange']] ];
				array_push($data,$input);
				}
			}
        
        $radio_checkbox_group=null;
        $view_data=[ 
             'data'=>$data,
             'radio'=>$radio_checkbox_group,
             'dashboard_url'=>$this->dashboard_url,
             'index_url'=>$this->index_url,
             'title'=>'Create '.$this->module,
             'module'=>$this->module,
            'plural_lowercase'=>'permissions',
              'image_field_name'=> $this->form_image_field_name,
              'has_image'=>$this->has_upload,
             'is_multiple'=>$this->is_multiple_upload,
           
             'storage_folder'=>$this->storage_folder,
             ];
        return view('admin.'.$this->view_folder.'.add',with($view_data));
    }
    public function store(PermissionRequest $request)
    {
        try{
           $permission = Permission::create($request->all());
           if($this->has_upload)
           {
            
            if($this->is_multiple_upload)
                 $this->upload($request,$permission->id);
            else{
                $image_name= $this->upload($request);
                $field= $this->form_image_field_name;
                $permission->{$field}=$image_name;
                $permission->save();
            }
        }
       return createResponse(true,$this->module.' created successfully',$this->index_url); 
       }
       catch(\Exception $ex)
        {
             return createResponse(false,$ex->getMessage());
        }
    }
 public function edit($id)
    {
       
        $model=Permission::findOrFail($id);
        
        $data=[['placeholder'=>'Enter Permission Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']];
        if(count($this->columns_with_select_field)>0){
				foreach($this->columns_with_select_field as $label=>$field_name){
				$input=['name'=>$field_name,'label'=>$label,'type'=>'select','default'=>$model->{field_name},'custom_key_for_option'=>'name','options'=>$this->getOptions($label)];
				array_push($data,$input);
				}
			}
        $radio_checkbox_group=null;
       
         $view_data=[ 
             'data'=>$data,
             'radio'=>$radio_checkbox_group,
             'dashboard_url'=>$this->dashboard_url,
             'index_url'=>$this->index_url,
             'title'=>'Edit '.$this->module,
             'module'=>$this->module,
             'has_image'=>$this->has_upload,
             'is_multiple'=>$this->is_multiple_upload,
             'image_field_name'=> $this->form_image_field_name,
             'storage_folder'=>$this->storage_folder,
             'plural_lowercase'=>'permissions','model'=>$model
             ];
             if($this->has_upload && $this->is_multiple_upload)
               $view_data['image_list']=$this->getImageList($id);
        return view('admin.'.$this->view_folder.'.edit',with($view_data));
       
    }
    public function show($id)
    {
        
        $data['row'] = Permission::findOrFail($id);
        $data['has_image']=$this->has_upload;
        $data['is_multiple']=$this->is_multiple_upload;
        $data['storage_folder']=$this->storage_folder;
        if($data['is_multiple'])
        {
         
            $data['image_list']=$this->getImageList($id);
        }
        $html=view('admin.'.$this->view_folder.'.view',with($data))->render();
        return createResponse(true,$html); 
    }
 public function view(Request $request)
    {
        $id=$request->id;
         $data['row'] = Permission::findOrFail($id);
        $data['has_image']=$this->has_upload;
        $data['is_multiple']=$this->is_multiple_upload;
        $data['storage_folder']=$this->storage_folder;
        $data['table_columns']=$this->table_columns;
        if($data['is_multiple'])
        {
         
            $data['image_list']=$this->getImageList($id);
        }
        $html=view('admin.'.$this->view_folder.'.view',with($data))->render();
        return createResponse(true,$html); 
    }
    public function update(PermissionRequest $request, $id)
    {
        try
        {
            $permission = Permission::findOrFail($id);
            $permission->update($request->all());
           if($this->has_upload){
                if($this->is_multiple_upload)
                    $this->upload($request,$permission->id);
                else{
                    $image_name= $this->upload($request);
                    $field= $this->form_image_field_name;
                    $permission->{$field}=$image_name;
                    $permission->save();
                }
            }
         return createResponse(true,$this->module.' updated successfully',$this->index_url); 
         }
       catch(\Exception $ex)
         {
            return createResponse(false,$ex->getMessage());
         }
    }

    public function destroy($id)
    {
        try
        {
            Permission::destroy($id);
     
            if($this->has_upload){
                $this->deleteFile($id);
            }
           return createResponse(true,$this->module.' Deleted successfully'); 
        }
        catch(\Exception $ex){
            return createResponse(false,'Failed to  Delete Properly');
        }
        
    }
    
     public function deleteFile($id)
    {
       
       
            if(!$this->is_multiple_upload)
            {
                $model=$this->module;
                $model_field=$this->form_image_field_name;
                $rowid=$id;
                deleteSingleFileOwnTable($this->storage_folder,$model,$model_field,$rowid);
            }
            else{
                $filemodel=$this->image_model_name;
                deleteAllFilesFromRelatedTable($this->storage_folder,$this->parent_field_name,$id,$filemodel);
            }
           
       
    }
    
  
    function getImageList($id){
            $image_model=$this->image_model_name;
            $model="App\\Models\\$image_model";
           return  $model::where($this->parent_field_name,$id)->get(['id','name']);
    }
    function loadAjaxForm(Request $request){
        $data=[];
        $form_type=$request->form_type;
        $id=$request->id;
        if($form_type=='add'){
                 $data1=[['placeholder'=>'Enter Permission Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']];
       
        $radio_checkbox_group=null;
        $data=[ 
             'data'=>$data1,
             'radio'=>$radio_checkbox_group,
             'dashboard_url'=>$this->dashboard_url,
             'index_url'=>$this->index_url,
             'title'=>'Create '.$this->module,
             'module'=>$this->module,
            'plural_lowercase'=>'permissions',
              'image_field_name'=> $this->form_image_field_name,
              'has_image'=>$this->has_upload,
             'is_multiple'=>$this->is_multiple_upload,
           
             'storage_folder'=>$this->storage_folder,
             ];
                
       
        }
        if($form_type=='edit'){
               $model=Permission::findOrFail($id);
        
        $data1=[['placeholder'=>'Enter Permission Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']];
       
        $radio_checkbox_group=null;
       
         $data=[ 
             'data'=>$data1,
             'radio'=>$radio_checkbox_group,
             'dashboard_url'=>$this->dashboard_url,
             'index_url'=>$this->index_url,
             'title'=>'Edit '.$this->module,
             'module'=>$this->module,
             'has_image'=>$this->has_upload,
             'is_multiple'=>$this->is_multiple_upload,
             'image_field_name'=> $this->form_image_field_name,
             'storage_folder'=>$this->storage_folder,
             'plural_lowercase'=>'permissions','model'=>$model
             ];
             if($this->has_upload && $this->is_multiple_upload)
               $data['image_list']=$this->getImageList($id);
        }
        if($form_type=='view'){
                $data['row'] = Permission::findOrFail($id);
        $data['has_image']=$this->has_upload;
        $data['is_multiple']=$this->is_multiple_upload;
        $data['storage_folder']=$this->storage_folder;
        $data['table_columns']=$this->table_columns;
		/***if columns shown in view is difrrent from table_columns jet
		$columns=\DB::getSchemaBuilder()->getColumnListing('permissions');
        natcasesort($columns);
         
		$cols=[];
		$exclude_cols=['id','from_area','branch','to_area','coupon_id','user_id','delivery_type_id','signature','map','otp_code','incentive_checked','franchisee_id'];
		foreach($columns as $col){
			if($col=='order_unique_id')
			  $col="order_tracking_id";
			$label=ucwords(str_replace('_',' ',$col));
			
			if(!in_array($col,$exclude_cols))
			array_push($cols,['column'=>$col,'label'=>$label,'sortable'=>'No']);
		}
		$data['table_columns']=$cols;
		***/
        if($data['is_multiple'])
        {
         
            $data['image_list']=$this->getImageList($id);
        }
        }
      $html=view('admin.'.$this->view_folder.'.modal.'.$form_type,with($data))->render();
      return createResponse(true,$html);
    }
     public function exportPermission(Request $request,$type){
       
           if($type=='excel')
        return Excel::download(new \App\Exports\PermissionExport,'permissions'.date("Y-m-d H:i:s").'.xlsx',\Maatwebsite\Excel\Excel::XLSX);
        if($type=='csv')
        return Excel::download(new \App\Exports\PermissionExport,'permissions'.date("Y-m-d H:i:s").'.csv',\Maatwebsite\Excel\Excel::CSV);
        if($type=='pdf')
      return Excel::download(new \App\Exports\PermissionExport,'permissions'.date("Y-m-d H:i:s").'.pdf',\Maatwebsite\Excel\Excel::MPDF);
    
      
   
    }
	public function pdf_generate_from_html(){
		$mpdf = new \Mpdf\Mpdf(['utf-8', 'A4-C']);
		$mpdf->autoScriptToLang = true;
		$mpdf->baseScript = 1;
		$mpdf->autoVietnamese = true;
		$mpdf->autoArabic = true;
        
		 $mpdf->autoLangToFont = true;
		 $html="somehtml";
		 $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
	 
		 $mpdf->Output();
		
	}
}