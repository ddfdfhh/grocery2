<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

class ReturnItemsController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        $this->index_url = route('return_items.index');
        $this->module = 'Return Items';
        $this->view_folder = 'return_items';
        $this->storage_folder = $this->view_folder;
        $this->has_upload = 0;
        $this->is_multiple_upload = 0;
        $this->has_export = 0;
        $this->pagination_count = 100;
        $this->crud_title = 'Return Items';
        $this->show_crud_in_modal = 0;
        $this->has_popup = 0;
        $this->has_detail_view = 0;
        $this->has_side_column_input_group = 0;
        $this->form_image_field_name = [
           
            [
                'field_name' => 'first_image',
                'single' => true,
                'has_thumbnail' => false,
            ],
            [
                'field_name' => 'second_image',
                'single' => true,
                'has_thumbnail' => false,
            ],
            [
                'field_name' => 'third_image',
                'single' => true,
                'has_thumbnail' => false,
            ],
            [
                'field_name' => 'fourth_image',
                'single' => true,
                'has_thumbnail' => false,
            ],
        ];

        $this->model_relations = [
            [
                'name' => 'product',
                'type' => 'BelongsTo',
                'save_by_key' => '',
                'column_to_show_in_view' => 'name',
            ],
            [
                'name' => 'variant',
                'type' => 'BelongsTo',
                'save_by_key' => '',
                'column_to_show_in_view' => 'name',
            ],
            [
                'name' => 'user',
                'type' => 'BelongsTo',
                'save_by_key' => '',
                'column_to_show_in_view' => 'name',
            ],
            [
                'name' => 'order_item',
                'type' => 'BelongsTo',
                'save_by_key' => '',
                'column_to_show_in_view' => 'order_id',
            ],
        ];

    }

    public function buildFilter(Request $r, $query)
    {
        $get = $r->all();
        if (count($get) > 0 && $r->isMethod('get')) {
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
    public function show(Request $request, $id)
    {
        $data= [];
        
        $data=[
            'module'=>'return_items',
            'model_relations'=>$this->model_relations,
            'image_field_names' => $this->form_image_field_name,
            'storage_folder' => $this->storage_folder,
            'module_table_name' => 'return_items',
            'has_export' => $this->has_export,
            'crud_title' => $this->crud_title,
            'show_crud_in_modal' => $this->show_crud_in_modal,
            'has_popup' => $this->has_popup,
            'has_side_column_input_group' => $this->has_side_column_input_group,
            'has_detail_view' => $this->has_detail_view,
           
        ];
        if (count($this->model_relations) > 0) {
            $data['row'] = \App\Models\ReturnItem::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        
        } else {
            $data['row'] = \App\Models\ReturnItem::findOrFail($id);
        }
        
        $data['view_columns'] = [
            [
                'label' => 'Order #',
                'column' => 'order_item_id',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
                'link'=>route('orders.view_item_id',['id'=>$data['row']->order_item_id ])
            ],
          
            [
                'column' => 'product_name',
                'label' => 'Product',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'variant_name',
                'label' => 'Variant',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'user_id',
                'label' => 'Customer',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'qty',
                'label' => 'Quantity',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],

            [
                'label' => 'Per Unit Price',
                'column' => 'per_unit_price',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'label' => 'Reason',
                'column' => 'reason',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'label' => 'details',
                'column' => 'Detail',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
           
            [
                'label' => 'First Image',
                'column' => 'first_image',
               
            ],
            [
                'label' => 'Second Image',
                'column' => 'second_image',
              
            ],
            [
                'label' => 'Third image',
                'column' => 'third_image',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'label' => 'Fourth image',
                'column' => 'fourth_image',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'label' => 'Return Status',
                'column' => 'return_status',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'label' => 'Refund Status',
                'column' => 'refund_status',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'label' => 'Return Created Date',
                'column' => 'created_at',
               
            ],
            [
                'label' => 'Returned  Date',
                'column' => 'returned_date',
              
            ],
          

        ];
        $view = $this->has_detail_view ? 'view_modal_detail' : 'view';
      
        $data['view_inputs'] = [];
        
        $data = array_merge(['plural_lowercase' => 'return_items'], $data);

        if ($request->ajax()) {
            if (!can('view_attributes')) {
                return createResponse(false, 'Dont have permission to view');
            }

            $html = view('admin.' . $this->view_folder . '.view.' . $view, with($data))->render();
            return createResponse(true, $html);

        } else {
            if (!can('view_attributes')) {
                return redirect()->back()->withError('Dont have permission to view');
            }

            return view('admin.' . $this->view_folder . '.view.' . $view, with($data));

        }

    }
    public function index(Request $request)
    {
        $view_columns = [
            [
                'column' => 'product_id',
                'label' => 'Product',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'variant_id',
                'label' => 'Variant',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'user_id',
                'label' => 'Customer',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'created_at',
                'label' => 'Date',
                'sortable' => 'Yes',
            ],

        ];
        $table_columns = [
            [
                'column' => 'product_id',
                'label' => 'Product',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'variant_id',
                'label' => 'Variant',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'user_id',
                'label' => 'Customer',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'return_status',
                'label' => 'Return Status',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'refund_status',
                'label' => 'Refund Status',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'created_at',
                'label' => 'Return Created Date',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'returned_date',
                'label' => ' Returned Date',
                'sortable' => 'Yes',
            ],

        ];
        $filterable_fields = [
            [
                'name' => 'created_at',
                'label' => 'Created At',
                'type' => 'date',
            ],
            [
                'name' => 'user_id',
                'label' => 'Customer',
                'type' => 'select',
                'options' => getListWithRoles('Customer'),
            ],
        ];
        $searchable_fields = [
            [
                'name' => 'user',
                'label' => 'Title',
                'type' => 'text',
            ],
        ];
        $this->pagination_count = 100;
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $search_by = $request->get('search_by');

            $query = $request->get('query');

            $search_val = str_replace(" ", "%", $query);
            if (empty($search_by)) {
                $search_by = 'name';
            }

            $list = \App\Models\ReturnItem::with(array_column($this->model_relations, 'name'))->when(!empty($search_val), function ($query) use ($search_val, $search_by) {
                return $query->where($search_by, 'like', '%' . $search_val . '%');
            })
                ->when(!empty($sort_by), function ($query) use ($sort_by, $sort_type) {
                    return $query->orderBy($sort_by, $sort_type);
                })->latest()->paginate($this->pagination_count);
            $data = [
                'table_columns' => $table_columns,
                'list' => $list,
                'sort_by' => $sort_by,
                'sort_type' => $sort_type,
                'storage_folder' => '',
                'plural_lowercase' => 'return_items',
                'module' => 'ComapnyLedger',
                'has_image' => 0,
                'model_relations' => [],
                'image_field_names' => [],

            ];
            return view('admin.return_item.page', with($data));
        } else {

            $query = null;

            $query = \App\Models\ReturnItem::with(array_column($this->model_relations, 'name'));

            $query = $this->buildFilter($request, $query);
            $list = $query->latest()->paginate($this->pagination_count);

            $view_data = [
                'list' => $list,

                'title' => 'Return Items',
                'searchable_fields' => $searchable_fields,
                'filterable_fields' => $filterable_fields,
                'plural_lowercase' => 'return_items',
                'table_columns' => $table_columns,
                'module' => 'ReturnItem',
                'has_export' => 0,
                'model_relations' => $this->model_relations,
                'module_table_name' => 'return_items',
                'bulk_update' => json_encode([
                    'return_status' => ['label' => 'Return Status', 'data' => getListFromIndexArray(['Pending', 'Returned','Cancelled'])],
                    'refund_status' => ['label' => 'Refund Status', 'data' => getListFromIndexArray(['Pending', 'Paid','Cancelled'])],

                ]),
                'show_view_in_popup' => false,
                'crud_title' => 'Return Item',

            ];
            return view('admin.return_items.index', $view_data);
        }
    }
}
