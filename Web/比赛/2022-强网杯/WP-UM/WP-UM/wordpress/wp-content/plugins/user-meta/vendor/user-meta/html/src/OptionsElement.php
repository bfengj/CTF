<?php
namespace UserMeta\Html;

/*
 * trait for options elements
 *
 * @author Khaled Hossain
 * @since 1.0.0
 */
trait OptionsElement
{

    /**
     * Counting number of options.
     */
    private $optionCount = 0;

    /**
     * Alias of select method.
     *
     * @param string $default:
     *            Default selected value
     * @param array $attributes            
     * @param array $options:
     *            Dropdown options
     *            
     * @return string : html select
     */
    protected function dropdown($default = null, array $attributes = [], array $options = [])
    {
        return $this->select($default, $attributes, $options);
    }

    /**
     * Generate html select.
     *
     * @param string $default:
     *            Default selected value
     * @param array $attributes            
     * @param array $options:
     *            Dropdown options
     *            
     * @return string : html select
     */
    protected function select($default = null, array $attributes = [], array $options = [])
    {
        $this->setProperties('select', $default, $attributes, $options);
        
        $html = $this->addLabel();
        
        $html .= "<select{$this->attributes()}>{$this->buildOptions()}</select>";
        
        return $this->_publish($html);
    }

    /**
     * Generate multiselect.
     *
     * @param
     *            string | array $default: Default selected value
     * @param array $attributes            
     * @param array $options:
     *            Dropdown options
     *            
     * @return string : html multiselect
     */
    protected function multiselect($default = [], array $attributes = [], array $options = [])
    {
        $this->setProperties('multiselect', $default, $attributes, $options);
        
        $html = $this->addLabel();
        
        $html .= "<select{$this->attributes()} multiple=\"multiple\">{$this->buildOptions()}</select>";
        
        return $this->_publish($html);
    }

    /**
     * Generate list of radios.
     *
     * @param string $default:
     *            Default checked value
     * @param array $attributes            
     * @param array $options:
     *            Dropdown options
     *            
     * @return string : html radio
     */
    protected function radioList($default = null, array $attributes = [], array $options = [])
    {
        $this->setProperties('radio', $default, $attributes, $options);
        
        $html = $this->addLabel();
        
        $html .= $this->buildOptions();
        
        return $html;
    }

    /**
     * Generate list of checkboxes.
     *
     * @param
     *            string | array $default: Default checked value
     * @param array $attributes            
     * @param array $options:
     *            Dropdown options
     *            
     * @return string : html checkboxes
     */
    protected function checkboxList($default = null, array $attributes = [], array $options = [])
    {
        $this->setProperties('checkboxList', $default, $attributes, $options);
        
        $html = $this->addLabel();
        
        $html .= $this->buildOptions();
        
        return $html;
    }

    /**
     * Set $this->options.
     *
     * Examine for each option element
     * if element contains 'value' and 'label': unchanged
     * elseif element is an array and type=optgroup: unchanged
     * - otherwise key=key, val=[label, ...]
     * in case of string: key | val
     *
     * @param array $options:            
     */
    protected function setOptions(array $options)
    {
        if (empty($options)) {
            return;
        }
        
        $isIndexedArray = $options === array_values($options);
        $isIntegerKeys = $this->_isIntegerKeys($options);
        
        $opt = [];
        foreach ($options as $key => $attr) {
            $single = [];
            if (isset($attr['value']) && isset($attr['label'])) {
                $single = $attr;
            } elseif (is_array($attr)) {
                if (isset($attr['type']) && 'optgroup' == $attr['type']) {
                    $single = $attr;
                } else {
                    $single['value'] = $key;
                    list ($label, $attr) = $this->_splitFirstFromArray($attr);
                    $single['label'] = $label;
                    foreach ($attr as $k => $v) {
                        $single[$k] = $v;
                    }
                }
            } elseif ($this->isString($attr)) {
                if ($isIndexedArray) {
                    $single['value'] = $attr;
                } elseif ($isIntegerKeys) {
                    $single['value'] = $key;
                } else {
                    $single['value'] = is_int($key) ? $attr : $key;
                }
                $single['label'] = $attr;
            }
            
            $opt[] = $single;
        }
        
        $this->options = $opt;
    }

