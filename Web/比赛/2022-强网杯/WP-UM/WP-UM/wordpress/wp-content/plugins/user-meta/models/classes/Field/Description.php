<?php
namespace UserMeta\Field;

use UserMeta\Html\Html;

/**
 * Handle description and rich_text field.
 *
 * @author Khaled Hossain
 * @since 1.2.0
 */
class Description extends Base
{

    protected function configure_description_()
    {
        if (! empty($this->field['rich_text']))
            $this->configure_rich_text_();
    }

    protected function configure_rich_text_()
    {
        if (isset($this->field['title_position']) && 'left' == $this->field['title_position']) {
            $this->inputBefore .= '<div class="um_left_margin">';
            $this->inputAfter .= '</div>';
        }

        /**
         * Default settings for wp_editor()
         *
         * @var array $settings
         */
        $settings = [
            'textarea_name' => $this->inputName,
            'media_buttons' => true,
            'tinymce' => true,
            'quicktags' => true,
            'editor_height' => ! empty($this->field['field_height']) ? str_replace('px', '', $this->field['field_height']) : null,
            'editor_class' => ! empty($this->field['field_class']) ? $this->field['field_class'] : null,
            'editor_css' => ! empty($this->field['field_style']) ? $this->field['field_style'] : null
        ];
        if (\UserMeta\isValuedArray('field_options', $this->field))
            $settings = array_merge($settings, $this->field['field_options']);

        if (! empty($this->readOnly)) {
            $settings['media_buttons'] = false;
            $settings['tinymce'] = false;
            $settings['quicktags'] = false;
        }

        $this->field['field_options'] = $settings;
    }

    private function renderRichText()
    {
        $editorID = preg_replace("/[^a-z0-9 ]/", '', strtolower($this->inputID));
        $settings = isset($this->field['field_options']) ? $this->field['field_options'] : [];

        ob_start();
        wp_editor($this->fieldValue, $editorID, $settings);
        $editorOutput = $this->inputBefore . ob_get_clean() . $this->inputAfter;

        if (! empty($this->readOnly)) {
            $this->javascript = "jQuery('#wp-{$editorID}-editor-container textarea').attr('readonly','readonly');";
        }

        return ! empty($this->field['field_size']) ? "<div style=\"width:{$this->field['field_size']}\">$editorOutput</div>" : $editorOutput;
    }

    protected function renderInput()
    {
        if (('description' == $this->fieldType) && empty($this->field['rich_text']))
            return Html::textarea($this->fieldValue, $this->inputAttr);

        /**
         * Commented since 1.4
         * if (! empty($this->field['use_previous_editor'])) {
         * $this->inputAttr['class'] .= ' um_rich_text';
         * return Html::textarea($this->fieldValue, $this->inputAttr);
         * }
         */

        return $this->renderRichText();
    }
}