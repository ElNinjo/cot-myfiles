
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
		Screen section
================================== */	
require $cfg['plugins_dir']."/myfiles/inc/myfiles.inc.php";

$folderid = cot_import('folderid','G','TXT');
$userid = cot_import('userid','G','TXT');
$shownewfolder = cot_import('newfolder','G','TXT');
$hidebuttons = cot_import('hidebuttons','G','TXT');
$foldermode = cot_import('foldermode','G','TXT');

if($hidebuttons!='' && $hidebuttons!='0' ) {
	$hidebuttons='1';
}
$fldrselect=myfiles_getMiniDir_html($folderid,$userid,$foldermode,$shownewfolder,$hidebuttons);

echo $fldrselect;
return;	

?>
