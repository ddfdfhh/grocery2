

/****************Image preview onload ***************************/
function singleImagePreview(input, placeToInsertImagePreview) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        let file = input.files[0];
        if (file["type"].search("image") >= 0) {
            reader.onload = function (event) {
                let y = event.target.result;
                $("#" + placeToInsertImagePreview).append(
                    `<img src='${y}'  class='img_rounded' />`
                );
            };

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }
}
/**======================================Multiple Image Preview js===================  */
var multiImagePreview = function (input, placeToInsertImagePreview) {
    if (input.files) {
        var filesAmount = input.files.length;
        for (i = 0; i < filesAmount; i++) {
            let file = input.files[i];
            if (file["type"].search("image") >= 0) {
                var reader = new FileReader();
                reader.onload = function (event) {
                    let y = event.target.result;
                    $("#" + placeToInsertImagePreview).append(
                        `<img src='${y}'  class='img_rounded' style='width:100px;height:100px;margin:5px' />`
                    );
                };
                reader.readAsDataURL(input.files[i]);
            }
        }
    }
};
/**=================Get interdependednt Select Box Data=========================== */
function showDependentSelectBox(
    dependee_key,
    dependent_key,
    value,
    dependent_select_box_id,
    table,
    table_id = "id",
    callback
) {
    if (value.length == 0) {
        return false;
    }
    let obj = { dependee_key, dependent_key, value, table, table_id };
    var callbackSuccess = function (response) {
        $("select#" + dependent_select_box_id).html(response["message"]);
        callback();
    };
    objectAjaxNoLoaderNoAlert(obj, "/getDependentSelectData", callbackSuccess);
}
function showDependentSelectBoxForMultiSelect(
    dependee_key,
    dependent_key,
    value = [],/**it should be array here passed  */
    dependent_select_box_id,
    table,
    table_id = "id",
    callback
) {
    
    /**this funcyion is for multiple value select  */
    if (value.length == 0) {
        return false;
    }
    let obj = {
        dependee_key,
        dependent_key,
        value: JSON.stringify(value),
        table,
        table_id,
    };
    var callbackSuccess = function (response) {
       
      
        $("select#" + dependent_select_box_id).html(response["message"]);
        callback();
    };
    objectAjaxNoLoaderNoAlert(
        obj,
        "/getDependentSelectDataMultipleVal",
        callbackSuccess
    );
}
function dynamicAddRemoveRow(
    todo
) {
    let container = $("#repeatable_container");
    if (todo == 'add') {
        let copy = container.children().first();
        let content_to_copy = copy.clone()
        container.append(content_to_copy);
        let row_list = container.find(".row");
        let count = row_list.length
        let last_row = container.find('.row').last();
        last_row.attr('id', 'row-' + count);
        let t = this
        $("select").each(function (i, obj) {
            let options = {
                ajax: {
                    delay: 250,
                    url: "/search_table",
                    dataType: "json",
                    data: function (params) {
                        let query = {
                            search_by_column: $(t).data("search-by-column"),
                            search_name_column: $(t).data("search-name-column"),
                            search_id_column: $(t).data("search-id-column"),
                            search_table: $(t).data("search-table"),
                            value: params.term,
                        };
                        console.log(query);
                        return query;
                    },
                    processResults: function (data) {
                        console.log("data", data);
                        return {
                            results: data.message,
                        };
                    },
                },
            };
            if (!$(obj).data("select2")) {
                $(obj).select2(options);
            }
        });
    }
    else {
        if (container.children().length > 1) {
            let len = container.children().length;;
            let price_input = $("#row-" + len)
                .find(".invoice-item-price")
                .first();

            let tax_input = $("#row-" + len)
                .find(".invoice-item-tax")
                .first();
            let discount_input = $("#row-" + len)
                .find(".invoice-item-discount")
                .first();
            let qty_input = $("#row-" + len)
                .find(".invoice-item-qty")
                .first();
            let row_total_price = $("#row-" + len)
                .find(".row-total-price")
                .first();
            let tax_val = tax_input.val();
            let price_val = price_input.val();
            let qty_val = qty_input.val();
            let discount_val = discount_input.val();
            let discounted_price = price_val * (1 - discount_val / 100);
            let tax_amount = (discounted_price * tax_val) / 100;
            let row_total = (discounted_price + tax_amount) * qty_val;/****itna total minus kardo subtotal se on remove  */
            container.children().last().remove();
        }
    }

}

