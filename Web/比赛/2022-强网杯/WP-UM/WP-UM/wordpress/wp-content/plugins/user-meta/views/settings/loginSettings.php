<?php
/**
 * Expected: $login
 * field slug: login
 */
global $userMeta;
$html = null;

/**
 * Login Page
 */
$html .= "<h4>" . __('Login Page', $userMeta->name) . "</h4>";
$html .= wp_dropdown_pages([
    'name' => 'login[login_page]',
    'id' => 'um_login_login_page',
    'class' => 'um_page_dropdown',
    'selected' => @$login['login_page'],
    'echo' => 0,
    'show_option_none' => 'None '
]);
$html .= '<a href="#" id="um_login_login_page_view" class="button-secondary">View Page</a>';

$createPageUrl = admin_url('admin-ajax.php');
$createPageUrl = add_query_arg([
    'page' => 'login',
    'method_name' => 'generatePage',
    'action' => 'pf_ajax_request'
], $createPageUrl);
$createPageUrl = wp_nonce_url($createPageUrl, 'generate_page');
$html .= "<a href='$createPageUrl' id='um_login_login_page_create' class='button-primary'>Create Page</a>";

$html .= "<p>" . sprintf(__('Login page should contain shortcode like: %s', $userMeta->name), "[user-meta-login]") . "</p>";

$html2 = $userMeta->createInput("login[disable_wp_login_php]", "checkbox", [
    "label" => sprintf(__('Disable default login url (%s)', $userMeta->name), site_url('wp-login.php')),
    "value" => @$login['disable_wp_login_php'],
    "id" => "um_login_disable_wp_login_php",
    "onchange" => "umSettingsToggleError()",
    'enclose' => 'p',
    'style' => 'margin-top:0px;'
]);

$loginUrl = ! empty($login['login_page']) ? get_permalink($login['login_page']) : null;
$html2 .= '<p><em>' . sprintf(__('Disable wp-login.php and redirect to front-end login page %s', $userMeta->name), $loginUrl) . '</em></p>';

$html .= "<div id=\"um_login_disable_wp_login_php_block\">$html2</div>";

$html .= "<div class='pf_divider'></div>";

/**
 * Login Form
 */
$html .= "<h4>" . __('Login Form', $userMeta->name) . "</h4>";
$html .= $userMeta->createInput("login[disable_lostpassword]", "checkbox", [
    "value" => @$login['disable_lostpassword'],
    "id" => "um_login_disable_lostpassword",
    "label" => __('Disable lost password feature', $userMeta->name),
    'enclose' => 'p',
    'style' => 'margin-top:0px;'
]);

$html .= $userMeta->createInput("login[disable_registration_link]", "checkbox", [
    "value" => @$login['disable_registration_link'],
    "id" => "um_login_disable_registration_link",
    "label" => __('Hide registration link', $userMeta->name),
    'enclose' => 'p',
    'style' => 'margin-top:0px;'
]);

$html .= $userMeta->createInput("login[disable_ajax]", "checkbox", [
    "value" => @$login['disable_ajax'],
    "id" => "um_login_disable_ajax",
    "label" => __('Disable AJAX submit', $userMeta->name),
    'enclose' => 'p',
    'style' => 'margin-top:0px;'
]);

$html .= $userMeta->renderPro("loginSettingsPro", [
    'login' => $login
], "settings");