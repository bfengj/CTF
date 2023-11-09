<?php
namespace UserMeta\Field;

/**
 * Handle datetime field.
 *
 * @author Khaled Hossain
 * @since 1.2.0
 */
class Datetime extends Base
{

    protected function configure_()
    {
        $dateFormat = ! empty($this->field['date_format']) ? $this->field['date_format'] : 'yy-mm-dd';
        if (! isset($this->field['field_options']['yearRange'])) {
            $this->field['field_options']['yearRange'] = ! empty($this->field['year_range']) ? $this->field['year_range'] : '1950:c';
        }
        if (empty($this->field['datetime_selection'])) {
            $this->field['datetime_selection'] = 'date';
        }

        if ($this->field['datetime_selection'] == 'date') {
            if (empty($this->field['field_options']['dateFormat']))
                $this->field['field_options']['dateFormat'] = $dateFormat;
            if (! isset($this->field['field_options']['changeYear']))
                $this->field['field_options']['changeYear'] = true;
            $methodName = 'datepicker';
        } elseif ($this->field['datetime_selection'] == 'datetime') {
            if (empty($this->field['field_options']['dateFormat']))
                $this->field['field_options']['dateFormat'] = $dateFormat;
            if (empty($this->field['field_options']['timeFormat']))
                $this->field['field_options']['timeFormat'] = 'hh:mm:ss';
            if (! isset($this->field['field_options']['changeYear']))
                $this->field['field_options']['changeYear'] = true;
            $methodName = 'datetimepicker';
        } elseif ($this->field['datetime_selection'] == 'time') {
            if (empty($this->field['field_options']['timeFormat']))
                $this->field['field_options']['timeFormat'] = 'hh:mm:ss';
            $methodName = 'timepicker';
        }
        if (! empty($methodName)) {
            $jsMethod = $methodName . '(' . json_encode($this->field['field_options']) . ');';
            $this->javascript .= 'jQuery("#' . $this->inputID . '").' . $jsMethod;
        }
        $this->setDisabled();
        if (empty($this->field['allow_custom'])) {
            $this->inputAttr['readonly'] = 'readonly';
        }
    }
}