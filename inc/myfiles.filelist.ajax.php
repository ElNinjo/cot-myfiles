
<?php
/* ====================
 Cotonti - Website engine
 * Myfiles plugin
 * Author: 2basix.nl (ez)
 * BSD License
 ===================================*/

defined('COT_CODE') or die('Wrong URL');

cot_block($usr['auth_read']);

/* ===============================
		AJAX section
================================== */	
require $cfg['plugins_dir']."/myfiles/inc/myfiles.inc.php";

$folderid = cot_import('folderid','G','TXT');
$userid = cot_import('userid','G','TXT');
$mode = cot_import('mode','G','TXT');
$thumbnails = cot_import('thumbnails','G','TXT');

if ($mode=="") {
	$mode="compact";
}

$filelist=myfiles_getFilelist_ajax_html($folderid,$userid,$mode,$thumbnails);
echo $filelist;
return;	

?>
