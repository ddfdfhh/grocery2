$(document).ready(function () {
$("radio1").on('change',function(){
    $("#").toggle()
    
})


});
function addPlus(containerid){
    copyContent=$('#'+containerid+' .copy_row').last().clone();
    console.log(copyContent)
    $("#"+containerid).append(copyContent);
 

}
function addPlusValidation(containerid){
    copyContent=$('#repeatable_validation .copy_row').last().clone();
    console.log(copyContent)
    $("#repeatable_validation").append(copyContent);

}
function removeMinusValidation(containerid){
    if($("#repeatable_validation .copy_row").length>1)
    $("#repeatable_validation .copy_row").last().remove();

}
function addPlusRepeatable(){
    target=event.target
    copyContent=$(target).closest('.repeatable3').last('.copy_row1').clone();
    
    $(target).closest('fieldset').append(copyContent);

}
function addPlusRepeatableOuter(){
   
        copyContent=$('#repeatable_outer .copy_row').last().clone();
        console.log(copyContent)
        $("#repeatable_outer").append(copyContent);
    
}
    function removeMinusRepeatableOuter(){
        if($("#repeatable_outer .copy_row").length>1)
        $("#repeatable_outer .copy_row").last().remove();
    
    }
function addPlusFile(){
   
        copyContent=$('#file_group .copy_row').last().clone();
        console.log(copyContent)
        $("#file_group").append(copyContent);
    
}
    function removeMinusFile(){
        if($("#file_group .copy_row").length>1)
        $("#file_group .copy_row").last().remove();
    
    }
function addPlusToggableOuter(){
   
        copyContent=$('#toggable_group .copy_row3').last().clone();
        console.log(copyContent)
        $("#toggable_group").append(copyContent);
    
}
    function removeMinusToggableOuter(){
        if($("#toggable_group .copy_row3").length>1)
        $("#toggable_group .copy_row3").last().remove();
    
    }
function addPlusRepeatableCreateNested(){
  
        copyContent = $(event.target)
            .closest("#nested_create")
            .find(".copy_row2")
            .last()
            .clone();
        
        $(event.target).closest(".repeatable").append(copyContent);
    
}
    function removeMinusRepeatableCreateNested(){
        if($(event.target).closest('.repeatable').find('.copy_row2').length>1)
        $(event.target)
            .closest(".repeatable")
            .find(".copy_row2")
            .last()
            .remove();
    
    }
function addPlusRepeatableSideColumnNested(){
  
        copyContent=$(event.target).closest('.repeatable').find('.copy_row2').last().clone();
        
        $(event.target).closest(".repeatable").append(copyContent);
    
}
    function removeMinusRepeatableSideColumnNested() {
        if (
            $(event.target).closest(".repeatable").find(".copy_row2").length > 1
        )
            $(event.target)
                .closest(".repeatable")
                .find(".copy_row2")
                .last()
                .remove();
    }
function addPlusToggleNested(){
   
        
     copyContent = $(event.target)
         .closest(".toggable_group")
         .find(".copy_row1")
         .last()
         .clone();

     $(event.target).closest(".toggable_group").append(copyContent);
    
    
}
    function removeMinusToggleNested(){
       if (
           $(event.target).closest(".toggable_group").find(".copy_row1")
               .length > 1
       )
           $(event.target)
               .closest(".toggable_group")
               .find(".copy_row1")
               .last()
               .remove();
    
    }
function addPlusIndexPage(){
   
        copyContent=$('#index_page_column_group .copy_row').last().clone();
        
        $("#index_page_column_group").append(copyContent);
    
}
    function removeMinusIndexPage(){
        if($("#index_page_column_group .copy_row").length>1)
        $("#index_page_column_group .copy_row1").last().remove();
    
    }
var cr=0;
function addPlusTableCreate(){
   cr+=1;
        copyContent=$('#f_container .copy_row').last().clone();
        $(copyContent).children().each(function(){
            $(this).find('select').each(function(){
               let attr=$(this).attr('name')
               attr=attr.split('__')[0];
               $(this).attr('name',attr+'__'+cr);
               
            })
            $(this).find('input').each(function(){
                let attr=$(this).attr('name')
                console.log(attr)
                if(attr.includes('contraints')){
                   attr=attr.replace('[]','').trim();
                   attr=attr.split('__')[0];
                   $(this).attr('name',attr+'__'+cr+'[]');
                }
                else{
                   attr=attr.split('__')[0];
                    $(this).attr('name',attr+'__'+cr);
                }
                
             })
            

            
        })
     let has_select = $(copyContent).find(".select2").length;
     if (has_select) {
         $(copyContent).find(".select2").remove();
     }
    $("#f_container").append(copyContent);
    $("select").each(function (i, obj) {
        if (!$(obj).data("select2")) {
            $(obj).select2();
        }
    });
    
}
    function removeMinusTableCreate(){
        cr--;
        if($("#f_container .copy_row").length>1)
        $("#f_container .copy_row").last().remove();
    
    }
