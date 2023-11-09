<?php
namespace UserMeta;

class LibRawFunctionsModel
{

    /**
     * Generate HTML input element
     *
     * @param string $name:
     *            Name of input element
     * @param mixed $value:
     *            Value of input element
     * @param string $type:
     *            Type of input element, Possible types are: text, password, select, textarea, file
     * @param array $attr
     *            : all other attribute will goes there.
     *            Supporting common attr: id, class, style, label, enclose, before, after
     *            For select element: use 'by_key' as for using $optipms key as select value
     *            Use enclose for enclosing by other html element: (eg. array('enclose'=>'p') or array("enclose"=>"p class='abc'") )
     * @param array $options
     *            : options for select, checkbox
     */
    function createInput($name = '', $type = 'text', $attr = array(), $options = array())
    {
        $name = trim($name);
        $name = $name ? "name=\"$name\"" : '';

        if (isset($attr['value'])) {
            if (is_string($attr['value']))
                $attr['value'] = esc_attr(trim($attr['value']));
            elseif (is_array($attr['value'])) {
                $attr['value'] = array_map('esc_attr', $attr['value']);
            }
        }
        $value = isset($attr['value']) ? $attr['value'] : null;

        // filter attr for add
        $excludeAttr = array(
            'checked',
            'before',
            'after',
            'enclose',
            'field_enclose',
            'label',
            'by_key',
            'label_class',
            'combind',
            'option_before',
            'option_after'
        );
        if ($type == 'checkbox' && ! empty($attr['combind']))
            array_push($excludeAttr, 'required');

        $excludeType = array(
            'select',
            'radio',
            'label',
            'checkbox',
            'textarea',
            'a',
            'img'
        ); // exclude adding value
        if (in_array($type, $excludeType))
            $excludeAttr[] = 'value';
        $include = null;
        if (is_array(@$attr)) {
            foreach ($attr as $key => $val) {
                if (! in_array($key, $excludeAttr)) {
                    if (! is_array($val))
                        $include .= self::notEmpty($val) ? "$key=\"$val\" " : "";
                }
            }
        }

        $option_before = isset($attr['option_before']) ? $attr['option_before'] : null;
        $option_after = isset($attr['option_after']) ? $attr['option_after'] : null;
        $by_key = isset($attr['by_key']) ? $attr['by_key'] : null;

        $label_id = ! empty($attr['label_id']) ? "id=\"{$attr['label_id']}\"" : null;
        $label_class = ! empty($attr['label_class']) ? "class=\"{$attr['label_class']}\"" : null;

        $html = '';

        if ($type == 'select') {
            $html .= "<select $name $include>";
            if (! empty($options) && is_array($options)) {
                foreach ($options as $key => $val)
                    $html .= self::buildHtmlSelectOption($key, $val, $value, $by_key);
            }
            $html .= "</select>";
        } elseif ($type == 'multiselect') {
            $name = rtrim($name, "\"") . "[]\"";
            $html .= "<select $name multiple=\"multiple\" $include>";
            $isInside = false;
            if (! empty($options) && is_array($options)) {
                foreach ($options as $key => $val)
                    $html .= self::buildHtmlSelectOption($key, $val, $value, $by_key);
            }
            $html .= "</select>";
        } elseif ($type == 'radio') {
            if (is_array(@$options)) {
                $i = 0;
                foreach ($options as $key => $val) {
                    if (! $by_key)
                        $key = $val;
                    $key = is_string($key) ? trim($key) : $key;
                    $checked = ($key == $value) ? "checked=\"checked\"" : "";

                    // Changing id for each option
                    if (! empty($attr['id'])) {
                        $includeModify = str_replace("id=\"{$attr['id']}\"", "id=\"{$attr['id']}_$i\"", $include);
                        $label = "<label for=\"{$attr['id']}_$i\">$val</label>";
                    } else {
                        $includeModify = $include;
                        $label = "<label>$val</label>";
                    }

                    $html .= "$option_before<input type=\"$type\" $name $includeModify value=\"$key\" $checked /> $label $option_after";
                    $i ++;
                }
            }
        } elseif ($type == 'checkbox') {
            $attr['combind'] = isset($attr['combind']) ? $attr['combind'] : false;
            if ($attr['combind']) {
                $name = rtrim($name, "\"") . "[]\"";
                if (is_array(@$options)) {
                    $i = 0;
                    foreach ($options as $key => $val) {
                        if (! $by_key)
                            $key = $val;
                        $key = is_string($key) ? trim($key) : $key;
                        if (is_array($value))
                            $checked = in_array($key, $value) ? "checked=\"checked\"" : "";
                        else
                            $checked = ($key == $value) ? "checked=\"checked\"" : "";

                        // Changing id for each option
                        if (! empty($attr['id'])) {
                            $includeModify = str_replace("id=\"{$attr['id']}\"", "id=\"{$attr['id']}_$i\"", $include);
                            $label = "<label for=\"{$attr['id']}_$i\">$val</label>";
                        } else {
                            $includeModify = $include;
                            $label = "<label>$val</label>";
                        }

                        $html .= "$option_before<input type=\"$type\" {$name} $includeModify value=\"$key\" $checked /> $label $option_after";
                        $i ++;
                    }
                }
            } else {
                $checked = '';
                if (isset($attr['checked']))
                    $checked = ! empty($attr['checked']) ? "checked=\"checked\"" : "";
                elseif (! empty($value))
                    $checked = "checked=\"checked\"";

                $checkboxValue = (! empty($value) && ! is_array($value)) ? $value : 1;
                $include .= 'value="' . $checkboxValue . '"';

                $html .= "<input type=\"$type\" $name $include $checked />";
            }
        } elseif ($type == 'textarea') {
            $html .= "<textarea $name $include>$value</textarea>";
        } elseif ($type == 'a') {
            $html .= "<a $name $include>$value</a>";
        } elseif ($type == 'img') {
            $html .= "<img $include />";
        } elseif ($type == 'file') {
            $html .= "<input type=\"$type\" $name $include />";
            $form_id = @$attr['form_id'];
            if ($form_id) {
                ?><script type="text/javascript">
                            var form = document.getElementById($form_id);
                            form.encoding = "multipart/form-data";
                            form.setAttribute('enctype', "multipart/form-data");
                    </script><?php
            }
        } elseif ($type == 'label') {
            $for = isset($attr['for']) ? "for=\"{$attr['for']}\"" : '';
            $html .= "<label $for $include>$value</label>";
        } else {
            $html .= "<input type=\"$type\" $name $include />";
        }

        $before = isset($attr['before']) ? $attr['before'] : null;
        $after = isset($attr['after']) ? $attr['after'] : null;
        // $html = $before . $html . $after;

        // Enclose by other html element
        if (! empty($attr['field_enclose'])) {
            $enclose = $attr['field_enclose'];
            $encloseTag = explode(' ', trim($enclose));
            $encloseTag = $encloseTag[0];
            $html = "<$enclose>$html</$encloseTag>";
        }

        // Add lebel if required
        if (isset($attr['label'])) {
            $for = isset($attr['id']) ? "for=\"{$attr['id']}\"" : '';
            $htmlLabel = "<label $label_id $label_class $for>{$attr['label']}</label>";
            if ($type == 'checkbox' && empty($attr['combind']))
                $html = "<label $label_id $label_class $for>$html {$attr['label']}</label>";
            else
                $html = $htmlLabel . ' ' . $html;
        }

        $html = $before . $html . $after;

        // Enclose by other html element
        if (! empty($attr['enclose'])) {
            $enclose = $attr['enclose'];
            $encloseTag = explode(' ', trim($enclose));
            $encloseTag = $encloseTag[0];
            $html = "<$enclose>$html</$encloseTag>";
        }

        return $html;
    }

