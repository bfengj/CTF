<?php
namespace UserMeta;

/**
 * Validate captcha.
 * 
 * @uses FormGenerate->validateCaptcha()
 * @param array $captchaField
 * @return boolean
 */
function isValidCaptcha($captchaField = [])
{
    global $userMeta;
    $captchaVersion = isset($captchaField['captcha_version']) ? $captchaField['captcha_version'] : 'v2';
    $siteKey = ($captchaVersion == 'v2') ? $captchaField['v2_site_key'] : $captchaField['v3_site_key'];
    $secretKey = ($captchaVersion == 'v2') ? $captchaField['v2_secret_key'] : $captchaField['v3_secret_key'];

    /**
     * Was there a reCAPTCHA response?
     */
    if (empty($_POST['g-recaptcha-response'])) {
        echo reloadCaptcha($captchaVersion);
        return false;
    }

    /**
     * If key are not set then no validation
     */
    if (empty($siteKey) || empty($secretKey)){
        return false;
    }

    /**
     *
     * @var string $requestMethodName: CurlPost|Post|SocketPost
     *      Default Post
     */
    $requestMethodName = apply_filters('user_meta_recaptcha_request_method', 'Post');
    $requestMethod = "\\ReCaptcha\\RequestMethod\\$requestMethodName";

    $recaptcha = new \ReCaptcha\ReCaptcha($secretKey, new $requestMethod());
    // $recaptcha = $recaptcha->setExpectedHostname($hostname);

    if ('v3' == $captchaVersion) {
        // $recaptcha = $recaptcha->setExpectedAction($action);
        // $recaptcha = $recaptcha->setScoreThreshold($threshold);
        // $recaptcha = $recaptcha->setChallengeTimeout($timeoutSeconds);
    }

    $resp = $recaptcha->verify($_POST["g-recaptcha-response"], $_SERVER["REMOTE_ADDR"]);
    if ($resp->isSuccess()) {
        return true;
    } else {
        echo reloadCaptcha($captchaVersion);
        return false;
    }
}

/**
 * Reload reCaptcha
 *
 * @uses Login->postLogin()
 * @return string javascript
 */
function reloadCaptcha($captchaVersion = 'v2')
{
    if ('v2' == $captchaVersion) {
        return '<script>if(typeof grecaptcha!="undefined"){grecaptcha.reset();}</script>';
    }
}
