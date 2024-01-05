var current_id = null;
 let modal = new bootstrap.Modal(
     document.querySelector("#json_modal", { focus: true })
 );
function setInputIdInRepeatable(input_id_to_get_val) {/***This set the select input values jisme kuch populate karna hai after modal close*** */
    console.log("current_id", current_id);
    let option_txt = $("#inp-" + input_id_to_get_val)
        .find(":selected")
        .text();
    let val = $("#inp-" + input_id_to_get_val).val();

    let first_select = $("#" + current_id)
        .find("select")
        .first();
    first_select.html(`<option selected value="${val}">${option_txt}</option>`);
   
    modal.hide();
    /***Add function like ajax to fetch data related to current set value */
}
/****Har row mein given button pe click karne pe popup ope karo json_modal givne in repeataable compoent */
function openJsonModal() {
    target = event.target;
    let c = $(target).closest(".copy_row");
    let id = c.attr("id");
    current_id = id;
   
    modal.show();
}
var repeatable_current_row = 1;
function addMoreRow() {
    repeatable_current_row++;
    let parent = $(event.target).closest(".repeatable");
    let options = {};
    if ($("#offcanvasEnd").find("form").length > 0)
        options["dropdownParent"] = $("#offcanvasEnd");
    if ($("#crud_modal").find("form").length > 0)
        options["dropdownParent"] = $("#crud_modal");
    let copy_content = parent.find(".copy_row")[0];
    let has_select = $(copy_content).find(".select2").length;
    if (has_select) {
        $(copy_content).find(".select2").remove();
    }
    let clone = $(copy_content).clone();
    clone.removeAttr("id");
    clone.attr("id", "copy-row-" + repeatable_current_row);
    clone.appendTo(parent);
    $("#copy-row-" + repeatable_current_row).find('select').each(function (i, obj) {
        if (!$(obj).data("select2")) {
            $(obj).select2(options);
        }
    });
   
    if ($(".datepicker").length > 0) {
        flatpickr(".datepicker");
    }
    
    
   
}
function removeRow() {
    repeatable_current_row--;
    let parent = $(event.target).closest(".repeatable");

    if (parent.find(".copy_row").length > 1)
        parent.children(".copy_row").last().remove();
}