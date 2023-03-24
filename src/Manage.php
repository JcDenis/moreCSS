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

use dcAuth;
use dcCore;
use dcNsProcess;
use dcPage;
use Exception;
use html;
use form;

class Manage extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_CONTEXT_ADMIN')
            && dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([
                dcAuth::PERMISSION_CONTENT_ADMIN,
            ]), dcCore::app()->blog->id);

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        $s = dcCore::app()->blog->settings->get('themes');

        if (isset($_POST['morecss'])) {
            try {
                // Save CSS
                $css = base64_encode($_POST['morecss']);
                $s->put('morecss', $css);
                $s->put('morecss_active', !empty($_POST['morecss_active']));

                // Minify it
                $css_min = preg_replace('` {2,}`', ' ', $_POST['morecss']);
                $css_min = preg_replace('/(\/\*[\s\S]*?\*\/)/', '', $css_min);
                $css_min = preg_replace('/(\t|\r|\n)/', '', $css_min);
                $css_min = str_replace([' { ', ' {', '{ '], '{', $css_min);
                $css_min = str_replace([' } ', ' }', '} '], '}', $css_min);
                $css_min = str_replace([' : ', ' :', ': '], ':', $css_min);
                $css_min = str_replace([' ; ', ' ;', '; '], ';', $css_min);
                $css_min = str_replace([' , ', ' ,', ', '], ',', $css_min);
                $s->put('morecss_min', is_string($css_min) ? base64_encode($css_min) : '');

                dcPage::addSuccessNotice(
                    __('Configuration successfully updated.')
                );
                dcCore::app()->adminurl->redirect(
                    'admin.plugin.' . My::id()
                );
            } catch (Exception $e) {
                dcCore::app()->error->add($e->getMessage());
            }
        }

        return true;
    }

    public static function render(): void
    {
        if (!static::$init) {
            return;
        }

        $s = dcCore::app()->blog->settings->get('themes');

        dcPage::openModule(
            My::name(),
            (
                dcCore::app()->auth->user_prefs->get('interface')->get('colorsyntax') ?
                dcPage::jsJson('dotclear_colorsyntax', ['colorsyntax' => dcCore::app()->auth->user_prefs->get('interface')->get('colorsyntax')]) .
                dcPage::jsLoadCodeMirror(dcCore::app()->auth->user_prefs->get('interface')->get('colorsyntax_theme'))
                : ''
            )
        );

        echo
        dcPage::breadcrumb([
            html::escapeHTML(dcCore::app()->blog->name) => '',
            My::name()                                  => '',
        ]) .
        dcPage::notices() . '

        <form action="' . dcCore::app()->admin->getPageURL() . '" id="file-form" method="post">

        <div><h3><label for="morecss">' . __('Style sheet:') . '</strong></label></h3>
        <p>' . form::textarea('morecss', 72, 25, [
            'default' => html::escapeHTML((string) base64_decode((string) $s->get('morecss'))),
            'class'   => 'maximal',
        ]) . '</p>

        <p><label class="classic" for="morecss_active">' .
        form::checkbox('morecss_active', 1, $s->get('morecss_active')) . ' ' .
        __('Enable additionnal CSS for the active theme') .
        '</label></p>

        <p>' .
        form::hidden('p', 'moreCSS') .
        dcCore::app()->formNonce() . '
        <input type="submit" name="write" value="' . __('Save') . ' (s)" accesskey="s" /></p>
        </form>';

        if (dcCore::app()->auth->user_prefs->get('interface')->get('colorsyntax')) {
            echo
            dcPage::jsJson('theme_editor_mode', ['mode' => 'css']) .
            dcPage::jsModuleLoad('themeEditor/js/mode.js') .
            dcPage::jsRunCodeMirror('editor', 'morecss', 'dotclear', dcCore::app()->auth->user_prefs->get('interface')->get('colorsyntax_theme'));
        }

        dcPage::closeModule();
    }
}
