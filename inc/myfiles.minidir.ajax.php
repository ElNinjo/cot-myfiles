
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
//$shownewfolder = cot_import('newfolder','G','TXT');
$userid = cot_import('userid','G','TXT');
$goback = cot_import('goback','G','INT');

$fldrselect=myfiles_getMiniDir_ajax_html($folderid,$userid,$goback);

echo $fldrselect;
return;	

?>
