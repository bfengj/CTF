<?php
global $userMeta;
/**
 * *
 * Expected: $id, $conditional
 */

extract($conditional);

$hidden = empty($conditional) ? 'um_hidden' : '';
?>

<div class="form-group">
	<p class="col-sm-offset-3 col-sm-9">
        <?php
        echo $userMeta->createInput("", "checkbox", array(
            "value" => ! empty($conditional) ? true : false,
            "label" => __('Enable Conditional Logic', $userMeta->name) . "<br />",
            "id" => "um_enable_conditional_logic_{$id}",
            "class" => "um_enable_conditional_logic form-control"
        ));
        ?>
        
    </p>
</div>

<div class="um_conditional_details <?php echo $hidden;?>">

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
            <?php
            echo $userMeta->createInput("", "select", array(
                "value" => isset($visibility) ? $visibility : null,
                "by_key" => true,
                "class" => "um_conditional_visibility"
            ), array(
                'show' => 'Show',
                'hide' => 'Hide'
            ));
            ?>

            <span>this field for following condition(s):</span>
		</div>
	</div>

	<div class="um_conditions">      
        <?php
        if (empty($conditional['rules']) || (! empty($conditional['rules']) && ! is_array($conditional['rules']))) {
            $conditional['rules'] = array(
                array()
            );
        }
        
        foreach ($conditional['rules'] as $rule) {
            echo $userMeta->renderPro('conditionalRow', array(
                'rule' => $rule,
                'fieldList' => $fieldList
            ), 'forms', true);
        }
        ?> 
    </div>

	<div class="form-group um_conditional_relation_div">
		<div class="col-sm-offset-3 col-sm-9">
            <?php
            echo $userMeta->createInput("", "select", array(
                "value" => isset($relation) ? $relation : null,
                "by_key" => true,
                "class" => "um_conditional_relation"
            ), array(
                'and' => 'All',
                'or' => 'Any'
            ));
            ?>

            <span>condition(s) need to match.</span>
		</div>
	</div>

</div>


