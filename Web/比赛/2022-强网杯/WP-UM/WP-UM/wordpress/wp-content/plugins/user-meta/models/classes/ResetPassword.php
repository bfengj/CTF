<?php
namespace UserMeta;

/**
 * Handle all reset password processes
 *
 * @author Khaled Hossain
 * @since 1.2.1
 */
class ResetPassword
{

    /**
     * Showing lost passord form
     *
     * @return string html
     */
    public function lostPasswordForm($config = [])
    {
        global $userMeta;
        $methodName = 'Lostpassword';

        $html = null;
        $html .= doActionHtml('login_form_lostpassword');
        if (empty($config))
            $config = $userMeta->getExecutionPageConfig('lostpassword');

        /**
         * Showing error when using by shortcode although disable_lostpassword is checked
         */
        $login = $userMeta->getSettings('login');
        if (! empty($login['disable_lostpassword']))
            return $userMeta->showError(__('Password reset is currently not allowed.', $userMeta->name));

        $html .= $userMeta->renderPro('lostPasswordForm', array(
            'config' => $config,
            'disableAjax' => ! empty($login['disable_ajax']) ? true : false,
            'methodName' => $methodName
        ), 'login');

        return $html;
    }

    /**
     * Validate key, showing form for reset password and process reset password request
     *
     * @param array $config
     * @return string html
     */
    public function resetPassword($config = [])
    {
        global $userMeta;
        if (empty($config))
            $config = $userMeta->getExecutionPageConfig('resetpass');

        $html = null;
        $html .= doActionHtml('login_form_resetpass');
        $html .= doActionHtml('login_form_rp');
        $user = check_password_reset_key(sanitize_text_field(@$_GET['key']), sanitize_text_field(rawurldecode(@$_GET['login'])));

        $errors = new \WP_Error();
        if (! is_wp_error($user)) {
            if (isset($_POST['pass1']) && $_POST['pass1'] != $_POST['pass2'])
                $errors->add('password_reset_mismatch', $userMeta->getMsg('password_reset_mismatch'));
            // if ($userMeta->isHookEnable('validate_password_reset'))
            do_action('validate_password_reset', $errors, $user);
            if ((! $errors->get_error_code()) && isset($_POST['pass1']) && ! empty($_POST['pass1'])) {
                reset_password($user, $_POST['pass1']);
                do_action('user_meta_after_reset_password', $user);
                $html .= $userMeta->showMessage($userMeta->getMsg('password_reseted'));

                $redirect = ! empty($config['redirect']) ? $config['redirect'] : null;
                $redirect = apply_filters('user_meta_reset_password_redirect', $redirect, $user);
                if (! empty($redirect))
                    $html .= $userMeta->jsRedirect($redirect, 5);

                return $html;
            }
        } else {
            if ($user->get_error_code() == 'invalid_key')
                return $userMeta->showError($userMeta->getMsg('invalid_key'), false);
            elseif ($user->get_error_code() == 'expired_key')
                return $userMeta->showError($userMeta->getMsg('expired_key'), false);
            else
                return $userMeta->showError($user->get_error_message(), false);
        }

        return $userMeta->renderPro('resetPasswordForm', array(
            'config' => $config,
            'user' => $user,
            'errors' => $errors
        ), 'login');
    }

    /**
     * Implementation of built-in function
     * Handles sending password retrieval email to user.
     * Function found in wp-login.php
     *
     * @return bool|\WP_Error True: when finish. WP_Error on error
     */
    private function retrieve_password($customLink = null)
    {
        global $userMeta;
        $errors = new \WP_Error();

        if (empty($_POST['user_login'])) {
            $errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.', $userMeta->name));
        } else {
            /**
             * We can not distinguish email only by '@', username could also contains '@'
             */
            $login = trim($_POST['user_login']);
            $user_data = get_user_by('login', $login);
            if (! $user_data)
                $user_data = get_user_by('email', $login);
        }

        // if ($userMeta->isHookEnable('lostpassword_post')) {
        do_action('lostpassword_post', $errors);
        // }

        if ($errors->get_error_code())
            return $errors;

        if (! $user_data) {
            $errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.', $userMeta->name));
            return $errors;
        }

        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;
        $key = get_password_reset_key($user_data);
        if (is_wp_error($key)) {
            return $key;
        }

        $resetLink = $customLink ? $customLink : network_site_url('wp-login.php', 'login');
        $resetLink = add_query_arg(array(
            'action' => 'rp',
            'key' => $key,
            'login' => rawurlencode($user_login)
        ), $resetLink);

        if ($userMeta->isPro()) {
            $userMeta->prepareEmail('lostpassword', $user_data, [
                'reset_password_link' => $resetLink,
                'key' => $key
            ]);
        } else {
            $emailData = $userMeta->defaultEmailsArray('lostpassword')['user_email'];
            $title = $userMeta->convertUserContent($user_data, $emailData['subject']);
            $message = $userMeta->convertUserContent($user_data, $emailData['body'], [
                'reset_password_link' => $resetLink,
                'key' => $key
            ]);
            $title = apply_filters('retrieve_password_title', $title, $user_login, $user_data);
            $message = apply_filters('retrieve_password_message', $message, $key, $user_login, $user_data);
            if ($message && ! wp_mail($user_email, wp_specialchars_decode($title), $message)) {
                $errors->add('email_not_sent', __('The email could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.'));
                return $errors;
            }
        }

        return true;
    }

    /**
     * Response request of lostPasswordForm
     *
     * This method will call by POST method
     * Called by AjaxModel::postLostpassword()
     */
    public function postLostPassword()
    {
        global $userMeta;
        $settings = $userMeta->getSettings('login');
        if (! empty($settings['resetpass_page'])) {
            $pageID = (int) $settings['resetpass_page'];
            $permalink = get_permalink($pageID);
        }

        $output = doActionHtml('login_form_retrievepassword');
        $resetPassLink = ! empty($permalink) ? $permalink : null;
        $response = $this->retrieve_password($resetPassLink);

        if ($response === true) {
            $output .= $userMeta->showMessage($userMeta->getMsg('check_email_for_link'), 'success', false);
            $redirect_to = ! empty($_POST['redirect_to']) ? esc_url($_POST['redirect_to']) : '';

            // if ($userMeta->isHookEnable('lostpassword_redirect'))
            $redirect_to = apply_filters('lostpassword_redirect', $redirect_to);

            if (! empty($redirect_to))
                $output .= $userMeta->jsRedirect($redirect_to, 5);
        } elseif (is_wp_error($response))
            $output .= $userMeta->showError($response->get_error_message(), false);

        return $userMeta->printAjaxOutput($output);
    }
}