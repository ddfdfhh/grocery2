var loaderRef = $("#loader"); /**Id of icon div embeded in submit buttons  */
$.validator.addMethod("pwcheck", function (value) {
    return (
        /^[A-Za-z0-9\d=#~!\-@._*]*$/.test(value) && // consists of only these
        /[a-z]/.test(value) &&
        /[A-Z]/.test(value) && // has a lowercase letter
        /\d/.test(value)
    ); // has a digit
});
$.validator.addMethod("phone", function (value) {
    return /^\d{10,}$/.test(value);
});
$.validator.addMethod("zip", function (value) {
    return /^\d{4,6}$/.test(value);
});
/**Change form validation default error messages  */
$.extend($.validator.messages, {
    required: "This field is required.",
    remote: "Please fix this field.",
    email: "Please enter a valid email address.",
    url: "Please enter a valid URL.",
    date: "Please enter a valid date.",
    dateISO: "Please enter a valid date (ISO).",
    number: "Please enter a valid number.",
    digits: "Please enter only digits.",
    equalTo: "Please enter the same password again.",
    maxlength: $.validator.format("Please enter no more than {0} characters."),
    minlength: $.validator.format("Please enter at least {0} characters."),
    rangelength: $.validator.format(
        "Please enter a value between {0} and {1} characters long."
    ),
    range: $.validator.format("Please enter a value between {0} and {1}."),
    max: $.validator.format("Please enter a value less than or equal to {0}."),
    min: $.validator.format(
        "Please enter a value greater than or equal to {0}."
    ),
    step: $.validator.format("Please enter a multiple of {0}."),
});
function blockUi() {
    let loading_msg = "";
    let options = {
        showOverlay: true,
        overlayCSS: {
            backgroundColor: "#000",
            opacity: 0.6,
        },
        css: {
            padding: 0,
            margin: 0,
            width: "30%",
            top: "40%",
            left: "35%",
            textAlign: "center",
            color: "#000",
            border: "none",
            cursor:'pointer',
            backgroundColor: "transparent",
        },
        message: "Please wait ..",
    };
    if (typeof $.blockUI !== "undefined") $.blockUI(options);
}
function unBlockUi() {
    if (typeof $.unblockUI !== "undefined") $.unblockUI();
}

function successAlert(msg) {
    Swal.fire({
        icon: "success",
        title: "Success",
        text: msg,
    });
    // iziToast.success({
    //   title: 'Great!',
    //   message: msg,
    //   position: 'topRight'
    // });
}

function errorAlert(error = "") {
    Swal.fire({
        icon: "error",
        title: "",
        html: error.length > 0 ? error : "Something went wrong!",
    });
    // iziToast.error({
    //   title: 'Oops!',
    //   message:(error.length>0)?error:'Something went wrong!',
    //   position: 'topRight'
    // });
}
function disableBtn(btn) {
    if (btn !== undefined) {
        btn.prop("disabled", true);
        btn.css("opacity", "0.7");
    }
    // loaderRef.css('display','inline-block');
    blockUi();
}
function enableBtn(btn) {
    if (btn !== undefined) {
        btn.css("opacity", "1");
        btn.prop("disabled", false);
    }
    // loaderRef.css('display','none');
    if (typeof $.unblockUI !== "undefined") $.unblockUI();
}
function formatErrorMessage(
    jqXHR,
    exception,
    show_server_validation_in_alert = true
) {
    let msg = "";
    if (jqXHR.status === 0) {
        msg = "Not connected.\nPlease verify your network connection.";
    } else if (jqXHR.status == 404) {
        msg = "The requested page not found";
    } else if (jqXHR.status == 500) {
         msg = jqXHR.responseJSON!==undefined? jqXHR.responseJSON.message.substr(20,30)+'....':'Internal Server error ocurred';
    } else if (jqXHR.status == 403) {
        msg = "Please refresh page.";
    } else if (jqXHR.status == 500) {
        msg = "Internal Server Error.";
    } else if (exception === "parsererror") {
        msg = "Requested JSON parse failed.";
    } else if (exception === "timeout") {
        msg = "Time out error.";
    } else if (exception === "abort") {
        msg = "Request aborted.";
    } else {
        msg = "Uncaught Error.\n" + jqXHR.responseText;
    }
    if (show_server_validation_in_alert) errorAlert(msg);
    else {
        $("#validation_errors").html("");
        $("#validation_errors").html(
            '<div class="alert alert-danger">' + msg + "</div>"
        );
    }
}
function showValidationErrorsNoAlert(error) {
    $("#validation_errors").html("");

    $("#validation_errors").append(
        '<div class="alert alert-danger">' + error + "</div"
    );
}
function handleFormSubmitError(
    xhr,
    status,
    errorThrown,
    show_server_validation_in_alert = true
) {
    console.log(xhr);
    if (xhr.responseJSON !== undefined && xhr.status!==500) {
        if (xhr.responseJSON.errors !== undefined) {
            var errorString = "";
            let i = 1;
            $.each(xhr.responseJSON.errors, function (key, value) {
                errorString += "<p>" + value + "</p>";
            });
            if (show_server_validation_in_alert) errorAlert(errorString);
            else {
                $("#validation_errors").html("");

                $("#validation_errors").append(
                    '<div class="alert alert-danger">' + errorString + "</div"
                );
            }
        } else if (xhr.responseJSON.message !== undefined) {
           
            if (show_server_validation_in_alert) errorAlert(xhr.responseJSON.message);
            else {
                $("#validation_errors").html("");

                $("#validation_errors").append(
                    '<div class="alert alert-danger">' +
                        xhr.responseJSON.message +
                        "</div"
                );
            }
        }
    } else {
        formatErrorMessage(xhr, errorThrown, show_server_validation_in_alert);
    }
}
function handleFormSubmitSuccess(
    formid = undefined,
    res,
    xhr,
    callbackSuccess = undefined,
    callbackError = undefined,
    show_server_validation_in_alert = true
) {
    /* if (res["success"] || xhr.status === 200 || xhr.status === 201) {
               
                $("#" + formid).trigger("reset");
                successAlert(res["message"]);
                if (callbackSuccess) callbackSuccess(res);
            } else {
                loaderRef.hide();
                // $('.alert-danger').show();
                // $('.alert-danger').html(res['message']);
                errorAlert(res["message"]);
                if (callbackError) callbackError(res);
            }*/
    if (res["success"]) {
        if (formid !== undefined) $("#" + formid).trigger("reset");
        if (show_server_validation_in_alert) successAlert(res["message"]);

        if (callbackSuccess) callbackSuccess(res);
    } else {
        loaderRef.hide();
        if (show_server_validation_in_alert) errorAlert(res["message"]);
        else showValidationErrorsNoAlert(res["message"]);
        if (callbackError) callbackError(res);
    }
}
/***==================================================Form validation template ========================================= */
//Without image
function formValidateFunctionTemplate(
    rules,
    messages = {},
    btnid,
    formid,
    url,
    callbackSuccess = undefined,
    callbackError = undefined,
    has_image = false,
    show_server_validation_in_alert = true
) {
    $("#" + formid).validate({
        errorClass: "invalid-feedback",
        // errorElement: "div",
        errorPlacement: function (error, element) {
            if (element.attr("type") == "radio") {
                error.insertAfter($("#position_around"));
            } else {
                $(element).closest(".form-group").append(error);
            }
        },
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },

        rules,
        messages,
        focusCleanup: true,
        submitHandler: function (form, event) {
            event.preventDefault();
            formid = $(form).attr("id");

            if (!has_image) {
                formAjaxSubmitWithServerValidationError(
                    "Submit",
                    btnid,
                    formid,
                    url,
                    callbackSuccess,
                    callbackError,
                    show_server_validation_in_alert
                );
            } else {
                formAjaxSubmitWithImageWithServerValidationError(
                    "Submit",
                    btnid,
                    formid,
                    url,
                    callbackSuccess,
                    callbackError,
                    show_server_validation_in_alert
                );
            }
        },
    });
}

