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

dcPage::check(dcCore::app()->auth->makePermissions([
    dcAuth::PERMISSION_CONTENT_ADMIN,
]));

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
        $s->put('morecss_min', base64_encode($css_min));

        dcAdminNotices::addSuccessNotice(
            __('Configuration successfully updated.')
        );
        dcCore::app()->adminurl->redirect(
            'admin.plugin.' . basename(__DIR__)
        );
    } catch (Exception $e) {
        dcCore::app()->error->add($e->getMessage());
    }
}

echo '
<html><head><title>' . __('Style sheet') . '</title>';
if (dcCore::app()->auth->user_prefs->interface->colorsyntax) {
    echo
    dcPage::jsJson('dotclear_colorsyntax', ['colorsyntax' => dcCore::app()->auth->user_prefs->interface->colorsyntax]) .
    dcPage::jsLoadCodeMirror(dcCore::app()->auth->user_prefs->interface->colorsyntax_theme);
}
echo '
</head><body>' .
dcPage::breadcrumb([
    html::escapeHTML(dcCore::app()->blog->name) => '',
    __('Style sheet')                           => '',
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

if (dcCore::app()->auth->user_prefs->interface->colorsyntax) {
    echo
    dcPage::jsJson('theme_editor_mode', ['mode' => 'css']) .
    dcPage::jsModuleLoad('themeEditor/js/mode.js') .
    dcPage::jsRunCodeMirror('editor', 'morecss', 'dotclear', dcCore::app()->auth->user_prefs->interface->colorsyntax_theme);
}

echo '
</body>
</html>';
