<?php
namespace UserMeta;

/**
 * This class contain several WP functions, those are already built in with wordpress core
 */
class LibBuiltinFunctionsModel
{

    /**
     *
     * @deprecated since 1.4. The method moves to ResetPassword::retrieve_password()
     *             Handles sending password retrieval email to user.
     *             Function function found in wp-login.php
     *            
     * @return bool|\WP_Error True: when finish. WP_Error on error
     */
    function retrieve_password($customLink = null)
    {
        global $userMeta;
        $errors = new \WP_Error();

        if (empty($_POST['user_login'])) {
            $errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.', $userMeta->name));
        } else {
            /**
             * We can not distinguish email only by '@', username could also contains '@'
             */
            $login = trim(sanitize_text_field($_POST['user_login']));
            $user_data = get_user_by('login', $login);
            if (! $user_data)
                $user_data = get_user_by('email', $login);
        }

        if ($userMeta->isHookEnable('lostpassword_post')) {
            do_action('lostpassword_post', $errors);
        }

        if ($errors->get_error_code()) {
            return $errors;
        }

        if (! $user_data) {
            $errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.', $userMeta->name));
            return $errors;
        }

        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;

        $key = $this->get_password_reset_key($user_data);
        if (is_wp_error($key)) {
            return $key;
        }

        $resetLink = $customLink ? $customLink : network_site_url('wp-login.php', 'login');
        $resetLink = add_query_arg(array(
            'action' => 'rp',
            'key' => $key,
            'login' => rawurlencode($user_login)
        ), $resetLink);

        $userMeta->prepareEmail('lostpassword', $user_data, array(
            'reset_password_link' => $resetLink,
            'key' => $key
        ));

        return true;
        // Codes for wp default email is not required.
    }

    /**
     *
     * @deprecated since 1.4
     *             Creates, stores, then returns a password reset key for user.
     *             Function function found in wp-includes/user.php
     *            
     * @since 1.3
     * @param \WP_User $user
     *            User to retrieve password reset key for.
     * @return string|\WP_Error Password reset key on success. WP_Error on error.
     */
    function get_password_reset_key($user)
    {
        global $wpdb, $wp_hasher, $userMeta;

        /**
         * Fires before a new password is retrieved.
         */
        if ($userMeta->isHookEnable('retrieve_password'))
            do_action('retrieve_password', $user->user_login);

        $allow = true;
        if (is_multisite() && is_user_spammy($user)) {
            $allow = false;
        }

        /**
         * Filters whether to allow a password to be reset.
         */
        if ($userMeta->isHookEnable('allow_password_reset'))
            $allow = apply_filters('allow_password_reset', $allow, $user->ID);

        if (! $allow) {
            return new \WP_Error('no_password_reset', __('Password reset is not allowed for this user'));
        } elseif (is_wp_error($allow)) {
            return $allow;
        }

        // Generate something random for a password reset key.
        $key = wp_generate_password(20, false);

        /**
         * Fires when a password reset key is generated.
         */
        if ($userMeta->isHookEnable('retrieve_password_key'))
            do_action('retrieve_password_key', $user->user_login, $key);

        // Now insert the key, hashed, into the DB.
        if (empty($wp_hasher)) {
            require_once ABSPATH . WPINC . '/class-phpass.php';
            $wp_hasher = new \PasswordHash(8, true);
        }
        $hashed = time() . ':' . $wp_hasher->HashPassword($key);
        $key_saved = $wpdb->update($wpdb->users, array(
            'user_activation_key' => $hashed
        ), array(
            'user_login' => $user->user_login
        ));
        if (false === $key_saved) {
            return new \WP_Error('no_password_key_update', __('Could not save password reset key to database.'));
        }

        return $key;
    }

    /**
     *
     * @deprecated since 1.4
     *             Handles resetting the user's password.
     *             Function found on wp-includes/user.php
     *             Diff: commenting wp_password_change_notification before WP-4.4
     *            
     * @param object $user
     *            The user
     * @param string $new_pass
     *            New password for the user in plaintext
     */
    function reset_password($user, $new_pass)
    {
        global $userMeta;

        if (version_compare(get_bloginfo('version'), '4.4.0', '>='))
            return reset_password($user, $new_pass);

        if ($userMeta->isHookEnable('password_reset'))
            do_action('password_reset', $user, $new_pass);

        wp_set_password($new_pass, $user->ID);
        update_user_option($user->ID, 'default_password_nag', false, true);
        // wp_password_change_notification( $user ); // commented wp default email
    }

    /**
     * Retrieve or display nonce hidden field for forms.
     *
     * Function found in wp-includes/functions.php
     * Diff: remove id attribute from hidden input.
     *
     * @return string Nonce field.
     */
    function wp_nonce_field($action = -1, $name = "_wpnonce", $referer = true, $echo = true)
    {
        $name = esc_attr($name);
        $nonce_field = '<input type="hidden" name="' . $name . '" value="' . wp_create_nonce($action) . '" />';

        if ($referer)
            $nonce_field .= wp_referer_field(false);

        if ($echo)
            echo $nonce_field;

        return $nonce_field;
    }
}