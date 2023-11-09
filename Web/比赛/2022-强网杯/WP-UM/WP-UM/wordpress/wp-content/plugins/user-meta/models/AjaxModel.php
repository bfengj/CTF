<?php
namespace UserMeta;

class AjaxModel
{

    function postInsertUser()
    {
        global $userMeta;
        $userMeta->verifyNonce();

        $umUserInsert = new UserInsert();

        return $umUserInsert->postInsertUserProcess();
    }

    /**
     * This method will call with um_login action
     */
    function postLogin()
    {
        global $userMeta;
        $userMeta->verifyNonce();
        try {
            return (new Login())->postLogin();
        } catch (\Throwable $t) {
            return $userMeta->printAjaxOutput($t->getMessage());
        } catch (\Exception $t) {
            return $userMeta->printAjaxOutput($t->getMessage());
        }
    }

    /**
     * This method has been called when user hit reset foget password button
     *
     * @return html|echo
     */
    function postLostpassword()
    {
        global $userMeta;
        $userMeta->verifyNonce();
        try {
            removeDisabledHooks();
            return (new ResetPassword())->postLostPassword();
        } catch (\Throwable $t) {
            return $userMeta->printAjaxOutput($t->getMessage());
        } catch (\Exception $t) {
            return $userMeta->printAjaxOutput($t->getMessage());
        }
    }

    function ajaxValidateUniqueField()
    {
        global $userMeta;
        $userMeta->verifyNonce(false);

        $status = false;
        if (! isset($_REQUEST['fieldId']) or ! $_REQUEST['fieldValue'])
            return;

        $id = ltrim(sanitize_text_field($_REQUEST['fieldId']), 'um_field_');
        $fields = $userMeta->getData('fields');

        if (isset($fields[$id])) {
            $fieldData = $userMeta->getFieldData($id, $fields[$id]);
            // $userMeta->isUserFieldAvailable is taking care of sanitize_*
            $status = $userMeta->isUserFieldAvailable($fieldData['field_name'], $_REQUEST['fieldValue']);

            if (! $status) {
                $msg = sprintf(__('%s already taken', $userMeta->name), sanitize_text_field($_REQUEST['fieldValue']));
                if (isset($_REQUEST['customCheck'])) {
                    echo "error";
                    die();
                }
            }

            $response[] = sanitize_key($_REQUEST['fieldId']);
            $response[] = isset($status) ? $status : true;
            $response[] = isset($msg) ? esc_html($msg) : null;

            echo json_encode($response);
        }

        die();
    }

    function ajaxFileUploader()
    {
        global $userMeta;
        $userMeta->verifyNonce();
        $config = [
            'extensions' => [
                'jpg',
                'jpeg',
                'png',
                'gif'
            ],
            'max_size' => 1 * 1024 * 1024,
            'overwrite' => false
        ];
        $config = apply_filters('user_meta_file_uploader_config', $config);

        // Access control
        if (empty($config)) {
            echo htmlspecialchars(json_encode([
                'error' => __('Permission denied.', $userMeta->name)
            ]), ENT_NOQUOTES);
            die();
        }

        $uploader = new FileUploader($config['extensions'], $config['max_size']);
        $result = $uploader->handleUpload($config['overwrite']);
        // to pass data through iframe you will need to encode all html tags
        echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        die();
    }

    function ajaxShowUploadedFile()
    {
        global $userMeta;
        $userMeta->verifyNonce();

        if (isset($_REQUEST['showimage'])) {
            if (isset($_REQUEST['imageurl'])) {
                // .. replaced with null for path traversal security reason from 2.4.3
                printf("<img src=\"%s\" />", esc_url(str_replace('..', '', $_REQUEST['imageurl'])));
            }
            die();
        }

        $field = File::determineField();
        if (! empty($field))
            echo (new File($field))->showFile();

        die();
    }

    function ajaxWithdrawLicense()
    {
        global $userMeta;
        $userMeta->verifyNonce(true);

        try {
            if (! is_super_admin()) {
                if (is_multisite()) {
                    throw new \Exception('Super admin account is needed to withdraw the pro license from network.');
                } else {
                    throw new \Exception('An admin account is needed to withdraw the pro license.');
                }
            }

            $status = $userMeta->withdrawLicense();

            if ($status === true) {
                echo $userMeta->showMessage(__('License has been withdrawn', $userMeta->name));
                echo $userMeta->jsRedirect($userMeta->adminPageUrl('settings', false));
            }
        } catch (\Exception $e) {
            echo $userMeta->showError($e->getMessage());
        }

        die();
    }

    function ajaxGeneratePage()
    {
        global $userMeta;
        check_admin_referer('generate_page');

        $pages = array(
            'login' => 'Login',
            'resetpass' => 'Reset password',
            'verify-email' => 'Email verification'
        );

        if (! empty($_REQUEST['page'])) {
            $page = sanitize_text_field($_REQUEST['page']);
            if (isset($pages[$page])) {
                $content = ('login' == $page) ? '[user-meta-login]' : '';
                $pageID = wp_insert_post(array(
                    'post_title' => $pages[$page],
                    'post_content' => $content,
                    'post_status' => 'publish',
                    'post_name' => $page,
                    'post_type' => 'page'
                ));
            }
        }

        if (! empty($pageID)) {
            $settings = $userMeta->getData('settings');
            switch ($page) {
                case 'login':
                    $settings['login']['login_page'] = $pageID;
                    $userMeta->updateData('settings', $settings);
                    wp_redirect($userMeta->adminPageUrl('settings', false) . '#um_settings_login');
                    exit();
                    break;

                case 'resetpass':
                    $settings['login']['resetpass_page'] = $pageID;
                    $userMeta->updateData('settings', $settings);
                    wp_redirect($userMeta->adminPageUrl('settings', false) . '#um_settings_login');
                    exit();
                    break;

                case 'verify-email':
                    $settings['registration']['email_verification_page'] = $pageID;
                    $userMeta->updateData('settings', $settings);
                    wp_redirect($userMeta->adminPageUrl('settings', false) . '#um_settings_registration');
                    exit();
                    break;
            }
        }
        wp_redirect($userMeta->adminPageUrl('settings', false));
        exit();
    }

    function ajaxSaveAdvancedSettings()
    {
        global $userMeta;
        $userMeta->checkAdminReferer(__FUNCTION__);

        if (! isset($_REQUEST))
            $userMeta->showError(__('Error occurred while updating', $userMeta->name));

        $data = $userMeta->arrayRemoveEmptyValue($_REQUEST);
        $data = $userMeta->removeNonArray($data);

        $userMeta->updateData('advanced', stripslashes_deep($data));
        echo $userMeta->showMessage(__('Successfully saved.', $userMeta->name));

        die();
    }

    function ajaxmodifyNoticeDate(){
        check_admin_referer('modify_notice_date');

        if (! empty($_REQUEST['feedback'])) {
            $feedback = sanitize_text_field($_REQUEST['feedback']);
            switch ($feedback){
                case 'later':
                    update_option('user_meta_feedback', ['date' => time() + 2*WEEK_IN_SECONDS, 'status' => 'later']);
                    wp_safe_redirect($_SERVER['HTTP_REFERER']);
                    exit();
                case 'close':
                    update_option('user_meta_feedback', ['date' => time(), 'status' => 'close']);
                    wp_safe_redirect($_SERVER['HTTP_REFERER']);
                    exit();
            }
        }

    }

    function ajaxTestMethod()
    {
        global $userMeta;
        echo 'Working...';
        $userMeta->dump($_REQUEST);
        die();
    }
}