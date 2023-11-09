<?php
namespace UserMeta;

class InitPluginModel
{

    function pluginInit()
    {
        global $userMeta;
        $userMeta->checkPro();
        if ($userMeta->isPro) {
            $userMeta->loadModels($userMeta->modelsPath . 'pro/');
        }
        $userMeta->loadDirectory($userMeta->pluginPath . '/helpers/', false);
        if ($userMeta->isPro) {
            $userMeta->loadDirectory($userMeta->pluginPath . '/helpers/pro/', false);
        }
        $this->loadControllers($userMeta->controllersPath);
        $userMeta->loadDirectory($userMeta->pluginPath . '/dev/', false);
        add_action('init', function () {
            do_action('user_meta_plugin_loaded');
        });
        $userMeta->proLoaded();
        $this->wpInitHook();
    }

    /**
     *
     * @depreciated since 1.2
     * Extensions should load by user_meta_loaded action
     */
    function wpInitHook()
    {
        global $userMeta;
        $this->loadExtension();
        if (! empty($userMeta->extensions)) {
            $config = [
                'namespace' => ''
            ];
            foreach ($userMeta->extensions as $extension) {
                $userMeta->loadDirectory($extension . '/models/', false, $config);
                $userMeta->loadDirectory($extension . '/controllers/', true, $config);
                $userMeta->loadDirectory($extension . '/helpers/', false, $config);
            }
        }
    }

    /**
     *
     * @depreciated since 1.2
     * Extensions should load by user_meta_loaded action
     */
    function loadExtension()
    {
        global $userMeta;
        if ($userMeta->isPro())
            $extensions = apply_filters('user_meta_load_extension', []);
        $userMeta->extensions = ! empty($extensions) ? $extensions : [];
    }

    function loadControllers($controllersPath)
    {
        global $userMeta;
        $controllersOrder = [];

        $classes = $instance = [];
        foreach (scandir($controllersPath) as $file) {
            if (preg_match("/.php$/i", $file))
                $classes[str_replace(".php", "", $file)] = $controllersPath . $file;
        }

        $proClasses = $userMeta->loadProControllers($classes, $controllersPath);
        if (is_array($proClasses))
            $classes = $proClasses;

        foreach ($classes as $className => $classPath) {
            require_once ($classPath);
            if (! in_array($className, $controllersOrder))
                $controllersOrder[] = $className;
        }

        foreach ($controllersOrder as $className) {
            $classWithNamespace = '\\' . __NAMESPACE__ . '\\' . $className;
            if (class_exists($classWithNamespace))
                $instance[] = new $classWithNamespace();
        }

        return $instance;
    }

    function renderPro($viewName, $parameter = array(), $subdir = null, $ob = false)
    {
        global $userMeta;

        $viewPath = self::locateView($viewName, $subdir);
        if (! $viewPath)
            return;

        if ($parameter)
            extract($parameter);

        if ($ob)
            ob_start();

        $pageReturn = include $viewPath;

        if ($ob) {
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }

        if ($pageReturn and $pageReturn != 1)
            return $pageReturn;

        if (isset($html))
            return $html;
    }

    function locateView($viewName, $subdir = null)
    {
        global $userMeta;

        $locations = array();
        if ($subdir)
            $subdir = $subdir . '/';

        $proLocations = $userMeta->locateProView($locations);
        if (is_array($proLocations))
            $locations = $proLocations;

        foreach ($userMeta->extensions as $extension)
            $locations[] = $extension . '/views/';
        $locations[] = $userMeta->viewsPath;

        foreach ($locations as $path) {
            $fullPath = $path . $subdir . $viewName . '.php';
            if (file_exists($fullPath))
                return $fullPath;
        }

        return false;
    }
}