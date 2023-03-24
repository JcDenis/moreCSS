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

try {
    if (!dcCore::app()->newVersion(
        basename(__DIR__),
        dcCore::app()->plugins->moduleInfo(basename(__DIR__), 'version')
    )) {
        return null;
    }

    $s = dcCore::app()->blog->settings->get('themes');
    $s->put('morecss_active', true, 'boolean', 'Enable additionnal CSS for the active theme', false, true);
    $s->put('morecss', '', 'string', 'Additionnal CSS for the active theme', false, true);
    $s->put('morecss_min', '', 'string', 'Minified addtionnal CSS for the active theme', false, true);

    return true;
} catch (Exception $e) {
    dcCore::app()->error->add($e->getMessage());
}

return false;
