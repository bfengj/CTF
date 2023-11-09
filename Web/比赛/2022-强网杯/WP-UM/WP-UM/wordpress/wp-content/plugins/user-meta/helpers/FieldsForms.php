<?php
namespace UserMeta;

/**
 * Get meta_keys of all extra fields saved by UM (shared and form's).
 * user_avatar is also included
 *
 * @since 1.4
 * @return array [meta_key => title]
 */
function getExtraMetaKeys()
{
    $metaKeys = [];
    $fields = (new FormBase())->getAllFields();
    foreach ($fields as $field) {
        $fieldTitle = isset($field['field_title']) ? $field['field_title'] : '';
        if (! empty($field['meta_key'])) {
            $metaKeys[$field['meta_key']] = $fieldTitle;
        } elseif (isset($field['field_type']) && $field['field_type'] == 'user_avatar') {
            $metaKeys['user_avatar'] = $fieldTitle;
        }
    }

    return $metaKeys;
}

/**
 * Get meta_key(s) of all file fields.
 *
 * @since 1.2
 * @return array [keys]
 */
function getFileMetaKeys()
{
    $fields = (new FormBase())->getAllFields();
    $metaKeys = [
        'user_avatar'
    ];
    foreach ($fields as $field) {
        if (isset($field['field_type']) && $field['field_type'] == 'file') {
            $metaKeys[] = $field['meta_key'];
        }
    }

    return array_unique($metaKeys);
}