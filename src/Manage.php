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
use Dotclear\Helper\Html\Form\{
    Checkbox,
    Form,
    Hidden,
    Label,
    Para,
    Submit,
    Textarea
};
use Dotclear\Helper\Html\Html;
use Exception;

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
            Html::escapeHTML(dcCore::app()->blog->name) => '',
            My::name()                                  => '',
        ]) .
        dcPage::notices() .

        (new Form('file-form'))->method('post')->action(dcCore::app()->admin->getPageURL())->fields([
            (new Para())->items([
                (new Label(__('Style sheet:')))->for('morecss'),
                (new Textarea('morecss', Html::escapeHTML((string) base64_decode((string) $s->get('morecss')))))->class('maximal')->cols(72)->rows(25),
            ]),
            (new Para())->items([
                (new Checkbox('morecss_active'))->value(1),
                (new Label(__('Enable additionnal CSS for the active theme'), Label::OUTSIDE_LABEL_AFTER))->for('morecss_active')->class('classic'),
            ]),
            (new Para())->items([
                dcCore::app()->formNonce(false),
                (new Hidden('p', 'moreCSS')),
                (new Submit(['write']))->value(__('Save') . ' (s)')->accesskey('s'),
            ]),
        ])->render();

        if (dcCore::app()->auth->user_prefs->get('interface')->get('colorsyntax')) {
            echo
            dcPage::jsJson('theme_editor_mode', ['mode' => 'css']) .
            dcPage::jsModuleLoad('themeEditor/js/mode.js') .
            dcPage::jsRunCodeMirror('editor', 'morecss', 'dotclear', dcCore::app()->auth->user_prefs->get('interface')->get('colorsyntax_theme'));
        }

        dcPage::closeModule();
    }
}
