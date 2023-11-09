<?php
global $userMeta;

use UserMeta\Html\Form;
/**
 * Expected: $hidden
 */
?>

<div class="um_plusminus_row um_advanced um_optgroup form-inline" style="<?php echo $hidden ?>margin-bottom:10px;">
	<div class="input-group">
		<div class="input-group-addon">Group</div>
    <?php
    /*
     * $input = new userMeta\Input\Text(array(
     * 'value' => isset( $option['label'] ) ? $option['label'] : null,
     * 'class' => 'um_option_gruop form-control',
     * 'placeholder' => 'Group Label',
     * ));
     * echo $input->render();
     */
    
    echo Form::text(Form::get('label', $option), [
        'class' => 'um_option_gruop form-control',
        'placeholder' => 'Group Label'
    ]);
    
    ?>
    </div>

	<button type="button" class="btn btn-default um_row_button_plus">
		<i class="fa fa-plus"></i>
	</button>
	<button type="button" class="btn btn-default um_row_button_minus">
		<i class="fa fa-minus"></i>
	</button>
</div>
