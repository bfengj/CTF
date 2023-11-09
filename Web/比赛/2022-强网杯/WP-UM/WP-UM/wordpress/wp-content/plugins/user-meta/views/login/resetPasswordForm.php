<?php
namespace UserMeta;

global $userMeta;
// Expected: $config, $user, $errors

$html = null;

$formID = ! empty($config['form_id']) ? $config['form_id'] : "um_resetpassword_form";
$formClass = ! empty($config['form_class']) ? $config['form_class'] : '';

if (isset($config['before_form']))
    $html .= $config['before_form'];

$html .= "<form id=\"$formID\" class=\"$formClass\" method=\"post\" >";

if (isset($config['heading'])) {
    if (! empty($config['heading']))
        $html .= "<h2>" . $config['heading'] . "</h2>";
} //else
    //$html .= "<h2>" . $userMeta->getMsg('resetpassword_heading') . "</h2>";

if (isset($config['intro_text'])) {
    if (! empty($config['intro_text']))
        $html .= "<p>" . $config['intro_text'] . "</p>";
} else
    $html .= "<p>" . $userMeta->getMsg('resetpassword_intro') . "</p>";

if ($errors->get_error_code())
    $html .= $userMeta->showError($errors->get_error_message(), false);

$html .= $userMeta->createInput('pass1', 'password', array(
    'label' => ! empty($config['pass1_label']) ? $config['pass1_label'] : $userMeta->getMsg('resetpassword_pass1_label'),
    'placeholder' => ! empty($config['pass1_placeholder']) ? $config['pass1_placeholder'] : '',
    'id' => ! empty($config['pass1_id']) ? $config['pass1_id'] : 'um_pass1',
    'class' => ! empty($config['pass1_class']) ? $config['pass1_class'] . ' ' : '' . 'um_input pass_strength validate[required]',
    'label_class' => ! empty($config['pass1_label_class']) ? $config['pass1_label_class'] : 'pf_label',
    'pattern' => ! empty($config['pattern']) ? $config['pattern'] : '',
    'after' => ! empty($config['pattern_rule_text']) ? '<p><small>'.$config['pattern_rule_text'].'</small></p>' : '',
    'oninvalid' => ! empty($config['invalid_pattern_text']) ? "setCustomValidity('".$config['invalid_pattern_text']."')" : "setCustomValidity('".$userMeta->getMsg('invalid_pattern_text')."')",
    'oninput' => "setCustomValidity('')",
    'autocomplete' => 'off',
    'enclose' => 'p'
));

$html .= $userMeta->createInput('pass2', 'password', array(
    'label' => ! empty($config['pass2_label']) ? $config['pass2_label'] : $userMeta->getMsg('resetpassword_pass2_label'),
    'placeholder' => ! empty($config['pass2_placeholder']) ? $config['pass2_placeholder'] : '',
    'id' => ! empty($config['pass2_id']) ? $config['pass2_id'] : 'um_pass2',
    'class' => ! empty($config['pass2_class']) ? $config['pass2_class'] . ' ' : '' . 'um_input validate[required,equals[um_pass1]]',
    'label_class' => ! empty($config['pass2_label_class']) ? $config['pass2_label_class'] : 'pf_label',
    'autocomplete' => 'off',
    'enclose' => 'p'
));

$html .= doActionHtml('resetpass_form', [
    $user
]);

$html .= $userMeta->nonceField();

if (isset($config['before_button']))
    $html .= $config['before_button'];

$html .= $userMeta->createInput('wp-submit', 'submit', array(
    'value' => ! empty($config['button_value']) ? $config['button_value'] : $userMeta->getMsg('resetpassword_button'),
    'id' => ! empty($config['button_id']) ? $config['button_id'] : 'um_resetpassword_button',
    'class' => ! empty($config['button_class']) ? $config['button_class'] : '',
    'enclose' => 'p'
));

if (isset($config['after_button']))
    $html .= $config['after_button'];

$html .= "</form>";

if (isset($config['after_form']))
    $html .= $config['after_form'];

$js = "jQuery(\"#$formID\").validationEngine();";
$js .= "jQuery(\".pass_strength\").password_strength();";

addFooterJs($js);
footerJs();