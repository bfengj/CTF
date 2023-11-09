<?php
namespace UserMeta;

class SupportModel
{

    function methodPack($methodName)
    {
        global $userMeta;
        $html = null;
        $html .= $userMeta->nonceField();
        $html .= $userMeta->methodName($methodName);
        $html .= $userMeta->wp_nonce_field($methodName, 'um_post_method_nonce', false, false);
        if (! empty($_SERVER['HTTP_REFERER'])) {
            $ref = ! empty($_REQUEST['pf_http_referer']) ? esc_attr($_REQUEST['pf_http_referer']) : esc_attr($_SERVER['HTTP_REFERER']);
            $html .= "<input type=\"hidden\" name=\"pf_http_referer\" value=\"" . $ref . "\">";
        }
        return $html;
    }

    /**
     * get name of forms
     */
    function getFormsName()
    {
        global $userMeta;

        $formsList = array();
        $forms = $userMeta->getData('forms');
        if (is_array($forms)) {
            foreach ($forms as $key => $val)
                $formsList[] = $key;
        }
        return $formsList;
    }

    /**
     * Get Form Data with fields data within $form['fields']
     *
     * @param string $formName
     * @return false if form not found || full form array including fields.
     */
    function getFormData($formName)
    {
        global $userMeta;

        $forms = $userMeta->getData('forms');
        if (empty($forms[$formName]))
            return new \WP_Error('no_form', sprintf(__('Form "%s" is not found.', $userMeta->name), $formName));

        $form = $forms[$formName];
        if (empty($form['fields']))
            return $form;

        if (is_array($form['fields'])) {
            $fields = array();
            $allFields = $userMeta->getData('fields');
            foreach ($form['fields'] as $key => $fieldID) {
                if (isset($allFields[$fieldID])) {
                    $field = array();
                    $field['field_id'] = $fieldID;
                    if (is_array($allFields[$fieldID]))
                        $field = $field + $allFields[$fieldID];

                    $fields[$fieldID] = $field;
                }
            }
            $form['fields'] = $fields;
        }

        return $form;
    }

    /**
     * Get um fields by
     *
     * @param string $by:
     *            key,
     *            field_group
     * @param
     *            $value:
     */
    function getFields($by = null, $param = null, $get = null, $isFree = false)
    {
        global $userMeta;
        $fieldsList = $userMeta->umFields();

        if (! $by)
            return $fieldsList;

        // $result = array();
        if ($param) {
            if ($by == 'key') {
                if (key_exists($param, $fieldsList))
                    return $fieldsList[$param];
            } else {
                foreach ($fieldsList as $key => $fieldData) {
                    if (! empty($fieldData[$by]) && $fieldData[$by] == $param) {
                        if ($isFree) {
                            if (! $fieldData['is_free'])
                                continue;
                        }

                        if (! $get)
                            $result[$key] = $fieldData;
                        else
                            $result[$key] = $fieldData[$get];
                    }
                }
            }
        }

        return isset($result) ? $result : false;
    }

    /**
     * Extract fielddata from fieldID
     *
     * @param
     *            $fieldID
     * @param $fieldData :
     *            if $fieldData not set the it will search for field option for fielddata
     * @return array: field Data
     */
    function getFieldData($fieldID, $fieldData = null)
    {
        global $userMeta;

        if (! $fieldData) {
            $fields = $userMeta->getData('fields');
            if (! isset($fields[$fieldID]))
                return;
            $fieldData = $fields[$fieldID];
        }

        // Setting Field Group
        $field_type_data = $userMeta->getFields('key', $fieldData['field_type']);
        $field_group = $field_type_data['field_group'];

        // Setting Field Name
        $fieldName = null;
        if ($field_group == 'wp_default') {
            $fieldName = $fieldData['field_type'];
        } else {
            if (isset($fields[$fieldID]['meta_key']))
                $fieldName = $fieldData['meta_key'];
        }

        // Check if field is readonly
        $readOnly = @$fieldData['read_only'];
        if (! @$readOnly && @$fieldData['read_only_non_admin'])
            $readOnly = $userMeta->isAdmin() ? false : true;

        $returnData = $fieldData;
        $returnData['field_id'] = $fieldID;
        $returnData['field_group'] = $field_group;
        $returnData['field_name'] = $fieldName;
        $returnData['meta_key'] = isset($fieldData['meta_key']) ? $fieldData['meta_key'] : null;
        $returnData['field_title'] = isset($fieldData['field_title']) ? $fieldData['field_title'] : null;
        $returnData['required'] = isset($fieldData['required']) ? true : false;
        $returnData['unique'] = isset($fieldData['unique']) ? true : false;
        $returnData['read_only'] = @$readOnly;

        return $returnData;
    }

