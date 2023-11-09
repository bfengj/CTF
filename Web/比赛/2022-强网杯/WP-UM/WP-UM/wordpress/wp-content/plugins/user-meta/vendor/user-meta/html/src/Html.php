<?php
namespace UserMeta\Html;

/*
 * Class for html form builder.
 *
 * @author Khaled Hossain
 * @since 1.0.0
 */
class Html
{
    use Config, OptionsElement, Tag;

    /**
     * Input type.
     */
    public $type;

    /**
     * Default value.
     */
    public $default;

    /**
     * Input attributes.
     */
    public $attributes = [];

    /**
     * Options array for select | multiselect | radio | checkboxList.
     */
    public $options = [];

    /**
     * Construct method is used for building collection.
     * Thats why parameter order is different than other element.
     *
     * @param string $type
     * @param array $attributes
     */
    public function __construct($type = null, array $attributes = [])
    {
        $this->type = $type;
        $this->attributes = $attributes;
        $this->default = [];
    }

    /**
     * Include collection into existing element
     *
     * @param string $type
     * @param array $attributes
     * @return \UserMeta\Html\Html
     */
    public function import($type = null, array $attributes = [])
    {
        $instance = new static($type, $attributes);
        $this->default[] = $instance;
        return $instance;
    }

    /**
     * Add html to collection
     *
     * @param
     *            Html object | string $html
     */
    public function add($html)
    {
        try {
            $this->default[] = is_object($html) ? $html->render() : $html;
        } catch (\Exception $e) {
            echo 'Exception: ', $e->getMessage(), "\n";
        }
    }

    /**
     * render collection elements
     *
     * @return string html
     */
    public function render()
    {
        $type = $this->type ?: '';
        $html = null;
        foreach ($this->default as $element) {
            if ($this->isString($element)) {
                $html .= $element;
            } elseif ($element instanceof Html) {
                $html .= $element->render();
            }
        }

        return $type ? static::$type($html, $this->attributes) : $html;
    }

    /**
     * Generate text input.
     *
     * @param string $default:
     *            Default value attribute
     * @param array $attributes:
     *            (optional)
     *            
     * @return string : html text input
     */
    protected function text($default = null, array $attributes = [])
    {
        return $this->input('text', $default, $attributes);
    }

    /**
     * Generate textarea.
     *
     * @param string $default:
     *            Inside text for textarea
     * @param array $attributes:
     *            (optional)
     *            
     * @return string : html textarea
     */
    protected function textarea($default = null, array $attributes = [])
    {
        return $this->tag('textarea', \esc_textarea($default), $attributes);
    }

    /**
     * Generate a single checkbox or list of checkboxes.
     *
     * @param bool $default:
     *            true, 1 or any value for checked and false or 0 for unchecked
     * @param array $attributes:
     *            (optional)
     * @param array $options:
     *            Generate list of checkbox when $options is not empty
     *            
     * @return string : html checkbox
     */
    protected function checkbox($default = false, array $attributes = [], array $options = [])
    {
        if (! empty($options)) {
            return $this->checkboxList($default, $attributes, $options);
        }

        return $this->_singleCheckboxRadio('checkbox', $default, $attributes);
    }

    /**
     * Generate a single radio or list of radios.
     *
     * @param bool $default:
     *            true, 1 or any value for checked and false or 0 for unchecked
     * @param array $attributes:
     *            (optional)
     * @param array $options:
     *            Generate list of radios when $options is not empty
     *            
     * @return string : html checkbox
     */
    protected function radio($default = false, array $attributes = [], array $options = [])
    {
        if (! empty($options)) {
            return $this->radioList($default, $attributes, $options);
        }

        return $this->_singleCheckboxRadio('radio', $default, $attributes);
    }

    /**
     * Generate html input.
     *
     * @param string $type:
     *            Input type attribute
     * @param string $default:
     *            Default value attribute
     * @param array $attributes
     *
     * @return string : Generic html input
     */
    protected function input($type, $default = null, array $attributes = [])
    {
        $this->setProperties($type, $default, $attributes);
        $this->_refineInputAttributes();

        return $this->_createInput();
    }

    private function _singleCheckboxRadio($type, $default, $attributes)
    {
        $this->setProperties($type, $default, $attributes);
        $this->_refineInputAttributes();

        $this->attributes['value'] = ! empty($attributes['value']) ? $attributes['value'] : '1';

        $this->attributes = array_merge($this->attributes, $this->getSelectedAttribute($this->attributes));

        return $this->_createInput();
    }

