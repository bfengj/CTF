(function($) {

    var userMeta = userMeta || {};

    userMeta.admin = {

        init: function() {
            this.events();
            this.initMultiselect();
        },

        events: function() {
            $(document).on('click', '.panel .panel-heading', this.togglePanel);
        },

        togglePanel: function() {
            $(this).closest(".panel").find(".collapse").slideToggle();
        },

        initMultiselect: function() {
            if ($.isFunction($.fn.multiselect)) {
                $('.um_multiselect').multiselect({
                    includeSelectAllOption: true
                });
            }
        },

        saveButton: function(arg, parentElement, successCallback) {
            $.ajax({
                type: "post",
                url: ajaxurl,
                data: arg,
                beforeSend: function() {
                    parentElement.find(".um_save_button").html('<i class="fa fa-spin fa-circle-o-notch"></i> ' + user_meta.saving);
                },
                success: function(data) {
                    var config = "";
                    try {
                        config = JSON.parse(data);
                        if (config.redirect_to) {
                            window.location.replace(config.redirect_to);
                        }
                    } catch (err) {}

                    if (data == '1' || (config && typeof config == 'object')) {
                    	typeof successCallback === 'function' && successCallback(data);
                    	parentElement.find(".um_save_button").removeClass('btn-primary').addClass('btn-success');
                    	parentElement.find(".um_save_button").html('<i class="fa fa-check"></i> ' + user_meta.saved);
                    	parentElement.find(".um_error_msg").html("");
                    } else {
                    	parentElement.find(".um_save_button").removeClass('btn-primary').addClass('btn-danger');
                    	parentElement.find(".um_save_button").html(user_meta.not_saved + ' <i class="fa fa-exclamation-triangle"></i>');
                    	parentElement.find(".um_error_msg").html('<span class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> ' + data + '</span>');
                    }

                    setTimeout(function() {
                    	parentElement.find(".um_save_button").removeClass('btn-success').removeClass('btn-danger').addClass('btn-primary');
                    	parentElement.find(".um_save_button").html('Save Changes');
                    }, 3000);
                }
            });
        }
    };

    userMeta.formEditor = {

        init: function() {
            if ($('#um_fields_editor').length) {
                this.name = 'fields_editor';
                this.editor = $('#um_fields_editor');
                this.fieldsLoad();
                this.fieldsEvents();

            } else if ($('#um_form_editor').length) {
                this.name = 'form_editor';
                this.editor = $('#um_form_editor');
                this.formLoad();
                this.formEvents();
            }
        },

        fieldsLoad: function() {
            this.expandFirstField();

            this.load();
        },

        formLoad: function() {
            this.collapseAll();
            this.sanitizeSelectors();

            this.load();
        },

        load: function() {
            $('#um_fields_container').sortable({handle: ".panel-heading"});

            $(window).scroll(this.steadySidebar);
            $(window).resize(this.steadySidebar);
            $(window).load(this.steadySidebar);

            //this.loadConditionalConfig();
            //$(this.editor).find('.um_selector_options .um_plusminus_rows_holder').sortable();
            this.triggerLoadMethods();
            
            userMeta.optionsSelection.init(this.editor);
        },
        
        /**
         * Common load method for load, add-new-field and change-field
         */
        triggerLoadMethods: function() {
        	userMeta.admin.initMultiselect();
        	userMeta.formEditor.loadConditionalConfig();
        	$(this.editor).find('.um_selector_options .um_plusminus_rows_holder').sortable();
        	$('[data-toggle="tooltip"]').tooltip();
        },

        fieldsEvents: function() {
            this.editor.on('click', '.um_save_button', this.updateFields);
            this.editor.on('click', '#um_fields_selectors .panel-heading', this.toggleSelectorPanel);

            this.editor.on('click', '.um_field_selecor', this.addNewField);

            this.events();
        },

        formEvents: function() {
            this.editor.on('click', '.um_field_selecor', this.addNewFormField);

            this.editor.on('click', '#um_fields_selectors .panel-heading', this.toggleSelectorPanel);
            this.editor.on('change', '.um_enable_conditional_logic', this.toggleConditionalPanel);

            this.editor.on('click', '.um_conditional_plus', this.conditionalPlus);
            this.editor.on('click', '.um_conditional_minus', this.conditionalMinus);

            this.editor.on('click', '.um_save_button', this.updateForm);

            this.events();
        },

        events: function() {
            this.editor.on('change', 'select[name=field_type]', this.changeField);

            this.editor.on('keyup', 'input[name=field_title]', this.changeTitle);
            this.editor.on('blur', 'input[name=field_title]', this.updateMetaKey);
            this.editor.on('blur', 'input[name=meta_key]', this.updateMetaKey);

            this.editor.on('click', '.panel .panel-heading .um_trash', this.removePanel);

            this.editor.on('change', '.um_parent', this.toggleConditionalConfig);
        },

        expandFirstField: function() {
            $('#um_fields_container .panel-collapse').removeClass('in');
            $('#um_fields_container .panel-collapse').first().addClass('in');

            $('#um_fields_selectors .panel-collapse').first().addClass('in');
        },

        collapseAll: function() {
            $('#um_fields_container .panel-collapse').removeClass('in');
        },

        sanitizeSelectors: function() {
            var first = $('#um_fields_selectors .panel-collapse').first();
            first.addClass('in');
            first.css('max-height', '300px');
            first.css('overflow', 'auto');
        },

        toggleSelectorPanel: function() {
            self = $(this).closest(".panel").find(".collapse");
            $(this).closest(".panel-group").find(".collapse").not(self).slideUp();
        },

        removePanel: function() {
            if (confirm('Confirm to remove?')) {
                if (userMeta.formEditor.name == 'form_editor') {
                    var fieldID = $(this).closest(".panel").find('.um_field_id').text();
                    $('#um_fields_selectors button[data-field-id="' + fieldID + '"]').show();

                    userMeta.formEditor.removeOptionFromConditions(fieldID);
                }

                $(this).closest(".panel").remove();
            }
        },
        
        addNewField: function() {
            var self = $(this);
            if (self.attr("class").indexOf("pf_blure") >= 0) return;
            
            var label = self.text();
            var newID = parseInt($('#um_max_id').val()) + 1;

            var arg = 'id=' + newID + '&field_type=' + $(this).attr('data-field-type');
            arg = arg + '&action=um_add_field&_wpnonce=' + $(this).attr('data-nonce');

            $.ajax({
                type: 'post',
                url: ajaxurl,
                data: arg,
                beforeSend: function() {
                    self.html('<i class="fa fa-spin fa-circle-o-notch"></i> ' + label);
                },
                success: function(data) {
                    self.html(label);
                    $('#um_fields_container').append(data);
                    $('#um_max_id').val(newID);

                    $('html, body').animate({
                        scrollTop: $('#um_admin_field_' + newID).offset().top
                    });

                    userMeta.formEditor.triggerLoadMethods();
                }
            });
        },

        addNewFormField: function() {
            var self = $(this);
            if (self.attr("class").indexOf("pf_blure") >= 0) return;
            
            var label = self.text();
            var newID = parseInt($("#um_max_id").val()) + 1;

            var isShared = 0;

            if ($(this).attr('data-is-shared') && parseInt($(this).attr('data-field-id')) > 0) {
                isShared = 1;
                newID = parseInt($(this).attr('data-field-id'));
            }

            var arg = 'id=' + newID + '&field_type=' + $(this).attr('data-field-type');
            arg = arg + '&action=um_add_form_field&is_shared=' + isShared + '&_wpnonce=' + $(this).attr('data-nonce');

            $.ajax({
                type: "post",
                url: ajaxurl,
                data: arg,
                beforeSend: function() {
                    self.text(label);
                },
                success: function(data) {
                    if (isShared) {
                        self.hide();
                    } else {
                        $('#um_max_id').val(newID);
                    }

                    self.text(label);
                    $('#um_fields_container').append(data);

                    $('html, body').animate({
                        scrollTop: $('#um_admin_field_' + newID).offset().top
                    });

                    userMeta.formEditor.triggerLoadMethods();

                    userMeta.formEditor.addOptionToConditions(newID);
                }
            });
        },

        changeField: function() {
            var field = $(this).closest('.panel');
            var id = $(field).find('.um_field_id').text();

            var fieldObj = $(this).closest('.panel-body').find('input, textarea, select').serializeArray();
            var arg = {};
            for (var i = 0; i < fieldObj.length; i++) {
                if (fieldObj[i].value) {
                    arg[fieldObj[i].name] = fieldObj[i].value;
                }
            }
            arg.id = id;
            arg.editor = $('#um_editor').val();

            var result = userMeta.optionsSelection.getSelectionOptions(field);
            if (result.options) {
                arg.options = result.options;
                arg.default_value = result.defaultOpt;
            }

            pfAjaxCall(this, "um_change_field", $.param(arg), function(data) {
                field.replaceWith(data);
                userMeta.formEditor.triggerLoadMethods();
                userMeta.formEditor.addOptionToConditions(id);
            });
        },

        changeTitle: function() {
            title = $(this).val();
            //if (!title){ title = 'Untitled'; }
            $(this).closest(".panel").find("h3 .um_field_label").text(title);
        },

        updateMetaKey: function() {
            self = $(this).closest('.panel');
            if (self.find('input[name=meta_key]').length && !self.find('input[name=meta_key]').val()) {
                title = self.find('input[name=field_title]').val();
                meta_key = title.trim().toLowerCase().replace(/[^a-z0-9 ]/g, '').replace(/\s+/g, '_');
                self.find('input[name=meta_key]').val(meta_key);
            }
        },

        steadySidebar: function() {
            var windowWidth = $(window).width();
            var windowHeight = $(window).height();
            var windowTop = $(window).scrollTop();

            var containerTop = $("#wpbody").offset().top + 5;
            //var containerTop = $(".wrap").offset().top;
            var FieldsContainerTop = $("#um_fields_container").offset().top;
            var FieldsContainerHeight = parseInt($("#um_fields_container").css("height"));
            var holderTop = $("#um_steady_sidebar_holder").offset().top;
            var sidebarTop = $("#um_steady_sidebar").offset().top;
            var sidebarHeight = parseInt($("#um_steady_sidebar").css("height"));

            if (FieldsContainerHeight < windowHeight /*|| sidebarHeight > windowHeight*/ ) {
                $('#um_steady_sidebar').css({
                    position: 'relative',
                    top: 0,
                    width: '100%'
                });
                return;
            }

            //var footerTop = $("#wpfooter").offset().top;
            //var footerHeight = parseInt($("#wpfooter").css("height")) ;
            //var sidebarHeight = windowHeight - containerTop;

            var adminbarHeight = parseInt($("#wpadminbar").css("height"));
            //var sidebarHeight = parseInt($("#um_steady_sidebar").css("height")) ;
            var frameTop = windowTop + containerTop;
            var footerScrollTop = $("#wpfooter").offset().top - windowHeight;

            if (windowWidth >= 790) { //Standard: 767
                if (frameTop >= sidebarTop) {
                    if (windowTop >= footerScrollTop) {
                        $('#um_steady_sidebar').css({
                            position: 'relative',
                            top: (footerScrollTop - holderTop + containerTop),
                            width: '100%'
                        });
                    } else if (FieldsContainerTop > sidebarTop) {
                        $('#um_steady_sidebar').css({
                            position: 'relative',
                            top: 0,
                            width: '100%'
                        });
                    } else {
                        $('#um_steady_sidebar').css({
                            position: 'fixed',
                            top: containerTop,
                            width: '26.5%'
                        });
                    }

                } else {
                    $('#um_steady_sidebar').css({
                        position: 'relative',
                        top: 0,
                        width: '100%'
                    });
                }

            } else {
                $('#um_steady_sidebar').css({
                    position: 'relative',
                    top: 0,
                    width: '100%'
                });
            }

            //if ( sidebarHeight > windowHeight ) {
            $('#um_steady_sidebar').css({
                height: windowHeight - adminbarHeight,
                overflow: 'hidden'
            });
            //}
            //console.log( FieldsContainerTop + ', ' + frameTop + ', ' + sidebarTop + ', ' + windowWidth  );
        },

        loadConditionalConfig: function() {
            this.editor.find('.panel-body .um_parent').each(function() {
                userMeta.formEditor.toggleConditionalConfig(this, $(this)); // First argument is for default event
            });
        },

        /**
         * Implemented for select and checkbox
         */
        toggleConditionalConfig: function(event, input) {
            if (!input) {
                input = $(this);
            }

            var panel = input.closest('.panel-body');
            var tagName = input.prop("tagName").toLowerCase();

            if (tagName == 'select') {
                var allChild = [];
                input.find('option').each(function() {
                    if ($(this).data('child')) {
                        child = $(this).data('child').split(',');
                        $.merge(allChild, child);
                    }
                });
                allChild = $.unique(allChild); //console.log(allChild);

                // Hide all child first
                $(allChild).each(function() { //console.log(panel.find( 'input[name='+ this +']' ).closest('p'));
                    userMeta.formEditor.toggleHideFieldBuilderFields(panel, this);
                });

                // Show relevent child
                if (input.find(':selected').data('child')) {
                    targetChild = input.find(':selected').data('child').split(',');
                    $(targetChild).each(function() {
                        userMeta.formEditor.toggleShowFieldBuilderFields(panel, this);
                    });
                }

            } else if (tagName == 'input' && input.attr('type') == 'checkbox') {
                targetChild = input.data('child').split(',');
                $(targetChild).each(function() { //panel.find( 'input[name='+ this +']' ).hide();
                    if (input.is(":checked")) {
                        userMeta.formEditor.toggleShowFieldBuilderFields(panel, this);
                    } else {
                        userMeta.formEditor.toggleHideFieldBuilderFields(panel, this);
                    }
                });
            }
        },

        toggleHideFieldBuilderFields: function(panel, inputName) {
            panel.find('input[name=' + inputName + ']').closest('.um_fb_field').slideUp();
            panel.find('select[name=' + inputName + ']').closest('.um_fb_field').slideUp();
        },

        toggleShowFieldBuilderFields: function(panel, inputName) {
            panel.find('input[name=' + inputName + ']').closest('.um_fb_field').slideDown();
            panel.find('select[name=' + inputName + ']').closest('.um_fb_field').slideDown();
        },

        toggleConditionalPanel: function() {
            var panel = $(this).closest('.panel-body').find('.um_conditional_details');
            if ($(this).is(":checked")) {
                panel.slideDown();
            } else {
                panel.slideUp();
            }

            userMeta.formEditor.conditionsCountsEvent(panel);
        },

        conditionalPlus: function() {
            var panel = $(this).closest('.um_conditional_details');

            var row = $(this).closest('.form-group');
            var clone = row.clone();

            clone.find('.um_conditional_value').val('');

            clone.insertAfter(row);

            userMeta.formEditor.conditionsCountsEvent(panel);
        },

        conditionalMinus: function(e) {
            e.preventDefault();

            var panel = $(this).closest('.um_conditional_details');

            rows = $(this).closest('.um_conditions').find('.form-group').length;
            if (rows > 1) {
                $(this).closest('.form-group').remove();
            }

            userMeta.formEditor.conditionsCountsEvent(panel);
        },

        conditionsCountsEvent: function(panel) {
            var rows = panel.find('.um_conditions .form-group').length;
            if (rows > 1) {
                panel.find('.um_conditional_relation_div').slideDown();
                panel.find('.um_conditional_minus').show();
            } else {
                panel.find('.um_conditional_relation_div').slideUp();
                panel.find('.um_conditional_minus').hide();
            }
        },

        addOptionToConditions: function(id) {
            var field = $('#um_admin_field_' + id + ' .panel-title');

            var optionLabel = $('#um_admin_field_' + id + ' .panel-title .um_field_panel_title').text();
            optionLabel = $(document.createElement("DIV")).html(optionLabel).text();

            this.editor.find('.um_conditional_field_id').each(function() {
                $(this).append('<option value="' + id + '">' + optionLabel + '</option>');
            });

            // Copy populated select
            first = this.editor.find('.um_conditional_field_id').first().html();
            $('#um_admin_field_' + id + ' .um_conditional_field_id').html(first);
        },

        removeOptionFromConditions: function(id) {
            this.editor.find('.um_conditional_field_id option[value="' + id + '"]').remove();
        },

        getConditionalLogic: function(element) {
            var condition = {},
                rules = [];

            if (element.find('.um_enable_conditional_logic').is(':checked')) {
                condition.visibility = element.find('.um_conditional_visibility').val();
                condition.relation = element.find('.um_conditional_relation').val();

                $(element).find('.um_conditional_details .um_conditions .form-group').each(function() {
                    var rule = {};
                    rule.field_id = $(this).find('.um_conditional_field_id').val();
                    rule.condition = $(this).find('.um_conditional_condition').val();
                    rule.value = $(this).find('.um_conditional_value').val();
                    rules.push(rule);
                });

                condition.rules = rules;
            }

            return condition;
        },

        updateFields: function() {
            var fields = [];
            $(".um_field_single").each(function(index) {
                fieldID = $(this).find(".um_field_id").text();
                field = {
                    'id': fieldID
                };
                fieldObj = $(this).find('input, textarea, select').serializeArray();
                for (var i = 0; i < fieldObj.length; i++) {
                    if (fieldObj[i].value) {
                        field[fieldObj[i].name] = fieldObj[i].value;
                    }
                }

                // Multiselect
                $(this).find('.um_multiselect').each(function() {
                    name = $(this).attr('name');
                    if (name == 'undefined') return;

                    delete field[name];
                    name = name.replace('[]', '');
                    multiselectVal = [];
                    $(this).parent().find('.multiselect-container li.active input').each(function() {
                        var val = $(this).val();
                        if (val && val != 'multiselect-all') {
                            multiselectVal.push($(this).val());
                        }
                    });
                    field[name] = multiselectVal;
                });

                field_type = $(this).find('.um_field_type').val();
                if ($.inArray(field_type, ['select', 'radio', 'checkbox', 'multiselect']) > -1) {
                    result = userMeta.optionsSelection.getSelectionOptions(this);
                    if (result.options) {
                        field.options = result.options;
                    }
                    field.default_value = result.defaultOpt;
                }

                //console.log(field_type);
                fields[index] = field;
            });

            var arg = {
                "action": "um_update_field",
                "fields": fields
            };

            var input = $('#um_additional_input').find('input').serializeArray();
            for (var i = 0; i < input.length; i++) {
                arg[input[i].name] = input[i].value;
            }

            var editor = $(this).closest('#um_fields_editor');
            userMeta.admin.saveButton(arg, editor);
        },

        updateForm: function() {
            var fields = [];
            $(".um_field_single").each(function(index) {
                fieldID = $(this).find(".um_field_id").text();
                field = {
                    'id': fieldID
                };
                fieldObj = $(this).find('input, textarea, select').serializeArray();
                for (var i = 0; i < fieldObj.length; i++) {
                    field[fieldObj[i].name] = fieldObj[i].value;
                }

                // Multiselect
                $(this).find('.um_multiselect').each(function() {
                    name = $(this).attr('name');
                    if (name == 'undefined') return;

                    delete field[name];
                    name = name.replace('[]', '');
                    multiselectVal = [];
                    $(this).parent().find('.multiselect-container li.active input').each(function() {
                        var val = $(this).val();
                        if (val && val != 'multiselect-all') {
                            multiselectVal.push($(this).val());
                        }
                    });
                    field[name] = multiselectVal;
                });

                $(this).find('input[type="checkbox"]').each(function() {
                    name = $(this).attr('name');
                    if (name && name != 'undefined') {
                        if ($(this).is(':checked')) {
                            field[name] = 1;
                        } else {
                            field[name] = 0;
                        }
                    }
                });

                condition = userMeta.formEditor.getConditionalLogic($(this));
                if (condition) {
                    field.condition = condition;
                }

                field_type = $(this).find('.um_field_type').val();
                if ($.inArray(field_type, ['select', 'radio', 'checkbox', 'multiselect']) > -1) {
                    result = userMeta.optionsSelection.getSelectionOptions(this);
                    if (result.options) {
                        field.options = result.options;
                    }
                    field.default_value = result.defaultOpt;
                }

                fields[index] = field;
            });

            var arg = {
                "action": "um_update_forms"
            };

            arg.form_key = $('input[name="form_key"]').val();
            arg.fields = fields;

            var i = 0;
            input = $('#um_form_settings_tab').find('input, textarea, select').serializeArray();
            for (i = 0; i < input.length; i++) {
                arg[input[i].name] = input[i].value;
            }

            input = $('#um_additional_input').find('input').serializeArray();
            for (i = 0; i < input.length; i++) {
                arg[input[i].name] = input[i].value;
            }

            //console.log(arg);

            var editor = $(this).closest('#um_form_editor');
            userMeta.admin.saveButton(arg, editor);
        }

    };

    userMeta.optionsSelection = {

            init: function(editor) {
                this.editor = editor;
                this.editor.on('change', 'input[name=advanced_mode]', this.toggleAdvancedMode);
                this.editor.on('click', '.um_selector_options .um_row_button_plus', this.rowPlus);
                this.editor.on('click', '.um_selector_options .um_row_button_minus', this.rowMinus);
                this.editor.on('click', '.um_selector_options .um_plusminus_row input[type=radio]', this.radioGroupWithoutName);
                this.editor.on('keyup', '.um_selector_options .um_option_label', this.updateValue);
            },

            toggleAdvancedMode: function() {
                var panel = $(this).closest('.panel-body');
                if (panel.find('.um_selector_options').length > 0) {
                    panel = panel.find('.um_selector_options');

                    if ($(this).is(":checked")) {
                        panel.find('.um_advanced').show('slow');
                    } else {
                        panel.find('.um_advanced').hide('slow');
                    }
                }
            },

            rowPlus: function() {
                var row = $(this).closest('.um_plusminus_row');
                var clone = row.clone();
                clone.find('input[type="text"]').val('');
                clone.find('input[type="radio"]').prop('checked', false);
                clone.find('input[type="checkbox"]').prop('checked', false);
                clone.insertAfter(row);
            },

            rowMinus: function() {
                var count = 0;

                if ($(this).closest('.um_plusminus_row.um_option').length > 0) {
                    count = $(this).closest('.um_plusminus_rows_holder').find('.um_plusminus_row.um_option').length;
                    if (count > 1) {
                        $(this).closest('.um_plusminus_row').remove();
                    }
                }

                if ($(this).closest('.um_plusminus_row.um_optgroup').length > 0) {
                    count = $(this).closest('.um_plusminus_rows_holder').find('.um_plusminus_row.um_optgroup').length;
                    if (count > 1) {
                        $(this).closest('.um_plusminus_row').remove();
                    }
                }
            },

            radioGroupWithoutName: function() {
                $(this).closest('.um_plusminus_rows_holder').find('.um_option_default').each(function() {
                    this.checked = false;
                });
                this.checked = true;
            },

            updateValue: function() {
                row = $(this).closest('.um_plusminus_row');
                val = this.value;
                row.find('.um_option_value').val(val);

                row.find('.um_option_default').val(row.find('.um_option_value').val());
            },

            getSelectionOptions: function(elem) {
                var options = [];
                var defaultOpt = [];
                $(elem).find('.um_selector_options .um_plusminus_row').each(function() {
                    var option = {};

                    if ($(this).find('.um_option_gruop').length > 0) {
                        if ($(this).find('.um_option_gruop').val()) {
                            option.type = 'optgroup';
                            option.label = $(this).find('.um_option_gruop').val();
                        }
                    } else {
                        option.value = $(this).find('.um_option_value').val();
                        option.label = $(this).find('.um_option_label').val();
                    }

                    if ($(this).find('.um_option_default').is(":checked")) {
                        defaultOpt.push($(this).find('.um_option_default').val());
                    }

                    if (!$.isEmptyObject(option)) {
                        options.push(option);
                    }
                });

                var result = [];
                result.options = options;
                result.defaultOpt = defaultOpt;
                //console.log(result);
                return result;
            },
        },
       
        
        userMeta.addons = {
            init: function() {
                if ($('#um_addons_admin').length) {
                    this.editor = $('#um_addons_admin');
                    this.loads();
                    this.events();
                }
            },

            loads: function() {
            	$.fn.bootstrapSwitch.defaults.size = 'mini';
            	$('.um_switch_checkbox').bootstrapSwitch();
            },
            
            events: function() {
                this.editor.on('switchChange.bootstrapSwitch', '.um_switch_checkbox', this.toggleAddon);
                this.editor.on('click', '.um_save_button', this.saveChanges);
            },

            ajaxSave: function(attr, parentElement, successCallback) {
            	$.ajax({
                    type: 'post',
                    url: ajaxurl,
                    data: attr,
                    beforeSend: function() {
                    	parentElement.find(".um_wait").html('<i class="fa fa-spin fa-circle-o-notch"></i> ' + user_meta.saving);
                    },
                    success: function(data) {
                    	if (data.trim() == '1') {
                    		successCallback(data);
                    		parentElement.find(".um_wait").html('<i class="fa fa-check"></i> ' + user_meta.saved);
                        } else {                  	
                    		parentElement.find(".um_wait").html('<i class="fa fa-exclamation-triangle"></i> ' + user_meta.not_saved + ' ' + String(data));
                        }
                        
                        setTimeout(function() {
                        	parentElement.find(".um_wait").html('');
                        }, 5000);
                    }
                });
            },

            toggleAddon: function(event, state) {
        		var panel = $(this).closest(".panel");
        		var attr = $(this).data('addon');
        		attr['toggle_state'] = state ? 1 : 0;
    			userMeta.addons.ajaxSave(attr, panel, function(data) {
                	if (state) {
                		panel.find(".um_ribbon").removeClass('hide').addClass('show');
                		panel.find(".um_addon_icon").removeClass('inactive').addClass('active');
                		panel.find(".um_options").removeClass('hide').addClass('show');
                		window.location.reload(); 
                	} else {
                		panel.find(".um_ribbon").removeClass('show').addClass('hide');
                		panel.find(".um_addon_icon").removeClass('active').addClass('inactive');
                		panel.find(".um_options").removeClass('show').addClass('hide');
                	}
    			});      		
            },
            
            saveChanges: function() {
            	var modal = $(this).closest('.modal');
        		var addonAttr = $(this).data('addon');
        		var attr = $('#um_modal_form_' + addonAttr['addon_name']).serialize() + '&' +  $.param(addonAttr);
            	userMeta.admin.saveButton(attr, modal, function(data) {});
            }
        },
        
        userMeta.settings = {
            init: function() {
                if ($('#um_settings_admin').length) {
                    this.editor = $('#um_settings_admin');
                    this.loads();
                    this.events();
                }
            },           
            loads: function() {
                $('.um_dropme').sortable({
                    connectWith: '.um_dropme',
                    cursor: 'pointer'
                }).droppable({
                    accept: '.button',
                    activeClass: 'um_highlight'
                });
                //$("#um_settings_tab").tabs();
                $("#loggedin_profile_tabs").tabs();
                $("#redirection_tabs").tabs();
                umSettingsRegistratioUserActivationChange();
            },
            events: function() {
            	new umSettingsDropdownPageChange();
                umSettingsToggleError();
                $('.um_page_dropdown, #um_login_login_page, #um_login_resetpass_page, #um_registration_email_verification_page').change(function() {
                	new umSettingsDropdownPageChange();
                    umSettingsToggleError();
                });
            }
    	},

        userMeta.advanced = {
            init: function() {
                if ($('#um_advanced_settings').length) {
                    this.editor = $('#um_advanced_settings');
                    this.events();
                }
            },
            events: function() {
                this.editor.on('click', '.um_generate_wpml_config', this.wpmlConfig);
            },
            wpmlConfig: function() {
                bindElement = $(this);
                pfAjaxCall(bindElement, 'um_generate_wpml_config', '', function(data) {
                    bindElement.after("<div class='pf_ajax_result'>" + data + "</div>");
                });
            }
        };

    $(function() {
        userMeta.admin.init();
        userMeta.formEditor.init();
        userMeta.addons.init();
        userMeta.advanced.init();
        userMeta.settings.init();
    });
    
})(jQuery);


