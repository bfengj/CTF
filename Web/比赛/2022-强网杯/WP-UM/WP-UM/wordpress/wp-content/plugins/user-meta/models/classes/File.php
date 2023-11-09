<?php
namespace UserMeta;

/**
 * Handle almost all files manipulation.
 *
 * @author Khaled Hossain
 * @since 1.1.7
 */
class File
{

    private $path;

    private $url;

    private $fileName;

    private $width;

    private $height;

    private $field = [];

    /**
     * Initialize File object with $field as field config array
     *
     * @param array $field
     */
    public function __construct(array $field)
    {
        $this->initFile($field);
    }

    /**
     * Initiate all private properties.
     *
     * @param mixed $field
     */
    private function initFile($field)
    {
        global $userMeta;

        $this->field = $field;

        $this->sanitizeField();

        if (empty($this->field['field_value']))
            return;

        $uploads = self::getFilePath($this->field['field_value']);
        if (empty($uploads))
            return;

        // .. replaced with null for path traversal security reason from 2.4.3
        $this->path = str_replace('..', '', $uploads['path']);
        $this->url = str_replace('..', '', $uploads['url']);

        $fileData = pathinfo($this->path);
        $this->fileName = $fileData['basename'];

        $this->width = isset($this->field['image_width']) ? $this->field['image_width'] : null;
        $this->height = isset($this->field['image_height']) ? $this->field['image_height'] : null;

        if (! empty($this->field['resize_image'])) {
            $crop = ! empty($this->field['crop_image']) ? true : false;
            $this->resize($crop);
        }
    }

    private function sanitizeField()
    {
        if (isset($this->field['field_type']) && $this->field['field_type'] == 'user_avatar') {
            if (! empty($this->field['image_size'])) {
                $this->field['image_width'] = $this->field['image_size'];
                $this->field['image_height'] = $this->field['image_size'];
            } else {
                $this->field['image_width'] = 96;
                $this->field['image_height'] = 96;
            }

            $class = "avatar avatar-{$this->field['image_width']} photo";
            if (! empty($this->field['field_class']))
                $class .= ' ' . $this->field['field_class'];

            $this->field['field_class'] = $class;
        }
    }

    private function resize($isCrop)
    {
        if (empty($this->width) || empty($this->height))
            return;

        $image = wp_get_image_editor($this->path);
        if (! is_wp_error($image)) {
            $image->resize($this->width, $this->height, $isCrop);
            $image->save($this->path);
        }
    }

    /**
     * Show file on browser
     * The method requires $fields array in construct method
     * e.g.: (new File($fields)->showFile()
     *
     * @return string html
     */
    public function showFile()
    {
        global $userMeta;

        $html = '';
        $class = isset($this->field['field_class']) ? $this->field['field_class'] : '';

        if ($userMeta->isImage($this->url)) {
            $arg = array(
                'src' => esc_url($this->url),
                'class' => $class,
                'alt' => esc_attr($this->fileName)
            );

            if (! empty($this->width))
                $arg['width'] = $this->width;

            if (! empty($this->height))
                $arg['height'] = $this->height;

            $html .= $userMeta->createInput(null, 'img', $arg);
        } else {
            $html .= $userMeta->createInput(null, 'a', array(
                'href' => esc_url($this->url),
                'class' => $class,
                'alt' => esc_attr($this->fileName),
                'value' => $this->fileName
            ));
        }

        $fieldName = isset($this->field['field_name']) ? $this->field['field_name'] : '';

        /**
         * Remove Link
         */
        if (empty($this->field['read_only']) && ! empty($this->url)) {
            $html .= "<p class='um_remove_file' data-field_name='" . $fieldName . "' onclick='umRemoveFile(this)'>
                   <span>" . __('Remove', $userMeta->name) . "</span></p>";
        }

        /**
         * Hidden field
         */
        if (! empty($this->field['field_name']) && empty($this->field['read_only']) && isset($this->field['field_value'])) {
            $html .= $userMeta->createInput($this->field['field_name'], 'hidden', array(
                'value' => $this->field['field_value']
            ));
        }

        return $html;
    }

    /**
     * Determine field from $_REQUEST
     *
     * 'form_key', 'field_name' and 'field_id' is available on 'um_file_uploader' and 'um_show_uploaded_file' action
     * however, 'filepath' is only available on 'um_show_uploaded_file' action
     *
     * @since 1.4
     * @uses FileController::fileUploadExtensions()
     * @uses FileController::fileUploadMaxSize()
     * @uses AjaxModel::ajaxShowUploadedFile()
     *      
     * @return array
     */
    public static function determineField()
    {
        $field = [];
        if (isset($_REQUEST['form_key']))
            $formName = sanitize_text_field($_REQUEST['form_key']);

        /**
         * First, try from field
         */
        if (! empty($formName)) {
            $form = new FormGenerate($formName, null, null);
            if ($form->isFound()) {
                $validFields = $form->validInputFields();
                if (empty($validFields[$_REQUEST['field_name']]))
                    return [];

                $field = $validFields[sanitize_text_field($_REQUEST['field_name'])];
            } else
                unset($form);
        }

        /**
         * If form field failed, then try shared field
         */
        if (! isset($form) && ! empty($_REQUEST['field_id'])) {
            $id = trim(str_replace('um_field_', '', esc_attr($_REQUEST['field_id'])));
            $umField = new Field($id);
            $field = $umField->getData();
        }

        if (! empty($field)) {
            if (! empty($_REQUEST['filepath']))
                $field['field_value'] = esc_attr($_REQUEST['filepath']);
        }

        return $field;
    }

