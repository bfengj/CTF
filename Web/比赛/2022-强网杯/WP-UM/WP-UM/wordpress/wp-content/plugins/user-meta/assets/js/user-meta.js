(function($){
    
    var userMeta = userMeta || {};
    
    userMeta.common = {
        init: function() {}
    }
    
    userMeta.front = {
        
        init: function() {
            $('.um_generated_form').each(function(){
                userMeta.front.initForm( this );
            });
        },
        
        initForm: function( editor ) {
            this.editor = $(editor);
            this.load();
            this.events();
        },
        
        load: function() {
            $('.um_user_form').validationEngine();
            $('.um_rich_text').wysiwyg({initialContent:' '});
            $('input, textarea').placeholder();
            umFileUploader();
        },
         
        events: function() {
            this.editor.on('change', '.um_parent', this.chekConditions);
        },
        
        chekConditions: function() {
            var editor = userMeta.front.editor;
            
            editor.find('script[type="text/json"].um_condition').each(function() {
                try {
                    var condition = JSON.parse($(this).text());            
                    var evals = [];
                    
                    $.each( condition.rules, function() {
                    	
                        var target = [];
                        $.each(editor.find('.um_field_' + this.field_id), function() {
                        	var parentField = $(this)
                        	var tagName = parentField.prop("tagName").toLowerCase();
                            if (tagName == 'input' && $.inArray(parentField.attr('type'), ['checkbox', 'radio']) > -1) {
                                if (parentField.is(":checked")) {
                                    target.push(parentField.val());
                                }
                            } else if (tagName == 'select' && parentField.attr('multiple')) {
                            	target = parentField.val();           
                            } else {
                            	target.push(parentField.val());	
                            }
                        });
 
                        switch ( this.condition ) {
                            case 'is' :
                            	evals.push($.inArray(this.value, target) > -1 ? true : false);
                            break;

                            case 'is_not' :
                            	evals.push($.inArray(this.value, target) > -1 ? true : false);
                            break;
                        }
                    })
                    
                    var result = evals[0];
                    
                    if ( evals.length > 1 ) {
                        for ( var i = 1; i < evals.length; i++ ) {
                            if ( 'and' == condition.relation ) {
                                result = result && evals[ i ];
                            } else {
                                result = result || evals[ i ];
                            }
                        }
                    }

                    if ( ( ( 'show' == condition.visibility ) && ! result ) || ( ( 'hide' == condition.visibility ) && result ) ) {
                        $(this).closest('.um_field_container').hide('slow');
                    } else {
                        $(this).closest('.um_field_container').show('slow');
                    }
                    
                } catch (err) {} 
            });
        }
    };
    
    $(function() {
        userMeta.common.init();
        userMeta.front.init();
    });

    $('.um_generated_form :first *:input:enabled:visible:first').focus();

})(jQuery);

var umAjaxRequest;

function pfAjaxCall(element, action, arg, handle) {    
    if ( typeof arg == 'object' ) {
        data = arg;
        if (action) data.action = action;
        //if (!data.pf_nonce) data.pf_nonce = pf_nonce;
    } else if ( typeof arg == 'string' ) {
        if (action) data = "action=" + action;
        if (arg)    data = arg + "&action=" + action;
        if (arg && !action) data = arg;

        var n = data.search("pf_nonce");
        if (n<0) {
            data = data + "&pf_nonce=" + pf_nonce;
        }
        data = data + "&is_ajax=true";
    }
    
    //if( typeof(ajaxurl) == 'undefined' ) ajaxurl = front.ajaxurl;

    umAjaxRequest = jQuery.ajax({
    type: "post",
    url: ajaxurl,
    data: data,
        beforeSend: function() { jQuery("<span class='pf_loading'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>").insertAfter(element); },
        success: function( data ){
            jQuery(".pf_loading").remove();
            handle(data);
        }
    });    
}

function pfAjaxRequest(element) {  
    bindElement = jQuery(element);
    bindElement.parent().children(".pf_ajax_result").remove();
    arg = jQuery( element ).serialize();
    pfAjaxCall( bindElement, 'pf_ajax_request', arg, function(data) {
        bindElement.after("<div class='pf_ajax_result'>"+data+"</div>");        
    });    
     
}

