<?php
/**
 * WordPress function for non WP users.
 * 
 * @author Khaled Hossain
 */

if (defined('ABSPATH') && file_exists( ABSPATH . 'wp-load.php' )) {
    return;
}

if (! function_exists('esc_attr')) {

    /**
     * Declare esc_attr if the project is used outside WordPress.
     * WordPress uses esc_attr for escaping attributes. The function contains rich set of filters.
     *
     * @since 1.0.0
     * @param
     *            string : $text
     * @return string
     */
    function esc_attr($text)
    {
        return htmlspecialchars($text, ENT_QUOTES);
    }
}

if (! function_exists('esc_html')) {

    /**
     * Declare esc_html if the project is used outside WordPress.
     * WordPress uses esc_html for escaping html. The function contains rich set of filters.
     *
     * @since 1.0.0
     * @param
     *            string : $text
     * @return string
     */
    function esc_html($text)
    {
        return htmlspecialchars($text, ENT_QUOTES);
    }
}

if (! function_exists('esc_url')) {

    /**
     * Declare esc_url if the project is used outside WordPress.
     * WordPress uses esc_url for escaping url. The function contains rich set of filters.
     *
     * @since 1.2
     * @param
     *            string : $text
     * @return string
     */
    function esc_url($text)
    {
        return $text;
    }
}

if (! function_exists('esc_textarea')) {

    /**
     * Declare esc_textarea if the project is used outside WordPress.
     * WordPress uses esc_textarea for escaping textarea. The function contains rich set of filters.
     *
     * @since 1.2
     * @param
     *            string : $text
     * @return string
     */
    function esc_textarea($text)
    {
        return htmlspecialchars($text, ENT_QUOTES);
    }
}