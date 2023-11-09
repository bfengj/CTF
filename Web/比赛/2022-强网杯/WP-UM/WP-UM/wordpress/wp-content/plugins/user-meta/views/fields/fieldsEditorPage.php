<?php
namespace UserMeta;

global $userMeta;

$formBuilder = new FormBuilder('fields_editor');
?>

<div class="wrap">
	<h1><?php _e('Shared Fields', $userMeta->name); ?></h1>
	<p><?php _e('Click field from right side panel for creating a new field', $userMeta->name); ?></p>
    <?php do_action( 'um_admin_notice' ); ?>
    
    <div id="um_fields_editor" class="row">

		<div class="col-xs-12 col-sm-8 ">
			<div id="um_fields_container" class="metabox-holder">
                <?php $formBuilder->displayAllFields(); ?>
            </div>
		</div>

		<div id="um_steady_sidebar_holder" class="col-xs-12 col-sm-4 ">
			<div id="um_steady_sidebar">
				<div id="um_fields_selectors" class="panel-group">
                    <?php $formBuilder->fieldsSelectorPanels(); ?>
                </div>

				<div id="um_additional_input" class="um_hidden">
                    <?php echo $userMeta->methodName( 'updateFields', true ); ?>
                    <?php echo $formBuilder->maxFieldInput(); ?>
                    <?php echo $formBuilder->additional(); ?>            
                </div>

				<p class="">
					<button style="float: right" type="button"
						class="um_save_button btn btn-primary"><?= __('Save Changes', $userMeta->name) ?></button>
				</p>
				<p class="um_clear"></p>
				<p class="um_error_msg"></p>

			</div>
		</div>

	</div>

</div>
