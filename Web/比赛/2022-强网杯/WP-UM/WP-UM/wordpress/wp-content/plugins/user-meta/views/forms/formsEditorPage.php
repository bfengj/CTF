<?php
namespace UserMeta;

global $userMeta;
?>

<div class="wrap">
	<h1><?php _e( 'Forms', $userMeta->name );?>
		<a href="?page=user-meta&action=new" class="add-new-h2"><?php _e( 'Add New', $userMeta->name ); ?></a>
	</h1>   
    <?php do_action( 'um_admin_notice' ); ?>
    <div id="dashboard-widgets-wrap">
		<div class="metabox-holder">
			<div id="um_admin_content">
                <?php
                if (! class_exists('WP_List_Table')) {
                    require_once (ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
                }
                $listTable = new FormsListTable();
                $listTable->prepare_items();
                $listTable->display();
                ?>                                          
            </div>

			<div id="um_admin_sidebar">                            
                <?php
                $panelArgs = [
                    'panel_class' => 'panel-default'
                ];
                echo panel(__('Get started', $userMeta->name), $userMeta->boxHowToUse(), $panelArgs);
                /*if (empty($userMeta->isPro)) {
                    echo panel(__('Live Demo', $userMeta->name), $userMeta->boxLiveDemo(), $panelArgs);
                    echo panel(__('User Meta Pro', $userMeta->name), $userMeta->boxGetPro(), $panelArgs);
                }*/
                echo panel(__('Shortcodes', $userMeta->name), $userMeta->boxShortcodesDocs(), $panelArgs);
                echo panel(__('Useful Links', $userMeta->name), $userMeta->boxLinks(), $panelArgs);
                echo panel(__('Rate Us', $userMeta->name), $userMeta->boxRate(), $panelArgs);
                ?>
            </div>
		</div>
	</div>
</div>

<script>
jQuery('#um_admin_sidebar .panel-collapse').removeClass('in');
jQuery('#um_admin_sidebar .panel-collapse').first().addClass('in');
</script>


