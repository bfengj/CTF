<?php
namespace UserMeta;

class VersionUpdateController
{

    function __construct()
    {
        add_action('admin_menu', [
            $this,
            'init'
        ], 15);
        add_action('network_admin_menu', [
            $this,
            'init'
        ], 15);
        add_action('admin_init', [
            $this,
            'deleteLiteVersion'
        ]);

        add_action('admin_notices', [
            $this,
            'showAdminNotices'
        ]);
    }

    public function init()
    {
        echoThrowable(function () {
            $this->_init();
        });
    }

    /**
     * Check if data update is needed after version update
     */
    private function _init()
    {
        global $userMeta;

        $history = $userMeta->getData('history', true);
        if (empty($history)) {
            if (is_multisite())
                $history = get_option('user_meta_history');
        }

        $lastVersion = null;
        if (! empty($history)) {
            if (isset($history['version']['last_version']))
                $lastVersion = $history['version']['last_version'];
        } else
            $history = array();

        if (version_compare($userMeta->version, $lastVersion, '<='))
            return;

        // Determine last version and run data update
        if ($lastVersion)
            $this->runUpgrade($lastVersion);
        else {
            if (get_option('user_meta_fields'))
                $this->runUpgrade('1.1.0');
            elseif (get_option('user_meta_field'))
                $this->runUpgrade('1.0.3');
        }

        // Saveing last version data
        $history['version']['last_version'] = $userMeta->version;
        $history['version'][$userMeta->version] = [
            'timestamp' => time()
        ];

        $userMeta->updateData('history', $history, true);
        do_action('user_meta_after_version_update', $lastVersion);
        // nocache_headers();
    }

    /**
     * Run upgrade one by one
     */
    private function runUpgrade($versionFrom)
    {
        global $userMeta;
        if (in_array($versionFrom, [
            '1.0.0',
            '1.0.1',
            '1.0.2',
            '1.0.3'
        ])) {
            try {
                $this->upgradeFrom_1_0_3_To_1_1_0();
                $this->upgradeAvatarFrom_1_0_3_To_1_1_0();
            } catch (\Exception $e) {}
            $versionFrom = '1.1.0';
        }
        if (in_array($versionFrom, [
            '1.0.5',
            '1.1.0',
            '1.1.1',
            '1.1.2rc1',
            '1.1.2rc2'
        ])) {
            try {
                $this->upgradeFrom_1_1_0_To_1_1_2();
            } catch (\Exception $e) {}
        }

        if (version_compare($versionFrom, '1.1.5rc3', '<')) {
            try {
                $userMeta->upgradeTo_1_1_5();
                $this->upgrade_to_1_1_5($versionFrom);
            } catch (\Exception $e) {}
        }
        if (version_compare($versionFrom, '1.1.6rc2', '<')) {
            try {
                $this->upgrade_to_1_1_6();
            } catch (\Exception $e) {}
        }
        if (version_compare($versionFrom, '1.1.6', '<=')) {
            try {
                $this->upgrade_to_1_1_7();
            } catch (\Exception $e) {}
        }
        if (version_compare($versionFrom, '1.3rc1', '<')) {
            try {
                $userMeta->upgradeTo_1_3($versionFrom);
            } catch (\Exception $e) {}
        }
        if (version_compare($versionFrom, '1.4rc1', '<')) {
            try {
                $this->upgradeTo_1_4();
            } catch (\Exception $e) {}
        }
        if (version_compare($versionFrom, '2.0', '<')) {
            try {
                $this->upgradeTo_2_0();
            } catch (\Exception $e) {}
        }
        $userMeta->notifyVersionUpdate();
    }