    /**
     * Get Custom Fields from 'Fields Editor'.
     *
     * @return array of meta_key if success, false if no meta key.
     */
    function getCustomFields()
    {
        global $userMeta;

        $fields = $userMeta->getData('fields');
        if (! $fields || ! is_array($fields))
            return false;

        foreach ($fields as $field) {
            if (@$field['meta_key'])
                $metaKeys[] = $field['meta_key'];
        }
        return @$metaKeys ? $metaKeys : false;
    }

    /**
     * Add Custom Fields to 'Fields Editor'.
     *
     * @param array $metaKeys:
     *            meta_key array which will be added.
     * @return bool: true if updadated, false if fail.
     */
    function addCustomFields($metaKeys = array())
    {
        global $userMeta;
        if (! $metaKeys || ! is_array($metaKeys))
            return false;

        $fields = $userMeta->getData('fields');
        $existingKeys = $this->getCustomFields();

        foreach ($metaKeys as $meta) {
            if (! $existingKeys)
                $add = true;
            elseif (! in_array($meta, $existingKeys))
                $add = true;

            if (@$add) {
                $fields[] = array(
                    'field_title' => $meta,
                    'field_type' => 'text',
                    'title_position' => 'top',
                    'meta_key' => $meta
                );
            }
            unset($add);
        }
        return $userMeta->updateData('fields', $fields);
    }

    function removeFromFileCache($filePath)
    {
        global $userMeta;

        if (empty($filePath))
            return;

        $cache = $userMeta->getData('cache');
        $fileCache = isset($cache['file_cache']) ? $cache['file_cache'] : array();

        $key = array_search($filePath, $fileCache);
        if ($key) {
            unset($cache['file_cache'][$key]);
            $userMeta->updateData('cache', $cache);
        }
    }

    function cleanupFileCache()
    {
        global $userMeta;
        $cache = $userMeta->getData('cache');

        $fileCache = isset($cache['file_cache']) ? $cache['file_cache'] : array();
        if (empty($fileCache) || ! is_array($fileCache))
            return;

        $time = time() - (60 * 60 * 10); // 10h
        foreach ($fileCache as $key => $filePath) {
            if ($key < $time) {
                unset($cache['file_cache'][$key]);
                if (file_exists(WP_CONTENT_DIR . $filePath))
                    unlink(WP_CONTENT_DIR . $filePath);
            }
        }
        $userMeta->updateData('cache', $cache);
    }

    function ajaxUmCommonRequest()
    {
        global $userMeta;
        $userMeta->verifyNonce();
        die();
    }

    function getProfileLink($pre = null)
    {
        global $userMeta;

        $general = $userMeta->getSettings('general');
        if (@$general['profile_page'])
            $link = get_permalink($general['profile_page']);
        else
            $link = admin_url('profile.php');

        return $link;
    }

    function pluginUpdateUrl()
    {
        global $userMeta;
        $plugin = $userMeta->pluginSlug;
        $url = wp_nonce_url("update.php?action=upgrade-plugin&plugin=$plugin", "upgrade-plugin_$plugin");
        return $url = admin_url(htmlspecialchars_decode($url));
    }

    /**
     * Get settings by key
     *
     * @param string $key
     * @return array
     */
    function getSettings($key)
    {
        global $userMeta;
        $settings = $userMeta->getData('settings');
        $data = isset($settings[$key]) ? $settings[$key] : [];
        if (! $data)
            $data = $userMeta->defaultSettingsArray($key);

        return $data;
    }

