<?php
namespace UserMeta;

global $userMeta;
// Expected: $config, $loginTitle, $disableAjax, $methodName

$uniqueID = isset($config['unique_id']) ? $config['unique_id'] : rand(0, 99);

$onSubmit = $disableAjax ? null : "onsubmit=\"umLogin(this); return false;\"";

$html = null;

if (isset($config['before_form']))
    $html .= $config['before_form'];

$formID = ! empty($config['form_id']) ? $config['form_id'] : "um_login_form$uniqueID";
$formClass = isset($config['form_class']) ? $config['form_class'] : 'um_login_form';

$html .= "<form id=\"$formID\" class=\"$formClass\" method=\"post\" $onSubmit >";

$html .= $userMeta->createInput('log', 'text', array(
    'value' => isset($_REQUEST['log']) ? esc_attr(stripslashes($_REQUEST['log'])) : '',
    'label' => ! empty($config['login_label']) ? $config['login_label'] : $loginTitle,
    'placeholder' => ! empty($config['login_placeholder']) ? $config['login_placeholder'] : '',
    'id' => ! empty($config['login_id']) ? $config['login_id'] : 'user_login' . $uniqueID,
    'class' => ! empty($config['login_class']) ? $config['login_class'] : 'um_login_field um_input',
    'label_class' => ! empty($config['login_label_class']) ? $config['login_label_class'] : 'pf_label',
    'enclose' => 'p'
));

$html .= $userMeta->createInput('pwd', 'password', array(
    'label' => ! empty($config['pass_label']) ? $config['pass_label'] : $userMeta->getMsg('login_pass_label'),
    'placeholder' => ! empty($config['pass_placeholder']) ? $config['pass_placeholder'] : '',
    'id' => ! empty($config['pass_id']) ? $config['pass_id'] : 'user_pass' . $uniqueID,
    'class' => ! empty($config['pass_class']) ? $config['pass_class'] : 'um_pass_field um_input',
    'label_class' => ! empty($config['pass_label_class']) ? $config['pass_label_class'] : 'pf_label',
    'enclose' => 'p'
));

$html .= doActionHtml('login_form');

$html .= $userMeta->createInput('rememberme', 'checkbox', array(
    'value' => isset($_REQUEST['rememberme']) ? true : false,
    'label' => ! empty($config['remember_label']) ? $config['remember_label'] : $userMeta->getMsg('login_remember_label'),
    'id' => ! empty($config['remember_id']) ? $config['remember_id'] : 'remember' . $uniqueID,
    'class' => ! empty($config['remember_class']) ? $config['remember_class'] : 'um_remember_field',
    'enclose' => 'p'
));

// $html .= "<input type='hidden' name='action' value='um_login' />";
// $html .= "<input type='hidden' name='action_type' value='login' />";

$html .= $userMeta->methodPack($methodName);

if (! empty($_REQUEST['redirect_to'])) {
    $html .= $userMeta->createInput('redirect_to', 'hidden', array(
        'value' => esc_url($_REQUEST['redirect_to'])
    ));
}

if (isset($config['before_button']))
    $html .= $config['before_button'];

$html .= $userMeta->createInput('wp-submit', 'submit', array(
    'value' => ! empty($config['button_value']) ? $config['button_value'] : $userMeta->getMsg('login_button'),
    'id' => ! empty($config['button_id']) ? $config['button_id'] : 'um_login_button' . $uniqueID,
    'class' => ! empty($config['button_class']) ? $config['button_class'] : 'um_login_button',
    'enclose' => 'p'
));

if (isset($config['after_button']))
    $html .= $config['after_button'];

$html .= "</form>";

if (isset($config['after_form']))
    $html .= $config['after_form'];

$js = "jQuery(\"input\").placeholder();";
addFooterJs($js);
footerJs();