    /**
     * function to run when UMP updated to 2.0
     * Move reCaptcha keys to fields and forms from the settings.
     */
    private function upgradeTo_2_0(){
        global $userMeta;
        $fields = $userMeta->getData('fields');
        $settings = $userMeta->getData('settings');
        $forms = $userMeta->getData('forms');

        if ( empty( $settings['general']['recaptcha_public_key'] ) && empty( $settings['general']['recaptcha_private_key'] ) )
            return;

        $v2SiteKey = !empty( $settings['general']['recaptcha_public_key'] ) ? $settings['general']['recaptcha_public_key'] : '';
        $v2SecretKey = !empty( $settings['general']['recaptcha_private_key'] ) ? $settings['general']['recaptcha_private_key'] : '';

        if (is_array($fields)) {
            foreach ($fields as $key => $field) {
                if ( !empty($field['field_type']) && $field['field_type'] == 'captcha' ) {
                    $fields[$key]['captcha_version'] = 'v2';
                    $fields[$key]['v2_site_key'] = $v2SiteKey;
                    $fields[$key]['v2_secret_key'] = $v2SecretKey;
                }
            }

            $userMeta->updateData('fields', $fields);
        }

        if (is_array($forms)) {
            foreach ($forms as $key => $val) {
                foreach( $val['fields'] as $id => $field ){
                    if ( !empty($field['field_type']) && $field['field_type'] == 'captcha' ) {
                        $forms[$key]['fields'][$id]['captcha_version'] = 'v2';
                        $forms[$key]['fields'][$id]['v2_site_key'] = $v2SiteKey;
                        $forms[$key]['fields'][$id]['v2_secret_key'] = $v2SecretKey;
                    }
                }
            }

            $userMeta->updateData('forms', $forms);
        }
    }

    /**
     * Migrate user-meta-advanced data
     * to: hookswitch, override-email and edit-default-form
     */
    private function upgradeTo_1_4()
    {
        global $userMeta;
        includeCapabilities();
        $advanced = $userMeta->getData('advanced');

        if (! empty($advanced['integration']) && ! empty($advanced['integration']['ump_wp_hooks'])) {
            $userMeta->updateData('addon_hookswitch', [
                'active_hooks' => $advanced['integration']['ump_wp_hooks']
            ]);
        }

        if (! empty($advanced['integration'])) {
            $data = [];
            if (! empty($advanced['integration']['override_registration_email']))
                $data['override_registration_email'] = true;
            if (! empty($advanced['integration']['override_resetpass_email']))
                $data['override_resetpass_email'] = true;
            $userMeta->updateData('addon_override-email', $data);
        }

        if (! empty($advanced['views'])) {
            $userMeta->updateData('addon_edit-default-form', $advanced['views']);
        }
    }

    private function upgrade_to_1_1_7()
    {
        global $userMeta;

        $fields = $userMeta->getData('fields');
        if (is_array($fields)) {
            foreach ($fields as $key => $field) {
                if (! isset($field['field_type']))
                    continue;

                if ($field['field_type'] == 'password') {
                    $fields[$key]['field_type'] = 'custom';
                    $fields[$key]['input_type'] = 'password';
                } elseif ($field['field_type'] == 'email') {
                    $fields[$key]['field_type'] = 'custom';
                    $fields[$key]['input_type'] = 'email';
                } elseif ($field['field_type'] == 'user_avatar' || $field['field_type'] == 'file') {
                    if (! empty($field['crop_image']))
                        $fields[$key]['resize_image'] = '1';
                }
            }

            $userMeta->updateData('fields', $fields);
        }
    }

    private function upgrade_to_1_1_6()
    {
        global $userMeta;

        $data = $userMeta->getData('settings');
        $data['login']['disable_registration_link'] = true;
        $userMeta->updateData('settings', $data);
    }

    private function upgrade_to_1_1_5($versionFrom)
    {
        global $userMeta;

        $pageName = apply_filters('user_meta_front_execution_page', 'resetpass');
        $pageID = $userMeta->postIDbyPostName($pageName);
        if (! empty($pageID)) {

            // Set resetpass page to ['login']['resetpass_page'] and ['registration']['email_verification_page']
            $settings = $userMeta->getData('settings');
            if (empty($settings['login']['resetpass_page']))
                $settings['login']['resetpass_page'] = $pageID;
            if (empty($settings['registration']['email_verification_page']))
                $settings['registration']['email_verification_page'] = $pageID;
            $userMeta->updateData('settings', $settings);

            // set resetpass page content to null
            if ($versionFrom != '1.1.5rc2') {
                $resetpassPage = array(
                    'ID' => $pageID,
                    'post_content' => ''
                );
                wp_update_post($resetpassPage);
            }
        }

        // Check default language is other than english or wpml is active
        if (get_bloginfo('language') != 'en-US' || function_exists('icl_object_id'))
            update_option('user_meta_show_translation_update_notice', 1);

        // Reset cache
        $userMeta->updateData('cache', null);

        // Create index.html file
        $uploads = $userMeta->uploadDir();
        if (file_exists($uploads['path']) && is_dir($uploads['path']) && ! file_exists($uploads['path'] . 'index.html'))
            touch($uploads['path'] . 'index.html');

        if (! wp_next_scheduled('user_meta_schedule_event'))
            wp_schedule_event(current_time('timestamp'), 'daily', 'user_meta_schedule_event');
    }

