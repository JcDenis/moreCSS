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

dcCore::app()->url->register(
    basename(__DIR__),
    'morecss.css',
    '^morecss\.css(.*?)$',
    function ($args) {
        header('Content-Type: text/css; charset=UTF-8');

        echo "/* CSS for plugin moreCss */ \n";
        echo (string) base64_decode((string) dcCore::app()->blog->settings->get('themes')->get('morecss_min'));

        exit;
    }
);
