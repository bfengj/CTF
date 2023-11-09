<?php
namespace UserMeta;

use UserMeta\Html\Html;
global $userMeta;
/**
 * Expected: $form
 * $form['fields'] : array containing field's id
 * $form['disable_ajax'] : bool (default false)
 * $form['onsubmit'] : full js code for onsubmit
 * $form['form_id'] : Name of the form id
 * $form['form_class'] : Name of the form class
 * $form['form_action'] : Action name for form
 * $form['form_start'] : Just after form starting tag
 * $form['form_end'] : Just before form ending tag
 *
 * get veriable by render: $actionType, $fields, $form
 */

if (empty($form))
    return $html = $userMeta->ShowError(__('Form is not found.', $userMeta->name));

if (empty($form['fields']))
    return $html = $userMeta->ShowError(__('Fields are not found.', $userMeta->name));

if (! is_array($form['fields']))
    return $html = $userMeta->ShowError(__('Fields were saved incorrectly.', $userMeta->name));

$fields = $form['fields'];
if (empty($userID))
    $userID = 0;

$pageCount = ! empty($form['page_count']) ? $form['page_count'] : 0;

/**
 * Applying filter hook.
 * Accept two arg: $form, $formName
 * return $form
 */
$form = apply_filters('user_meta_form_config', $form, $form['form_key'], $userID);
$form = apply_filters('user_meta_form_config_render', $form, $form['form_key'], $userID);

$uniqueID = sanitize_key($form['form_key']);

$formID = ! empty($form['form_id']) ? $form['form_id'] : "um_form_$uniqueID";
$formClass = ! empty($form['form_class']) ? $form['form_class'] : null;
$formAction = ! empty($form['form_action']) ? "action=\"" . $form['form_action'] . "\"" : null;
$onsubmitEsc = ! empty($form['onsubmit']) ? "onsubmit=\"" . esc_js($form['onsubmit']) . "return false;\"" : null;

$html = null;

if ($actionType == 'registration' && $userMeta->isHookEnable('login_form_register', [
    'form' => $form['form_key']
])) {
    do_action('login_form_register');
}

if (@$_REQUEST['form_key'] == $form['form_key'] && @$_REQUEST['action_type'] == $actionType) {
    if (isset($userMeta->um_post_method_status->$methodName))
        $html .= $userMeta->um_post_method_status->$methodName;
}

$formClass .= ' um_generated_form';

do_action('user_meta_before_form', $form['form_key']);
$formIdEsc = esc_attr($formID);
$formClassEsc = esc_attr($formClass);
$html .= "<form method=\"post\" $formAction id=\"$formIdEsc\" class=\"$formClassEsc\" $onsubmitEsc enctype=\"multipart/form-data\" > ";
if (! empty($form['form_start']))
    $html .= $form['form_start'];

if (is_array($fields)) {

    $inPage = false;
    $inSection = false;
    $isNext = false;
    $isPrevious = false;
    $currentPage = 0;
    foreach ($fields as $fieldID => $field) {

        if ($field['field_type'] == 'page_heading') {
            $currentPage ++;
            $isNext = $currentPage >= 2 ? true : false;
            $isPrevious = $currentPage > 2 ? true : false;
        }

        $html .= (new Field(null, $field, [
            'action_type' => $actionType,
            'unique_id' => $uniqueID,
            'user_id' => $userID,
            'form' => $form,
            'in_page' => $inPage,
            'in_section' => $inSection,
            'is_next' => $isNext,
            'is_previous' => $isPrevious,
            'current_page' => $currentPage
        ]))->render();

        if ($field['field_type'] == 'page_heading') {
            $inPage = true;
            $inSection = false;
        }

        if ($field['field_type'] == 'section_heading')
            $inSection = true;
    }

    // Similar to generateField: field_type==page_heading
    if ($inSection) {
        $html .= "</div>";
    }
    if ($pageCount >= 2) {
        $previousPage = $currentPage - 1;
        $html .= Html::button(__('Previous', $userMeta->name), [
            "onclick" => "umPageNavi($previousPage, false, this)",
            "class" => "previous_button um_button " . ! empty($form['button_class']) ? $form['button_class'] : ""
        ]);
    } else {
        $html .= "<div class=\"um_clear\"></div>";
    }
    // End

    if ($actionType == 'registration' && $userMeta->isHookEnable('register_form', array(
        'form' => $form['form_key']
    ))) {
        $html .= doActionHtml('register_form');
    }

    $html .= Html::hidden($form['form_key'], [
        'name' => 'form_key'
    ]);
    $html .= Html::hidden($actionType, [
        'name' => 'action_type'
    ]);
    $html .= Html::hidden($userID, [
        'name' => 'user_id'
    ]);

    $html .= $userMeta->methodPack($methodName);

    if ($actionType == 'profile')
        $buttonValue = __('Update', $userMeta->name);
    elseif ($actionType == 'registration')
        $buttonValue = __('Register', $userMeta->name);
    elseif ($actionType == 'login')
        $buttonValue = __('Login', $userMeta->name);

    if (! empty($form['button_title']))
        $buttonValue = $form['button_title'];

    if (isset($buttonValue)) {
        $html .= Html::submit($buttonValue, [
            'name' => 'um_submit_button',
            'id' => 'insert_user',
            'class' => ! empty($form['button_class']) ? $form['button_class'] : 'um_button'
        ]);
    }

    if ($inPage)
        $html .= "</div>";
}

if (! empty($form['form_end']))
    $html .= $form['form_end'];

$html .= "</form>";

do_action('user_meta_after_form', $form['form_key'], $userID);

/**
 * Call proper js if there is a pagination on the page
 */
if (! empty($currentPage)) {
    $js = "umPageNavi(1, false, '$formID');";
    addFooterJs($js);
}
footerJs();

$html = apply_filters('user_meta_form_display', $html, $form['form_key'], $form, $userID);