function pfToggleMetaBox(toggleIcon) {
    jQuery(toggleIcon).parents('.postbox').children('.inside').toggle();

    if (jQuery(toggleIcon).parents('.postbox').hasClass('closed')) {
        jQuery(toggleIcon).parents('.postbox').removeClass("closed");
    } else {
        jQuery(toggleIcon).parents('.postbox').addClass("closed");
    }
}

function pfRemoveMetaBox(removeIcon) {
    if (confirm('Confirm to remove?')) {
        jQuery(removeIcon).parents('.postbox').parents('.meta-box-sortables').remove();
    }
}

function umNewField(element) {
    newID = parseInt(jQuery("#last_id").val()) + 1;
    arg = 'id=' + newID + '&field_type=' + jQuery(element).attr('field_type');
    pfAjaxCall(element, 'um_add_field', arg, function(data) {
        jQuery("#um_fields_container").append(data);
        jQuery("#last_id").val(newID);
    });
}

function umChangeFieldTitle(element) {
    title = jQuery(element).val();
    if (!title) {
        title = 'Untitled Field';
    }
    jQuery(element).parents(".postbox").children("h3").children(".um_admin_field_title").text(title);
}

function umUpdateMetaKey(element) {
    if (jQuery(element).parents(".postbox").find(".um_meta_key_editor").length) {
        if (!jQuery(element).parents(".postbox").find(".um_meta_key_editor").val()) {
            title = jQuery(element).parents(".postbox").find(".um_field_title_editor").val();
            meta_key = title.trim().toLowerCase().replace(/[^a-z0-9 ]/g, '').replace(/\s+/g, '_');
            jQuery(element).parents(".postbox").find(".um_meta_key_editor").val(meta_key);
        }
    }
}

