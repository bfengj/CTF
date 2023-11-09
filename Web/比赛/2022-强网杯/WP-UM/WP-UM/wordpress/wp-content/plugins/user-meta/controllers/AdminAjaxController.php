<?php
namespace UserMeta;

use UserMeta\Html\Html;

class AdminAjaxController
{

    function __construct()
    {
        add_action('wp_ajax_um_add_field', array(
            $this,
            'ajaxAddField'
        ));
        add_action('wp_ajax_um_add_form_field', array(
            $this,
            'ajaxAddFormField'
        ));
        add_action('wp_ajax_um_change_field', array(
            $this,
            'ajaxChangeField'
        ));
        add_action('wp_ajax_um_update_field', array(
            $this,
            'ajaxUpdateFields'
        ));

        add_action('wp_ajax_um_add_form', array(
            $this,
            'ajaxAddForm'
        ));
        add_action('wp_ajax_um_update_forms', array(
            $this,
            'ajaxUpdateForms'
        ));

        /**
         * Add wp_ajax_user_meta_ajax action hook
         */
        (new RouteResponse())->initHooks();
    }

    function ajaxAddField()
    {
        global $userMeta;
        $userMeta->verifyAdminNonce('add_field');

        if (empty($_POST['id']))
            die();

        if (! empty($_POST['field_type'])) {
            $arg = $this->sanitizeAjaxInputs($_POST);
            $arg['is_new'] = true;
            $fieldBuilder = new FieldBuilder($arg);
            $fieldBuilder->setEditor('fields_editor');
            echo $fieldBuilder->buildPanel();
        }

        die();
    }

    function ajaxAddFormField()
    {
        global $userMeta;
        $userMeta->verifyAdminNonce('add_field');

        if (empty($_POST['id']))
            die();

        if (! empty($_POST['is_shared'])) {
            $fields = $userMeta->getData('fields');
            $fieldId = sanitize_key($_POST['id']);

            if (isset($fields[$fieldId])) {
                $field = $fields[$fieldId];
                $field['id'] = $fieldId;
                $field['is_shared'] = true;
                $fieldBuilder = new FieldBuilder($field);
                $fieldBuilder->setEditor('form_editor');
                echo $fieldBuilder->buildPanel();
            } else {
                echo Html::div(sprintf('Shared field with id %s does not exists!', $fieldId), [
                    'class' => 'alert alert-warning',
                    'role' => 'alert'
                ]);
            }
        } elseif (! empty($_POST['field_type'])) {
            $arg = $this->sanitizeAjaxInputs($_POST);
            $arg['is_new'] = true;
            $fieldBuilder = new FieldBuilder($arg);
            $fieldBuilder->setEditor('form_editor');
            echo $fieldBuilder->buildPanel();
        }

        die();
    }

    function ajaxChangeField()
    {
        global $userMeta;
        $userMeta->verifyNonce(true);

        if (isset($_POST['field_type']) && isset($_POST['id']) && $_POST['editor']) {
            $field = $this->sanitizeAjaxInputs($_POST);
            $fieldBuilder = new FieldBuilder($field);
            $fieldBuilder->setEditor(sanitize_key($_POST['editor']));
            echo $fieldBuilder->buildPanel();
        }

        die();
    }

    function ajaxUpdateFields()
    {
        global $userMeta;
        $userMeta->verifyAdminNonce('updateFields');

        $fields = array();
        if (isset($_POST['fields'])) {
            $fields = $userMeta->arrayRemoveEmptyValue($_POST['fields']);
            /*
             * @todo make sanitizeDeep work on default_value for HTML input
             $fields = sanitizeDeep($userMeta->arrayRemoveEmptyValue($_POST['fields']), [
             'default_value' => 'wp_check_invalid_utf8' ?
             ]);
             */
        }

        $formBuilder = new FormBuilder();

        $fields = $formBuilder->sanitizeFieldsIDs($fields);

        $fields = apply_filters('user_meta_pre_configuration_update', $fields, 'fields_editor');
        $userMeta->updateData('fields', $fields);

        $formBuilder->setMaxFieldID();

        if (! empty($formBuilder->redirect_to)) {
            echo json_encode(array(
                'redirect_to' => $formBuilder->redirect_to
            ));
            die();
        }

        echo 1;
        die();
    }

    function ajaxAddForm()
    {
        global $userMeta;
        $userMeta->verifyNonce(true);

        $fields = $userMeta->getData('fields');
        $userMeta->render('form', array(
            'id' => sanitize_key($_POST['id']),
            'fields' => $fields
        ));
        die();
    }

