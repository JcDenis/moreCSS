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
if (!defined('DC_CONTEXT_ADMIN')) { exit; }
 
if (version_compare(DC_VERSION,'2.6','<')) {
	dcCore::app()->error->add(sprintf(__('Dotclear version 2.6 minimum is required. moreCSS is deactivated.')));
	dcCore::app()->plugins->deactivateModule('moreCSS');
	return false;
}

$new_version = dcCore::app()->plugins->moduleInfo('moreCSS','version');
 
$current_version = dcCore::app()->getVersion('moreCSS');
 
if (version_compare($current_version,$new_version,'>=')) {
	return;
}

$s =& dcCore::app()->blog->settings->themes;
$s->put('morecss','','string','Additionnal css for the active theme',true,true);
$s->put('morecss_min','','string','Minified addtionnal css for the active theme',true,true);

dcCore::app()->setVersion('moreCSS',$new_version);
return true;