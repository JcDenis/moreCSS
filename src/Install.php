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
use Dotclear\Core\Process;
use Exception;

class Install extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::INSTALL));
    }

    public static function process(): bool
    {
        if (!self::status() || is_null(dcCore::app()->blog)) {
            return false;
        }

        try {
            $s = dcCore::app()->blog->settings->get('themes');
            $s->put('morecss_active', true, 'boolean', 'Enable additionnal CSS for the active theme', false, true);
            $s->put('morecss', '', 'string', 'Additionnal CSS for the active theme', false, true);
            $s->put('morecss_min', '', 'string', 'Minified addtionnal CSS for the active theme', false, true);

            return true;
        } catch (Exception $e) {
            dcCore::app()->error->add($e->getMessage());
        }

        return true;
    }
}
