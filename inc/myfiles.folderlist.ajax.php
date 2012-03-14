
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
$hidebtns = cot_import('hidebtns','G','TXT');

$dl_html=myfiles_getFolderList_ajax_html($folderid,$userid,$hidebtns);

echo $dl_html;
return;	

?>
