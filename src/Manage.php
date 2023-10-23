<?php

declare(strict_types=1);

namespace Dotclear\Plugin\moreCSS;

use Dotclear\App;
use Dotclear\Core\Process;
use Dotclear\Core\Backend\{
    Notices,
    Page
};
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

/**
 * @brief       moreCSS manage class.
 * @ingroup     moreCSS
 *
 * @author      Osku (author)
 * @author      Jean-Christian Denis (latest)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Manage extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::MANAGE));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        $s = App::blog()->settings()->get('themes');

        if (isset($_POST['morecss'])) {
            try {
                // Save CSS
                $css = base64_encode($_POST['morecss']);
                $s->put('morecss', $css);
                $s->put('morecss_active', !empty($_POST['morecss_active']));

                // Minify it
                $css_min = (string) preg_replace('` {2,}`', ' ', $_POST['morecss']);
                $css_min = (string) preg_replace('/(\/\*[\s\S]*?\*\/)/', '', $css_min);
                $css_min = (string) preg_replace('/(\t|\r|\n)/', '', $css_min);
                $css_min = str_replace([' { ', ' {', '{ '], '{', $css_min);
                $css_min = str_replace([' } ', ' }', '} '], '}', $css_min);
                $css_min = str_replace([' : ', ' :', ': '], ':', $css_min);
                $css_min = str_replace([' ; ', ' ;', '; '], ';', $css_min);
                $css_min = str_replace([' , ', ' ,', ', '], ',', $css_min);
                $s->put('morecss_min', base64_encode($css_min));

                Notices::addSuccessNotice(
                    __('Configuration successfully updated.')
                );
                My::redirect();
            } catch (Exception $e) {
                App::error()->add($e->getMessage());
            }
        }

        return true;
    }

    public static function render(): void
    {
        if (!self::status()) {
            return;
        }

        $s = App::blog()->settings()->get('themes');

        Page::openModule(
            My::name(),
            (
                App::auth()->prefs()->get('interface')->get('colorsyntax') ?
                Page::jsJson('dotclear_colorsyntax', ['colorsyntax' => App::auth()->prefs()->get('interface')->get('colorsyntax')]) .
                Page::jsLoadCodeMirror(App::auth()->prefs()->get('interface')->get('colorsyntax_theme'))
                : ''
            )
        );

        echo
        Page::breadcrumb([
            Html::escapeHTML(App::blog()->name()) => '',
            My::name()                            => '',
        ]) .
        Notices::getNotices() .

        (new Form('file-form'))->method('post')->action(App::backend()->getPageURL())->fields([
            (new Para())->items([
                (new Label(__('Style sheet:')))->for('morecss'),
                (new Textarea('morecss', Html::escapeHTML((string) base64_decode((string) $s->get('morecss')))))->class('maximal')->cols(72)->rows(25),
            ]),
            (new Para())->items([
                (new Checkbox('morecss_active', (bool) $s->get('morecss_active')))->value(1),
                (new Label(__('Enable additionnal CSS for the active theme'), Label::OUTSIDE_LABEL_AFTER))->for('morecss_active')->class('classic'),
            ]),
            (new Para())->items([
                App::nonce()->formNonce(),
                (new Hidden('p', 'moreCSS')),
                (new Submit(['write']))->value(__('Save') . ' (s)')->accesskey('s'),
            ]),
        ])->render();

        if (App::auth()->prefs()->get('interface')->get('colorsyntax')) {
            echo
            Page::jsJson('theme_editor_mode', ['mode' => 'css']) .
            Page::jsLoad(App::blog()->getPF('themeEditor/js/mode.js')) .
            Page::jsRunCodeMirror('editor', 'morecss', 'dotclear', App::auth()->prefs()->get('interface')->get('colorsyntax_theme'));
        }

        Page::closeModule();
    }
}