function umNewForm(element) {
    newID = parseInt(jQuery("#form_count").val()) + 1;
    pfAjaxCall(element, 'um_add_form', 'id=' + newID, function(data) {
        jQuery("#um_fields_container").append(data);
        jQuery("#form_count").val(newID);

        jQuery('.um_dropme').sortable({
            connectWith: '.um_dropme',
            cursor: 'pointer'
        }).droppable({
            accept: '.postbox',
            activeClass: 'um_highlight'
        });
    });
}

function umUpdateForms(element) {
    if (!jQuery(element).validationEngine("validate")) return;

    jQuery(".um_selected_fields").each(function(index) {
        var length = jQuery(this).children(".postbox").size();
        n = index + 1;
        jQuery("#field_count_" + n).val(length);
    });

    bindElement = jQuery(".pf_save_button");
    bindElement.parent().children(".pf_ajax_result").remove();
    arg = jQuery(element).serialize();
    pfAjaxCall(bindElement, 'um_update_forms', arg, function(data) {
        bindElement.after("<div class='pf_ajax_result'>" + data + "</div>");
    });
}

function umChangeFormTitle(element) {
    title = jQuery(element).val();
    if (!title) {
        title = 'Untitled Form';
    }
    jQuery(element).parents(".postbox").children("h3").text(title);
}

