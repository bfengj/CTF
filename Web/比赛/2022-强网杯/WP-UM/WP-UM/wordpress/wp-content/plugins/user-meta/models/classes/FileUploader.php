<?php
namespace UserMeta;

/**
 * Provided classes for qqUploader.
 * Handle file uploads via XMLHttpRequest.
 * Three classes in this file: qqUploadedFileXhr, qqUploadedFileForm and FileUploader.
 *
 * @since 1.1.7 moved from uploader.php
 */
class qqUploadedFileXhr
{

    /**
     * Save the file to the specified path
     *
     * @return boolean TRUE on success
     */
    public function save($path)
    {
        $input = fopen('php://input', 'r');
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        /*
         * $realSize as bytes
         */
        if ($realSize != $this->getSize())
            return false;

        $target = fopen($path, 'w');
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }

    /**
     * Get file name from request
     *
     * @return string
     */
    public function getName()
    {
        return sanitize_file_name($_GET['qqfile']);
    }

    /**
     * Get file size as bytes from $_SERVER
     *
     * @throws \Exception
     * @return number bytes
     */
    public function getSize()
    {
        if (isset($_SERVER['CONTENT_LENGTH']))
            return (int) $_SERVER['CONTENT_LENGTH'];
        else
            throw new \Exception('Getting content length is not supported.');
    }
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 *
 * @since 1.1.7 moved from uploader.php
 */
class qqUploadedFileForm
{

    /**
     * Save the file to the specified path
     *
     * @return boolean TRUE on success
     */
    public function save($path)
    {
        if (! move_uploaded_file($_FILES['qqfile']['tmp_name'], $path))
            return false;

        return true;
    }

    public function getName()
    {
        return $_FILES['qqfile']['name'];
    }

    public function getSize()
    {
        return $_FILES['qqfile']['size'];
    }
}

/**
 * Ajax file uploader
 *
 * @since 1.1.7
 */
class FileUploader
{

    private $allowedExtensions = [];

    /**
     * $sizeLimit in Bytes
     *
     * @var integer Default 1024*1024=1M
     */
    private $sizeLimit = 1048576;

    /**
     *
     * @var qqUploadedFileXhr | qqUploadedFileForm | false
     */
    private $file;

    public function __construct(array $allowedExtensions = [], $sizeLimit = 1048576)
    {
        $this->allowedExtensions = array_map('strtolower', $allowedExtensions);
        $this->sizeLimit = $sizeLimit;
        $this->setProperties();
    }

    private function setProperties()
    {
        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false;
        }

        $uploads = File::uploadDir();
        $this->subdir = $uploads['subdir'];
        $this->pathinfo = pathinfo($this->file->getName());
        $this->uploadPath = $uploads['path'];
        $this->uploadUrl = $uploads['url'];
        $this->fileName = strtolower(sanitize_file_name($this->pathinfo['filename']));
        $this->extension = strtolower($this->pathinfo['extension']);
    }

