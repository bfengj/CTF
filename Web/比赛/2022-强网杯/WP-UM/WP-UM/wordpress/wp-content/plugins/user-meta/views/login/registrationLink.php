<?php
global $userMeta;
// Expected: $config

$link = $userMeta->getMsg('registration_link', wp_registration_url());

$linkClass = ! empty($config['registration_link_class']) ? $config['registration_link_class'] : '';
$linkClass = ! empty($linkClass) ? "class=\"$linkClass\"" : '';

return "<p $linkClass>$link</p>";