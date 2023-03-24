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
if (!defined('DC_CONTEXT_ADMIN')) {
    return null;
}

dcCore::app()->menu[dcAdmin::MENU_BLOG]->addItem(
    __('Style sheet'),
    dcCore::app()->adminurl->get('admin.plugin.' . basename(__DIR__)),
    [dcPage::getPF(basename(__DIR__) . '/icon.png')],
    preg_match('/' . preg_quote(dcCore::app()->adminurl->get('admin.plugin.' . basename(__DIR__))) . '(&.*)?$/', $_SERVER['REQUEST_URI']),
    dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([dcAuth::PERMISSION_CONTENT_ADMIN]), dcCore::app()->blog->id)
);

dcCore::app()->addBehavior('adminDashboardFavoritesV2', function (dcFavorites $favs) {
    $favs->register(basename(__DIR__), [
        'title'       => __('Style sheet'),
        'url'         => dcCore::app()->adminurl->get('admin.plugin.' . basename(__DIR__)),
        'small-icon'  => [dcPage::getPF(basename(__DIR__) . '/icon.png')],
        'large-icon'  => [dcPage::getPF(basename(__DIR__) . '/icon-big.png')],
        'permissions' => dcCore::app()->auth->makePermissions([dcAuth::PERMISSION_CONTENT_ADMIN]),
    ]);
});
