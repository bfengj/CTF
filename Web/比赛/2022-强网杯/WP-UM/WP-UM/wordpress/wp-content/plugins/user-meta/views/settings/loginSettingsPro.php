<?php
/**
 * Expected: $login
 * field slug: login
 */
global $userMeta;
$html = null;

$roles = $userMeta->getRoleList();

$html .= "<div class='pf_divider'></div>";

/**
 * Login By
 */
$html .= "<h4>" . __('User login by', $userMeta->name) . "</h4>";
$html .= $userMeta->createInput("login[login_by]", "radio", array(
    'value' => @$login['login_by'],
    'id' => 'um_login_login_by',
    'by_key' => true
), $userMeta->loginByArray());
$html .= "<div class='pf_divider'></div>";

/**
 * Reset Password
 */
$html .= "<h4>" . __('Reset Password Page', $userMeta->name) . "</h4>";
$html .= wp_dropdown_pages(array(
    'name' => 'login[resetpass_page]',
    'id' => 'um_login_resetpass_page',
    'class' => 'um_page_dropdown',
    'selected' => @$login['resetpass_page'],
    'echo' => 0,
    'show_option_none' => 'None '
));
$html .= '<a href="#" id="um_login_resetpass_page_view" class="button-secondary">View Page</a>';

$createPageUrl = admin_url('admin-ajax.php');
$createPageUrl = add_query_arg(array(
    'page' => 'resetpass',
    'method_name' => 'generatePage',
    'action' => 'pf_ajax_request'
), $createPageUrl);
$createPageUrl = wp_nonce_url($createPageUrl, 'generate_page');
$html .= "<a href='$createPageUrl' id='um_login_resetpass_page_create' class='button-primary'>Create Page</a>";

$html .= " <span class='um_required_resetpass_page_page' style='color:red'><em><strong>(" . __('Please select any page for resetting password as your default login url is disabled!', $userMeta->name) . ")</strong></em></span>";
$html .= '<p><em>' . __('This is the page a user will be redirected to when they want to retrieve/reset their password.', $userMeta->name) . '</em></p>';

$html .= "<div class='pf_divider'></div>";

/**
 * LoggedIn Profile
 */
$html .= "<h4>" . __('Logged in user profile settings', $userMeta->name) . "</h4>";
$html .= "<div id=\"loggedin_profile_tabs\">";
$html .= "<ul>";
foreach ($roles as $key => $val)
    $html .= "<li><a href=\"#profile-tabs-$key\">$val</a></li>";
$html .= "</ul>";

$placeholder = "<p><strong>Place Holder</strong></p>
    <p><em>%site_title%, %site_url%, %logout_url%, %admin_url%, %ID%, %user_login%, %user_email%, %user_url%, %first_name%, %last_name%, %display_name%, %nickname%, %avatar%, %your_meta_key%</em></p> ";

foreach ($roles as $key => $val) {
    $html .= $userMeta->createInput("login[loggedin_profile][{$key}]", "textarea", array(
        "value" => @$login['loggedin_profile'][$key] ? @$login['loggedin_profile'][$key] : @$login['loggedin_profile']['subscriber'],
        "label" => "Logged in user profile",
        "label_class" => "pf_label",
        "rows" => "10",
        "cols" => "50",
        "after" => $placeholder,
        "enclose" => "div id=\"profile-tabs-$key\""
    ));
}
$html .= "</div>";

if (is_multisite()) {
    $html .= "<div class='pf_divider'></div>";
    $html .= "<h4>" . __('Multisite', $userMeta->name) . "</h4>";
    
    $html .= $userMeta->createInput("login[blog_member_only]", "checkbox", array(
        'value' => @$login['blog_member_only'],
        'id' => 'um_login_blog_member_only',
        'label' => __('Prevent user login if user is not member of current site.', $userMeta->name),
        'enclode' => 'p',
        'style' => 'margin-top:0px;'
    ));
}