    /**
     * Creating input.
     *
     * @return string html
     */
    private function _createInput()
    {
        $html = $this->addLabel();
        $html .= '<input' . $this->attributes() . '/>';

        return $this->_publish($html);
    }

    /**
     * Every generated element should call this function before returning final output
     *
     * @param string $element
     * @return string
     */
    private function _publish($element)
    {
        return $this->_refinePublish($element);
    }

    /**
     * Refine html before publish
     *
     * @param string $element
     * @return string
     */
    private function _refinePublish($element)
    {
        $html = '';
        if (! empty($this->attributes[$this->config['BEFORE']]))
            $html .= $this->attributes[$this->config['BEFORE']];

        $html .= $element;

        if (! empty($this->attributes[$this->config['AFTER']]))
            $html .= $this->attributes[$this->config['AFTER']];

        if (! empty($this->attributes[$this->config['ENCLOSE']])) {
            list ($type, $attr) = $this->_splitFirstFromArray($this->attributes[$this->config['ENCLOSE']]);
            $html = $this->tag($type, $html, $attr);
        }

        return $html;
    }

    /**
     * Adding label to element.
     * label attribute can be string or array.
     * In case of array, non-key first value will treat as $default
     *
     * @return string html
     */
    private function addLabel()
    {
        if (isset($this->attributes['label'])) {
            list ($default, $attr) = $this->_splitFirstFromArray($this->attributes[$this->config['LABEL']]);

            if (isset($this->attributes['id']) && ! in_array($this->type, [
                'radio',
                'checkboxList'
            ])) {
                $attr['for'] = $this->attributes['id'];
            }

            return static::_build('label', [
                $default,
                $attr
            ]);
        }

        return null;
    }

    /**
     * Split first element from given array
     * In case of string, first=$args
     * In case of array, $first=$args[0], $args=rest of $args
     *
     * @param string|array $args
     * @return array list($first, $args)
     */
    private function _splitFirstFromArray($args)
    {
        if (is_array($args)) {
            $first = isset($args[0]) ? $args[0] : null;
            unset($args[0]);
        } else {
            $first = $args;
            $args = [];
        }

        return [
            $first,
            $args
        ];
    }

    /**
     * Set class properties.
     *
     * @param string $type:
     *            Input type attribute
     * @param string $default:
     *            Default value attribute
     * @param array $attributes
     * @param array $options
     */
    private function setProperties($type, $default, array $attributes, array $options = [])
    {
        $this->type = $type ?: 'text';
        $this->default = $default;
        $this->attributes = $attributes;
        $this->_refineAttribute();

        $this->setOptions($options);
    }

    private function _refineAttribute()
    {
        $attributes = [];
        foreach ($this->attributes as $key => $val) {
            if (is_int($key) && ! empty($val)) {
                $key = $val;
            }
            $attributes[$key] = $val;
        }
        $this->attributes = $attributes;
    }

    /**
     * Adding type and value to $this->attributes
     * Useful for making input fields.
     * name attribute was added to keep following order: type, name, value.
     */
    private function _refineInputAttributes()
    {
        $this->attributes = array_merge([
            'type' => $this->type,
            'name' => null,
            'value' => $this->default
        ], $this->attributes);
    }

    /**
     * Build attributes string from $this->attributes property.
     *
     * @return string: Attributes string
     */
    private function attributes()
    {
        $attributes = $this->_getRefinedAttributes();

        return $this->toString($attributes);
    }

    /**
     * Apply refinement to $this->attributes and get refined $attributes.
     *
     * @return array: $attributes
     */
    private function _getRefinedAttributes()
    {
        $attributes = $this->attributes;
        $attributes = $this->onlyNonEmpty($attributes);
        $attributes = $this->onlyString($attributes);
        $attributes = $this->escapeAttributes($attributes);
        $attributes = $this->removeKeys($attributes, $this->config);

        return $attributes;
    }

    /**
     * Convert associative array to string.
     *
     * @param array: $attributes
     *
     * @return string: Attributes string
     */
    private function toString(array $attributes)
    {
        $string = '';

        foreach ($attributes as $key => $val) {
            if ($this->isString($val)) {
                $string .= " $key=\"$val\"";
            }
        }

        return $string;
    }

