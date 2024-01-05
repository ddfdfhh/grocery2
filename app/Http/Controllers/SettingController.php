<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use \Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        $this->index_url = route('settings.index');
        $this->module = 'Setting';
        $this->view_folder = 'settings';
        $this->storage_folder = $this->view_folder;
        $this->has_upload = 0;
        $this->is_multiple_upload = 0;
        $this->has_export = 0;
        $this->pagination_count = 100;
        $this->crud_title = 'Setting';
        $this->show_crud_in_modal = 1;
        $this->has_popup = 1;
        $this->has_detail_view = 0;
        $this->has_side_column_input_group = 0;
        $this->form_image_field_name = [
            [
                'field_name' => 'logo_image',
                'single' => true,'has_thumbnail'=>false
            ],
        ];

        $this->model_relations = [];

    }
    public function sideColumnInputs($model = null)
    {
        $data = [
            'side_title' => 'Any Title',
            'side_inputs' => [],

        ];

        return $data;
    }
    public function createInputsData()
    {
        $data = [
            [
                'label' => null,
                'inputs' => [
                    [
                        'placeholder' => 'Enter company_name',
                        'name' => 'company_name',
                        'label' => 'Company Name',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->company_name : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter delivery_charge',
                        'name' => 'delivery_charge',
                        'label' => 'Delivery Charge',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->delivery_charge : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter sgst',
                        'name' => 'sgst',
                        'label' => 'SGST',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->sgst : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter cgst',
                        'name' => 'cgst',
                        'label' => 'CGST',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->cgst : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter message',
                        'name' => 'message',
                        'label' => 'Message',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->message : "",
                        'attr' => [],
                    ],
                   
                    [
                        'placeholder' => 'Enter website_url',
                        'name' => 'website_url',
                        'label' => 'Website Url',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->website_url : "",
                        'attr' => [],
                    ],
                    
                ],
            ],
        ];
        if (count($this->form_image_field_name) > 0) {
            foreach ($this->form_image_field_name as $g) {
                $y = [
                    'placeholder' => '',
                    'name' => $g['single'] ? $g['field_name'] : $g['field_name'] . '[]',
                    'label' => $g['single'] ? properSingularName($g['field_name']) : properPluralName($g['field_name']),
                    'tag' => 'input',
                    'type' => 'file',
                    'default' => '',
                    'attr' => $g['single'] ? [] : ['multiple' => 'multiple'],
                ];
                array_push($data[0]['inputs'], $y);
            }
        }
        return $data;
    }
    public function editInputsData($model)
    {
        $data = [
            [
                'label' => null,
                'inputs' => [
                    [
                        'placeholder' => 'Enter company_name',
                        'name' => 'company_name',
                        'label' => 'Company Name',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->company_name : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter delivery_charge',
                        'name' => 'delivery_charge',
                        'label' => 'Delivery Charge',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->delivery_charge : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter sgst',
                        'name' => 'sgst',
                        'label' => 'SGST',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->sgst : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter cgst',
                        'name' => 'cgst',
                        'label' => 'CGST',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->cgst : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter message',
                        'name' => 'message',
                        'label' => 'Message',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->message : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter website_url',
                        'name' => 'website_url',
                        'label' => 'Website Url',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->website_url : "",
                        'attr' => [],
                    ],
                   
                ],
            ],
        ];
        if (count($this->form_image_field_name) > 0) {
            foreach ($this->form_image_field_name as $g) {
                $y = [
                    'placeholder' => '',
                    'name' => $g['single'] ? $g['field_name'] : $g['field_name'] . '[]',
                    'label' => $g['single'] ? properSingularName($g['field_name']) : properPluralName($g['field_name']),
                    'tag' => 'input',
                    'type' => 'file',
                    'default' => $g['single'] ? $this->storage_folder . '/' . $model->{$g['field_name']} :
                    json_encode($this->getImageList($model->id, $g['table_name'], $g['parent_table_field'], $this->storage_folder)),
                    'attr' => $g['single'] ? [] : ['multiple' => 'multiple'],
                ];
                array_push($data[0]['inputs'], $y);
            }
        }
        return $data;
    }
    public function commonVars($model = null)
    {

        $repeating_group_inputs = [
            [
                'colname' => 'delivery_slots',
                'label' => 'Delivery Slot',
                'inputs' => [
                    [
                        'placeholder' => 'Enter name',
                        'name' => 'delivery_slots__json__name[]',
                        'label' => 'Name',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => '',
                        'attr' => [],
                    ],
                ],
                'index_with_modal' => 0,
                'modalInputBoxIdWhoseValueToSetInSelect' => '',
                'hide' => false,
            ],
            [
                'colname' => 'order_tags',
                'label' => 'Order Statuses',
                'inputs' => [
                    [
                        'placeholder' => 'Enter name',
                        'name' => 'order_tags__json__name[]',
                        'label' => 'Name',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => '',
                        'attr' => [],
                    ],
                ],
                'index_with_modal' => 0,
                'modalInputBoxIdWhoseValueToSetInSelect' => '',
                'hide' => false,
            ],
        ];
        $toggable_group = [];

        $table_columns = [
            [
                'column' => 'company_name',
                'label' => 'Company Name',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'delivery_charge',
                'label' => 'Delivery Charge',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'message',
                'label' => 'Message',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
          
           
            [
                'column' => 'website_url',
                'label' => 'Website Url',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
        ];
        $view_columns = [
           
            [
                'column' => 'company_name',
                'label' => 'Company Name',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'delivery_charge',
                'label' => 'Delivery Charge',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'sgst',
                'label' => 'SGST',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'cgst',
                'label' => 'CGST',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'delivery_charge',
                'label' => 'Delivery Charge',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'message',
                'label' => 'Message',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'delivery_slots',
                'label' => 'Delivery Slots',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
           
            
            [
                'column' => 'website_url',
                'label' => 'Website Url',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'order_tags',
                'label' => 'Order Status List',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => false,
            ],
        ];

        $searchable_fields = [
            [
                'name' => 'company_name',
                'label' => 'Company Name',
            ],
        ];
        $filterable_fields = [
            [
                'name' => 'company_name',
                'label' => 'Company Name',
                'type' => 'number',
            ],
        ];

        $data['data'] = [

            'dashboard_url' => $this->dashboard_url,
            'index_url' => $this->index_url,
            'title' => 'All ' . $this->crud_title . 's',
            'module' => $this->module,
            'model_relations' => $this->model_relations,
            'searchable_fields' => $searchable_fields,
            'filterable_fields' => $filterable_fields,
            'storage_folder' => $this->storage_folder,
            'plural_lowercase' => 'settings',
            'has_image' => $this->has_upload,
            'table_columns' => $table_columns,
            'view_columns' => $view_columns,

            'image_field_names' => $this->form_image_field_name,
            'storage_folder' => $this->storage_folder,
            'module_table_name' => 'settings',
            'has_export' => $this->has_export,
            'crud_title' => $this->crud_title,
            'show_crud_in_modal' => $this->show_crud_in_modal,
            'has_popup' => $this->has_popup,
            'has_side_column_input_group' => $this->has_side_column_input_group,
            'has_detail_view' => $this->has_detail_view,
            'repeating_group_inputs' => $repeating_group_inputs,
            'toggable_group' => $toggable_group,
        ];

        return $data;

    }
    public function afterCreateProcess($request, $post, $model)
    {
        $meta_info = $this->commonVars()['data'];

        return $this->afterCreateProcessBase($request, $post, $model, $meta_info);
    }
    public function common_view_data($id)
    {
        $data['row'] = null;
        if (count($this->model_relations) > 0) {
            $data['row'] = Setting::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = Setting::findOrFail($id);
        }
        $data['view_inputs'] = [];
        /***If you want to show any form iput in view ***
        $data['view_inputs'] = [
        [
        'label' => '',
        'inputs' => [
        [
        'placeholder' => 'Enter title',
        'name' => 'title',
        'label' => 'Title',
        'tag' => 'input',
        'type' => 'text',
        'default' => '',
        'attr' => [],
        ],
        [
        'placeholder' => 'Enter remark',
        'name' => 'remark',
        'label' => 'Remark',
        'tag' => 'input',
        'type' => 'file',
        'default' => '',
        'attr' => ['class'=>'summernote'],
        ],
        ],
        ],
        ];
         ***/
        $data = array_merge($this->commonVars()['data'], $data);
        // dd($data);
        return $data;
    }
    public function index(Request $request)
    {

        $tabs = [
            /*[
        'label' => 'Active',
        'value' => 'Active',
        'count' => 1,
        'column' => 'status',
        ],
        [
        'label' => 'In-Active',
        'value' => 'In-Active',
        'count' => 3,
        'column' => 'status',
        ],*/
        ];
        $common_data = $this->commonVars()['data'];
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $search_by = $request->get('search_by');
            $query = $request->get('query');

            $search_val = str_replace(" ", "%", $query);
            if (empty($search_by)) {
                $search_by = 'name';
            }

            $tabs_column = count($tabs) > 0 ? array_column($tabs, 'column') : [];

            $db_query = Setting::when(!empty($search_val), function ($query) use ($search_val, $search_by) {
                return $query->where($search_by, 'like', '%' . $search_val . '%');
            })
                ->when(!empty($sort_by), function ($query) use ($sort_by, $sort_type) {
                    return $query->orderBy($sort_by, $sort_type);
                });

            if (count($tabs_column) > 0) {
                foreach ($tabs_column as $col) {
                    if ($request->has($col) && !empty($request->{$col})) {
                        $db_query = $db_query->where($col, $request->{$col});
                    }

                }

            }

            $list = $db_query->latest()->paginate($this->pagination_count);
            $data = array_merge($common_data, [

                'list' => $list,
                'sort_by' => $sort_by,
                'sort_type' => $sort_type,
                'bulk_update' => '',

                /*
            Multi rows select karke koi column mein values update kara ho jaise status update,user assign
            'bulk_update' => json_encode([
            'status'=>['label'=>'Status','data'=>getListFromIndexArray(['Active','In-Active'])],
            'user_id'=>['label'=>'Assign User','data'=>getList('User')]

            ])
             */

            ]);
            return view('admin.' . $this->view_folder . '.page', with($data));
        } else {
            if (!can('list_settings')) {
                return redirect()->back()->withError('Dont have permission to list');
            }
            $query = null;
            if (count($this->model_relations) > 0) {
                $query = Setting::with(array_column($this->model_relations, 'name'));
            } else {
                $query = Setting::query();
            }
            $query = $this->buildFilter($request, $query);
            $list = $query->latest()->paginate($this->pagination_count);
            $view_data = array_merge($common_data, [

                'list' => $list,
                'bulk_update' => '', 'tabs' => $tabs,
                /*
            Multi rows select karke koi column mein values update kara ho jaise status update,user assign
            'bulk_update' => json_encode([
            'status'=>['label'=>'Status','data'=>getListFromIndexArray(['Active','In-Active'])],
            'user_id'=>['label'=>'Assign User','data'=>getList('User')]

            ])
             */

            ]);
            $index_view = count($tabs) > 0 ? 'index_tabs' : 'index';
            return view('admin.' . $this->view_folder . '.' . $index_view, $view_data);
        }

    }

    public function create(Request $r)
    {
        $data = $this->createInputsData();
        $view_data = array_merge($this->commonVars()['data'], [
            'data' => $data,

        ]);

        if ($r->ajax()) {

            if (!can('create_settings')) {
                return createResponse(false, 'Dont have permission to create');
            }

            $html = view('admin.' . $this->view_folder . '.modal.add', with($view_data))->render();
            return createResponse(true, $html);
        } else {

            if (!can('create_settings')) {
                return redirect()->back()->withError('Dont have permission to create');
            }
            return view('admin.' . $this->view_folder . '.add', with($view_data));
        }

    }
    public function store(SettingRequest $request)
    {
        if (!can('create_settings')) {
            return createResponse(false, 'Dont have permission to create');
        }
        \DB::beginTransaction();

        try {
            $post = $request->all();

            $post = formatPostForJsonColumn($post);
            /* Saving name alongwith id in json column takki join se na retrive karna pade
            copy this code from contrller file and paste and edit here
            $post=$this->processJsonColumnToAddNameOrAddtionalData($post);
             */

            $setting = Setting::create($post);
            $this->afterCreateProcess($request, $post, $setting);
            \DB::commit();
            return createResponse(true, $this->crud_title . ' created successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();

            return createResponse(false, $ex->getMessage());
        }
    }
    public function edit(Request $request, $id)
    {

        $model = Setting::findOrFail($id);

        $data = $this->editInputsData($model);

        $view_data = array_merge($this->commonVars($model)['data'], [
            'data' => $data, 'model' => $model,

        ]);

        if ($request->ajax()) {
            if (!can('edit_settings')) {
                return createResponse(false, 'Dont have permission to edit');
            }

            $html = view('admin.' . $this->view_folder . '.modal.edit', with($view_data))->render();
            return createResponse(true, $html);

        } else {
            if (!can('edit_settings')) {
                return redirect()->back()->withError('Dont have permission to edit');
            }

            return view('admin.' . $this->view_folder . '.edit', with($view_data));

        }

    }

    public function show(Request $request, $id)
    {
        $view = $this->has_detail_view ? 'view_modal_detail' : 'view_modal';
        $data = $this->common_view_data($id);

        if ($request->ajax()) {
            if (!can('view_settings')) {
                return createResponse(false, 'Dont have permission to view');
            }

            $html = view('admin.' . $this->view_folder . '.view.' . $view, with($data))->render();
            return createResponse(true, $html);

        } else {
            if (!can('view_settings')) {
                return redirect()->back()->withError('Dont have permission to view');
            }

            return view('admin.' . $this->view_folder . '.view.' . $view, with($data));

        }

    }

    public function update(SettingRequest $request, $id)
    {
        if (!can('edit_settings')) {
            return createResponse(false, 'Dont have permission to update');
        }
        \DB::beginTransaction();

        try
        {
            $post = $request->all();

            $setting = Setting::findOrFail($id);

            $post = formatPostForJsonColumn($post);
            /* Saving name alongwith id in json column takki join se na retrive karna pade
            copy this code from contrller file and paste and edit here
            $post=$this->processJsonColumnToAddNameOrAddtionalData($post);
             */
           // dd($post);
            $setting->update($post);
            $this->afterCreateProcess($request, $post, $setting);
            \DB::commit();
            return createResponse(true, $this->crud_title . ' updated successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();
            return createResponse(false, $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!can('delete_settings')) {
            return createResponse(false, 'Dont have permission to delete');
        }
        \DB::beginTransaction();
        try
        {
            if (Setting::where('id', $id)->exists()) {
                Setting::destroy($id);
            }

            if ($this->has_upload) {
                $this->deleteFile($id);
            }
            \DB::commit();
            return createResponse(true, $this->module . ' Deleted successfully');
        } catch (\Exception $ex) {
            \DB::rollback();
            return createResponse(false, 'Failed to  Delete Properly');
        }

    }
    public function deleteFile($id)
    {

        return $this->deleteFileBase($id, $this->storage_folder);

    }

    public function exportSetting(Request $request, $type)
    {
        if (!can('export_settings')) {
            return redirect()->back()->withError('Not allowed to export');
        }
        $meta_info = $this->commonVars()['data'];
        return $this->exportModel('Setting', 'settings', $type, $meta_info);

    }
    public function load_toggle(Request $r)
    {
        $value = trim($r->val);
        $rowid = $r->has('row_id') ? $r->row_id : null;
        $row = null;
        if ($rowid) {
            $model = app("App\\Models\\" . $this->module);
            $row = $model::where('id', $rowid)->first();
        }
        $index_of_val = 0;
        $is_value_present = false;
        $i = 0;
        foreach ($this->toggable_group as $val) {

            if ($val['onval'] == $value) {

                $is_value_present = true;
                $index_of_val = $i;
                break;
            }
            $i++;
        }
        if ($is_value_present) {
            if ($row) {
                $this->toggable_group = [];

            }
            $data['inputs'] = $this->toggable_group[$index_of_val]['inputs'];

            $v = view('admin.attribute_families.toggable_snippet', with($data))->render();
            return createResponse(true, $v);
        } else {
            return createResponse(true, "");
        }

    }
    public function getImageList($id, $table, $parent_field_name)
    {

        return $this->getImageListBase($id, $table, $parent_field_name, $this->storage_folder);
    }
}
