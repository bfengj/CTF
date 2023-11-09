<?php
namespace UserMeta;

/**
 * Generate single field
 *
 * @author Khaled Hossain
 * @since 1.1.4
 */
class Field
{

    /**
     * Field id from shared field
     */
    private $id;

    private $data = [];

    private $rules = [];

    private $errors = [];

    /**
     * accept: user_id, insert_type etc
     */
    private $options = [];

    /**
     * Field type based on Shared field
     */
    private $type;

    private static $fieldTypes;

    /**
     * Assign id, data and options to object
     *
     * @param int $id
     * @param array $data
     * @param array $options
     */
    public function __construct($id, $data = [], $options = [])
    {
        global $userMeta;

        $this->id = $id;
        if (empty($id) && ! empty($data['id']))
            $this->id = $data['id'];

        $this->data = $data;

        if (empty($this->data) && ! empty($this->id)) {
            $fields = $userMeta->getData('fields');
            if (isset($fields[$this->id])) {
                $this->data = $fields[$this->id];
            }
        }

        $this->options = $options;
    }

    /**
     * Get sanitized field
     *
     * @return array
     */
    public function getData()
    {
        $this->sanitizeField();
        return ! empty($this->data) ? $this->data : [];
    }

    /**
     * Get single value form $this->data
     *
     * @param string $key
     */
    public function getConfig($key = null)
    {
        $this->sanitizeField();

        if (empty($key))
            return $this->data;

        if (isset($this->data[$key]))
            return $this->data[$key];

        return false;
    }

    /**
     * Generate html field based on object data.
     *
     * @param array $field
     * @param array $extra
     * @return string html
     */
    public function render($field = [], $extra = [])
    {
        $field = $field ?: $this->data;
        $extra = $extra ?: $this->options;

        $form = ! empty($extra['form']) ? $extra['form'] : null;
        $formKey = ! empty($extra['form_key']) ? $extra['form_key'] : (isset($form['form_key']) ? $form['form_key'] : null);
        $userID = ! empty($extra['user_id']) ? $extra['user_id'] : null;

        $field = apply_filters('user_meta_field_config', $field, $this->id, $formKey, $userID);
        $field = apply_filters('user_meta_field_config_render', $field, $this->id, $formKey, $userID);

        $generate = new Field\Generate($field, [
            'actionType' => isset($extra['action_type']) ? $extra['action_type'] : null,
            'uniqueID' => isset($extra['unique_id']) ? $extra['unique_id'] : null,
            'userID' => $userID,
            'form' => $form,
            'inPage' => isset($extra['in_page']) ? $extra['in_page'] : null,
            'inSection' => isset($extra['in_section']) ? $extra['in_section'] : null,
            'isNext' => isset($extra['is_next']) ? $extra['is_next'] : null,
            'isPrevious' => isset($extra['is_previous']) ? $extra['is_previous'] : null,
            'currentPage' => isset($extra['current_page']) ? $extra['current_page'] : null
        ]);
        $this->fieldGenerateObject = $generate->getObject();
        $html = $generate->render();

        return apply_filters('user_meta_field_display', $html, $this->id, $formKey, $field, $userID);
    }

    /**
     * Get UserMeta\Field\Generate for unittests
     *
     * @return NULL|object
     */
    public function getGenerateObject()
    {
        return isset($this->fieldGenerateObject) ? $this->fieldGenerateObject : null;
    }

    /*
     * Only used in user-meta-field-value shortcode.
     */
    public function displayValue($metaKey = '')
    {
        global $userMeta;

        $key = $metaKey;
        if (empty($metaKey) || ! empty($this->id)) {
            $this->sanitizeField();
            $key = ! empty($this->data['field_name']) ? $this->data['field_name'] : $key;
        }

        if (empty($key))
            return;

        $fieldValue = null;
        $user = $userMeta->determineUser();

        if (! empty($user)) {

            if (isset($this->data['default_value']))
                $fieldValue = $userMeta->convertUserContent($user, $this->data['default_value']);

            $key = trim($key);
            if (isset($user->$key))
                $fieldValue = $user->$key;

            if (is_array($fieldValue))
                $fieldValue = implode(', ', $fieldValue);

            if (! empty($this->type) && in_array($this->type, array(
                'user_avatar',
                'file'
            ))) {
                $field = $this->data;

                if (! empty($field)) {
                    $field['field_value'] = $fieldValue;
                    $field['read_only'] = true;
                }

                $umFile = new File($field);

                $fieldValue = $umFile->showFile();
            }
        }

        return $fieldValue;
    }

