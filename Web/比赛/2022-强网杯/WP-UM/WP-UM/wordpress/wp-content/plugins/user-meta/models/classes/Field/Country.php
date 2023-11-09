<?php
namespace UserMeta\Field;

use UserMeta\Html\Html;

/**
 * Handling country field.
 * 
 * @author Khaled Hossain
 * @since 2.2
 */
class Country extends Base
{

    protected function _configure_country()
    {
        $this->inputType = 'select';
    }

    protected function configure_country_()
    {
        $this->setDisabled();
    }

    protected function render_input_country()
    {
        global $userMeta;
        $options = $userMeta->countryArray();
        if (isset($this->field['country_selection_type']) 
            && 'by_country_name' == $this->field['country_selection_type']) {
            $options = array_combine(array_values($options), array_values($options));
        }
        $options = array_merge([
            '' => ''
        ], $options);

        return Html::select($this->fieldValue, $this->inputAttr, $options);
    }
}