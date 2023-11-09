<?php
namespace UserMeta\Field;

use UserMeta\Html\Html;

/**
 * Handling image_url field.
 * 
 * @author Khaled Hossain
 * @since 2.2
 */
class ImageUrl extends Base
{

    protected function _configure_image_url()
    {
        $this->inputType = 'url';
        $this->addValidation('custom[url]');
    }

    protected function configure_image_url_()
    {
        if ($this->fieldValue) {
            $this->fieldResult = Html::img([
                'src' => $this->fieldValue
            ]);
        }
        $this->inputAttr['onblur'] = 'umShowImage(this)';
    }
}