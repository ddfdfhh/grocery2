function fetchAttributeFamilyAttributes(id) {
    obj = {
        id,
    };
    $("#repeatable_container_existing").empty();
   
    let callbackSuccess = function (res) {
        $("#repeatable_container_existing").html(res["message"]);
        initTaggedInput();
    };
    objectAjaxNoLoaderNoAlert(obj, "/admin/getAttributesHtml", callbackSuccess);
   
}
function toggleTwoContainerAndFetch(cur_value, div_id, onVal,other_div_id) {
  
    let div = $("#" + div_id)
    let other_div = $("#" + other_div_id)
    cur_value=cur_value.trim() ,
    onVal= onVal.trim()

    if (cur_value == onVal) {
       
        $(div).show();
        $(other_div).css('display','none');
        
    } else {
        $(div).css('display','none');
        $(other_div).show();
    }
   
   
}

function dynamicAddRemoveRowSimple(
    todo,container_id
) {
    let container = $("#"+container_id);
    if (todo == 'add') {
        let content_to_copy = $("#copy").html();
        
        let row_list = container.find(".row");
        let count = parseInt(row_list.length)
        content_to_copy=content_to_copy.replace("xattribute",'attribute-'+count);
        container.append(content_to_copy);
       
        let last_row = container.find('.row').last();
        last_row.attr('id', 'row-' + (count+1));
        
       

    }
    else {
        if (container.children().length > 1) {
            container.children().last().remove();
            generateVariant();
        }
    }

}