    /**
     * Directory to upload files
     *
     * @return array: [path, url, subdir]
     */
    public static function uploadDir()
    {
        $dir = apply_filters('user_meta_upload_dir', '/uploads/files/');
        if (empty($dir))
            $dir = '/uploads/files/';

        $dir = '/' . trim($dir, '/') . '/';

        $path = WP_CONTENT_DIR . $dir;
        $url = WP_CONTENT_URL . $dir;

        if (! file_exists($path) && ! is_dir($path)) {
            mkdir($path, 0777, true);
            touch($path . 'index.html');
        }

        return array(
            'path' => $path,
            'url' => $url,
            'subdir' => $dir
        );
    }

    /**
     * Determine file full path based on given sub path.
     *
     * @param string $fileSubPath
     * @param boolean $onlyContentDir
     *            (Default: false)
     * @return array: [path, url] | []
     */
    public static function getFilePath($fileSubPath, $onlyContentDir = false)
    {
        $file = array();

        if (empty($fileSubPath))
            return $file;

        /**
         * Check file in WP_CONTENT_DIR
         */
        if (file_exists(WP_CONTENT_DIR . $fileSubPath)) {
            $file['path'] = WP_CONTENT_DIR . $fileSubPath;
            $file['url'] = WP_CONTENT_URL . $fileSubPath;
            return $file;
        }

        if ($onlyContentDir)
            return $file;

        /**
         * UMP backword compatibility
         */
        $uploads = wp_upload_dir();
        if (file_exists($uploads['basedir'] . $fileSubPath)) {
            $file['path'] = $uploads['basedir'] . $fileSubPath;
            $file['url'] = $uploads['baseurl'] . $fileSubPath;
            return $file;
        }

        /**
         * Backword compatibility for multisite
         */
        if (is_multisite()) {
            $siteurl = get_option('siteurl');

            // Check main site first
            if (file_exists(WP_CONTENT_DIR . '/uploads' . $fileSubPath)) {
                $file['path'] = WP_CONTENT_DIR . '/uploads' . $fileSubPath;
                $file['url'] = WP_CONTENT_URL . '/uploads' . $fileSubPath;
                return $file;
            }

            // Now check whole network
            foreach (getBlogIdsFromSites() as $blogId) {
                if (file_exists(WP_CONTENT_DIR . "/blogs.dir/{$blogId}/files" . $fileSubPath)) {
                    $file['path'] = WP_CONTENT_DIR . "/blogs.dir/{$blogId}/files" . $fileSubPath;
                    $file['url'] = WP_CONTENT_URL . "/blogs.dir/{$blogId}/files" . $fileSubPath;
                    return $file;
                }
            }
        }

        return $file;
    }

    /**
     * Delete all user's files.
     *
     * @param int $userID
     */
    public static function deleteFiles($userID)
    {
        $metaKeys = getFileMetaKeys();
        foreach ($metaKeys as $metaKey) {
            $subPath = get_user_meta($userID, $metaKey, true);
            if (! empty($subPath)) {
                self::deleteFile($subPath);
            }
        }
    }

    /**
     * Delete old files when user updated their profile.
     *
     * @param array $oldData
     */
    public static function deleteOldFiles($oldData)
    {
        $metaKeys = getFileMetaKeys();
        foreach ($oldData as $key => $val) {
            if (in_array($key, $metaKeys)) {
                self::deleteFile($val);
            }
        }
    }

    /**
     * Delate single file based on subpath
     *
     * @param string $subPath
     */
    private static function deleteFile($subPath)
    {
        $file = self::getFilePath($subPath, true);
        if (! empty($file['path'])) {
            unlink($file['path']);
        }
    }

    /**
     * Convert size string to bytes
     *
     * @param string $str:
     *            e.g 2M
     * @return number
     */
    public static function toBytes($str)
    {
        $val = (int) trim($str);
        $last = strtolower($str[strlen($str) - 1]);
        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }

    /**
     * Convert bytes to KB, MB or GB
     *
     * @param number $size
     * @param string $unitTo
     *            KB | MB | GB
     * @return number
     */
    public static function convertSize($size, $unitTo)
    {
        switch (strtolower($unitTo)) {
            case 'gb':
                $size /= 1024;
            case 'mb':
                $size /= 1024;
            case 'kb':
                $size /= 1024;
        }

        return $size;
    }

    /**
     * Get server setting for upload max file size in KB
     *
     * @return number
     */
    public static function getServerMaxSizeLimit($unit = 'kb')
    {
        $size = min(self::toBytes(ini_get('post_max_size')), self::toBytes(ini_get('upload_max_filesize')));

        return self::convertSize($size, $unit);
    }
}