function umAuthorizePro(element) {
    if (!jQuery(element).validationEngine("validate")) return;

    arg = jQuery(element).serialize();
    bindElement = jQuery("#authorize_pro");
    pfAjaxCall(bindElement, 'um_update_settings', arg, function(data) {
        bindElement.parent().children(".pf_ajax_result").remove();
        bindElement.after("<div class='pf_ajax_result'>" + data + "</div>");
    });
}

function umWithdrawLicense(element) {
    bindElement = jQuery(element);
    arg = "method_name=withdrawLicense";
    bindElement.parent().children(".pf_ajax_result").remove();
    pfAjaxCall(bindElement, 'pf_ajax_request', arg, function(data) {
        bindElement.after("<div class='pf_ajax_result'>" + data + "</div>");
    });
}

function umUpdateSettings(element) {
    bindElement = jQuery("#update_settings");

    jQuery(".um_selected_fields").each(function(index) {
        var length = jQuery(this).children(".postbox").size();
        n = index + 1;
        jQuery("#field_count_" + n).val(length);

    });

    arg = jQuery(element).serialize();
    pfAjaxCall(bindElement, 'um_update_settings', arg, function(data) {
        bindElement.parent().children(".pf_ajax_result").remove();
        bindElement.after("<div class='pf_ajax_result'>" + data + "</div>");
    });
}