    /*
     * Only used inside user-meta-field shortcode.
     */
    function generateField()
    {
        global $userMeta;

        if (empty($this->data))
            return;

        $field = $this->getConfig();

        $user = $userMeta->determineUser();
        $userID = ! empty($user) ? $user->ID : 0;
        $formKey = ! empty($this->options['form_key']) ? $this->options['form_key'] : '';

        // Determine Field Value
        $fieldValue = null;
        if (isset($field['default_value'])) {
            $fieldValue = $userMeta->convertUserContent($user, $field['default_value']);
        }

        if (! empty($field['field_name'])) {
            $fieldName = $field['field_name'];
            if (isset($user->$fieldName))
                $fieldValue = $user->$fieldName;
        }

        $field['field_value'] = $fieldValue;

        $html = $this->render($field, [
            'action_type' => isset($this->options['insert_type']) ? $this->options['insert_type'] : null,
            'user_id' => $userID,
            'unique_id' => 'shortcode'
        ]);

        if (in_array($field['field_type'], array(
            'file',
            'user_avatar'
        ))) {
            $html .= "<script type=\"text/javascript\">jQuery(document).ready(function(){umFileUploader();});</script>";
        }

        return $html;
    }

    /**
     * Set :
     * self::$fieldTypes
     * $this->type
     * $this->typeData
     *
     * $this->data['field_group']
     * $this->data['field_name']
     * $this->data['id']
     */
    private function sanitizeField()
    {
        global $userMeta;

        if (empty($this->data))
            return;

        if (empty(self::$fieldTypes))
            self::$fieldTypes = $userMeta->umFields();

        $this->type = ! empty($this->data['field_type']) ? $this->data['field_type'] : '';

        if (isset(self::$fieldTypes[$this->type]))
            $typeData = self::$fieldTypes[$this->type];

        if (isset($typeData['field_group']))
            $this->data['field_group'] = $typeData['field_group'];

        $fieldName = null;
        if (isset($this->data['field_group']) && $this->data['field_group'] == 'wp_default') {
            $fieldName = $this->data['field_type'];
        } else {
            if (! empty($this->data['meta_key']))
                $fieldName = $this->data['meta_key'];
        }

        $this->data['field_name'] = $fieldName;
        $this->data['id'] = $this->id;
    }

    /**
     * Add rule for backend validation
     *
     * @param string $rule
     */
    public function addRule($rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * Do backend validation
     *
     * @return boolean
     */
    public function validate()
    {
        $this->assignRules();

        foreach ($this->rules as $rule) {
            $value = isset($this->data['field_value']) ? $this->data['field_value'] : null;
            $validate = new ValidationRule($rule, $value, array(
                'field_name' => isset($this->data['field_name']) ? $this->data['field_name'] : null,
                'field_title' => isset($this->data['field_title']) ? $this->data['field_title'] : null,
                'user_id' => isset($this->options['user_id']) ? $this->options['user_id'] : 0,
                'insert_type' => isset($this->options['insert_type']) ? $this->options['insert_type'] : null
            ));

            if ('regex' == $validate->getRule()) {
                $regex = ! empty($this->data['regex']) ? '/' . $this->data['regex'] . '/' : null;
                $errorText = ! empty($this->data['error_text']) ? $this->data['error_text'] : '';
                $validate->setProperty($regex, $errorText);
            }

            if (! $validate->validate())
                $this->errors[$validate->getRule()] = $validate->getError();
        }

        return empty($this->errors) ? true : false;
    }

    /**
     * Assign some default rules to specefic fields
     */
    private function assignRules()
    {
        if (isset($this->data['field_type'])) {
            switch ($this->data['field_type']) {
                case 'user_login':
                    $this->rules[] = 'required';
                    $this->rules[] = 'unique';
                    break;
                case 'user_email':
                    $this->rules[] = 'required';
                    $this->rules[] = 'unique';
                    $this->rules[] = 'email';
                    break;
                case 'email':
                    $this->rules[] = 'email';
                    break;
                case 'url':
                case 'user_url':
                    $this->rules[] = 'url';
                    break;
                case 'user_registered':
                    $this->rules[] = 'datetime';
                    break;
                case 'number':
                    $this->rules[] = 'number';
                    break;
                case 'phone':
                    $this->rules[] = 'phone';
                    break;
                /*
                 * case 'custom':
                 * $this->rules[] = 'custom';
                 * break;
                 */
            }

            if (! empty($this->data['required'])) {
                if (! in_array('required', $this->rules))
                    $this->rules[] = 'required';
            }

            if (! empty($this->data['unique'])) {
                if (! in_array('unique', $this->rules))
                    $this->rules[] = 'unique';
            }

            if (! empty($this->data['regex'])) {
                $this->rules[] = 'regex';
            }

            if (! empty($this->data['required']) && ! empty($this->data['min_required_options'])) {
                $this->rules[] = [
                    'required_options',
                    (int) $this->data['min_required_options']
                ];
            }

            if (! empty($this->data['max_allowed_options'])) {
                $this->rules[] = [
                    'allowed_options',
                    (int) $this->data['max_allowed_options']
                ];
            }
        }
    }

    /**
     * Get validation errors
     *
     * @return string[]
     */
    public function getErrors()
    {
        $errors = [];
        foreach ($this->errors as $rule => $error) {
            // Commented since 1.5
            // $title = isset($this->data['field_title']) ? $this->data['field_title'] : null;
            // $errors["validate_$rule"] = sprintf($error, $title);
            $errors["validate_$rule"] = $error;
        }
        return $errors;
    }
}