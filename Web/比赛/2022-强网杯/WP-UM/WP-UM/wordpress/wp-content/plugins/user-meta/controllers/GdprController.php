<?php
namespace UserMeta;

/**
 * All GDPR related hooks start from here
 * Reff: https://github.com/allendav/wp-privacy-requests/blob/master/EXPORT.md
 *
 * @author Khaled Hossain
 * @since 1.4
 */
class GdprController
{

    public function __construct()
    {
        add_filter('wp_privacy_personal_data_exporters', [
            $this,
            'registerPluginExporter'
        ]);
    }

    public function registerPluginExporter($exporters)
    {
        $exporters[] = [
            'exporter_friendly_name' => 'User Meta Plugin',
            'callback' => [
                new Gdpr(),
                'dataExporter'
            ]
        ];
        return $exporters;
    }
}