# moreCSS

Notes for AI assistants working on the Dotclear plugin moreCSS.

**Read first** the shared conventions of the author's Dotclear modules:
https://raw.githubusercontent.com/JcDenis/gh2da/master/CONVENTIONS.md
(if a web tool refuses the address, use `curl -s` on it from the shell).
The rules below take precedence for this module.

## Module

It helps admin to add more CSS rules to current theme without editing it.

Admin page. Public side: behaviors.

Required Dotclear and PHP versions: see `_define.php`.

## Structure

* `src/Backend.php`: admin side: menu entry, favorites, behaviors registration.
* `src/Frontend.php`: public side: behaviors, template tags and URL handlers registration.
* `src/Install.php`: install and update, default settings.
* `src/Manage.php`: admin page of the module.
* `src/My.php`: module helpers (`My::id()`, settings, permissions).
* `src/Prepend.php`: loaded on every request: autoload, URL handlers, permission types.
* `src/Uninstall.php`: cleaners for the plugin Uninstaller.
* Behaviors used: `adminDashboardFavoritesV2`, `publicHeadContent`.

## Pitfalls

* None recorded yet: add here the traps met while working on the module.

## Testing

* Check the admin page, in English and French.
* CI browses the common admin and public pages; extra pages can be listed in `.github/dotclear-test.txt`.
