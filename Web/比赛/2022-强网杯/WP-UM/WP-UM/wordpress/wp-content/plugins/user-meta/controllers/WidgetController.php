<?php
namespace UserMeta;

/**
 * Initialize widgets
 *
 * @since 1.2.1
 * @author Khaled Hossain
 */
class WidgetController
{

    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {
        /**
         * Register FormWidget widget
         */
        add_action('widgets_init', function () {
            register_widget("UserMeta\FormWidget");
        });

        /**
         * Register LoginWidget widget
         */
        add_action('widgets_init', function () {
            register_widget("UserMeta\LoginWidget");
        });
    }
}

