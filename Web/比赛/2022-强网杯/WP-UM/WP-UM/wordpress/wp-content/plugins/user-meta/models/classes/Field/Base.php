<?php
namespace UserMeta\Field;

use UserMeta\Html\Html;

/**
 * Base class for creating single field, based on provided arguments.
 * See `Field\Generate` class to generate field html.
 *
 * @author Khaled Hossain
 * @since 1.2.0
 */
abstract class Base
{

    /**
     *
     * @var array Field's data.
     */
    protected $field = [];

    /**
     *
     * @var string Field type for the plugin.
     */
    protected $fieldType;

    /**
     * Saved or calculated field value for showing default
     *
     * @var mixed
     */
    protected $fieldValue;

    /**
     *
     * @var string Html input type.
     */
    protected $inputType;

    /**
     *
     * @var array Html input attributes.
     */
    protected $inputAttr = [];

    /**
     *
     * @var string title position attribute.
     */
    protected $titlePosition;

    /**
     *
     * @var string Html input name attribute.
     */
    protected $inputName;

    /**
     *
     * @var string Html input id attribute.
     */
    protected $inputID;

    /**
     *
     * @var array Html input classes.
     */
    protected $inputClass = [];

    /**
     *
     * @var string Html input style attribute.
     */
    protected $inputStyle;

    /**
     *
     * @var string String before input.
     */
    protected $inputBefore;

    /**
     *
     * @var string String after input.
     */
    protected $inputAfter;

    /**
     *
     * @var string Text for label.
     */
    protected $label;

    /**
     *
     * @var string id attribute for label.
     */
    protected $labelID;

    /**
     *
     * @var array Html classess for label.
     */
    protected $labelClass = [];

    /**
     *
     * @var array Attributes for description.
     */
    protected $descriptionAttr = [];

    /**
     *
     * @var array Attributes for container div.
     */
    protected $containerAttr = [];

    /**
     *
     * @var string placeholder text for input.
     */
    protected $placeholder;

    /**
     * Is read-only bool | readonly.
     *
     * @var string
     */
    protected $readOnly;

    /**
     *
     * @var array Validations rules
     */
    protected $validations = [];

    /**
     *
     * @var string registration | profile
     */
    protected $actionType;

    /**
     * Unique id based on form name.
     *
     * @var string
     */
    protected $uniqueID;

    /**
     * User ID whos data is showing
     *
     * @var int
     */
    protected $userID;

    /**
     * Use like field after.
     *
     * @var string
     */
    protected $fieldResult;

    /**
     *
     * @var string
     */
    protected $moreContent;

    /**
     * Javascript code to add.
     *
     * @var string
     */
    protected $javascript;

    /**
     * Check if the field is qualified for further processing
     *
     * @var boolean
     */
    protected $isQualified = true;

    protected $requiredAttrs = [
        'id',
        'field_type'
    ];

    /**
     *
     * @param array $field:
     *            field's data
     * @param array $extra:
     *            Extra configurations.
     */
    public function __construct(array $field, array $extra)
    {
        $this->field = $field;
        $this->setExtra($extra);
        if (! $this->isQualified())
            return;

        $this->callMethod('_configure');
        $preMethodName = '_configure_' . $this->field['field_type'];
        $this->callMethod($preMethodName);

        $this->configure();

        $postMethodName = 'configure_' . $this->fieldType . '_';
        $this->callMethod($postMethodName);
        $this->callMethod('configure_');
    }

    /**
     * Set extra configurations.
     *
     * @param array $extra:
     *            Extra configurations.
     */
    protected function setExtra(array $extra)
    {
        $validOptions = [
            'actionType',
            'uniqueID',
            'userID'
        ];
        foreach ($validOptions as $option) {
            if (isset($extra[$option])) {
                $this->$option = $extra[$option];
            }
        }
    }

    /**
     * Check if we need to continue processing.
     *
     * @return bool
     */
    protected function isQualified()
    {
        global $userMeta;
        foreach ($this->requiredAttrs as $attr) {
            if (empty($this->field[$attr])) {
                return $this->isQualified = false;
            }
        }
        if (! empty($this->field['admin_only'])) {
            if (! $userMeta->isAdmin()) {
                return $this->isQualified = false;
            }
        }
        if (! empty($this->field['non_admin_only'])) {
            if ($userMeta->isAdmin($this->userID)) {
                return $this->isQualified = false;
            }
        }

        return $this->isQualified;
    }

    /**
     * Call method from the object.
     *
     * @param string $methodName
     *            : Name of method to call
     * @param string $defaultMethod
     *            : Name of default method to call if provided methodName is not exists
     */
    protected function callMethod($methodName, $defaultMethod = null)
    {
        if (method_exists($this, $methodName)) {
            $this->$methodName();
        } elseif ($defaultMethod) {
            $this->$defaultMethod();
        }
    }

