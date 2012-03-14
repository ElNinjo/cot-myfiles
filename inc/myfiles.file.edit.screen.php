
<?php
/* ====================
 Cotonti - Website engine
 * Myfiles plugin
 * Author: 2basix.nl (ez)
 * BSD License
 ===================================*/

defined('COT_CODE') or die('Wrong URL');

//=========================================
//==-- Screen section return HTML (complete screen)
//=========================================
if (!$usr['auth_write']) { 
	//ERROR, 	
	die($L['myfiles_err_nowrite']);
}

// folder id is on the url
$fileid = cot_import('fileid','G','TXT');
$userid=$usr['id'];

if ($usr['isadmin']) {
	// existing file, admin can change everything, NO userid check needed
	$curfile_sql= $db->query("SELECT * FROM $db_pfs WHERE pfs_id='$fileid'");
} else {
	// existing file, first check if userid and fileid matches.. 
	$curfile_sql= $db->query("SELECT * FROM $db_pfs WHERE pfs_userid='$userid' AND pfs_id='$fileid'");
}

$curfile= $curfile_sql->fetch();

if ($curfile_sql->rowCount()==0) { 
	//ERROR, file not found... 		
	die($L['myfiles_err_invalidid']);
}

$uploadaction =  'plug.php?r=myfiles&m=fileedit&a=submit&fileid='.$fileid;
$close = cot_import('close','G','TXT');

$pfs_date = @date($cfg['dateformat'], $curfile['pfs_date'] + $usr['timezone'] * 3600);
$pfs_folderid = $curfile['pfs_folderid'];
$pfs_extension = $curfile['pfs_extension'];
$pfs_desc = htmlspecialchars($curfile['pfs_desc']);
$pfs_size = floor($curfile['pfs_size']/1024);

// location of the file (url) is depending on $curfile['pfs_usrfolder']
if ($curfile['pfs_usrfolder']=='1') {
	// file uses FSM 
	$pfs_url= $cfg['pfs_dir'].$curfile['pfs_userid'].'/'.$curfile['pfs_file'];
	$thumb_url= $cfg['thumbs_dir'].$curfile['pfs_userid'].'/'.$curfile['pfs_file'];
} else {
	// file uses root
	$pfs_url= $cfg['pfs_dir'].$curfile['pfs_file'];
	$thumb_url=$cfg['thumbs_dir'].$curfile['pfs_file'];
}
if (!file_exists($thumb_url)) {
	$thumb_url="";
}

require $cfg['plugins_dir']."/myfiles/inc/myfiles.header.php";
$mskin = $cfg['plugins_dir']."/myfiles/tpl/myfiles.file.edit.tpl"; 
$t = new XTemplate($mskin);

$t-> assign(array(
	"MYFILES_BASEDIR"			=> 	$cfg['plugins_dir']."/myfiles",
	"FILE_ID"					=> 	$fileid,
	"MYFILES_CLOSEONFINISH"		=> 	$close,
	"FILE_UPLOAD_ACTION"		=> 	$uploadaction,
	"FILE_FRIENDLYNAME"			=> 	htmlspecialchars($curfile['pfs_friendlyname']),
	"FILE_DESC"					=> 	$pfs_desc,
	"FILE_SIZE"					=>	$pfs_size,
	"FILE_EXT"					=>	$pfs_extension,
	"FILE_URL"					=>	$pfs_url,
	"FILE_THUMBURL"				=>  $thumb_url,
	"FILE_DATE"					=>	$pfs_date, 
	"FOLDER_ID"					=>	$pfs_folderid
));

/* === Hook === */
	$extp = cot_getextplugins('myfiles.fileedit.tags');
	if (is_array($extp))
	{ foreach($extp as $k => $pl) { include_once($cfg['plugins_dir'].'/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */
$t->parse("MAIN");
$t->out("MAIN");
require $cfg['plugins_dir']."/myfiles/inc/myfiles.footer.php";

?>