    /**
     * Get Email Template.
     * if database is empty then use default data.
     *
     * @param
     *            : string $key
     * @return : array
     */
    function getEmailsData($key)
    {
        global $userMeta;

        $data = $userMeta->getData('emails');
        $emails = @$data[$key];

        // if( empty( $emails ) ){
        $default = $userMeta->defaultEmailsArray($key);
        $roles = $userMeta->getRoleList();

        if (empty($emails['user_email']))
            $emails['user_email']['um_disable'] = @$default['user_email']['um_disable'];
        if (empty($emails['admin_email']))
            $emails['admin_email']['um_disable'] = @$default['admin_email']['um_disable'];

        foreach ($roles as $key => $val) {
            if (empty($emails['user_email'][$key]['subject']))
                $emails['user_email'][$key]['subject'] = @$default['user_email']['subject'];
            if (empty($emails['user_email'][$key]['body']))
                $emails['user_email'][$key]['body'] = @$default['user_email']['body'];

            if (empty($emails['admin_email'][$key]['subject']))
                $emails['admin_email'][$key]['subject'] = @$default['admin_email']['subject'];
            if (empty($emails['admin_email'][$key]['body']))
                $emails['admin_email'][$key]['body'] = @$default['admin_email']['body'];
        }
        // }

        return $emails;
    }

    function getAllAdminEmail()
    {
        $emails = array(
            get_bloginfo('admin_email')
        );

        $users = get_users(array(
            'role' => 'administrator'
        ));
        foreach ($users as $user) {
            $emails[] = $user->user_email;
        }

        return array_unique($emails);
    }

    /**
     * Filter role based on given role.
     * In givn role, role key should be use as array value.
     *
     * @param
     *            type (array) $roles
     */
    function allowedRoles($allowedRoles)
    {
        global $userMeta;
        $roles = $userMeta->getRoleList(true);

        if (! is_array($roles) || empty($roles))
            return false;
        if (! is_array($allowedRoles) || empty($allowedRoles))
            return false;

        foreach ($roles as $key => $val) {
            if (! in_array($key, $allowedRoles))
                unset($roles[$key]);
        }

        return $roles;
    }

    function adminPageUrl($page, $html_link = true)
    {
        global $userMeta;

        if ($page == 'forms') :
            $link = 'user-meta';
            $label = __('Forms', $userMeta->name);
         elseif ($page == 'fields') :
            $link = 'user-meta-fields';
            $label = __('Shared Fields', $userMeta->name);
         elseif ($page == 'settings') :
            $link = 'user-meta-settings';
            $label = __('Settings', $userMeta->name);
        endif;
        if (! @$link)
            return;

        $url = admin_url("admin.php?page=$link");
        if ($html_link)
            return "<a href='$url'>$label</a>";

        return $url;
    }

