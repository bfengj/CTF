<?php
namespace UserMeta;

class AdminPagesController
{

    function __construct()
    {
        add_action('admin_menu', array(
            $this,
            'menuItem'
        ));
        add_action('admin_notices', array(
            $this,
            'umAdminNotices'
        ));
    }

    function menuItem()
    {
        global $userMeta, $umAdminPages;
        includeCapabilities();

        $parentSlug = 'user-meta';
        $isPro = $userMeta->isPro();
        // $pluginTitle = $isPro ? 'User Meta Pro' : 'User Meta';
        $pluginTitle = 'User Meta';

        // Top Level Menu
        add_menu_page($pluginTitle, $pluginTitle, 'user_meta_admin', $parentSlug, [
            $this,
            'forms_init'
        ], $userMeta->assetsUrl . 'images/ump-icon.png');

        $pages = $userMeta->adminPages();

        foreach ($pages as $key => $page) {
            if (! $isPro && empty($page['is_free']))
                continue;
            if ($isPro && ! empty($page['not_in_pro']))
                continue;
            $menuTitle = (! $isPro && ! $page['is_free']) ? '<span style="opacity:.5;filter:alpha(opacity=50);">' . $page['menu_title'] . '</span>' : $page['menu_title'];
            $callBack = ! empty($page['callback']) ? $page['callback'] : array(
                $this,
                $key . '_init'
            );
            $hookName = add_submenu_page($parentSlug, $page['page_title'], $menuTitle, 'user_meta_admin', $page['menu_slug'], $callBack);
            add_action('load-' . $hookName, array(
                $this,
                'onLoadUmAdminPages'
            ));
            $pages[$key]['hookname'] = $hookName;
        }

        $umAdminPages = $pages;

        add_filter('plugin_action_links_' . $userMeta->pluginSlug, array(
            &$this,
            'pluginSettingsMenu'
        ));
    }

    function onLoadUmAdminPages()
    {
        do_action('user_meta_load_admin_pages');
    }