/*********==================================Template  ends ========================================= */
/**==============================================Form Ajax Submission With server validation Only  ============= */
//Without Image
function formAjaxSubmitWithServerValidationError(
    btnText,
    btnid,
    formid,
    url,
    callbackSuccess = undefined,
    callbackError = undefined,
    show_server_validation_in_alert = true
) {
    let btn = $("#" + btnid);
    let formData = $("#" + formid).serialize();

    $.ajax({
        url: url,
        method: "POST",
        dataType: "json",
        data: formData,

        beforeSend: function () {
            btn.html("Please wait..");
            disableBtn(btn);
        },
        success: function (res, textStatus, xhr) {
            enableBtn(btn);

            handleFormSubmitSuccess(
                formid,
                res,
                xhr,
                callbackSuccess,
                callbackError,
                show_server_validation_in_alert
            );
        },
        complete: function () {
            enableBtn(btn);
        },

        error: function (xhr, status, errorThrown) {
            enableBtn(btn);
            handleFormSubmitError(
                xhr,
                status,
                errorThrown,
                show_server_validation_in_alert
            );
        },
    });
}
//With Image
function formAjaxSubmitWithImageWithServerValidationError(
    btnText,
    btnid,
    formid,
    url,
    callbackSuccess = undefined,
    callbackError = undefined,
    show_server_validation_in_alert = true
) {
    let btn = $("#" + btnid);
    let formData = new FormData(document.getElementById(formid));
    // formData.append('file', $('#image')[0].files[0]);

    $.ajax({
        url: url,
        method: "POST",
        dataType: "json",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,

        beforeSend: function () {
            disableBtn(btn);
        },
        success: function (res, textStatus, xhr) {
            enableBtn(btn);
            handleFormSubmitSuccess(
                formid,
                res,
                xhr,
                callbackSuccess,
                callbackError,
                show_server_validation_in_alert
            );
        },
        complete: function () {
            enableBtn(btn);
        },
        error: function (xhr, status, errorThrown) {
            enableBtn(btn);
            handleFormSubmitError(
                xhr,
                status,
                errorThrown,
                show_server_validation_in_alert
            );
        },
    });
}