    /**
     * Distribute one page settings data to multipart array
     */
    private function upgradeFrom_1_1_0_To_1_1_2()
    {
        global $userMeta;

        $roles = $userMeta->getRoleList();
        if (! $roles) {
            $roles = array(
                'administrator' => 'Administrator',
                'editor' => 'Editor',
                'author' => 'Author',
                'contributor' => 'Contributor',
                'subscriber' => 'Subscriber'
            );
        }

        /**
         * Converting Settings
         */
        $data = $userMeta->getData('settings'); // Retrieve old settings data.
        $defaultLoginSettings = $userMeta->defaultSettingsArray('login');

        $settings['general']['profile_page'] = @$data['profile_page'];
        $settings['general']['profile_in_admin'] = @$data['profile_in_admin'];
        $settings['general']['recaptcha_public_key'] = @$data['recaptcha_public_key'];
        $settings['general']['recaptcha_private_key'] = @$data['recaptcha_private_key'];

        $settings['login']['login_by'] = @$data['login_by'];
        $settings['login']['login_page'] = @$data['login_page'];
        $settings['login']['disable_ajax'] = @$data['disable_ajax_login'];

        $settings['login']['login_form'] = @$defaultLoginSettings['login_form'];
        foreach ($roles as $roleKey => $roleVal)
            $settings['login']['loggedin_profile'][$roleKey] = $defaultLoginSettings['loggedin_profile']['subscriber'];

        $userMeta->updateData('settings', $settings);

        /**
         * Converting Emails
         */
        $data = get_option('user-meta-email');

        foreach ($roles as $key => $val) {
            $emails['registration']['user_email'][$key]['subject'] = str_replace(array(
                '%BLOG_TITLE%',
                '%BLOG_URL%'
            ), array(
                '%site_title%',
                '%site_url%'
            ), @$data['user_email']['subject']);
            $emails['registration']['user_email'][$key]['body'] = str_replace(array(
                '%BLOG_TITLE%',
                '%BLOG_URL%'
            ), array(
                '%site_title%',
                '%site_url%'
            ), @$data['user_email']['body']);
            $emails['registration']['admin_email'][$key]['subject'] = str_replace(array(
                '%BLOG_TITLE%',
                '%BLOG_URL%'
            ), array(
                '%site_title%',
                '%site_url%'
            ), @$data['admin_email']['subject']);
            $emails['registration']['admin_email'][$key]['body'] = str_replace(array(
                '%BLOG_TITLE%',
                '%BLOG_URL%'
            ), array(
                '%site_title%',
                '%site_url%'
            ), @$data['admin_email']['body']);
        }
        $emails['registration']['user_email']['um_disable'] = @$data['user_email']['enable'] ? '' : true;
        $emails['registration']['admin_email']['um_disable'] = @$data['admin_email']['enable'] ? '' : true;

        $userMeta->updateData('emails', $emails);
    }

