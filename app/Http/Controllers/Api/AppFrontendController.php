<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use \Carbon\Carbon;

class AppFrontEndController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getContentSections()
    {
        $setting = \DB::table('settings')->first();
       
    
        $sections = \App\Models\ContentSection::with(['products.variants', 'categories.children', 'banner.banner_images'])
            ->whereVisible('Yes')->orderBy('section_number', 'Asc')->get();
        // dd($sections->toArray());
        foreach ($sections as $t) {
            if (!empty($t->categories)) {
                foreach ($t->categories as $r) {
                    // if (!empty($categories_with_offer)) {
                    //     if (in_array($r->id, $categories_with_offer)) {
                    //         if (isset($category_dis[$r->id])) {
                    //             $r->discount_type = $category_dis[$r->id]['discount_type'];
                    //             $r->discount = $category_dis[$r->id]['discount'];
                    //         }
                    //     }
                    // }

                    // // $r->thumbnail = [];
                    // // if ($r->image) {
                    // //     $r->thumbnail = getThumbnailsFromImage($r->image);
                    // // }

                    $r->children_count = count($r->children);
                    unset($r->children);
                    unset($r->deleted_at);
                    unset($r->pivot);
                }
            }
            if (!empty($t->products)) {
                $categories_with_offer = getCurrentAutomaticBulkOfferWithoutMinimumAmountSet()['categories_with_offer'];
                $products_with_offer = getCurrentAutomaticBulkOfferWithoutMinimumAmountSet()['products_with_offer'];
              
                foreach ($t->products as $r) {
                    $r = modifiedProductDetail($r, $setting, $categories_with_offer, $products_with_offer);

                }
            }
            if (!empty($t->collections)) {
                foreach ($t->collections as $r) {
                    if ($t->product_id) {
                        $product_ids = array_column(json_decode($r->product_id, true), 'id');
                        $r->product_count = count($product_ids);
                    }
                    else
                    $r->product_count =0;

                }
            }
            if ($t->banner) {
                $t->banner_images = $t->banner->banner_images;
                unset($t->banner);
            } else {
                $t->banner_images = null;
            }

        }

        return response()->json(['data' => $sections], 200);
    }
    public function getSingleContentSection($id)
    {
        $setting = \DB::table('settings')->first();
       

        $t = \App\Models\ContentSection::with(['products', 'categories.children', 'banner.banner_images', 'collections'])
            ->whereVisible('Yes')->whereId($id)->first();
        if (!is_null($t)) {
            if (!empty($t->categories)) {
                foreach ($t->categories as $r) {
                    // if (!empty($categories_with_offer)) {
                    //     if (in_array($r->id, $categories_with_offer)) {
                    //         if (isset($category_dis[$r->id])) {
                    //             $r->discount_type = $category_dis[$r->id]['discount_type'];
                    //             $r->discount = $category_dis[$r->id]['discount'];
                    //         }
                    //     }
                    // }

                    $r->thumbnail = [];
                    // if ($r->image) {
                    //     $r->thumbnail = getThumbnailsFromImage($r->image);
                    // }

                    $r->children_count = count($r->children);
                    unset($r->children);
                    unset($r->deleted_at);
                    unset($r->pivot);
                }
            }

            if (!empty($t->products)) {
                $categories_with_offer = getCurrentAutomaticBulkOfferWithoutMinimumAmountSet()['categories_with_offer'];
                $products_with_offer = getCurrentAutomaticBulkOfferWithoutMinimumAmountSet()['products_with_offer'];
              
                foreach ($t->products as $r) {
                    $r = modifiedProductDetail($r, $setting, $categories_with_offer, $products_with_offer);

                }
            }
            if ($t->banner) {
                $t->banner_images = $t->banner->banner_images;
                unset($t->banner);
            } else {
                $t->banner_images = null;
            }
        }
        return response()->json(['data' => $t ? $t : json_encode([])], 200);
    }

    public function getBanners()
    {
        $sections = \App\Models\Banner::with('banner_images')->get();
        return response()->json(['data' => $sections], 200);
    }
}