/**==============Objec Data  Ajax send with loading icon on button  =================================*/
function objectAjaxWithBtnAndLoader(
    btnid = undefined,
    object,
    url,
    callbackSuccess = undefined,
    callbackError = undefined,
    show_server_validation_in_alert = true
) {
    let btn = $("#" + btnid);

    let formData = object;
    $.ajax({
        url: url,
        method: "POST",
        dataType: "json",
        data: formData,

        beforeSend: function () {
            disableBtn(btn);
        },
        success: function (res, textStatus, xhr) {
            enableBtn(btn);
            handleFormSubmitSuccess(
                undefined,
                res,
                xhr,
                callbackSuccess,
                callbackError,
                show_server_validation_in_alert
            );
        },
        complete: function () {
            enableBtn(btn);
        },
        error: function (xhr, status, errorThrown) {
            enableBtn(btn);
            handleFormSubmitError(
                xhr,
                errorThrown,
                show_server_validation_in_alert
            );
        },
    });
}
/**==============Objec Data  Ajax send no loading icon no alert  =================================*/
function objectAjaxNoLoaderNoAlert(
    object,
    url,
    callbackSuccess = undefined,
    callbackError = undefined,
    method = "POST",
    show_error_in_alert = true
) {
    let formData = object;
    $.ajax({
        url: url,
        method,
        dataType: "json",
        data: formData,

        success: function (res, textStatus, xhr) {
            if (res["success"]) {
                if (callbackSuccess) callbackSuccess(res);
            } else {
                if (callbackError) callbackError(res["message"]);
            }
        },

        error: function (xhr, status, errorThrown) {
            handleFormSubmitError(xhr, errorThrown, show_error_in_alert);
        },
    });
}
