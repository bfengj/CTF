<?php
/**
 * Expected: $settings, $forms, $fields, $default
 */
global $userMeta;

$isPro = $userMeta->isPro();
$pageTitle = $isPro ? __('User Meta Pro Settings', $userMeta->name) : __('User Meta Settings', $userMeta->name);
?>

<div class="wrap">
	<h1><?= $pageTitle ?></h1>
    <?php do_action( 'um_admin_notice' ); ?>
    <div id="dashboard-widgets-wrap">
		<div id="um_settings_admin" class="metabox-holder">
			<div id="um_admin_content">
                <?php
                if ($userMeta->isPro) {
                    $userMeta->renderPro("activationForm", null, "pro/settings");
                }

                $tabs = [
                    [
                        'id' => 'um_settings_general',
                        'title' => __('General', $userMeta->name),
                        'body' => $userMeta->renderPro("generalSettings", [
                            'general' => isset($settings['general']) ? $settings['general'] : $default['general']
                        ], "settings"),
                        'is_active' => true
                    ],
                    [
                        'id' => 'um_settings_login',
                        'title' => __('Login', $userMeta->name),
                        'body' => $userMeta->renderPro("loginSettings", array(
                            'login' => isset($settings['login']) ? $settings['login'] : $default['login']
                        ), "settings")
                    ],
                    [
                        'id' => 'um_settings_registration',
                        'title' => __('Registration', $userMeta->name),
                        'body' => $userMeta->renderPro("registrationSettings", array(
                            'registration' => isset($settings['registration']) ? $settings['registration'] : $default['registration']
                        ), "settings")
                    ]
                ];

                if ($isPro) {
                    $tabs = array_merge($tabs, [
                        [
                            'id' => 'um_settings_redirection',
                            'title' => __('Redirection', $userMeta->name),
                            'body' => $userMeta->renderPro("redirectionSettings", array(
                                'redirection' => isset($settings['redirection']) ? $settings['redirection'] : $default['redirection']
                            ), "settings")
                        ],
                        [
                            'id' => 'um_settings_backend_profile',
                            'title' => __('Backend Profile', $userMeta->name),
                            'body' => $userMeta->renderPro("backendProfile", array(
                                'backend_profile' => isset($settings['backend_profile']) ? $settings['backend_profile'] : $default['backend_profile'],
                                'forms' => $forms,
                                'fields' => $fields
                            ), "settings")
                        ]
                    ]);
                }

                $tabs = array_merge($tabs, [
                    [
                        'id' => 'um_settings_text',
                        'title' => __('Text', $userMeta->name),
                        'body' => $userMeta->renderPro('textSettings', [
                            'text' => isset($settings['text']) ? $settings['text'] : []
                        ], 'settings', true)
                    ]
                ]);
                ?>
                
				<form id="um_settings_form" action="" method="post"
					onsubmit="umUpdateSettings(this); return false;">

					<div class="panel panel-default">
						<div class="panel-body">
                    		<?= UserMeta\tabs($tabs)?>
                    	</div>
					</div>
										
                    <?php
                    echo $userMeta->nonceField();
                    echo $userMeta->createInput("save_field", "submit", array(
                        "value" => __("Save Changes", $userMeta->name),
                        "id" => "update_settings",
                        "class" => "button-primary",
                        "enclose" => "p"
                    ));
                    ?>
                </form>
			</div>

			<div id="um_admin_sidebar">
                <?php
                $panelArgs = [
                    'panel_class' => 'panel-default'
                ];
                echo UserMeta\panel(__('Get started', $userMeta->name), $userMeta->boxHowToUse(), $panelArgs);
                echo UserMeta\panel('Shortcodes', $userMeta->boxShortcodesDocs(), $panelArgs);
                echo UserMeta\panel(__('Rate Us', $userMeta->name), $userMeta->boxRate(), $panelArgs);
                ?>
            </div>
		</div>
	</div>
</div>