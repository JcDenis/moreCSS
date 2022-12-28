<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of moreCSS a plugin for Dotclear 2.
# 
# Copyright (c) 2011-2018 Osku and contributors
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

dcCore::app()->menu[dcAdmin::MENU_BLOG]->addItem(
    __('Style sheet'),
    dcCore::app()->adminurl->get('admin.plugin.moreCSS'),
    [dcPage::getPF('moreCSS/icon.png')],
    preg_match('/' . preg_quote(dcCore::app()->adminurl->get('admin.plugin.moreCSS')) . '(&.*)?$/', $_SERVER['REQUEST_URI']),
    dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([dcAuth::PERMISSION_CONTENT_ADMIN]), dcCore::app()->blog->id)
);

/* Register favorite */
dcCore::app()->addBehavior('adminDashboardFavoritesV2', function (dcFavorites $favs) {
    $favs->register('moreCSS', [
        'title'       => __('Style sheet'),
        'url'         => dcCore::app()->adminurl->get('admin.plugin.moreCSS'),
        'small-icon'  => [dcPage::getPF('moreCSS/icon.png')],
        'large-icon'  => [dcPage::getPF('moreCSS/icon-big.png')],
        'permissions' => dcCore::app()->auth->makePermissions([dcAuth::PERMISSION_CONTENT_ADMIN]),
    ]);
});
