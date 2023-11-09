<?php
namespace UserMeta\Field;

use UserMeta\Html\Html;

/**
 * Handle user_email and email field.
 *
 * @author Khaled Hossain
 * @since 1.2.0
 */
class Email extends Multiple
{

    protected $inputType = 'email';

    /**
     * Hold original object after configuring everythig.
     *
     * @var object
     */
    protected $original;

    protected function _configure_user_email()
    {
        $this->setRequired();
        $this->addValidation('custom[email]');
    }

    /**
     * Backward compatibility of email field
     */
    protected function _configure_email()
    {
        $this->addValidation('custom[email]');
    }

    protected function renderInputWithLabel()
    {
        $html = $this->buildInputWithLabel();
        if (! empty($this->field['retype_email'])) {
            $html .= Html::p($this->getRetypeInstance()->buildInputWithLabel());
        }

        return $html;
    }
}