function show(v, event) {

    let t = $(event.target).closest('.row');

    $(t).find('.p').first().html(`<div class="form-group">
     <label class="form-label">Value</label>
     <div>
         <input  class="form-control attribute_values" name="value-${v}" data-role="tagsinput" />
     </div>
 </div>`)
    initTaggedInput();
}
function onLoadEditCoupon(){
   var discount_method=$('input[name="discount_method"]:checked').val();
   toggleForDiscountMethod(discount_method);
   var coupon_type=$('input[name="type"]:checked').val();
   toggleDiscountRuleDiv(coupon_type);
}
function toggleForDiscountMethod(value) {
    if (value == 'Coupon Code') {
        $('#inp-coupon_code').closest('.form-group').show();
        $('#inp-details').closest('.form-group').show();
        $('#inp-customer_usage_limit').closest('.form-group').show();
        $('#inp-total_usage_limit').closest('.form-group').show();
    
    }
    else  {
        $('#inp-coupon_code').closest('.form-group').hide();
     
        $('#inp-details').closest('.form-group').hide();
        $('#inp-customer_usage_limit').closest('.form-group').hide();
        $('#inp-total_usage_limit').closest('.form-group').hide();
    }
}
function toggleDiscountRuleDiv(value) {
    
    if (value == 'Individual Quantity') {
        $('#quantity_rule').show();
      

        $('#buy_products').hide();
      
        $('#get_products').hide();
      
        $('#inp-maximum_discount_limit').closest('.form-group').hide();
        $('#inp-discount').closest('.form-group').hide();
        $('#inp-discount_type').closest('.form-group').hide();
        $('#category_id').closest('.col-md-12').show();
        $('#inp-product_id').closest('.col-md-6').show();
    }
    else if (value == 'Cart') {
        $('#quantity_rule').hide();
        $('#buy_products').hide();
        $('#get_products').hide();
      
        $('#inp-maximum_discount_limit').closest('.form-group').show();
        $('#inp-discount').closest('.form-group').show();
        $('#inp-discount_type').closest('.form-group').show();
        $('#category_id').closest('.col-md-12').show();
        $('#inp-product_id').closest('.col-md-6').show();

    }
    else if (value == 'BOGO') {


        $('#buy_products').show();
        $('#get_products').show();


        $('#quantity_rule').hide();
        $('#category_id').closest('.col-md-12').hide();
        $('#inp-product_id').closest('.col-md-6').hide();
       

        $('#inp-maximum_discount_limit').closest('.form-group').hide();
        $('#inp-discount').closest('.form-group').hide();
        $('#inp-discount_type').closest('.form-group').hide();
    }
    else if (value == 'Bulk' || value == 'Shipping') {
        $('#quantity_rule').closest('.form-group').hide();
        $('#buy_products').closest('.form-group').hide();
        $('#get_products').closest('.form-group').hide();

        $('#inp-maximum_discount_limit').closest('.form-group').hide();
        $('#inp-discount').closest('.form-group').show();
        $('#inp-discount_type').closest('.form-group').show();
        $('#category_id').closest('.col-md-12').show();
        $('#inp-product_id').closest('.col-md-6').show();
    }

}
function fetchFeeStructureRow(id) {
    obj = {
        id,
    };
    $("#here").empty();
    fetchHtmlContent(obj, "here", "/admin/getFeeStructureRow");
}
function showProductsonMultiCategorySelect() {
    let values = $('#category_id').val();
    console.log(values);
    console.log(values);
    let callback = function () {
        $("input[name='product_id[]']").select2("destroy");
        $("input[name='product_id[]'").select2({
            dropdownParent: $("#crud_modal"),
            minimumInputLength: 3,
            ajax: {
                delay: 250,

                url: "/search_table",
                dataType: "json",
                data: function (params) {

                },
                processResults: function (data) {
                    console.log("data", data);
                    return {
                        results: data.message,
                    };
                },
            },
        });
    };

    showDependentSelectBoxForMultiSelect(
        'category_id',
        'name',
        values,
        'inp-products',
        'products',
        "id",
        callback
    )
}

