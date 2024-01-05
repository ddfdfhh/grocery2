"use strinct";
let numberFormat = new Intl.NumberFormat("en-IN", {
    style: "currency",
    currency: "INR",
});
if ($('#invoice_modal').length > 0) {
    let invoice_model = new bootstrap.Modal(
        document.querySelector("#invoice_modal"),
        { backdrop: "static" }
    );
}
function dynamicAddRemoveRow(todo) {
    let container = $("#repeatable_container");
    if (todo == "add") {
        let copy = container.children().first();
        let content_to_copy = copy.clone();
        container.append(content_to_copy);
        let row_list = container.find(".row1");
        let count = row_list.length;
        let last_row = container.find(".row1").last();
        last_row.attr("id", "row-" + count);
        let t = this;
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
    } else {
        if (container.children().length > 1) {
            
            container.children().last().remove();
            calculateOverall();
        }
    }
}
function setInputAutocompleteSearch(value) {
    let t = event.target;
  let hidden_input = $(t).closest('.row1')
      .find("input[type='hidden']")
      .first();
    $.ajax({
        url: "/search_table",
        type: "post",
        data: {
            search_by_column: $(t).data("search-by-column"),
            search_name_column: $(t).data("search-name-column"),
            search_id_column: $(t).data("search-id-column"),
            search_table: $(t).data("search-table"),
            value,
        },
        dataType: "json",
        success: function (response) {
            let ul = $(t)
                .closest(".autocomplete")
                .find(".dropdown-menu")
                .first();
            response = response["message"];
            if (Array.isArray(response)) {
                var len = response.length;

                ul.empty();
                for (var i = 0; i < len; i++) {
                    var id = response[i]["id"];
                    var name = response[i]["text"];
                    if (name !== undefined) {
                        ul.append("<li value='" + id + "'>" + name + "</li>");
                    }
                }

                // binding click event to li
                ul.find("li").bind("click", function () {
                    $(t).val($(this).text());
                    hidden_input.val($(this).attr('value'));
                    ul.empty();
                    console.log("vall", $(this).attr("value"));
                    fetchRowFromTable(
                        $(t).data("search-table"),
                        $(this).attr("value"),
                        t
                    );
                });
            } else {
                ul.empty();
                ul.html("<li value=''>Not Found</li>");
            }
        },
    });
}
let sub_total = 0;
let total_sgst_tax = 0;
let total_cgst_tax = 0;
let total_igst_tax = 0;
let total_discount = 0;
let net_payable = 0;
/****Invoice generate ke time when product is fetched then on change pe ye funnction call hoga */
function fetchRowFromTable(table, id, input_el) {
    let price_input = $(input_el)
        .closest(".row1")
        .find(".invoice-item-price")
        .first();

    let hsn_input = $(input_el)
        .closest(".row1")
        .find(".invoice-item-hsn")
        .first();
    let sgst_tax_amount_input = $(input_el)
        .closest(".row1")
        .find(".invoice-item-sgst-amount")
        .first();
    let sgst_tax_per_input = $(input_el)
        .closest(".row1")
        .find(".invoice-item-sgst-per")
        .first();
    let cgst_tax_amount_input = $(input_el)
        .closest(".row1")
        .find(".invoice-item-cgst-amount")
        .first();
    let cgst_tax_per_input = $(input_el)
        .closest(".row1")
        .find(".invoice-item-cgst-per")
        .first();
    let igst_tax_amount_input = $(input_el)
        .closest(".row1")
        .find(".invoice-item-igst-amount")
        .first();
    let igst_tax_per_input = $(input_el)
        .closest(".row1")
        .find(".invoice-item-igst-per")
        .first();

    let discount_input = $(input_el)
        .closest(".row1")
        .find(".invoice-item-discount")
        .first();
    let qty_input = $(input_el)
        .closest(".row1")
        .find(".invoice-item-qty")
        .first();
    let taxable_amount_input = $(input_el)
        .closest(".row1")
        .find(".invoice-item-taxable_amount")
        .first();
    let row_total_price_input = $(input_el)
        .closest(".row1")
        .find(".invoice-item-row-total-price")
        .first();
    let sgst_tax_per_val = 0;
    let cgst_tax_per_val = 0;
    let igst_tax_per_val = 0;

    let price_val = 0;
    let qty_val = 0;
    let discount_val = 0;
    let row_total = 0;
    objectAjaxNoLoaderNoAlert(
        { table, id },
        `/fetchRowFromTable`,
        (htmlLoadcallback = function (res) {
            console.log(res["message"]);

            price_val = res["message"]["price"];
            price_input.val(price_val);
            qty_input.val(1);
            qty_val = 1;
            // alert();

            if (res["message"]["hsn_code"] !== undefined)
                hsn_input.val(res["message"]["hsn_code"]);
            if (res["message"]["sgst_rate"] !== undefined)
                sgst_tax_per_input.val(res["message"]["sgst_rate"]);
            if (res["message"]["cgst_rate"] !== undefined)
                cgst_tax_per_input.val(res["message"]["cgst_rate"]);
            if (res["message"]["igst_rate"] !== undefined)
                igst_tax_per_input.val(res["message"]["igst_rate"]);
            if (res["message"]["sgst_rate"] !== undefined)
                sgst_tax_per_input.val(res["message"]["sgst_rate"]);
            if (res["message"]["hsn_code"] !== undefined)
                hsn_input.val(res["message"]["hsn_code"]);
            if (res["message"]["discount"] !== undefined) {
                discount_input.val(res["message"]["discount"]);
                discount_val = res["message"]["discount"];
            } else {
                discount_input.val(0);
                discount_val = 0;
            }

            price_input.trigger("change");
            qty_input.trigger("change");
            discount_input.trigger("change");
            igst_tax_per_input.trigger("change");
            cgst_tax_per_input.trigger("change");
            sgst_tax_per_input.trigger("change");
            reCalcuate(
                price_val,
                discount_val,
                sgst_tax_per_val,
                cgst_tax_per_val,
                igst_tax_per_val,
                sgst_tax_amount_input,
                cgst_tax_amount_input,
                igst_tax_amount_input,
                taxable_amount_input,
                qty_val,
                row_total_price_input
            );
        })
    );

    price_input.change(function (e) {
        price_val = $(this).val();
        reCalcuate(
            price_val,
            discount_val,
            sgst_tax_per_val,
            cgst_tax_per_val,
            igst_tax_per_val,
            sgst_tax_amount_input,
            cgst_tax_amount_input,
            igst_tax_amount_input,
            taxable_amount_input,
            qty_val,
            row_total_price_input
        );
    });
    sgst_tax_per_input.change(function (e) {
        sgst_tax_per_val = $(this).val();
        reCalcuate(
            price_val,
            discount_val,
            sgst_tax_per_val,
            cgst_tax_per_val,
            igst_tax_per_val,
            sgst_tax_amount_input,
            cgst_tax_amount_input,
            igst_tax_amount_input,
            taxable_amount_input,
            qty_val,
            row_total_price_input
        );
    });
    cgst_tax_per_input.change(function (e) {
        cgst_tax_per_val = $(this).val();
        reCalcuate(
            price_val,
            discount_val,
            sgst_tax_per_val,
            cgst_tax_per_val,
            igst_tax_per_val,
            sgst_tax_amount_input,
            cgst_tax_amount_input,
            igst_tax_amount_input,
            taxable_amount_input,
            qty_val,
            row_total_price_input
        );
    });
    igst_tax_per_input.change(function (e) {
        igst_tax_per_val = $(this).val();
        reCalcuate(
            price_val,
            discount_val,
            sgst_tax_per_val,
            cgst_tax_per_val,
            igst_tax_per_val,
            sgst_tax_amount_input,
            cgst_tax_amount_input,
            igst_tax_amount_input,
            taxable_amount_input,
            qty_val,
            row_total_price_input
        );
    });
    discount_input.change(function (e) {
        discount_val = $(this).val();
        reCalcuate(
            price_val,
            discount_val,
            sgst_tax_per_val,
            cgst_tax_per_val,
            igst_tax_per_val,
            sgst_tax_amount_input,
            cgst_tax_amount_input,
            igst_tax_amount_input,
            taxable_amount_input,
            qty_val,
            row_total_price_input
        );
    });
    qty_input.change(function (e) {
        qty_val = $(this).val();
        reCalcuate(
            price_val,
            discount_val,
            sgst_tax_per_val,
            cgst_tax_per_val,
            igst_tax_per_val,
            sgst_tax_amount_input,
            cgst_tax_amount_input,
            igst_tax_amount_input,
            taxable_amount_input,
            qty_val,
            row_total_price_input
        );
    });
}
function reCalcuate(
    price_val,
    discount_val,
    sgst_tax_per_val,
    cgst_tax_per_val,
    igst_tax_per_val,
    sgst_tax_amount_input,
    cgst_tax_amount_input,
    igst_tax_amount_input,
    taxable_amount_input,
    qty_val,
    row_total_price_input
) {
    let discount = (price_val * discount_val) / 100;
    let discounted_price = price_val - discount;
    let sgst_tax_amount = (discounted_price * sgst_tax_per_val) / 100;
    sgst_tax_amount_input.val(sgst_tax_amount * qty_val);
    let cgst_tax_amount = (discounted_price * cgst_tax_per_val) / 100;
    cgst_tax_amount_input.val(cgst_tax_amount * qty_val);
    let igst_tax_amount = (discounted_price * igst_tax_per_val) / 100;
    igst_tax_amount_input.val(igst_tax_amount * qty_val);
    taxable_amount_input.val(discounted_price * qty_val);
    row_total =
        (discounted_price +
            sgst_tax_amount +
            cgst_tax_amount +
            igst_tax_amount) *
        qty_val;
    row_total_price_input.val(row_total);
    calculateOverall();
}
function calculateOverall() {
    let total_qty = 0;
    let total_discount = 0;
    let total_sgst_tax = 0;
    let total_cgst_tax = 0;
    let total_igst_tax = 0;
    let sub_total = 0;
    let net_payable = 0;
    let additional_discount = 0;
    $("#repeatable_container .row1").each(function (el) {
        let price = parseFloat(
            $(this).find(".invoice-item-price").first().val()
        );

        let qty = parseFloat($(this).find(".invoice-item-qty").first().val());
        total_qty += qty;
        sub_total += price * qty;
        let discount_per = parseFloat(
            $(this).find(".invoice-item-discount").first().val()
                ? $(this).find(".invoice-item-discount").first().val()
                : 0.0
        );
        let discount_amount = parseFloat((price * discount_per) / 100);
        let discounted_price = price - discount_amount;
        total_discount += discount_amount * qty;
        let sgst_per = parseFloat(
            $(this).find(".invoice-item-sgst-per").first().val()
                ? $(this).find(".invoice-item-sgst-per").first().val()
                : 0.0
        );
        let sgst_tax_amount = parseFloat((discounted_price * sgst_per) / 100);
        total_sgst_tax += sgst_tax_amount * qty;
        let cgst_per = parseFloat(
            $(this).find(".invoice-item-cgst-per").first().val()
                ? $(this).find(".invoice-item-cgst-per").first().val()
                : 0.0
        );

        let cgst_tax_amount = parseFloat((discounted_price * cgst_per) / 100);
        total_cgst_tax += cgst_tax_amount * qty;
        let igst_per = parseFloat(
            $(this).find(".invoice-item-igst-per").first().val()
                ? $(this).find(".invoice-item-igst-per").first().val()
                : 0
        );

        let igst_tax_amount = parseFloat((discounted_price * igst_per) / 100);

        total_igst_tax += igst_tax_amount * qty;
    });
    net_payable =
        sub_total +
        total_igst_tax +
        total_cgst_tax +
        total_sgst_tax -
        total_discount;

    $("#subtotal").val(sub_total);
    $("#total_discount").val(total_discount);
    $("#total_sgst").val(total_sgst_tax);
    $("#total_cgst").val(total_cgst_tax);
    $("#total_igst").val(total_igst_tax);
    console.log("netpa", net_payable);
    $("#net_payable").val(net_payable);
}
$("#total_discount").change(function () {
    let total_discount = parseFloat($(this).val());
    let sub_total = parseFloat($("#subtotal").val());
    console.log('subtot',sub_total)
    let additional_discount = parseFloat($("#additional_discount").val());
    let total_sgst= parseFloat($("#total_sgst").val());
    let total_cgst = parseFloat($("#total_cgst").val());
    let total_igst = parseFloat($("#total_igst").val());
    let total_shipping_charge = parseFloat($("#total_shipping_charge").val());
    let net_payable = sub_total + total_sgst + total_cgst + total_igst + total_shipping_charge - (total_discount + additional_discount);
   
    $("#net_payable").val(net_payable);
});
$("#additional_discount").change(function () {
    let additional_discount = parseFloat($(this).val());

    let sub_total = parseFloat($("#subtotal").val());
    let total_discount = parseFloat($("#total_discount").val());
  let total_sgst = parseFloat($("#total_sgst").val());
  let total_cgst = parseFloat($("#total_cgst").val());
  let total_igst = parseFloat($("#total_igst").val());
    let total_shipping_charge = parseFloat($("#total_shipping_charge").val());
  let net_payable =
      sub_total +
      total_sgst +
      total_cgst +
      total_igst +
      total_shipping_charge -
      (total_discount + additional_discount);

  $("#net_payable").val(net_payable);
});

