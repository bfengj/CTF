<?php
namespace UserMeta;

class SettingsController
{

    function __construct()
    {
        add_action('wp_ajax_um_update_settings', array(
            $this,
            'ajaxUpdateSettings'
        ));
    }

    function ajaxUpdateSettings()
    {
        global $userMeta;
        $userMeta->verifyNonce(true);

        if (@$_REQUEST['action_type'] == 'authorize_pro') {
            $userMeta->updateProAccountSettings($_REQUEST);
            die();
        }

        $settings = $userMeta->arrayRemoveEmptyValue(@$_REQUEST);
        //@todo use sanitizeDeep
        //$settings = $userMeta->arrayRemoveEmptyValue(sanitizeDeep($_REQUEST));

        $extraFieldCount = @$settings['backend_profile']['field_count'];
        $extraFields = @$settings['backend_profile']['fields'];

        if (is_array($extraFields)) {
            foreach ($extraFields as $key => $val) {
                if ($key >= $extraFieldCount)
                    unset($settings['backend_profile']['fields'][$key]);
            }
        }

        unset($settings['action']);
        unset($settings['pf_nonce']);
        unset($settings['is_ajax']);
        unset($settings['backend_profile']['field_count']);

        $settings = apply_filters('user_meta_pre_configuration_update', $settings, 'settings');

        $userMeta->updateData('settings', $settings);

        echo $userMeta->showMessage(__('Settings successfully saved.', $userMeta->name));
        die();
    }
}