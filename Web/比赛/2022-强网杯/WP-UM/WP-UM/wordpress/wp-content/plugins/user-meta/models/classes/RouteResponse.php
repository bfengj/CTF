<?php
namespace UserMeta;

/**
 * Handle ajax/post response
 * Provide nonce validation utils
 *
 * Handle ajax response from user_meta_ajax action
 * E.g: action=user_meta_ajax&_target=toggle_addon
 * Hadle by action : user_meta_ajax_%target%
 *
 * Although we have implemented optional $nonceText
 * but using of $nonceText is not required because if nonce is generated based on
 * target name, changing target name on browser means the user have to generate a nonce manually
 *
 * @author Khaled Hossain
 * @since 1.4
 */
final class RouteResponse
{

    private $noncePrefix = 'um_nonce_';

    /**
     * Initialize hooks in controller
     *
     * @uses AdminAjaxController::__construct()
     */
    public function initHooks()
    {
        add_action('wp_ajax_user_meta_ajax', [
            $this,
            'ajaxResponse'
        ]);
    }

    /**
     * Handle ajax response from user_meta_ajax action
     * E.g: action=user_meta_ajax&_target=toggle_addon
     * Hadle by action : user_meta_ajax_%target%
     */
    public function ajaxResponse()
    {
        $target = isset($_REQUEST['_target']) ? sanitize_text_field($_REQUEST['_target']) : null;
        if ($target)
            do_action('user_meta_ajax_' . $target);
        else
            echo 'not found!';

        die();
    }

    /**
     * action parameter for wp_verify_nonce()
     *
     * @param string $target
     * @return string
     */
    private function nonceAction($target = null)
    {
        if (! $target && isset($_REQUEST['_target']))
            $target = sanitize_text_field($_REQUEST['_target']);
        elseif (! $target && isset($_REQUEST['action']))
        $target = sanitize_text_field($_REQUEST['action']);

        if (! $target)
            die(__('Security check: Empty target', 'user-meta'));

        return $this->noncePrefix . $target;
    }

    /**
     * Creating nonce for ajax request
     *
     * @param string $target
     * @return string
     */
    public function createNonce($target = null)
    {
        return wp_create_nonce($this->nonceAction($target));
    }

    /**
     * Verify nonce for ajax request
     *
     * @param string $nonceText
     *            (not required)
     */
    public function verifyNonce($nonceText = null)
    {
        if (empty($_REQUEST['_wpnonce']))
            die(__('Security check: Empty nonce', 'user-meta'));

        $nonce = sanitize_text_field($_REQUEST['_wpnonce']);
        if (! wp_verify_nonce($nonce, $this->nonceAction($nonceText)))
            die(__('Security check: Nonce missmatch', 'user-meta'));
    }

    /**
     * Verify nonce and admin access
     * If $adminReferer is true, verify admin referer too
     *
     * @param boolean $adminReferer
     * @param string $nonceText
     *            (not required)
     */
    public function verifyAdminNonce($adminReferer = true, $nonceText = null)
    {
        $this->verifyNonce($nonceText);
        if ($adminReferer)
            $this->verifyAdminReferer();

        if (! isUserMetaAdmin())
            die(__('Security check: Admin only', 'user-meta'));
    }

    /**
     * Makes sure that a visitor was referred from another admin page.
     * We can not use check_admin_referer() as the function return true when valid nonce has been provided
     */
    private function verifyAdminReferer()
    {
        $adminurl = strtolower(admin_url());
        $referer = strtolower(wp_get_referer());
        if (! (strpos($referer, $adminurl) === 0))
            die(__('Security check: Invalid admin referer', 'user-meta'));
    }

    /**
     * Prepare request that can pass nonce validation
     *
     * @param string $target
     * @param array $attr
     * @param string $nonceText
     *            (not required)
     * @return array
     */
    public function prepareRequest($target, array $attr = [], $nonceText = null)
    {
        return array_merge([
            'action' => 'user_meta_ajax',
            '_wpnonce' => $this->createNonce($nonceText ? $nonceText : $target),
            '_target' => $target
        ], $attr);
    }
}