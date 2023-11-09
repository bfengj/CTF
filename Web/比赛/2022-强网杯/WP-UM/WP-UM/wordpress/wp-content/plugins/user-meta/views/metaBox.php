<?php
global $pfInstance;

$deleteLink = $deleteIcon ? "<div class='pf_trash' title='" . __('Click to remove', $pfInstance->name) . "' onclick='pfRemoveMetaBox(this);'><br></div>" : null;
$display = ! $isOpen ? "style='display:none'" : "";
$closed = ! $isOpen ? 'closed ' : '';

$html = "
<div id='side-sortables' class='meta-box-sortables'>
    <div id='user_meta' class='postbox $closed'>
        $deleteLink
        <div class='handlediv' title='" . __('Click to toggle', $pfInstance->name) . "' onclick='pfToggleMetaBox(this);'><br><span title='Click to toggle'><i class='fa fa-caret-down'></i></span></div>
        <h3 class='hndle'>$title</h3>
        <div class='inside' $display>
            <p></p>
            $content
            <p></p>
        </div>
    </div>
</div>
";
