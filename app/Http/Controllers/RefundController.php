<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

class RefundController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        $this->index_url = route('refunds.index');
        $this->module = 'Refunds';
        $this->view_folder = 'refunds';
        $this->storage_folder = $this->view_folder;
        $this->has_upload = 0;
        $this->is_multiple_upload = 0;
        $this->has_export = 0;
        $this->pagination_count = 100;
        $this->crud_title = 'Refunds';
        $this->show_crud_in_modal = 0;
        $this->has_popup = 0;
        $this->has_detail_view = 0;
        $this->has_side_column_input_group = 0;
        $this->form_image_field_name = [
           
           
        ];

        $this->model_relations = [
           
            [
                'name' => 'user',
                'type' => 'BelongsTo',
                'save_by_key' => '',
                'column_to_show_in_view' => 'name',
            ],
            [
                'name' => 'order',
                'type' => 'BelongsTo',
                'save_by_key' => '',
                'column_to_show_in_view' => 'id',
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
   
    public function index(Request $request)
    {
        $view_columns = [
            [
                'column' => 'user_id',
                'label' => 'Customer',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'amount',
                'label' => 'Amount',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'order_id',
                'label' => 'Customer',
                'sortable' => 'Yes',
                'link'=>'Yes'
            ],
            [
                'column' => 'created_at',
                'label' => 'Date',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'status',
                'label' => 'Refund Status',
                'sortable' => 'Yes',
            ],

        ];
        $table_columns = [
            [
                'column' => 'user_id',
                'label' => 'Customer',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'amount',
                'label' => 'Amount',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'order_id',
                'label' => 'Order #',
                'sortable' => 'Yes',
                'link'=>'Yes'
            ],
            [
                'column' => 'created_at',
                'label' => 'Date',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'status',
                'label' => 'Refund Status',
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

            $list = \App\Models\Refund::with(array_column($this->model_relations, 'name'))->when(!empty($search_val), function ($query) use ($search_val, $search_by) {
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
                'plural_lowercase' => 'refunds',
                'module' => 'Refund',
                'has_image' => 0,
                'model_relations' => [],
                'image_field_names' => [],

            ];
            return view('admin.refund.page', with($data));
        } else {

            $query = null;

            $query = \App\Models\Refund::with(array_column($this->model_relations, 'name'));

            $query = $this->buildFilter($request, $query);
            $list = $query->latest()->paginate($this->pagination_count);

            $view_data = [
                'list' => $list,

                'title' => 'Refund ',
                'searchable_fields' => $searchable_fields,
                'filterable_fields' => $filterable_fields,
                'plural_lowercase' => 'refunds',
                'table_columns' => $table_columns,
                'module' => 'ReturnItem',
                'has_export' => 0,
                'model_relations' => $this->model_relations,
                'module_table_name' => 'refund',
                'bulk_update' => json_encode([
                    'status' => ['label' => 'Refund Status', 'data' => getListFromIndexArray(['Pending', 'Paid','Cancelled'])],
                    
                ]),
                'show_view_in_popup' => false,
                'crud_title' => 'Return Item',

            ];
            return view('admin.refunds.index', $view_data);
        }
    }
}