function umInsertUser(element) {
    if (typeof(tinyMCE) !== 'undefined') tinyMCE.triggerSave();
    if (!jQuery(element).validationEngine("validate")) return;
    
    bindElement = jQuery(element);
    bindElement.children(".pf_ajax_result").remove();
    arg = jQuery( element ).serialize();
    pfAjaxCall( bindElement, 'pf_ajax_request', arg, function(data) {
        if (jQuery(data).attr('action_type') == 'registration')
            jQuery(element).replaceWith(data);
        else
            bindElement.append("<div class='pf_ajax_result'>"+data+"</div>");        
    });    
}

function umLogin(element) {
    //if( !jQuery(element).validationEngine("validate") ) return;
        
    arg = jQuery( element ).serialize();
    bindElement = jQuery(element);
    bindElement.children(".pf_ajax_result").remove();
    pfAjaxCall(bindElement, 'pf_ajax_request', arg, function(data) {
        if ( jQuery(data).attr('status') == 'success' ) {
            //jQuery(element).replaceWith(data); //Commented from 1.1.5rc2, not showing anything while redirecting
            redirection = jQuery(data).attr('redirect_to');
            if ( redirection )
                window.location.href = redirection;
        }
        else
            bindElement.append("<div class='pf_ajax_result'>"+data+"</div>");
    });      
}

function umLogout(element) {
    arg = 'action_type=logout';
    
    pfAjaxCall(element, 'um_login', arg, function(data) {
        //alert(data);
        //jQuery("#" + jQuery() )
        jQuery(element).after(data);
        //jQuery(element).parents(".error").remove();    
    });    
}

function umPageNavi(pageID, isNext, element) {
    var hasError = false;
    var hasHtml5Error = false;
    
    if (typeof element == "object")
        formID = "#" + jQuery(element).parents("form").attr("id");
    else
        formID = "#" + element;

    if (isNext) {
        checkingPage = parseInt(pageID) - 1;
        
        jQuery(formID + " #um_page_segment_" + checkingPage + " .um_input").each(function() {
            fieldID = jQuery(this).attr("id");  
            
            if (fieldID !== undefined) {  
                validateHtml5 = jQuery("#" + fieldID)[0].checkValidity();  
                if (! validateHtml5) {
                    hasHtml5Error = true;
                    hasError = true;  
                }
                
                error = ! jQuery("#" + fieldID).validationEngine("validate");      
                if (error) hasError = true;  
            }
        });
        
        if (hasHtml5Error) {
            jQuery(formID).find(":submit").click();
        }            
    } else
        jQuery(formID).validationEngine("hide");
    
    if (hasError) return false;
    
    jQuery(formID).children(".um_page_segment").hide();
    jQuery(formID).children("#um_page_segment_" + pageID).fadeIn("slow");
    jQuery("html, body").animate({
        scrollTop: jQuery("#um_page_segment_" + pageID).offset().top
    });
}

