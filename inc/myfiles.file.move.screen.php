<?php
/* ====================
 Cotonti - Website engine
 * Myfiles plugin
 * Author: 2basix.nl (ez)
 * BSD License
 ===================================*/
defined('COT_CODE') or die('Wrong URL.');

// userid is on the url
$userid = cot_import('userid','G','TXT');
if ($userid=='') {
	// hmm this could even be userid = 0  (so guests)
	$userid=$usr['id'];
	if ($userid=='0') {
		die($L['myfiles_err_noaccess']);
	}
} else {
	if ($usr['id']!=$userid && !$usr['isadmin']) {
		// other then your own userid and not an admin
		die($L['myfiles_err_noaccess']);
	}
}	

$folderid = cot_import('folderid','G','TXT');

require $cfg['plugins_dir']."/myfiles/inc/myfiles.header.php";
require $cfg['plugins_dir']."/myfiles/inc/myfiles.inc.php";

$folderselector=myfiles_getMiniDir_html($folderid,$userid,'list','1');

$mskin = $cfg['plugins_dir']."/myfiles/tpl/myfiles.file.movetofolder.tpl"; 
$t = new XTemplate($mskin);

$t-> assign(array(
	"MYFILES_BASEDIR"			=> 	$cfg['plugins_dir']."/myfiles",
	"FOLDER_SELECTOR"			=>	$folderselector
));

/* === Hook === */
	$extp = cot_getextplugins('myfiles.filemovetofolder.tags');
	if (is_array($extp))
	{ foreach($extp as $k => $pl) { include_once($cfg['plugins_dir'].'/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */
$t->parse("MAIN");
$t->out("MAIN");
require $cfg['plugins_dir']."/myfiles/inc/myfiles.footer.php";

	

?>
