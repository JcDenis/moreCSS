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
use dcUtils;

class Frontend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_RC_PATH');

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init || !dcCore::app()->blog->settings->get('themes')->get('morecss_active')) {
            return false;
        }

        dcCore::app()->addBehavior('publicHeadContent', function (): void {
            $css = (string) base64_decode((string) dcCore::app()->blog->settings->get('themes')->get('morecss_min'));
            if (!empty($css)) {
                echo dcUtils::cssLoad(
                    dcCore::app()->blog->url . dcCore::app()->url->getURLFor(My::id()),
                    'screen',
                    md5($css) //no cache on content change
                );
            }
        });

        return true;
    }
}
