<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function field_exist(Request $r)
    {
        $exist = fieldExist($r->model, $r->field, $r->value);
        if ($exist) {
            return response()->json("Email already exist", 200);
        } else {
            return response()->json("true", 200);
        }

    }
    public function fetchRowFromTable(Request $r)
    {
        $table = $r->table;
        $id = $r->id;
        $row = \DB::Table($table)->whereId($id)->first();
        if ($row) {
            return createResponse(true, $row);
        } else {
            return createResponse(false, 'Not foud');
        }

    }
    public function search_table(Request $r)
    {

        $table = $r->search_table;
        $search_in_column = $r->search_by_column;
        $search_value = $r->value;
        $id_column = $r->search_id_column;
        $name_column = $r->search_name_column;
        $where = $r->has('where') ? json_decode($r->where, true) : null;
        $whereIn = $r->has('whereIn') ? json_decode($r->whereIn, true) : null;

        $query = \DB::table($table)->where($search_in_column, 'LIKE', $search_value . '%');
        if ($where) {
            $query = $query->where($where);
        }
        if ($whereIn) {
            $col = array_keys($whereIn)[0];
            $query = $query->whereIn($col, $whereIn[$col]);
        }

        $list = $query->get([$id_column, $name_column])->toArray();

        if (count($list) > 0) {
            $ar = [];
            foreach ($list as $item) {
                array_push($ar, ['id' => $item->{$id_column}, 'text' => $item->{$name_column}]);
            }
            return createResponse(true, $ar);
        } else {
            return createResponse(false, '');
        }

    }
    public function getColumnsFromTable(Request $r)
    {
        $table = $r->table;
        \Session::put('table', $table);
        $field_name = $r->field_name;
        $type = $r->type;
        $multiple = $type == 'checkbox' ? 'multiple' : '';
        $event_call = $r->has('event') ? $r->event : '';
        $cols = \Schema::getColumnListing($table);
        $resp = '';
        $resp = '<div class="form-group"><label  class="form-label">Select Fields</label>
        <select id="' . $field_name . '" name="' . $field_name . '[]" ' . $multiple . ' class="form-control"  tabindex="-1" aria-hidden="true" onchange="' . $event_call . '">';
        $options = ' <option value="" selected="" ></option>';

        foreach ($cols as $col) {
            $options .= ' <option value="' . $col . '" >' . $col . '</option>';
        }
        $resp .= $options . '</select> </div>';

        return createResponse(true, $resp);
    }
    public function getColumnsFromTableCheckbox(Request $r)
    {
        $table = $r->table;
        $field_name = $r->field_name;
        $cols = \Schema::getColumnListing($table);
        $resp = '';
        $resp = '';
        foreach ($cols as $col) {
            $resp .= '<div class="form-check form-check-inline">
        <input type="checkbox" name="' . $field_name . '[]" value="' . $col . '"
           class="form-check-input" aria-invalid="false">
        <label class="form-check-label">' . $col . '</label>
            </div>';
        }

        return createResponse(true, $resp);
    }

    public function getValidationHtml(Request $r)
    {

        $field_name = $r->field_name;

        $cols = getValidation();

        $resp = '';
        foreach ($cols as $col) {
            $resp .= '<div class="form-check form-check-inline">
                  <input  type="checkbox" name="' . $field_name . '_rules[]"  value="' . $col->value . '" class="form-check-input" aria-invalid="false">
                  <label  class="form-check-label">' . $col->label . '</label>
                  </div>';

        }

        return createResponse(true, $resp);
    }
    public function getToggableGroupHtml(Request $r)
    {

        $field_name = $r->field_name;
        $table = \Session::get('table');
        $cols = \Schema::getColumnListing($table);
        $toggable_val = '';
        $select_inputs = '<div class="form-group"><label  class="form-label">Select Inputs</label>
        <select name="' . $field_name . '_inputtype[]"  class="form-control"  tabindex="-1" aria-hidden="true" >';
        $options = ' <option value="" selected="" >Select input type</option>';
        foreach (getInputs() as $inp) {
            $options .= ' <option value="' . $inp->value . '" >' . $inp->label . '</option>';
        }

        $select_inputs .= $options . '</select> </div>';
        $select_fields = '<div class="form-group"><label  class="form-label">Select Fields</label>
        <select name="' . $field_name . '_fields[]"  class="form-control"  tabindex="-1" aria-hidden="true" >';
        $options = ' <option value="" selected="" >Select fields</option>';
        foreach ($cols as $col) {
            $options .= ' <option value="' . $col . '" >' . $col . '</option>';
        }

        $select_fields .= $options . '</select> </div>';

        $resp = '<fieldset class="form-group border p-3 fieldset">
       <legend class="w-auto px-2 legend">Inputs Generation </legend>
       <div id="nested_togggle" class="toggable_group" style="margin-bottom:5px">
           <div class="row">

               <div class="col-md-12">
                   <div class="d-flex justify-content-end">

                       <button type="button" class="btn btn-success btn-xs mr-5"
                           onclick="addPlusToggleNested()">+</button>


                       <button type="button" class="btn btn-danger btn-xs"
                           onclick="removeMinusToggleNested()">-</button>

                   </div>
               </div>
           </div>
           <div class="row copy_row1 border-1">
           <div class="col-md-2 mb-3">' . $select_fields . '
           </div>
               <div class="col-md-2 mb-3">' . $select_inputs . '
               </div>




               <div class="col-md-3 mb-3">

                   <div class="form-group">
                       <label for="name" class="form-label">Options(Comma seprated) </label>
                       <input type="text" id="module" name="' . $field_name . '_options[]" value=""
                           class="form-control valid is-valid" placeholder="toption1,option2,option3..">

                   </div>
               </div>

               <div class="col-md-2 mb-3">
                   <p>Is Multiple?
                   <div class="form-check form-check-inline">
                       <input type="checkbox" name="' . $field_name . '_multiple[]" value="Yes"
                           class="form-check-input valid is-valid" aria-invalid="false">
                       <label class="form-check-label">Yes</label>
                   </div>
                   <div class="form-check form-check-inline">
                       <input type="checkbox" name="' . $field_name . '_multiple[]" value="No"
                           class="form-check-input valid is-valid" aria-invalid="false">
                       <label class="form-check-label">No</label>
                   </div>
               </div>
               <div class="col-md-3 mb-3">

                   <div class="form-group">
                       <label for="name" class="form-label">Atrr.(Options(Comma seprated)) </label>
                       <input type="text" id="module" name="' . $field_name . '_attributes[]" value=""
                           class="form-control valid is-valid" placeholder="multiple=>true,onChange=>somfunciton">

                   </div>
               </div>

           </div>
           <hr>
     </div>

   </fieldset>';

        $toggable_val = '<label for="name" class="form-label">Value</label>
           <input type="text"  name="' . $field_name . '_toggable_val[]" class="form-control valid is-valid" placeholder="Enter conditional value" />';
        return createResponse(true, ['label' => $toggable_val, 'html' => $resp]);
    }
    public function getRepeatableHtml(Request $r)
    {

        $field_name = $r->field_name;

        $label = '';
        $select = '<div class="form-group"><label  class="form-label">Select Inputs</label>
        <select name="' . $field_name . '_inputtype[]"  id="repeatable_' . $field_name . '" class="form-control"  tabindex="-1" aria-hidden="true" >';
        $options = ' <option value="" selected="" >Select input type</option>';
        foreach (getInputs() as $inp) {
            $options .= ' <option value="' . $inp->value . '" >' . $inp->label . '</option>';
        }

        $select .= $options . '</select> </div>';

        $resp = '<fieldset class="form-group border p-3 fieldset">
       <legend class="w-auto px-2 legend">Inputs Generation </legend>
       <div  class="repeatable3" style="margin-bottom:5px">
           <div class="row">

               <div class="col-md-12">
                   <div class="d-flex justify-content-end">

                       <button type="button" class="btn btn-success btn-xs mr-5"
                           onclick="addPlusRepeatable()">+</button>


                       <button type="button" class="btn btn-danger btn-xs"
                           onclick="removeMinusRepeatable()">-</button>

                   </div>
               </div>
           </div>
           <div class="row copy_row1 border-1">
               <div class="col-md-2 mb-3">' . $select . '
               </div>
               <div class="col-md-2 mb-3">

                   <div class="form-group">
                       <label for="name" class="form-label">Key Name</label>
                       <input type="text" id="module" name="' . $field_name . '_keys[]" value=""
                           class="form-control valid is-valid" placeholder="Enter keyy name for json">

                   </div>
               </div>

               <div class="col-md-3 mb-3">

                   <div class="form-group">
                       <label for="name" class="form-label">Options(Comma seprated) </label>
                       <input type="text" id="module" name="' . $field_name . '_options[]" value=""
                           class="form-control valid is-valid" placeholder="toption1,option2,option3..">

                   </div>
               </div>

               <div class="col-md-2 mb-3">
                   <p>Is Multiple?
                   <div class="form-check form-check-inline">
                       <input type="checkbox" name="' . $field_name . '_multiple[]" value="Yes"
                           class="form-check-input valid is-valid" aria-invalid="false">
                       <label class="form-check-label">Yes</label>
                   </div>
                   <div class="form-check form-check-inline">
                       <input type="checkbox" name="' . $field_name . '_multiple[]" value="No"
                           class="form-check-input valid is-valid" aria-invalid="false">
                       <label class="form-check-label">No</label>
                   </div>
               </div>
               <div class="col-md-3 mb-3">

                   <div class="form-group">
                       <label for="name" class="form-label">Atrr.(Options(Comma seprated)) </label>
                       <input type="text" id="module" name="' . $field_name . '_attributes[]" value=""
                           class="form-control valid is-valid" placeholder="multiple=>true,onChange=>somfunciton">

                   </div>
               </div>

           </div>
           <hr>
     </div>

   </fieldset>';

        $label = '<label for="name" class="form-label">Label</label>
           <input type="text"  name="' . $field_name . '_label[]" class="form-control valid is-valid" placeholder="Enter  label" />';
        $priority = '<label for="name" class="form-label">Priority</label>
           <input type="number"  name="' . $field_name . '_priority[]" class="form-control valid is-valid" placeholder="Enter  priorit" />';
        return createResponse(true, ['label' => $label, 'html' => $resp, 'priority' => $priority]);
    }
    public function getCreateInputOptionHtml(Request $r)
    {

        $field_name = $r->field_name;
        $index = $r->cur_index;

        $label = '';
        $select = '<div class="form-group"><label  class="form-label">Select Inputs</label>
        <select id="repeatable_' . $field_name . '" name="' . $field_name . '_inputtype_create_' . $index . '[]"  class="form-control"  tabindex="-1" aria-hidden="true" >';
        $options = ' <option value="" selected="" >Select input type</option>';
        foreach (getInputs() as $inp) {
            $options .= ' <option value="' . $inp->value . '" >' . $inp->label . '</option>';
        }

        $select .= $options . '</select> </div>';

        $resp = ' <div class="row copy_row1 border-1">
               <div class="col-md-2 mb-3">' . $select . '
               </div>


               <div class="col-md-3 mb-3">

                   <div class="form-group">
                       <label for="name" class="form-label">Options(Comma seprated) </label>
                       <input type="text" name="' . $field_name . '_options_create_' . $index . '[]" value=""
                           class="form-control valid is-valid" placeholder="toption1,option2,option3..">

                   </div>
               </div>

               <div class="col-md-3 mb-3">
                   <p>Is Multiple?
                   <div class="form-check form-check-inline">
                       <input type="checkbox" name="' . $field_name . '_multiple_create_' . $index . '[]" value="Yes"
                           class="form-check-input >
                       <label class="form-check-label">Yes</label>
                   </div>
                   <div class="form-check form-check-inline">
                       <input type="checkbox" name="' . $field_name . '_multiple_create_' . $index . '[]" value="No"
                           class="form-check-input  aria-invalid="false">
                       <label class="form-check-label">No</label>
                   </div>
               </div>
               <div class="col-md-3 mb-3">

                   <div class="form-group">
                       <label for="name" class="form-label">Atrr.(Options(Comma seprated)) </label>
                       <input type="text" name="' . $field_name . '_attributes_create_' . $index . '[]" value=""
                           class="form-control valid is-valid" placeholder="multiple=>true,onChange=>somfunciton">

                   </div>
               </div>

           </div>';

        $label = '<label for="name" class="form-label">Label</label>
           <input type="text"  name="' . $field_name . '_label_create_' . $index . '[]" class="form-control valid is-valid" placeholder="Enter fiedset label" />';
        return createResponse(true, ['label' => $label, 'html' => $resp]);
    }
    public function getSideColumnInputOptionHtml(Request $r)
    {

        $field_name = $r->field_name;
        $index = $r->cur_index;

        $label = '';
        $select = '<div class="form-group"><label  class="form-label">Select Inputs</label>
        <select id="repeatable_' . $field_name . '" name="' . $field_name . '_inputtype_sidecolumn_' . $index . '[]"  class="form-control"  tabindex="-1" aria-hidden="true" >';
        $options = ' <option value="" selected="" >Select input type</option>';
        foreach (getInputs() as $inp) {
            $options .= ' <option value="' . $inp->value . '" >' . $inp->label . '</option>';
        }

        $select .= $options . '</select> </div>';

        $resp = ' <div class="row copy_row1 border-1">
               <div class="col-md-2 mb-3">' . $select . '
               </div>


               <div class="col-md-3 mb-3">

                   <div class="form-group">
                       <label for="name" class="form-label">Options(Comma seprated) </label>
                       <input type="text" name="' . $field_name . '_options_sidecolumn_' . $index . '[]" value=""
                           class="form-control valid is-valid" placeholder="toption1,option2,option3..">

                   </div>
               </div>

               <div class="col-md-3 mb-3">
                   <p>Is Multiple?
                   <div class="form-check form-check-inline">
                       <input type="checkbox" name="' . $field_name . '_multiple_sidecolumn_' . $index . '[]" value="Yes"
                           class="form-check-input >
                       <label class="form-check-label">Yes</label>
                   </div>
                   <div class="form-check form-check-inline">
                       <input type="checkbox" name="' . $field_name . '_multiple_sidecolumn_' . $index . '[]" value="No"
                           class="form-check-input  aria-invalid="false">
                       <label class="form-check-label">No</label>
                   </div>
               </div>
               <div class="col-md-3 mb-3">

                   <div class="form-group">
                       <label for="name" class="form-label">Atrr.(Options(Comma seprated)) </label>
                       <input type="text" name="' . $field_name . '_attributes_sidecolumn_' . $index . '[]" value=""
                           class="form-control valid is-valid" placeholder="multiple=>true,onChange=>somfunciton">

                   </div>
               </div>

           </div>';

        $label = '<label for="name" class="form-label">Label</label>
           <input type="text"  name="' . $field_name . '_label_sidecolumn_' . $index . '[]" class="form-control valid is-valid" placeholder="Enter fiedset label" />';
        return createResponse(true, ['label' => $label, 'html' => $resp]);
    }

    public function table_field_update(Request $r)
    {
        $table = $r->table;

        parse_str($r->form_val, $values);
        foreach ($values as $k => $v) {
            if (empty($v)) {
                unset($values[$k]);
            }

        };
        $keys = array_keys($values);
        if (in_array('delivery_status',$keys) && $table == 'orders') {

            $values['status_update_date'] = date('Y-m-d H:i:s');
        }
        if (in_array('driver_id',$keys) && $table == 'orders') {
            $otp = rand(100000, 999999);
            $values['otp'] = $otp;
        }
        $ids = json_decode($r->ids, true);
        $order_status_message = isset($values['order_status_message'])?$values['order_status_message']:'';
        unset($values['order_status_message']);
       
        \DB::table($table)->whereIn('id', $ids)->update($values);
        /****Below are particual eefect of update based on table not used in all software  */
        if (in_array('delivery_status',$keys) && $table == 'orders') {
            $order_status_from_post = $values['delivery_status'];
            
            $y = \DB::table($table)->whereIn('id', $ids)->get();
            foreach ($y as $row) {
                $delivery_updates = $row->order_delivery_updates != null ? json_decode($row->order_delivery_updates, true) : [];
                if (!empty($delivery_updates)) {
                    $insert_ar = [];
                    $status_names=array_column($delivery_updates,'name');
                    
                        if (in_array($order_status_from_post,$status_names)){
                            
                            foreach ($delivery_updates as $k => $v) {
                                $delivery_updates[$k]['date'] = date('Y-m-d H:i:s');
                                $delivery_updates[$k]['message'] = $order_status_message;

                            }
                        } else {
                         array_push($delivery_updates, [
                                'name' => $order_status_from_post,
                                 'date' => date('Y-m-d H:i:s'),
                                 'message'=>$order_status_message
                            ]);
                            
                        }
                        \DB::table($table)->whereId($row->id)->update([
                            'status_update_date'=>date('Y-m-d H:i:s'),
                            'delivery_status'=>$order_status_from_post,
                            'order_delivery_updates'=>json_encode($delivery_updates)
                        ]);
                    }
                    else{
                        $updates_ar=[
                            ['name' => $order_status_from_post,
                            'date' => date('Y-m-d H:i:s'),
                            'message'=>$order_status_message]
                        ];
                        \DB::table($table)->whereId($row->id)->update([
                            'status_update_date'=>date('Y-m-d H:i:s'),
                            'delivery_status'=>$order_status_from_post,
                            'order_delivery_updates'=>
                        json_encode($updates_ar)]);
                    }
                }

            }
        

        return createResponse(true, 'Updated successfuly');
    }
    public function singleFieldUpdateFromTable(Request $r)
    {
        $table = $r->table;
        $id = $r->id;
        $column = $r->field;
        $val = $r->val;

        \DB::table($table)->whereId($id)->update([$column => $val]);
        if ($table == 'fees_payments') {
            if ($column == 'payment_status') {
                $amount = \DB::table($table)->whereId($id)->first()->paid_amount;
                \DB::table('school_ledger')->insert(['name' => 'Fee Payment', 'amount' => $amount, 'mode' => 'Income', 'handle_by_id' => auth()->id()]);
            }

        }

        return createResponse(true, 'Updated successfuly');
    }
    public function bulkDelete(Request $r)
    {
        $table = $r->table;
        $ids = json_decode($r->ids, true);
        if (count($ids) > 0) {
            \DB::table($table)->whereIn('id', $ids)->update(['deleted_at' => date("Y-m-d H:i:s")]);
        }

        return createResponse(true, 'Deleted successfuly');
    }
    public function deleteRecordFromTable(Request $r)
    {
        $table = $r->table;
        $id = $r->id;
        if ($id) {
            \DB::table($table)->whereId($id)->update(['deleted_at' => date("Y-m-d H:i:s")]);
        }

        return createResponse(true, 'Deleted successfuly');
    }
    public function deleteFileFromTable(Request $r)
    {
        $table = $r->table;
        $id = $r->id;
        $folder = $r->folder;
        $file_name = \DB::table($table)->whereId($id)->first()->name;
        if ($file_name) {
            $path = storage_path('app/public/' . $folder . '/' . $file_name);
            if (\File::exists($path)) {
                unlink($path);
            }
            $thumbnail_path = storage_path('app/public/' . $folder . '/thumbnail');
            $or_thumbnail_path = storage_path('app/public/' . $folder . '/thumbnails');
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            if (\File::exists($thumbnail_path)) {

                foreach (array_keys(getThumbnailDimensions()) as $size) {
                    {
                        $img = basename($path) . '_' . $size . '.' . $extension;
                        $file_path = 'storage/' . $folder . '/thumbnail/' . $img;

                        if (\File::exists(public_path($file_path))) {
                            \File::delete(public_path($file_path));
                        }
                    }
                }
            }
            if (\File::exists($or_thumbnail_path)) {

                foreach (array_keys(getThumbnailDimensions()) as $size) {
                    {
                        $img = basename($path) . '_' . $size . '.' . $extension;
                        $file_path = 'storage/' . $folder . '/thumbnails/' . $img;

                        if (\File::exists(public_path($file_path))) {
                            \File::delete(public_path($file_path));
                        }
                    }
                }
            }
            \DB::table($table)->whereId($id)->delete();
            return createResponse(true, 'File deleted successfully');
        } else {
            return createResponse(false, 'Error in deleteting File');
        }

    }
    public function deleteFileFromSelf(Request $r)
    {
        $field_name = $r->field_name;
        $folder = $r->folder_name;
        $id = $r->row_id;
        
        $model_name = $r->modelName;
        $model_instance = app("App\\Models\\" . $model_name);
        $file_name = $model_instance->whereId($id)->first()->{$field_name};
       
        $model_instance->whereId($id)->update([$field_name => null]);
       
        if ($file_name) {
            $path = storage_path('app/public/' . $folder . '/' . $file_name);
            if (\File::exists($path)) {
              
                unlink($path);
            }
           
            $thumbnail_path = storage_path('app/public/' . $folder . '/thumbnail');
            $or_thumbnail_path = storage_path('app/public/' . $folder . '/thumbnails');
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            if (\File::exists($thumbnail_path)) {

                foreach (array_keys(getThumbnailDimensions()) as $size) {
                    {
                        $img = basename($path) . '_' . $size . '.' . $extension;
                        $file_path = 'storage/' . $folder . '/thumbnail/' . $img;

                        if (\File::exists(public_path($file_path))) {
                            \File::delete(public_path($file_path));
                        }
                    }
                }
            }
            if (\File::exists($or_thumbnail_path)) {

                foreach (array_keys(getThumbnailDimensions()) as $size) {
                    {
                        $img = basename($path) . '_' . $size . '.' . $extension;
                        $file_path = 'storage/' . $folder . '/thumbnails/' . $img;

                        if (\File::exists(public_path($file_path))) {
                            \File::delete(public_path($file_path));
                        }
                    }
                }
            }

            return createResponse(true, 'File deleted successfully');
        } else {
            return createResponse(false, 'Error in deleteting File');
        }

    }
    public function getCity(Request $r)
    {
        $state = $r->state;
        $t = \DB::table('cities')->where('city_state', 'LIKE', '%' . $state . '%')->get();
        $str = '';
        foreach ($t as $r) {
            $str .= "<option value='" . $r->city_id . "'>" . $r->city_name . "</option>";
        }
        return response()->json(['success' => true, 'message' => $str]);

    }
    public function getDependentSelectData(Request $r)
    {

        $dependee_key = $r->dependee_key;
        $dependent_key = $r->dependent_key;
        $table = $r->table;
        $table_id = $r->table_id;
        $value = $r->value;
        $where = $r->where;

        $t = \DB::table($table)->where($dependee_key, 'LIKE', '%' . $value . '%')->get();
        $str = '';
        foreach ($t as $r) {
            $str .= "<option value='" . $r->{$table_id} . "'>" . $r->{$dependent_key} . "</option>";
        }
        return response()->json(['success' => true, 'message' => $str]);

    }
    public function getDependentSelectDataMultipleVal(Request $r)
    {

        $dependee_key = $r->dependee_key;
        $dependent_key = $r->dependent_key;

        $table = $r->table;
        $table_id = $r->table_id;
        $value = [];
        if ($r->value) {
            $value = json_decode($r->value, true);
        }
//this is multiple values from parent select box so json decode it

        $t = \DB::table($table)->whereIn($dependee_key, $value)->get();
        $str = '';
        foreach ($t as $r) {
            $str .= "<option value='" . $r->{$table_id} . "'>" . $r->{$dependent_key} . "</option>";
        }
        return response()->json(['success' => true, 'message' => $str]);

    }
    public function load_Category(Request $request)
    { /*****load category for fast select  */
        $query = $request->input('query');
        $ar = [];
        if (!empty($query)) {
            $cat_options = \App\Models\Category::where('name', 'like', '%' . $query . '%')->orWhere('slug', 'like', '%' . $query . '%')->get(['id', 'name']);

            foreach ($cat_options as $t) {
                array_push($ar, ['text' => $t->name, 'value' => $t->id]);
            }
        }
        return response()->json($ar);
    }
    public function assignUser(Request $r)
    {
        try {
            $rowids = json_decode($r->ids, true);

            $selected_user = $r->selected_users;
            $set_in_table = $r->set_in_table;
            $field_to_set = $r->field_to_set;
            \DB::table($set_in_table)->whereIn('id', $rowids)->update([$field_to_set => $selected_user]);
            return response()->json(['success' => true, 'message' => "Assigned successfully"]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()]);

        }

    }
    public function getModelFieldValueById(Request $r)
    {
        if ($r->ajax()) {
            $model = $r->model;
            $id = $r->id;
            $field = $r->field;
            $response = getFieldById($model, $id, $field);
            return createResponse(true, $response);
        }
    }
    public function getUnitByMeterialId(Request $r)
    {
        if ($r->ajax()) {
            $id = $r->material_id;
            $row = \DB::table('input_material AS A')->select('B.name')->join('unit AS B', 'B.id', '=', 'A.unit_id')->where('A.id', $id)->first();

            $response = $row ? $row->name : '';
            return createResponse(true, $response);
        }
    }
    public function deleteInJsonColumnData(Request $r)
    {
        if ($r->ajax()) {
            \DB::beginTransaction();
            try {

                $rowid = $r->row_id;
                $json_column_name = $r->json_column_name;
                $key = $r->by_json_key;
                $json_key_val = $r->json_key_val;
                $table = $r->table;
                $t = \DB::table($table)->whereId($rowid)->first();
                if (is_null($t)) {
                    return createResponse(false, 'Please refresh the page and try again');
                }
                $existing_json_data = $t->{$json_column_name} ? json_decode($t->{$json_column_name}, true) : [];
                if (!empty($existing_json_data)) {
                    $i = 0;
                    foreach ($existing_json_data as $item) {
                        if ($item[$key] == $json_key_val) {
                            break;
                        }
                        $i++;
                    }
                    unset($existing_json_data[$i]);

                    $updated_data = json_encode(array_values($existing_json_data));
                    \DB::table($table)->whereId($rowid)->update([$json_column_name => $updated_data]);
                }
                \DB::commit();
                return createResponse(true, 'Deleted  successfullly');
            } catch (\Exception $ex) {
                \DB::rollback();
                return createResponse(false, $ex->getMessage());

            }
        } else {
            return createResponse(false, 'Invalid Request');
        }

    }
}
