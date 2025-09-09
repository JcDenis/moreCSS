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
    '2.6',
    [
        'requires'    => [['core', '2.36']],
        'permissions' => 'My',
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . $this->id . '/issues',
        'details'     => 'https://github.com/JcDenis/' . $this->id . '/',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/' . $this->id . '/master/dcstore.xml',
        'date'        => '2025-03-02T17:19:09+00:00',
    ]
);