    /**
     * Validate before uploading
     *
     * @throws \Exception
     */
    private function validate()
    {
        global $userMeta;

        if (! is_writable($this->uploadPath))
            throw new \Exception(__('Server error. Upload directory is not writable.', $userMeta->name));

        if (! $this->file)
            throw new \Exception(__('No files were uploaded.', $userMeta->name));

        $size = $this->file->getSize();
        if ($size == 0)
            throw new \Exception(__('File is empty', $userMeta->name));

        if ($size > $this->sizeLimit) {
            $sizeMB = $this->sizeLimit / (1024 * 1024) . 'MB';
            throw new \Exception(sprintf(__('File is too large. Maximum allowed file size is %s', $userMeta->name), $sizeMB));
        }

        if ($size > File::getServerMaxSizeLimit('b'))
            throw new \Exception(__('Server error. Please increase value of post_max_size and upload_max_filesize', $userMeta->name));

        if ($this->allowedExtensions && ! in_array(strtolower($this->extension), $this->allowedExtensions)) {
            $these = implode(', ', $this->allowedExtensions);
            throw new \Exception(sprintf(__('File %1$s has an invalid extension, it should be one of %2$s.', $userMeta->name), $this->pathinfo['filename'], $these));
        }
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     * Not in use since 1.4
     */
    public function handleUploadOld($replaceOldFile = FALSE)
    {
        global $userMeta;

        $uploads = $userMeta->uploadDir();
        $uploadPath = $uploads['path'];
        $uploadUrl = $uploads['url'];

        if (! is_writable($uploadPath)) {
            return [
                'error' => __('Server error. Upload directory is not writable.', $userMeta->name)
            ];
        }
        if (! $this->file) {
            return [
                'error' => __('No files were uploaded.', $userMeta->name)
            ];
        }
        $size = $this->file->getSize();
        if ($size == 0) {
            return [
                'error' => __('File is empty', $userMeta->name)
            ];
        }
        if ($size > $this->sizeLimit) {
            return [
                'error' => __('File is too large', $userMeta->name)
            ];
        }

        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        $filename = strtolower(sanitize_file_name($filename));
        $ext = strtolower($pathinfo['extension']);
        if ($this->allowedExtensions && ! in_array(strtolower($ext), $this->allowedExtensions)) {
            $these = implode(', ', $this->allowedExtensions);
            return [
                'error' => sprintf(__('File %1$s has an invalid extension, it should be one of %2$s.', $userMeta->name), $pathinfo['filename'], $these)
            ];
        }

        if (! $replaceOldFile) {
            // / don't overwrite previous files that were uploaded
            while (file_exists($uploadPath . $filename . '.' . $ext)) {
                $filename .= '-' . time();
            }
        }

        $field_name = isset($_REQUEST['field_name']) ? sanitize_text_field($_REQUEST['field_name']) : null;
        $filepath = $uploads['subdir'] . "$filename.$ext";
        if ($this->file->save($uploadPath . $filename . '.' . $ext)) {
            do_action('pf_file_upload_after_uploaded', $field_name, $filepath);
            return [
                'success' => true,
                'field_name' => $field_name,
                'filepath' => $filepath
            ];
        } else {
            return [
                'error' => __('Uploaded file could not be saved.', $userMeta->name) . __('The upload was cancelled due to server error', $userMeta->name)
            ];
        }
    }

    /**
     * Only public method for uploading
     *
     * @param boolean $replaceOldFile
     * @return array: ['success'=>true] or ['error'=>'error message']
     */
    public function handleUpload($replaceOldFile = false)
    {
        try {
            /**
             * don't overwrite previous files that were uploaded.
             * 'while' is making sure of no overwrite.
             */
            if (! $replaceOldFile) {
                while (file_exists($this->getFileFullPath())) {
                    $this->fileName .= '-' . time();
                }
            }
            $this->validate();
            $this->upload();

            return $this->uploadedResult();
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get successfully uploaded file info
     *
     * @return array
     */
    private function uploadedResult()
    {
        $fieldName = isset($_REQUEST['field_name']) ? sanitize_text_field($_REQUEST['field_name']) : null;
        do_action('pf_file_upload_after_uploaded', $fieldName, $this->getFileSubPath());
        return [
            'success' => true,
            'field_name' => $fieldName,
            'filepath' => $this->getFileSubPath()
        ];
    }

    /**
     * Get full file path
     * Using method insted of property because of dynamic $this->fileName
     *
     * @return string
     */
    private function getFileFullPath()
    {
        return $this->uploadPath . $this->fileName . '.' . $this->extension;
    }

    /**
     * Get relative file path to store in db
     * Using method insted of property because of dynamic $this->fileName
     *
     * @return string
     */
    private function getFileSubPath()
    {
        return $this->subdir . $this->fileName . '.' . $this->extension;
    }

    /**
     * Upload file to determined full path
     *
     * @throws \Exception
     */
    private function upload()
    {
        global $userMeta;
        if (! $this->file->save($this->getFileFullPath()))
            throw new \Exception(__('Uploaded file could not be saved.', $userMeta->name) . __('The upload was cancelled due to server error', $userMeta->name));
    }
}