    /**
     * Handle creating new form and updating existing form.
     */
    function ajaxUpdateForms()
    {
        global $userMeta;
        $userMeta->verifyAdminNonce('formEditor');

        if (empty($_POST['current_query_string'])) {
            echo '"current_query_string" is missing';
            die();
        }

        /**
         * $_SERVER[HTTP_REFERER] holds url of browser address bar
         * We need it because request sent to admin-ajax.php
         *
         * $_REQUEST holds form data
         */
        // Commented since 2.1
        // $parse = parse_url($_SERVER['HTTP_REFERER']);
        // parse_str($parse['query'], $query);

        /**
         * $_POST['current_query_string'] holds query_string of the browser address bar
         * e.g.
         * page=user-meta&action=new
         * We need it because request sent to admin-ajax.php
         *
         * $_POST holds form data
         * $query holds extracted array from current_query_string.
         */
        $query = [];
        parse_str(sanitize_text_field($_POST['current_query_string']), $query);

        if (empty($query['action'])) {
            echo '"action" parameter is missing in "current_query_string"';
            die();
        }

        if (! empty($_POST['form_key'])) {
            $formKey = sanitize_text_field($_POST['form_key']);
        } else {
            echo 'Form name is required.';
            die();
        }

        $forms = $userMeta->getData('forms');

        $formBuilder = new FormBuilder();

        if ('edit' == $query['action']) {
            if (empty($query['form']) || empty($_POST['form_key'])) {
                echo '"form" parameter is missing in "current_query_string"';
                die();
            }

            if ($query['form'] != $_POST['form_key']) {
                if (isset($forms[$_POST['form_key']])) {
                    echo 'Form: "' . esc_html($_POST['form_key']) . '" already exists!';
                    die();
                }

                unset($forms[$query['form']]);
                $query['form'] = sanitize_text_field($_POST['form_key']);
                // Commented since 2.0
                // $formBuilder->redirect_to = $parse['scheme'] . '://' . $parse['host'] . $parse['path'] . '?' . http_build_query($query);
                $formBuilder->redirect_to = admin_url('admin.php') . '?' . http_build_query($query);
            }
        } elseif ('new' == $query['action']) {
            if (isset($forms[$_POST['form_key']])) {
                echo 'Form: "' . esc_html($_POST['form_key']) . '" already exists!';
                die();
            }

            $query['form'] = sanitize_text_field($_POST['form_key']);
            $query['action'] = 'edit';
            // Commented since 2.0
            // $formBuilder->redirect_to = $parse['scheme'] . '://' . $parse['host'] . $parse['path'] . '?' . http_build_query($query);
            $formBuilder->redirect_to = admin_url('admin.php') . '?' . http_build_query($query);
        }

        $fields = $formBuilder->getSharedFields();

        $form = stripslashes_deep($_POST);

        // $form = $userMeta->arrayRemoveEmptyValue( $_POST );

        $formFields = isset($form['fields']) ? $form['fields'] : [];

        $formFields = $formBuilder->sanitizeFieldsIDs($formFields);

        foreach ($formFields as $id => $field) {
            if (is_array($field)) {
                foreach ($field as $key => $val) {
                    // Process shared fields
                    if (isset($fields[$id][$key])) {
                        if ($fields[$id][$key] == $val)
                            unset($formFields[$id][$key]);
                    } else {
                        if (empty($val))
                            unset($formFields[$id][$key]);
                    }
                }
            }

            if (! empty($field['make_field_shared']) && ! isset($fields[$id])) {
                unset($formFields[$id]['make_field_shared']);
                $fields[$id] = $formFields[$id];
                $formFields[$id] = array();
                $triggerFieldsUpdate = true;
            }
        }

        $form['fields'] = $formFields;

        $form = $userMeta->removeAdditional($form);

        $forms[$formKey] = $form;

        $forms = apply_filters('user_meta_pre_configuration_update', $forms, 'forms_editor');

        $userMeta->updateData('forms', $forms);

        // $userMeta->dump($fields);
        if (! empty($triggerFieldsUpdate)) {
            $userMeta->updateData('fields', $fields);
            if (empty($formBuilder->redirect_to)) {
                // Commented since 2.0
                // $formBuilder->redirect_to = $parse['scheme'] . '://' . $parse['host'] . $parse['path'] . '?' . $parse['query'];
                $formBuilder->redirect_to = admin_url('admin.php') . '?' . sanitize_text_field($_POST['current_query_string']);
            }
        }

        $formBuilder->setMaxFieldID();

        if (! empty($formBuilder->redirect_to)) {
            echo json_encode(array(
                'redirect_to' => $formBuilder->redirect_to
            ));
            die();
        }

        echo 1;
        die();
    }

    /**
     * Sanitize ajax inputs
     *
     * @param array $inputs
     * @return array
     */
    private function sanitizeAjaxInputs($inputs = [])
    {
        $sanitizeCallbacks = [
            'id' => 'sanitize_key',
            'field_type' => 'sanitize_key'
        ];

        return sanitizeDeep($inputs, $sanitizeCallbacks);
    }
}
