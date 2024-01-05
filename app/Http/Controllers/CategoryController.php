<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Image;
use \Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        $this->index_url = route('categories.index');
        $this->module = 'Category';
        $this->view_folder = 'categories';
        $this->storage_folder = $this->view_folder;
        $this->has_upload = 1;
        $this->is_multiple_upload = 0;
        $this->has_export = 0;
        $this->pagination_count = 100;
        $this->crud_title = 'Category';
        $this->show_crud_in_modal = 0;
        $this->has_popup = 1;
        $this->has_detail_view = 0;
        $this->has_side_column_input_group = 0;
        $this->form_image_field_name = [
            [
                'field_name' => 'image',
                'single' => true, 'has_thumbnail' => true,
            ],
            [
                'field_name' => 'banner_image',
                'single' => true, 'has_thumbnail' => false,
            ],
        ];

        $this->model_relations = [
            [
                'name' => 'category',
                'type' => 'BelongsTo',
                'save_by_key' => '',
                'column_to_show_in_view' => 'name',
            ],
        ];

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

        $lists = \App\Models\Category::get(['id', 'name']);

        $categories = [];
        foreach ($lists as $list) {
            $ar = (object) ['id' => $list['id'], 'name' => $list['name']];
            array_push($categories, $ar);
        }

        $data = [
            [
                'label' => null,
                'inputs' => [
                    [
                        'placeholder' => 'Enter name',
                        'name' => 'name',
                        'label' => 'Name*',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->name : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter category_id',
                        'name' => 'category_id',
                        'label' => 'Parent Category',
                        'tag' => 'select',
                        'type' => 'select',
                        'custom_key_for_option' => 'name',
                        'custom_id_for_option' => 'id',
                        'default' => "",
                        'options' => $categories,
                        'attr' => ['class' => 'select2'],
                        'multiple' => false,
                    ],
                    [
                        'name' => 'status',
                        'label' => 'Status',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->status) ? $model->status : 'Active',
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Active',
                                'value' => 'Active',
                            ],
                            (object) [
                                'label' => 'In-Active',
                                'value' => 'In-Active',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true,
                    ],
                    [
                        'placeholder' => 'Enter sgst',
                        'name' => 'sgst',
                        'label' => 'SGST(%)',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->sgst : "0.0",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter csgst',
                        'name' => 'csgst',
                        'label' => 'CGST(%)',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->csgst : "0.0",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter igst',
                        'name' => 'igst',
                        'label' => 'IGST(%)',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->igst : "0.0",
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

        $lists = \App\Models\Category::get(['id', 'name']);

        $categories = [];
        foreach ($lists as $list) {
            $ar = (object) ['id' => $list['id'], 'name' => $list['name']];
            array_push($categories, $ar);
        }

        $data = [
            [
                'label' => null,
                'inputs' => [
                    [
                        'placeholder' => 'Enter name',
                        'name' => 'name',
                        'label' => 'Name*',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->name : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter category_id',
                        'name' => 'category_id',
                        'label' => 'Parent Category',
                        'tag' => 'select',
                        'type' => 'select',
                        'custom_key_for_option' => 'name',
                        'custom_id_for_option' => 'id',
                        'default' => $model->category_id,
                        'options' => $categories,
                        'attr' => [],
                        'multiple' => false,
                    ],
                    [
                        'name' => 'status',
                        'label' => 'Status',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->status) ? $model->status : 'Active',
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Active',
                                'value' => 'Active',
                            ],
                            (object) [
                                'label' => 'In-Active',
                                'value' => 'In-Active',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true,
                    ],
                    [
                        'placeholder' => 'Enter sgst',
                        'name' => 'sgst',
                        'label' => 'SGST(%)',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->sgst : "0.0",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter csgst',
                        'name' => 'csgst',
                        'label' => 'CGST(%)',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->csgst : "0.0",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter igst',
                        'name' => 'igst',
                        'label' => 'IGST(%)',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->igst : "0.0",
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
                    'default' => $g['single'] ? $this->storage_folder . '/' . $model->{$g['field_name']} : json_encode($this->getImageList($id, $g['table_name'], $g['parent_table_field'], $this->storage_folder)),
                    'attr' => $g['single'] ? [] : ['multiple' => 'multiple'],
                ];
                array_push($data[0]['inputs'], $y);
            }
        }
        return $data;
    }
    public function commonVars($model = null)
    {

        $repeating_group_inputs = [];
        $toggable_group = [];

        $table_columns = [
            [
                'column' => 'name',
                'label' => 'Name',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'category_id',
                'label' => 'Parent',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'image',
                'label' => 'Image',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'banner_image',
                'label' => 'Banner Image',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'status',
                'label' => 'Status',
                'sortable' => 'Yes',

            ],
            [
                'column' => 'sgst',
                'label' => 'Sgst',
                'sortable' => 'Yes',

            ],
            [
                'column' => 'csgst',
                'label' => 'Csgst',
                'sortable' => 'Yes',

            ],
            [
                'column' => 'igst',
                'label' => 'Igst',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'created_at',
                'label' => 'Created At',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
        ];
        $view_columns = [
            
            [
                'column' => 'category_id',
                'label' => 'Parent ',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'csgst',
                'label' => 'Csgst',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'igst',
                'label' => 'Igst',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'image',
                'label' => 'Image',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'banner_image',
                'label' => 'Banner Image',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'name',
                'label' => 'Name',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'sgst',
                'label' => 'Sgst',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'status',
                'label' => 'Status',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
        ];

        $searchable_fields = [
            [
                'name' => 'name',
                'label' => 'Name',
            ],
        ];
        $filterable_fields = [
            [
                'name' => 'category_id',
                'label' => 'Category ',
                'type' => 'select',
                'options' => getList('Category '),
            ],
            [
                'name' => 'created_at',
                'label' => 'Created At',
                'type' => 'date',
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
            'plural_lowercase' => 'categories',
            'has_image' => $this->has_upload,
            'table_columns' => $table_columns,
            'view_columns' => $view_columns,

            'image_field_names' => $this->form_image_field_name,
            'storage_folder' => $this->storage_folder,
            'module_table_name' => 'categories',
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
            $data['row'] = Category::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = Category::findOrFail($id);
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

            $db_query = Category::when(!empty($search_val), function ($query) use ($search_val, $search_by) {
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
                'bulk_update' => json_encode([
                    'status' => ['label' => 'Status', 'data' => getListFromIndexArray(['Active', 'In-Active'])],
                ]),

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
            if (!can('list_categories')) {
                return redirect()->back()->withError('Dont have permission to list');
            }
            $query = null;
            if (count($this->model_relations) > 0) {
                $query = Category::with(array_column($this->model_relations, 'name'));
            } else {
                $query = Category::query();
            }
            $query = $this->buildFilter($request, $query);
            $list = $query->latest()->paginate($this->pagination_count);
            $view_data = array_merge($common_data, [

                'list' => $list,
                'bulk_update' => json_encode([
                    'status' => ['label' => 'Status', 'data' => getListFromIndexArray(['Active', 'In-Active'])],
                ]), 'tabs' => $tabs,
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

            if (!can('create_categories')) {
                return createResponse(false, 'Dont have permission to create');
            }

            $html = view('admin.' . $this->view_folder . '.modal.add', with($view_data))->render();
            return createResponse(true, $html);
        } else {

            if (!can('create_categories')) {
                return redirect()->back()->withError('Dont have permission to create');
            }
            return view('admin.' . $this->view_folder . '.add', with($view_data));
        }

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
                        $ar = json_decode($post[$key], true);
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
                $has_thumbnail = $item['has_thumbnail'];
                if ($request->hasfile($field_name)) {

                    $image_name = $this->upload1($request->file($field_name), $field_name);
                    if ($image_name) {
                        $model->{$field_name} = $image_name;
                        $model->save();
                    }

                }

            }

        }

        return $post;
    }
    public function store(CategoryRequest $request)
    {
        if (!can('create_categories')) {
            return createResponse(false, 'Dont have permission to create');
        }
        \DB::beginTransaction();

        try {
            $post = $request->all();
            $post['sgst']=empty($r->sgst)?0.0:$r->sgst;
            $post['cgst']=empty($r->cgst)?0.0:$r->cgst;
            $post['igst']=empty($r->igst)?0.0:$r->igst;
            $post = formatPostForJsonColumn($post);

            $category = Category::create($post);
            $this->afterCreateProcess($request, $post, $category);
            \DB::commit();
            return createResponse(true, $this->crud_title . ' created successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();

            return createResponse(false, $ex->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {

        $model = Category::findOrFail($id);

        $data = $this->editInputsData($model);

        $view_data = array_merge($this->commonVars($model)['data'], [
            'data' => $data, 'model' => $model,

        ]);

        if ($request->ajax()) {
            if (!can('edit_categories')) {
                return createResponse(false, 'Dont have permission to edit');
            }

            $html = view('admin.' . $this->view_folder . '.modal.edit', with($view_data))->render();
            return createResponse(true, $html);

        } else {
            if (!can('edit_categories')) {
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
            if (!can('view_categories')) {
                return createResponse(false, 'Dont have permission to view');
            }

            $html = view('admin.' . $this->view_folder . '.view.' . $view, with($data))->render();
            return createResponse(true, $html);

        } else {
            if (!can('view_categories')) {
                return redirect()->back()->withError('Dont have permission to view');
            }

            return view('admin.' . $this->view_folder . '.view.' . $view, with($data));

        }

    }

    public function update(CategoryRequest $request, $id)
    {
        if (!can('edit_categories')) {
            return createResponse(false, 'Dont have permission to update');
        }
        \DB::beginTransaction();

        try
        {
            $post = $request->all();

            $category = Category::findOrFail($id);
            $post['sgst']=empty($r->sgst)?0.0:$r->sgst;
            $post['cgst']=empty($r->cgst)?0.0:$r->cgst;
            $post['igst']=empty($r->igst)?0.0:$r->igst;
            $post = formatPostForJsonColumn($post);
            /* Saving name alongwith id in json column takki join se na retrive karna pade
            copy this code from contrller file and paste and edit here
            $post=$this->processJsonColumnToAddNameOrAddtionalData($post);
             */
            $old_image = $category->image;
            if ($request->hasFile('image')) {
                if ($old_image) {
                    $file_path = 'storage/' . $this->storage_folder . '/' . $old_image;
                    if (\File::exists(public_path($file_path))) {
                        \File::delete(public_path($file_path));
                    }

                  
                 
                }
            }
            $old_banner_image = $category->banner_image;
            if ($request->hasFile('banner_image')) {
                if ($old_banner_image) {
                    $file_path = 'storage/' . $this->storage_folder . '/' . $old_banner_image;
                    if (\File::exists(public_path($file_path))) {
                        \File::delete(public_path($file_path));
                    }

                   
                }}
            
            $category->update($post);
            $this->afterCreateProcess($request, $post, $category);
            \DB::commit();
            return createResponse(true, $this->crud_title . ' updated successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();
            return createResponse(false, $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!can('delete_categories')) {
            return createResponse(false, 'Dont have permission to delete');
        }

        try
        {
            if (Category::where('id', $id)->exists()) {
                Category::destroy($id);
            }

            if ($this->has_upload) {
                $this->deleteFile($id);
            }

            return createResponse(true, $this->module . ' Deleted successfully');
        } catch (\Exception $ex) {

            return createResponse(false, 'Failed to  Delete Properly');
        }

    }
    public function deleteFile($id)
    {

        return $this->deleteFileBase($id, $this->storage_folder);

    }

    public function exportCategory(Request $request, $type)
    {
        if (!can('export_categories')) {
            return redirect()->back()->withError('Not allowed to export');
        }
        $meta_info = $this->commonVars()['data'];
        return $this->exportModel('Category', 'categories', $type, $meta_info);

    }

    public function getImageList($id, $table, $parent_field_name)
    {

        return $this->getImageListBase($id, $table, $parent_field_name, $htis->storage_folder);
    }
    public function storeSingleFile($folder, $filerequest)
    {
        $folder = str_replace('\\', '/', $folder);
        $filenameWithExt = $filerequest->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $filerequest->getClientOriginalExtension();
        $filename = time();
        $fileNameToStore = $filename . '.' . $extension;
        //chmod(\Storage::path('public/categories'), 0755);
        $destinationPath = public_path('\\storage\\' . $folder . '\\' . $fileNameToStore);
         $destinationPath = str_replace('\\', '/', $destinationPath);
        $img = Image::make($filerequest->getRealPath())->resize(300,300, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $img->orientate()->save($destinationPath, 100);
        tinifyImage($destinationPath);
        return $fileNameToStore;
    }
   
    public function upload1($request_files, $fieldname)
    {

        $uploaded_filename = null;
        if ($request_files != null) {
            if ($fieldname == 'image') {
                $uploaded_filename = $this->storeSingleFile($this->storage_folder, $request_files);
            } else {
                $uploaded_filename = storeSingleFile($this->storage_folder, $request_files,false);
            }

        }
        return $uploaded_filename;

    }
}
