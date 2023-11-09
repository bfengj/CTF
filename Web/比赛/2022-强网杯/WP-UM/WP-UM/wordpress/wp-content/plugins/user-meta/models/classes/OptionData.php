<?php
namespace UserMeta;

/**
 * Read/Write data from options table.
 *
 * @since 2.3
 */
class OptionData
{

    /**
     * Note: private const is not supported in all version of php
     */
    const FIELDS_KEY = 'fields';

    const FORMS_KEY = 'forms';

    /**
     * Read flat array of fields.
     *
     * @param boolean $networkwide
     * @return array
     */
    public static function getFields($networkwide = false)
    {
        $fields = self::getData(self::FIELDS_KEY, $networkwide);
        foreach ($fields as &$field) {
            if (empty($field['field_type'])) {
                $field['field_type'] = 'text';
            }
        }

        return $fields;
    }

    public static function updateFields($fields, $networkwide = false)
    {
        return self::updateData(self::FIELDS_KEY, $fields, $networkwide);
    }

    public static function deleteFields($networkwide = false)
    {
        return self::deleteData(self::FIELDS_KEY, $networkwide);
    }

    public static function getForms($networkwide = false)
    {
        return self::getData(self::FORMS_KEY, $networkwide);
    }

    public static function updateForms($forms, $networkwide = false)
    {
        return self::updateData(self::FORMS_KEY, $forms, $networkwide);
    }

    public static function deleteForms($networkwide = false)
    {
        return self::deleteData(self::FORMS_KEY, $networkwide);
    }

    /**
     * Read data from option table.
     *
     * @param string $key
     * @param boolean $networkwide
     * @return array
     */
    private static function getData($key, $networkwide = false)
    {
        global $userMeta;
        $data = $userMeta->getData($key, $networkwide);

        return is_array($data) ? $data : [];
    }

    private static function updateData($key, $fields = [], $networkwide = false)
    {
        global $userMeta;
        return $userMeta->updateData($key, $fields, $networkwide);
    }

    private static function deleteData($key, $networkwide = false)
    {
        global $userMeta;
        return $userMeta->deleteData($key, $networkwide);
    }
}