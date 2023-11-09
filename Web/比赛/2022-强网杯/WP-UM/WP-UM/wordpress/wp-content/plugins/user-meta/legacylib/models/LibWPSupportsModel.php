<?php
namespace UserMeta;

class LibWPSupportsModel
{

    /**
     * Generate nonce field
     */
    function nonceField()
    {
        global $pfInstance;
        return $pfInstance->wp_nonce_field($pfInstance->settingsArray('nonce'), 'pf_nonce', true, false);
    }

    function verifyNonce($adminOnly = false)
    {
        global $pfInstance;

        if (empty($_REQUEST['pf_nonce']))
            die('Security check: empty nonce');

        $nonce = sanitize_text_field($_REQUEST['pf_nonce']);
        $nonceText = $pfInstance->settingsArray('nonce');
        if (! wp_verify_nonce($nonce, $nonceText))
            die('Security check: nonce missmatch');

        if ($adminOnly) {
            if (! isUserMetaAdmin())
                die(__('Security check: admin only', $pfInstance->name));
        }

        return true;
    }

    function checkAdminReferer($methodName)
    {
        check_admin_referer('pf' . ucwords(str_replace('ajax', '', $methodName)));

        if (! (self::isAdmin()))
            die(__('Security check: admin only'));

        return true;
    }

    function methodName($methodName, $combind = false)
    {
        global $pfInstance;

        $html = null;
        $html .= "<input type=\"hidden\" name=\"method_name\" value=\"$methodName\" />";
        if ($combind) {
            $html .= $pfInstance->wp_nonce_field('pf' . ucwords($methodName), '_wpnonce', true, false);
        }

        return $html;
    }

    // Verify methodName
    function verifyAdminNonce($methodName)
    {
        if (empty($_REQUEST['_wpnonce']))
            die('Security check: empty nonce');

        $nonce = sanitize_text_field($_REQUEST['_wpnonce']);
        $nonceText = 'pf' . ucwords(str_replace('ajax', '', $methodName));

        if (! wp_verify_nonce($nonce, $nonceText))
            die('Security check: nonce missmatch');

        if (! isUserMetaAdmin())
            die(__('Security check: admin only'));

        return true;
    }

    /**
     * Retrieve the current user_id
     *
     * @since 1.1.3
     * @return int
     */
    function userID()
    {
        return get_current_user_id();
    }

    /**
     *
     * @deprecated use \UserMeta\isAdmin() instead
     * @param int $userID
     * @return boolean
     */
    function isAdmin($userID = null)
    {
        return isAdmin();
    }

    function isAdminSection()
    {
        if (! is_admin())
            return false;

        $ajaxurl = admin_url('admin-ajax.php');
        $pos = strpos($ajaxurl, sanitize_text_field($_SERVER['REQUEST_URI']));
        return ($pos === false) ? true : false;
    }

    function getRoleList($includeNoRole = false)
    {
        global $wp_roles, $pfInstance;
        $roles = $wp_roles->role_names;
        if ($includeNoRole)
            $roles['none'] = __("No role defined", $pfInstance->name);
        return $roles;
    }

    function getUserRole($userID)
    {
        $user = new \WP_User($userID);
        if (is_wp_error($user))
            return false;

        $user_roles = @$user->roles;
        if (is_array($user_roles))
            return array_shift($user_roles);

        return false;
    }

    /**
     * Retrieve the current user object if user logged in, false if not logged in.
     *
     * @since 1.1.3
     *       
     * @return \WP_User Current user WP_User object | bool false if not logged in.
     */
    function getCurrentUser()
    {
        $user = wp_get_current_user();

        if (empty($user->ID))
            return false;

        return $user;
    }

    /**
     * Check value is unique for given field.
     *
     * If more then one user found with given field and value, this method will return true for first user false for all other users.
     *
     * @param string $fieldName
     * @param mixed $fieldValue
     * @param string $comparingID
     *            : if no $comparingID is given, $user_ID or $_REQUEST['user_id'] will use for compare.
     * @return boolean
     */
    function isUserFieldAvailable($fieldName, $fieldValue, $comparingID = null)
    {
        global $user_ID, $pluginFramework, $wpdb;
        $unique = true;
        $wpUserTable = $pluginFramework->wpUserTableFieldsArray();

        // Set $comparingID if not set
        // if( !$comparingID ){
        if (is_null($comparingID)) {
            $comparingID = $user_ID;
            $comparingID = isset($_REQUEST['user_id']) ? sanitize_text_field($_REQUEST['user_id']) : $comparingID;
        }

        if ($fieldName == 'user_login') :
            $fieldValue = sanitize_user($fieldValue, true);
            if (! function_exists('username_exists'))
                require_once (ABSPATH . WPINC . '/registration.php');
            $user_id = username_exists($fieldValue);
         elseif ($fieldName == 'user_email') :
            $fieldValue = sanitize_email($fieldValue);
            if (! function_exists('email_exists'))
                require_once (ABSPATH . WPINC . '/registration.php');
            $user_id = email_exists($fieldValue);
            // Check from wp_users table
        elseif (in_array($fieldName, $wpUserTable)) :
            $user_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->users WHERE $fieldName = %s", $fieldValue));
            // Check by usermeta
        else :
            $users = get_users("meta_key=$fieldName&meta_value=$fieldValue&meta_compare='='");
            if (count($users) > 0)
                $user_id = $users[0]->ID;
        endif;

        if (isset($user_id)) {
            if ($user_id) {
                if ($user_id != $comparingID)
                    $unique = false;
            }
        }
        return $unique;
    }

