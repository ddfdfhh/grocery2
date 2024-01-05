<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Routing\Controller as BaseController;
use Maatwebsite\Excel\Facades\Excel;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use \Illuminate\Http\Request;

class Controller extends BaseController
{

    public function mail($to, $subject, $body, $attachment_path = null, $attachment_name = null)
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = false; //Enable verbose debug output
            $mail->isSMTP(); //Send using SMTP
            $mail->Host = config('mail.mailers.smtp.host');
            $mail->SMTPAuth = true; //Enable SMTP authentication
            $mail->Username = config('mail.mailers.smtp.username'); //SMTP username
            $mail->Password = config('mail.mailers.smtp.password'); //SMTP password
            $mail->SMTPSecure = config('mail.mailers.smtp.encryption'); //Enable implicit TLS encryption
            $mail->Port = config('mail.mailers.smtp.port'); //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(config('mail.from.address'), config('mail.from.name'));
            $mail->addAddress($to); //Add a recipient

            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = $subject;
            // $str=view('emails.registration_email',['user'=>auth()->user()])->render();
            $mail->Body = $body;
            if ($attachment_path) {
                $mail->addAttachment($attachment_path, $attachment_name);
            }
            $mail->send();
            return createResponse(true, 'Message has been sent');
        } catch (Exception $e) {
            return createResponse(false, $mail->ErrorInfo);

        }
    }
    public function buildFilter(Request $r, $query)
    {
        $get = $r->all();
        if (count($get) > 0 && $r->isMethod('get')) {
            unset($get['page']);
            foreach ($get as $key => $value) {
                if ((!is_array($value) && strlen($value) > 0) || (is_array($value) && count($value) > 0)) {
                    if (strpos($key, 'start') !== false) {
                        $field_name = explode('_', $key);

                        $x = array_shift($field_name);
                        $field_name = implode('_', $field_name);

                        $query = $query->whereDate($field_name, '>=', \Carbon\Carbon::parse($value));
                    } elseif (strpos($key, 'end') !== false) {
                        $field_name = explode('_', $key);
                        $x = array_shift($field_name);
                        $field_name = implode('_', $field_name);
                        $query = $query->whereDate($field_name, '<=', \Carbon\Carbon::parse($value));
                    } elseif (strpos($key, 'min') !== false) {
                        $field_name = explode('_', $key);
                        $x = array_shift($field_name);
                        $field_name = implode('_', $field_name);
                        $query = $query->where($field_name, '>=', $value);
                    } elseif (strpos($key, 'max') !== false) {
                        $field_name = explode('_', $key);
                        $x = array_shift($field_name);
                        $field_name = implode('_', $field_name);
                        $query = $query->where($field_name, '<=', $value);
                    } else {
                        if (!is_array($value)) {
                            $query = $query->where($key, $value);
                        } else {
                            //dd($value);
                            $query = $query->whereIn($key, $value);
                        }
                    }
                }
            }
        }
        return $query;
    }
    public function afterCreateProcessBase($request, $post, $model, $meta_info)
    {
        /*Use this function even when saving only related
        HasMny or many to many table from other contrller function like adding comments from single
        post page using ajax,place this function there
        or assigning vendror to porducts in case of many to many then in ajax call place there but column name shoul be in model_relations
         */
        $model_relations = $meta_info['model_relations'];
        if (count($meta_info['model_relations']) > 0) {
            if (in_array('BelongsToMany', array_column($model_relations, 'type'))) {
                foreach (array_keys($post) as $key) {

                    if (isFieldBelongsToManyToManyRelation($model_relations, $key) >= 0) {
                        /*jaise ki agar product mei vendor select karna ho multiple vendor select to unka array of ids milega
                        wo hi $ar mein hai so many to manny ke case mein many product to many vendror
                         */
                        $ar=json_decode($post[$key],true);
                        if (!empty($post[$key])) {
                            $model->{$key}()->sync($ar);
                        }

                    }
                }
            }

            if (in_array('HasMany', array_column($model_relations, 'type'))) {
                foreach (array_keys($post) as $key) {

                    if (isFieldBelongsToHasManyRelation($model_relations, $key) >= 0) {
                        if (!empty($post[$key])) {
                            $i = 0;
                            $index = 0;
                            /****Now we try to find by what sav_by_key to save hasMny model isliye pahle index find kare in model relation this $key belongs */
                            foreach ($model_relations as $rel) {
                                if ($key == $rel['name']) {
                                    $index = $i;
                                    break;
                                }
                                $i++;
                            }

                            $ar = json_decode($post[$key], true);

                            $save_by_key = $model_relations[$index]['save_by_key'];

                            if (is_array($ar) && count($ar) > 0) {
                                $ar = array_map(function ($v) use ($save_by_key) {
                                    return [$save_by_key => $v]; /****u can add oterhs column $save_by_key  like user_id to be added in hasMny table here array map */
                                }, $ar);

                                $model->{$key}()->createMany($ar);
                            }
                        }
                    }

                }
            }

        }

        if ($meta_info['has_image']) {
            foreach ($meta_info['image_field_names'] as $item) {

                $field_name = $item['field_name'];
                $single = $item['single'];
                $has_thumbnail=isset($item['has_thumbnail'])?$item['has_thumbnail']:false;
                if ($request->hasfile($field_name)) {
                    if (is_array($request->file($field_name))) {
                        $image_model_name = modelName($item['table_name']);
                        $parent_table_field = !empty($item['parent_table_field']) ? $item['parent_table_field'] : null;
                        $this->upload($request->file($field_name),$has_thumbnail, $model->id, $image_model_name, $parent_table_field);
                    } else {
                        $image_name = $this->upload($request->file($field_name),$has_thumbnail);
                        if ($image_name) {
                            $model->{$field_name} = $image_name;
                            $model->save();
                        }
                    }

                }

            }

        }

        return $post;
    }
    public function exportModel($model, $file_name, $type, $meta_info)
    {

        $export_class = "App\\Exports\\" . $model . "Export";

        $filter = [];

        $filter_date = [];
        $date_field = null;
        foreach ($_GET as $key => $val) {
            if (!empty($val)) {
                if (str_contains($key, 'start_')) {
                    $date_field = str_replace('start_', '', $key);
                    $filter_date['min'] = $val;
                } else if (str_contains($key, 'end_')) {
                    $date_field = str_replace('end_', '', $key);
                    $filter_date['max'] = $val;
                } else {
                    $filter[$key] = $val;
                }
            }

        }
        if ($type == 'excel') {
            return Excel::download(new $export_class($meta_info['model_relations'], $filter, $filter_date, $date_field), $file_name . date("Y-m-d H:i:s") . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($type == 'csv') {
            return Excel::download(new $export_class($meta_info['model_relations'], $filter, $filter_date, $date_field), $file_name . date("Y-m-d H:i:s") . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }

    }

    public function upload($request_files,$has_thumbnail, $parent_table_id = null, $image_model_name = null, $parent_table_field = null)
    {

        $uploaded_filename = null;
        if ($request_files != null) {

            $uploaded_filename = is_array($request_files) && $parent_table_id ?
            storeMultipleFile($this->storage_folder, $request_files, $image_model_name, $parent_table_id, $parent_table_field,$has_thumbnail)
            : storeSingleFile($this->storage_folder, $request_files,$has_thumbnail);
            if (!is_array($uploaded_filename)) {
                return $uploaded_filename;
            }

        }
        return $uploaded_filename;

    }
    public function deleteFileBase($id, $storage_folder)
    {
        foreach ($this->form_image_field_name as $item) {
            $field_name = $item['field_name'];
            $single = $item['single'];

            $table_name = !empty($item['table_name']) ? $item['table_name'] : null;
            $parent_table_field = !empty($item['parent_table_field']) ? $item['parent_table_field'] : null;
            if ($single) {
                $model = $this->module;
                $mod = app("App\\Models\\$model");
                $filerow = $mod->findOrFail($id);
                $image_name = $filerow->{$field_name};
                $path = storage_path('app/public/' . $this->storage_folder . '/' . $image_name);
                if (\File::exists($path)) {
                    unlink($path);

                }
            } else {
                $list = \DB::table($table_name)->where($parent_table_field, $id)->get(['name']);
                if (count($list) > 0) {
                    foreach ($list as $t) {
                        try {
                            $path = storage_path('app/public/' . $storage_folder . '/' . $t->name);
                            if (\File::exists($path)) {
                                unlink($path);

                            }
                        } catch (\Exception $ex) {

                        }
                    }
                }

            }

        }

    }
    public function getImageListBase($id, $table, $parent_field_name, $storage_folder)
    {

        $ar = \DB::table($table)->where($parent_field_name, $id)->get(['id', 'name'])->map(function ($val) use ($table,$storage_folder) {

            $val->table = $table;
            $val->folder = $storage_folder;
            return $val;
        })->toArray();
        return $ar;
    }
    public function processJsonColumnToAddNameOrAddtionalData($post){
        /****Copy this to controller and edit */
            $ids = $post['column_having_json__json__any_id'];
              $names_array = \DB::table($table_to_get_name_from_id)->whereIn('id', $ids)->pluck($get_by_key, 'id')->toArray();
             $ar = json_decode($post['column_having_json']);

            $ar = array_map(function ($v) use ($names_array) {
               
                $name = isset($names_array[$v->id]) ? $names_array[$v->id] : '';
                $v->name = $name;
               return $v;
            }, $ar);
             unset($post['column_having_json']);
             $post['column_having_json'] = json_encode($ar);
             return $post;
    }
}