// Get Pro Message in admin section
function umGetProMessage(element) {
    alert(user_meta.get_pro_link);
}

// Toggle custom field in Admin Import Page
function umToggleCustomField(element) {
    if (jQuery(element).val() == 'custom_field')
        jQuery(element).parent().children(".um_custom_field").fadeIn();
    else
        jQuery(element).parent().children(".um_custom_field").fadeOut();
}

/**
 * Export and Import
 */

var umAjaxRequest;

function umUserImportDialog(element) {
    jQuery("#import_user_dialog").html('<center>' + user_meta.please_wait + '</center>');
    jQuery("#dialog:ui-dialog").dialog("destroy");
    jQuery("#import_user_dialog").dialog({
        modal: true,
        beforeClose: function(event, ui) {
            umAjaxRequest.abort();
            jQuery(".pf_loading").remove();
        },
        buttons: {
            Close: function() {
                jQuery(this).dialog("close");
            }
        }
    });
    umUserImport(element, 0, 1);
}

function umUserImport(element, file_pointer, init) {
    arg = jQuery(element).serialize();
    arg = arg + '&step=import&file_pointer=' + file_pointer;
    if (init) arg = arg + '&init=1';
    pfAjaxCall(element, 'um_user_import', arg, function(data) {
        jQuery("#import_user_dialog").html(data);
        if (jQuery(data).attr('do_loop') == 'do_loop') {
            umUserImport(element, jQuery(data).attr('file_pointer'));
        }
    });
}

