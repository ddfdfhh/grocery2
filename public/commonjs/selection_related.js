function formatState(state) {
    let txt = state.text;
    let p = txt.split("(");

    if (p.length > 1) {
        p[1] = p[1].replace(")", "");

        let n = !Number.isInteger(p[1].trim(")")) ? parseInt(p[1]) : p[1];
        return $("<span>" + p[0] + "<strong>(" + n + ")</strong></span>");
    } else return state.text;
}

function applySelect2(elem, in_popup = true, container_id = null) {
    if (container_id) {
        let elems = $("#" + container_id).find(elem);
     let id=elems.attr('id');
    
        if (elems.length > 0) {
            elems.each(function () {
                if (!$(this).hasClass('no-select2')) {
                    let options = {
                        placeholder: "Select..",
                        templateResult: formatState,
                    };
                    if (in_popup) options["dropdownParent"] = $("#" + container_id);
                    let t = this;

                    let ajax_search = $(this).attr("data-ajax-search");
                    if ($(t).attr("multiple") !== undefined) {
                        options["tokenSeparators"] = [",", " "];
                    }

                    if (ajax_search !== undefined) {
                        options["ajax"] = {
                            delay: 250,

                            url: "/search_table",
                            dataType: "json",
                            data: function (params) {
                                let query = {
                                    search_by_column: $(t).data("search-by-column"),
                                    search_name_column:
                                        $(t).data("search-name-column"),
                                    search_id_column: $(t).data("search-id-column"),
                                    search_table: $(t).data("search-table"),
                                    value: params.term,
                                    whereIn: $(t).data("search-wherein")
                                   
                                };
                                if(query['whereIn']!=undefined){
                                    let cat=query['whereIn'];
                                 query['whereIn']=JSON.stringify({[cat]:$('#'+cat).val()});
                                }
                                console.log(query);
                                return query;
                            },
                            processResults: function (data) {
                                console.log("data", data);
                                return {
                                    results: data.message,
                                };
                            },
                        };
                    }

                    $(t).select2(options);
                }
            });
        }
    } else {
        if ($(elem).length > 0) {
            if ($(elem)[0].length > 0) {
                $(elem).each(function () {
                    if (!$(this).hasClass('no-select2')) {
                    let options = {
                        placeholder: "Select..",
                        templateResult: formatState,
                    };

                    let t = this;
                    let id = $(t).attr("id");
                    let ajax_search = $(this).attr("data-ajax-search");
                    if ($(t).attr("multiple") !== undefined) {
                        options["tokenSeparators"] = [",", " "];
                    }

                    if (ajax_search !== undefined) {
                        options["ajax"] = {
                            delay: 250,

                            url: "/search_table",
                            dataType: "json",
                            data: function (params) {
                                let query = {
                                    search_by_column:
                                        $(t).data("search-by-column"),
                                    search_name_column:
                                        $(t).data("search-name-column"),
                                    search_id_column:
                                        $(t).data("search-id-column"),
                                    search_table: $(t).data("search-table"),
                                    value: params.term,
                                    whereIn: $(t).data("search-wherein")
                                   
                                };
                                if(query['whereIn']!=undefined){
                                    let cat=query['whereIn'];
                                 query['whereIn']=JSON.stringify({[cat]:$('#'+cat).val()});
                                }
                                return query;
                            },
                            processResults: function (data) {
                                console.log("data", data);
                                return {
                                    results: data.message,
                                };
                            },
                        };
                    }
                    if (id != "multiselect" && id !== "customSort_to")
                        $(t).select2(options);
                }
                });
            }
        }
    }
}

function applySelect2ChangeEventPopulateOther(data = {}) {
    /**
     * data={
     * parent_id:
     * dependent_id:
     * dependee_key:
     * dependent_key
     * ependent_select_box_id:,
     * dependent_table:,
     * dependent_table_table_id,
     * callback:
     * }
     */
    if ($("#" + data["parent_id"]).length > 0) {
        $("#" + data["parent_id"]).on("change", function () {
            let val = $(this).val();

            if (Array.isArray(val) && val.length > 0) {
                /*to store json not with id but with name*/

                if (val[0].includes("-")) {
                    let spl = val[0].split("-");
                    if (
                        spl.length == 2 &&
                        Number.isInteger(spl[0]) &&
                        !Number.isInteger(spl[1])
                    ) {
                        val = val.map(function (v) {
                            return v.split("-")[0];
                        });
                    }
                }
                showDependentSelectBoxForMultiSelect(
                    data["dependee_key"],
                    data["dependent_key"],
                    val,
                    data["dependent_id"],
                    data["dependent_table"],
                    data["dependent_table_table_id"],
                    data["callback"]
                );
            } else showDependentSelectBox(data["dependee_key"], data["dependent_key"], val, data["dependent_id"], data["dependent_table"], data["dependent_table_table_id"], data["callback"]);
        });
    }
}
function initiateSelect2ChangeEvents(in_popup = true, container_id = null) {
    let data_state = {
        parent_id: "inp-state_id",
        dependent_id: "inp-city_id",
        dependee_key: "state_id" /***in child table column for parent */,
        dependent_key:
            "name" /***in child table column name for childin option name */,
        dependent_select_box_id: "inp-city_id",
        dependent_table: "city",
        dependent_table_table_id: "id",
        callback: function () {
            // applySelect2("#inp-city", in_popup, container_id);
        },
    };
    applySelect2ChangeEventPopulateOther(data_state);
    let data_country = {
        parent_id: "inp-country",
        dependent_id: "inp-state",
        dependee_key: "country" /***in child table column for parent */,
        dependent_key:
            "name" /***in child table column name for childin option name */,
        dependent_select_box_id: "inp-state_id",
        dependent_table: "state_id",
        dependent_table_table_id: "id",
        callback: function () {
            applySelect2("#inp-state_id", in_popup, container_id);
        },
    };
    applySelect2ChangeEventPopulateOther(data_country);
}