    function buildHtmlSelectOption($key, $val, $selectedOptions, $by_key)
    {
        $html = null;

        if (is_array($val)) {
            $html .= "<optgroup label=\"$key\">";
            foreach ($val as $k => $v)
                $html .= self::buildHtmlSelectOption($k, $v, $selectedOptions, $by_key);
            $html .= "</optgroup>";
        } else {
            if (! $by_key)
                $key = $val;

            $selected = "selected=\"selected\"";
            if (is_array($selectedOptions))
                $selected = in_array($key, $selectedOptions) ? $selected : '';
            else
                $selected = ($selectedOptions == $key) ? $selected : '';

            $html .= "<option value=\"$key\" $selected>$val</option>";
        }

        return $html;
    }

    /**
     * Remove directory with all files
     */
    function deleteDirectory($dir)
    {
        if (! file_exists($dir))
            return true;
        if (! is_dir($dir) || is_link($dir))
            return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..')
                continue;
            if (! deleteDirectory($dir . "/" . $item)) {
                chmod($dir . "/" . $item, 0777);
                if (! deleteDirectory($dir . "/" . $item))
                    return false;
            }
            ;
        }
        return rmdir($dir);
    }

    /**
     * Extract fileinfo like filename, extension, directry from file name
     *
     * @param string $file:
     *            filename
     * @return object : ext, name, dir
     */
    function fileinfo($file)
    {
        $return = new \stdClass();
        $fileData = explode('.', $file);
        $return->ext = strpos($file, '.') ? end($fileData) : null;

        $realPath = rtrim(trim($file), '.' . $return->ext);
        $replacedPath = str_replace("\\", "/", $realPath);

        if (strpos($replacedPath, '/')) {
            $pathData = explode("/", $replacedPath);
            $return->name = end($pathData);
            $return->dir = rtrim($realPath, end($pathData));
            $return->dir = rtrim($return->dir, "/[\/\\]/");
        } else {
            $return->name = $replacedPath;
            $return->dir = null;
        }
        return $return;
    }

    /**
     * go up one directory/url
     *
     * @param string $path:
     *            url or path
     * @return string : path/url without last slash
     */
    function directoryUp($path)
    {
        $path = rtrim(trim($path), "/[\/\\]/"); // Removing last slash
        $replacedPath = str_replace("\\", "/", $path);
        $pathData = explode('/', $replacedPath);
        $lastPath = end($pathData);
        $return = rtrim($path, $lastPath); // Removing last path
        $return = rtrim($return, "/[\/\\]/"); // Removing last slash
        return $return;
    }

    /**
     * Remove empty value and array from array
     *
     * @param
     *            array
     * @param bool $keepEmptyArray
     *            true for keep and false for not keep. Default false.
     * @return array
     */
    function arrayRemoveEmptyValue($array, $keepEmptyArray = false)
    {
        $result = array();

        if (! is_array($array))
            return $result;

        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $child = self::arrayRemoveEmptyValue($val);
                if ($child)
                    $result[$key] = $child;
                elseif ($keepEmptyArray)
                    $result[$key] = $child;
            } else {
                if (self::notEmpty($val))
                    $result[$key] = $val;
            }

            if (@$result[$key] && is_string(@$result[$key]))
                $result[$key] = stripslashes($result[$key]);
        }
        return $result;
    }

    function notEmpty($data)
    {
        if (is_int($data))
            return true;
        elseif (is_string($data)) {
            $data = trim($data);
            if (("0" == $data) || ! empty($data))
                return true;
        } else {
            if (! empty($data))
                return true;
        }

        return false;
    }

    /**
     * Remove non array data from array on first chield
     */
    function removeNonArray($data)
    {
        if (! is_array($data))
            return false;

        foreach ($data as $key => $val) {
            if (! is_array($val))
                unset($data[$key]);
        }

        return $data;
    }

    /**
     * get maximum key of an array
     */
    function maxKey($arr)
    {
        if (! is_array($arr))
            return false;
        if (! $arr)
            return false;

        $keys = array();
        foreach ($arr as $k => $v)
            $keys[] = $k;
        return max($keys);
    }

    /**
     * Convert data to string
     *
     * @param mixed $data
     *            for convert
     * @param $separator :
     *            default:','
     * @return string
     */
    function toString($data, $separator = ',')
    {
        $result = null;
        if (! $data)
            return $result;
        if (is_array($data))
            $result = implode($separator, $data);
        else
            $result = $data;
        return (string) $result;
    }

    /**
     * Convert data to array
     *
     * @param mixed $data
     *            for convert
     * @param $separator :
     *            default:','
     * @return array
     */
    function toArray($data, $fieldSeparator = ',', $keySeparator = '=', $gruopStart = ':', $groupEnd = '|')
    {
        $result = array();

        if (! $data)
            return $result;
        if (! is_string($data))
            return (array) $data;

        $chrs1 = array(
            ',',
            '=',
            ':',
            '|'
        );
        $chrs2 = array(
            '\,',
            '\=',
            '\:',
            '\|'
        );
        $chrs3 = array(
            '&#44;',
            '&#61;',
            '&#58;',
            '&#124;'
        );
        $data = str_replace($chrs2, $chrs3, $data);

        // Expload field by comma
        $fields = explode($fieldSeparator, $data);

        foreach ($fields as $field) {

            if (strpos($field, $gruopStart) !== false) {
                $field = explode($gruopStart, $field);
                $gruopKey = trim($field[0]);
                $field = $field[1];
            }

            if (strpos($field, $groupEnd) !== false) {
                $field = str_replace($groupEnd, '', $field);
                $endGroup = true;
            }

            $field = explode($keySeparator, $field);
            $fieldKey = trim(str_replace($chrs3, $chrs1, $field[0]));
            $fieldVal = isset($field[1]) ? trim($field[1]) : $fieldKey;

            if (! empty($gruopKey))
                $result[$gruopKey][$fieldKey] = $fieldVal;
            else
                $result[$fieldKey] = $fieldVal;

            if (! empty($endGroup))
                unset($gruopKey);
        }

        return $result;
    }

    function sortByPosition($a, $b)
    {
        if (! isset($a['position']) || ! isset($b['position']))
            return 0;
        if ($a['position'] == $b['position'])
            return 0;
        return $a['position'] > $b['position'] ? 1 : - 1;
    }

    /**
     * Siteurl without protocol and trailing slash
     *
     * @deprecated replaced with cleanSiteUrl()
     * @param string $url
     */
    function prepareUrl($url)
    {
        return cleanSiteUrl($url);
    }

    /**
     * Generating hash for timestamp that remains same for certain time
     *
     * If $span is 10, and time() is 1488373933, generated timestamp will be 1488373930
     * Generated timestamp from 1488373930 to 1488373939 will be always 1488373930
     * floor function make it happens
     *
     * @param number $span
     *            default 3600*24(s)
     * @param number $time
     * @return string hash
     */
    function generateTimeNonce($span = 86400, $time = 0)
    {
        $time = ! empty($time) ? $time : time();
        return md5('secret' . floor($time / $span) * $span);
    }

    /**
     * Check if hash value is in valid timeframe
     *
     * Can qualify time + span
     * Could qualify time + (2*span-1)
     * Could qualify time - (2*span-1)
     *
     * If $span is 10, and time() is 1488373933, points will be 1488373920, 1488373930, 1488373940
     * So for timestamp 1488373931, qualify all timestamp between 1488373920 to 1488373949
     *
     * @param string $hash
     * @param number $span
     *            default 3600*24(s)
     * @param number $time
     * @return boolean
     */
    function verifyTimeNonce($hash, $span = 86400, $time = 0)
    {
        $points = [];
        $time = ! empty($time) ? $time : time();
        $points[] = md5('secret' . floor(($time - $span) / $span) * $span);
        $points[] = md5('secret' . floor($time / $span) * $span);
        $points[] = md5('secret' . floor(($time + $span) / $span) * $span);

        return in_array($hash, $points) ? true : false;
    }

    /**
     *
     * @deprecated replaced with dump()
     */
    function dump($data, $dump = false)
    {
        dump($data, $dump);
    }
}