function someInitOnAnyPopupOpen() {
    const myModalEl = document.getElementById("myModal");
    const bulkUpdateModal = document.getElementById("bulk_update_modal");

    const filterModal = document.getElementById("filter_modal");
    const jsonModal = document.getElementById("json_modal");
    const invoiceModal = document.getElementById("invoice_modal");
    const accept_payment_modal = document.getElementById(
        "accept_payment_modal"
    );

    if (myModalEl) {
        myModalEl.addEventListener("shown.bs.modal", (event) => {
            applySelect2("select", true, "myModal");
        });
    }
    if (bulkUpdateModal) {
        bulkUpdateModal.addEventListener("shown.bs.modal", (event) => {
            applySelect2("select", true, "bulk_update_modal");
        });
    }

    if (jsonModal) {
        jsonModal.addEventListener("shown.bs.modal", (event) => {
            applySelect2("select", true, "json_modal");
            flatpickr("input[type='date']");
        });
    }
    if (accept_payment_modal) {
        accept_payment_modal.addEventListener("shown.bs.modal", (event) => {
            applySelect2("select", true, "accept_payment_modal");
            initializeFormAjaxSubmitAndValidation();
        });
    }
    if (invoiceModal) {
        invoiceModal.addEventListener("shown.bs.modal", (event) => {
            applySelect2("select", true, "invoice_modal");
            flatpickr("input[type='date']");
        });
    }
    if (filterModal) {
        filterModal.addEventListener("shown.bs.modal", (event) => {
            applySelect2("select", true, "filter_modal");
            flatpickr("input[type='date']"); /**tfor date inpit */
        });
    }
    var myDropdown = document.getElementById("filter");

    if (myDropdown) {
        myDropdown.addEventListener("shown.bs.dropdown", function () {
            alert();
            applySelect2("select", true, "filter");
        });
    }
}

function inilizeEvents() {
    if ($("#filter").length > 0) {
        $("#filter").on("hide.bs.dropdown", function (e) {
            if (e.clickEvent) {
                e.preventDefault();
            }
        });
    }

    //applySelect2("select", false);
    initiateSelect2ChangeEvents(false);
    someInitOnAnyPopupOpen();

    if ($("#image").length > 0) {
        $("#image").on("change", function () {
            multiImagePreview(this, "gallery1");
        });
    }
    if ($("#inp-image").length > 0) {
        $("#inp-image").on("change", function () {
            /***always take for single image filed name image ,here inp is aapended automatically to image id */
            singleImagePreview(this, "gallery1");
        });
    }
    if ($("#inp-password").length > 0) {
        $("#inp-password").keyup(function (event) {
            var password = $("#password").val();
            checkPasswordStrength(password);
        });
    }

    $("input[name=has_variant]").on("change", function (v) {
        $("#add_variant").toggle();
    });
}



$(document).ready(function () {

    if ($("form").length > 0) initializeFormAjaxSubmitAndValidation();
    onLoadEditCoupon();
    onlyPageLoadInit();
});



function onlyCrudPopupRelatedInit(module, modal, modal_id) {
    initialiseSummernote();
    applySelect2("select", (in_popup = true), modal_id);
    initiateSelect2ChangeEvents(true, modal_id);
    flatpickr("input[type='date']");

    initFilePreviewEvent();
    initTaggedInput()
    showToggableDivOnLoadIfPresent();
    //initializeModalFormValidation(module, bsOffcanvas);
    initializeModalFormValidation(module, modal);
    initMultiSelectMoveTo()

}
function onlyPageLoadInit() {
    applySelect2("select", false);
    flatpickr("input[type=date]");
    initTaggedInput()
    hideShowToggleClassHavingFormControl()
    initialiseSummernote();
    inilizeEvents(); /***isi mein opup or modal or dropdown related innit hai */

    showToggableDivOnLoadIfPresent();
    initFilePreviewEvent();

    initMultiSelectMoveTo()

}

