<?php
namespace UserMeta;

/**
 * Handle all GDPR features
 * Reff: https://github.com/allendav/wp-privacy-requests/blob/master/EXPORT.md
 *
 * @author Khaled Hossain
 * @since 1.4
 */
class Gdpr
{

    /**
     * Export data for 'Export Personal Data' menu
     *
     * @uses GdprController::registerPluginExporter()
     * @param string $email
     * @param number $page
     * @return array
     */
    public function dataExporter($email, $page = 1)
    {
        $user = get_user_by('email', $email);
        if (empty($user)) {
            return [
                'data' => [],
                'done' => true
            ];
        }

        $export_items = [
            [
                'group_id' => 'user',
                'group_label' => 'User',
                'item_id' => 'user-meta-data',
                'data' => $this->_getUserData($user)
            ]
        ];

        return [
            'data' => $export_items,
            'done' => true
        ];
    }

    /**
     * Get a single user data to export
     *
     * @param \WP_User $user
     * @return array
     */
    private function _getUserData($user)
    {
        $userData = [];
        $metaKeys = apply_filters('user_meta_privacy_data_exporter_meta_keys', getExtraMetaKeys());
        $fileMetaKeys = getFileMetaKeys();
        foreach ($metaKeys as $metaKey => $fieldTitle) {
            if (empty($user->$metaKey))
                continue;
            $metaValue = $user->$metaKey;
            if (is_array($user->$metaKey)) {
                $metaValue = implode(', ', $metaValue);
            }
            if (in_array($metaKey, $fileMetaKeys)) {
                $file = File::getFilePath($user->$metaKey);
                $metaValue = ! empty($file) ? $file['url'] : '';
            }
            $userData[] = [
                'name' => $fieldTitle,
                'value' => $metaValue
            ];
        }

        return $userData;
    }
}