    /*
     * Calling all _setXYZ method by declared order.
     */
    protected function configure()
    {
        $methods = get_class_methods(__CLASS__);
        foreach ($methods as $key => $method) {
            if (strpos($method, '_set') === 0) {
                $this->callMethod($method . "_{$this->fieldType}", $method);
            }
        }
    }

    protected function _setFieldType()
    {
        $this->fieldType = $this->field['field_type'];
    }

    protected function _setFieldValue()
    {
        $this->fieldValue = isset($this->field['field_value']) ? $this->field['field_value'] : null;
    }

    protected function _setTitlePosition()
    {
        $this->titlePosition = isset($this->field['title_position']) ? $this->field['title_position'] : null;
    }

    protected function _setInputName()
    {
        $this->inputName = isset($this->field['field_name']) ? $this->field['field_name'] : null;
    }

    protected function _setInputID()
    {
        $this->inputID = empty($this->field['input_id']) ? "um_field_{$this->field['id']}_{$this->uniqueID}" : $this->field['input_id'];
    }

    protected function _setInputClass()
    {
        $this->addInputClass("um_field_{$this->field['id']}");
        $this->addInputClass('um_input');
        if (! empty($this->field['field_class'])) {
            $this->addInputClass($this->field['field_class']);
        }
        if (! empty($this->field['is_parent'])) {
            $this->addInputClass('um_parent');
        }
    }

    protected function _setInputStyle()
    {
        if (! empty($this->field['field_style'])) {
            $this->inputStyle .= $this->field['field_style'];
        }
        if (! empty($this->field['field_size'])) {
            $this->inputStyle .= "width:{$this->field['field_size']};";
        }
        if (! empty($this->field['field_height'])) {
            $this->inputStyle .= "height:{$this->field['field_height']};";
        }
    }

    protected function _setInputBefore()
    {
        $this->inputBefore = ! empty($this->field['before']) ? $this->field['before'] : '';
    }

    protected function _setInputAfter()
    {
        $this->inputAfter = ! empty($this->field['after']) ? $this->field['after'] : '';
    }

    protected function _setLabel()
    {
        if (isset($this->field['field_title'])) {
            $this->label = $this->field['field_title'];
        }
    }

    protected function _setLabelID()
    {
        $this->labelID = empty($this->field['label_id']) ? $this->inputID . '_label' : $this->field['label_id'];
    }

    protected function _setLabelClass()
    {
        if (! empty($this->field['label_class'])) {
            $this->addLabelClass($this->field['label_class']);
        }
        if ($this->titlePosition == 'top') {
            $this->addLabelClass('um_label_top');
        } elseif ($this->titlePosition == 'left') {
            $this->addLabelClass('um_label_left');
        } elseif ($this->titlePosition == 'right') {
            $this->addLabelClass('um_label_right');
        } elseif ($this->titlePosition == 'inline') {
            $this->addLabelClass('um_label_inline');
        } elseif ($this->titlePosition == 'hidden') {
            $this->addLabelClass('um_hidden');
        }

        if (empty($this->labelClass)) {
            $this->addLabelClass('pf_label');
        }
    }

    protected function _setDescriptionID()
    {
        $this->descriptionAttr['id'] = empty($this->field['description_id']) ? "{$this->inputID}_description" : $this->field['description_id'];
    }

    protected function _setDescriptionClass()
    {
        $this->descriptionAttr['class'] = ! empty($this->field['description_class']) ? $this->field['description_class'] : 'um_description';
        if ($this->titlePosition == 'left') {
            $this->descriptionAttr['class'] .= ' um_left_margin';
        }
    }

    protected function _setDescriptionStyle()
    {
        if (isset($this->field['description_style'])) {
            $this->descriptionAttr['style'] = $this->field['description_style'];
        }
    }

    protected function _setDivClass()
    {
        $class = [];
        $class[] = 'um_field_container';
        if (isset($this->field['css_class'])) {
            $class[] = $this->field['css_class'];
        }
        if (! empty($this->titlePosition) && $this->titlePosition == 'inline') {
            $class[] = 'um_inline';
        }
        if (! empty($this->field['is_hide'])) {
            $class[] = 'um_hidden';
        }
        $this->containerAttr['class'] = implode(' ', $class);
    }

    protected function _setDivStyle()
    {
        if (isset($this->field['css_style'])) {
            $this->containerAttr['style'] = "{$this->field['css_style']}";
        }
    }

    /**
     * Set placeholder property.
     *
     * If 'Placeholder' is selected as 'Label Position', use 'Field Label' as placeholder.
     */
    protected function _setPlaceholder()
    {
        if (isset($this->field['placeholder'])) {
            $this->placeholder = $this->field['placeholder'];
        } elseif ('placeholder' == $this->titlePosition) {
            $this->placeholder = isset($this->field['field_title']) ? $this->field['field_title'] : null;
        }
    }