    /**
     * Escape attributes before display.
     *
     * @param array $attributes
     * @return array
     */
    private function escapeAttributes(array $attributes)
    {
        if (! empty($attributes[$this->config['DISABLE_ESCAPE']])) {
            return $attributes;
        }

        foreach ($attributes as $key => $value) {
            $attributeConfig = ! empty($this->attributesConfig[$key]) ? $this->attributesConfig[$key] : [];
            if (! empty($attributeConfig['_escape_function'])) {
                $escapeFunction = $attributeConfig['_escape_function'];
            }

            if (! empty($escapeFunction)) {
                $attributes[$key] = $this->escapeDeep($value, $escapeFunction);
            }
            unset($escapeFunction);
        }

        return $attributes;
    }

    /**
     * Apply escape function to data.
     *
     * @param array|string $data
     * @param string $functionName
     * @return mixed
     */
    private function escapeDeep($data, $functionName)
    {
        if (is_array($data)) {
            echo $data;
            return array_map($functionName, $data);
        } elseif (is_string($data)) {
            return call_user_func($functionName, $data);
        }

        return $data;
    }

    /**
     * Apply esc_attr/htmlspecialchars to both input string and array.
     *
     * @deprecated not in use since 1.1, use escapeDeep instead.
     * @param array: $attributes
     * @return mixed: htmlspecialchars filtered data
     */
    private function filter($data)
    {
        if (is_array($data)) {
            return array_map('\\esc_attr', $data);
        } elseif (is_string($data)) {
            return \esc_attr($data);
        }

        return $data;
    }

    /**
     * Add elements to array for given keys.
     *
     * @todo
     *
     * @param array $data:
     *            Given array
     * @param array $keys:
     *            Given keys to remove
     *            
     * @return array
     */
    private function addKeys(array $data, array $keys, $default = '')
    {
        foreach ($keys as $key) {
            if (! isset($data[$key])) {
                $data[$key] = $default;
            }
        }

        return $data;
    }

    /**
     * Remove elements from array for given keys.
     *
     * @param array $data:
     *            Given array
     * @param array $keys:
     *            Given keys to remove
     *            
     * @return array
     */
    private function removeKeys(array $data, array $keys)
    {
        foreach ($data as $key => $itm) {
            if (in_array($key, $keys)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * Filter all non string value from given array.
     *
     * @param array $data
     *
     * @return array
     */
    private function onlyString(array $data)
    {
        foreach ($data as $key => $itm) {
            if (! $this->isString($itm)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * Filter all empty value from given array.
     *
     * @param array $data
     *
     * @return array
     */
    private function onlyNonEmpty(array $data)
    {
        foreach ($data as $key => $itm) {
            if (empty($itm)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * Check if given argument is string.
     *
     * @param mixed $date
     *
     * @return bool
     */
    private function isString($data)
    {
        if (in_array(gettype($data), [
            'array',
            'object'
        ])) {
            return false;
        }

        return true;
    }

    /**
     * Get value for given key from an array.
     */
    private function get($key, array $data, $default = null)
    {
        if (isset($data[$key])) {
            return $data[$key];
        }

        return $default;
    }

    /**
     * Determine which method to call input()/tag()
     *
     * @param
     *            string methodName
     */
    private function _determineInputOrTag($method)
    {
        return in_array($method, $this->inputTypes) ? 'input' : 'tag';
    }

    /**
     * Build html element.
     * Every _build() is creating new instance to avoid confliction.
     *
     * @param string $method:
     *            Method name to call
     * @param array $args:
     *            Arguments array to pass to invocked method call
     *            
     * @return string html
     */
    private static function _build($method, array $args)
    {
        $instance = new static();
        try {
            if (! method_exists($instance, $method)) {
                array_unshift($args, $method);
                $method = $instance->_determineInputOrTag($method);
            }

            return call_user_func_array([
                $instance,
                $method
            ], $args);
        } catch (\Exception $e) {
            return 'Exception: ' . $e->getMessage() . "\n";
        }
    }

    /**
     * Call dynamic instance methods.
     * eg: $form->text();
     *
     * @param string $method:
     *            Method name to call
     * @param array $args:
     *            Arguments array to pass to invocked method call
     *            
     * @return string html
     */
    public function __call($method, $args)
    {
        $html = static::_build($method, $args);
        if ($html)
            $this->default[] = $html;

        return $html;
    }

    /**
     * Call static methods.
     * eg: Form::text('something');
     *
     * @param string $method:
     *            Method name to call
     * @param array $args:
     *            Arguments array to pass to invocked method call
     *            
     * @return string html
     */
    public static function __callStatic($method, $args)
    {
        return static::_build($method, $args);
    }
}