    private function upgradeFrom_1_0_3_To_1_1_0()
    {
        global $userMeta;

        $cache = get_option('user_meta_cache');
        if (isset($cache['upgrade']['1.0.3']['fields_upgraded']))
            return;

        // Check if upgrade is needed
        $fields = $userMeta->getData('fields');
        $exists = false;
        if ($fields) {
            if (is_array($fields)) {
                foreach ($fields as $value) {
                    if (isset($value['field_type']))
                        $exists = true;
                }
            }
        }
        if ($exists)
            return;

        $i = 0;
        // get Default fields
        $prevDefaultFields = get_option('user_meta_field_checked');
        if ($prevDefaultFields) {
            foreach ($prevDefaultFields as $fieldName => $noData) {
                if ($fieldName == 'avatar')
                    $fieldName = 'user_avatar';
                $fieldData = $userMeta->getFields('key', $fieldName);
                if (! $fieldData)
                    continue;
                $i ++;
                $newField[$i]['field_title'] = isset($fieldData['title']) ? $fieldData['title'] : null;
                $newField[$i]['field_type'] = $fieldName;
                $newField[$i]['title_position'] = 'top';
            }
        }

        // get meta key
        $prevFields = get_option('user_meta_field');
        if ($prevDefaultFields) {
            foreach ($prevFields as $fieldData) {
                if (! $fieldData)
                    continue;
                $i ++;
                $fieldType = $fieldData['meta_type'] == 'dropdown' ? 'select' : 'text';
                $newField[$i]['field_title'] = isset($fieldData['meta_label']) ? $fieldData['meta_label'] : null;
                $newField[$i]['field_type'] = $fieldType;
                $newField[$i]['title_position'] = 'top';
                $newField[$i]['description'] = isset($fieldData['meta_description']) ? $fieldData['meta_description'] : null;
                $newField[$i]['meta_key'] = isset($fieldData['meta_key']) ? $fieldData['meta_key'] : null;
                $newField[$i]['required'] = $fieldData['meta_required'] == 'yes' ? 'on' : null;
                if (isset($fieldData['meta_option'])) {
                    if ($fieldData['meta_option'] and is_string($fieldData['meta_option'])) {
                        $options = $userMeta->arrayRemoveEmptyValue(unserialize($fieldData['meta_option']));
                        if ($options)
                            $newField[$i]['options'] = implode(',', $options);
                    }
                }
                $newField[$i] = $userMeta->arrayRemoveEmptyValue($newField[$i]);
            }
        }

        // Defining Form data
        $newForm['profile']['form_key'] = 'profile';
        $n = 0;
        while ($n < $i) {
            $n ++;
            $newForm['profile']['fields'][] = $n;
        }

        if (isset($newField)) {
            $userMeta->updateData('fields', $newField);
            $userMeta->updateData('forms', $newForm);
            $cache['upgrade']['1.0.3']['fields_upgraded'] = true;
            update_option('user_meta_cache', $cache);
        }

        return true;
    }

    private function upgradeAvatarFrom_1_0_3_To_1_1_0()
    {
        global $userMeta;

        $cache = get_option('user_meta_cache');
        if (isset($cache['upgrade']['1.0.3']['avatar_upgraded']))
            return;

        $users = get_users(array(
            'meta_key' => 'user_meta_avatar'
        ));
        if (! $users)
            return;

        $uploads = wp_upload_dir();
        foreach ($users as $user) {
            $oldUrl = get_user_meta($user->ID, 'user_meta_avatar', true);
            if ($oldUrl) {
                $newPath = str_replace($uploads['baseurl'], '', $oldUrl);
                update_user_meta($user->ID, 'user_avatar', $newPath);
            }
        }

        $cache['upgrade']['1.0.3']['avatar_upgraded'] = true;
        update_option('user_meta_cache', $cache);

        return true;
    }

    public function showAdminNotices()
    {
        global $userMeta;

        if (get_option('user_meta_show_translation_update_notice')) {
            $url = $userMeta->adminPageUrl('settings', false);
            $url = add_query_arg(array(
                'action_type' => 'notice',
                'action_name' => 'dismiss_translation_notice'
            ), $url);
            echo '<div class="updated fade"><p>' . __('Some texts of UserMetaPro have been updated. If you are using your site in any other languages than english, please update your translation.');
            echo ' <a href="' . $url . '" class="button">' . __('Dismiss', $userMeta->name) . '</a></p></div>';
        }
    }

    /**
     * Deactivate and delete the lite version of the plugin.
     */
    public function deleteLiteVersion()
    {
        echoThrowable(function () {
            global $userMeta;
            if ($userMeta->isPro && file_exists(WP_PLUGIN_DIR . '/user-meta/user-meta.php')) {
                deactivate_plugins('user-meta/user-meta.php', true);
                delete_plugins([
                    'user-meta/user-meta.php'
                ]);
            }
        });
    }
}