function liveSearchSelect(selectboxid, url) {
    $("#" + selectboxid).autocomplete({
        source: function (request, response) {
            var ajaxOpt = { url: url, data: { term: request.term } };
            $.ajax(ajaxOpt).done(function (data) {
                response(
                    data
                ); /**==========Response(data )shold be array with item {value:'v1', label:'Value 1',extradata:'jQuery1'} ====******/
            });
        },
    });
}
function multiSelectCheckBoxAction(table) {

    /******field coulumn whose value is to be set to value **/
    let p = new Array();
    $(`input[name='ids[]']:checked`).each(function (i) {
        p.push($(this).val());
    });
    if (p.length < 1) {
        errorAlert("Please select rows first");
        return;
    }

    let url = "/table_field_update";
    Swal.fire({
        title: "Are you sure want to proceed?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, do it!",
    }).then((result) => {
        if (result.isConfirmed) {

            let form_val = $("#bulk_form").serialize();
            obj = { ids: JSON.stringify(p), table, form_val };
            let callbackSuccess = function () {
                for (i of p) {
                    $("#row-" + i).hide();
                }
                location.reload();
            };
            objectAjaxWithBtnAndLoader("btnid", obj, url, callbackSuccess);
        }
    });
}
function bulkDelete(table) {
    let p = new Array();
    $(`input[name='ids[]']:checked`).each(function (i) {
        p.push($(this).val());
    });
    if (p.length < 1) {
        errorAlert('Please select rows to update')
        return false;
    }

    /******field coulumn whose value is to be set to value **/
    Swal.fire({
        title: "Are you sure want to delete selected records",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, do it!",
    }).then((result) => {
        if (result.isConfirmed) {


            obj = {
                ids: JSON.stringify(p),
                table,

            };
            let callbackSuccess = function () {
                $(".modal").hide();
                p.forEach(function (v) {
                    $("#row-" + v).hide();
                })
            };
            objectAjaxWithBtnAndLoader(
                "btnid",
                obj,
                "/bulk_delete",
                callbackSuccess
            );
        }
    });
}
function deleteByAjax(rowid, btnid, url) {
    obj = { id: rowid };
    let callbackSuccess = function () {
        $("#row-" + i).hide();
    };
    objectAjaxWithBtnAndLoader(btnid, obj, url, callbackSuccess);
}
function deleteRecord(id, url) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            let formData = { id: id, _method: "DELETE" };
            $.ajax({
                url: url,
                method: "POST",
                dataType: "json",
                data: formData,
                beforeSend: function () {
                    blockUi();
                },
                success: function (res) {
                    successAlert("Record Deleted successfully");
                    $("#row-" + id).hide();
                },
                complete: function () {
                    unBlockUi();
                },
                error: function (xhr, status, errorThrown) {
                    formatErrorMessage(xhr, errorThrown);
                },
            });
        }
    });
}
function deleteRecordFromTable(id, table) {
    url = '/deleteRecordFromTable'
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            let formData = { id: id, table };
            $.ajax({
                url: url,
                method: "POST",
                dataType: "json",
                data: formData,
                beforeSend: function () {
                    blockUi();
                },
                success: function (res) {
                    successAlert("Record Deleted successfully");
                    $("#row-" + id).hide();
                },
                complete: function () {
                    unBlockUi();
                },
                error: function (xhr, status, errorThrown) {
                    formatErrorMessage(xhr, errorThrown);
                },
            });
        }
    });
}
/*===============Checks password strenght with  live options===========================  */
//password-strength.js
function passwordStrengthChecker() {
    $("#password").keyup(function (event) {
        var password = $("#password").val();
        checkPasswordStrength(password);
    });
}
function singleFieldUpdateInTable(rowid, field, val, table) {
    obj = { id: rowid, table, val, field };
    let callbackSuccess = function () {
        //$("#row-" + rowid).hide();
        location.reload();
    };
    url = '/singleFieldUpdateFromTable'
    objectAjaxWithBtnAndLoader('df', obj, url, callbackSuccess);;
}
/**=========================================Multiple Tagged input box  search wit own custom add also =====================================*/
//Fast select https://dbrekalo.github.io/fastselect/#section-Examples
/**<input
  type="text"
    
      multiple
      class="tagsInput"
      value="Algeria,Angola"
      data-initial-value='[{"text": "Algeria", "value" : "Algeria"}, {"text": "Angola", "value" : "Angola"}]'
      data-user-option-allowed="true"
      data-url="demo/data.json"
      data-load-once="true"
  name="language"//>*/