    /**
     * Building options attributes.
     *
     * @param array $options            
     *
     * @return string : html atributes
     */
    protected function optionAttributes(array $option)
    {
        ++ $this->optionCount;
        
        $attributes = [];
        
        if (! in_array($this->type, [
            'select',
            'multiselect'
        ])) {
            $attributes = $this->_getRefinedAttributes();
        }
        
        $attributes = array_merge($attributes, $option, $this->getSelectedAttribute($option));
        
        $this->refineOptionsAttributes($attributes);
        
        return $this->toString($attributes);
    }

    /**
     * Building optgroup | groups attributes.
     *
     * @param array $options            
     *
     * @return string : html atributes
     */
    protected function groupAttributes(array $option)
    {
        $attributes = $this->removeKeys($option, [
            'type',
            'label'
        ]);
        
        return $this->toString($attributes);
    }

    /**
     * Apply refinement to options attributes, call by reference.
     *
     * @param
     *            array &$attributes
     *            
     * @return array : $atributes
     */
    protected function refineOptionsAttributes(array &$attributes)
    {
        foreach ($attributes as $key => $val) {
            if ($this->type == 'checkboxList' && $key == 'name' && $val) {
                // We can pass `[]` directly to name attributes if needed
                // $attributes[$key] = "{$val}[]";
            }
            
            if (in_array($this->type, [
                'radio',
                'checkboxList'
            ]) && $key == 'id' && $val) {
                $attributes[$key] = "{$val}_{$this->optionCount}";
            }
        }
        
        $attributes = $this->removeKeys($attributes, [
            'value',
            'label'
        ]);
    }

    /**
     * Get selected/checked attribute.
     *
     * @param array $option:
     *            single option or attributes contains key 'value'
     *            
     * @return array : e.g. ['checked' => 'checked'] | []
     */
    protected function getSelectedAttribute(array $option)
    {
        switch ($this->type) {
            case 'select':
            case 'multiselect':
                $key = 'selected';
                break;
            
            case 'radio':
            case 'checkbox':
            case 'radioList':
            case 'checkboxList':
                $key = 'checked';
                break;
            
            default:
                throw new \Exception('selected or checked attribute is not available for ' . $this->type);
        }
        
        if ($this->default === true) {
            return [
                $key => $key
            ];
        }
        
        if (empty($option['value']) || empty($this->default)) {
            return [];
        }
        
        if (is_array($this->default)) {
            return in_array($option['value'], $this->default) ? [
                $key => $key
            ] : [];
        } else {
            return $this->default == $option['value'] ? [
                $key => $key
            ] : [];
        }
        
        return [];
    }

    /**
     * Front method for building option.
     * Each options array should iterate through this method.
     *
     * @return string : option html
     */
    protected function buildOptions()
    {
        $html = '';
        foreach ($this->options as $option) {
            if (! empty($option['type']) && $option['type'] == 'optgroup') {
                if (isset($option['label']) && $option['label'] == '__end__') {
                    $html .= $this->groupEnd();
                    $inGroup = false;
                } else {
                    if (! empty($inGroup)) {
                        $html .= $this->groupEnd();
                    }
                    
                    $html .= $this->groupStart($option);
                    $inGroup = true;
                }
            } else {
                $html .= $this->_buildSingleOption($option);
            }
        }
        
        if (! empty($inGroup)) {
            $html .= $this->groupEnd();
        }
        
        return $html;
    }

    /**
     * Building single option.
     * Used only by value contaning option not groups.
     *
     * @param array $option            
     *
     * @return string : option html
     */
    protected function _buildSingleOption(array $option)
    {
        $option = $this->addKeys($option, [
            'value',
            'label'
        ]);
        $optionRefined = $this->removeKeys($option, [
            '_option_before',
            '_option_after'
        ]);
        
        return $this->_optionEnclose($option, 'before') . $this->callMethod('Single', $optionRefined) . $this->_optionEnclose($option, 'after');
    }

