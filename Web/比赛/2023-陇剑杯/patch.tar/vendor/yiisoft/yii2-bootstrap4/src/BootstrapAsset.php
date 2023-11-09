<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\bootstrap4;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Twitter bootstrap css files.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 */
class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@npm/bootstrap/dist';
    public $css = [
        'css/bootstrap.css',
    ];
}