function umUserExport(element, type) {
    var arg = jQuery(element).parent("form").serialize();
    arg = arg.replace(/\(/g, "%28").replace(/\)/g, "%29"); //Replace "()"
    var field_count = jQuery(element).parent("form").children(".um_selected_fields").children(".postbox").size();

    arg = arg + "&action_type=" + type + "&field_count=" + field_count;

    pfAjaxCall(element, 'pf_ajax_request', arg, function(data) {
    	if (type == 'export' || type == 'save_export') {
        	var fileName = 'Users_' + new Date().toISOString() + '.csv';
        	umDownloadUri('data:text/csv;charset=utf-8,' + encodeURIComponent(data), fileName);
    	}    	
    	if (type == 'save') {
    		alert('Form saved');
    	}          
    });
    
    // Not in use since 1.5
    /*
    if (type == 'export' || type == 'save_export') {
        document.location.href = ajaxurl + "?action=pf_ajax_request&" + arg;
    } else if (type == 'save') {
        pfAjaxCall(element, 'pf_ajax_request', arg, function(data) {
            alert('Form saved');
        });
    }
    */
}

function umDownloadUri(uri, name) {
	var link = document.createElement("a");
	link.download = name;
	link.href = uri;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
	delete link;
}

function umNewUserExportForm(element) {
    var formID = jQuery("#new_user_export_form_id").val();
    incID = formID + 1;
    jQuery("#new_user_export_form_id").val(parseInt(formID) + 1);

    arg = 'method_name=userExportForm&form_id=' + formID;

    pfAjaxCall(element, 'pf_ajax_request', arg, function(data) {
        jQuery(element).before(data);

        jQuery('.um_dropme').sortable({
            connectWith: '.um_dropme',
            cursor: 'pointer'
        }).droppable({
            accept: '.postbox',
            activeClass: 'um_highlight'
        });
        jQuery(".um_date").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true
        });
    });
}

