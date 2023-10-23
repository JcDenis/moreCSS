<?php

declare(strict_types=1);

namespace Dotclear\Plugin\moreCSS;

use Dotclear\App;
use Dotclear\Core\Process;
use Exception;

/**
 * @brief       moreCSS install class.
 * @ingroup     moreCSS
 *
 * @author      Osku (author)
 * @author      Jean-Christian Denis (latest)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Install extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::INSTALL));
    }

    public static function process(): bool
    {
        if (!self::status() || !App::blog()->isDefined()) {
            return false;
        }

        try {
            $s = App::blog()->settings()->get('themes');
            $s->put('morecss_active', true, 'boolean', 'Enable additionnal CSS for the active theme', false, true);
            $s->put('morecss', '', 'string', 'Additionnal CSS for the active theme', false, true);
            $s->put('morecss_min', '', 'string', 'Minified addtionnal CSS for the active theme', false, true);

            return true;
        } catch (Exception $e) {
            App::error()->add($e->getMessage());
        }

        return true;
    }
}
