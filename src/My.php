<?php

declare(strict_types=1);

namespace Dotclear\Plugin\moreCSS;

use Dotclear\App;
use Dotclear\Module\MyPlugin;

/**
 * @brief       moreCSS My helper.
 * @ingroup     moreCSS
 *
 * @author      Osku (author)
 * @author      Jean-Christian Denis (latest)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class My extends MyPlugin
{
    protected static function checkCustomContext(int $context): ?bool
    {
        return match ($context) {
            // Add content admin perm to backend
            self::MANAGE, self::MENU => App::task()->checkContext('BACKEND')
                && App::auth()->check(App::auth()->makePermissions([
                    App::auth()::PERMISSION_ADMIN,
                    App::auth()::PERMISSION_CONTENT_ADMIN,
                ]), App::blog()->id()),

            default => null,
        };
    }
}
