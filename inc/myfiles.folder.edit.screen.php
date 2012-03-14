<?php
/* ====================
 Cotonti - Website engine
 * Myfiles plugin
 * Author: 2basix.nl (ez)
 * BSD License
 ===================================*/

defined('COT_CODE') or die('Wrong URL');

if (!$usr['auth_write']) { 
	die($L['myfiles_err_nowrite']);
}

// folder id is on the url
$folderid = cot_import('folderid','G','TXT');
$userid   = cot_import('userid','G','TXT');

// check for correct user!
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

if ($folderid=="-1") {
	// "/" is root folder
	$path = "/";
} else {
	// folderid is anexisting record in the database !
	$curfolder=myfiles_getFolder($folderid,$theuserid);
	if ($curfolder==NULL) {
		die($L['myfiles_err_invalidid']);
	}
}

//=========================================
//==-- Screen section return HTML (complete screen)
//=========================================
	$uploadaction =  'plug.php?r=myfiles&m=folderedit&a=submit&folderid='.$folderid.'&userid='.$theuserid;

	$browser = cot_import('browser','G','TXT');
	$close = cot_import('close','G','TXT');

	// make it an array
	$parentfolder=myfiles_getParentFolder_Path($curfolder['pff_path']);
	if (!empty($browser)) {
		// ""=no newfolder visible NOT needed in folderadd
		$fldrselect=myfiles_getMiniDir_html($folderid,$theuserid,"");
	} else {
		$folderpath=myfiles_getFolderPathText($folderid);
	}	
	
	if ($theuserid!=$usr['id']) {
		//someone else!
		if ($userid=="0") {
			$pstart=myfiles_getusername($userid,$L['myfiles_sitefiles']);
		} else {
			$pstart=myfiles_getusername($theuserid);
		}		
		$pstart="<b>".$pstart."</b>";
	} else {
		$pstart=$L['myfiles_myfiles'];
	}
	
	
	
	require $cfg['plugins_dir']."/myfiles/inc/myfiles.header.php";
	$mskin = $cfg['plugins_dir']."/myfiles/tpl/myfiles.folder.edit.tpl"; 
	$t = new XTemplate($mskin);

	$t-> assign(array(
		"MYFILES_BASEDIR"			=> 	$cfg['plugins_dir']."/myfiles",
		"MYFILES_FOLDERSELECT"		=> 	$fldrselect,
		"FOLDER_ID"					=> 	$folderid,
		"FOLDER_USERID"				=> 	$theuserid,
		"MYFILES_CLOSEONFINISH"		=> 	$close,
		"FOLDER_UPLOAD_ACTION"		=> 	$uploadaction,
		"FOLDER_TITLE"				=> 	$curfolder['pff_title'],
		"FOLDER_DESCR"				=> 	$curfolder['pff_desc'],
		"FOLDER_PUBLIC_Y_CHECKED"	=> 	($curfolder['pff_ispublic']=="1")? 'checked="checked"'	:'',
		"FOLDER_PUBLIC_N_CHECKED"	=> 	($curfolder['pff_ispublic']=="0")? 'checked="checked"'	:'',
		"FOLDER_GALLERY_Y_CHECKED"	=> 	($curfolder['pff_isgallery']=="1")? 'checked="checked"'	:'',
		"FOLDER_GALLERY_N_CHECKED"	=> 	($curfolder['pff_isgallery']=="0")? 'checked="checked"'	:'',
		"FOLDER_PATH"				=>	$folderpath,
		"FOLDER_BROWSER"			=> ($browser!='') ? "1":"0",
		"FOLDER_PATHSTART"			=>  $pstart,
		"FOLDER_ID"					=>	$parentfolder
	));
	
	/* === Hook === */
		$extp = cot_getextplugins('myfiles.folderedit.tags');
		if (is_array($extp))
		{ foreach($extp as $k => $pl) { include_once($cfg['plugins_dir'].'/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
	/* ===== */
	$t->parse("MAIN");
	$t->out("MAIN");
	require $cfg['plugins_dir']."/myfiles/inc/myfiles.footer.php";

?>
