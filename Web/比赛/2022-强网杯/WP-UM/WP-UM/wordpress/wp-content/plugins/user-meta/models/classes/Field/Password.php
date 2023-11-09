<?php
namespace UserMeta\Field;

use UserMeta\Html\Html;

/**
 * Handle user_pass and password field.
 *
 * @author Khaled Hossain
 * @since 1.2.0
 */
class Password extends Multiple
{

    protected $inputType = 'password';

    /**
     * Determine if current password is required
     *
     * @var boolean
     */
    private $requiredCurrent;

    protected function _configure_user_pass()
    {
        if ('registration' == $this->actionType) {
            $this->setRequired();
        }
        $this->field['field_value'] = '';
    }

    protected function configure_()
    {
        if (! empty($this->field['password_strength'])) {
            $this->javascript .= 'jQuery("#' . $this->inputID . '").password_strength();';
        }

        if (! empty($this->field['required_current_password']) && ('profile' == $this->actionType)) {
            $this->requiredCurrent = true;
        }
    }

    private function getCurrentPasswordInstance()
    {
        global $userMeta;

        $instance = $this->getNewInstance();
        $instance->inputClass = $this->validations = [];
        $instance->addValidation('funcCall[umConditionalRequired]');
        $slug = '_current';
        $instance->inputName = $this->inputName . $slug;
        $instance->inputID = $this->inputID . $slug;
        $instance->labelID = $this->labelID . $slug;
        if (! empty($this->label)) {
            $instance->label = isset($this->field['current_pass_title']) ? $this->field['current_pass_title'] : sprintf(__("Current %s", $userMeta->name), $this->label);
        }
        if (! empty($this->placeholder)) {
            $instance->placeholder = sprintf(__('Current %s', $userMeta->name), $this->placeholder);
            if (isset($this->field['current_pass_placeholder'])) {
                $instance->placeholder = $this->field['current_pass_placeholder'];
            }
        }
        $instance->_setInputClass();
        $instance->_setValidationClass();
        $instance->_setInputAttr();

        return $instance;
    }

    private function getNewPasswordInstance()
    {
        global $userMeta;

        $instance = $this->getNewInstance();
        if ($this->requiredCurrent) {
            if (! empty($this->label)) {
                $instance->label = isset($this->field['new_pass_title']) ? $this->field['new_pass_title'] : sprintf(__("New %s", $userMeta->name), $this->label);
            }
            if (! empty($this->placeholder)) {
                $instance->placeholder = isset($this->field['new_pass_placeholder']) ? $this->field['new_pass_placeholder'] : sprintf(__("New %s", $userMeta->name), $this->placeholder);
            }
            $instance->_setInputAttr();
        }
        if (! empty($this->field['regex'])) {
            $instance->inputAttr['pattern'] = $this->field['regex'];
            $instance->inputAttr['oninput'] = "setCustomValidity('')";
        }

        if (! empty($this->field['error_text'])) {
            $instance->inputAttr['oninvalid'] = "setCustomValidity('{$this->field['error_text']}')";
        }

        return $instance;
    }

    protected function renderInputWithLabel()
    {
        $html = null;
        if ($this->requiredCurrent) {
            $html .= Html::p($this->getCurrentPasswordInstance()->buildInputWithLabel());
        }
        $html .= $this->getNewPasswordInstance()->buildInputWithLabel();
        if (! empty($this->field['retype_password'])) {
            $html .= Html::p($this->getRetypeInstance()->buildInputWithLabel());
        }

        return $html;
    }
}