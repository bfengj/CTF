<?php
// +---------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +---------------------------------------------------------------------
// | Copyright (c) 2013-present http://www.thinkcmf.com All rights reserved.
// +---------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +---------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +---------------------------------------------------------------------
namespace cmf\behavior;

use think\db\Query;
use think\exception\HttpResponseException;
use think\facade\Hook;
use think\Db;
use think\facade\Response;
use think\facade\Route;

class InitHookBehavior
{

    // 行为扩展的执行入口必须是run
    public function run($param)
    {
        Route::any('plugin/:_plugin/[:_controller]/[:_action]', "\\cmf\\controller\\PluginController@index");
        Route::get('new_captcha', "\\cmf\\controller\\CaptchaController@index");

        if (!cmf_is_installed()) {
            return;
        }

        $systemHookPlugins = cache('init_hook_plugins_system_hook_plugins');
        if (empty($systemHookPlugins)) {
            $systemHooks = Db::name('hook')->where('type', 1)->whereOr(function (Query $query) {
                $query->where('type', 3)->where('app', ['eq', ''], ['eq', 'cmf'], 'or');
            })->column('hook');

            $systemHookPlugins = Db::name('hook_plugin')->field('hook,plugin')->where('status', 1)
                ->where('hook', 'in', $systemHooks)
                ->order('list_order ASC')
                ->select()->toArray();

            if (!empty($systemHookPlugins)) {
                cache('init_hook_plugins_system_hook_plugins', $systemHookPlugins, null, 'init_hook_plugins');
            }
        }

        if (!empty($systemHookPlugins)) {
            if (is_array($systemHookPlugins)) {
                foreach ($systemHookPlugins as $hookPlugin) {
                    Hook::add($hookPlugin['hook'], cmf_get_plugin_class($hookPlugin['plugin']));
                }
            }
        }
    }
}