//$('.multipleInputDynamic').fastselect();
/**==============================================Select with Live Search Ajax============================================================= */
//FastSelect
/**<input type="text" value="Algeria" data-initial-value='{"text": "Algeria", "value" : "Algeria"}' 
  class="singleInputDynamicWithInitialValue" data-url="demo/data.json" data-load-once="true" name="language" />*/

function hideShowToggleClassHavingFormControl() {
    let ar = $("input");

    if (ar.length > 0) {
        ar.each((v) => {
            let el = ar[v];

            let hide =
                $(el).data("hide") !== undefined ? $(el).data("hide") : "false";

            if (hide == true) $(el).closest(".form-group").parent().hide();
        });
    }
}
/**================================Fetch remote html content into current container */
function fetchHtmlContent(obj, container_id, url) {
    let callbackSuccess = function (res) {
        $("#" + container_id).html(res["message"]);
    };
    objectAjaxNoLoaderNoAlert(obj, url, callbackSuccess);
}
/**===============================================Check ALl Checkbox============================== */
window.onload = function () {
    $('#check_all').change(function () {
        let target = event.target;
        checkAll($(target).is(":checked"));
    })
}
function checkAll(is_checked) {
    $("input[name='ids[]']").not(this).prop("checked", is_checked);
}
/**=============================SHow More Or Less Button**/
/**====Styles to add ==
  <style>
     
  .more{
      font-size:14px!important;overflow-wrap:break-word;max-width:200px;white-space:initial;
  }
  .morecontent span {
    display: none;
  }
  .morelink {
    display: block;color:orange!important;font-weight:bold;
  }
  </style>
  
  
   */
var showChar = 250; // How many characters are shown by default
var ellipsestext = "...";
var moretext = "Show more >";
var lesstext = "Show less";

$(".more").each(function () {
    var content = $(this).html();
    console.log(content);
    if (content.length > showChar) {
        var c = content.substr(0, showChar);
        var h = content.substr(showChar, content.length - showChar);
        console.log(c);
        console.log(h);
        var html =
            c +
            '<span class="moreellipses">' +
            ellipsestext +
            '&nbsp;</span><span class="morecontent"><span>' +
            h +
            '</span>&nbsp;&nbsp;<a href="" class="morelink">' +
            moretext +
            "</a></span>";
        console.log(html);
        $(this).html(html);
    }
});

$(".morelink").click(function () {
    if ($(this).hasClass("less")) {
        $(this).removeClass("less");
        $(this).html(moretext);
    } else {
        $(this).addClass("less");
        $(this).html(lesstext);
    }
    $(this).parent().prev().toggle();
    $(this).prev().toggle();
    return false;
});
/****================================================== */
/*******************View rcord in modal form not sidepopup  *******/
function viewRecord(id, url, module) {
    loading = true;
    disableBtn();
    $(`#${module}_modal .modal-body`).html(
        '<div class="spinner-border text-muted"></div>'
    );
    $(`#${module}_modal .modal-body`).css('textAlign', 'center');
    let obj = {
        id: id,
    };
    let callbackSuccess = function (res) {
        var myModal = new bootstrap.Modal(
            document.getElementById(`${module}_modal`),
            {}
        );
        myModal.show();
        enableBtn();
        setTimeout(function () {
            $(`#${module}_modal .modal-body`).html(res["message"]);
            $(`#${module}_modal .modal-body`).css("textAlign", "left");
        }, 1000);
    };
    objectAjaxNoLoaderNoAlert(obj, url, callbackSuccess, undefined, "GET");
}
/**********Delete file from separate file table **************** */
function deleteFileFromTable(id, table, folder, url) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            let obj = {
                id,
                table,
                folder,
            };
            let callback = function (res) {
                // location.reload();
                $("#img_div-" + id).hide();
                if ($("#variant_img_div-" + id).length > 0)
                    $("#variant_img_div-" + id).hide();
            };

            objectAjaxWithBtnAndLoader((btnid = undefined), obj, url, callback);
        }
    });
}
function deleteFileSelf(file_name, modelName, folder_name, field_name, row_id) {
    let url = "/delete_file_self";
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            let obj = {
                file_name,
                modelName,
                folder_name,
                field_name,
                row_id,
            };
            let callback = function (res) {
                // location.reload();
                $("#img_div").hide();
                if ($("#variant_img_div").length > 0)
                    $("#variant_img_div").hide();

            };

            objectAjaxWithBtnAndLoader((btnid = undefined), obj, url, callback);
        }
    });
}
/****Delete data from JSON colummn  */
function deleteJsonColumnData(
    row_id_val,
    inside_json_column_id,
    table,
    json_id_val,
    json_column_name,
    url
) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            let callbackSuccess = function (res) {
                $(".detail #row-" + json_id_val).hide();
            };
            let callbackError = function (res) { };

            objectAjaxWithBtnAndLoader(
                "remark_btn-",
                {
                    json_column_name,
                    table,
                    by_json_key: inside_json_column_id,
                    row_id: row_id_val,
                    json_key_val: json_id_val,
                },
                url,
                callbackSuccess,
                callbackError,
                true
            );
        }
    });
}
function showToggableDivOnLoadIfPresent() {
    if ($(".toggable_div").length > 0) {
        $(".toggable_div").each(function () {
            let id = $(this).attr("id");
            let colname = $(this).attr("data-colname");

            let inputidforval = $(this).data("inputidforvalue");
            let rowid = $(this).data("rowid");
            console.log(inputidforval);
            if (inputidforval.length > 0) {
                let val = inputidforval;

                let module = $(this).data("module");

                objectAjaxNoLoaderNoAlert(
                    { val: val, row_id: rowid, colname },
                    `/admin/${module.toLowerCase()}/load_snippets`,
                    (htmlLoadcallback = function (res) {
                        $("#" + id).html(res["message"]);
                    })
                );
            }
        });
    }
}
/*************This function when loading form in offcanvas modal from right ************** */

