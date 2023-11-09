<?php
namespace UserMeta\Field;

use UserMeta\Html\Html;

/**
 * Handle following text like field:
 * user_login
 * user_url
 * display_name
 * nickname
 * first_name
 * last_name
 * user_registered
 * jabber
 * aim
 * yim
 * text
 * hidden
 * phone
 * url
 *
 * @todo Implement for text type
 *      
 * @author Khaled Hossain
 * @since 1.2.0
 */
class Text extends Base
{

    protected $inputType = 'text';

    protected function _configure_user_login()
    {
        if ('profile' == $this->actionType) {
            $this->setReadOnly();
        }
        $this->setRequired();
    }

    protected function _configure_user_url()
    {
        $this->inputType = 'url';
        $this->addValidation('custom[url]');
    }

    protected function _configure_user_registered()
    {
        $this->addValidation('custom[datetime]');
        if (empty($this->field['field_options']['dateFormat']))
            $this->field['field_options']['dateFormat'] = 'yy-mm-dd';
        if (empty($this->field['field_options']['timeFormat']))
            $this->field['field_options']['timeFormat'] = 'hh:mm:ss';
        if (! isset($this->field['field_options']['changeYear']))
            $this->field['field_options']['changeYear'] = true;
    }

    protected function configure_user_registered_()
    {
        $this->javascript .= 'jQuery("#' . $this->inputID . '").datetimepicker(' . json_encode($this->field['field_options']) . ');';
        if ($this->readOnly)
            $this->inputAttr['disabled'] = 'disabled';
        $this->inputAttr['readonly'] = 'readonly';
    }

    protected function render_input_textarea()
    {
        return Html::textarea($this->fieldValue, $this->inputAttr);
    }

    protected function _configure_hidden()
    {
        $this->inputType = 'hidden';
    }

    protected function _configure_phone()
    {
        $this->addValidation('custom[phone]');
    }

    protected function _configure_url()
    {
        $this->inputType = 'url';
        $this->addValidation('custom[url]');
    }
}
