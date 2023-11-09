<?php
namespace UserMeta;

global $userMeta;
// Expected: $config, $disableAjax, $methodName

$uniqueID = isset($config['unique_id']) ? $config['unique_id'] : rand(0, 99);

$html = null;

if (empty($config['only_lost_pass_form'])) {
    $linkText = isset($config['link_text']) ? $config['link_text'] : $userMeta->getMsg('lostpassword_link');
    $linkClass = isset($config['link_class']) ? $config['link_class'] : '';
    $html .= "<a href=\"javascript:void(0);\" class=\"lostpassword_link$uniqueID $linkClass\">" . $linkText . "</a>";
    $displayNone = "display:none;";
} else
    $displayNone = null;

if (isset($userMeta->um_post_method_status->$methodName)) {
    $response = $userMeta->um_post_method_status->$methodName;
    $displayNone = null;
}

$html .= doActionHtml('lost_password');

$onSubmit = $disableAjax ? null : "onsubmit=\"pfAjaxRequest(this); return false;\"";

if (isset($config['before_form']))
    $html .= $config['before_form'];

$formID = ! empty($config['form_id']) ? $config['form_id'] : "um_lostpass_form$uniqueID";
$formClass = ! empty($config['form_class']) ? $config['form_class'] : '';

$html .= "<form id=\"$formID\" class=\"um_lostpass_form $formClass\" method=\"post\" $onSubmit >";

if (isset($config['before_div']))
    $html .= $config['before_div'];

$html .= "<div class=\"lostpassword_form_div$uniqueID\" style=\"$displayNone\" >";

if (! empty($response))
    $html .= $response;

$introText = isset($config['intro_text']) ? $config['intro_text'] : $userMeta->getMsg('lostpassword_intro');
$html .= "<p>" . $introText . "</p>";

/*
 * if( !@$_REQUEST['is_ajax'] && @$_REQUEST['method_name'] == 'lostpassword' )
 * $html .= $userMeta->ajaxLostpassword();
 */

$html .= $userMeta->createInput('user_login', 'text', array(
    'value' => isset($_POST['user_login']) ? esc_attr(stripslashes($_POST['user_login'])) : '',
    'label' => isset($config['input_label']) ? $config['input_label'] : $userMeta->getMsg('lostpassword_label'),
    'placeholder' => ! empty($config['placeholder']) ? $config['placeholder'] : '',
    'id' => ! empty($config['input_id']) ? $config['input_id'] : 'user_login' . $uniqueID,
    'class' => ! empty($config['input_class']) ? $config['input_class'] : 'um_lostpass_field um_input',
    'label_class' => ! empty($config['input_label_class']) ? $config['input_label_class'] : 'pf_label',
    'enclose' => 'p'
));

$html .= doActionHtml('lostpassword_form');

$html .= $userMeta->methodPack($methodName);

if (! empty($_REQUEST['redirect_to'])) {
    $html .= $userMeta->createInput('redirect_to', 'hidden', array(
        'value' => esc_url($_REQUEST['redirect_to'])
    ));
}

if (isset($config['before_button']))
    $html .= $config['before_button'];

$html .= $userMeta->createInput('wp-submit', 'submit', array(
    'value' => ! empty($config['button_value']) ? $config['button_value'] : $userMeta->getMsg('lostpassword_button'),
    'id' => ! empty($config['button_id']) ? $config['button_id'] : 'um_lostpass_button' . $uniqueID,
    'class' => ! empty($config['button_class']) ? $config['button_class'] : 'um_lostpass_button',
    'enclose' => 'p'
));

if (isset($config['after_button']))
    $html .= $config['after_button'];

$html .= "</div>";

if (isset($config['after_div']))
    $html .= $config['after_div'];

$html .= "</form>";

if (isset($config['after_form']))
    $html .= $config['after_form'];

$js = "jQuery('.lostpassword_link$uniqueID').click(function(){";
$js .= "jQuery('.lostpassword_form_div$uniqueID').toggle('slow');";
$js .= "});";

addFooterJs($js);
footerJs();