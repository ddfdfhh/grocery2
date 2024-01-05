<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebsiteBannerRequest;
use App\Models\WebsiteBanner;
use File;
use \Illuminate\Http\Request;

class WebsiteBannerController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        $this->index_url = route('website_banners.index');
        $this->module = 'WebsiteBanner';
        $this->view_folder = 'website_banners';
        $this->storage_folder = $this->view_folder;
        $this->has_upload = 1;
        $this->is_multiple_upload = 0;
        $this->has_export = 0;
        $this->pagination_count = 100;
        $this->crud_title = 'Website Banner/Slider';
        $this->show_crud_in_modal = 1;
        $this->has_popup = 1;
        $this->has_detail_view = 0;
        $this->has_side_column_input_group = 0;
        $this->form_image_field_name = [
            [
                'field_name' => 'column1_image',
                'single' => true,
            ],
            [
                'field_name' => 'column2_image',
                'single' => true,
            ],
            [
                'field_name' => 'column3_image',
                'single' => true,
            ],
            [
                'field_name' => 'carousel_images',
                'single' => false,
                'parent_table_field' => 'banner_id',
                'table_name' => 'website_carousel_images',
                'image_model_name' => 'WebsiteCarouselImage',
                'has_thumbnail' => true,
            ],
        ];

        $this->model_relations = [
            [
                'name' => 'collection',
                'type' => 'BelongsTo',
                'save_by_key' => '',
                'column_to_show_in_view' => 'name',
            ]
          
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
        $data = [
            [
                'label' => null,
                'inputs' => [
                    [
                        'placeholder' => 'Enter name',
                        'name' => 'name',
                        'label' => 'Name',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->name : "",
                        'attr' => [],
                    ],
                    [
                        'name' => 'collection_id',
                        'label' => 'Link To Collection',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) && isset($model->collection_id) ? formatDefaultValueForEdit($model, 'collection_id', false) : (!empty(getList('Collection')) ? getList('Collection')[0]->id : ''),
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Collection'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'name' => 'is_slider',
                        'label' => 'Is Slider?',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => 'No',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getListFromIndexArray(['Yes', 'No']),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
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
                        'placeholder' => 'Enter name',
                        'name' => 'name',
                        'label' => 'Name',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->name : "",
                        'attr' => [],
                    ],
                    [
                        'name' => 'collection_id',
                        'label' => 'Link To Collection',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) && isset($model->collection_id) ? formatDefaultValueForEdit($model, 'collection_id', false) : (!empty(getList('Collection')) ? getList('Collection')[0]->id : ''),
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Collection'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'name' => 'is_slider',
                        'label' => 'Is Slider?',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => $model->is_slider,
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getListFromIndexArray(['Yes', 'No']),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
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
                    'default' => $g['single'] ? $this->storage_folder . '/' . $model->{$g['field_name']} : json_encode($this->getImageList($model->id, $g['table_name'], $g['parent_table_field'], $this->storage_folder)),
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
                'column' => 'column1_image',
                'label' => 'Column1 Image',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'column2_image',
                'label' => 'Column2 Image',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'column3_image',
                'label' => 'Column3 Image',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],

        ];
        $view_columns = [
            [
                'column' => null,
                'label' => '',
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
                'column' => 'is_slider',
                'label' => 'Is Slider?',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
               
            ],
            [
                'column' => 'column1_image',
                'label' => 'Column1 Image',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'column2_image',
                'label' => 'Column2 Image',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'column3_image',
                'label' => 'Column3 Image',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'collection_id',
                'label' => 'Collection Id',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'carousel_images',
                'label' => 'Carousel Images ',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ]
        ];

        $searchable_fields = [
            [
                'name' => 'name',
                'label' => 'Name',
            ],
        ];
        $filterable_fields = [
            [
                'name' => 'collection_id',
                'label' => 'Collection ',
                'type' => 'select',
                'options' => getList('Collection '),
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
            'plural_lowercase' => 'website_banners',
            'has_image' => $this->has_upload,
            'table_columns' => $table_columns,
            'view_columns' => $view_columns,

            'image_field_names' => $this->form_image_field_name,
            'storage_folder' => $this->storage_folder,
            'module_table_name' => 'website_banners',
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
        /*Use this function even when saving only related
        HasMny or many to many table from other contrller function like adding comments from single
        post page using ajax,place this function there
        or assigning vendror to porducts in case of many to many then in ajax call place there but column name shoul be in model_relations
         */
        $meta_info = $this->commonVars()['data'];
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
                                  $x= $model->{$key};

                                $x()->createMany($ar);
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
                $has_thumbnail = isset($item['has_thumbnail']) ? $item['has_thumbnail'] : false;
                if ($request->hasfile($field_name)) {
                    if (is_array($request->file($field_name))) {
                        $image_model_name = modelName($item['table_name']);
                        $parent_table_field = !empty($item['parent_table_field']) ? $item['parent_table_field'] : null;
                        $this->upload1($request->file($field_name), $has_thumbnail, $model->id, $image_model_name, $parent_table_field);
                    } else {
                        $image_name = $this->upload1($request->file($field_name), $has_thumbnail);
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
    public function common_view_data($id)
    {
        $data['row'] = null;
        if (count($this->model_relations) > 0) {
            $data['row'] = WebsiteBanner::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = WebsiteBanner::findOrFail($id);
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

            $db_query = WebsiteBanner::when(!empty($search_val), function ($query) use ($search_val, $search_by) {
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
            if (!can('list_website_banners')) {
                return redirect()->back()->withError('Dont have permission to list');
            }
            $query = null;
            if (count($this->model_relations) > 0) {
                $query = WebsiteBanner::with(array_column($this->model_relations, 'name'));
            } else {
                $query = WebsiteBanner::query();
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

            if (!can('create_website_banners')) {
                return createResponse(false, 'Dont have permission to create');
            }

            $html = view('admin.' . $this->view_folder . '.modal.add', with($view_data))->render();
            return createResponse(true, $html);
        } else {

            if (!can('create_website_banners')) {
                return redirect()->back()->withError('Dont have permission to create');
            }
            return view('admin.' . $this->view_folder . '.add', with($view_data));
        }

    }
    public function store(WebsiteBannerRequest $request)
    {
        if (!can('create_website_banners')) {
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

            $website_banner = WebsiteBanner::create($post);
            $this->afterCreateProcess($request, $post, $website_banner);
            \DB::commit();
            return createResponse(true, $this->crud_title . ' created successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();

            return createResponse(false, $ex->getMessage());
        }
    }
    public function edit(Request $request, $id)
    {

        $model = WebsiteBanner::findOrFail($id);

        $data = $this->editInputsData($model);

        $view_data = array_merge($this->commonVars($model)['data'], [
            'data' => $data, 'model' => $model,

        ]);

        if ($request->ajax()) {
            if (!can('edit_website_banners')) {
                return createResponse(false, 'Dont have permission to edit');
            }

            $html = view('admin.' . $this->view_folder . '.modal.edit', with($view_data))->render();
            return createResponse(true, $html);

        } else {
            if (!can('edit_website_banners')) {
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
            if (!can('view_website_banners')) {
                return createResponse(false, 'Dont have permission to view');
            }

            $html = view('admin.' . $this->view_folder . '.view.' . $view, with($data))->render();
            return createResponse(true, $html);

        } else {
            if (!can('view_website_banners')) {
                return redirect()->back()->withError('Dont have permission to view');
            }

            return view('admin.' . $this->view_folder . '.view.' . $view, with($data));

        }

    }

    public function update(WebsiteBannerRequest $request, $id)
    {
        if (!can('edit_website_banners')) {
            return createResponse(false, 'Dont have permission to update');
        }
        \DB::beginTransaction();

        try
        {
            $post = $request->all();

            $website_banner = WebsiteBanner::findOrFail($id);

            $post = formatPostForJsonColumn($post);
            /* Saving name alongwith id in json column takki join se na retrive karna pade
            copy this code from contrller file and paste and edit here
            $post=$this->processJsonColumnToAddNameOrAddtionalData($post);
             */
            $website_banner->update($post);
            $this->afterCreateProcess($request, $post, $website_banner);
            \DB::commit();
            return createResponse(true, $this->crud_title . ' updated successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();
            return createResponse(false, $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!can('delete_website_banners')) {
            return createResponse(false, 'Dont have permission to delete');
        }
        \DB::beginTransaction();
        try
        {
            if (WebsiteBanner::where('id', $id)->exists()) {
                WebsiteBanner::destroy($id);
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

    public function exportWebsiteBanner(Request $request, $type)
    {
        if (!can('export_website_banners')) {
            return redirect()->back()->withError('Not allowed to export');
        }
        $meta_info = $this->commonVars()['data'];
        return $this->exportModel('WebsiteBanner', 'website_banners', $type, $meta_info);

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

    public function upload1($request_files, $has_thumbnail, $parent_table_id = null, $image_model_name = null, $parent_table_field = null)
    {
        $dimensions = [
            'tiny' => ['width' => 350, 'height' => 300],
            'small' => ['width' => 500, 'height' => 250],
            'medium' => ['width' => 800, 'height' => 300],
            'large' => ['width' => 1600, 'height' => 340],
        ];
        $dimensions_carousel = [
            'tiny' => ['width' => 350, 'height' => 300],

            'medium' => ['width' => 900, 'height' => 400],
            'large' => ['width' => 1600, 'height' => 600],
        ];
      // dd(is_array($request_files) && $parent_table_id);
        $uploaded_filename = null;
        if ($request_files != null) {

            $uploaded_filename = is_array($request_files) && $parent_table_id ?
            storeMultipleFileCustomDimension($dimensions_carousel, $this->storage_folder, $request_files, $image_model_name, $parent_table_id, $parent_table_field, $has_thumbnail)
            : storeSingleFileCustomDimension($dimensions, $this->storage_folder, $request_files, $has_thumbnail);
            if (!is_array($uploaded_filename)) {
                return $uploaded_filename;
            }

        }
        return $uploaded_filename;

    }
}