function umAddFieldToExport(element) {
    var metaKey = jQuery(element).parent().children(".um_add_export_meta_key").val();
    if (metaKey) {
        var button = '<div class="postbox">Title:<input type="text" style="width:50%" name="fields[' + metaKey + ']" value="' + metaKey + '" /> (' + metaKey + ')</div>';
        jQuery(element).parents("form").children(".um_selected_fields").append(button);
        jQuery(element).parent().children(".um_add_export_meta_key").val("").focus();
    } else {
        alert('Please provide Meta Key.');
    }
}

function umDragAllFieldToExport(element) {
    jQuery(element).parents("form").children(".um_selected_fields").append(
        jQuery(element).parents("form").children(".um_availabele_fields").html()
    );
    jQuery(element).parents("form").children(".um_availabele_fields").html("");
}

function umRemoveFieldToExport(element, formID) {
    if (confirm("This form will removed permanantly. Confirm to Remove?")) {
        var arg = 'method_name=RemoveExportForm&form_id=' + formID;
        pfAjaxCall(element, 'pf_ajax_request', arg, function(data) {

        });
        jQuery(element).parents(".panel").hide('slow').empty();
    }
}

function umToggleVisibility(condition, result, reverse) {
    reverse = typeof reverse == 'undefined' ? true : false;
    val = jQuery(condition).val();
    val = reverse ? !val : val;
    val ? jQuery(result).show() : jQuery(result).hide();
}

