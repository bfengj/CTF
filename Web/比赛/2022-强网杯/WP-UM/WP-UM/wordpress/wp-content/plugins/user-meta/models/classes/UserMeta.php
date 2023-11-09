<?php
namespace UserMeta;

/**
 * Initialize class for UserMeta and hold global $userMeta object
 *
 * @author Khaled Hossain
 * @since 1.2.1
 */
class UserMeta extends Framework
{

    public $title;

    public $version;

    public $name = 'user-meta';

    public $prefix = 'um_';

    public $prefixLong = 'user_meta_';

    public $website = 'https://user-meta.com';

    public function __construct($file)
    {
        $this->pluginSlug = plugin_basename($file);
        $this->pluginPath = dirname($file);
        $this->file = $file;
        $this->modelsPath = $this->pluginPath . '/models/';
        $this->controllersPath = $this->pluginPath . '/controllers/';
        $this->viewsPath = $this->pluginPath . '/views/';

        $this->pluginUrl = plugins_url('', $file);
        $this->assetsUrl = $this->pluginUrl . '/assets/';

        $pluginHeaders = [
            'Name' => 'Plugin Name',
            'Version' => 'Version'
        ];

        $pluginData = get_file_data($this->file, $pluginHeaders);

        $this->title = $pluginData['Name'];
        $this->version = $pluginData['Version'];

        // Load Plugins & Framework modal classes
        global $pluginFramework, $userMetaCache;
        $userMetaCache = new \stdClass();

        $this->loadModels($this->modelsPath);
        $this->loadModels($pluginFramework->modelsPath);
    }
}