    protected function _setReadOnly()
    {
        global $userMeta;
        if (! empty($this->field['read_only_non_admin'])) {
            if (! ($userMeta->isAdmin())) {
                $this->setReadOnly();
            }
        }
        if (! empty($this->field['read_only'])) {
            $this->setReadOnly();
        }
    }

    protected function _setRequired()
    {
        if (! empty($this->field['required'])) {
            $this->setRequired();
        }
    }

    protected function _setValidationClass()
    {
        if (! empty($this->validations)) {
            $this->addInputClass('validate[' . implode(',', $this->validations) . ']');
        }
    }

    protected function _setInputAttr()
    {
        global $userMeta;
        $this->inputAttr = array(
            'name' => $this->inputName,
            'readonly' => ! empty($this->readOnly) ? $this->readOnly : '',
            'id' => $this->inputID,
            'class' => implode(' ', $this->inputClass),
            'style' => $this->inputStyle,
            'maxlength' => ! empty($this->field['max_char']) ? $this->field['max_char'] : null,
            // "option_after" => isset( $option_after ) ? $option_after : "",
            'before' => $this->inputBefore,
            'after' => $this->inputAfter,
            'placeholder' => __($this->placeholder, $userMeta->name)
        );
        $this->inputAttr = $userMeta->arrayRemoveEmptyValue($this->inputAttr);
    }

    protected function setDisabled()
    {
        if (! empty($this->inputAttr['readonly'])) {
            unset($this->inputAttr['readonly']);
            $this->inputAttr['disabled'] = 'disabled';
        }
    }

    protected function setReadOnly()
    {
        $this->readOnly = 'readonly';
    }

    protected function setRequired()
    {
        $this->addValidation('required');
    /**
     * As we did not added support translation of html5 required validation message, so we move only to old style js validation.
     * $attr['required'] = 'required';
     */
    }

    protected function addInputClass($itm)
    {
        $this->addToProperty('inputClass', $itm);
    }

    protected function addLabelClass($itm)
    {
        $this->addToProperty('labelClass', $itm);
    }

    protected function addValidation($rule)
    {
        $this->addToProperty('validations', $rule);
    }

    private function addToProperty($propertyName, $itm)
    {
        if (! in_array($itm, $this->$propertyName)) {
            array_push($this->$propertyName, $itm);
        }
    }

    /**
     * Render label for field.
     *
     * Default config has been used if $attr is not provided.
     *
     * @todo This param is not used so far. Remove if needed
     * @param array $attr:label,id,class,for
     */
    protected function renderLabel($attr = [])
    {
        global $userMeta;
        /**
         * Expecting $attr:label,id,class,for
         */
        extract($attr);

        if (! empty($this->titlePosition) && in_array($this->titlePosition, [
            'none',
            'placeholder'
        ])) {
            return;
        }
        $labelClass = ! empty($class) ? $class : $this->labelClass;

        return Html::label(__(! empty($label) ? $label : $this->label, $userMeta->name), [
            'id' => ! empty($id) ? $id : $this->labelID,
            'class' => implode(' ', $labelClass),
            'for' => ! empty($for) ? $for : $this->inputID
        ]);
    }

    protected function renderDescription()
    {
        global $userMeta;
        $element = 'inline' == $this->titlePosition ? 'span' : 'p';
        if (isset($this->field['description'])) {
            return Html::tag($element, __($this->field['description'], $userMeta->name), $this->descriptionAttr);
        }
    }

    /**
     *
     * @todo : Check again
     */
    protected function renderInput()
    {
        $methodName = 'render_input_' . $this->fieldType;
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        return Html::input($this->inputType, $this->fieldValue, $this->inputAttr);
    }

    protected function renderInputWithLabel()
    {
        return $this->renderLabel() . $this->renderInput();
    }

    protected function getConditionScript()
    {
        if (! empty($this->field['condition'])) {
            return '<script type="text/json" class="um_condition">' . json_encode($this->field['condition']) . '</script>';
        }
    }

    /**
     * Render complete html
     * Blogname, PageHeading and SectionHeading has different render method
     */
    public function render()
    {
        if (! $this->isQualified)
            return;

        $html = '';
        $html .= $this->renderInputWithLabel();
        $html .= $this->renderDescription();
        if (! empty($this->fieldResult)) {
            $this->fieldResult = Html::div($this->fieldResult, [
                'id' => $this->inputID . '_result',
                'class' => 'um_field_result'
            ]);
        }
        $html .= $this->fieldResult;
        $html .= $this->getConditionScript();

        if (! empty($this->javascript)) {
            \UserMeta\addFooterJs($this->javascript);
        }

        return Html::div($html, $this->containerAttr);
    }
}