    function pluginSettingsMenu($links)
    {
        global $userMeta;
        $settings_link = '<a href="' . get_admin_url(null, 'admin.php?page=user-meta-settings') . '">' . __('Settings', $userMeta->name) . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    function umAdminNotices()
    {
        global $current_screen;
        if ($current_screen->parent_base == 'user-meta')
            do_action('user_meta_admin_notices');
    }

    function forms_init()
    {
        global $userMeta;
        $userMeta->enqueueScripts([
            'jquery-ui-sortable',
            'font-awesome',
            'user-meta',
            'user-meta-admin',
            'bootstrap',
            'bootstrap-multiselect'
        ]);
        $userMeta->runLocalization();

        if (empty($_REQUEST['action']))
            return $userMeta->render('formsEditorPage', [], 'forms');

        switch ($_REQUEST['action']) {
            case 'new':
                $userMeta->render('editForm', [
                    'formName' => ''
                ], 'forms');
                break;

            case 'edit':
                $userMeta->render('editForm', [
                    'formName' => esc_html(filter_input(INPUT_GET, 'form'))
                ], 'forms');
                break;

            case 'duplicate':
            case 'delete':
                $userMeta->render('formsEditorPage', [], 'forms');
                break;
        }
    }

    function fields_init()
    {
        global $userMeta;
        $userMeta->enqueueScripts(array(
            'jquery-ui-sortable',
            'font-awesome',
            'bootstrap',
            'bootstrap-multiselect',
            'user-meta',
            'user-meta-admin'
        ));
        $userMeta->runLocalization();

        $userMeta->render('fieldsEditorPage', array(), 'fields');
    }

    function email_notification_init()
    {
        global $userMeta;
        $userMeta->enqueueScripts(array(
            'jquery-ui-core',
            'jquery-ui-tabs',
            'jquery-ui-all',
            'font-awesome',
            'bootstrap',

            'user-meta',
            'user-meta-admin'
        ));
        $userMeta->runLocalization();

        $data = array(
            'registration' => $userMeta->getEmailsData('registration'),
            'email_verification' => $userMeta->getEmailsData('email_verification'),
            'admin_approval' => $userMeta->getEmailsData('admin_approval'),
            'activation' => $userMeta->getEmailsData('activation'),
            'deactivation' => $userMeta->getEmailsData('deactivation'),
            'lostpassword' => $userMeta->getEmailsData('lostpassword'),
            'reset_password' => $userMeta->getEmailsData('reset_password'),
            'profile_update' => $userMeta->getEmailsData('profile_update')
        );

        $userMeta->renderPro('emailNotificationPage', array(
            'data' => $data,
            'roles' => $userMeta->getRoleList()
        ), 'email');
    }

    function export_import_init()
    {
        global $userMeta;
        $userMeta->enqueueScripts(array(
            'jquery-ui-core',
            'jquery-ui-sortable',
            'jquery-ui-draggable',
            'jquery-ui-droppable',
            'jquery-ui-datepicker',
            'jquery-ui-dialog',
            'jquery-ui-progressbar',

            'font-awesome',
            'bootstrap',
            'bootstrap-multiselect',

            'user-meta',
            'user-meta-admin',
            'jquery-ui-all',
            'fileuploader',
            'opentip'
        ));
        $userMeta->runLocalization();

        $cache = $userMeta->getData('cache');
        $csvCache = @$cache['csv_files'];

        // importPage maxSize: 20M
        $userMeta->renderPro('importExportPage', array(
            'csvCache' => $csvCache,
            'maxSize' => (20 * 1024 * 1024)
        ), 'exportImport');
    }

    public function addons_init()
    {
        global $userMeta;
        $userMeta->enqueueScripts([
            'font-awesome',
            'bootstrap',
            'bootstrap-switch',
            'bootstrap-multiselect',
            'user-meta',
            'user-meta-admin'
        ], [
            'bootstrap',
            'bootstrap-multiselect'
        ]);
        $userMeta->runLocalization();

        $userMeta->renderPro('addonsPage', [], 'addons');
    }

    function settings_init()
    {
        global $userMeta;
        self::moreExecution();

        $userMeta->enqueueScripts(array(
            'jquery-ui-core',
            'jquery-ui-widget',
            'jquery-ui-mouse',
            'jquery-ui-sortable',
            'jquery-ui-draggable',
            'jquery-ui-droppable',
            'jquery-ui-accordion',
            'jquery-ui-tabs',
            'jquery-ui-all',

            'font-awesome',
            'bootstrap',

            'user-meta',
            'user-meta-admin',
            'validationEngine'
        ));
        $userMeta->runLocalization();

        $settings = $userMeta->getData('settings');
        $forms = $userMeta->getData('forms');
        $fields = $userMeta->getData('fields');
        $default = $userMeta->defaultSettingsArray();

        $userMeta->render('settingsPage', array(
            'settings' => $settings,
            'forms' => $forms,
            'fields' => $fields,
            'default' => $default
        ));
    }

    function pro_ads_init()
    {
        global $userMeta;
        $userMeta->enqueueScripts([
            'bootstrap'
        ]);
        $userMeta->renderPro('proAdsPage');
    }

    function advanced_init()
    {
        global $userMeta;
        $userMeta->enqueueScripts(array(
            'jquery-ui-core',
            'jquery-ui-tabs',
            'jquery-ui-all',

            'user-meta',
            'user-meta-admin',
            'bootstrap',
            'bootstrap-multiselect',
            'multiple-select'
        ));
        $userMeta->runLocalization();

        $userMeta->renderPro('advancedPage', array(
            'advanced' => $userMeta->getData('advanced')
        ), 'advanced');
    }

    function moreExecution()
    {
        $actionType = ! empty($_GET['action_type']) ? sanitize_text_field($_REQUEST['action_type']) : false;
        if ($actionType == 'notice') {
            if (! empty($_GET['action_name']))
                $_GET['action_name'] == 'dismiss_translation_notice' ? delete_option('user_meta_show_translation_update_notice') : false;
        }
    }
}