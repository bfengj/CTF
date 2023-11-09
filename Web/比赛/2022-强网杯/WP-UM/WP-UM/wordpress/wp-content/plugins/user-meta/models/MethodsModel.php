<?php
namespace UserMeta;

class MethodsModel
{

    function userUpdateRegisterProcess($actionType, $formName, $rolesForms = null)
    {
        global $userMeta;

        $userMeta->enqueueScripts(array(
            'user-meta',
            'jquery-ui-all',
            'fileuploader',
            'wysiwyg',
            'jquery-ui-datepicker',
            'jquery-ui-slider',
            'timepicker',
            'validationEngine',
            'password_strength',
            'placeholder',
            'multiple-select'
        ));
        $userMeta->runLocalization();

        $actionType = strtolower($actionType);

        if (empty($actionType))
            return $userMeta->showError(__('Please provide a name of action type.', $userMeta->name));

        if (! $userMeta->validActionType($actionType))
            return $userMeta->showError(sprintf(__('Sorry. type="%s" is not valid.', $userMeta->name), $actionType));

        /*
         * if ( ! $userMeta->isPro() ) {
         * if ( ! in_array( $actionType, array('profile','public') ) )
         * return $userMeta->showError( "type='$actionType' is only supported, in pro version. Get " . $userMeta->getProLink( 'User Meta Pro' ), "info", false );
         * }
         */

        $user = wp_get_current_user();
        $userID = (isset($user->ID) ? (int) $user->ID : 0);
        $isLoggedIn = ! empty($userID);

        if ($actionType == 'profile-registration')
            $actionType = $isLoggedIn ? 'profile' : 'registration';

        // Checking Permission
        if ($actionType == 'profile') {
            if (! $isLoggedIn) {
                $msg = $userMeta->getMsg('profile_required_loggedin');
                return empty($msg) ? null : $userMeta->showMessage($msg, 'info');
            }

            if (! empty($_REQUEST['user_id'])) {
                if ($userID != esc_attr($_REQUEST['user_id'])) {
                    if ($user->has_cap('add_users')) {
                        $userID = esc_attr($_REQUEST['user_id']);
                        $user = get_user_by('id', $userID);
                        if (empty($user))
                            return $userMeta->showError(__('No user found!.', $userMeta->name));
                    } else
                        return $userMeta->showError(__("You do not have permission to access user profile.", $userMeta->name));
                }

                /*
                 * if( $user->has_cap( 'add_users' ) ){
                 * $userID = esc_attr( $_REQUEST['user_id'] );
                 * $user = get_user_by('id', $userID);
                 * if( empty($user) )
                 * return $userMeta->showError( __( 'No user found!.', $userMeta->name ) );
                 * }else
                 * return $userMeta->showError( __( "You do not have permission to access user profile.", $userMeta->name ) );
                 */
            }
        } elseif ($actionType == 'registration') {
            if ($isLoggedIn && ! $user->has_cap('add_users'))
                return $userMeta->showMessage(sprintf(__('You have already registered. See your <a href="%s">profile</a>', $userMeta->name), $userMeta->getProfileLink()), 'info');
            elseif (! apply_filters('user_meta_allow_registration', true))
                return $userMeta->showError(__('User registration is currently not allowed.', $userMeta->name));
            // elseif ( ! get_option( 'users_can_register' ) )
        } elseif ($actionType == 'public') {
            if (! empty($_REQUEST['user_id'])) {
                $userID = esc_attr($_REQUEST['user_id']);
                $user = get_user_by('id', $userID);
                if (empty($user))
                    return $userMeta->showError(__('No user found!.', $userMeta->name));
            } else {
                if (! $isLoggedIn) {
                    $msg = $userMeta->getMsg('public_non_lggedin_msg');
                    return empty($msg) ? null : $userMeta->showMessage($msg, 'info');
                }
            }
        }

        if (! empty($rolesForms)) {
            if (is_string($rolesForms))
                $rolesForms = $userMeta->toArray($rolesForms);
            if ($userID && in_array($actionType, array(
                'profile',
                'public'
            ))) {
                $role = $userMeta->getUserRole($userID);
                if (isset($rolesForms[$role])) {
                    $formName = $rolesForms[$role];
                }
            }
        }

        if (empty($formName))
            return $userMeta->showError(__('Please provide a form name.', $userMeta->name));

        $formBuilder = new FormGenerate($formName, $actionType, $userID);

        if (! $formBuilder->isFound())
            return $userMeta->ShowError(sprintf(__('Form "%s" is not found.', $userMeta->name), $formName));

        // $savedValues = in_array( $actionType, array('profile','public') ) ? get_userdata( $userID ) : null;

        $form = $formBuilder->getForm();

        // $userMeta->dump($form);

        /*
         * $form = $userMeta->getFormData( $formName );
         * if ( is_wp_error( $form ) )
         * return $userMeta->ShowError( $form );
         */

        $form['form_class'] = ! empty($form['form_class']) ? $form['form_class'] : '';
        $form['form_class'] = 'um_user_form ' . $form['form_class'];
        if (empty($form['disable_ajax']))
            $form['onsubmit'] = "umInsertUser(this);";

        $output = $userMeta->render('generateForm', array(
            'form' => $form,
            // 'fieldValues' => in_array( $actionType, array('profile','public') ) ? get_userdata( $userID ) : null,
            'actionType' => $actionType,
            'userID' => $userID,
            'methodName' => 'InsertUser'
        ));

        return $output;
    }

    function userLoginProcess($formName = null)
    {
        global $userMeta;

        if (! empty($formName)) {
            $userMeta->enqueueScripts(array(
                'user-meta',
                'jquery-ui-all',
                'fileuploader',
                'wysiwyg',
                'jquery-ui-datepicker',
                'jquery-ui-slider',
                'timepicker',
                'validationEngine',
                'password_strength',
                'placeholder',
                'multiple-select'
            ));
        } else {
            $userMeta->enqueueScripts(array(
                'user-meta',
                'placeholder'
            ));
        }
        $userMeta->runLocalization();
        removeDisabledHooks();

        return (new Login())->loginForm($formName);
    }
}