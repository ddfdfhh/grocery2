<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContentSectionRequest;
use App\Models\ContentSection;
use Image;
use \Illuminate\Http\Request;

class ContentSectionController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        $this->index_url = route('content_sections.index');
        $this->module = 'ContentSection';
        $this->view_folder = 'content_sections';
        $this->storage_folder = $this->view_folder;
        $this->has_upload = 1;
        $this->is_multiple_upload = 0;
        $this->has_export = 0;
        $this->pagination_count = 100;
        $this->crud_title = 'App Content Section';
        $this->show_crud_in_modal = 1;
        $this->has_popup = 1;
        $this->has_detail_view = 0;
        $this->has_side_column_input_group = 0;
        $this->form_image_field_name = [
            [
                'field_name' => 'section_background_image',
                'single' => true,
                'has_thumbnail' => true,
            ],
            [
                'field_name' => 'header_image',
                'single' => true,
                'has_thumbnail' => true,
            ],
            [
                'field_name' => 'wrapper_image',
                'single' => true,
                'has_thumbnail' => true,
            ],
        ];

        $this->model_relations = [
            [
                'name' => 'banner',
                'type' => 'BelongsTo',
                'column_to_show_in_view' => 'name',
            ],
            [
                'name' => 'categories',
                'type' => 'BelongsToMany',
                'column_to_show_in_view' => 'name',
            ],
            [
                'name' => 'products',
                'type' => 'BelongsToMany',
                'column_to_show_in_view' => 'name',
            ],
            [
                'name' => 'collections',
                'type' => 'BelongsToMany',
                'column_to_show_in_view' => 'name',
            ],
        ];

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
    public function createInputsData()
    {
        $data = [
            [
                'label' => null,
                'inputs' => [
                    [
                        'name' => 'products',
                        'label' => 'Products',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => '',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => [],
                        'custom_id_for_option' => 'id',
                        'multiple' => true,
                    ],
                    [
                        'name' => 'collections',
                        'label' => 'Collection',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => '',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Collection'),
                        'custom_id_for_option' => 'id',
                        'multiple' => true,
                    ],
                    [
                        'placeholder' => 'Enter section number',
                        'name' => 'section_number',
                        'label' => 'Section Number',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->section_number : "",
                        'attr' => [], 'col' => 6,
                    ],
                    [
                        'placeholder' => 'Enter section_title',
                        'name' => 'section_title',
                        'label' => 'Section Display Title',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->section_title : "",
                        'attr' => [], 'col' => '6',
                    ],
                    [
                        'placeholder' => 'Enter section_subtitle',
                        'name' => 'section_subtitle',
                        'label' => 'Section Display Subtitle Title',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->section_subtitle : "",
                        'attr' => [], 'col' => '6',
                    ],
                    [
                        'placeholder' => 'Enter item count',
                        'name' => 'item_count',
                        'label' => 'Item Count',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->item_count : "7",
                        'attr' => [], 'col' => 6,
                    ],

                    [
                        'name' => 'display',
                        'label' => 'Grid Display Orientation',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->display) ? $model->display : 'Horizontal',
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Horizontal',
                                'value' => 'Horizontal',
                            ],
                            (object) [
                                'label' => 'Vertical',
                                'value' => 'Vertical',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true, 'col' => 6,
                    ],
                    [
                        'name' => 'show_category_in_circle',
                        'label' => 'Show Category in Circular Form',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->show_category_in_circle) ?
                        $model->show_category_in_circle : '',
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
                        'inline' => true, 'col' => '6',
                    ],
                    [
                        'name' => 'show_sale_badge',
                        'label' => 'Show Sale Badge',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->show_sale_badge) ?
                        $model->show_sale_badge : '',
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
                        'inline' => true, 'col' => 6,
                    ],
                    [
                        'name' => 'visible',
                        'label' => 'Is Visible',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->visible) ? $model->visible : 'Yes',
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
                        'inline' => true, 'col' => '6',
                    ],

                   
                    [
                        'name' => 'is_banner_slider',
                        'label' => 'Is Banner/Slider',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->is_banner_slider) ? $model->is_banner_slider : 'No',
                        'attr' => [

                        ],
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
                        'inline' => true, 'col' => '6',
                    ],
                    [
                        'name' => 'banner_id',
                        'label' => 'Select banner/Slider',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => '',
                        'attr' => ['class' => 'no-select2'],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Banner'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false, 'col' => 6,
                    ],
                    [
                        'name' => 'show_detailed_product_card',
                        'label' => 'Show Detailded Product Card',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->show_detailed_product_card) ? $model->show_detailed_product_card : 'Yes',
                        'attr' => [

                        ],
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
                        'inline' => true, 'col' => '6',
                    ],
                    [
                        'name' => 'heading_location',
                        'label' => 'Section Heading Location',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->heading_location) ? $model->heading_location : 'Left',
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Left',
                                'value' => 'Left',
                            ],
                            (object) [
                                'label' => 'Center',
                                'value' => 'Center',
                            ],
                            (object) [
                                'label' => 'Right',
                                'value' => 'Right',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true, 'col' => 6,
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
        $products = $model->products;
        $collections = $model->collections;
        // dd($products->toArray());
        $data = [
            [
                'label' => null,
                'inputs' => [
                    [
                        'name' => 'products',
                        'label' => 'Products',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => !empty($products) ? array_column($products->toArray(), 'id') : [],
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Product'),
                        'custom_id_for_option' => 'id',
                        'multiple' => true,
                    ],
                    [
                        'name' => 'collections',
                        'label' => 'Collection',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => !empty($collections) ? array_column($collections->toArray(), 'id') : [],
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Collection'),
                        'custom_id_for_option' => 'id',
                        'multiple' => true,
                    ],
                    [
                        'placeholder' => 'Enter section number',
                        'name' => 'section_number',
                        'label' => 'Section Number',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->section_number : "",
                        'attr' => [], 'col' => 6,
                    ],
                    [
                        'placeholder' => 'Enter section_title',
                        'name' => 'section_title',
                        'label' => 'Section Display Title',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->section_title : "",
                        'attr' => [], 'col' => 6,
                    ],
                    [
                        'placeholder' => 'Enter section_subtitle',
                        'name' => 'section_subtitle',
                        'label' => 'Section Display Subtitle Title',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->section_subtitle : "",
                        'attr' => [], 'col' => 6,
                    ],
                    [
                        'placeholder' => 'Enter item count',
                        'name' => 'item_count',
                        'label' => 'Item Count',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->item_count : "7",
                        'attr' => [], 'col' => 6,
                    ],

                    [
                        'name' => 'display',
                        'label' => 'Grid Display Orientation',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->display) ? $model->display : 'Horizontal',
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Horizontal',
                                'value' => 'Horizontal',
                            ],
                            (object) [
                                'label' => 'Vertical',
                                'value' => 'Vertical',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true, 'col' => 6,
                    ],
                    [
                        'name' => 'show_category_in_circle',
                        'label' => 'Show Category in Circular Form',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->show_category_in_circle) ?
                        $model->show_category_in_circle : '',
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
                        'inline' => true, 'col' => '6',
                    ],
                    [
                        'name' => 'show_sale_badge',
                        'label' => 'Show Sale Badge',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->show_sale_badge) ?
                        $model->show_sale_badge : '',
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
                        'inline' => true, 'col' => 6,
                    ],
                    [
                        'name' => 'visible',
                        'label' => 'Is Visible',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->visible) ? $model->visible : 'Yes',
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
                        'inline' => true, 'col' => '6',
                    ],

                    [
                        'name' => 'is_banner_slider',
                        'label' => 'Is Banner/Slider',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->is_banner_slider) ? $model->is_banner_slider : 'No',
                        'attr' => [

                        ],
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
                        'inline' => true, 'col' => '6',
                    ],
                    [
                        'name' => 'banner_id',
                        'label' => 'Select banner/Slider',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => $model->banner_id,
                        'attr' => ['class' => 'no-select2'],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Banner'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'name' => 'show_detailed_product_card',
                        'label' => 'Show Detailded Product Card',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->show_detailed_product_card) ? $model->show_detailed_product_card : 'Yes',
                        'attr' => [

                        ],
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
                        'inline' => true, 'col' => '6',
                    ],
                    [
                        'name' => 'heading_location',
                        'label' => 'Section Heading Location',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) && isset($model->heading_location) ? $model->heading_location : 'Left',
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Left',
                                'value' => 'Left',
                            ],
                            (object) [
                                'label' => 'Center',
                                'value' => 'Center',
                            ],
                            (object) [
                                'label' => 'Right',
                                'value' => 'Right',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true, 'col' => 6,
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
                    'default' => $g['single'] ? $model->{$g['field_name']} : json_encode($this->getImageList($id, $g['table_name'], $g['parent_table_field'], $this->storage_folder)),
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
                'column' => 'section_number',
                'label' => 'Section Number',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
                'sortable' => 'Yes',
            ],
            [
                'column' => 'section_title',
                'label' => 'Section Title',
                'sortable' => 'Yes',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'categories',
                'label' => 'Categories',
                'sortable' => 'No',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'products',
                'label' => 'Products',
                'sortable' => 'No',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'is_banner_slider',
                'label' => 'Is banner/Slider',
                'sortable' => 'No',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'section_background_image',
                'label' => 'Background Image',
                'sortable' => 'No',
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
                'label' => 'Products',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'section_number',
                'label' => 'Section Number',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'section_title',
                'label' => 'Section Title',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'section_subtitle',
                'label' => 'Section Subtitle',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'vertical_view',
                'label' => 'Display Orientation View',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            
            [
                'column' => 'heading_location',
                'label' => 'Heading/Title Location',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'section_background_image',
                'label' => 'Section Background Image',
                'sortable' => 'No',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'header_image',
                'label' => 'Header Image',
                'sortable' => 'No',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'wrapper_image',
                'label' => 'Wrapper Image',
                'sortable' => 'No',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'visible',
                'label' => 'Visible?',
                'sortable' => 'No',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'is_banner_slider',
                'label' => 'Is banner/Slider?',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'banner_id',
                'label' => 'Banner?Slider',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'show_category_in_circle',
                'label' => 'Show Cat in Circle',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
            [
                'column' => 'is_sale_badge',
                'label' => 'Sale Badge?',
                'show_json_button_click' => false,
                'by_json_key' => 'id',
                'inline_images' => true,
            ],
        ];

        $searchable_fields = [
            [
                'name' => 'section_name',
                'label' => 'Section Name',
            ],
            [
                'name' => 'section_title',
                'label' => 'Section Title',
            ],
        ];
        $filterable_fields = [
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
            'plural_lowercase' => 'content_sections',
            'has_image' => $this->has_upload,
            'table_columns' => $table_columns,
            'view_columns' => $view_columns,

            'image_field_names' => $this->form_image_field_name,
            'storage_folder' => $this->storage_folder,
            'module_table_name' => 'content_sections',
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
            $data['row'] = ContentSection::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = ContentSection::findOrFail($id);
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

            $db_query = ContentSection::when(!empty($search_val), function ($query) use ($search_val, $search_by) {
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
            if (!can('list_content_sections')) {
                return redirect()->back()->withError('Dont have permission to list');
            }
            $query = null;
            if (count($this->model_relations) > 0) {
                $query = ContentSection::with(array_column($this->model_relations, 'name'));
            } else {
                $query = ContentSection::query();
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
        $cats = \App\Models\Category::whereNull('category_id')->get()->toArray();
        $s = '';
        $i = 0;
        $category_options = gt($cats, $i, $s);
        $data = $this->createInputsData();
        $view_data = array_merge($this->commonVars()['data'], [
            'data' => $data, 'category_options' => $category_options,

        ]);

        if ($r->ajax()) {

            if (!can('create_content_sections')) {
                return createResponse(false, 'Dont have permission to create');
            }

            $html = view('admin.' . $this->view_folder . '.modal.add', with($view_data))->render();
            return createResponse(true, $html);
        } else {

            if (!can('create_content_sections')) {
                return redirect()->back()->withError('Dont have permission to create');
            }
            return view('admin.' . $this->view_folder . '.add', with($view_data));
        }

    }
    public function store(ContentSectionRequest $request)
    {
        if (!can('create_content_sections')) {
            return createResponse(false, 'Dont have permission to create');
        }
        \DB::beginTransaction();

        try {
            $post = $request->all();
            if (empty($post['categories'][0])) {
                unset($post['categories']);
            }
            if (empty($post['products'][0])) {
                unset($post['products']);
            }
            if (empty($post['collections'][0])) {
                unset($post['collections']);
            }
             if (empty($post['products'][0])) {
                unset($post['products']);
            }
           
            $post = formatPostForJsonColumn($post);
            /* Saving name alongwith id in json column takki join se na retrive karna pade
            copy this code from contrller file and paste and edit here
            $post=$this->processJsonColumnToAddNameOrAddtionalData($post);
             */
            // $ids = json_decode($post['category_id']);
            // $names_array = \DB::table('categories')->whereIn('id', $ids)->pluck('name', 'id')->toArray();
            // $ar = [];
            // foreach ($ids as $id) {
            //     if ($id) {
            //         $name = isset($names_array[$id]) ? $names_array[$id] : '';
            //         $ar[] = ['id' => $id, 'name' => $name];
            //     }
            // }

            // unset($post['category_id']);
            // $post['category_id'] = json_encode($ar);
           
           $post['background_color']=$post['background_color']=='#000000'?null:$post['background_color'];
           $post['section_background_color']=$post['section_background_color']=='#000000'?null:$post['section_background_color'];
            $app_content_section = ContentSection::create($post);
            // dd($post);
            $this->afterCreateProcess($request, $post, $app_content_section);
            \DB::commit();
            return createResponse(true, $this->crud_title . ' created successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();

            return createResponse(false, $ex->getMessage());
        }
    }
    public function edit(Request $request, $id)
    {

        $model = ContentSection::findOrFail($id);

        $data = $this->editInputsData($model);

        $cats = \App\Models\Category::whereNull('category_id')->get()->toArray();
        $s = '';
        $i = 0;
        $categories = !empty($model->categories) ? array_column(json_decode($model->categories, true), 'id') : null;
        $category_options = gt_multiple($cats, $i, $s, $categories);
        $view_data = array_merge($this->commonVars($model)['data'], [
            'data' => $data, 'model' => $model, 'category_options' => $category_options,

        ]);

        if ($request->ajax()) {
            if (!can('edit_content_sections')) {
                return createResponse(false, 'Dont have permission to edit');
            }

            $html = view('admin.' . $this->view_folder . '.modal.edit', with($view_data))->render();
            return createResponse(true, $html);

        } else {
            if (!can('edit_content_sections')) {
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
            if (!can('view_content_sections')) {
                return createResponse(false, 'Dont have permission to view');
            }

            $html = view('admin.' . $this->view_folder . '.view.' . $view, with($data))->render();
            return createResponse(true, $html);

        } else {
            if (!can('view_content_sections')) {
                return redirect()->back()->withError('Dont have permission to view');
            }

            return view('admin.' . $this->view_folder . '.view.' . $view, with($data));

        }

    }

    public function update(ContentSectionRequest $request, $id)
    {
        if (!can('edit_content_sections')) {
            return createResponse(false, 'Dont have permission to update');
        }
        \DB::beginTransaction();

        try
        {
            $post = $request->all();

            $app_content_section = ContentSection::findOrFail($id);
 
            $post = formatPostForJsonColumn($post);
            /* Saving name alongwith id in json column takki join se na retrive karna pade
            copy this code from contrller file and paste and edit here
            $post=$this->processJsonColumnToAddNameOrAddtionalData($post);
             */
            $old_image = $app_content_section->wrapper_image;
            if ($request->hasFile('wrapper_image')) {
                if ($old_image) {
                    $file_path = 'storage/' . $this->storage_folder . '/' . $old_image;
                    if (\File::exists(public_path($file_path))) {
                        \File::delete(public_path($file_path));
                    }

                }
            }
            $old_image = $app_content_section->header_image;
            if ($request->hasFile('header_image')) {
                if ($old_image) {
                    $file_path = 'storage/' . $this->storage_folder . '/' . $old_image;
                    if (\File::exists(public_path($file_path))) {
                        \File::delete(public_path($file_path));
                    }

                }
            }
            $old_image = $app_content_section->section_background_image;
            if ($request->hasFile('section_background_image')) {
                if ($old_image) {
                    $file_path = 'storage/' . $this->storage_folder . '/' . $old_image;
                    if (\File::exists(public_path($file_path))) {
                        \File::delete(public_path($file_path));
                    }

                }
            }
            $post['background_color']=$post['background_color']=='#000000'?null:$post['background_color'];
            $post['section_background_color']=$post['section_background_color']=='#000000'?null:$post['section_background_color'];
         
            $app_content_section->update($post);
            $this->afterCreateProcess($request, $post, $app_content_section);
            \DB::commit();
            return createResponse(true, $this->crud_title . ' updated successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();
            return createResponse(false, $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!can('delete_content_sections')) {
            return createResponse(false, 'Dont have permission to delete');
        }
        // \DB::beginTransaction();
        try
        {
            if (ContentSection::where('id', $id)->exists()) {

                ContentSection::destroy($id);
            }

            if ($this->has_upload) {
                $this->deleteFile($id);
            }
            // \DB::commit();
            return createResponse(true, $this->module . ' Deleted successfully');
        } catch (\Exception $ex) {
            // \DB::rollback();
            return createResponse(false, 'Failed to  Delete Properly');
        }

    }
    public function deleteFile($id)
    {

        return $this->deleteFileBase($id, $this->storage_folder);

    }

    public function exportContentSection(Request $request, $type)
    {
        if (!can('export_content_sections')) {
            return redirect()->back()->withError('Not allowed to export');
        }
        $meta_info = $this->commonVars()['data'];
        return $this->exportModel('ContentSection', 'content_sections', $type, $meta_info);

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
        $img = Image::make($filerequest->getRealPath())->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->orientate()->save($destinationPath, 100);
        tinifyImage($destinationPath);
        return $fileNameToStore;
    }
    public function storeSingleFileBackground($folder, $filerequest)
    {

        $folder = str_replace('\\', '/', $folder);
        $filenameWithExt = $filerequest->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $filerequest->getClientOriginalExtension();
        $filename = time();
        $fileNameToStore = $filename . '.' . $extension;

        $destinationPath = public_path('\\storage\\' . $folder . '\\' . $fileNameToStore);
        $destinationPath = str_replace('\\', '/', $destinationPath);
        $img = Image::make($filerequest->getRealPath())->resize(380, 200, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($destinationPath, 100);
        tinifyImage($destinationPath);
        return $fileNameToStore;
    }
    public function storeSingleFileHeader($folder, $filerequest)
    {
        $folder = str_replace('\\', '/', $folder);
        $filenameWithExt = $filerequest->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $filerequest->getClientOriginalExtension();
        $filename = time();
        $fileNameToStore = $filename . '.' . $extension;

        $destinationPath = public_path('\\storage\\' . $folder . '\\' . $fileNameToStore);
        $destinationPath = str_replace('\\', '/', $destinationPath);
        $img = Image::make($filerequest->getRealPath())->resize(380, 50, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($destinationPath, 100);
        tinifyImage($destinationPath);
        return $fileNameToStore;
    }
    public function upload1($request_files, $fieldname)
    {

        $uploaded_filename = null;
        if ($request_files != null) {
            if ($fieldname == 'wrapper_image') {
                $uploaded_filename = $this->storeSingleFile($this->storage_folder, $request_files);
            } else {
                $uploaded_filename = storeSingleFile($this->storage_folder, $request_files, false);
            }

        }
        return $uploaded_filename;

    }
}