    /**
     * Non-ajax file upload
     *
     * @param string $fieldName
     * @param
     *            array | string $extensions array('jpg','png','gif') | jpg,png,gif
     * @param int $maxSize
     *            1048576 (in Byte 2MB default)
     * @param boolean $replaceOldFile
     *            False
     * @return \WP_Error|string
     */
    function fileUpload($fieldName, $extensions = array( 'jpg', 'png', 'gif' ), $maxSize = 1048576, $replaceOldFile = false)
    {
        global $pfInstance;

        $uploads = $pfInstance->uploadDir();
        $uploadPath = $uploads['path'];
        $uploadUrl = $uploads['url'];

        // $uploads = wp_upload_dir();
        // $uploadPath = $uploads['path'] . '/';
        // $uploadUrl = $uploads['url'] . '/';

        if (! isset($_FILES[$fieldName]['name']))
            return new \WP_Error('no_field', __('No file upload field found!', $pfInstance->name));

        // sanitize_text_field changed to sanitizeDeep from v2.4
        $file = sanitizeDeep($_FILES[$fieldName]);

        $size = $file['size'];

        if ($size == 0)
            return new \WP_Error('no_file', __('File is empty.', $pfInstance->name));

        if (! is_writable($uploadPath))
            return new \WP_Error('not_writable', __('Server error. Upload directory is not writable.', $pfInstance->name));

        if ($size > $maxSize)
            return new \WP_Error('max_size', sprintf(__('File %s is too large.', $pfInstance->name), $file['name']));

        $pathInfo = pathinfo($file['name']);
        $fileName = $pathInfo['filename'];
        $fileName = str_replace(" ", "-", $fileName);
        $ext = $pathInfo['extension'];

        if (is_string($extensions))
            $extensions = explode(",", $extensions);

        if (is_array($extensions)) {
            $extensions = array_map("trim", $extensions);
            $extensions = array_map("strtolower", $extensions);
        }

        if ($extensions && ! in_array(strtolower($ext), $extensions)) {
            $these = implode(', ', $extensions);
            return new \WP_Error('invalid_extension', sprintf(__('File %1$s has an invalid extension, it should be one of %2$s.', $pfInstance->name), $file['name'], $these));
        }

        // / don't overwrite previous files that were uploaded
        if (! $replaceOldFile) {
            while (file_exists($uploadPath . $fileName . '.' . $ext))
                $fileName .= time();
        }

        $fileName = $fileName . '.' . $ext;

        if (! move_uploaded_file($file['tmp_name'], $uploadPath . $fileName)) {
            return new \WP_Error('error', __('Uploaded file could not be saved.', $pfInstance->name) . __('The upload was cancelled due to server error', $pfInstance->name));
        }

        // $filepath = $uploads['subdir'] . "/" . $fileName;
        $filepath = $uploads['subdir'] . $fileName;

        return $filepath;
    }

    /**
     * Print the output to browser if it is ajax request.
     * and run die() immediately.
     */
    function printAjaxOutput($html)
    {
        if (! empty($_REQUEST['is_ajax'])) {
            echo $html;
            die();
        } else {
            return $html;
        }  
    }

    /**
     * Showing error.
     *
     * @param
     *            : string | WP_Error
     * @return : echo error if is_ajax | return error
     */
    function showError($errors)
    {
        $html = null;

        if (is_string($errors))
            $html = $errors;
        elseif (is_wp_error($errors)) {
            foreach ($errors->get_error_messages() as $error)
                $html .= "<div>$error</div>";
        }

        $html = $this->isAdminSection() ? "<div class='error'><p>$html</p></div>" : "<div class='pf_error'>$html</div>";
        return $this->printAjaxOutput($html);
    }

    function showMessage($message, $type = 'success')
    {
        $class = 'pf_' . $type;
        if ($this->isAdminSection() && ($type == 'success'))
            return "<div class='updated'><p>$message</p></div>";
        return "<div class='$class'>$message</div>";
    }

