<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\bootstrap4;

use yii\helpers\ArrayHelper;

/**
 * A Bootstrap 4 enhanced version of [[\yii\widgets\ActiveField]].
 *
 * This class adds some useful features to [[\yii\widgets\ActiveField|ActiveField]] to render all
 * sorts of Bootstrap 4 form fields in different form layouts:
 *
 * - [[inputTemplate]] is an optional template to render complex inputs, for example input groups
 * - [[horizontalCssClasses]] defines the CSS grid classes to add to label, wrapper, error and hint
 *   in horizontal forms
 * - [[inline]]/[[inline()]] is used to render inline [[checkboxList()]] and [[radioList()]]
 * - [[enableError]] can be set to `false` to disable to the error
 * - [[enableLabel]] can be set to `false` to disable to the label
 * - [[label()]] can be used with a `bool` argument to enable/disable the label
 *
 * There are also some new placeholders that you can use in the [[template]] configuration:
 *
 * - `{beginLabel}`: the opening label tag
 * - `{labelTitle}`: the label title for use with `{beginLabel}`/`{endLabel}`
 * - `{endLabel}`: the closing label tag
 * - `{beginWrapper}`: the opening wrapper tag
 * - `{endWrapper}`: the closing wrapper tag
 *
 * The wrapper tag is only used for some layouts and form elements.
 *
 * Note that some elements use slightly different defaults for [[template]] and other options.
 * You may want to override those predefined templates for checkboxes, radio buttons, checkboxLists
 * and radioLists in the [[\yii\widgets\ActiveForm::fieldConfig|fieldConfig]] of the
 * [[\yii\widgets\ActiveForm]]:
 *
 * - [[checkTemplate]] the default template for checkboxes and radios
 * - [[radioTemplate]] the template for radio buttons in default layout
 * - [[checkHorizontalTemplate]] the template for checkboxes in horizontal layout
 * - [[radioHorizontalTemplate]] the template for radio buttons in horizontal layout
 * - [[checkEnclosedTemplate]] the template for checkboxes and radios enclosed by label
 *
 * Example:
 *
 * ```php
 * use yii\bootstrap4\ActiveForm;
 *
 * $form = ActiveForm::begin(['layout' => 'horizontal']);
 *
 * // Form field without label
 * echo $form->field($model, 'demo', [
 *     'inputOptions' => [
 *         'placeholder' => $model->getAttributeLabel('demo'),
 *     ],
 * ])->label(false);
 *
 * // Inline radio list
 * echo $form->field($model, 'demo')->inline()->radioList($items);
 *
 * // Control sizing in horizontal mode
 * echo $form->field($model, 'demo', [
 *     'horizontalCssClasses' => [
 *         'wrapper' => 'col-sm-2',
 *     ]
 * ]);
 *
 * // With 'default' layout you would use 'template' to size a specific field:
 * echo $form->field($model, 'demo', [
 *     'template' => '{label} <div class="row"><div class="col-sm-4">{input}{error}{hint}</div></div>'
 * ]);
 *
 * // Input group
 * echo $form->field($model, 'demo', [
 *     'inputTemplate' => '<div class="input-group"><div class="input-group-prepend">
 *         <span class="input-group-text">@</span>
 *     </div>{input}</div>',
 * ]);
 *
 * ActiveForm::end();
 * ```
 *
 * @see \yii\bootstrap4\ActiveForm
 * @see https://getbootstrap.com/docs/4.5/components/forms/
 *
 * @author Michael Härtl <haertl.mike@gmail.com>
 * @author Simon Karlen <simi.albi@outlook.com>
 */