function umFileUploader() {
    jQuery(".um_file_uploader_field").each(function(index) {

        var divID = jQuery(this).attr("id");
        var fieldID = jQuery(this).attr("um_field_id");
        var formKey = jQuery(this).attr("um_form_key") || "";
        
        allowedExtensions = jQuery(this).attr("extension");
        allowedExtensions = allowedExtensions.replace(/\s+/g,"");
        maxSize = jQuery(this).attr("maxsize")
        if ( !allowedExtensions )
            allowedExtensions = "jpg,jpeg,png,gif";
        if ( !maxSize )
            maxSize = 1 * 1024 * 1024;

        var uploader = new qq.FileUploader({
            // pass the dom node (ex. $(selector)[0] for jQuery users)
            element: document.getElementById(divID),
            // path to server-side upload script
            action: ajaxurl,
            params: {"action":"um_file_uploader", "field_name":jQuery(this).attr("name"), field_id:fieldID, form_key:formKey, "pf_nonce":pf_nonce },
            allowedExtensions: allowedExtensions.split(","),
            sizeLimit: maxSize,
            onComplete: function(id, fileName, responseJSON){
                if( !responseJSON.success ) return;
                
                // responseJSON comes from uploader.php return
                handle = jQuery('#'+fieldID);
                arg = 'field_name=' + responseJSON.field_name + '&filepath=' + responseJSON.filepath + '&field_id=' + fieldID + '&form_key=' + formKey;

                // Check if it is used by User Import Upload
                if ( responseJSON.field_name == 'txt_upload_ump_import' ) {
                    arg = arg + '&method_name=ImportUmp';
                    pfAjaxCall( handle, 'pf_ajax_request', arg, function(data){
                        jQuery('#'+fieldID+'_result').empty().append( data );      
                    });                                     
                } else if ( responseJSON.field_name == 'csv_upload_user_import' ) {
                    arg = arg + '&step=one';                   
                    pfAjaxCall( handle, 'um_user_import', arg, function(data){
                    	jQuery("#csv_upload_user_import_result").html(data);  
                        //jQuery(handle).parents(".meta-box-sortables").replaceWith(data);    
                    });                    
                } else {
                    pfAjaxCall( handle, 'um_show_uploaded_file', arg, function(data) {
                        jQuery('#'+divID+'_result').empty().append( data );       
                    });                    
                }
            }
        });         
    });
}

function umShowImage(element) {
    url = jQuery(element).val();
    if (!url) {
        jQuery(element).parents(".um_field_container").children(".um_field_result").empty();
        return;
    }
    
    arg = 'showimage=true&imageurl=' + url;
    pfAjaxCall( element, 'um_show_uploaded_file', arg, function(data){
        jQuery(element).parents(".um_field_container").children(".um_field_result").empty().append(data);     
    });
}
  
function umRemoveFile(element) {
    if (confirm(fileuploader.confirm_remove)) {
        fieldName = jQuery(element).attr("data-field_name");
        jQuery(element).parents(".um_field_container").find(".qq-upload-success").remove();
        jQuery(element).parents(".um_field_result").empty().append("<input type='hidden' name='"+fieldName+"' value='' />");         
    }
}    

function umUpgradeFromPrevious(element) {
    arg = 'typess=xx';
    pfAjaxCall( element, 'um_common_request', arg, function(data) {
        jQuery(element).parents(".error").remove();    
    }); 
}

function umRedirection(element) {
    var arg = jQuery( element ).parent("form").serialize();       
    document.location.href = ajaxurl + "?action=pf_ajax_request&" + arg;  
}

function umConditionalRequired(field, rules, i, options) {
    var baseField = field.attr('id').split('_');
    baseField.pop();
    baseField = baseField.join('_');
 
    if (jQuery('#' + baseField).val().length > 0 && field.val().length == 0)
        rules.push('required'); 
}

function umShowVideo(element, type, width, height){
    url = jQuery(element).val();
    if (!url) {
        jQuery(element).parents(".um_field_container").children(".um_field_result").empty();
        return;
    }

    if (type == "youtube_video") {
        regex = /^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&"'>]+)/;
        id = (url.match(regex) != null && url.match(regex)[1]) ? url.match(regex)[1] : null;

        if( !id || id.length != 11) {
            jQuery(element).parents(".um_field_container").children(".um_field_result").empty();
            return;
        } else {
            src = 'https://www.youtube.com/embed/' + id;
            embed = '<iframe width=' + width + ' height=' + height + ' src=' + src + '></iframe>';
            jQuery(element).parents(".um_field_container").children(".um_field_result").empty().append(embed);
            return;
        }
    } else if (type == "vimeo_video") {
        regex = /https?:\/\/(?:www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/;
        id = (url.match(regex) != null && url.match(regex)[3]) ? url.match(regex)[3] : null;

        if( !id ) {
            jQuery(element).parents(".um_field_container").children(".um_field_result").empty();
            return;
        } else {
            src = 'https://player.vimeo.com/video/' + id;
            embed = '<iframe src='+ src + ' width=' + width + ' height=' + height + '></iframe>';
            jQuery(element).parents(".um_field_container").children(".um_field_result").empty().append(embed);
            return;
        }
    }
}