$("#total_shipping_charge").change(function () {
    let additional_discount = parseFloat($("#additional_discount").val());

    let sub_total = parseFloat($("#subtotal").val());
    let total_discount = parseFloat($("#total_discount").val());
    let total_sgst= parseFloat($("#total_sgst").val());
    let total_cgst = parseFloat($("#total_cgst").val());
    let total_igst = parseFloat($("#total_igst").val());
    let total_shipping_charge = parseFloat($(this).val());
    let net_payable =
        sub_total +
        total_sgst +
        total_cgst +
        total_igst +
        total_shipping_charge -
        (total_discount + additional_discount);
    $("#net_payable").val(net_payable);
});

function showSubCategories(value) {
    let callback = function (res) {
        let options = { dropdownParent: $("#invoice_modal") };
        $("select").each(function (i, obj) {
            if (!$(obj).data("select2")) {
                $(obj).select2(options);
            }
        });
    };
    showDependentSelectBox(
        "parent_id",
        "name",
        value,
        "sub_category",
        "categories",
        "id",
        callback
    );
}
function fetchProducts(value) {
    let callback = function (res) {
        let options = {
            dropdownParent: $("#invoice_modal"),
            ajax: {
                delay: 250,

                url: "/search_table",
                dataType: "json",
                data: function (params) {
                    let query = {
                        search_by_column:
                            $("#inp-products").data("search-by-column"),
                        search_name_column:
                            $("#inp-products").data("search-name-column"),
                        search_id_column:
                            $("#inp-products").data("search-id-column"),
                        search_table: $("#inp-products").data("search-table"),
                        value: params.term,
                        where: {
                            category_id: value,
                        },
                    };

                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data.message,
                    };
                },
            },
        };

        // $("#inp-products").select2('destroy');
        $("#inp-products").select2(options);
    };
    showDependentSelectBox(
        "category_id",
        "name",
        value,
        "products",
        "products",
        "id",
        callback
    );
}

