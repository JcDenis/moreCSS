<?php

declare(strict_types=1);

namespace Dotclear\Plugin\moreCSS;

use Dotclear\App;
use Dotclear\Core\Process;

/**
 * @brief   moreCSS prepend class.
 * @ingroup moreCSS
 *
 * @author      Osku (author)
 * @author      Jean-Christian Denis (latest)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Prepend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::PREPEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        App::url()->register(
            My::id(),
            'morecss.css',
            '^morecss\.css(.*?)$',
            function (string $args): void {
                header('Content-Type: text/css; charset=UTF-8');

                echo "/* CSS for plugin moreCss */ \n";
                echo (string) base64_decode((string) App::blog()->settings()->get('themes')->get('morecss_min'));

                exit;
            }
        );

        return true;
    }
}
