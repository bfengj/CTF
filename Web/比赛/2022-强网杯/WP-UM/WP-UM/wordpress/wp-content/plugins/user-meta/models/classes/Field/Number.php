<?php
namespace UserMeta\Field;

/**
 * Handling number field.
 * 
 * @author Khaled Hossain
 * @since 2.2
 */
class Number extends Base
{

    protected function _configure_number()
    {
        $this->inputType = 'number';
        if (! empty($this->field['as_range'])) {
            $this->inputType = 'range';
        }

        if (! empty($this->field['integer_only'])) {
            $this->addValidation('custom[integer]');
        } else {
            $this->addValidation('custom[number]');
        }

        if (isset($this->field['min_number'])) {
            $this->addValidation("min[{$this->field['min_number']}]");
        }
        if (isset($this->field['max_number'])) {
            $this->addValidation("max[{$this->field['max_number']}]");
        }
    }

    protected function configure_number_()
    {
        if (empty($this->field['integer_only'])) {
            $this->inputAttr['step'] = 'any';
        }
        if (isset($this->field['min_number'])) {
            $this->inputAttr['min'] = $this->field['min_number'];
        }
        if (isset($this->field['max_number'])) {
            $this->inputAttr['max'] = $this->field['max_number'];
        }
        if (isset($this->field['step'])) {
            $this->inputAttr['step'] = $this->field['step'];
        }
    }
}