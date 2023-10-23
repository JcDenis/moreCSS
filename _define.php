<?php

/**
 * @file
 * @brief       The plugin moreCSS definition
 * @ingroup     moreCSS
 *
 * @defgroup    moreCSS Plugin moreCSS.
 *
 * Another CSS stylesheet for the active theme.
 *
 * @author      Osku (author)
 * @author      Jean-Christian Denis (latest)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
$this->registerModule(
    'Style sheet',
    'Another CSS stylesheet for the active theme',
    'Osku and contributors',
    '2.5.2',
    [
        'requires'    => [['core', '2.28']],
        'permissions' => 'My',
        'type'        => 'plugin',
        'support'     => 'https://git.dotclear.watch/JcDenis/' . basename(__DIR__) . '/issues',
        'details'     => 'https://git.dotclear.watch/JcDenis/' . basename(__DIR__) . '/src/branch/master/README.md',
        'repository'  => 'https://git.dotclear.watch/JcDenis/' . basename(__DIR__) . '/raw/branch/master/dcstore.xml',
    ]
);
