<?php
/*
 * Plugin Name: User Meta Lite
 * Plugin URI: https://user-meta.com
 * Description: A well-designed, feature-rich, and easy to use user management plugin.
 * Version: 2.4.3
 * Requires at least: 4.7
 * Requires PHP: 5.6.0
 * Author: User Meta
 * Author URI: https://user-meta.com
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: user-meta
 * Domain Path: /helpers/languages
 */
if (version_compare(PHP_VERSION, '5.6.0', '<')) {
    /**
     * 'User Meta Lite' will replaced by 'User Meta Lite' by deploy script for the lite version.
     */
    add_action('admin_notices', function () {
        echo '<div class=\"error\"><p>User Meta Lite plugin requires <strong>  PHP 5.6.0</strong> or above. Current PHP version: ' . PHP_VERSION . '</p></div>';
    });
    return;
}

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Please don\'t access this file directly.');
}

require __DIR__ . '/vendor/autoload.php';

global $pluginFramework, $userMeta;

if (! is_object($pluginFramework)) {
    $pluginFramework = new UserMeta\Framework();
}
$pluginFramework->loadDirectory($pluginFramework->controllersPath);

$userMeta = new UserMeta\UserMeta(__FILE__);
$userMeta->init();