class ActiveField extends \yii\widgets\ActiveField
{
    /**
     * @var bool whether to render [[checkboxList()]] and [[radioList()]] inline.
     */
    public $inline = false;
    /**
     * @var string|null optional template to render the `{input}` placeholder content
     */
    public $inputTemplate;
    /**
     * @var array options for the wrapper tag, used in the `{beginWrapper}` placeholder
     */
    public $wrapperOptions = [];
    /**
     * {@inheritdoc}
     */
    public $options = ['class' => ['widget' => 'form-group']];
    /**
     * {@inheritdoc}
     */
    public $inputOptions = ['class' => ['widget' => 'form-control']];
    /**
     * @var array the default options for the input checkboxes. The parameter passed to individual
     * input methods (e.g. [[checkbox()]]) will be merged with this property when rendering the input tag.
     *
     * If you set a custom `id` for the input element, you may need to adjust the [[$selectors]] accordingly.
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     * @since 2.0.7
     */
    public $checkOptions = [
        'class' => ['widget' => 'custom-control-input'],
        'labelOptions' => [
            'class' => ['widget' => 'custom-control-label']
        ]
    ];
    /**
     * @var array the default options for the input radios. The parameter passed to individual
     * input methods (e.g. [[radio()]]) will be merged with this property when rendering the input tag.
     *
     * If you set a custom `id` for the input element, you may need to adjust the [[$selectors]] accordingly.
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     * @since 2.0.7
     */
    public $radioOptions = [
        'class' => ['widget' => 'custom-control-input'],
        'labelOptions' => [
            'class' => ['widget' => 'custom-control-label']
        ]
    ];
    /**
     * {@inheritdoc}
     */
    public $errorOptions = ['class' => 'invalid-feedback'];
    /**
     * {@inheritdoc}
     */
    public $labelOptions = [];
    /**
     * {@inheritdoc}
     */
    public $hintOptions = ['class' => ['widget' => 'form-text', 'text-muted'], 'tag' => 'small'];
    /**
     * @var null|array CSS grid classes for horizontal layout. This must be an array with these keys:
     *  - 'offset' the offset grid class to append to the wrapper if no label is rendered
     *  - 'label' the label grid class
     *  - 'wrapper' the wrapper grid class
     *  - 'error' the error grid class
     *  - 'hint' the hint grid class
     */
    public $horizontalCssClasses = [];
    /**
     * @var string the template for checkboxes in default layout
     */
    public $checkTemplate = "<div class=\"custom-control custom-checkbox\">\n{input}\n{label}\n{error}\n{hint}\n</div>";
    /**
     * @var string the template for radios in default layout
     * @since 2.0.5
     */
    public $radioTemplate = "<div class=\"custom-control custom-radio\">\n{input}\n{label}\n{error}\n{hint}\n</div>";
    /**
     * @var string the template for checkboxes and radios in horizontal layout
     */
    public $checkHorizontalTemplate = "{beginWrapper}\n<div class=\"custom-control custom-checkbox\">\n{input}\n{label}\n{error}\n{hint}\n</div>\n{endWrapper}";
    /**
     * @var string the template for checkboxes and radios in horizontal layout
     * @since 2.0.5
     */
    public $radioHorizontalTemplate = "{beginWrapper}\n<div class=\"custom-control custom-radio\">\n{input}\n{label}\n{error}\n{hint}\n</div>\n{endWrapper}";
    /**
     * @var string the `enclosed by label` template for checkboxes and radios in default layout
     */
    public $checkEnclosedTemplate = "<div class=\"form-check\">\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>";
    /**
     * @var bool whether to render the error. Default is `true` except for layout `inline`.
     */
    public $enableError = true;
    /**
     * @var bool whether to render the label. Default is `true`.
     */
    public $enableLabel = true;


