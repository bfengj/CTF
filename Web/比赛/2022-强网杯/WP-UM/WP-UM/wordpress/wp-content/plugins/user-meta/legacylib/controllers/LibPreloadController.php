<?php
namespace UserMeta;

class LibPreloadController
{

    function __construct()
    {
        global $pluginFramework;

        add_action('wp_enqueue_scripts', array(
            $this,
            'addjQuery'
        ));
        add_action('admin_print_scripts', array(
            $this,
            'setVariable'
        ));
        add_action('wp_print_scripts', array(
            $this,
            'setVariable'
        ));
    }

    function setVariable()
    {
        global $pluginFramework;
        $ajaxurl = admin_url('admin-ajax.php');
        $nonceText = $pluginFramework->settingsArray('nonce');
        $nonce = wp_create_nonce($nonceText);

        if (is_admin())
            echo "<script type='text/javascript'>pf_nonce='$nonce';</script>";
        else
            echo "<script type='text/javascript'>ajaxurl='$ajaxurl';pf_nonce='$nonce';</script>";
    }

    function addjQuery()
    {
        if (is_admin())
            return;

        wp_enqueue_script('jquery');
    }
}