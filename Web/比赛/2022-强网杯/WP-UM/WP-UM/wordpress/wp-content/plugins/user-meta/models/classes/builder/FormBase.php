<?php
namespace UserMeta;

/**
 * Without setting $fromName parameter, it is possible to get shared fields by calling getSharedFields method.
 * When $formName is set, $this->data contains form's data and $this->data['fields'] contains populated fields.
 *
 * @author Khaled Hossain
 * @since 1.1.7
 */
class FormBase
{

    /**
     *
     * @var (string) Form Name
     */
    protected $name;

    /**
     *
     * @var (array) Form Data including populated fields.
     */
    protected $data = [];

    /**
     *
     * @var (bool) Is form found in DB?
     */
    protected $found;

    /**
     *
     * @var (array) All raw shared fields from DB.
     */
    protected $sharedFields = [];

    /**
     *
     * @var (array) All raw forms from DB.
     */
    private $rawForms = [];

    /**
     *
     * @param (string) $formName
     */
    function __construct($formName = null)
    {
        $this->name = $formName;

        /**
         * get all shared fields from db and set to $this->allFields.
         */
        $this->_loadAllFields();

        /**
         * Populate: $this->found, $this->data, Sanitize: $this->data['fields']
         */
        if (! empty($this->name)) {
            $this->_loadForm();
            $this->_initForm();
        }
    }

    /**
     * get all shared fields from db and set to $this->allFields.
     */
    private function _loadAllFields()
    {
        // Commented since 2.3
        // global $userMeta;
        // $allFields = $userMeta->getData('fields');
        // $this->sharedFields = is_array($allFields) ? $allFields : array();
        $this->sharedFields = OptionData::getFields();
    }

    /**
     * Load raw form and form's fields from DB.
     */
    private function _loadForm()
    {
        global $userMeta;

        if (empty($this->name))
            return;

        if ('wp_backend_profile' == $this->name) {
            $backendProfile = $userMeta->getSettings('backend_profile');
            $this->data['fields'] = isset($backendProfile['fields']) ? $backendProfile['fields'] : [];
        } else {
            // $forms = $userMeta->getData('forms'); // commented since 2.3
            $forms = OptionData::getForms();
            if (isset($forms[$this->name])) {
                $this->found = true;
                $this->data = $forms[$this->name];
            }
        }
    }

    /**
     * Populate: $this->found, $this->data.
     * Sanitize: $this->data['fields']: Merge by $this->allFields.
     * Set: $field['is_shared'] in case of shared field.
     */
    private function _initForm()
    {
        $formFields = [];
        if (! empty($this->data['fields']) && is_array($this->data['fields'])) {
            foreach ($this->data['fields'] as $id => $field) {
                $id = is_array($field) ? $id : $field;
                $field = is_array($field) ? $field : [];
                if (! empty($this->sharedFields[$id]) && is_array($this->sharedFields[$id])) {
                    $field = array_merge($this->sharedFields[$id], $field);
                    $field['is_shared'] = true;
                }

                $field['id'] = $id;

                if (! empty($field['field_type']))
                    $formFields[$id] = $field;
            }
        }

        $this->data['fields'] = $formFields;
    }

    /**
     * Is form found in DB?
     *
     * @return (bool)
     */
    function isFound()
    {
        return $this->found ? true : false;
    }

    /**
     * Get raw shared fields from DB.
     *
     * @return (array)
     */
    function getSharedFields()
    {
        return $this->sharedFields;
    }

    /**
     * Get all shared and form's field together.
     * Shared fields getting preference than form' fields.
     *
     * @return (array)
     */
    function getAllFields()
    {
        $fields = $this->getSharedFields();
        $formsFields = $this->getRawForms();
        foreach ($formsFields as $form) {
            if (! empty($form['fields']) && is_array($form['fields'])) {
                foreach ($form['fields'] as $key => $val) {
                    if (! isset($fields[$key])) {
                        $fields[$key] = $val;
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * Get raw forms from DB.
     *
     * @return (array)
     */
    function getRawForms()
    {
        global $userMeta;
        if (! empty($this->rawForms))
            return $this->rawForms;

        // commented since 2.3
        // $forms = $userMeta->getData('forms');
        // return $this->rawForms = is_array($forms) ? $forms : [];

        return $this->rawForms = OptionData::getForms();
    }
}
