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

use dcAdmin;
use dcCore;
use dcFavorites;
use dcNsProcess;
use dcPage;

class Backend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_CONTEXT_ADMIN');

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init || is_null(dcCore::app()->auth) || is_null(dcCore::app()->blog) || is_null(dcCore::app()->adminurl)) {
            return false;
        }

        dcCore::app()->menu[dcAdmin::MENU_BLOG]->addItem(
            My::name(),
            dcCore::app()->adminurl->get('admin.plugin.' . My::id()),
            [dcPage::getPF(My::id() . '/icon.png')],
            preg_match('/' . preg_quote((string) dcCore::app()->adminurl->get('admin.plugin.' . My::id())) . '(&.*)?$/', $_SERVER['REQUEST_URI']),
            dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([dcCore::app()->auth::PERMISSION_CONTENT_ADMIN]), dcCore::app()->blog->id)
        );

        dcCore::app()->addBehavior('adminDashboardFavoritesV2', function (dcFavorites $favs): void {
            if (is_null(dcCore::app()->auth) || is_null(dcCore::app()->adminurl)) {
                return;
            }
            $favs->register(My::id(), [
                'title'       => My::name(),
                'url'         => dcCore::app()->adminurl->get('admin.plugin.' . My::id()),
                'small-icon'  => [dcPage::getPF(My::id() . '/icon.png')],
                'large-icon'  => [dcPage::getPF(My::id() . '/icon-big.png')],
                'permissions' => dcCore::app()->auth->makePermissions([dcCore::app()->auth::PERMISSION_CONTENT_ADMIN]),
            ]);
        });

        return true;
    }
}
