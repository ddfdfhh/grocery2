<?php

namespace App\Http\Controllers;

use App\Models\Product;
use File;
use \Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        $this->index_url = route('products.index');
        $this->module = 'Product';
        $this->view_folder = 'products';
        $this->storage_folder = $this->view_folder;
        $this->has_upload = 1;
        $this->is_multiple_upload = 0;
        $this->has_export = 1;
        $this->pagination_count = 100;
        $this->crud_title = 'Product';
        $this->show_crud_in_modal = 1;
        $this->has_popup = 0;
        $this->has_detail_view = 0;
        $this->has_side_column_input_group = 0;
        $this->dimensions = getThumbnailDimensions();
        $this->form_image_field_name = [
            [
                'field_name' => 'image',
                'single' => true,
                'has_thumbnail'=>true,
            ],
            [
                'field_name' => 'product_images',
                'single' => false,
                'parent_table_field' => 'product_id',
                'table_name' => 'product_images',
                'image_model_name' => 'ProductImage',
                'has_thumbnail'=>true,
            ],
        ];

        $this->model_relations = [
            [
                'name' => 'category',
                'type' => 'BelongsTo',
                'save_by_key' => '',
                'column_to_show_in_view' => 'name',
            ],
            [
                'name' => 'product_images',
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
                        'placeholder' => 'Enter name',
                        'name' => 'image',
                        'label' => 'Image',
                        'tag' => 'input',
                        'type' => 'file',
                        'default' => '',
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
                        'placeholder' => 'Enter name',
                        'name' => 'name',
                        'label' => 'Name',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->name : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter name',
                        'name' => 'image',
                        'label' => 'Image',
                        'tag' => 'input',
                        'type' => 'file',
                        'default' => '',
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

        $repeating_group_inputs = [
            [
                'colname' => 'attrributes',
                'label' => 'Attributes',
                'inputs' => [
                    [
                        'name' => 'attrributes__json__id[]',
                        'label' => 'Select Id',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => (!empty(getList('Attribute')) ? getList('Attribute')[0]->id : ''),
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Attribute'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter value',
                        'name' => 'attrributes__json__value[]',
                        'label' => 'Value',
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
                'column' => 'name',
                'label' => 'Name',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'category_id',
                'label' => 'Category',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'price',
                'label' => 'Price',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'sale_price',
                'label' => 'Sale Price',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'quantity',
                'label' => 'Quantity',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'image',
                'label' => 'Image',
                'sortable' => 'No',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'status',
                'label' => 'Status',
                'sortable' => 'No',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'visibility',
                'label' => 'Visibility',
                'sortable' => 'No',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
        ];
        $view_columns = [
           
            [
                'column' => 'name',
                'label' => 'Name',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'description',
                'label' => 'Description',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'price',
                'label' => 'Price',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'sale_price',
                'label' => 'Sale Price',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'discount_type',
                'label' => 'Discount Type',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'discount',
                'label' => 'Discount',
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
            [
                'column' => 'category_id',
                'label' => 'Category Id',
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
                'column' => 'product_images',
                'label' => 'Gallery Images',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'tags',
                'label' => 'Tags',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'short_description',
                'label' => 'Short Description',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'brand_id',
                'label' => 'Brand Id',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'attrributes',
                'label' => 'Attrributes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'unit',
                'label' => 'Unit',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'max_quantity_allowed',
                'label' => 'Max Quantity Allowed',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'quantity',
                'label' => 'Stock Quantity',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'minimum_qty_alert',
                'label' => 'Minimum Qty Alert',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
           
            [
                'column' => 'meta_title',
                'label' => 'Meta Title',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'meta_description',
                'label' => 'Meta Description',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'meta_keywords',
                'label' => 'Meta Keywords',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'created_at',
                'label' => 'Created At',
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
            [
                'name' => 'meta_title',
                'label' => 'Meta Title',
            ],
        ];
        $filterable_fields = [
            [
                'name' => 'price',
                'label' => 'Price',
                'type' => 'number',
            ],
            [
                'name' => 'sale_price',
                'label' => 'Sale Price',
                'type' => 'number',
            ],
            [
                'name' => 'discount_type',
                'label' => 'Discount Type',
                'type' => 'number',
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'number',
            ],
            [
                'name' => 'category_id',
                'label' => 'Category ',
                'type' => 'select',
                'options' => getList('Category '),
            ],
            [
                'name' => 'vendor_id',
                'label' => 'Vendor ',
                'type' => 'select',
                'options' => getList('Vendor '),
            ],
            [
                'name' => 'brand_id',
                'label' => 'Brand ',
                'type' => 'select',
                'options' => getList('Brand '),
            ],
            [
                'name' => 'stock_quantity',
                'label' => 'Stock Quantity',
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
            'plural_lowercase' => 'products',
            'has_image' => $this->has_upload,
            'table_columns' => $table_columns,
            'view_columns' => $view_columns,

            'image_field_names' => $this->form_image_field_name,
            'storage_folder' => $this->storage_folder,
            'module_table_name' => 'products',
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
            $data['row'] = Product::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = Product::findOrFail($id);
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

            $db_query = Product::when(!empty($search_val), function ($query) use ($search_val, $search_by) {
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
                    'status'=>['label'=>'Status','data'=>getListFromIndexArray(['Active','In-Active'])],
                    'visibility'=>['label'=>'Visibility','data'=>getListFromIndexArray(['Public','Hidden'])],
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
            if (!can('list_products')) {
                return redirect()->back()->withError('Dont have permission to list');
            }
            $query = null;
            if (count($this->model_relations) > 0) {
                $query = Product::with(array_column($this->model_relations, 'name'));
            } else {
                $query = Product::query();
            }
            $query = $this->buildFilter($request, $query);
            $list = $query->latest()->paginate($this->pagination_count);
            $view_data = array_merge($common_data, [

                'list' => $list,
                'bulk_update' => json_encode([
                    'status'=>['label'=>'Status','data'=>getListFromIndexArray(['Active','In-Active'])],
                    'visibility'=>['label'=>'Visibility','data'=>getListFromIndexArray(['Public','Hidden'])],
                    
                    ]), 'tabs' => $tabs,

            ]);
            $index_view = count($tabs) > 0 ? 'index_tabs' : 'index';
           
            return view('admin.' . $this->view_folder . '.' . $index_view, $view_data);
        }

    }
    public function gt($ar, $i, $s, $selected_id = null)
    {
        $i++;
        foreach ($ar as $k) {
            $selected = $selected_id == $k['id'] ? 'selected' : '';
            $s .= '<option ' . $selected . ' value="' . $k['id'] . '"> ' . str_repeat('-', $i) . $k['name'] . '</option>';

            $childs = \App\Models\Category::whereCategoryId($k['id'])->get()->toArray();
            if (count($childs) > 0) {
                $s = $this->gt($childs, $i, $s, $selected_id);
            }

        }

        return $s;
    }
    public function create(Request $r)
    {
        $cats = \App\Models\Category::whereNull('category_id')->get()->toArray();
        $s = '';
        $i = 0;
        $category_options = $this->gt($cats, $i, $s);

        $view_data = array_merge($this->commonVars()['data'], [

            'brands' => getList('Brand'),
            'category_options' => $category_options,
            'attributes' => getList('Attribute'),
            'attribute_families' => getList('AttributeFamily'),

        ]);

        if ($r->ajax()) {

            if (!can('create_products')) {
                return createResponse(false, 'Dont have permission to create');
            }

            $html = view('admin.' . $this->view_folder . '.modal.add', with($view_data))->render();
            return createResponse(true, $html);
        } else {

            if (!can('create_products')) {
                return redirect()->back()->withError('Dont have permission to create');
            }
            return view('admin.' . $this->view_folder . '.add', with($view_data));
        }

    }

    public function store(Request $request)
    {
        if (!can('create_products')) {
            return createResponse(false, 'Dont have permission to create');
        }
        \DB::beginTransaction();

        try {
            $post = $request->all();
            $post['has_variant']=isset($post['has_variant'])?'Yes':'No';
            $post['continue_selling']=isset($post['continue_selling'])?'Yes':'No';
            $post['notify_out_of_stock']=isset($post['notify_out_of_stock'])?'Yes':'No';
            $post['featured']=isset($post['featured'])?'Yes':'No';
            $post['is_combo']=isset($post['is_combo'])?'Yes':'No';
            $post['sgst']=isset($post['sgst'])?$post['sgst']:0.0;
            $post['cgst']=isset($post['cgst'])?$post['cgst']:0.0;
            $post['igst']=isset($post['igst'])?$post['igst']:0.0;
            $variants = [];
            $attributes = [];
            $variant_error=[];
            foreach ($post as $k => $v) {
                if (str_contains($k, 'variant_')) {
                    $k = str_replace('variant_', '', $k);
                    $t = explode('__', $k);
                    $variants[$t[1]][$t[0]] = is_null($v)?$post[$t[0]]:$v;
                    // if(empty($v) && $t[0]=='price'){
                    //     $variant_error[]=$k;
                    // }
                } elseif (str_contains($k, 'attribute-')) {

                    $p = explode('-', $k);
                    if(!empty($post['value-'.$v]) && !in_array($v,$attributes))
                         $attributes[] = $v;
                }
            }
            if(!empty($variant_error)){
                return createResponse(false,'Price is no set for variants '.implode(',',$variant_error));
            }
           // dd($attributes);
            $attribte_json = [];

            if (count($attributes) > 0) {
                $attr = \App\Models\Attribute::whereIn('id', $attributes)->get();

                foreach ($attr as $t) {
                    array_push($attribte_json, ['id' => $t->id, 'name' => $t->name,
                        'value' => isset($post['value-' . $t->id]) ? $post['value-' . $t->id] : '']);
                }
            }
            // dd($attribte_json);
            $post['attributes'] = !empty($attribte_json) ? json_encode($attribte_json) : null;
            $post = formatPostForJsonColumn($post);

            $product = Product::create($post);

            $this->storage_folder = 'products\\' . $product->id;
            $this->afterCreateProcess($request, $post, $product);
            $this->saveVariantWithFiles($request, $variants, $product->id);
           //   dd('okj');
            \DB::commit();
             
            return createResponse(true, $this->crud_title . ' created successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();

            return createResponse(false, $ex->getMessage() . '===' . $ex->getLine());
        }
    }
    public function saveVariantWithFiles(Request $r, $variants, $product_id)
    {
        $variant_main_image = [];
        $variant_gallery = [];
        $stored_main_files = [];
        $post = $r->all();

        foreach ($post as $k => $v) {
            if (str_contains($k, 'variant_image')) {
                $x = str_replace('variant_image__', '', $k);

                $variant_main_image[$x] = $v;
            } elseif (str_contains($k, 'variant_product_images')) {
                $x = str_replace('variant_product_images__', '', $k);

                $variant_gallery[$x] = $v;
            }

        }

        if (count($variant_main_image) > 0) {
            foreach ($variant_main_image as $k => $v) {
                $filerequest = $r->file('variant_image__' . $k);
                $folder = $this->storage_folder . '/variants';
                $filename = storeSingleFile($folder, $filerequest, true);
                // generateThumbnail($filerequest, $folder,$this->dimensions);
                $stored_main_file[$k] = $filename;
            }
        }

        $generated_variant_id = [];
        foreach ($variants as $k => $v) {
            $file = isset($stored_main_file[$k]) ? $stored_main_file[$k] : null;
            $insert_ar = [
                'product_id' => $product_id,
                'price' => $v['price'], 'name' => $k,
                'sale_price' => $v['sale_price'],
                'max_quantity_allowed'=>$v['max_quantity_allowed'],
                'quantity'=>$v['quantity'],
                'image' => $file,
            ];
            $product_variant = \App\Models\ProductVariant::create($insert_ar);
            $generated_variant_id[$k] = $product_variant->id;
        }

        if (count($variant_gallery) > 0) {
            foreach ($variant_gallery as $k => $v) {
                $filerequest = $r->file('variant_product_images__' . $k);
                $folder = $this->storage_folder . '\\variants';
                storeMultipleFile($folder, $filerequest, 'ProductVariantImage', $generated_variant_id[$k], 'product_variant_id', true);
            }
        }

    }
    public function edit(Request $request, $id)
    {

        $model = Product::with(['variants', 'images', 'variants.images'])->findOrFail($id);
        //dd($model->variants->toArray());
        $data = $this->editInputsData($model);
        $cats = \App\Models\Category::whereNull('category_id')->get()->toArray();
        $s = '';
        $i = 0;
        $category_options = $this->gt($cats, $i, $s, $model->category_id);
        $view_data = array_merge($this->commonVars($model)['data'], [
            'data' => $data, 'model' => $model, 'brands' => getList('Brand'),
            'category_options' => $category_options,
            'attributes' => getList('Attribute'),
            'attribute_families' => getList('AttributeFamily'),

        ]);

        if ($request->ajax()) {
            if (!can('edit_products')) {
                return createResponse(false, 'Dont have permission to edit');
            }

            $html = view('admin.' . $this->view_folder . '.modal.edit', with($view_data))->render();
            return createResponse(true, $html);

        } else {
            if (!can('edit_products')) {
                return redirect()->back()->withError('Dont have permission to edit');
            }

            return view('admin.' . $this->view_folder . '.edit', with($view_data));

        }

    }

    public function show(Request $request, $id)
    {
        $view = 'view' ;
        $data = $this->common_view_data($id);

        if ($request->ajax()) {
            if (!can('view_products')) {
                return createResponse(false, 'Dont have permission to view');
            }

            $html = view('admin.' . $this->view_folder . '.view.' . $view, with($data))->render();
            return createResponse(true, $html);

        } else {
            if (!can('view_products')) {
                return redirect()->back()->withError('Dont have permission to view');
            }

            return view('admin.' . $this->view_folder . '.view.' . $view, with($data));

        }

    }

    public function update(Request $request, $id)
    {
        if (!can('edit_products')) {
            return createResponse(false, 'Dont have permission to update');
        }
        \DB::beginTransaction();

        try
        {
            $post = $request->all();
            $post['sgst']=isset($post['sgst'])?$post['sgst']:0.0;
            $post['cgst']=isset($post['cgst'])?$post['cgst']:0.0;
            $post['igst']=isset($post['igst'])?$post['igst']:0.0;
            $post['has_variant']=isset($post['has_variant'])?'Yes':'No';
            $post['continue_selling']=isset($post['continue_selling'])?'Yes':'No';
            $post['notify_out_of_stock']=isset($post['notify_out_of_stock'])?'Yes':'No';
            $post['featured']=isset($post['featured'])?'Yes':'No';
            $post['is_combo']=isset($post['is_combo'])?'Yes':'No';
            $product = Product::with(['variants', 'variants.images'])->findOrFail($id);

            $variants = [];

            $attributes = [];
            $variant_error=[];
            foreach ($post as $k => $v) {
                if (str_contains($k, 'variant_')) {
                    $k = str_replace('variant_', '', $k);
                    $t = explode('__', $k);
                    $variants[$t[1]][$t[0]] =is_null($v)?$post[$t[0]]:$v;
                    // if(empty($v) && $t[0]=='price'){
                    //     $variant_error[]=$t[1];
                    // }
                } elseif (str_contains($k, 'attribute-')) {

                    $p = explode('-', $k);

                    $attributes[] = $v;
                }
            }
            if(!empty($variant_error)){
                return createResponse(false,'Price is no set for variants '.implode(',',$variant_error));
            }
            $attribte_json = [];

            if (count($attributes) > 0) {
                $attr = \App\Models\Attribute::whereIn('id', $attributes)->get();

                foreach ($attr as $t) {
                    array_push($attribte_json, ['id' => $t->id, 'name' => $t->name,
                        'value' => isset($post['value-' . $t->id]) ? $post['value-' . $t->id] : '']);
                }
            }
            if (count($variants))
            // dd($attribte_json);
            {
                $post['attributes'] = !empty($attribte_json) ? json_encode($attribte_json) : null;
            }

            $post = formatPostForJsonColumn($post);

            $product->update($post);

            $this->storage_folder = 'products\\' . $product->id;
           
            $this->saveVariantWithFilesUpdate($request, $variants, $product);

            $this->afterCreateProcess($request, $post, $product);
           // dd('ok');
            \DB::commit();
            return createResponse(true, $this->crud_title . ' updated successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();
            return createResponse(false, $ex->getMessage() . '==' . $ex->getLine());
        }
    }
    public function saveVariantWithFilesUpdate(Request $r, $variants, $product)
    {
        $variant_main_image = [];
        $variant_gallery = [];
        $stored_main_files = [];
        $post = $r->all();
        $variant_ids = [];
        $product_id = $product->id;
        $old_variants = $product->variants->toArray();
        $old_variants_name = !empty($old_variants) ? array_column($old_variants, 'name') : [];
        $cur_variants_name = array_keys($variants);
        $unmatched_ids = [];
        $is_changed_attribute = false;
        if (count($old_variants_name) > 0) {
            if (count($old_variants_name) == count($cur_variants_name)) {
                /**If count is same before and after edit then it means either all variants keys are completely same
                 *  or completely changed,so if completely changed delete all variant at one using product id  */
                $is_changed_attribute = false;
                foreach ($cur_variants_name as $k) {
                    if (!in_array($k, $old_variants_name)) {
                        $is_changed_attribute = true;
                    }

                }
                if ($is_changed_attribute) {
                    $var_main_images = []; /*collect all variant main images to delete ***/
                    $var_gallery_images = []; /*collect all variant gallery images to delete ***/
                    $var_images_id = []; /*collect all variant ids  to delete ***/
                    $variants_records = \App\Models\ProductVariant::with('images')->whereProductId($product_id)->get();
                    if ($variants_records) {
                        foreach ($variants_records as $rec) {
                            $var_main_images[] = $rec->image;
                            if ($rec->images) {

                                foreach ($rec->images as $img) {
                                    $var_images_id[] = $img->id;
                                    $var_gallery_images[] = $rec->name;
                                }
                            }
                        }
                    }
                    try { /***Delete main images  */
                        if (!empty($var_main_images)) {
                            foreach ($var_main_images as $img) {

                                $file_path = 'storage/products/' . $product_id . '/variants/' . $img;
                                if (\File::exists(public_path($file_path))) {
                                    \File::delete(public_path($file_path));
                                }
                                $extension = pathinfo($file_path, PATHINFO_EXTENSION);
                                foreach (array_keys($this->dimensions) as $size) {
                                    {
                                        $img = basename($img) . '_' . $size . '.' . $extension;
                                        $file_path = 'storage/products/' . $product_id . '/variants/thumbnail/' . $img;

                                        if (\File::exists(public_path($file_path))) {
                                            \File::delete(public_path($file_path));
                                        }
                                    }
                                }
                            }
                        }
                    } catch (\Exception $ex) {
                        dd($ex->getMessage() . '===' . $ex->getLine());
                    }
                    try { /***Delete gallery images  */

                        if (!empty($var_gallery_images)) {
                            foreach ($var_gallery_images as $img) {

                                $file_path = 'storage/products/' . $product_id . '/variants/' . $img;
                                if (\File::exists(public_path($file_path))) {
                                    \File::delete(public_path($file_path));
                                }
                                $extension = pathinfo($file_path, PATHINFO_EXTENSION);
                                foreach (array_keys($this->dimensions) as $size) {
                                    $img = basename($img) . '_' . $size . '.' . $extension;
                                    $file_path = 'storage/products/' . $product_id . '/variants/thumbnail/' . $img;
                                    if (\File::exists(public_path($file_path))) {
                                        \File::delete(public_path($file_path));
                                    }
                                }

                            }
                        }
                    } catch (\Exception $ex) {
                        dd($ex->getMessage() . '===' . $ex->getLine());
                    }
                    if (!empty($var_images_id)) {
                        try { /***Delete variant image record usign its id  */

                            \App\Models\ProductVariantImage::whereIn('product_variant_id', $var_images_id)->delete();
                        } catch (\Exception $ex) {
                            dd($ex->getMessage() . '===' . $ex->getLine());
                        }
                    }
                    try { /***Delete all variant using product id   */
                        \App\Models\ProductVariant::whereProductId($product_id)->delete();
                    } catch (\Exception $ex) {
                        dd($ex->getMessage() . '===' . $ex->getLine());
                    }
                }
            } else {

                $is_changed_attribute = true;
                foreach ($old_variants as $o) {
                    if (!in_array($o['name'], $cur_variants_name)) {
                        $unmatched_ids[] = $o;
                    }

                }
                $main_images = [];
                $var_images = [];
                foreach ($unmatched_ids as $var_row) {
                    if ($var_row['image']) {
                        $main_images[] = $var_row['image'];
                    }

                    if ($var_row['images']) {
                        foreach ($var_row['images'] as $img) {$var_images[] = $img;}
                    }
                }
                try {
                    if (!empty($main_images)) {
                        foreach ($main_images as $img) {

                            $file_path = 'storage/products/' . $product_id . '/variants/' . $img;
                            if (\File::exists(public_path($file_path))) {
                                \File::delete(public_path($file_path));
                            }
                        }
                    }
                } catch (\Exception $ex) {
                    dd($ex->getMessage() . '===' . $ex->getLine());
                }
                try {
                    if (!empty($var_images)) {
                        foreach ($var_images as $img) {

                            $file_path = 'storage/products/' . $product_id . '/variants/' . $img;
                            if (\File::exists(public_path($file_path))) {
                                \File::delete(public_path($file_path));
                            }
                        }
                    }
                } catch (\Exception $ex) {
                    dd($ex->getMessage() . '===' . $ex->getLine());
                }
                try {
                    \App\Models\ProductVariant::whereIn('id', array_column($unmatched_ids, 'id'))->delete();
                    \App\Models\ProductVariantImage::whereIn('product_variant_id', array_column($unmatched_ids, 'id'))->delete();
                } catch (\Exception $ex) {
                    dd($ex->getMessage() . '===' . $ex->getLine());
                }
            }
        } else {
            $is_changed_attribute = true;
        }

        //dd($old_variants_name);
        foreach ($post as $k => $v) {
            if (str_contains($k, 'variant_image')) {
                $x = str_replace('variant_image__', '', $k);

                $variant_main_image[$x] = $v;
            } elseif (str_contains($k, 'variant_id')) {
                $k = str_replace('variant_id__', '', $k);

                $variant_ids[$k] = $v;
            } elseif (str_contains($k, 'variant_product_images')) {
                $x = str_replace('variant_product_images__', '', $k);

                $variant_gallery[$x] = $v;
            }

        }

        if (count($variant_main_image) > 0) {
            foreach ($variant_main_image as $k => $v) {
                $filerequest = $r->file('variant_image__' . $k);
                $folder = $this->storage_folder . '/variants';
                $filename = storeSingleFile($folder, $filerequest, true);
                // generateThumbnail($filerequest, $folder,$this->dimensions);
                $stored_main_file[$k] = $filename;
            }
        }
        // echo "<pre>";print_r($variants);

        $generated_variant_id = [];
        foreach ($variants as $k => $v) {
            if (!in_array($k, $old_variants_name) || (!in_array($k, $old_variants_name) && $is_changed_attribute)) { /***agar old exist nai karta ya attribute change hue hai it means insert */
                $file = isset($stored_main_file[$k]) ? $stored_main_file[$k] : null;
                $insert_ar = [
                    'product_id' => $product_id,
                    'price' => $v['price'], 'name' => $k,
                    'sale_price' => $v['sale_price'],
                    'image' => $file,
                    'max_quantity_allowed'=>$v['max_quantity_allowed'],
                    'quantity'=>$v['quantity'],
                ];
                $product_variant = \App\Models\ProductVariant::create($insert_ar);
                $generated_variant_id[$k] = $product_variant->id;
            } else {
                if (!empty($variant_ids)) {
                    $file = isset($stored_main_file[$k]) ? $stored_main_file[$k] : null;
                    $update_ar = [
                        'product_id' => $product_id,
                        'price' => $v['price'], 'name' => $k,
                        'sale_price' => $v['sale_price'],
                        'max_quantity_allowed'=>$v['max_quantity_allowed'],
                        'quantity'=>$v['quantity'],

                    ];
                    if ($file) {
                        $update_ar['image'] = $file;
                    }

                    $variant_id = $variant_ids[$k];
                    if (\App\Models\ProductVariant::whereId($variant_id)->exists()) {
                        \App\Models\ProductVariant::whereId($variant_id)->update($update_ar);
                    }

                }
            }
        }

        if (count($variant_gallery) > 0) {
            foreach ($variant_gallery as $k => $v) {
                if (isset($generated_variant_id[$k])) {
                    $filerequest = $r->file('variant_product_images__' . $k);
                    $folder = $this->storage_folder . '\\variants';
                    storeMultipleFile($folder, $filerequest, 'ProductVariantImage', $generated_variant_id[$k], 'product_variant_id', true);
                } elseif (!empty($old_variants_name) && in_array($k, $old_variants_name)) {
                    $filerequest = $r->file('variant_product_images__' . $k);
                    $folder = $this->storage_folder . '\\variants';
                    storeMultipleFile($folder, $filerequest, 'ProductVariantImage', $variant_ids[$k], 'product_variant_id', true);
                }
            }
        }

    }
    public function destroy($id)
    {
        if (!can('delete_products')) {
            return createResponse(false, 'Dont have permission to delete');
        }

        try
        {

            $row = Product::with(['variants', 'variants.images'])->where('id', $id)->first();
            if ($row) {
                //dd($row->toArray());
                $product_id = $row->id;
                $variants = $row->variants;
                $var_ids = $variants ? array_column($variants->toArray(), 'id') : [];
                
                $file_path = 'storage/products/' . $product_id;

                try {
                    if (\File::exists(public_path($file_path))) {

                        \File::deleteDirectory(public_path($file_path));
                    }
                    foreach ($variants as $t) {

                        if ($t->images) {
                            \App\Models\ProductVariantImage::whereIn('id', array_column($t->images->toArray(), 'id'))->delete();
                        }

                    }
                } catch (\Exception $ex) {
                    dd($ex->getMessage());
                }
                \App\Models\ProductVariant::whereProductId($id)->delete();
                \App\Models\ProductImage::whereProductId($id)->delete();
                Product::destroy($id);
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

    public function exportProduct(Request $request, $type)
    {
        if (!can('export_products')) {
            return redirect()->back()->withError('Not allowed to export');
        }
        $meta_info = $this->commonVars()['data'];
        return $this->exportModel('Product', 'products', $type, $meta_info);

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
        // dd($this->storage_folder);

        return $this->getImageListBase($id, $table, $parent_field_name, $this->storage_folder);
    }
    public function generateAccordian(Request $r)
    {
        $product_id = $r->product_id;
        $combinations = $r->combinations;
        //dd($combinations);
        $rec = \App\Models\ProductVariant::with('images')->whereProductId($product_id)->get();
        $variant_names = $rec ? array_column($rec->toArray(), 'name') : [];
        $ar = [];
        foreach ($combinations as $name) {
            $found = false;
            foreach ($rec as $r) {
                if ($r->name == $name) {
                    $found = $r;
                }

            }
            if ($found) {
                $ar[] = ['name' => $name, 'product_id' => $product_id, 'row' => $found];
            } else {
                $ar[] = ['name' => $name, 'product_id' => $product_id, 'row' => null];
            }

            //    /  dd($ar);

        }
        $str = view('admin.products.html', ['ar' => $ar])->render();
        return createResponse(true, $str);
    }
}