    /**
     * Convert content for user provided by %field_name%
     * Supported Extra filter: blog_title, blog_url, avatar, logout_link, admin_link
     *
     * @param \WP_User $user
     *            object
     * @param $data: (string)
     *            string for convertion
     * @return (string) converted string
     */
    function convertUserContent($user, $data, $extra = [])
    {
        global $userMeta;
        if (! is_string($data))
            return $data;

        preg_match_all('/\%[a-zA-Z0-9_-]+\%/i', $data, $matches);
        if (is_array(@$matches[0])) {
            $patterns = $matches[0];
            $replacements = array();
            foreach ($patterns as $key => $pattern) {
                $fieldName = strtolower(trim($pattern, '%'));
                $result = '';
                if ($fieldName == 'site_title')
                    $result = get_bloginfo('name');
                elseif ($fieldName == 'site_url')
                    $result = site_url();
                elseif ($fieldName == 'role')
                    $result = $userMeta->getUserRole($user->ID);
                elseif ($fieldName == 'avatar')
                    $result = get_avatar($user->ID);
                elseif ($fieldName == 'login_url')
                    $result = wp_login_url();
                elseif ($fieldName == 'logout_url')
                    $result = wp_logout_url();
                elseif ($fieldName == 'lostpassword_url')
                    $result = wp_lostpassword_url();
                elseif ($fieldName == 'admin_url')
                    $result = admin_url();
                elseif ($fieldName == 'activation_url')
                    $result = $userMeta->userActivationUrl('admin_approval', $user->ID, false);
                elseif ($fieldName == 'email_verification_url')
                    $result = $userMeta->emailVerificationUrl($user);
                elseif ($fieldName == 'user_modified_data')
                    $result = $userMeta->userModifiedData($user, $extra);
                elseif ($fieldName == 'generated_password')
                    $result = $userMeta->generatePassword($user);
                elseif ($fieldName == 'login_form')
                    $result = $userMeta->lgoinForm();
                elseif ($fieldName == 'lostpassword_form')
                    $result = (new ResetPassword())->lostPasswordForm;
                elseif ($fieldName == 'reset_password_link')
                    $result = ! empty($extra['reset_password_link']) ? $extra['reset_password_link'] : '';
                elseif (! empty($user->$fieldName))
                    $result = is_array($user->$fieldName) ? implode(',', $user->$fieldName) : $user->$fieldName;

                $replacements[$key] = apply_filters('user_meta_placeholder_output', $result, $fieldName, $user, $extra);
            }

            $data = str_replace($patterns, $replacements, $data);
        }

        return $data;
    }

    /**
     * Determine user
     *
     * @since 1.1.6
     *       
     * @param int $userID
     * @return \WP_user | false
     */
    function determineUser($userID = 0)
    {
        global $userMeta;

        if (empty($userID) && ! empty($_GET['user_id']) && $userMeta->isAdmin())
            $userID = (int) $_GET['user_id'];

        if (! empty($userID))
            $user = new \WP_User($userID);
        else
            $user = wp_get_current_user();

        if (! empty($user->ID))
            return $user;

        return false;
    }

    function getCustomFieldRegex()
    {
        global $userMeta;

        $fields = $userMeta->getData('fields');

        $rules = array();
        if (is_array($fields)) {
            foreach ($fields as $id => $field) {
                if ('custom' == @$field['field_type']) {
                    $custom = array();
                    $custom['regex'] = @$field['regex'];
                    $custom['alertText'] = @$field['error_text'];
                    $rules['umCustomField_' . $id] = $custom;
                }
            }
        }

        return json_encode($rules);
    }

    function checkPro()
    {
        global $userMeta;
        $userMeta->isPro = file_exists($userMeta->modelsPath . 'pro/ProAuthModel.php') ? true : false;
    }

    function uploadDir()
    {
        return File::uploadDir();
    }

    function determinFileDir($fileSubPath, $checkOnlyOneDir = false)
    {
        return File::getFilePath($fileSubPath, $checkOnlyOneDir);
    }

    function fieldListforDropdown($fields = array())
    {
        global $userMeta;

        if (empty($fields))
            $fields = $userMeta->getData('fields');

        $dropdown = array();
        if (! empty($fields) && is_array($fields)) {
            foreach ($fields as $id => $data) {
                if (! in_array($data['field_type'], array(
                    'page_heading',
                    'section_heading',
                    'html',
                    'captcha'
                )))
                    $dropdown[$id] = $data['field_title'];
            }
        }

        return $dropdown;
    }

    function removeAdditional($data)
    {
        if (! is_array($data))
            return $data;

        $additional = array(
            'action',
            'method_name',
            'init_max_id',
            'max_id',
            '_wpnonce',
            '_wp_http_referer'
        );

        foreach ($additional as $input) {
            if (isset($data[$input]))
                unset($data[$input]);
        }

        return $data;
    }

    /**
     * Create meta box
     * Should be called inside of 'metabox-holder' class div
     */
    function metaBox($title, $content, $deleteIcon = false, $isOpen = true)
    {
        global $userMeta;
        return $userMeta->render('metaBox', array(
            'title' => $title,
            'content' => $content,
            'deleteIcon' => $deleteIcon,
            'isOpen' => $isOpen
        ));
        return $html;
    }
}