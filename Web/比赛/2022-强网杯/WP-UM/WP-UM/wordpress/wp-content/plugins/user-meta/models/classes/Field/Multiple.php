<?php
namespace UserMeta\Field;

/**
 * This class can handle multiple user input
 * Direct Child: Email, Password, Custom
 *
 * @author Khaled Hossain
 * @since 1.2.0
 */
abstract class Multiple extends Base
{

    /**
     * Get new instance for building another input
     *
     * @return object|\UserMeta\Field\Multiple
     */
    protected function getNewInstance()
    {
        return clone $this;
    }

    /**
     * Get instance of retype field
     * e.g retype email
     *
     * @return object|\UserMeta\Field\Multiple
     */
    protected function getRetypeInstance()
    {
        global $userMeta;

        $instance = $this->getNewInstance();
        $instance->inputClass = $this->validations = [];
        $instance->addValidation("equals[{$this->inputID}]");
        $slug = '_retype';
        $instance->inputName = $this->inputName . $slug;
        $instance->inputID = $this->inputID . $slug;
        $instance->labelID = $this->labelID . $slug;
        if (! empty($this->label)) {
            $instance->label = ! empty($this->field['retype_label']) ? $this->field['retype_label'] : sprintf(__('Retype %s', $userMeta->name), $this->label);
            $instance->label = isset($this->field['retype_field_title']) ? $this->field['retype_field_title'] : $instance->label;
        }
        if (! empty($this->placeholder)) {
            $instance->placeholder = sprintf(__('Retype %s', $userMeta->name), $this->placeholder);
            if (isset($this->field['retype_placeholder'])) {
                $instance->placeholder = $this->field['retype_placeholder'];
            }
        }
        $instance->_setInputClass();
        $instance->_setValidationClass();
        $instance->_setInputAttr();

        return $instance;
    }

    /**
     * Call parent method to render input with label
     */
    protected function buildInputWithLabel()
    {
        return parent::renderInputWithLabel();
    }
}