function load_form_offcanvas(module, url, crud_title, form_type) {
    let lowercase_name = module.toLowerCase();
    var myOffcanvas = document.getElementById("offcanvasEnd");
    $("#offcanvasEnd .offcanvas-body").addClass("text-center");

    $("#offcanvasEndLabel").html(form_type + "&nbsp;&nbsp;" + crud_title);
    $("#offcanvasEnd .offcanvas-body").html(
        "<div class='spinner-border' style='position:absolute;top: 50%;left:50%'></div>"
    );
    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
    bsOffcanvas.show();
    let obj = {};
    let htmlLoadcallback = function (res) {
        $("#offcanvasEnd .offcanvas-body").removeClass("text-center");
        $("#offcanvasEnd .offcanvas-body").html(res["message"]);
        onlyCrudPopupRelatedInit(module, bsOffcanvas, "offcanvasEnd");
    };
    calbackError = function (msg) {
        bsOffcanvas.hide();
        errorAlert(msg); /****In case permission error to load form */
    };
    objectAjaxNoLoaderNoAlert(
        obj,
        url,
        htmlLoadcallback,
        calbackError,
        "GET"
    ); /**called to load form */
}
function load_form_modal(module, url, crud_title, form_type) {
    let lowercase_name = module.toLowerCase();

    let myModal_id = document.getElementById("crud_modal");
    $("#crud_modal .modal-body").addClass("text-center");
    //  properName = properName.replace("Create", "");

    $("#modal-title").html(form_type + "&nbsp;&nbsp;" + crud_title);
    $("#crud_modal .modal-body").html(
        "<div class='spinner-border' style='position:absolute;top: 50%;left:50%'></div>"
    );
    let crud_modal = new bootstrap.Modal(myModal_id);
    crud_modal.show();
    let obj = {};
    let htmlLoadcallback = function (res) {
        $("#crud_modal .modal-body").removeClass("text-center");
        $("#crud_modal .modal-body").html(res["message"]);
        onlyCrudPopupRelatedInit(module, crud_modal, "crud_modal");
    };
    calbackError = function (msg) {
        crud_modal.hide();

        errorAlert(msg); /****In case permission error to load form */
    };
    objectAjaxNoLoaderNoAlert(
        obj,
        url,
        htmlLoadcallback,
        calbackError,
        "GET"
    ); /**called to load form */
}
function initializeModalFormValidation(module) {
    let rules = getModuleWiseRules(module);
    let messages = getModuleWiseValidationMessages(module);

    let lowercase_name = module.toLowerCase();
    if ($("#" + lowercase_name + "_form").length > 0) {
        let has_file = false;

        $("#" + lowercase_name + "_form")
            .find("input")
            .each(function (el) {
                if ($(this).attr("type") === "file") {
                    has_file = true;
                    return false;
                }
            });
        url = $("#" + lowercase_name + "_form").attr("action");

        let { callbackSuccess, callbackError } = getModuleWiseCallbacks(module);

        formValidateFunctionTemplate(
            rules,
            messages,
            lowercase_name + "_btn",
            lowercase_name + "_form",
            url,
            callbackSuccess,
            callbackError,
            has_file
        );
    }
}
function initTaggedInput() {
    let ar = $("input[data-role=tagsinput]");

    if (ar.length > 0) {

        ar.each((v) => {
            let el = ar[v];

            let tags =
                $(el).data("tagvalue") !== undefined
                    ? $(el).data("tagvalue")
                    : "";
            $(el).tagsinput("add", tags, {
                tagClass: "badge bg-primary",
            });

            $(el).on('change', function (event) {

                generateVariant()
            });
        });
    }
}
function generateVariant() {

    $els = $('.attribute_values');
    
    ar = {};
    if ($els.length > 0) {
        $els.each(function () {
            let name = $(this).attr('name');
            if (ar[name] == undefined)
                ar[name] = $(this).val()

        });
    }
    
    let orginal_ar = [];
    for (key in ar) {
        let val = ar[key];
        let val_ar = val.split(',');
        orginal_ar.push(val_ar);

    }
    let combinations = combineArraysRecursively(orginal_ar);

    let g = '';
   
    if (combinations.length > 0) {
        
        if($('#model_id').length==0){
                    for (i = 0; i < combinations.length; i++) {
                        let n = combinations[i];
                        g += returnAccordian(n, i);
                    }

                    $('#accord').html(g);
                    initFilePreviewEvent('variant_container');
         }
         else{
            let callbackSuccess = function (res) {
                $('#accord').html(res["message"]);
                initFilePreviewEvent('variant_container');
            };
            objectAjaxNoLoaderNoAlert({product_id:$('#model_id').val(),combinations}, "/admin/generateAccordian", callbackSuccess);
         }
      
    }

}
function returnAccordian(name, i) {
    let s =`<div class="accordion-item shadow mb-2">
      <h2 class="accordion-header">
          <button class="accordion-button" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapse${i}" aria-expanded="true"
              aria-controls="collapse${i}">
              ${name}
          </button>
      </h2>
      <div id="collapse${i}" class="accordion-collapse collapse"
          aria-labelledby="headingOne" data-bs-parent="#default-accordion-example">
          <div class="accordion-body">
              <div class="row">
                 
                  <div class="col-md-3">
                      <div class="form-group">
                          <label class="form-label" for="product-title-input">
                              Price</label>

                          <input type="number" class="form-control" name="variant_price__${name}"
                              placeholder="Price">



                      </div>


                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label class="form-label" for="product-title-input">Sale
                              Price</label>

                          <input type="number" class="form-control" name="variant_sale_price__${name}"
                              id="product-price-input" placeholder="Sale Price"
                             
                              >



                      </div>


                  </div>
                  <div class="col-md-3">
                  <div class="form-group">
                      <label class="form-label" for="product-title-input">Quantity</label>

                      <input type="number" class="form-control" name="variant_quantity__${name}"
                          id="product-price-input" placeholder="Quantity"
                         
                          >



                  </div>


              </div>
              <div class="col-md-2">
              <div class="form-group">
                  <label class="form-label" for="product-title-input">Max Quantity Allowed</label>

                  <input type="number" class="form-control" name="variant_max_quantity_allowed__${name}"
                      id="product-price-input" placeholder="Max Quantity Allowed"
                     
                      >



              </div>


          </div>

                  
              </div>
          </div>
      </div>
              </div>`;
    
    return s;

}
function returnAccordianWithImageUpload(name, i) {
    let s =`<div class="accordion-item shadow mb-2">
      <h2 class="accordion-header">
          <button class="accordion-button" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapse${i}" aria-expanded="true"
              aria-controls="collapse${i}">
              ${name}
          </button>
      </h2>
      <div id="collapse${i}" class="accordion-collapse collapse"
          aria-labelledby="headingOne" data-bs-parent="#default-accordion-example">
          <div class="accordion-body">
              <div class="row">
                 
                  <div class="col-md-2">
                      <div class="form-group">
                          <label class="form-label" for="product-title-input">
                              Price</label>

                          <input type="number" class="form-control" name="variant_price__${name}"
                              placeholder="Price" >



                      </div>


                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          <label class="form-label" for="product-title-input">Sale
                              Price</label>

                          <input type="number" class="form-control" name="variant_sale_price__${name}"
                              id="product-price-input" placeholder="Sale Price"
                             
                              >



                      </div>


                  </div>

                  <div class="col-md-3">
                      <div class="form-group">
                          <label class="form-label" for="product-title-input">Main Image
                          </label>

                          <input type="file" class="form-control"
                              name="variant_image__${name}" />



                      </div>


                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label class="form-label" for="product-title-input">Gallery
                          </label>

                          <input type="file" multiple class="form-control"
                              name="variant_product_images__${name}" />



                      </div>


                  </div>
              </div>
          </div>
      </div>
              </div>`;
    
    return s;

}
function combineArraysRecursively(array_of_arrays) {

    // First, handle some degenerate cases...

    if (!array_of_arrays) {
        // Or maybe we should toss an exception...?
        return [];
    }

    if (!Array.isArray(array_of_arrays)) {
        // Or maybe we should toss an exception...?
        return [];
    }

    if (array_of_arrays.length == 0) {
        return [];
    }

    for (let i = 0; i < array_of_arrays.length; i++) {
        if (!Array.isArray(array_of_arrays[i]) || array_of_arrays[i].length == 0) {
            // If any of the arrays in array_of_arrays are not arrays or are zero-length array, return an empty array...
            return [];
        }
    }

    // Done with degenerate cases...
    let outputs = [];

    function permute(arrayOfArrays, whichArray = 0, output = "") {

        arrayOfArrays[whichArray].forEach((array_element) => {
            if (whichArray == array_of_arrays.length - 1) {
                // Base case...
                if (array_element.length > 1 && output.length > 0)
                    outputs.push(output + "-" + array_element);
                else
                    outputs.push(output + array_element);

            }
            else {
                // Recursive case...
                permute(arrayOfArrays, whichArray + 1, output + array_element);
            }
        });/*  forEach() */
    }

    permute(array_of_arrays);

    return outputs;


}
function initialiseSummernote() {
    if ($(".summernote").length > 0) {
        $(".summernote").each(function (el) {
            $(this).summernote();
        });
    }
}
function initMultiSelectMoveTo() {
    if ($("#multiselect").length > 0) {
        $("#multiselect").multiselect({
            afterMoveToRight: function (left, right, options) {
                right.find("option").each(function (i, el) {
                    $(el).prop("selected", true);
                });
            },
        });
    }
}
function initializeFormAjaxSubmitAndValidation() {
    $("form").each(function () {
        let module = $(this).data("module");
        if (module !== undefined) {
            console.log("all fom", module);
            let url = $(this).attr("action");
            let rules = getModuleWiseRules(module);
            let has_file = false;
            let this_form = this;
            $(this_form)
                .find("input")
                .each(function (el) {
                    let input = this;
                    if ($(input).attr("type") === "file") {
                        has_file = true;
                        return false;
                    }
                });
            let messages = getModuleWiseValidationMessages(module);
            let lowercase_name = module.toLowerCase();
            let { callbackSuccess, callbackError } =
                getModuleWiseCallbacks(module);
            show_server_validation_in_alert = true;
            if (module == "Login" || module == "Registration")
                show_server_validation_in_alert = false;
            formValidateFunctionTemplate(
                rules,
                messages,
                lowercase_name + "_btn",
                lowercase_name + "_form",
                url,
                callbackSuccess,
                callbackError,
                has_file,
                show_server_validation_in_alert
            );
        }
    });
}

/***form formating the select2 js option value like adding custom html in options ******/
function toggleDivDisplay(cur_value, div_id, onVal) {

    if (cur_value == onVal) {
        $("#" + div_id).show();
    }
    else {
        $("#" + div_id).hide()
    }
}
function toggleContainerDirectly(cur_value, div_id, onVal) {
    let div = $("#" + div_id)
    cur_Value = cur_value.trim(),
        onVal = onVal.trim()
    if (cur_value == onVal) {
        $(div).show();
    } else {
        $(div).hide();
    }
}
/****Show one container while hiding other contaienr  */
function toggleTwoContainer(cur_value, div_id, onVal, other_div_id) {

    let div = $("#" + div_id)
    let other_div = $("#" + other_div_id)
    cur_value = cur_value.trim(),
        onVal = onVal.trim()

    if (cur_value == onVal) {

        $(div).show();
        $(other_div).css('display', 'none');
    } else {
        $(div).css('display', 'none');
        $(other_div).show();
    }
    $('#accord').empty();


}