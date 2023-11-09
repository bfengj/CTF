<?php
namespace UserMeta\Field;

/**
 * Handling html field.
 * 
 * @author Khaled Hossain
 * @since 2.2
 */
class Html extends Base
{

    protected function render_input_html()
    {
        return $this->fieldValue ? html_entity_decode($this->fieldValue) : '';
    }
}