<?php
/* ====================
 Cotonti - Website engine
 * Myfiles plugin
 * Author: 2basix.nl (ez)
 * BSD License
 ===================================*/

defined('COT_CODE') or die('Wrong URL');

// write permissions ?
if (!$usr['auth_write']) {
	die($L['myfiles_err_nowrite']);
}

$folderid = cot_import('folderid','G','TXT');
if ($folderid != "" && (int)$folderid<-1) {
	die($L['myfiles_err_invalidid']);
}

// check for correct user!
$userid = cot_import('userid','G','TXT');
$theuserid = "";
if ($userid=="") {
	$theuserid=$usr['id'];
} else {
	$theuserid=$userid;
}
if ($theuserid!=$usr['id'] && !$usr['isadmin']) {
	die($L['myfiles_err_noaccess']);
}

require $cfg['plugins_dir']."/myfiles/inc/myfiles.inc.php";

$browser = cot_import('browser','G','TXT');
$close = cot_import('close','G','TXT');
$uploadaction = 'plug.php?r=myfiles&m=folderadd&a=submit&userid='.$theuserid;

// check if I have rights to even add to this folderid
// -1 == ROOT folder (Myfiles) which does NOT actually exists in the database
if ((int)$folderid>0) {

	// folderid is anexisting record in the database !
	$curfolder=myfiles_getFolder($folderid,$theuserid);
	if ($curfolder==NULL) {
		die($L['myfiles_err_invalidid']);
		return;	
	}
}	

if (!empty($browser)) {
	// ""=no newfolder visible NOT needed in folderadd
	$fldrselect=myfiles_getMiniDir_html($folderid,$theuserid ,"");
} else {
	$folderpath=myfiles_getFolderPathText($folderid);
}	

if ($theuserid!=$usr['id']) {
	//someone else!
	if ($userid=="0") {
		$pstart=myfiles_getusername($userid,$L['myfiles_sitefiles']);
	} else {
		$pstart=myfiles_getusername($theuserid);
		if ($pstart=='Unknown') {
			die($L['myfiles_err_invalidid'].'');
		}
	}		
	$pstart="<b>".$pstart."</b>";
} else {
	$pstart=$L['myfiles_myfiles'];
}

require $cfg['plugins_dir']."/myfiles/inc/myfiles.header.php";
$mskin = $cfg['plugins_dir']."/myfiles/tpl/myfiles.folder.add.tpl"; 
$t = new XTemplate($mskin);

$t-> assign(array(
	"MYFILES_BASEDIR"			=> $cfg['plugins_dir']."/myfiles",
	"MYFILES_FOLDERSELECT"		=> $fldrselect,
	"FOLDER_ID"					=> $folderid,
	"FOLDER_USERID"				=> $theuserid,
	"FOLDER_BROWSER"			=> ($browser!='') ? "1":"0",
	"FOLDER_PATHSTART"			=> $pstart,
	"FOLDER_PATH"				=> $folderpath,
	"MYFILES_CLOSEONFINISH"		=> $close,
	"FOLDER_UPLOAD_ACTION"		=> $uploadaction
));

/* === Hook === */
	$extp = cot_getextplugins('myfiles.foldernew.tags');
	if (is_array($extp))
	{ foreach($extp as $k => $pl) { include_once($cfg['plugins_dir'].'/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */
$t->parse("MAIN");
$t->out("MAIN");
require $cfg['plugins_dir']."/myfiles/inc/myfiles.footer.php";

?>
