<?php
namespace UserMeta\Field;

use UserMeta\Html\Html;

/**
 * Handle user_avatar and file field.
 *
 * @author Khaled Hossain
 * @since 1.2.0
 */
class File extends Base
{

    protected $inputType = 'file';

    protected $form;

    protected function setExtra(array $extra)
    {
        parent::setExtra($extra);
        if (isset($extra['form'])) {
            $this->form = $extra['form'];
        }
    }

    protected function configure_()
    {
        $this->fieldResult = $this->getFile();
        if (isset($this->field['title_position']) && 'left' == $this->field['title_position']) {
            $this->fieldResult = Html::div($this->fieldResult, [
                'class' => 'um_left_margin'
            ]);
        }
    }

    private function getFile()
    {
        $avatar = null;
        if ('user_avatar' == $this->fieldType) {
            if (empty($this->field['field_value'])) {
                if (empty($this->field['hide_default_avatar'])) {
                    $avatar = ('registration' == $this->actionType) ? get_avatar('nobody@noemail') : get_avatar($this->userID);
                }
            }
        }
        if ($avatar) {
            return $avatar;
        }
        $file = new \UserMeta\File($this->field);
        return $file->showFile();
    }

    protected function renderInput()
    {
        if ($this->readOnly) {
            return;
        }
        if (! empty($this->field['disable_ajax'])) {
            return parent::renderInput();
        }
        $leftClass = isset($this->field['title_position']) && $this->field['title_position'] == 'left' ? 'um_left_margin' : '';
        return Html::div(null, [
            'id' => $this->inputID,
            'um_field_id' => 'um_field_' . $this->field['id'],
            'um_form_key' => $this->form['form_key'],
            'name' => $this->inputName,
            'class' => 'um_file_uploader_field ' . $leftClass,
            'extension' => ! empty($this->field['allowed_extension']) ? $this->field['allowed_extension'] : 'jpg,jpeg,png,gif',
            'maxsize' => ! empty($this->field['max_file_size']) ? $this->field['max_file_size'] * 1024 : 1 * 1024 * 1024
        ]);
    }
}
