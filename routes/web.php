<?php
use App\Http\Controllers\CommonController;
use App\Http\Controllers\CrudGeneratorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/home', function () { /*home is redirect route defined in fortservice provider after logi auth  from here divert route based on role,dont use separate admin rout files now  */
    if (auth()->user()->hasRole(['Admin'])) {
        return redirect(route('admin.dashboard'));
    } else {
        return redirect(route('user.dashboard'));
    }

});
/** ==============Email verification customisation =========== */
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

//resend mail
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
/**===================================End custom verification============================== */
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/clear_cache', [FrontendController::class, 'clear_cache'])->name('clear_cache');
Route::get('/cache', [FrontendController::class, 'cache'])->name('cache');
Route::group(['middleware' => ['guest']], function () {
    /**
     * Register Routes
     */

    Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'show'])->name('register.show');
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register.perform');

    /**
     * Login Routes
     */
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'show'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.perform');

    Route::get('forget-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'ForgetPassword'])->name('ForgetPasswordGet');
    Route::post('forget-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'ForgetPasswordStore'])->name('ForgetPasswordPost');
    Route::get('reset-password/{token}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'ResetPassword'])->name('ResetPasswordGet');
    Route::post('reset-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'ResetPasswordStore'])->name('ResetPasswordPost');
    Route::get('verify_email/{_vX00}/{_tX00}', [App\Http\Controllers\Auth\RegisterController::class, 'verify_email'])->name('email_verify');

});

Route::get('/redirect', [FrontendController::class, 'redirect']);
Route::post('/fieldExist', [CommonController::class, 'field_exist']);
Route::post('/getDependentSelectData', [CommonController::class, 'getDependentSelectData']);
Route::post('/getDependentSelectDataMultipleVal', [CommonController::class, 'getDependentSelectDataMultipleVal']);
Route::group(['middleware' => ['auth']], function () {
    Route::get('/unauthorized', [App\Http\Controllers\UnauthorizedController::class, 'index'])->name('admin.unauthorized');

    Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('auth.logout');
    //Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.dashboard');
    Route::post('/getUnitByMeterialId', [App\Http\Controllers\CommonController::class, 'getUnitByMeterialId']);
    Route::match(['get', 'post'], '/search_table', [App\Http\Controllers\CommonController::class, 'search_table']);
    Route::post('/fetchRowFromTable', [App\Http\Controllers\CommonController::class, 'fetchRowFromTable']);
    Route::post('/deleteRecordFromTable', [App\Http\Controllers\CommonController::class, 'deleteRecordFromTable']);

    Route::post('delete_file_from_table', [CommonController::class, 'deleteFileFromTable'])->name('deleteTableFile');

    Route::post('deleteInJsonColumnData', [CommonController::class, 'deleteInJsonColumnData'])->name('deleteInJsonColumnData');
    Route::post('assignUser', [CommonController::class, 'assignUser'])->name('assignUser');
    Route::post('delete_file_self', [CommonController::class, 'deleteFileFromSelf'])->name('deleteFileSelf');
    Route::post('table_field_update', [CommonController::class, 'table_field_update'])->name('table_filed_update');
    Route::post('singleFieldUpdateFromTable', [CommonController::class, 'singleFieldUpdateFromTable'])->name('singleFieldUpdateFromTable');
    Route::post('bulk_delete', [CommonController::class, 'bulkDelete'])->name('bulkDelete');
    Route::post('getTableColumn', [CommonController::class, 'getColumnsFromTable']);
    Route::post('getTableColumnCheckboxForm', [CommonController::class, 'getColumnsFromTableCheckbox']);
    Route::post('getValidationHtml', [CommonController::class, 'getValidationHtml']);
    Route::post('getRepeatableHtml', [CommonController::class, 'getRepeatableHtml']);
    Route::post('getCreateInputOptionHtml', [CommonController::class, 'getCreateInputOptionHtml']);
    Route::post('getSideColumnInputOptionHtml', [CommonController::class, 'getSideColumnInputOptionHtml']);
    Route::post('getToggableGroupHtml', [CommonController::class, 'getToggableGroupHtml']);
    /*****Genrate Modules route for non admin */

});
Route::middleware(['IsTelecaller'])->group(function () {

    //Route::resource('states', 'StateController');
    // Route::post('states/view', [App\Http\Controllers\StateController::class, 'view'])->name('states.view');
    // Route::post("state/load_form", [App\Http\Controllers\StateController::class, "loadAjaxForm"])->name("state.loadAjaxForm");
    // Route::get("export_states/{type}", [App\Http\Controllers\StateController::class, "exportState"])->name("state.export");

    // Route::resource('cities', 'CityController');
    // Route::post('cities/view', [App\Http\Controllers\CityController::class, 'view'])->name('cities.view');
    // Route::post("city/load_form", [App\Http\Controllers\CityController::class, "loadAjaxForm"])->name("city.loadAjaxForm");
    // Route::get("export_cities/{type}", [App\Http\Controllers\CityController::class, "exportCity"])->name("city.export");

    // Route::resource('settings', 'SettingController');
    // Route::post('settings/view', [App\Http\Controllers\SettingController::class, 'view'])->name('settings.view');
    // Route::post("setting/load_form", [App\Http\Controllers\SettingController::class, "loadAjaxForm"])->name("setting.loadAjaxForm");
    // Route::get("export_settings/{type}", [App\Http\Controllers\SettingController::class, "exportSetting"])->name("setting.export");

});

/******Modules Routes From Here */

Route::prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard_data', [DashboardController::class, 'dashboard_data'])->name('admin.dashboard_data');

    Route::get('/crud', [CrudGeneratorController::class, 'index'])->name('admin.crud');

    Route::match(['get', 'post'], '/generateModule', [CrudGeneratorController::class, 'generateModule'])->name('admin.generateModule');
    Route::match(['get', 'post'], '/generateTable', [CrudGeneratorController::class, 'generateTable'])->name('admin.generateTable');
    Route::match(['get', 'post'], '/addTableRelationship', [CrudGeneratorController::class, 'addTableRelationship'])->name('admin.addTableRelationship');
/******Modules Routes From Here */

    Route::resource('roles', RoleController::class);
    Route::post('roles/view', [App\Http\Controllers\RoleController::class, 'view'])->name('roles.view');
    Route::get("export_roles/{type}", [App\Http\Controllers\RoleController::class, "exportRole"])->name("role.export");

    Route::resource('permissions', PermissionController::class);
    Route::post('permissions/view', [PermissionController::class, 'view'])->name('permissions.view');
    Route::post("permission/load_form", [PermissionController::class, "loadAjaxForm"])->name("permission.loadAjaxForm");
    Route::resource('users', UserController::class);
    Route::post('users/view', [UserController::class, 'view'])->name('users.view');
    Route::post("user/load_form", [UserController::class, "loadAjaxForm"])->name("user.loadAjaxForm");
    Route::get("export_users/{type}", [UserController::class, "exportUser"])->name("user.export");

    // Route::resource('invoices', InvoiceController::class);
    // Route::get("export_invoices/{type}", [App\Http\Controllers\InvoiceController::class, "exportInvoice"])->name("invoice.export");
    // Route::post("accept_payments", [App\Http\Controllers\InvoiceController::class, "accept_payments"])->name("invoice.accept_payments");
    // Route::get("mail/{id}", [App\Http\Controllers\InvoiceController::class, "invoice_mail"])->name("invoice.mail");

/**=========================Genrate rotues from here */
Route::get('users/{role}', [UserController::class, 'index1']);
    Route::post('generateAccordian', [ProductController::class, 'generateAccordian']);
    Route::resource('products', ProductController::class);

    Route::resource('attributes', App\Http\Controllers\AttributeController::class);
    Route::post('attributes/view', [App\Http\Controllers\AttributeController::class, 'view'])->name('attributes.view');
    Route::get("export_attributes/{type}", [App\Http\Controllers\AttributeController::class, "exportAttribute"])->name("attribute.export");

    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::post('categories/view', [App\Http\Controllers\CategoryController::class, 'view'])->name('categories.view');
    Route::get("export_categories/{type}", [App\Http\Controllers\CategoryController::class, "exportCategory"])->name("category.export");

    Route::resource('products', App\Http\Controllers\ProductController::class);
    Route::post('products/view', [App\Http\Controllers\ProductController::class, 'view'])->name('products.view');
    Route::get("export_products/{type}", [App\Http\Controllers\ProductController::class, "exportProduct"])->name("product.export");

    Route::resource('brands', App\Http\Controllers\BrandController::class);
    Route::post('brands/view', [App\Http\Controllers\BrandController::class, 'view'])->name('brands.view');
    Route::get("export_brands/{type}", [App\Http\Controllers\BrandController::class, "exportBrand"])->name("brand.export");

    Route::resource('attribute_famlies', App\Http\Controllers\AttributeFamilyController::class);
    Route::post('attribute_famlies/view', [App\Http\Controllers\AttributeFamilyController::class, 'view'])->name('attributefamilies.view');
    Route::get("export_attributefamilies/{type}", [App\Http\Controllers\AttributeFamilyController::class, "exportAttributeFamily"])->name("attributefamily.export");
    Route::post('getAttributesHtml', [App\Http\Controllers\AttributeFamilyController::class, 'getAttributesHtml']);

    
   
    Route::resource('customer_groups', App\Http\Controllers\CustomerGroupController::class);
    Route::post('customer_groups/view', [App\Http\Controllers\CustomerGroupController::class, 'view'])->name('customergroups.view');
    Route::get("export_customergroups/{type}", [App\Http\Controllers\CustomerGroupController::class, "exportCustomerGroup"])->name("customergroup.export");

   
    Route::resource('slider_banners', App\Http\Controllers\SliderBannerController::class);
    Route::post('slider_banners/view', [App\Http\Controllers\SliderBannerController::class, 'view'])->name('sliderbanners.view');
    Route::get("export_sliderbanners/{type}", [App\Http\Controllers\SliderBannerController::class, "exportSliderBanner"])->name("sliderbanner.export");

    Route::resource('banners', App\Http\Controllers\BannerController::class);
    Route::post('banners/view', [App\Http\Controllers\BannerController::class, 'view'])->name('banners.view');
    Route::get("export_banners/{type}", [App\Http\Controllers\BannerController::class, "exportBanner"])->name("banner.export");

    Route::resource('content_sections', App\Http\Controllers\ContentSectionController::class);
    Route::post('content_sections/view', [App\Http\Controllers\ContentSectionController::class, 'view'])->name('contentsections.view');
    Route::get("export_contentsections/{type}", [App\Http\Controllers\ContentSectionController::class, "exportContentSection"])
    ->name("contentsection.export");

    Route::get('driver_orders/{driver_id}', [App\Http\Controllers\OrderController::class, 'driver_orders'])->name('driver.orders');
    Route::resource('orders', App\Http\Controllers\OrderController::class);
    Route::post('orders/view', [App\Http\Controllers\OrderController::class, 'view'])->name('orders.view');
    Route::get('order_item/{id}', [App\Http\Controllers\OrderController::class, 'show_order_related_to_item_id'])->name('orders.view_item_id');
    Route::get("export_orders/{type}", [App\Http\Controllers\OrderController::class, "exportOrders"])->name("orders.export");
    Route::resource("return_items",App\Http\Controllers\ReturnItemsController::class);
    Route::resource('refunds', App\Http\Controllers\RefundController::class);

    
Route::resource('coupons',App\Http\Controllers\CouponController::class);
Route::post('coupons/view', [App\Http\Controllers\CouponController::class,'view'])->name('coupons.view');
Route::get("export_coupons/{type}", [App\Http\Controllers\CouponController::class,"exportCoupon"])->name("coupon.export");

Route::resource('settings',App\Http\Controllers\SettingController::class);
Route::post('settings/view', [App\Http\Controllers\SettingController::class,'view'])->name('settings.view');

Route::resource('collections',App\Http\Controllers\CollectionController::class);
Route::post('collections/view', [App\Http\Controllers\CollectionController::class,'view'])->name('collections.view');
Route::get("export_collections/{type}", [App\Http\Controllers\CollectionController::class,"exportCollection"])->name("collection.export");


Route::resource('product_addons',App\Http\Controllers\ProductAddonController::class);
Route::post('product_addons/view', [App\Http\Controllers\ProductAddonController::class,'view'])->name('productaddons.view');
Route::get("export_productaddons/{type}", [App\Http\Controllers\ProductAddonController::class,"exportProductAddons"])->name("productaddons.export");


Route::resource('website_banners',App\Http\Controllers\WebsiteBannerController::class);
Route::post('website_banners/view', [App\Http\Controllers\WebsiteBannerController::class,'view'])->name('websitebanners.view');
Route::get("export_websitebanners/{type}", [App\Http\Controllers\WebsiteBannerController::class,"exportWebsiteBanner"])->name("websitebanner.export");



Route::resource('website_content_sections',App\Http\Controllers\WebsiteContentSectionController::class);
Route::post('website_content_sections/view', [App\Http\Controllers\WebsiteContentSectionController::class,'view'])->name('websitecontentsections.view');
Route::get("export_websitecontentsections/{type}", [App\Http\Controllers\WebsiteContentSectionController::class,"exportWebsiteContentSection"])->name("websitecontentsection.export");
});