function removeMinus(containerid){
   
  
    if($("#"+containerid+" .copy_row").length>1)
    $("#"+containerid+" .copy_row").last().remove();

}
function addPlusRepeatableCreate(){
   cr+=1;
        copyContent=$('#repeatable_create .copy_row').first().clone();
        $(copyContent).children().each(function(){
            $(this).find('select').each(function(){
               let attr=$(this).attr('name')
               console.log(attr)
               if(attr){
               attr=attr.replace('[]','').trim()
               $(this).attr('name',attr+'_'+cr+'[]');
               }
            })
            $(this).find('input').each(function(){
                let attr=$(this).attr('name')
                console.log(attr)
                if(attr){
                attr=attr.replace('[]','').trim()
                $(this).attr('name',attr+'_'+cr+'[]');
                }
             })
             $(this).find('textarea').each(function(){
                let attr=$(this).attr('name')
                console.log(attr)
                if(attr){
                attr=attr.replace('[]','').trim()
                $(this).attr('name',attr+'_'+cr+'[]');
                }
             })

            
        })
        $("#repeatable_create").append(copyContent);
    
}
    function removeMinusRepeatableCreate(){
        if($("#repeatable_create .copy_row").length>1)
        $("#repeatable_create .copy_row").last().remove();
    
}
cr = 0;
function addPlusRepeatableSideColumnOuter() {
    cr += 1;
    copyContent = $("#repeatable_side_column .copy_row").last().clone();
    $(copyContent)
        .children()
        .each(function () {
            $(this)
                .find("select")
                .each(function () {
                    let attr = $(this).attr("name");
                    console.log(attr);
                    if (attr) {
                        attr = attr.replace("[]", "").trim();
                        $(this).attr("name", attr + "_" + cr + "[]");
                    }
                });
            $(this)
                .find("input")
                .each(function () {
                    let attr = $(this).attr("name");
                    console.log(attr);
                    if (attr) {
                        attr = attr.replace("[]", "").trim();
                        $(this).attr("name", attr + "_" + cr + "[]");
                    }
                });
            $(this)
                .find("textarea")
                .each(function () {
                    let attr = $(this).attr("name");
                    console.log(attr);
                    if (attr) {
                        attr = attr.replace("[]", "").trim();
                        $(this).attr("name", attr + "_" + cr + "[]");
                    }
                });
        });
    $("#repeatable_side_column").append(copyContent);
}
    function removeMinusRepeatableSideColumnOuter() {
        if ($("#repeatable_side_column .copy_row").length > 1)
            $("#repeatable_side_column .copy_row").last().remove();
    }