    function jsRedirect($redirect_to, $timeout = 0)
    {
        global $pfInstance;
        if (! $redirect_to)
            return;

        // if ( 0 === strpos( $redirect_to, 'http') )
        // $redirect_to = site_url( $redirect_to );
        // return $redirect_to;
        $timeout = ($timeout * 1000) / 2;
        $msg = $pfInstance->showMessage(__('Redirecting...', $pfInstance->name), 'info');

        $html = null;
        $html .= "<div class=\"pf_redirect\" style=\"display:none;\">$msg</div>";
        $html .= "<script type=\"text/javascript\">";
        if ($timeout) {
            $html .= "jQuery(document).ready(function(){jQuery('.pf_redirect').fadeIn($timeout).fadeOut($timeout,function(){window.location.href = \"$redirect_to\";});});";
        } else
            $html .= "window.location.href = \"$redirect_to\";";
        $html .= "</script>";
        return $html;
    }

    function jQueryRolesTab($tabID, $tabs = array(), $tabsData = array(), $default = null)
    {
        $html = "<ul>";
        foreach ($tabs as $key => $val)
            $html .= "<li><a href=\"#$tabID-tab-$key\">$val</a></li>";
        $html .= "</ul>";
        foreach ($tabs as $key => $val) {
            $data = isset($tabsData[$key]) ? $tabsData[$key] : $default;
            $html .= "<div id=\"$tabID-tab-$key\">$data</div>";
        }

        $html = "<div id=\"$tabID-tabs\">$html</div>";

        $js = "
            <script type=\"text/javascript\">
                jQuery(document).ready(function(){
                    jQuery( \"#$tabID-tabs\" ).tabs();
                });
            </script>
        ";

        return $html . $js;
    }

    /**
     * Send email
     *
     * @param
     *            : array $data, keys: email, subject, body, from_email, from_name, format
     */
    function sendEmail($data)
    {
        if (empty($data['email']))
            return;
        if (empty($data['subject']))
            return;

        $data['body'] = isset($data['body']) ? $data['body'] : '';

        if (function_exists('htmlspecialchars_decode')) {
            $data['subject'] = htmlspecialchars_decode($data['subject']);
            $data['body'] = htmlspecialchars_decode($data['body']);
        }

        if (! empty($data['from_email'])) {
            global $fromEmail;
            $fromEmail = $data['from_email'];
            add_filter('wp_mail_from', function () {
                global $fromEmail;
                return $fromEmail;
            }, 40);
        }

        if (! empty($data['from_name'])) {
            global $fromName;
            $fromName = $data['from_name'];
            add_filter('wp_mail_from_name', function () {
                global $fromName;
                return $fromName;
            }, 40);
        }

        if (! empty($data['format'])) {
            global $mailFormat;
            $mailFormat = $data['format'];
            add_filter('wp_mail_content_type', function () {
                global $mailFormat;
                return $mailFormat;
            }, 40);
        }

        $isSent = wp_mail($data['email'], $data['subject'], $data['body']);

        if (! $isSent && is_array($data['email'])) {
            foreach ($data['email'] as $email) {
                $isSent = wp_mail($email, $data['subject'], $data['body']) ? true : $isSent;
            }
        }

        return $isSent;
    }

    function generateTextFile($fileName, $data = null)
    {
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . $fileName);
        header('Content-Type: text/plain; charset=' . get_option('blog_charset'), true);

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
        echo $data;
    }

    function postIDbyPostName($postName)
    {
        global $wpdb;

        $id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s", $postName));
        return $id;
    }

    function convertErrorArray($data)
    {
        if (! is_array(@$data['errors']))
            return $data;

        $errors = new \WP_Error();
        foreach ($data['errors'] as $code => $error) {
            if (is_array($error)) {
                foreach ($error as $message)
                    $errors->add($code, $message);
            } elseif (is_string($error))
                $errors->add($code, $error);
        }

        if (isset($data['error_data'])) {
            if (is_array($data['error_data'])) {
                foreach ($data['error_data'] as $code => $errData)
                    $errors->add_data($errData, $code);
            }
        }

        return $errors;
    }

    function isImage($url)
    {
        if (empty($url))
            return false;

        // Is not a recommanded way. Commented since 1.4
        // if (ini_get('allow_url_fopen'))
        // return getimagesize($url) ? true : false;

        $ext = preg_match('/\.([^.]+)$/', $url, $matches) ? strtolower($matches[1]) : false;
        $image_exts = array(
            'jpg',
            'jpeg',
            'jpe',
            'gif',
            'png'
        );
        return in_array($ext, $image_exts) ? true : false;
    }
}