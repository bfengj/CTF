<?php
namespace UserMeta;

/**
 * Check if a provided|current user has admin access
 *
 * @since 1.4
 * @uses user-meta-site
 * @param int $userID
 * @return boolean
 */
function isAdmin($userID = 0)
{
    if ($userID)
        return user_can($userID, 'administrator');

    return current_user_can('administrator');
}

/**
 * Determing user_id based on user_login or user_email from provided array.
 *
 * @since 1.4
 * @param array $userData
 * @return int user_id
 */
function userIDfromUsernameOrEmail(array $userData)
{
    $userID = null;
    if (! empty($userData['user_login']) && empty($userData['user_email'])) {
        $userID = username_exists(trim($userData['user_login']));
        if (! $userID) {
            $userID = email_exists(trim($userData['user_login']));
        }
    } elseif (! empty($userData['user_login'])) {
        $userID = username_exists(trim($userData['user_login']));
    } elseif (! empty($userData['user_email'])) {
        $userID = email_exists(trim($userData['user_email']));
    }

    return $userID;
}

/**
 * Add user_meta_admin capability to administrator role
 *
 * @uses PreloadsController::userMetaActivation(), VersionUpdateController::upgradeTo_1_4()
 *      
 * @todo add more capabilites
 * @since 1.4
 */
function includeCapabilities()
{
    $role = get_role('administrator');
    if (! $role->has_cap('user_meta_admin'))
        $role->add_cap('user_meta_admin');
}

/**
 * Check if the current use has user_meta_admin capability
 *
 * @since 1.4
 * @return boolean
 */
function isUserMetaAdmin()
{
    return current_user_can('user_meta_admin');
}

