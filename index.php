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
if (!defined('DC_CONTEXT_ADMIN')) { return; }

$page_title = __('Style sheet');

$config = array();
$s =& dcCore::app()->blog->settings->themes;

if (isset($_POST['file_content'])) {
	try {
	// Save CSS
	$css = base64_encode($_POST['file_content']);
	$s->put('morecss',$css);
	
	// Minify it
	$css_min = preg_replace('` {2,}`', ' ', $_POST['file_content']);
	$css_min = preg_replace('/(\/\*[\s\S]*?\*\/)/', '', $css_min);
	$css_min = preg_replace('/(\t|\r|\n)/', '', $css_min);
	$css_min = str_replace(array(' { ',' {','{ '),'{', $css_min);
	$css_min = str_replace(array(' } ',' }','} '),'}', $css_min);
	$css_min = str_replace(array(' : ',' :',': '),':', $css_min);
	$css_min = str_replace(array(' ; ',' ;','; '),';', $css_min);
	$css_min = str_replace(array(' , ',' ,',', '),',', $css_min);
	$s->put('morecss_min',base64_encode($css_min));
	
	http::redirect(dcCore::app()->admin->getPageURL().'&config=1');
	} catch (Exception $e) {
		dcCore::app()->error->add($e->getMessage());
	}
}

$css_content = base64_decode($s->morecss);

?>
<html>
<head>
  <title><?php echo $page_title; ?></title>
  <script>
  <?php echo dcPage::jsJson('dotclear.msg.saving_document',__("Saving document...")); ?>
  <?php echo dcPage::jsJson('dotclear.msg.document_saved',__("Document saved")); ?>
  <?php echo dcPage::jsJson('dotclear.msg.error_occurred',__("An error occurred:")); ?>
  </script>
	<?php echo dcPage::jsConfirmClose('file-form'); ?>
  <script src="index.php?pf=moreCSS/script.js"></script>
</head>
<body>
<?php
	echo dcPage::breadcrumb(
		array(
			html::escapeHTML(dcCore::app()->blog->name) => '',
			'<span class="page-title">'.$page_title.'</span>' => ''
		));

?>

<?php
echo
'<form action="'.dcCore::app()->admin->getPageURL().'" id="file-form" method="post">'.

'<div>'.
	'<p><label for="file_content">'.__('Style sheet:').'</label></p>'.
	'<p>'.form::textarea('file_content',60,20,html::escapeHTML($css_content),'maximal','').'</p>'.
'</div><p>'.form::hidden('p','moreCSS').
	dcCore::app()->formNonce().
	'<input type="submit" name="write" value="'.__('Save').' (s)" accesskey="s" /></p>
</form>';

?>

</body>
</html>
