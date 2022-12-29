<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of moreCSS a plugin for Dotclear 2.
#
# Copyright (c) 2011 2018 Osku and contributors
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------
if (!defined('DC_RC_PATH')) { return; }

dcCore::app()->addBehavior('publicHeadContent',array('moreCSSpublicBehaviors','stylesheet'));

class moreCSSpublicBehaviors
{
	public static function stylesheet()
	{
		$css = base64_decode(dcCore::app()->blog->settings->themes->morecss_min);
		
		if ($css != '') {
			echo
			"\n<!-- Additionnal CSS --> \n".
			'<style type="text/css">'."\n". $css ."\n".'</style>'."\n";
		}
	}
}
