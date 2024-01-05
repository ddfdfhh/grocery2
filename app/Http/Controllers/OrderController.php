<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrdersRequest;
use App\Models\Order;
use \Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        $this->index_url = route('orders.index');
        $this->module = 'Orders';
        $this->view_folder = 'orders';
        $this->storage_folder = $this->view_folder;
        $this->has_upload = 0;
        $this->is_multiple_upload = 0;
        $this->has_export = 0;
        $this->pagination_count = 100;
        $this->crud_title = 'Order';
        $this->show_crud_in_modal = 0;
        $this->has_popup = 0;
        $this->has_detail_view = 1;
        $this->has_side_column_input_group = 0;
        $this->form_image_field_name = [];

        $this->model_relations = [
            [
                'name' => 'items',
                'type' => 'HasMany',
                'save_by_key' => 'name',
                'column_to_show_in_view' => 'name',
            ],
            [
                'name' => 'user',
                'type' => 'BelongsTo',
                'save_by_key' => '',
                'column_to_show_in_view' => 'name',
            ],
            [
                'name' => 'driver',
                'type' => 'BelongsTo',
                'save_by_key' => '',
                'column_to_show_in_view' => 'name',
            ],
            [
                'name' => 'applied_coupons',
                'type' => 'HasMany',
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
                        'placeholder' => 'Enter gross_total',
                        'name' => 'gross_total',
                        'label' => 'Total',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->gross_total : "",
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
        $settings = \DB::table('settings')->first();
        $order_status_list = $settings->order_tags ? array_column(json_decode($settings->order_tags, true), 'name') : [];

        $repeating_group_inputs = [
            [
                'colname' => 'order_delivery_updates',
                'label' => 'Order Status',
                'inputs' => [
                    [
                        'name' => 'order_delivery_updates__json__name[]',
                        'label' => 'Select Status',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => 'Order Placed',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => !empty($order_status_list) ? getListFromIndexArray($order_status_list) : [
                            (object) [
                                'id' => 'Order Placed',
                                'name' => 'Order Placed',
                            ],
                            (object) [
                                'id' => 'Out For Delivery',
                                'name' => 'Out For Delivery',
                            ],
                            (object) [
                                'id' => 'Delivered',
                                'name' => 'Delivered',
                            ],
                            (object) [
                                'id' => 'Cancelled',
                                'name' => 'Cancelled',
                            ],
                            (object) [
                                'id' => 'Exchanged',
                                'name' => 'Exchanged',
                            ],
                            (object) [
                                'id' => 'Partially Exchanged',
                                'name' => 'Partially Exchanged',
                            ],
                            (object) [
                                'id' => 'Returned',
                                'name' => 'Returned',
                            ],
                            (object) [
                                'id' => 'Partially Returned',
                                'name' => 'Partially Returned',
                            ],
                        ],
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter message',
                        'name' => 'order_delivery_updates__json__message[]',
                        'label' => 'Any Status Message',
                        'tag' => 'textarea',
                        'type' => 'textarea',
                        'default' => '',
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter date',
                        'name' => 'order_delivery_updates__json__date[]',
                        'label' => 'Date',
                        'tag' => 'input',
                        'type' => 'datetime-local',
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
                'column' => 'total_amount',
                'label' => 'Amount Before Discount',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'total_amount_after_discount',
                'label' => 'Amount After Discount',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'net_payable',
                'label' => 'Paid Amount',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'shipping_cost',
                'label' => 'Delivery Cost',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'uuid',
                'label' => 'Order No',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'user_id',
                'label' => 'Customer Name',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'no_of_items',
                'label' => 'No Of Items',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'delivery_status',
                'label' => 'Delivery Status',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'paid_status',
                'label' => 'Paid Status',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'created_at',
                'label' => 'Order Date',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
        ];
        $view_columns = [

            [
                'column' => 'uuid',
                'label' => 'Order No',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
           
            [
                'column' => 'order_delivery_updates',
                'label' => 'Order Delivery Updates',
                'show_json_button_click' => true,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'total_amount',
                'label' => 'Total Amount Before Discount',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'net_payable',
                'label' => 'Paid',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'total_amount_after_discount',
                'label' => 'Total Amount After Discount',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'total_tax',
                'label' => 'Total Tax',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'total_discount',
                'label' => 'Total Discount',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'shipping_cost',
                'label' => 'Shipping Cost',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'paid_status',
                'label' => 'Paid Status',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
           
           
            [
                'column' => 'user_id',
                'label' => 'User Id',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'driver_id',
                'label' => 'Driver',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'delivered_date',
                'label' => 'Delivered Date',
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
           
            [
                'column' => 'cart_level_discount',
                'label' => ' Cart Level Discount',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'no_of_items',
                'label' => 'No Of Items',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
           
        ];

        $searchable_fields = [
            [
                'name' => 'uuid',
                'label' => 'Order No',
            ],
            [
                'name' => 'cart_session_id',
                'label' => 'Cart Session ',
            ],
        ];
        $filterable_fields = [
            [
                'name' => 'gross_total',
                'label' => 'Gross Total',
                'type' => 'number',
            ],
            [
                'name' => 'net_payable',
                'label' => 'Net Payable',
                'type' => 'number',
            ],
            [
                'name' => 'paid_status',
                'label' => 'Paid Status',
                'type' => 'select',
                'options' => getListFromIndexArray(['Order Placed', 'Paid', 'Unpaid']),
            ],
            [
                'name' => 'delivery_status',
                'label' => 'Delivery Status',
                'type' => 'select',
                'options' => getListFromIndexArray(['Order Placed', 'Processing', 'Delivered', 'Shipped', 'Cancelled', 'Returned']),
            ],
            [
                'name' => 'user_id',
                'label' => 'User ',
                'type' => 'select',
                'options' => getListWithRoles('Customer'),
            ],
            [
                'name' => 'delivered_date',
                'label' => 'Delivered Date',
                'type' => 'number',
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
            'plural_lowercase' => 'orders',
            'has_image' => $this->has_upload,
            'table_columns' => $table_columns,
            'view_columns' => $view_columns,

            'image_field_names' => $this->form_image_field_name,
            'storage_folder' => $this->storage_folder,
            'module_table_name' => 'orders',
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
        $data['row'] = Order::with(['items', 'user:id,name,email,address,phone,pincode', 'driver:id,name,email,address,phone,pincode',
            'applied_coupons.coupon:id,name'])->findOrFail($id);

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
        $settings = \DB::table('settings')->first();
        $order_status_list = $settings->order_tags ? array_column(json_decode($settings->order_tags, true), 'name') : [];
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

            $db_query = Order::when(!empty($search_val), function ($query) use ($search_val, $search_by) {
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

            $order_status_list = $settings->order_tags ? array_column(json_decode($settings->order_tags, true), 'name') : [];
            $data = array_merge($common_data, [

                'list' => $list,
                'sort_by' => $sort_by,
                'sort_type' => $sort_type,
                'bulk_update' => '',
                'bulk_update' => json_encode([
                    'delivery_status' => ['label' => 'Delivery Status',
                        'data' => !empty($order_status_list) ? getListFromIndexArray($order_status_list) : getListFromIndexArray(['Order Placed', 'Out For Delivery', 'Delivered', 'Cancelled', 'Returned', 'Partially Returned'])],
                    'driver_id' => ['label' => 'Assign Driver', 'data' => getListWithRoles('Driver')],
                    'order_status_message' => ['label' => 'Status Message', 'input_type' => 'textarea', 'data' => ''],
                    'paid_status' => ['label' => 'Payment Status', 'data' => getListFromIndexArray(['Order Placed', 'Paid', 'Refunded'])],
                ]),

            ]);
            return view('admin.' . $this->view_folder . '.page', with($data));
        } else {
            if (!can('list_orders')) {
                return redirect()->back()->withError('Dont have permission to list');
            }
            $query = null;
            if (count($this->model_relations) > 0) {
                $query = Order::with(array_column($this->model_relations, 'name'));
            } else {
                $query = Order::query();
            }
            $query = $this->buildFilter($request, $query);
            $list = $query->latest()->paginate($this->pagination_count);
            $view_data = array_merge($common_data, [

                'list' => $list,
                'tabs' => $tabs,
                'bulk_update' => json_encode([
                    'delivery_status' => ['label' => 'Delivery Status',
                        'data' => !empty($order_status_list) ? getListFromIndexArray($order_status_list) : getListFromIndexArray(['Order Placed', 'Out For Delivery', 'Delivered', 'Cancelled', 'Returned', 'Partially Returned'])],
                    'driver_id' => ['label' => 'Assign Driver', 'data' => getListWithRoles('Driver')],
                    'order_status_message' => ['label' => 'Status Message', 'input_type' => 'textarea', 'data' => ''],
                    'paid_status' => ['label' => 'Payment Status', 'data' => getListFromIndexArray(['Order Placed', 'Paid', 'Refunded'])],
                ]),
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

            if (!can('create_orders')) {
                return createResponse(false, 'Dont have permission to create');
            }

            $html = view('admin.' . $this->view_folder . '.modal.add', with($view_data))->render();
            return createResponse(true, $html);
        } else {

            if (!can('create_orders')) {
                return redirect()->back()->withError('Dont have permission to create');
            }
            return view('admin.' . $this->view_folder . '.add', with($view_data));
        }

    }
    public function store(OrdersRequest $request)
    {
        if (!can('create_orders')) {
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

            $order = Order::create($post);
            $this->afterCreateProcess($request, $post, $order);
            \DB::commit();
            return createResponse(true, $this->crud_title . ' created successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();

            return createResponse(false, $ex->getMessage());
        }
    }
    public function edit(Request $request, $id)
    {

        $model = Order::findOrFail($id);

        $data = $this->editInputsData($model);

        $view_data = array_merge($this->commonVars($model)['data'], [
            'data' => $data, 'model' => $model,

        ]);

        if ($request->ajax()) {
            if (!can('edit_orders')) {
                return createResponse(false, 'Dont have permission to edit');
            }

            $html = view('admin.' . $this->view_folder . '.modal.edit', with($view_data))->render();
            return createResponse(true, $html);

        } else {
            if (!can('edit_orders')) {
                return redirect()->back()->withError('Dont have permission to edit');
            }

            return view('admin.' . $this->view_folder . '.edit', with($view_data));

        }

    }

    public function show(Request $request, $id)
    {
        //  dd('here');

        $view = 'view_detail';
        $data = $this->common_view_data($id);
      
        return view('admin.' . $this->view_folder . '.view.' . $view, with($data));

    }

    public function update(OrdersRequest $request, $id)
    {
        if (!can('edit_orders')) {
            return createResponse(false, 'Dont have permission to update');
        }
        \DB::beginTransaction();

        try
        {
            $post = $request->all();

            $order = Order::findOrFail($id);

            $post = formatPostForJsonColumn($post);
            /* Saving name alongwith id in json column takki join se na retrive karna pade
            copy this code from contrller file and paste and edit here
            $post=$this->processJsonColumnToAddNameOrAddtionalData($post);
             */
            $order->update($post);
            $this->afterCreateProcess($request, $post, $order);
            \DB::commit();
            return createResponse(true, $this->crud_title . ' updated successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();
            return createResponse(false, $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!can('delete_orders')) {
            return createResponse(false, 'Dont have permission to delete');
        }
        \DB::beginTransaction();
        try
        {
            if (Order::where('id', $id)->exists()) {
                Order::destroy($id);
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

    public function exportOrders(Request $request, $type)
    {
        if (!can('export_orders')) {
            return redirect()->back()->withError('Not allowed to export');
        }
        $meta_info = $this->commonVars()['data'];
        return $this->exportModel('Orders', 'orders', $type, $meta_info);

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
    public function show_order_related_to_item_id(Request $r, $id)
    {

        $order_id = \DB::table('order_items')->whereId($id)->first()->order_id;
        $view = 'view_detail';
        $data = $this->common_view_data($order_id);
        $data['orders_items'] = \DB::table('order_items')->whereId($order_id)->get();
        //dd($id);
        return view('admin.' . $this->view_folder . '.view.' . $view, with($data));

    }
    public function driver_orders(Request $request, $driver_id)
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

            $db_query = Order::whereDriverId($driver_id)->when(!empty($search_val), function ($query) use ($search_val, $search_by) {
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
                'bulk_update' => json_encode([
                    'delivery_status' => ['label' => 'Delivery Status',
                        'data' => getListFromIndexArray(['Order Placed', 'Out For Delivery', 'Delivered', 'Cancelled', 'Returned', 'Partially Returned'])],
                    'driver_id' => ['label' => 'Assign Driver', 'data' => getListWithRoles('Driver'),
                        'status_message' => ['label' => 'Status Message', 'input_type' => 'TextArea', 'data' => ''],
                    ],

                ]),

            ]);
            return view('admin.' . $this->view_folder . '.page', with($data));
        } else {
            if (!can('list_orders')) {
                return redirect()->back()->withError('Dont have permission to list');
            }
            $query = null;
            if (count($this->model_relations) > 0) {
                $query = Order::whereDriverId($driver_id)->with(array_column($this->model_relations, 'name'));
            } else {
                $query = Order::query();
            }
            $query = $this->buildFilter($request, $query);
            $list = $query->latest()->paginate($this->pagination_count);
            $view_data = array_merge($common_data, [

                'list' => $list,
                'bulk_update' => '', 'tabs' => $tabs,
                'bulk_update' => json_encode([
                    'delivery_status' => ['label' => 'Delivery Status',
                        'data' => getListFromIndexArray(['Order Placed', 'Out For Delivery', 'Delivered', 'Cancelled', 'Returned', 'Partially Returned'])],
                    'driver_id' => ['label' => 'Assign Driver', 'data' => getListWithRoles('Driver'),
                        'status_message' => ['label' => 'Status Message', 'input_type' => 'textarea', 'data' => ''],
                        'paid_status' => ['label' => 'Payment Status', 'data' => getListFromIndexArray(['Order Placed', 'Paid', 'Refunded'])]],
                ]),

            ]);
            $index_view = count($tabs) > 0 ? 'index_tabs' : 'index';
            return view('admin.' . $this->view_folder . '.' . $index_view, $view_data);
        }

    }
}