function umSettingsRegistratioUserActivationChange() {
    var userActivationType = jQuery('.um_registration_user_activation:checked').val();
    if (userActivationType == 'auto_active') {
        jQuery('#um_settings_registration_block_2').hide();
        jQuery('#um_settings_registration_block_1').fadeIn();
    } else if (userActivationType == 'email_verification') {
        jQuery('#um_settings_registration_block_1').hide();
        jQuery('#um_settings_registration_block_2').fadeIn();
    } else if (userActivationType == 'admin_approval') {
        jQuery('#um_settings_registration_block_1').hide();
        jQuery('#um_settings_registration_block_2').hide();
    } else if (userActivationType == 'both_email_admin') {
        jQuery('#um_settings_registration_block_1').hide();
        jQuery('#um_settings_registration_block_2').fadeIn();
    }
}

function umSettingsDropdownPageChange() {
	this.setViewButtonURL()
	this.toggleViewButton();
    umToggleVisibility('#um_login_login_page', '#um_login_login_page_create');
    umToggleVisibility('#um_login_login_page', '#um_login_disable_wp_login_php_block', false);
    umToggleVisibility('#um_registration_email_verification_page', '#um_registration_email_verification_page_create');
    umToggleVisibility('#um_login_resetpass_page', '#um_login_resetpass_page_create');
}

umSettingsDropdownPageChange.prototype.setViewButtonURL = function() {
	jQuery('#um_general_profile_page_view')
		.attr('href', user_meta.site_url + '?p=' + jQuery('#um_general_profile_page').val());
	jQuery('#um_login_login_page_view')
		.attr('href', user_meta.site_url + '?p=' + jQuery('#um_login_login_page').val());
	jQuery('#um_login_resetpass_page_view')
		.attr('href', user_meta.site_url + '?p=' + jQuery('#um_login_resetpass_page').val());
	jQuery('#um_registration_user_registration_page_view')
		.attr('href', user_meta.site_url + '?p=' + jQuery('#um_registration_user_registration_page').val());
	jQuery('#um_registration_email_verification_page_view')
		.attr('href', user_meta.site_url + '?p=' + jQuery('#um_registration_email_verification_page').val());
}

umSettingsDropdownPageChange.prototype.toggleViewButton = function() {
	umToggleVisibility('#um_general_profile_page', '#um_general_profile_page_view', false);
	umToggleVisibility('#um_login_login_page', '#um_login_login_page_view', false);
	umToggleVisibility('#um_login_resetpass_page', '#um_login_resetpass_page_view', false);
	umToggleVisibility('#um_registration_user_registration_page', '#um_registration_user_registration_page_view', false);
	umToggleVisibility('#um_registration_email_verification_page', '#um_registration_email_verification_page_view', false);
}

function umSettingsToggleError() {
    umToggleVisibility('#um_registration_email_verification_page', '.um_required_email_verification_page');
    showError = false;
    if (jQuery('#um_login_disable_wp_login_php:checked').val()) {
        if (!jQuery('#um_login_resetpass_page').val())
            showError = true;
    }
    if (showError)
        jQuery('.um_required_resetpass_page_page').fadeIn();
    else
        jQuery('.um_required_resetpass_page_page').fadeOut();
}

jQuery(document.body).on('click', '#um_review_box_perfect', function (e) {
    jQuery(this).parents('#um_review_box_main').hide();
    jQuery('.um_hidden[data-key="perfect"]').show();
});

function reviewNoticeHideShow(id, key){
    jQuery('#'+id).parents('#um_review_box_main').hide();
    jQuery('.um_hidden[data-key='+key+']').show();
    console.log(key);
}