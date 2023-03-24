<?php
/**
 * @brief moreCSS, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugin
 *
 * @author Osku and contributors
 *
 * @copyright Jean-Christian Denis
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('DC_RC_PATH')) {
    return null;
}

if (!dcCore::app()->blog->settings->get('themes')->get('morecss_active')) {
    return null;
}

dcCore::app()->addBehavior('publicHeadContent', function () {
    $css = (string) base64_decode((string) dcCore::app()->blog->settings->get('themes')->get('morecss_min'));
    if (!empty($css)) {
        echo dcUtils::cssLoad(
            dcCore::app()->blog->url . dcCore::app()->url->getURLFor(basename(__DIR__)),
            'screen',
            md5($css) //no cache on content change
        );
    }
});
