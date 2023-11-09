<?php
use UserMeta\Html\Html;
// Expected: $text
?>

<h4><?= __('Custom text for front-end', 'user-meta') ?></h4>
<em><?= __('(Leave blank to use default)', 'user-meta') ?></em>

<?php
$msgs = $userMeta->msgs();
foreach ($msgs as $key => $msg) {
    if (strpos($key, 'group_') !== false) {
        echo "<div class='pf_divider'></div><h4>$msg</h4>";
        continue;
    }

    /*
     * Commented since 2.3
     *
     * $html .= $userMeta->createInput("text[$key]", 'text', array(
     * 'id' => "text_$key",
     * 'label' => $key,
     * 'value' => ! empty($text[$key]) ? $text[$key] : $msg,
     * "label_class" => "pf_label",
     * "class" => "um_input",
     * "style" => "width: 600px;",
     * 'enclose' => 'p'
     * ));
     */

    echo Html::text(! empty($text[$key]) ? $text[$key] : $msg, [
        'name' => "text[$key]",
        'id' => "text_$key",
        'label' => $key,
        'class' => 'um_input form-control',
        'style' => 'width: 600px;',
        '_enclose' => [
            'div',
            'class' => 'form-group'
        ]
    ]);
}