    /**
     * {@inheritdoc}
     */
    public function __construct($config = [])
    {
        $layoutConfig = $this->createLayoutConfig($config);
        $config = ArrayHelper::merge($layoutConfig, $config);
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function render($content = null)
    {
        if ($content === null) {
            if (!isset($this->parts['{beginWrapper}'])) {
                $options = $this->wrapperOptions;
                $tag = ArrayHelper::remove($options, 'tag', 'div');
                $this->parts['{beginWrapper}'] = Html::beginTag($tag, $options);
                $this->parts['{endWrapper}'] = Html::endTag($tag);
            }
            if ($this->enableLabel === false) {
                $this->parts['{label}'] = '';
                $this->parts['{beginLabel}'] = '';
                $this->parts['{labelTitle}'] = '';
                $this->parts['{endLabel}'] = '';
            } elseif (!isset($this->parts['{beginLabel}'])) {
                $this->renderLabelParts();
            }
            if ($this->enableError === false) {
                $this->parts['{error}'] = '';
            }
            if ($this->inputTemplate) {
                $options = $this->inputOptions;

                if ($this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT) {
                    $this->addErrorClassIfNeeded($options);
                }
                $this->addAriaAttributes($options);

                $input = isset($this->parts['{input}'])
                    ? $this->parts['{input}']
                    : Html::activeTextInput($this->model, $this->attribute, $options);
                $this->parts['{input}'] = strtr($this->inputTemplate, ['{input}' => $input]);
            }
        }
        return parent::render($content);
    }

    /**
     * {@inheritdoc}
     */
    public function checkbox($options = [], $enclosedByLabel = false)
    {
        $checkOptions = $this->checkOptions;
        $options = ArrayHelper::merge($checkOptions, $options);
        Html::removeCssClass($options, 'form-control');
        $labelOptions = ArrayHelper::remove($options, 'labelOptions', []);
        $wrapperOptions = ArrayHelper::remove($options, 'wrapperOptions', []);
        $this->labelOptions = ArrayHelper::merge($this->labelOptions, $labelOptions);
        $this->wrapperOptions = ArrayHelper::merge($this->wrapperOptions, $wrapperOptions);

        if (!isset($options['template'])) {
            $this->template = ($enclosedByLabel) ? $this->checkEnclosedTemplate : $this->checkTemplate;
        } else {
            $this->template = $options['template'];
        }
        if ($this->form->layout === ActiveForm::LAYOUT_HORIZONTAL) {
            if (!isset($options['template'])) {
                $this->template = $this->checkHorizontalTemplate;
            }
            Html::removeCssClass($this->labelOptions, $this->horizontalCssClasses['label']);
            Html::addCssClass($this->wrapperOptions, $this->horizontalCssClasses['offset']);
        }
        unset($options['template']);

        if ($enclosedByLabel) {
            if (isset($options['label'])) {
                $this->parts['{labelTitle}'] = $options['label'];
            }
        }

        $this->addErrorClassIfNeeded($options);
        return parent::checkbox($options, false);
    }

    /**
     * {@inheritdoc}
     */
    public function radio($options = [], $enclosedByLabel = false)
    {
        $checkOptions = $this->radioOptions;
        $options = ArrayHelper::merge($checkOptions, $options);
        Html::removeCssClass($options, 'form-control');
        $labelOptions = ArrayHelper::remove($options, 'labelOptions', []);
        $wrapperOptions = ArrayHelper::remove($options, 'wrapperOptions', []);
        $this->labelOptions = ArrayHelper::merge($this->labelOptions, $labelOptions);
        $this->wrapperOptions = ArrayHelper::merge($this->wrapperOptions, $wrapperOptions);

        if (!isset($options['template'])) {
            $this->template = $enclosedByLabel ? $this->checkEnclosedTemplate : $this->radioTemplate;
        } else {
            $this->template = $options['template'];
        }
        if ($this->form->layout === ActiveForm::LAYOUT_HORIZONTAL) {
            if (!isset($options['template'])) {
                $this->template = $this->radioHorizontalTemplate;
            }
            Html::removeCssClass($this->labelOptions, $this->horizontalCssClasses['label']);
            Html::addCssClass($this->wrapperOptions, $this->horizontalCssClasses['offset']);
        }
        unset($options['template']);

        if ($enclosedByLabel && isset($options['label'])) {
            $this->parts['{labelTitle}'] = $options['label'];
        }

        $this->addErrorClassIfNeeded($options);
        return parent::radio($options, false);
    }

    /**
     * {@inheritdoc}
     */
    public function checkboxList($items, $options = [])
    {
        if (!isset($options['item'])) {
            $this->template = str_replace("\n{error}", '', $this->template);
            $itemOptions = isset($options['itemOptions']) ? $options['itemOptions'] : [];
            $encode = ArrayHelper::getValue($options, 'encode', true);
            $itemCount = count($items) - 1;
            $error = $this->error()->parts['{error}'];
            $options['item'] = function ($i, $label, $name, $checked, $value) use (
                $itemOptions,
                $encode,
                $itemCount,
                $error
            ) {
                $options = array_merge($this->checkOptions, [
                    'label' => $encode ? Html::encode($label) : $label,
                    'value' => $value
                ], $itemOptions);
                $wrapperOptions = ArrayHelper::remove($options, 'wrapperOptions', ['class' => ['custom-control', 'custom-checkbox']]);
                if ($this->inline) {
                    Html::addCssClass($wrapperOptions, 'custom-control-inline');
                }

                $this->addErrorClassIfNeeded($options);
                $html = Html::beginTag('div', $wrapperOptions) . "\n" .
                    Html::checkbox($name, $checked, $options) . "\n";
                if ($itemCount === $i) {
                    $html .= $error . "\n";
                }
                $html .= Html::endTag('div') . "\n";

                return $html;
            };
        }

        parent::checkboxList($items, $options);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function radioList($items, $options = [])
    {
        if (!isset($options['item'])) {
            $this->template = str_replace("\n{error}", '', $this->template);
            $itemOptions = isset($options['itemOptions']) ? $options['itemOptions'] : [];
            $encode = ArrayHelper::getValue($options, 'encode', true);
            $itemCount = count($items) - 1;
            $error = $this->error()->parts['{error}'];
            $options['item'] = function ($i, $label, $name, $checked, $value) use (
                $itemOptions,
                $encode,
                $itemCount,
                $error
            ) {
                $options = array_merge($this->radioOptions, [
                    'label' => $encode ? Html::encode($label) : $label,
                    'value' => $value
                ], $itemOptions);
                $wrapperOptions = ArrayHelper::remove($options, 'wrapperOptions', ['class' => ['custom-control', 'custom-radio']]);
                if ($this->inline) {
                    Html::addCssClass($wrapperOptions, 'custom-control-inline');
                }

                $this->addErrorClassIfNeeded($options);
                $html = Html::beginTag('div', $wrapperOptions) . "\n" .
                    Html::radio($name, $checked, $options) . "\n";
                if ($itemCount === $i) {
                    $html .= $error . "\n";
                }
                $html .= Html::endTag('div') . "\n";

                return $html;
            };
        }

        parent::radioList($items, $options);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function listBox($items, $options = [])
    {
        if ($this->form->layout === ActiveForm::LAYOUT_INLINE) {
            Html::removeCssClass($this->labelOptions, 'sr-only');
        }

        return parent::listBox($items, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function dropdownList($items, $options = [])
    {
        if ($this->form->layout === ActiveForm::LAYOUT_INLINE) {
            Html::removeCssClass($this->labelOptions, 'sr-only');
        }

        return parent::dropdownList($items, $options);
    }

    /**
     * Renders Bootstrap static form control.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. There are also a special options:
     *
     * - encode: bool, whether value should be HTML-encoded or not.
     *
     * @return $this the field object itself
     * @see https://getbootstrap.com/docs/4.5/components/forms/#readonly-plain-text
     */
    public function staticControl($options = [])
    {
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activeStaticControl($this->model, $this->attribute, $options);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function label($label = null, $options = [])
    {
        if (is_bool($label)) {
            $this->enableLabel = $label;
            if ($label === false && $this->form->layout === ActiveForm::LAYOUT_HORIZONTAL) {
                Html::addCssClass($this->wrapperOptions, $this->horizontalCssClasses['offset']);
            }
        } else {
            $this->enableLabel = true;
            $this->renderLabelParts($label, $options);
            parent::label($label, $options);
        }
        return $this;
    }

    /**
     * @param bool $value whether to render a inline list
     * @return $this the field object itself
     * Make sure you call this method before [[checkboxList()]] or [[radioList()]] to have any effect.
     */
    public function inline($value = true)
    {
        $this->inline = (bool)$value;
        return $this;
    }

    /**
     * @param array $instanceConfig the configuration passed to this instance's constructor
     * @return array the layout specific default configuration for this instance
     */
    protected function createLayoutConfig($instanceConfig)
    {
        $config = [
            'hintOptions' => [
                'tag' => 'small',
                'class' => ['form-text', 'text-muted'],
            ],
            'errorOptions' => [
                'tag' => 'div',
                'class' => 'invalid-feedback',
            ],
            'inputOptions' => [
                'class' => 'form-control'
            ],
            'labelOptions' => [
                'class' => []
            ]
        ];

        $layout = $instanceConfig['form']->layout;

        if ($layout === ActiveForm::LAYOUT_HORIZONTAL) {
            $config['template'] = "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}";
            $config['wrapperOptions'] = [];
            $config['labelOptions'] = [];
            $config['options'] = [];
            $cssClasses = [
                'offset' => ['col-sm-10', 'offset-sm-2'],
                'label' => ['col-sm-2', 'col-form-label'],
                'wrapper' => 'col-sm-10',
                'error' => '',
                'hint' => '',
                'field' => 'form-group row'
            ];
            if (isset($instanceConfig['horizontalCssClasses'])) {
                $cssClasses = ArrayHelper::merge($cssClasses, $instanceConfig['horizontalCssClasses']);
            }
            $config['horizontalCssClasses'] = $cssClasses;

            Html::addCssClass($config['wrapperOptions'], $cssClasses['wrapper']);
            Html::addCssClass($config['labelOptions'], $cssClasses['label']);
            Html::addCssClass($config['errorOptions'], $cssClasses['error']);
            Html::addCssClass($config['hintOptions'], $cssClasses['hint']);
            Html::addCssClass($config['options'], $cssClasses['field']);
        } elseif ($layout === ActiveForm::LAYOUT_INLINE) {
            $config['inputOptions']['placeholder'] = true;
            $config['enableError'] = false;

            Html::addCssClass($config['labelOptions'], ['screenreader' => 'sr-only']);
        }

        return $config;
    }

    /**
     * {@inheritdoc}
     */
    public function fileInput($options = [])
    {
        Html::addCssClass($options, ['widget' => 'form-control-file']);
        return parent::fileInput($options);
    }

    /**
     * @param string|null $label the label or null to use model label
     * @param array $options the tag options
     */
    protected function renderLabelParts($label = null, $options = [])
    {
        $options = array_merge($this->labelOptions, $options);
        if ($label === null) {
            if (isset($options['label'])) {
                $label = $options['label'];
                unset($options['label']);
            } else {
                $attribute = Html::getAttributeName($this->attribute);
                $label = Html::encode($this->model->getAttributeLabel($attribute));
            }
        }
        if (!isset($options['for'])) {
            $options['for'] = Html::getInputId($this->model, $this->attribute);
        }
        $this->parts['{beginLabel}'] = Html::beginTag('label', $options);
        $this->parts['{endLabel}'] = Html::endTag('label');
        if (!isset($this->parts['{labelTitle}'])) {
            $this->parts['{labelTitle}'] = $label;
        }
    }
}
