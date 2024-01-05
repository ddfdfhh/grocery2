<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use \Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        $this->index_url = route('coupons.index');
        $this->module = 'Coupon';
        $this->view_folder = 'coupons';
        $this->storage_folder = $this->view_folder;
        $this->has_upload = 0;
        $this->is_multiple_upload = 0;
        $this->has_export = 0;
        $this->pagination_count = 100;
        $this->crud_title = 'Coupon';
        $this->show_crud_in_modal = 0;
        $this->has_popup = 0;
        $this->has_detail_view = 0;
        $this->has_side_column_input_group = 0;
        $this->form_image_field_name = [];

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
                        'name' => 'product_id',
                        'label' => 'Select Products',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => '',
                        'attr' => [
                            'id' => 'sdsd',
                            'data-ajax-search' => 'true',
                            'data-search-table' => 'products',
                            'data-search-id-column' => 'id',
                            'data-search-name-column' => 'name',
                            'data-search-by-column' => 'name',
                            'data-search-wherein' => 'category_id',
                        ],
                        'custom_key_for_option' => 'name',
                        'options' => [],
                        'custom_id_for_option' => 'id',
                        'multiple' => true, 'col' => '6',
                    ],
                    [
                        'name' => 'include_or_exclude',
                        'label' => 'Inlcude Or Exclude Above Selected',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->include_or_exclude) ? $model->include_or_exclude : 'Include',
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Include',
                                'value' => 'Include',
                            ],
                            (object) [
                                'label' => 'Exclude',
                                'value' => 'Exclude',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true,
                    ],
                    [
                        'placeholder' => 'Enter name',
                        'name' => 'name',
                        'label' => 'Short Description',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->name : "",
                        'attr' => [],
                    ],
                    [
                        'name' => 'discount_method',
                        'label' => 'Discount Method',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->discount_method) ? $model->discount_method : 'Coupon Code',
                        'attr' => ['onChange' => 'toggleForDiscountMethod(this.value)'],
                        'value' => [
                            (object) [
                                'label' => 'Coupon Code',
                                'value' => 'Coupon Code',
                            ],
                            (object) [
                                'label' => 'Automatic',
                                'value' => 'Automatic',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true,
                    ],
                    [
                        'placeholder' => 'Enter coupon_code',
                        'name' => 'coupon_code',
                        'label' => 'Coupon Code',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->coupon_code : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter details',
                        'name' => 'details',
                        'label' => 'T & C',
                        'tag' => 'textarea',
                        'type' => 'textarea',
                        'default' => isset($model) ? $model->details : "",
                        'attr' => ['class' => 'summernote'], 'col' => '12',
                    ],

                    [
                        'placeholder' => 'Enter start_date',
                        'name' => 'start_date',
                        'label' => 'Start Date',
                        'tag' => 'input',
                        'type' => 'datetime-local',
                        'default' => isset($model) ? $model->start_date : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter end_date',
                        'name' => 'end_date',
                        'label' => 'Expiry Date',
                        'tag' => 'input',
                        'type' => 'datetime-local',
                        'default' => isset($model) ? $model->end_date : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter customer_usage_limit',
                        'name' => 'customer_usage_limit',
                        'label' => 'Per Customer Usage  Limit',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->customer_usage_limit : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter total_usage_limit',
                        'name' => 'total_usage_limit',
                        'label' => 'Total Usage Limit',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->total_usage_limit : "",
                        'attr' => [],
                    ],
                    [
                        'name' => 'customer_group_id',
                        'label' => 'Apply Customer Group',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => '',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('CustomerGroup'),
                        'custom_id_for_option' => 'id',
                        'multiple' => true,
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
                        'name' => 'show_in_front',
                        'label' => 'Show In Front App',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->show_in_front) ? $model->show_in_front : 'Yes',
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Yes',
                                'value' => 'Yes',
                            ],
                            (object) [
                                'label' => 'No',
                                'value' => 'No',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true,
                    ],
                    [
                        'name' => 'free_shipping',
                        'label' => 'Is Free Shipping',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->free_shipping) ? $model->free_shipping : 'No',
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Yes',
                                'value' => 'Yes',
                            ],
                            (object) [
                                'label' => 'No',
                                'value' => 'No',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true,
                    ],
                    [
                        'name' => 'type',
                        'label' => 'Coupon Discount Type',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->type) ? $model->type : 'Bulk',
                        'attr' => ['onChange' => 'toggleDiscountRuleDiv(this.value)'],
                        'value' => [
                            (object) [
                                'label' => 'Normal Discount',
                                'value' => 'Bulk',
                            ],
                            (object) [
                                'label' => 'Quantity Based',
                                'value' => 'Individual Quantity',
                            ],
                            (object) [
                                'label' => 'Cart Amount Based',
                                'value' => 'Cart',
                            ],
                            (object) [
                                'label' => 'Buy X Get Y',
                                'value' => 'BOGO',
                            ],
                            (object) [
                                'label' => 'Shipping Discount',
                                'value' => 'Shipping',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true,
                    ],
                    [
                        'placeholder' => 'Enter minimum_order_amount',
                        'name' => 'minimum_order_amount',
                        'label' => 'Minimum Cart Amount',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->minimum_order_amount : "",
                        'attr' => [],
                    ],

                    [
                        'placeholder' => 'Enter maximumm discount limit',
                        'name' => 'maximum_discount_limit',
                        'label' => 'Maximum Discount Limit',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->maximum_discount_limit : "",
                        'attr' => ['class' => 'd-none'],
                    ], [
                        'name' => 'cart_amount_calculation_method',
                        'label' => 'Cart Amount Calculation Method',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->cart_amount_calculation_method) ?
                        $model->cart_amount_calculation_method : 'All Items',
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Exclude Selected',
                                'value' => 'Exclude Selected',
                            ],
                            (object) [
                                'label' => 'All Items',
                                'value' => 'All Items',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true,
                    ],
                    [
                        'name' => 'discount_type',
                        'label' => 'Discount Type',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) && isset($model->discount_type) ?
                        formatDefaultValueForEdit($model, 'discount_type', false) : 'Flat',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => [
                            (object) [
                                'id' => 'Flat',
                                'name' => 'Flat',
                            ],
                            (object) [
                                'id' => 'Percent',
                                'name' => 'Percent',
                            ],
                        ],
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter discount',
                        'name' => 'discount',
                        'label' => 'Discount',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->discount : "",
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
        $customer_grps = !empty($model->customer_group_id) ? json_decode($model->customer_group_id, true) : [];
     // dd(json_decode($model->product_id, true));
        $products = !empty($model->product_id) ? json_decode($model->product_id, true) : [];
        $data = [
            [
                'label' => null,
                'inputs' => [
                    [
                        'name' => 'product_id',
                        'label' => 'products',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' =>array_column($products,'id'),
                        'attr' => [
                            'id' => 'sdsd',
                            'data-ajax-search' => 'true',
                            'data-search-table' => 'products',
                            'data-search-id-column' => 'id',
                            'data-search-name-column' => 'name',
                            'data-search-by-column' => 'name',
                            'data-search-wherein' => 'category_id',
                        ],
                        'custom_key_for_option' => 'name',
                        'options' => $products,
                        'custom_id_for_option' => 'id',
                        'multiple' => true,
                    ],

                    [
                        'name' => 'include_or_exclude',
                        'label' => 'Inlcude Or Exclude Above Selected',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->include_or_exclude) ? $model->include_or_exclude : 'Include',
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Include',
                                'value' => 'Include',
                            ],
                            (object) [
                                'label' => 'Exclude',
                                'value' => 'Exclude',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true,
                    ],
                    [
                        'placeholder' => 'Enter name',
                        'name' => 'name',
                        'label' => 'Short Description',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->name : "",
                        'attr' => [],
                    ],
                    [
                        'name' => 'discount_method',
                        'label' => 'Discount Method',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->discount_method) ? $model->discount_method : 'Coupon Code',
                        'attr' => ['onChange' => 'toggleForDiscountMethod(this.value)'],
                        'value' => [
                            (object) [
                                'label' => 'Coupon Code',
                                'value' => 'Coupon Code',
                            ],
                            (object) [
                                'label' => 'Automatic',
                                'value' => 'Automatic',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true,
                    ],
                    [
                        'placeholder' => 'Enter coupon_code',
                        'name' => 'coupon_code',
                        'label' => 'Coupon Code',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->coupon_code : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter details',
                        'name' => 'details',
                        'label' => 'T & C',
                        'tag' => 'textarea',
                        'type' => 'textarea',
                        'default' => isset($model) ? $model->details : "",
                        'attr' => ['class' => 'summernote'], 'col' => '12',
                    ],

                    [
                        'placeholder' => 'Enter start_date',
                        'name' => 'start_date',
                        'label' => 'Start Date',
                        'tag' => 'input',
                        'type' => 'datetime-local',
                        'default' => isset($model) ? $model->start_date : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter end_date',
                        'name' => 'end_date',
                        'label' => 'Expiry Date',
                        'tag' => 'input',
                        'type' => 'datetime-local',
                        'default' => isset($model) ? $model->end_date : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter customer_usage_limit',
                        'name' => 'customer_usage_limit',
                        'label' => 'Per Customer Usage  Limit',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->customer_usage_limit : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter total_usage_limit',
                        'name' => 'total_usage_limit',
                        'label' => 'Total Usage Limit',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->total_usage_limit : "",
                        'attr' => [],
                    ],
                    [
                        'name' => 'customer_group_id',
                        'label' => 'Apply Customer Group',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => !empty($customer_grps) ? $customer_grps[0]['id'] : '',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('CustomerGroup'),
                        'custom_id_for_option' => 'id',
                        'multiple' => true,
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
                        'name' => 'show_in_front',
                        'label' => 'Show In Front App',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->show_in_front) ? $model->show_in_front : 'Yes',
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Yes',
                                'value' => 'Yes',
                            ],
                            (object) [
                                'label' => 'No',
                                'value' => 'No',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true,
                    ],
                    [
                        'name' => 'free_shipping',
                        'label' => 'Is Free Shipping',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->free_shipping) ? $model->free_shipping : 'No',
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Yes',
                                'value' => 'Yes',
                            ],
                            (object) [
                                'label' => 'No',
                                'value' => 'No',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true,
                    ],
                    [
                        'name' => 'type',
                        'label' => 'Coupon Discount Type',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->type) ? $model->type : 'Bulk',
                        'attr' => ['onChange' => 'toggleDiscountRuleDiv(this.value)'],
                        'value' => [
                            (object) [
                                'label' => 'Normal Discount',
                                'value' => 'Bulk',
                            ],
                            (object) [
                                'label' => 'Quantity Based',
                                'value' => 'Individual Quantity',
                            ],
                            (object) [
                                'label' => 'Cart Amount Based',
                                'value' => 'Cart',
                            ],
                            (object) [
                                'label' => 'Buy X Get Y',
                                'value' => 'BOGO',
                            ],
                            (object) [
                                'label' => 'Shipping Discount',
                                'value' => 'Shipping',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true,
                    ],
                    [
                        'placeholder' => 'Enter minimum_order_amount',
                        'name' => 'minimum_order_amount',
                        'label' => 'Minimum Cart Amount',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->minimum_order_amount : "",
                        'attr' => [],
                    ],

                    [
                        'placeholder' => 'Enter maximumm discount limit',
                        'name' => 'maximum_discount_limit',
                        'label' => 'Maximum Discount Limit',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->maximum_discount_limit : "",
                        'attr' => ['class' => 'd-none'],
                    ], [
                        'name' => 'cart_amount_calculation_method',
                        'label' => 'Cart Amount Calculation Method',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->cart_amount_calculation_method) ?
                        $model->cart_amount_calculation_method : 'All Items',
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Exclude Selected',
                                'value' => 'Exclude Selected',
                            ],
                            (object) [
                                'label' => 'All Items',
                                'value' => 'All Items',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true,
                    ],
                    [
                        'name' => 'discount_type',
                        'label' => 'Discount Type',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) && isset($model->discount_type) ?
                        formatDefaultValueForEdit($model, 'discount_type', false) : 'Flat',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => [
                            (object) [
                                'id' => 'Flat',
                                'name' => 'Flat',
                            ],
                            (object) [
                                'id' => 'Percent',
                                'name' => 'Percent',
                            ],
                        ],
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter discount',
                        'name' => 'discount',
                        'label' => 'Discount',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->discount : "",
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
                'colname' => 'quantity_rule',
                'label' => 'Quantity Rule',
                'inputs' => [
                    [
                        'placeholder' => 'Enter Min Quantity',
                        'name' => 'quantity_rule__json__min_quantity[]',
                        'label' => 'Min Quantity',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => '',
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter Max Quantity',
                        'name' => 'quantity_rule__json__max_quantity[]',
                        'label' => 'Max Quantity',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => '',
                        'attr' => [],
                    ],
                    [
                        'name' => 'quantity_rule__json__discount_type[]',
                        'label' => 'Select Discount Type',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => 'Flat',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => [
                            (object) [
                                'id' => 'Flat',
                                'name' => 'Flat',
                            ],
                            (object) [
                                'id' => 'Percent',
                                'name' => 'Percent',
                            ],
                        ],
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter discount',
                        'name' => 'quantity_rule__json__discount[]',
                        'label' => 'Discount',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => '',
                        'attr' => [],
                    ],
                    [
                        'name' => 'quantity_rule__json__is_range[]',
                        'label' => 'Has range',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => 'Yes',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => [
                            (object) [
                                'id' => 'Yes',
                                'name' => 'Yes',
                            ],
                            (object) [
                                'id' => 'No',
                                'name' => 'No',
                            ],
                        ],
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                ],
                'index_with_modal' => 0,
                'modalInputBoxIdWhoseValueToSetInSelect' => '',
                'hide' => 'true',
            ],
            [
                'colname' => 'buy_products',
                'label' => 'Buy Products',
                'inputs' => [
                    [
                        'name' => 'buy_products__json__product_id[]',
                        'label' => 'Select Product ',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => (!empty(getList('Product')) ? getList('Product')[0]->id : ''),
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Product'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter qty',
                        'name' => 'buy_products__json__qty[]',
                        'label' => 'Qty',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => '',
                        'attr' => [],
                    ],
                ],
                'index_with_modal' => 0,
                'modalInputBoxIdWhoseValueToSetInSelect' => '',
                'hide' => !empty($model) ? ($model->type == 'BOGO' ? 'false' : 'true') : 'true',
                'disableButtons' => false,
            ],
            [
                'colname' => 'get_products',
                'label' => 'Get Products',
                'inputs' => [
                    [
                        'name' => 'get_products__json__product_id[]',
                        'label' => 'Select Product ',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => (!empty(getList('Product')) ? getList('Product')[0]->id : ''),
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Product'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter qty',
                        'name' => 'get_products__json__qty[]',
                        'label' => 'Qty',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => '',
                        'attr' => [],
                    ],
                    [
                        'name' => 'get_products__json__discount_type[]',
                        'label' => 'Select Discount_type',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => 'Flat',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => [
                            (object) [
                                'id' => 'Flat',
                                'name' => 'Flat',
                            ],
                            (object) [
                                'id' => 'Percent',
                                'name' => 'Percent',
                            ],
                        ],
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter discount',
                        'name' => 'get_products__json__discount[]',
                        'label' => 'Discount',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => '',
                        'attr' => [],
                    ],
                ],
                'index_with_modal' => 0,
                'modalInputBoxIdWhoseValueToSetInSelect' => '',
                'hide' => !empty($model) ? ($model->type == 'BOGO' ? 'false' : 'true') : 'true',
                'disableButtons' => true,
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
                'column' => 'coupon_code',
                'label' => 'Coupon Code',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'discount_method',
                'label' => 'Discount Method',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'type',
                'label' => 'Type',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],

            [
                'column' => 'start_date',
                'label' => 'Start Date',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'end_date',
                'label' => 'End Date',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],

            [
                'column' => 'show_in_front',
                'label' => 'Show In Front',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'status',
                'label' => 'Status',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
        ];
        $view_columns = [
            [
                'column' => 'category_id',
                'label' => 'Categories',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'product_id',
                'label' => 'Products ',
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
                'column' => 'discount_method',
                'label' => 'Discount Method',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'details',
                'label' => 'Details',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'coupon_code',
                'label' => 'Coupon Code',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'customer_group_id',
                'label' => 'Customer Group Id',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'customer_usage_limit',
                'label' => 'Customer Usage Limit',
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
                'column' => 'discount_type',
                'label' => 'Discount Type',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'start_date',
                'label' => 'Start Date',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'end_date',
                'label' => 'Expiry Date',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'free_shipping',
                'label' => 'Has Free Shipping',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'minimum_order_amount',
                'label' => 'Minimum Order Amount',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],

            [
                'column' => 'maximum_discount_limit',
                'label' => 'Maximum Discount Limit',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],

            [
                'column' => 'show_in_front',
                'label' => 'Show In Front',
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
                'column' => 'total_usage_limit',
                'label' => 'Total Usage Limit',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'total_used_till_now',
                'label' => 'Total Used Till Now',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'quantity_rule',
                'label' => 'Quantity Rule',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'buy_products',
                'label' => 'Buy X Products',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'get_products',
                'label' => 'Get  Y Products',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
        ];

        $searchable_fields = [
            [
                'name' => 'coupon_code',
                'label' => 'Coupon Code',
            ],
        ];
        $filterable_fields = [
            [
                'name' => 'created_at',
                'label' => 'Created At',
                'type' => 'date',
            ],
            [
                'name' => 'discount_type',
                'label' => 'Discount Type',
                'type' => 'select', 'options' => getListFromIndexArray(['Flat', 'Percent']),
            ],
            [
                'name' => 'end_date',
                'label' => 'Expiry Date',
                'type' => 'number',
            ],
            [
                'name' => 'free_shipping',
                'label' => 'Free Shipping',
                'type' => 'select', 'options' => getListFromIndexArray(['Yes', 'No']),
            ],
            [
                'name' => 'minimum_order_amount',
                'label' => 'Minimum Order Amount',
                'type' => 'number',
            ],
            [
                'name' => 'start_date',
                'label' => 'Start Date',
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
            'plural_lowercase' => 'coupons',
            'has_image' => $this->has_upload,
            'table_columns' => $table_columns,
            'view_columns' => $view_columns,

            'image_field_names' => $this->form_image_field_name,
            'storage_folder' => $this->storage_folder,
            'module_table_name' => 'coupons',
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
            $data['row'] = Coupon::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = Coupon::findOrFail($id);
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

            $db_query = Coupon::when(!empty($search_val), function ($query) use ($search_val, $search_by) {
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
            if (!can('list_coupons')) {
                return redirect()->back()->withError('Dont have permission to list');
            }
            $query = null;
            if (count($this->model_relations) > 0) {
                $query = Coupon::with(array_column($this->model_relations, 'name'));
            } else {
                $query = Coupon::query();
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

        $cats = \App\Models\Category::whereNull('category_id')->get()->toArray();
        $s = '';
        $i = 0;
        $category_options = gt($cats, $i, $s);
        $data = $this->createInputsData();
        $view_data = array_merge($this->commonVars()['data'], [
            'data' => $data, 'category_options' => $category_options,

        ]);
        if ($r->ajax()) {

            if (!can('create_coupons')) {
                return createResponse(false, 'Dont have permission to create');
            }

            $html = view('admin.' . $this->view_folder . '.modal.add', with($view_data))->render();
            return createResponse(true, $html);
        } else {

            if (!can('create_coupons')) {
                return redirect()->back()->withError('Dont have permission to create');
            }
            return view('admin.' . $this->view_folder . '.add', with($view_data));
        }

    }
    public function store(CouponRequest $request)
    {
        if (!can('create_coupons')) {
            return createResponse(false, 'Dont have permission to create');
        }
        \DB::beginTransaction();

        try {
            $post = $request->all();
            if (empty($post['category_id'][0])) {
                $post['category_id'] = null;
            }
            if (empty($post['product_id'][0])) {
                $post['product_id'] = null;
            }
            if (empty($post['customer_group_id'][0])) {
                $post['customer_group_id'] = null;
            }
            //  !empty($post['customer_group_id'][0])
            $post = formatPostForJsonColumn($post);
            /* Saving name alongwith id in json column takki join se na retrive karna pade
            copy this code from contrller file and paste and edit here
            $post=$this->processJsonColumnToAddNameOrAddtionalData($post);
             */
            if (!empty($post['category_id'])) {

                $ids = json_decode($post['category_id']);
                $names_array = \DB::table('categories')->whereIn('id', $ids)->pluck('name', 'id')->toArray();
                $ar = [];
                foreach ($ids as $id) {
                    if ($id) {
                        $name = isset($names_array[$id]) ? $names_array[$id] : '';
                        $ar[] = ['id' => $id, 'name' => $name];
                    }
                }

                unset($post['category_id']);
                $post['category_id'] = json_encode($ar);
            }

            if (!empty($post['product_id'])) {
                $ids = json_decode($post['product_id']);
                $names_array = \DB::table('products')->whereIn('id', $ids)->pluck('name', 'id')->toArray();
                $ar = [];
                foreach ($ids as $id) {
                    if ($id) {
                        $name = isset($names_array[$id]) ? $names_array[$id] : '';
                        $ar[] = ['id' => $id, 'name' => $name];
                    };
                }

                unset($post['product_id']);
                $post['product_id'] = json_encode($ar);
            }
            if (!empty($post['customer_group_id'])) {
                $ids = json_decode($post['customer_group_id']);
                $names_array = \DB::table('customer_groups')->whereIn('id', $ids)->pluck('name', 'id')->toArray();
                $ar = [];
                foreach ($ids as $id) {
                    if ($id) {
                        $name = isset($names_array[$id]) ? $names_array[$id] : '';
                        $ar[] = ['id' => $id, 'name' => $name];
                    }
                }

                unset($post['customer_group_id']);
                $post['customer_group_id'] = json_encode($ar);
            }
            $post['customer_group_id'] = !empty($post['customer_group_id']) ? $post['customer_group_id'] : null;

            $ids = $post['buy_products__json__product_id'];
            $names_array = \DB::table('products')->whereIn('id', $ids)->pluck('name', 'id')->toArray();
            $ar = json_decode($post['buy_products']);

            $ar = array_map(function ($v) use ($names_array) {

                $name = isset($names_array[$v->product_id]) ? $names_array[$v->product_id] : '';
                $v->name = $name;
                return $v;
            }, $ar);
            unset($post['buy_products']);
            $post['buy_products'] = json_encode($ar);

            $ids = $post['get_products__json__product_id'];
            $names_array = \DB::table('products')->whereIn('id', $ids)->pluck('name', 'id')->toArray();
            $ar = json_decode($post['get_products']);

            $ar = array_map(function ($v) use ($names_array) {

                $name = isset($names_array[$v->product_id]) ? $names_array[$v->product_id] : '';
                $v->name = $name;
                return $v;
            }, $ar);
            unset($post['get_products']);
            $post['get_products'] = json_encode($ar);
            $coupon = Coupon::create($post);
            $this->afterCreateProcess($request, $post, $coupon);
            \DB::commit();
            return createResponse(true, $this->crud_title . ' created successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();

            return createResponse(false, $ex->getMessage());
        }
    }
    public function edit(Request $request, $id)
    {

        $model = Coupon::findOrFail($id);
        $data = $this->editInputsData($model);
        $cats = \App\Models\Category::whereNull('category_id')->get()->toArray();
        $s = '';
        $i = 0;
        $categories = !empty($model->category_id) ? array_column(json_decode($model->category_id, true), 'id') : null;
        $category_options = $categories ? gt_multiple($cats, $i, $s, $categories) : gt($cats, $i, $s);

        $view_data = array_merge($this->commonVars($model)['data'], [
            'data' => $data, 'model' => $model, 'category_options' => $category_options,

        ]);

        if ($request->ajax()) {
            if (!can('edit_coupons')) {
                return createResponse(false, 'Dont have permission to edit');
            }

            $html = view('admin.' . $this->view_folder . '.modal.edit', with($view_data))->render();
            return createResponse(true, $html);

        } else {
            if (!can('edit_coupons')) {
                return redirect()->back()->withError('Dont have permission to edit');
            }

            return view('admin.' . $this->view_folder . '.edit', with($view_data));

        }

    }

    public function show(Request $request, $id)
    {
        $view = 'view';
        $data = $this->common_view_data($id);

        if ($request->ajax()) {
            if (!can('view_coupons')) {
                return createResponse(false, 'Dont have permission to view');
            }

            $html = view('admin.' . $this->view_folder . '.view.' . $view, with($data))->render();
            return createResponse(true, $html);

        } else {
            if (!can('view_coupons')) {
                return redirect()->back()->withError('Dont have permission to view');
            }

            return view('admin.' . $this->view_folder . '.view.' . $view, with($data));

        }

    }

    public function update(CouponRequest $request, $id)
    {
        if (!can('edit_coupons')) {
            return createResponse(false, 'Dont have permission to update');
        }
        \DB::beginTransaction();

        try
        {
            $post = $request->all();
            if (empty($post['category_id'][0])) {
                $post['category_id'] = null;
            }
            if (empty($post['product_id'][0])) {
                $post['product_id'] = null;
            }
            if (empty($post['customer_group_id'][0])) {
                $post['customer_group_id'] = null;
            }
            $coupon = Coupon::findOrFail($id);

            $post = formatPostForJsonColumn($post);
            if (!empty($post['category_id'])) {
                $ids = json_decode($post['category_id']);
                $names_array = \DB::table('categories')->whereIn('id', $ids)->pluck('name', 'id')->toArray();
                $ar = [];
                foreach ($ids as $id) {
                    $name = isset($names_array[$id]) ? $names_array[$id] : '';
                    $ar[] = ['id' => $id, 'name' => $name];
                }

                unset($post['category_id']);
                $post['category_id'] = json_encode($ar);
            }
     
            if (!empty($post['product_id'])) {
                $ids = json_decode($post['product_id']);
                $names_array = \DB::table('products')->whereIn('id', $ids)->pluck('name', 'id')->toArray();
                $ar = [];
                foreach ($ids as $id) {
                    $name = isset($names_array[$id]) ? $names_array[$id] : '';
                    $ar[] = ['id' => $id, 'name' => $name];
                }

                unset($post['product_id']);
                $post['product_id'] = json_encode($ar);
            }
            if (!empty($post['customer_group_id'])) {
                $ids = json_decode($post['customer_group_id']);
                $names_array = \DB::table('customer_groups')->whereIn('id', $ids)->pluck('name', 'id')->toArray();
                $ar = [];
                foreach ($ids as $id) {
                    $name = isset($names_array[$id]) ? $names_array[$id] : '';
                    $ar[] = ['id' => $id, 'name' => $name];
                }

                unset($post['customer_group_id']);
                $post['customer_group_id'] = json_encode($ar);
            }
            $post['customer_group_id'] = empty($post['customer_group_id']) ? null : $post['customer_group_id'];
            $ids = $post['buy_products__json__product_id'];
            $names_array = \DB::table('products')->whereIn('id', $ids)->pluck('name', 'id')->toArray();
            $ar = json_decode($post['buy_products']);

            $ar = array_map(function ($v) use ($names_array) {

                $name = isset($names_array[$v->product_id]) ? $names_array[$v->product_id] : '';
                $v->name = $name;
                return $v;
            }, $ar);
            unset($post['buy_products']);
            $post['buy_products'] = json_encode($ar);

            $ids = $post['get_products__json__product_id'];
            $names_array = \DB::table('products')->whereIn('id', $ids)->pluck('name', 'id')->toArray();
            $ar = json_decode($post['get_products']);

            $ar = array_map(function ($v) use ($names_array) {

                $name = isset($names_array[$v->product_id]) ? $names_array[$v->product_id] : '';
                $v->name = $name;
                return $v;
            }, $ar);
            unset($post['get_products']);
            $post['get_products'] = json_encode($ar);
            $post['coupon_code'] = $post['discount_method'] == 'Automatic' ? null : $post['coupon_code'];
            $post['details'] = $post['discount_method'] == 'Automatic' ? null : $post['details'];
            $coupon->update($post);
            $this->afterCreateProcess($request, $post, $coupon);
            \DB::commit();
            return createResponse(true, $this->crud_title . ' updated successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();
            return createResponse(false, $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!can('delete_coupons')) {
            return createResponse(false, 'Dont have permission to delete');
        }
        \DB::beginTransaction();
        try
        {
            if (Coupon::where('id', $id)->exists()) {
                Coupon::destroy($id);
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

    public function exportCoupon(Request $request, $type)
    {
        if (!can('export_coupons')) {
            return redirect()->back()->withError('Not allowed to export');
        }
        $meta_info = $this->commonVars()['data'];
        return $this->exportModel('Coupon', 'coupons', $type, $meta_info);

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
