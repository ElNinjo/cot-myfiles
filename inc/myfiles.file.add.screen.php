<?php
/* ====================
 Cotonti - Website engine
 * Myfiles plugin
 * Author: 2basix.nl (ez)
 * BSD License
 ===================================*/

defined('COT_CODE') or die('Wrong URL');

cot_block($usr['auth_write']);

	//=================================
	// userid comes from GET always !!!
	// if its emppty assume the current user
	//=================================
	$userid = cot_import('userid','G','TXT');
	if ($userid!='') {
		// there is a userid on the url, check it first for basic rights
		if ($usr['id']!=$userid & !$usr['isadmin']) {
			// other then your own userid and not an admin
			die($L['myfiles_err_noaccess']);
		}
	}

	include_once $cfg['plugins_dir'].'/myfiles/inc/myfiles.inc.php';
	

	//================================
	//==--      process folder    --==
	//================================
	$folderid = cot_import('folderid','G','TXT');
	// what if no folderid is given ?  (take root)
	if ($folderid=='' || $folderid=='-1') {
		$folderid='0';		// root
	}
	if ($folderid!='0') {
		// valid folder
		$folderrecord=myfiles_getFolder($folderid);
		if ($userid=='') {
			// no userid on url, so take userid from the folderid		
			$userid=$folderrecord['pff_userid'];
		}
	}
	if ($userid=='') {
		// hmm this could even be userid = 0  (so guests)
		$userid=$usr['id'];
		if ($userid=='0') {
			// guests do not have personal files !!!!!!  (Sitefiles ??????? are for guests ????)
			die($L['myfiles_err_noaccess']);
		}
	}
	
	$size_info=explode(',', myfiles_getUploadSizeInfo($userid));
	
	$pstart='';
	$folderpath='';
	
	// addicons windowtype "newpage or update"
	$singleaction = cot_import('singleaction','G','TXT');
	$browser = cot_import('browser','G','TXT');
	if ($browser=='') {
	  $browser='0';
	} else {
		$browser='1';
	}
	
	// determine if a browser should be shown, or just the path (folderid is known)
	if ($browser=='1') {
		// ""=no newfolder visible NOT needed in folderadd
		$fldrselect=myfiles_getMiniDir_html($folderid,$userid ,'');
	} else {
		// process username	
		if ($userid!=$usr['id']) {
			//someone else!
			if ($userid=='0') {
				$pstart=myfiles_getusername($userid,$L['myfiles_sitefiles']);
			} else {
				$pstart=myfiles_getusername($userid);
			}		
			$pstart='<b>'.$pstart.'</b>';
		} else {
			$pstart=$L['myfiles_myfiles'];
		}
		$folderpath=myfiles_getFolderPathText($folderid);
	}	
	
	$add = cot_import('add','G','TXT');
	$addicons='0';
	$addform='';
	$addfield='';
	if ($add!='') {
		if ($add=='updtext') {
			$addform='update';
			$addfield='rpagetext';
		}
		if ($add=='updurl') {
			$addform="update";
			$addfield="rpageurl";
		}
		if ($add=='newtext') {
			$addform="newpage";
			$addfield="newpagetext";
		}
		if ($add=='newurl') {
			$addform="newpage";
			$addfield="newpageurl";
		}
		if ($add=='newtopic') {
			$addform="newtopic";
			$addfield="newmsg";
		}
		if ($add=='newpost') {
			$addform='newpost';
			$addfield='newmsg';
		}
		if ($addform!='') { $addicons='1'; }
	}

	if ($cfg['plugin']['myfiles']['myfiles_bbcode']=='No' && $addicons=='1') {
		// no BBCODE, so do not add icons for bbcode support !
		$addicons=='0';
	}
	
	$uploadaction =  'plug.php?r=myfiles&m=fileupload&a=submit';
	if ($userid!=$usr['id']) {
		$uploadaction .=  '&userid='.$userid;
	}
	
	require_once $cfg['plugins_dir'].'/myfiles/inc/myfiles.header.php';
	$mskin = $cfg['plugins_dir'].'/myfiles/tpl/myfiles.file.add.tpl'; 
	$t = new XTemplate($mskin);

	
	if ($singleaction!='') {
		// Singleaction is meant for just one upload !!!
		$maxupload=1;
	} else {
		$maxupload=(int)$cfg['plugin']['myfiles']['myfiles_maxupload']; 
	}
	
	$t-> assign(array(
		'MYFILES_BASEDIR'			=> $cfg['plugins_dir']."/myfiles",
		'MYFILES_FOLDERSELECT'		=> $fldrselect,
		'MYFILES_ADDFOLDERID'		=> $folderid,
		'FOLDER_BROWSER'			=> $browser,
		'MYFILES_ADDICONS'			=> $addicons,
		'MYFILES_ADDFORM'			=> $addform,
		'MYFILES_ADDFIELD'			=> $addfield,
		'FOLDER_PATH'				=> $folderpath,
		'FOLDER_PATHSTART'			=> $pstart,
		'MYFILES_MAXUPLOAD'			=> $maxupload,
		'MYFILES_QUICKACTION'		=> $singleaction,
		'MYFILES_ALLOWEDEXT'		=> json_encode($myFiles['extensions']),
		'MYFILES_INFO_MAXFILE'		=> $size_info[0],
		'MYFILES_INFO_MAXSTORAGE' 	=> $size_info[1]
	));
	

	// add the small upload slots
	for ( $counter=0; $counter<$maxupload ; $counter++) {
		$t-> assign(array(
			'UPLOAD_ROWID'			=>	(string)$counter
		));
		$t->parse('MAIN.UPLOAD_SMALLSLOT');	
	}

	// add the file form slots  (upload forms)
	for ( $counter=0; $counter<$maxupload ; $counter++) {
		$t-> assign(array(
			'UPLOAD_ROWID'			=>	(string)$counter,
			'UPLOAD_CLASS'			=>	($counter==0)? 'shown':'hidden',
			'UPLOAD_ROWACTION'		=> 	$uploadaction,
			'UPLOAD_ROWMAXFILE'		=>	(string)($maxfile*1024)
		));
		$t->parse("MAIN.UPLOAD_ROW");	
	}
//	cot_print($singleaction);
	
	/* === Hook === */
		$extp = cot_getextplugins('myfiles.upload.tags');
		if (is_array($extp))
		{ foreach($extp as $k => $pl) { include_once($cfg['plugins_dir'].'/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
	/* ===== */
	$t->parse('MAIN');
	$t->out('MAIN');
	//require_once $cfg['plugins_dir']."/myfiles/inc/myfiles.footer.php";

?>
