<?php

declare(strict_types=1);

namespace Dotclear\Plugin\moreCSS;

use Dotclear\App;
use Dotclear\Core\Process;

/**
 * @brief   moreCSS frontend class.
 * @ingroup moreCSS
 *
 * @author      Osku (author)
 * @author      Jean-Christian Denis (latest)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Frontend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::FRONTEND));
    }

    public static function process(): bool
    {
        if (!self::status() || !App::blog()->settings()->get('themes')->get('morecss_active')) {
            return false;
        }

        App::behavior()->addBehavior('publicHeadContent', function (): void {
            $css = (string) base64_decode((string) App::blog()->settings()->get('themes')->get('morecss_min'));
            if (!empty($css)) {
                echo App::plugins()->cssLoad(
                    App::blog()->url() . App::url()->getURLFor(My::id()),
                    'screen',
                    md5($css) //no cache on content change
                );
            }
        });

        return true;
    }
}
