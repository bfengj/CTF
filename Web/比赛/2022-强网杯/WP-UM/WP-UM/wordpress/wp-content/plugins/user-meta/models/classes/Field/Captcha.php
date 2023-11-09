<?php
namespace UserMeta\Field;

use UserMeta\Html\Html;

/**
 * Handling captcha field.
 * helpers/captcha.php has used for the verification.
 *
 * Support of ReCaptcha v3 @since 1.5
 *
 * Filter hooks:
 * user_meta_recaptcha_language @since 1.3
 * user_meta_recaptcha_js_in_content @since 1.3
 *
 * @author Khaled Hossain
 * @since 1.2
 */
class Captcha extends Base
{

    private $recaptchaApiUrl = 'https://www.google.com/recaptcha/api.js';

    /**
     * ReCaptcha version
     *
     * @var string
     */
    private $captchaVersion;

    private $siteKey;

    /**
     * Captcha library needs to add only once
     *
     * @var bool
     */
    private static $isCaptchaLibLoaded;

    /**
     * Rendering options for captcha
     *
     * @var array
     */
    private $_renderOptions = [];

    protected function isQualified()
    {
        parent::isQualified();
        if (! empty($this->field['registration_only']) && 'registration' != $this->actionType) {
            return $this->isQualified = false;
        }

        return $this->isQualified;
    }

    protected function _configure()
    {
        $this->setCaptchaVersion();
        $this->setCaptchaConfig();
        $this->setCaptchaRenderOptions();
    }

    private function setCaptchaVersion()
    {
        $this->captchaVersion = ! empty($this->field['captcha_version']) ? $this->field['captcha_version'] : 'v2';
    }

    private function setCaptchaConfig()
    {
        // set the site key based on captcha version
        if ($this->captchaVersion == 'v2'){
            $this->siteKey = ! empty($this->field['v2_site_key']) ? $this->field['v2_site_key'] : null;
        }
        else{
            $this->siteKey = ! empty($this->field['v3_site_key']) ? $this->field['v3_site_key'] : null;
        }
        
    }

    private function setCaptchaRenderOptions()
    {
        $this->_renderOptions = [
            'sitekey' => $this->siteKey ?: 'invalid',
            'theme' => ! empty($this->field['captcha_theme']) ? $this->field['captcha_theme'] : 'light',
            'type' => ! empty($this->field['captcha_type']) ? $this->field['captcha_type'] : 'image'
        ];

        /**
         * Use `render_options` for adding extra config to captcha rendering
         *
         * @since 1.2
         */
        if (! empty($this->field['render_options']) && is_array($this->field['render_options'])) {
            $this->_renderOptions = array_merge($this->_renderOptions, $this->field['render_options']);
        }
    }

    /**
     *
     * @todo Use dynamic action_name
     * @todo Use dynamic input id to accomadate multiple captcha
     *      
     */
    private function captchaCodeJsV3()
    {
        /**
         * Add js outside jQuery ready block
         */
        return '<script>
                var umReCaptchaCallback = function() {
                    jQuery(".um_recaptcha").each(function() {
                        grecaptcha.ready(function() {
                            grecaptcha.execute("' . $this->siteKey . '", {action: "action_name"})
                            .then(function(token) {
                               jQuery("#um_recaptcha_token").val(token);
                            });
                        });
                    });
                };
            </script>';
    }

    private function captchaCodeJsV2()
    {
        /**
         * Add js outside jQuery ready block
         */
        return '<script>
                var umReCaptchaCallback = function() {
                    jQuery(".um_recaptcha").each(function() {
                        var options = ' . json_encode($this->_renderOptions) . ';
                        options["theme"] = jQuery(this).attr("data-theme");
                        options["type"] = jQuery(this).attr("data-type");
                        grecaptcha.render(this, options);
                    });
                };
            </script>';
    }

    private function captchaLibJs()
    {
        $query = [
            'onload' => 'umReCaptchaCallback',
            'render' => $this->captchaVersion == 'v3' ? $this->siteKey : 'explicit'
        ];

        $language = ! empty($this->field['captcha_lang']) ? $this->field['captcha_lang'] : null;
        $language = apply_filters('user_meta_recaptcha_language', $language);
        if ($language) {
            $query['hl'] = $language;
        }

        return '<script src="' . $this->recaptchaApiUrl . '?' . http_build_query($query) . '"
            async defer>
            </script>';
    }

    /**
     * Load captcha js only once.
     *
     * @sinsce 1.3
     */
    private function loadCaptchaJs()
    {
        if (self::$isCaptchaLibLoaded)
            return;

        $html = null;
        if ($this->captchaVersion == 'v3') {
            $captchaCode = $this->captchaCodeJsV3();
        } else {
            $captchaCode = $this->captchaCodeJsV2();
        }
        $captchaLib = $this->captchaLibJs();

        /**
         * By default, recaptcha js loaded in footer.
         * Loading on $captchaLib depends on $captchaCode, so we load captchaCode before $captchaLib
         */
        if (apply_filters('user_meta_recaptcha_js_in_content', false)) {
            $html .= $captchaCode;
            $html .= $captchaLib;
        } else {
            \UserMeta\addFooterCode($captchaCode);
            \UserMeta\addFooterCode($captchaLib);
        }
        self::$isCaptchaLibLoaded = true;

        return $html;
    }

    public function renderInput()
    {
        global $userMeta;
        $html = null;

        if ($this->captchaVersion == 'v3') {
            $html .= Html::hidden(null, [
                'name' => 'g-recaptcha-response',
                'id' => 'um_recaptcha_token',
                'class' => 'um_recaptcha'
            ]);
        } else {
            $html .= Html::div(null, [
                'class' => 'um_recaptcha',
                'data-theme' => $this->_renderOptions['theme'],
                'data-type' => $this->_renderOptions['type']
            ]);
        }

        $html .= $this->loadCaptchaJs();

        /**
         * Showing error
         */
        if (! $this->siteKey) {
            $text = __('Please set "Site key" and "Secret key" for the Captcha field', $userMeta->name);
            $html .= Html::tag('span', $text, [
                'style' => 'color:red;'
            ]);
        }

        return $html;
    }
}