    /**
     * Adding option_before and option_after to single option's string.
     *
     * @param array $option            
     * @param string $key
     *            : before | after
     *            
     * @return string : text
     */
    protected function _optionEnclose(array $option, $key = 'before')
    {
        if (isset($option["_option_$key"])) {
            return $option["_option_$key"];
        } elseif (isset($this->attributes["_option_$key"])) {
            return $this->attributes["_option_$key"];
        }
        
        return '';
    }

    /**
     * Starting tag of group.
     *
     * @param array $option            
     *
     * @return string : html
     */
    protected function groupStart(array $option)
    {
        $option = $this->addKeys($option, [
            'label'
        ]);
        
        return $this->callMethod('GroupStart', $option);
    }

    /**
     * Ending tag of group.
     *
     * @return string : html
     */
    protected function groupEnd()
    {
        return $this->callMethod('groupEnd');
    }

    /**
     * Calling underlaying method based on slug and $this->type.
     *
     * @param string $slug:
     *            _{$this->type}{$slug}
     *            
     * @return callback
     */
    protected function callMethod($slug, $arg1 = null)
    {
        $methodName = "_{$this->type}{$slug}";
        if (method_exists($this, $methodName)) {
            return $this->$methodName($arg1);
        } else {
            throw new \Exception(get_class($this) . "::$methodName() does not exists!");
        }
    }

    /**
     * Single select option.
     *
     * @param array $option            
     *
     * @return string : html
     */
    protected function _selectSingle(array $option)
    {
        return "<option value=\"{$option['value']}\"{$this->optionAttributes($option)}>{$option['label']}</option>";
    }

    /**
     * Single multiselect option.
     *
     * @param array $option            
     *
     * @return string : html
     */
    protected function _multiselectSingle(array $option)
    {
        return $this->_selectSingle($option);
    }

    /**
     * Single radio.
     *
     * @param array $option            
     *
     * @return string : html
     */
    protected function _radioSingle(array $option)
    {
        return "<label><input type=\"radio\" value=\"{$option['value']}\"{$this->optionAttributes($option)}/> {$option['label']}</label>";
    }

    /**
     * Single checkbos.
     *
     * @param array $option            
     *
     * @return string : html
     */
    protected function _checkboxListSingle(array $option)
    {
        return "<label><input type=\"checkbox\" value=\"{$option['value']}\"{$this->optionAttributes($option)}/> {$option['label']}</label>";
    }

    /**
     * Group start for select.
     *
     * @param array $option            
     *
     * @return string : html
     */
    protected function _selectGroupStart(array $option)
    {
        return "<optgroup label=\"{$option['label']}\"{$this->groupAttributes($option)}>";
    }

    /**
     * Group start for multiselect.
     *
     * @param array $option            
     *
     * @return string : html
     */
    protected function _multiselectGroupStart(array $option)
    {
        return $this->_selectGroupStart($option);
    }

    /**
     * Group start for radio.
     *
     * @param array $option            
     *
     * @return string : html
     */
    protected function _radioGroupStart(array $option)
    {
        return "<div><label{$this->groupAttributes($option)}>{$option['label']}</label><br />";
    }

    /**
     * Group start for checkboxList.
     *
     * @param array $option            
     *
     * @return string : html
     */
    protected function _checkboxListGroupStart($option)
    {
        return $this->_radioGroupStart($option);
    }

    /**
     * Group end for select.
     *
     * @return string : html
     */
    protected function _selectGroupEnd()
    {
        return '</optgroup>';
    }

    /**
     * Group end for multiselect.
     *
     * @return string : html
     */
    protected function _multiselectGroupEnd()
    {
        return '</optgroup>';
    }

    /**
     * Group end for radio.
     *
     * @return string : html
     */
    protected function _radioGroupEnd()
    {
        return '</div>';
    }

    /**
     * Group end for checkboxList.
     *
     * @return string : html
     */
    protected function _checkboxListGroupEnd()
    {
        return '</div>';
    }

    /**
     * Check if all keys of given array are integer
     *
     * @param array $array            
     * @return boolean
     */
    private function _isIntegerKeys(array $array)
    {
        return count(array_filter(array_keys($array), 'is_int')) == count($array);
    }
}
