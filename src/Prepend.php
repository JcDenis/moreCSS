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
declare(strict_types=1);

namespace Dotclear\Plugin\moreCSS;

use dcCore;
use dcNsProcess;

class Prepend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_RC_PATH');

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        dcCore::app()->url->register(
            My::id(),
            'morecss.css',
            '^morecss\.css(.*?)$',
            function (string $args): void {
                header('Content-Type: text/css; charset=UTF-8');

                echo "/* CSS for plugin moreCss */ \n";
                echo (string) base64_decode((string) dcCore::app()->blog->settings->get('themes')->get('morecss_min'));

                exit;
            }
        );

        return true;
    }
}