function removeMinusRepeatable(){
    target = event.target;
    let l = $(target).closest('fieldset').find('.repeatable3').length
    console.log('lll',l);
    if(l>1)
       $(target).closest('.repeatable3').remove();

}
function showExportableFields(value,field_name,containerid){
    if(value=='Yes'){
    $('#exportable_div').show();
    getColumnsOfTableCheckboxFormate(field_name,containerid);
    }
    else {
    $('#exportable_div').hide();
  
    }
}
function showHideImageGroup(value){
    if(value=='Yes'){
    $('#file_grop_container').show();
   
    }
    else {
    $('#file_grop_container').hide();
  
    }
}
function showHideSideColumnGroup(value){
    if(value=='Yes'){
    $('#side_column_container').show();
   
    }
    else {
    $("#side_column_container").hide();
  
    }
}
function showHideFields(value){
    let target=event.target
    if(value=='Multiple'){
       $(target).closest('.copy_row').find('.file_cols_div').hide();
       $(target).closest('.copy_row').find('.file_field_input').show();
       
       
    }
    else {
        $(target).closest('.copy_row').find('.file_field_input').hide();
        $(target).closest('.copy_row').find('.file_cols_div').show();
       
    
       
  
    }
}
function showOptionInput(field_name,val,option_input_id){
    if(val=='select'){
      $('#'+option_input_id).show();
      $('#'+option_input_id).attr('name',field_name+'_options[]');
   }
    else {
        $('#'+option_input_id).hide();
  
    }
}
function showRepeatingDiv(value,containerid){
    if(value=='Yes'){
    $('#'+containerid).show();
    getColumnsOfTable('repeatable_cols[]','repeatable_cols_div','radio','getRepeatableHtml(this.value)');
    }
    else {
        $('#'+containerid).hide();
  
    }
}
function getColumnsOfTable(field_name,containerid,type='radio',event=null){
    table=$('#table').val();
    objectAjaxNoLoaderNoAlert(
        {table,field_name,type,event},
        `/getTableColumn`,
        htmlLoadcallback=function(res){
           
            $("#"+containerid).html(res['message'])
             $("select#view_page_columns").select2();
        });
}
function getColumnsOfTableCheckboxFormate(field_name,containerid,event=null){
    table=$('#table').val();
    objectAjaxNoLoaderNoAlert(
        {table,field_name,event},
        `/getTableColumnCheckboxForm`,
        htmlLoadcallback=function(res){
           
            $("#"+containerid).html(res['message'])
         
        });
}
function getColumnsOfTableCheckboxFormate1(field_name,containerid,event=null){
    table=$('#table').val();
    objectAjaxNoLoaderNoAlert(
        {table,field_name,event},
        `/getTableColumnCheckboxForm`,
        htmlLoadcallback=function(res){
            console.log(event.target)
            $(event.target).closest('.copy_row').find('.f_cols').html(res['message'])
          //  $('select').select2();
        });
}
function getValidationHtml(val){
   var self=event.target
  
    objectAjaxNoLoaderNoAlert(
        {field_name:val},
        `/getValidationHtml`,
        htmlLoadcallback=function(res){
           
           $(self).closest('.copy_row').find('.rules').html(res['message'])
        });
}
function getRepeatableHtml(val){
   var self=event.target
   
    objectAjaxNoLoaderNoAlert(
        {field_name:val},
        `/getRepeatableHtml`,
        htmlLoadcallback=function(res){
          
            $(self).closest('.copy_row').find('.inputs').html(res['message']['html'])
            $(self).closest('.copy_row').find('.repeatable_label').html(res['message']['label'])
          
        });
}
function showSideColumnHtml(val) {
    var self = event.target;

    objectAjaxNoLoaderNoAlert(
        { field_name: val },
        `/getSideColumnFormHtml`,
        (htmlLoadcallback = function (res) {
            $(self)
                .closest(".copy_row")
                .find(".inputs")
                .html(res["message"]["html"]);
            $(self)
                .closest(".copy_row")
                .find(".side_column_label")
                .html(res["message"]["label"]);
          
        })
    );
}
function getToggableGroupHtml(val){
   var self=event.target
   
    objectAjaxNoLoaderNoAlert(
        {field_name:val},
        `/getToggableGroupHtml`,
        htmlLoadcallback=function(res){
          
            $(self).closest('.copy_row3').find('.toggable_inputs').html(res['message']['html'])
            $(self).closest('.copy_row3').find('.toggable_value').html(res['message']['label'])
        });
}
function getCreateInputOptionHtml(val){
   var self=event.target
   
    objectAjaxNoLoaderNoAlert(
        {field_name:val,cur_index:cr},
        `/getCreateInputOptionHtml`,
        htmlLoadcallback=function(res){
          
            $(self).closest('.copy_row2').find('.create_inputs').html(res['message']['html'])
            $(self).closest('.copy_row2').find('.create_label').html(res['message']['label'])
        });
}
function getSideColumnInputOptionHtml(val){
   var self=event.target
   
    objectAjaxNoLoaderNoAlert(
        {field_name:val,cur_index:cr},
        `/getSideColumnInputOptionHtml`,
        htmlLoadcallback=function(res){
          
            $(self).closest('.copy_row2').find('.side_column_inputs').html(res['message']['html'])
            $(self)
                .closest(".copy_row2")
                .find(".side_column_label")
                .html(res["message"]["label"]);
        });
}
function setVal(val){
    table=val
    spl=val.split('_');
    let g='';
    spl.forEach(function(val1){
        g+=capitalizeFirstLetter(val1)
    });
    $("#module").val(g)
    $("#plural").val(table)
    getColumnsOfTable("view_page_columns", "view_page_columns", "checkbox", "");
    getColumnsOfTable(
        "validation_fields[]",
        "validation_fields",
        "radio",
        "getValidationHtml(this.value)"
    );
    getColumnsOfTable('create_fields','create_cols_div','radio','getCreateInputOptionHtml(this.value)');
    getColumnsOfTable(
        "side_column_fields",
        "side_column_div",
        "radio",
        "getSideColumnInputOptionHtml (this.value)"
    );
    getColumnsOfTable('toggalbe_fields','toggable_cols_div','radio','getToggableGroupHtml(this.value)');
    getColumnsOfTable('image_col_name','file_cols_div','radio','');
    getColumnsOfTable('index_page_cols','index_page_cols_div','radio','setLabelNameInIndexSetting(this.value)');
    getColumnsOfTableCheckboxFormate('filterable_fields','filterable_cols_div');
    getColumnsOfTableCheckboxFormate('searchable_fields','searchable_cols_div');
    getColumnsOfTableCheckboxFormate('searchable_fields','searchable_cols_div');
}
function fetchColumns(val){
    table=val
   
    getColumnsOfTableCheckboxFormate1('searchable_fields','searchable_cols_div');
}
function setLabelNameInIndexSetting(val){
    spl=val.split('_');
    let g='';
    spl.forEach(function(val1){
        g+=capitalizeFirstLetter(val1)
    });
    $(event.target).closest('.copy_row').find('.index_label').val(g);
}
function capitalizeFirstLetter(str) {
    if(str)
    return str[0].toUpperCase() + str.slice(1);
  }