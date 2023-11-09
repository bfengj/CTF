<?php
namespace UserMeta;

/**
 * Controller for all file related tasks
 *
 * @author Khaled Hossain
 * @since 1.4
 */
class FileController
{

    public function __construct()
    {
        global $userMeta;

        add_filter('get_avatar', [
            $this,
            'getAvatar'
        ], 10, 5);

        add_filter('user_meta_file_uploader_config', [
            $this,
            'fileUploaderConfig'
        ]);
        add_action('pf_file_upload_after_uploaded', [
            $this,
            'updateFileCache'
        ], 10, 2);

        add_action('user_meta_schedule_event', [
            $userMeta,
            'cleanupFileCache'
        ]);

        add_action('delete_user', [
            $this,
            'deleteFiles'
        ], 10, 2);
        add_filter('user_meta_user_modified_old_data_tracker', [
            $this,
            'deleteOldFiles'
        ]);

        add_action('wp_ajax_um_file_uploader', [
            $userMeta,
            'ajaxFileUploader'
        ]);
        add_action('wp_ajax_nopriv_um_file_uploader', [
            $userMeta,
            'ajaxFileUploader'
        ]);
        add_action('wp_ajax_um_show_uploaded_file', [
            $userMeta,
            'ajaxShowUploadedFile'
        ]);
        add_action('wp_ajax_nopriv_um_show_uploaded_file', [
            $userMeta,
            'ajaxShowUploadedFile'
        ]);
    }

    /**
     * Filter for get_avatar.
     * Allow to change degault avatar to custom one.
     *
     * @param string $avatar
     * @param string $id_or_email
     * @param int $size
     * @param string $default
     * @param string $alt
     * @return string html img tag
     */
    public function getAvatar($avatar = '', $id_or_email, $size = '96', $default = '', $alt = false)
    {
        global $userMeta;
        $safe_alt = (false === $alt) ? '' : esc_attr($alt);

        if (is_numeric($id_or_email))
            $user_id = (int) $id_or_email;
        elseif (is_string($id_or_email))
            $user_id = email_exists($id_or_email);
        elseif (is_object($id_or_email)) {
            if (! empty($id_or_email->user_id))
                $user_id = (int) $id_or_email->user_id;
            elseif (! empty($id_or_email->comment_author_email))
                $user_id = email_exists($id_or_email->comment_author_email);
        }

        if (! isset($user_id))
            return $avatar;

        $umAvatar = get_user_meta($user_id, 'user_avatar', true);

        $file = $userMeta->determinFileDir($umAvatar);
        if (! empty($file)) {
            $avatar = "<img alt='{$safe_alt}' src='{$file['url']}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }

        return $avatar;
    }

    /**
     * Determine file uploader config
     *
     * @param array $config
     * @return false|array: false for access controll and array for config
     */
    public function fileUploaderConfig(array $config)
    {
        global $userMeta;
        if (empty($_REQUEST['field_id']))
            return false;

        if (in_array($_REQUEST['field_id'], [
            'csv_upload_user_import',
            'txt_upload_ump_import'
        ]) && ! $userMeta->isAdmin()) {
            return false;
        }

        if ($_REQUEST['field_id'] == 'csv_upload_user_import') {
            $config = [
                'extensions' => [
                    'csv'
                ],
                'max_size' => 10 * 1024 * 1024,
                'overwrite' => true
            ];
        } elseif ($_REQUEST['field_id'] == 'txt_upload_ump_import') {
            $config = [
                'extensions' => [
                    'txt'
                ],
                'max_size' => 10 * 1024 * 1024,
                'overwrite' => true
            ];
        } elseif (strpos($_REQUEST['field_id'], 'um_field_') !== false) {
            $field = File::determineField();
            if (! empty($field['field_type']) && ! in_array($field['field_type'], [
                'user_avatar',
                'file'
            ])) {
                return false;
            }
            if (! empty($field['allowed_extension'])) {
                $extensions = str_replace(' ', '', $field['allowed_extension']);
                $config['extensions'] = explode(",", $extensions);
            }
            if (! empty($field['max_file_size']))
                $config['max_size'] = $field['max_file_size'] * 1024;
        }

        return $config;
    }

    public function updateFileCache($fieldName, $filePath)
    {
        global $userMeta;
        $cache = $userMeta->getData('cache');

        $fileCache = isset($cache['file_cache']) ? $cache['file_cache'] : array();
        if (! in_array($filePath, $fileCache)) {
            $fileCache[time()] = $filePath;
            $cache['file_cache'] = $fileCache;
            $userMeta->updateData('cache', $cache);
        }
    }

    /**
     * Delete user's avatar and files.
     * Called by delete_user action.
     *
     * @param int $userID
     * @param int $reassign:
     *            Don't needs to focus on $reassign. Everytime usermeta get deleted.
     */
    public function deleteFiles($userID, $reassign)
    {
        File::deleteFiles($userID);
    }

    /**
     * Delete old files while user update their profile.
     * Called by user_meta_user_modified_old_data_tracker filter.
     *
     * @param array $oldData
     * @param \WP_User $user
     */
    public function deleteOldFiles($oldData)
    {
        File::deleteOldFiles($oldData);
        return $oldData;
    }
}