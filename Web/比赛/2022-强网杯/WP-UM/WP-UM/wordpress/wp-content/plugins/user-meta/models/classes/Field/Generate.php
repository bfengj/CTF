<?php
namespace UserMeta\Field;

/**
 * Interface for generate field html based on provided arguments.
 *
 * This class only generate field based on provided argument. Not query from db or saved field.
 * So, pre-populated argument should provide.
 * See `Field` and `FormGenerate` classes to pre-populate $field argument
 *
 * @author Khaled Hossain
 * @since 1.2.0
 */
class Generate
{

    private $fieldType;

    private $field;

    private $extra;

    private $object;

    /**
     * Generate field based on provided arguments.
     *
     * @param array $field:
     *            field's data
     * @param array $extra:
     *            Extra configurations.
     */
    public function __construct(array $field, array $extra = [])
    {
        if (empty($field['field_type'])) {
            throw new \Exception('field_type is required within $field array in ' . __CLASS__);
        }

        $this->fieldType = $field['field_type'];
        $this->field = $field;
        $this->extra = $extra;

        $classFullName = $this->getClassFullName();
        if (class_exists($classFullName)) {
            $this->object = new $classFullName($this->field, $this->extra);
        }
    }

    /**
     * Get object of generated field
     *
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Render generated html.
     *
     * @return string html
     */
    public function render()
    {
        if (empty($this->object)) {
            return;
        }

        return $this->object->render();
    }

    /**
     * Get class name with namespace.
     * First looking into array, then existed class and then default Text.
     *
     * @return string : class name
     */
    private function getClassFullName()
    {
        $classTypes = array(
            'user_email' => 'Email',
            'user_pass' => 'Password',
            'user_avatar' => 'Avatar',
            'select' => 'OptionsElement',
            'radio' => 'OptionsElement',
            'checkbox' => 'OptionsElement',
            'multiselect' => 'OptionsElement'
        );
        if (isset($classTypes[$this->fieldType])) {
            return __NAMESPACE__ . '\\' . $classTypes[$this->fieldType];
        }
        $className = $this->toCamelCase($this->fieldType);
        if (class_exists(__NAMESPACE__ . '\\' . $className)) {
            return __NAMESPACE__ . '\\' . $className;
        }

        return __NAMESPACE__ . '\\Text';
    }

    /**
     * Convert text to CamelCase.
     * e.g. Changing first_name into FirstName.
     * PHP-5.4.32, 5.5.16 added delimiters parameter in ucwords().
     *
     * @param string $text
     *
     * @return string
     */
    private function toCamelCase($text)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $text)));
    }
}
