<?php
namespace UserMeta\Field;

use UserMeta\Html\Html;

/**
 * Handle custom field.
 *
 * @author Khaled Hossain
 * @since 1.2.0
 */
class Custom extends Multiple
{

    protected $inputType = 'text';

    protected function _configure()
    {
        if (! empty($this->field['input_type'])) {
            $this->inputType = $this->field['input_type'];
            if ('password' == $this->inputType) {
                $this->field['field_value'] = '';
            }
        }
    }

    protected function configure_()
    {
        if (! empty($this->field['regex'])) {
            $this->inputAttr['pattern'] = $this->field['regex'];
            $this->inputAttr['oninput'] = "setCustomValidity('')";
        }
        if (! empty($this->field['error_text'])) {
            $this->inputAttr['oninvalid'] = "setCustomValidity('{$this->field['error_text']}')";
        }
    }

    protected function renderInputWithLabel()
    {
        $html = $this->buildInputWithLabel();
        if ('email' == $this->inputType && ! empty($this->field['retype_email'])) {
            $html .= Html::p($this->getRetypeInstance()->buildInputWithLabel());
        } elseif ('password' == $this->inputType && ! empty($this->field['retype_password'])) {
            $html .= Html::p($this->getRetypeInstance()->buildInputWithLabel());
        }

        return $html;
    }
}