/*****Modal Popup **************** */
function setInputIdInRepeatable1(input_id_to_get_val,from_module=undefined) {
    /***This set the select input values jisme kuch populate karna hai after modal close*** */
    console.log("current_id", current_id);
    let option_txt = $("#inp-" + input_id_to_get_val)
        .find(":selected")
        .text();
    let val = $("#inp-" + input_id_to_get_val).val();

    let first_input = $("#" + current_id)
        .find("input[type='text']")
        .first();
    let first_input_hidden = $("#" + current_id)
        .find("input[type='hidden']")
        .first();
    first_input.val(option_txt);

    first_input_hidden.val(val);
    if (from_module == 'Invoice') {
        invoice_model.hide();
        /***Add function like ajax to fetch data related to current set value */
        fetchRowFromTable("products", val, first_input);
    }
}
/****Har row mein given button pe click karne pe popup ope karo json_modal givne in repeataable compoent */

function openModalForInvoiceProductAdd() {
    target = event.target;
    let c = $(target).closest(".row1");
    let id = c.attr("id");
    current_id = id;

    invoice_model.show();
}
function fetchCustomerDetail(id) {
    let table = "customers";

    objectAjaxNoLoaderNoAlert(
        { table, id },
        `/fetchRowFromTable`,
        (htmlLoadcallback = function (res) {
            res = res["message"];
            $("#customer_name").val(
                res["name"] ? res["name"] : res["company_name"]
            );
            $("#company_name").val(res["company_name"]);
            $("#customer_email").val(res["customer_email"]);
            $("#billing_address").val(
                res["billing_address_line_1"] +
                    " " +
                    res["billing_address_line_2"] +
                    " " +
                    res["billing_address_landmark"] +
                    " " +
                    res["billing_address_state"]
            );
            $("#billing_address_line_1").val(res["billing_address_line_1"]);
            $("#billing_address_line_2").val(res["billing_address_line_2"]);
            $("#billing_address_state").val(res["billing_address_state"]);
            $("#billing_address_zipcode").val(res["billing_address_zipcode"]);
            $("#gstin").val(res["gstin"]);

            $("#billing_address_contact_no").val(
                res["billing_address_contact_no"]
            );

            $("#shipping_address").val(
                res["shipping_address_line_1"] +
                    " " +
                    res["shipping_address_line_2"] +
                    " " +
                    res["shipping_address_landmark"] +
                    " " +
                    res["shipping_address_state"]
            );
            $("#shipping_address_zipcode").val(res["shipping_address_zipcode"]);

            $("#shipping_address_contact_no").val(
                res["shipping_address_contact_no"]
            );
        })
    );
}
function hideNonQuotationDiv(val) {
    document.querySelector("#title").innerHTML = val;
    if (val == "Quotation") $("#non-quotation").addClass("hide");
    else $("#non-quotation").removeClass("hide");
}
