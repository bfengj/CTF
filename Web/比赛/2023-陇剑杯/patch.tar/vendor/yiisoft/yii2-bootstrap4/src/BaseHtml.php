<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\bootstrap4;

use yii\helpers\ArrayHelper;

/**
 * BaseHtml provides concrete implementation for [[Html]].
 *
 * Do not use BaseHtml. Use [[Html]] instead.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 */
class BaseHtml extends \yii\helpers\Html
{
    /**
     * @var int a counter used to generate [[id]] for widgets.
     * @internal
     */
    public static $counter = 0;
    /**
     * @var string the prefix to the automatically generated widget IDs.
     * @see getId()
     */
    public static $autoIdPrefix = 'i';
    /**
     * @var array list of tag attributes that should be specially handled when their values are of array type.
     * In particular, if the value of the `data` attribute is `['name' => 'xyz', 'age' => 13]`, two attributes
     * will be generated instead of one: `data-name="xyz" data-age="13"`.
     * @since 2.0.3
     */
    public static $dataAttributes = ['data', 'data-ng', 'ng', 'aria'];


    /**
     * Renders Bootstrap static form control.
     *
     * @param string $value static control value.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. There are also a special options:
     *
     * @return string generated HTML
     * @see https://getbootstrap.com/docs/4.5/components/forms/#readonly-plain-text
     */
    public static function staticControl($value, $options = [])
    {
        static::addCssClass($options, 'form-control-plaintext');
        $value = (string)$value;
        $options['readonly'] = true;
        return static::input('text', null, $value, $options);
    }

    /**
     * Generates a Bootstrap static form control for the given model attribute.
     * @param \yii\base\Model $model the model object.
     * @param string $attribute the attribute name or expression. See [[getAttributeName()]] for the format
     * about attribute expression.
     * @param array $options the tag options in terms of name-value pairs. See [[staticControl()]] for details.
     * @return string generated HTML
     * @see staticControl()
     */
    public static function activeStaticControl($model, $attribute, $options = [])
    {
        if (isset($options['value'])) {
            $value = $options['value'];
            unset($options['value']);
        } else {
            $value = static::getAttributeValue($model, $attribute);
        }
        return static::staticControl($value, $options);
    }

    /**
     * {@inheritdoc}
     */
    public static function radioList($name, $selection = null, $items = [], $options = [])
    {
        if (!isset($options['item'])) {
            $itemOptions = ArrayHelper::remove($options, 'itemOptions', []);
            $encode = ArrayHelper::getValue($options, 'encode', true);
            $options['item'] = function ($index, $label, $name, $checked, $value) use ($itemOptions, $encode) {
                $options = array_merge([
                    'class' => 'form-check-input',
                    'label' => $encode ? static::encode($label) : $label,
                    'labelOptions' => ['class' => 'form-check-label'],
                    'value' => $value
                ], $itemOptions);
                return '<div class="form-check">' . static::radio($name, $checked, $options) . '</div>';
            };
        }

        return parent::radioList($name, $selection, $items, $options);
    }

    /**
     * {@inheritdoc}
     */
    public static function checkboxList($name, $selection = null, $items = [], $options = [])
    {
        if (!isset($options['item'])) {
            $itemOptions = ArrayHelper::remove($options, 'itemOptions', []);
            $encode = ArrayHelper::getValue($options, 'encode', true);
            $options['item'] = function ($index, $label, $name, $checked, $value) use ($itemOptions, $encode) {
                $options = array_merge([
                    'class' => 'form-check-input',
                    'label' => $encode ? static::encode($label) : $label,
                    'labelOptions' => ['class' => 'form-check-label'],
                    'value' => $value
                ], $itemOptions);
                return '<div class="form-check">' . Html::checkbox($name, $checked, $options) . '</div>';
            };
        }

        return parent::checkboxList($name, $selection, $items, $options);
    }

    /**
     * @inheritdoc
     */
    protected static function booleanInput($type, $name, $checked = false, $options = [])
    {
        $options['checked'] = (bool)$checked;
        $value = array_key_exists('value', $options) ? $options['value'] : '1';
        if (isset($options['uncheck'])) {
            // add a hidden field so that if the checkbox is not selected, it still submits a value
            $hiddenOptions = [];
            if (isset($options['form'])) {
                $hiddenOptions['form'] = $options['form'];
            }
            $hidden = static::hiddenInput($name, $options['uncheck'], $hiddenOptions);
            unset($options['uncheck']);
        } else {
            $hidden = '';
        }
        if (isset($options['label'])) {
            $label = $options['label'];
            $labelOptions = isset($options['labelOptions']) ? $options['labelOptions'] : [];
            unset($options['label'], $options['labelOptions']);

            if (!isset($options['id'])) {
                $options['id'] = static::getId();
            }

            $input = static::input($type, $name, $value, $options);

            if (isset($labelOptions['wrapInput']) && $labelOptions['wrapInput']) {
                unset($labelOptions['wrapInput']);
                $content = static::label($input . $label, $options['id'], $labelOptions);
            } else {
                $content = $input . "\n" . static::label($label, $options['id'], $labelOptions);
            }
            return $hidden . $content;
        }

        return $hidden . static::input($type, $name, $value, $options);
    }

    /**
     * {@inheritdoc}
     */
    public static function error($model, $attribute, $options = [])
    {
        if (!array_key_exists('class', $options)) {
            $options['class'] = ['invalid-feedback'];
        }
        return parent::error($model, $attribute, $options);
    }

    /**
     * Returns an autogenerated ID
     * @return string Autogenerated ID
     */
    protected static function getId()
    {
        return static::$autoIdPrefix . static::$counter++;
    }
}
