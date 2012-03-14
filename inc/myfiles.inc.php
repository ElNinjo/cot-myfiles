<?php
/* ====================
 Cotonti - Website engine
 * Myfiles plugin
 * Author: 2basix.nl (ez)
 * BSD License
 ===================================*/

defined('COT_CODE') or die('Wrong URL');

//======================================================================================
// http://www.php.net/manual/en/function.strtok.php
function subtok($string,$chr,$pos,$len = NULL) {
  return implode($chr,array_slice(explode($chr,$string),$pos,$len));
}

//======================================================================
//==-- Make PFS compatible with myfiles
//==-- By filling in correct path data for folders created by 'original' PFS
//======================================================================
function myfiles_compatibility() {
	global $db, $db_pfs_folders,$myFiles;
	// update the folders made by original PFS
	// These folders will always be root folders
//	cot_watch($db_pfs_folders);
	$sql = $db->query("UPDATE $db_pfs_folders SET pff_path=CONCAT('".$myFiles['cfg_pathsep']."',SUBSTRING(CONCAT('00000000000',pff_id),-11,11), '".$myFiles['cfg_pathsep']."') WHERE pff_path='/init/'");
}

//==-- Get the username
function myfiles_getusername($userid,$sitename="") {
	global $db, $usr,$db_users;
	
	// do not change this text
	$ret="Unknown";
	if ($userid=='') {
		return $ret;
	}	
	if ($userid=="0" && $sitename!="") {
		return $sitename;
	}
	
	if ($userid==$usr['id']) {
		// i am asking my own name ! (that is allready loaded)
		$ret=$usr['name'];
	} else {
		$sql_usr = $db->query("SELECT * FROM $db_users WHERE user_id=$userid");
		if ($sql_usr->rowCount()>0) {
			$record = $sql_usr->fetch();
			$ret=$record['user_name'];
		}		
	}	
	return $ret;
}

//================================================================
//==-- taken out of functions.php
//================================================================
function myfiles_get_uploadmax()
{
	static $par_a = array('upload_max_filesize', 'post_max_size', 'memory_limit');
	static $opt_a = array('G' => 1073741824, 'M' => 1048576, 'K' => 1024);
	$val_a = array();
	foreach ($par_a as $par)
	{
		$val = ini_get($par);
		$opt = strtoupper($val[strlen($val) - 1]);
		$val = isset($opt_a[$opt]) ? $val * $opt_a[$opt] : (int)$val;
		if ($val > 0)
		{
			$val_a[] = $val;
		}
	}
	return floor(min($val_a) / 1024); // KB
}

/*==============================================================================
								myfiles_getUploadSizeInfo
--------------------------------------------------------------------------------								
	this function returns multiple info as a string and comma separated !
		max uploadsize for 1 file	$maxfile	
		max storage size			$maxtotal
		size that has been used		pfs_totalsize		rounded
		size left in storage							(max storage size-size that has been used)
	NOTE: All in KB	
============================================================================== */
function myfiles_getUploadSizeInfo($userid) {
	global $db, $usr,$db_groups,$db_pfs;
	$db_pfs = (isset($db_pfs)) ? $db_pfs : 'cot_pfs';

	/*	important factors:		am i admin
								is it site pfs ?  ($userid=0)
								$usr[id]== 0	  (so guests)		(!! guests do NOT have pfs !!)  */
	if ($usr['id']==0) {
		// guests do NOT have pfs !
		return '0,0,0,0';
	}	

	if ($userid==$usr['id'] && $usr['id']>0) {
		$user_info=$usr['profile'];
	} else {
		$user_info = cot_userinfo($userid);
	}
	// site (userid=0) has same sizes as the admins = group 5!   (this is a fixed group number !!!!)
	$maingroup = ($userid==0) ? 5 : $user_info['user_maingrp'];
//	cot_print($db_x);

	$sql = $db->query("SELECT grp_pfs_maxfile, grp_pfs_maxtotal FROM $db_groups WHERE grp_id='$maingroup'");
	if ($row = $sql->fetch())
	{
		$maxfile = min($row['grp_pfs_maxfile'], myfiles_get_uploadmax());
		$maxtotal = $row['grp_pfs_maxtotal'];
	} else { 
		return '0,0,0,0';
	}
	$sql = $db->query("SELECT SUM(pfs_size) FROM $db_pfs WHERE pfs_userid='$userid' ");
	$pfs_totalsize = round($sql->fetchColumn());
	return $maxfile.','.$maxtotal.','.$pfs_totalsize.','.($maxtotal-$pfs_totalsize);
}

// Funtion: myfiles_getBrowserSite_JSON()
function myfiles_getBrowserSite_JSON() {
}

//=================================================================
// Funtion: myfiles_getRootJSON()
// returns the root JSON notation for a user
//=================================================================
function myfiles_getRootJSON($rootid="-1",$subfoldercount=0) {
	global $L;
	$curfjson = '{"pff_id":"'.$rootid.'",'.
					'"pff_path":"",'.
					'"pff_title":"'.$L['myfiles_root'].'",'.
					'"pff_desc":"'.$L['myfiles_rootdesc'].'",'.
					'"pff_ispublic":"0",'.
					'"pff_isgallery":"0",'.
					'"subfoldercount":'.$subfoldercount.','.
					'"pff_count":"0"}';	
	return $curfjson;
}

//=================================================================
// Funtion: myfiles_getParentFolder
//-----------------------------------------------------------------
// returns the folderid from the parent   
// e.g.   	path = /3/5/6/7/
//		  	will return 6
//			6 is the parentfolder for folder 7 (wich is this folder)
//=================================================================
function myfiles_getParentFolder_Path($linkpath="") {
	global $myFiles;
	// pathsep = "/" 
	$array = explode($myFiles['cfg_pathsep'], $linkpath);
	$previousfolder	= "-1";
	$xx="-1";
	foreach ($array as $i) {
		if ($i!='') {
			$previousfolder=$xx;
			$xx=$i;
		}
	}
	return $previousfolder;
}	

//=================================================================
// Funtion: myfiles_getFolder
//-----------------------------------------------------------------
// returns 	NULL if the folder is NOT found (or if it is a root folder)
//			folderrecord (assoc)array if it is found 
//=================================================================
function myfiles_getFolder($folderid="",$userid="") {
	global $db, $db_pfs_folders,$cfg,$myFiles;
	$ret=NULL;
	
	if ($folderid!='' && (int)$folderid>0) {
		// extra filter on userid ?
		if ($userid!="") {
			$filter = "pff_id='$folderid' AND pff_userid='$userid'";
		} else {
			$filter = "pff_id='$folderid'";
		}

		if ($myFiles['cfg_compatible']=='Yes') {
			myfiles_compatibility();
		}
		// search the specified folder to get the details
		$sql_fldr = $db->query("SELECT * FROM $db_pfs_folders WHERE $filter");
		if ($sql_fldr->rowCount()>0) {
			$ret = $sql_fldr->fetch();
		}
	} else {
		// ROOT or invalid number
	}
	
	return $ret;
}

//============================================================
//==--  Get all info we need about folder rights
//==--	""		NO rights
//==--	gives   "<RWA>,<Userid>"		userid from root or folder userid
//============================================================
// default gets the root files  (folder id = 0)
function myfiles_getFolderRightsInfo($folderid='0',$userid='') {
	global $usr,$myFiles;
	
	if ($usr['isadmin']) {
		if ($userid=='') {
			$userid=$usr['id'];
		}
		return 'RWA,'.$userid;
	}	
	if ($folderid=='' || $folderid=='-1' || $folderid=='0') {
		// ok, we need to check which user's root folder
		if ($userid==$usr['id'] || $userid=="") {
			// your asking permission to your own root folder
			return 'RWA,'.$usr['id'];
		} else {
			if ($myFiles['cfg_public_root']=='1') {
				return 'R,'.$userid;
			} else {
				return '';
			}
		}
	}	
	// rootfolders (0) are not real folders (they have NO records, so will return NULL also)
	$frow = myfiles_getFolder($folderid);
	if ($frow==NULL) {
		// folder NOT found 
		return '';
	}
	if ($frow['pff_ispublic']==1 || $frow['pff_userid']==$usr['id'] ) {
		if ($frow['pff_userid']==$usr['id']) {
			return 'RWA,'.$frow['pff_userid'];
		} else {
			return 'R,'.$frow['pff_userid'];
		}
	}
	return '';
}

//============================================================
//==--  Check if we have reading rights for a folder !
//============================================================
// default gets the root files  (folder id = 0)
function myfiles_allowedToReadFolder($folderid='0',$userid='') {
	$folderrights=myfiles_getFolderRightsInfo($folderid,$userid);
	$pos = strpos($folderrights,"R");
	return ($pos === FALSE) ? FALSE : TRUE;
}

//=================================================================
// Funtion: myfiles_getFolderPath
//-----------------------------------------------------------------
// returns '/' aka root, if the folderid is "", -1 or 0
// returns NULL if folder is NOT found
//=================================================================
function myfiles_getFolderPath($folderid='') {
	global $myFiles;
	$linkpath=NULL;
	if ($folderid=='' || $folderid=='-1' || $folderid=='0') {
		$linkpath=$myFiles['cfg_pathsep'];
	}	
	$frow=myfiles_getFolder($folderid);
	if ($frow!=NULL) {
		$linkpath=$frow['pff_path'];
	}
	return $linkpath;
}	

//==============================================================
// Get the folder path in text (with the foldernames !!!)
//  e.g.   /Main level/First/Second/Third/
//
//  	returns "" if the folderid is not found
//  	returns "/" if the folderid is -1 or empty
//==============================================================
function myfiles_getFolderPathText($folderid='',$showlastsep=1) {
	global $db, $db_pfs_folders,$L,$myFiles,$cfg;

	$ret="";
	if ($folderid=='' || $folderid=='-1' || $folderid=='0') {
		return $myFiles['cfg_pathsep'];
	}
	$frow=myfiles_getFolder($folderid);
	if ($frow!=NULL) {
		$cleanpath = substr($frow['pff_path'], 1, -1);
		$list=str_replace($myFiles['cfg_pathsep'],',',$cleanpath);
		$sql_list = $db->query("SELECT * FROM $db_pfs_folders WHERE pff_id IN (".$list.")");
		if (!$sql_list) {
			return '';
		}
		$ret='';
		for ($i=0;$i<25;$i++) {
			$folderid=subtok($cleanpath,$myFiles['cfg_pathsep'],$i,1);
			if ($folderid != '') {
				// strip leading zeros
				$folderid=ltrim($folderid,'0');	
				// look for the folder id in the sql results
				// must be fetch() in PDO
				while($row = $sql_list->fetch() ) {
					if ($row['pff_id']==$folderid) {
						$ret.=$myFiles['cfg_pathsep'].$row['pff_title'];
						break;
					}
				} 
			} else {
				break;
			}	
		}
		if ($showlastsep==1) {
			$ret.=$myFiles['cfg_pathsep'];
		}	
	}
	return $ret;
}	

//============================================================
//==--   Internal function  
//============================================================
// $userid from the folder to search in
// paramsearchpath = pff_path from the folder to search in
// $iamAdmin = default false
//			use false to only get the PUBLIC folders
//			Admins gets all folders
//=============================================================
function myfiles_getSubfoldersJSON($userid="",$paramsearchpath="",$iamAdmin=false) {
	global $db, $usr,$cfg,$db_pfs_folders,$myFiles;

	if ($userid=='') {
		return '[]';
	}	
	if ($userid==$usr['id'] || $iamAdmin) {
		// get ALL folders
		$extrafilter = " ";
	} else {
		// IT is not MY folder, or i am not an admin, so only get publicfolders
		$extrafilter = " AND pff_isublic='1' ";
	}

	if ($myFiles['cfg_compatible']=='Yes') {
		myfiles_compatibility();
	}
	
	//==-- CURRENT Folder search criterea
	$searchpath = $myFiles['cfg_pathsep'].'%'.$myFiles['cfg_pathsep'];
	if ($paramsearchpath!="") {
		$searchpath = $paramsearchpath.'%'.$myFiles['cfg_pathsep'];
	}

	//==-- SUB Folders
	$searchpathexcl = $searchpath.'%'.$myFiles['cfg_pathsep'];	
	$sql_subfolders = $db->query('SELECT * FROM '.$db_pfs_folders." WHERE pff_userid='$userid' AND pff_path LIKE '".$searchpath.
																			"' AND pff_path NOT LIKE '".$searchpathexcl."'".$extrafilter.'ORDER BY pff_title');
	$subfjson='[';
	if ($sql_subfolders->rowCount()>0) {
		$asep='';
		while($srow = $sql_subfolders->fetch()) {
			$subfjson.=$asep.json_encode($srow);
			$asep=',';
		}
	} else {
		// no sub folders
	}
	$subfjson.=']';

	return $subfjson;
}	

//============================================================
//==--   Internal function
//============================================================
// $userid from the folder to search in  (need this parameter for root)
// paramsearchpath = pff_path from the folder to search in
//============================================================
function myfiles_getSubfoldersCount($userid="",$paramsearchpath="") {
	global $db, $usr,$cfg,$db_pfs_folders,$myFiles;
	if ($myFiles['cfg_compatible']=='Yes') {
		myfiles_compatibility();
	}
	$searchpath = $myFiles['cfg_pathsep']."%".$myFiles['cfg_pathsep'];	
	if ($paramsearchpath!="") {
		$searchpath = $paramsearchpath."%".$myFiles['cfg_pathsep'];
	}
	$searchpathexcl = $searchpath."%".$myFiles['cfg_pathsep'];	
	$sql_subfolders = $db->query("SELECT * FROM ".$db_pfs_folders." WHERE pff_userid='$userid' AND pff_path LIKE '".$searchpath.
																			"' AND pff_path NOT LIKE '".$searchpathexcl."' ORDER BY pff_title");
	return $sql_subfolders->rowCount();
}

//============================================================
//==--  Get files from a folder in JSON format
//==--  JSON format is without the wrapper structure (so no endusage)
//============================================================
// default gets the root files  (folder id = 0)
function myfiles_getFolderFilesJSON($userid,$folderid="0") {
	global $db, $usr,$db_pfs;

	$iamAdmin=$usr['isadmin'];
	if ($folderid=='' || $folderid=='-1') {
		$folderid='0';
	}	
	
	if (!myfiles_allowedToReadFolder($folderid,$userid)) {
		return '[]';
	}
	
	$sql_files = $db->query("SELECT * FROM $db_pfs WHERE pfs_userid='$userid' AND pfs_folderid='$folderid' ORDER BY pfs_file ASC");
	$fjson='[';
	if ($sql_files->rowCount()>0) {
		$asep='';
		while($srow = $sql_files->fetch()) {
			$fjson.=$asep.json_encode($srow,JSON_FORCE_OBJECT);
			$asep=',';
		}
	} else {
		// no sub folders, do nothing !
	}
	$fjson.=']';
	return $fjson;
}

//============================================================
//==--  Get files count from a folder
//============================================================
// default gets the root files  (folder id = 0)
function myfiles_getFilesCount($folderid='0') {
	global $db, $db_pfs;
	if ($folderid=='' || $folderid=='-1') {
		$folderid='0';
	}	
	$sql_files = $db->query("SELECT pfs_id FROM $db_pfs WHERE pfs_folderid='$folderid'");
	return $sql_files->rowCount();
}

//============================================================
//==--  Get myfiles infoblock HTML
//============================================================
function myfiles_getInfoBlock_html($userid='',$template='') {
	global $db, $cfg,$L,$usr,$myFiles,$db_groups,$db_pfs;

	if ($userid=='') {
		// hmm this could even be userid = 0  (so guests)
		$userid=$usr['id'];
	}
	if ($usr['id']!=$userid & !$usr['isadmin']) {
		// other then your own userid and not an admin
		die($L['myfiles_err_noaccess']);
	}

	$user_info = cot_userinfo($userid);
	$maingroup = ($userid==0) ? 5 : $user_info['user_maingrp'];

	$sql = $db->query("SELECT grp_pfs_maxfile, grp_pfs_maxtotal FROM $db_groups WHERE grp_id='$maingroup'");
	if ($row = $sql->fetch())
	{
		$maxfile = min($row['grp_pfs_maxfile'], cot_get_uploadmax());
		$maxtotal = $row['grp_pfs_maxtotal'];
	} else { 
		die($L['myfiles_err_noaccess']);
	}

	$u_totalsize=0;
	$sql = $db->query("SELECT SUM(pfs_size) FROM $db_pfs WHERE pfs_userid='$userid' ");
	$pfs_totalsize = $sql->fetchColumn();

	if ((int)$maxtotal==0) {
		$usedsizekb = 0;
		$pfs_precentbar = 0;
	} else {
		$usedsizekb = floor(100*$pfs_totalsize/1024/$maxtotal);
		$pfs_precentbar = floor(100 * $pfs_totalsize / 1024 / $maxtotal);
	}	
	if ($template=='') {
		$template='myfiles.storageinfo.tpl';
	}
	$mskin = $cfg['plugins_dir'].'/myfiles/tpl/'.$template; 
	$mib = new XTemplate($mskin);

	$mib-> assign(array(
		'MYFILES_BASEDIR'			=> $cfg['plugins_dir'].'/myfiles',
		"STORAGEINFO_USEDSIZE"		=> 	$usedsizekb,
		"STORAGEINFO_MAXTOTAL"		=> 	$maxtotal,
		"STORAGEINFO_MAXFILE"		=> 	$maxfile,
		"STORAGEINFO_USERID"		=>	$userid,
		"STORAGEINFO_USEDPERC"		=>	$pfs_precentbar
	));
	$mib->parse("MAIN");
	return $mib->text();
}

//============================================================
//==--  Get complete minidir HTML    (selectbox folder browser)
//==--	if $userid=0, this means the sitefiles
//============================================================
function myfiles_getMiniDir_html($folderid='',$userid='',$foldermode='list',$hidebuttons='0',$folderrights="") {
	global $cfg,$L,$usr,$myFiles;

	$username='';
	$showuser='0';
	$folderid=ltrim($folderid,'0');			// strip leading zeros if present
	
	/** ==-- rights --== **/
	if ($folderrights=="") {
		$folderrights=myfiles_getFolderRightsInfo($folderid,$userid);
	}	
	$pos = strpos($folderrights,"A");
	$adminaccess = ($pos === FALSE) ? FALSE : TRUE;
	$pos = strpos($folderrights,"W");
	$writeaccess = ($pos === FALSE) ? FALSE : TRUE;
	$pos = strpos($folderrights,"R");
	$readaccess = ($pos === FALSE) ? FALSE : TRUE;
	$folderuserid=subtok($folderrights,',',1,1);

	if (!$readaccess) {
		die($L['myfiles_err_noaccess']);
	}
	if ($userid!='') {
		$showuser='1';
		$username=myfiles_getusername($userid,$L['myfiles_sitefiles']);
		if ($username=="") {
			$showuser='0';
		}
	}
	if (!$adminaccess && $usr['id']!=$userid) {
		// its NOT me and current user is NOT an admin
		$hidebuttons='1';
	}	
	
	if ($foldermode=='list' || $foldermode=='') {
		// dirlist (folder list)
		$mskin1 = $cfg['plugins_dir'].'/myfiles/tpl/myfiles.folderlist.tpl'; 
		$gmd = new XTemplate($mskin1);	
		$ajaxpart=myfiles_getFolderList_ajax_html($folderid,$userid,$hidebuttons);
		$gmd-> assign(array(
				'MYFILES_BASEDIR'		=> $cfg['plugins_dir'].'/myfiles',
				'DIRLIST_PREVFOLDERID'	=> "-1",
				'DIRLIST_CURFOLDERID'	=> ($folderid=="") ? '-1':$folderid,
				'DIRLIST_CURFOLDERINFO'	=> $curfjson,
				'DIRLIST_SHOWUSERNAME'	=> $showuser,
				'DIRLIST_USERNAME'		=> $username,
				'DIRLIST_USERID'		=> $userid,
				'DIRLIST_HIDEBTNS'		=> $hidebuttons,
				'DIRLIST_MAXFOLDERDEPTH' => $myFiles['cfg_maxfolderdepth'],
				'DIRLIST_AJAXPART'		=> $ajaxpart
			));
	
	} else {
		//===========================
		// minidir (select box)
		//===========================
		$mskin1 = $cfg['plugins_dir'].'/myfiles/tpl/myfiles.minidir.tpl'; 
		$gmd = new XTemplate($mskin1);	
		$ajaxpart=myfiles_getMiniDir_ajax_html($folderid,$userid,$iamAdmin);
		$gmd-> assign(array(
				'MYFILES_BASEDIR'		=> $cfg['plugins_dir'].'/myfiles',
				'MINIDIR_PREVFOLDERID'	=> "-1",
				'MINIDIR_CURFOLDERID'	=> ($folderid=="") ? '-1':$folderid,
				'MINIDIR_CURFOLDERINFO'	=> $curfjson,
				'MINIDIR_SHOWUSERNAME'	=> $showuser,
				'MINIDIR_USERNAME'		=> $username,
				'MINIDIR_USERID'		=> $userid,
				'MINIDIR_MAXFOLDERDEPTH' => $myFiles['cfg_maxfolderdepth'],
				'MINIDIR_HIDEBTNS'		=> $hidebuttons,
				'MINIDIR_AJAXPART'		=> $ajaxpart
			));
	}
	$gmd->parse('MAIN');
	return $gmd->text();
}	

//============================================================
//==--  Get ajax part of dirlist HTML
//============================================================
function myfiles_getFolderList_ajax_html($folderid="",$userid="",$hidebuttons='0') {
	global $db, $usr,$cfg,$db_pfs_folders,$L,$myFiles;

	$iamAdmin=$usr['isadmin'];
//	$shownewfolder="1";
	$folderid=ltrim($folderid,'0');			// strip leading zeros
	$curfolderid=$folderid;
	if ($userid=="") {
		$userid=$usr['id'];
	} else {
		if ($usr['id']!=$userid && !$iamAdmin) {
			return $L['myfiles_err_noaccess'];
		}
	}
	$mskin2 = $cfg['plugins_dir']."/myfiles/tpl/myfiles.folderlist.ajax.tpl"; 
	$gmda = new XTemplate($mskin2);	

	//================================================================
	//==-- CURRENT Folder
	//================================================================
	$searchpath = $myFiles['cfg_pathsep']."%".$myFiles['cfg_pathsep'];
	$linkpath=$myFiles['cfg_pathsep'];
	$subfjson = '[]';
	$frow = myfiles_getFolder($folderid, $userid);
	if ($frow!=NULL) {
		$frow['subfoldercount']=myfiles_getSubfoldersCount($userid,$frow['pff_path']);
		$curfjson=json_encode($frow);
		$linkpath=$frow['pff_path'];
		$searchpath = $frow['pff_path']."%".$myFiles['cfg_pathsep'];
	} else {
		$sfcount=myfiles_getSubfoldersCount($userid,"");
		$curfjson = myfiles_getRootJSON("-1",$sfcount);
	}

	//================================================================
	//==-- LINKPATH Folders  (Breadcrumb)
	//================================================================
	$previousfolder	= "-1";
	$linkpathtext="";
	if ($linkpath != $myFiles['cfg_pathsep'])  {
		$array = explode($myFiles['cfg_pathsep'], $linkpath);
		$sep = "";
		$list= "";
		$xx="-1";
		foreach ($array as $i) {
			if ($i!='') {
				$i=ltrim($i,'0');			// strip leading zeros
				$list.=$sep.$i;
				$previousfolder=$xx;
				$xx=$i;
				$sep=",";
			}
		}
		
		if ($list!="") {
			$filter=" AND pff_id IN (".$list.")";		// pff_id IN (30,31,40)
		}	
		$sql_linkfolders = $db->query("SELECT pff_id, pff_title, pff_desc FROM $db_pfs_folders WHERE pff_userid='$userid'".$filter.' ORDER BY pff_path');
		$sep=" ".$myFiles['cfg_pathsep']." ";
		if ($sql_linkfolders->rowCount()>0) {
			$nolinkpath=" ".$myFiles['cfg_pathsep'];
			$gmda-> assign(array(
									"MINIDIR_FOLDERID"	=> 	'-1',
									"MINIDIR_LINKTITLE"	=> 	$L['myfiles_root'],
									"MINIDIR_LINKTEXT"	=> 	$L['myfiles_root'],
									"MINIDIR_LINKSEP"	=>	""
								));
			$linkpathtext.=$L['myfiles_root']." ";
			$gmda->parse("MAIN.LINK_ITEM");	
		
			foreach ($array as $i) {
				if ($i!='') {
					$i=ltrim($i,'0');			// strip leading zeros
					while($srow = $sql_linkfolders->fetch()) {
						// shorttitle handling
						if (strlen(trim($srow['pff_title'])) > 20) {
							$shorttitle=substr(trim($srow['pff_title']), 0,17).'...';
						} else {
							$shorttitle=trim($srow['pff_title']);
						}
						// replace shorttitle spaces with &nbsp;
						$shorttitle = str_replace(' ', '&nbsp;', $shorttitle);

						if ($folderid==$srow['pff_id']) {
							$nolinkpath=" ".$myFiles['cfg_pathsep'].' '.$shorttitle.' '.$myFiles['cfg_pathsep'];
						}
						if ($srow['pff_id']==$i) {
							$gmda-> assign(array(
								'MINIDIR_FOLDERID'	=> 	$i,
								'MINIDIR_LINKTITLE'	=> 	trim($srow['pff_title']).' || '.trim($srow['pff_desc']),
								'MINIDIR_LINKTEXT'	=> 	$shorttitle,
								'MINIDIR_LINKSEP'	=>	$sep
							));
							$linkpathtext.=$sep." ".trim($srow['pff_title'])." ";
							$gmda->parse('MAIN.LINK_ITEM');	
							$sep=' '.$myFiles['cfg_pathsep']." ";
							$curfolderid=$i;
							break;
						}
					}
				}
			}
		} else {
			// no linkpath
			$nolinkpath=$L['myfiles_root']."&nbsp;".$myFiles['cfg_pathsep'];
			$gmda-> assign(array(
									'MINIDIR_FOLDERID'	=> 	'-1',
									'MINIDIR_LINKTITLE'	=> 	$L['myfiles_root'],
									'MINIDIR_LINKTEXT'	=> 	$L['myfiles_root'],
									'MINIDIR_LINKSEP'	=>	''
								));
			$linkpathtext.=$L['myfiles_root']." ";
			$gmda->parse('MAIN.LINK_ITEM');	
		}
	} else {
		// no linkpath
		$nolinkpath=$L['myfiles_root'].'&nbsp;'.$myFiles['cfg_pathsep'];
		$gmda-> assign(array(
								'MINIDIR_FOLDERID'	=> 	'-1',
								'MINIDIR_LINKTITLE'	=> 	$L['myfiles_root'],
								'MINIDIR_LINKTEXT'	=> 	$L['myfiles_root'],
								'MINIDIR_LINKSEP'	=>	''
							));
		$linkpathtext.=$L['myfiles_root'].' ';
		$gmda->parse("MAIN.LINK_ITEM");	
	}
	
	
	//================================================================
	//==-- SUB Folders
	//================================================================
	$searchpathexcl = $searchpath."%".$myFiles['cfg_pathsep'];	
	$sql_subfolders = $db->query("SELECT * FROM ".$db_pfs_folders." WHERE pff_userid='$userid' AND pff_path LIKE '".$searchpath."' AND pff_path NOT LIKE '".$searchpathexcl."' ORDER BY pff_title");
	
	$subfjson="[".$curfjson;
	$numsubfolders=$sql_subfolders->rowCount();
	if ($numsubfolders>0) {
		$asep=",";

		while($srow = $sql_subfolders->fetch()) {
			$srow['lastchange'] = @date($cfg['dateformat'], $srow['pff_updated'] + $usr['timezone'] * 3600);

			$gmda-> assign(array(
								'FLDR_ID'			=> 	$srow['pff_id'],
								'FLDR_NAME'			=> 	$srow['pff_title'],
								'FLDR_DESC'			=> 	$srow['pff_desc'],
								'FLDR_PUBLIC'		=> 	$srow['pff_ispublic'],
								'FLDR_IMAGE'		=> 	$srow['pff_isgallery'],
								'FLDR_LASTCHANGE'	=>	$srow['lastchange'],
								'MINIDIR_HIDEBTNS'	=>	$hidebuttons
							));
			$gmda->parse("MAIN.DIRLIST_ITEM");	

			$subfjson.=$asep.json_encode($srow);
			$asep=",";
		}
	} else {
		// no sub folders
		
	}
	$subfjson.=']';

	$gmda-> assign(array(
			'MINIDIR_FOLDERSEP'		=> $myFiles['cfg_pathsep'],
			'MINIDIR_CURFOLDERID'	=> ($folderid=="") ? '-1':$curfolderid,	
			'MINIDIR_PREVFOLDERID'	=> $previousfolder,
			'MINIDIR_HASSUBFOLDERS'	=> ($numsubfolders>0) ? "1":"0",
			'MINIDIR_HIDEBTNS'		=> $hidebuttons,
			'MINIDIR_SUBFOLDERINFO'	=> $subfjson			
		));

	$gmda->parse('MAIN');
	return $gmda->text();
}	

//============================================================
//==--  Get ajax part of minidir HTML   (selectbox browser)
//============================================================
function myfiles_getMiniDir_ajax_html($folderid="",$userid="",$goback=0) {
	global $db, $usr,$cfg,$db_pfs_folders,$L,$myFiles;

	$iamAdmin=$usr['isadmin'];
	$folderid=ltrim($folderid,'0');			// strip leading zeros
	$curfolderid=$folderid;
	if ($userid=="") {
		$userid=$usr['id'];
	} else {
		if ($usr['id']!=$userid && !$iamAdmin) {
			return $L['myfiles_err_noaccess'];
		}
	}
	$mskin2 = $cfg['plugins_dir']."/myfiles/tpl/myfiles.minidir.ajax.tpl"; 
	$gmda = new XTemplate($mskin2);	

	//================================================================
	//==-- CURRENT Folder
	//================================================================
	$searchpath = $myFiles['cfg_pathsep']."%".$myFiles['cfg_pathsep'];
	$linkpath=$myFiles['cfg_pathsep'];

	// root data !!!
	$subfjson = '[]';
	// a root folder: pff_path = '/xxx/'
	
	$frow = myfiles_getFolder($folderid, $userid);
	if ($frow!=NULL) {
		$frow['subfoldercount']=myfiles_getSubfoldersCount($userid,$frow['pff_path']);
		$curfjson=json_encode($frow);
		if ($goback==1) {
			$parentfolderpath = subtok($frow['pff_path'],$myFiles['cfg_pathsep'],0,-2).$myFiles['cfg_pathsep'];
			$parid=subtok($parentfolderpath,$myFiles['cfg_pathsep'],-2);
			$parid=str_replace($myFiles['cfg_pathsep'], '', $parid);
			$parid=ltrim($parid,'0');
			if ($parid!='') {
				$pprow=myfiles_getFolder($parid,$userid);
				$curfjson=json_encode($pprow);
			} else {
				$sfcount=myfiles_getSubfoldersCount($userid,"");
				$curfjson = myfiles_getRootJSON("-1",$sfcount);
			}
			$linkpath=$parentfolderpath;
			$searchpath = $parentfolderpath."%".$myFiles['cfg_pathsep'];
			if ($parentfolderpath==$myFiles['cfg_pathsep']) {
				$curfolderid=-1;
			}
		} else {
			// go further in the tree
			$linkpath=$frow['pff_path'];
			$searchpath = $frow['pff_path']."%".$myFiles['cfg_pathsep'];
		}
	} else {
		// root data !!!
		$sfcount=myfiles_getSubfoldersCount($userid,"");
		$curfjson = myfiles_getRootJSON("-1",$sfcount);
	}

	//================================================================
	//==-- LINKPATH Folders  (Breadcrumb)
	//================================================================
	$previousfolder	= "-1";
	$linkpathtext="";
	if ($linkpath != $myFiles['cfg_pathsep'])  {
		// linkpath = "/a/b/" or something, we need to make links out of a and b
		$array = explode($myFiles['cfg_pathsep'], $linkpath);
		$sep = "";
		$list= "";
		$xx="-1";
		foreach ($array as $i) {
			if ($i!='') {
				$i=ltrim($i,'0');			// strip leading zeros
				$list.=$sep.$i;
				$previousfolder=$xx;
				$xx=$i;
				$sep=",";
			}
		}
		
		if ($list!="") {
			$filter=" AND pff_id IN (".$list.")";		// pff_id IN (30,31,40)
		}	
		// need this data to create path links to higher folders (linkpath)
		$sql_linkfolders = $db->query("SELECT pff_id, pff_title, pff_desc FROM $db_pfs_folders WHERE pff_userid='$userid'".$filter.' ORDER BY pff_path');
		$sep=" ".$myFiles['cfg_pathsep']." ";
		if ($sql_linkfolders->rowCount()>0) {
			$nolinkpath=" ".$myFiles['cfg_pathsep'];
			$gmda-> assign(array(
									"MINIDIR_FOLDERID"	=> 	'-1',
									"MINIDIR_LINKTITLE"	=> 	$L['myfiles_root'],
									"MINIDIR_LINKTEXT"	=> 	$L['myfiles_root'],
									"MINIDIR_LINKSEP"	=>	""
								));
			$linkpathtext.=$L['myfiles_root']." ";
			$gmda->parse("MAIN.LINK_ITEM");	
		
			foreach ($array as $i) {
				if ($i!='') {
					$i=ltrim($i,'0');			// strip leading zeros
					while($srow = $sql_linkfolders->fetch()) {
						// shorttitle handling
						if (strlen(trim($srow['pff_title'])) > 20) {
							$shorttitle=substr(trim($srow['pff_title']), 0,17).'...';
						} else {
							$shorttitle=trim($srow['pff_title']);
						}
						// replace shorttitle spaces with &nbsp;
						$shorttitle = str_replace(' ', '&nbsp;', $shorttitle);

						if ($folderid==$srow['pff_id']) {
							$nolinkpath=" ".$myFiles['cfg_pathsep'].' '.$shorttitle.' '.$myFiles['cfg_pathsep'];
						}
						if ($srow['pff_id']==$i) {
							$gmda-> assign(array(
								'MINIDIR_FOLDERID'	=> 	$i,
								'MINIDIR_LINKTITLE'	=> 	trim($srow['pff_title']).' || '.trim($srow['pff_desc']),
								'MINIDIR_LINKTEXT'	=> 	$shorttitle,
								'MINIDIR_LINKSEP'	=>	$sep
							));
							$linkpathtext.=$sep." ".trim($srow['pff_title'])." ";
							$gmda->parse('MAIN.LINK_ITEM');	
							$sep=' '.$myFiles['cfg_pathsep']." ";
							$curfolderid=$i;
							break;
						}
					}
				}
			}
		} else {
			// no linkpath
			$nolinkpath=$L['myfiles_root']."&nbsp;".$myFiles['cfg_pathsep'];
			$gmda-> assign(array(
									'MINIDIR_FOLDERID'	=> 	'-1',
									'MINIDIR_LINKTITLE'	=> 	$L['myfiles_root'],
									'MINIDIR_LINKTEXT'	=> 	$L['myfiles_root'],
									'MINIDIR_LINKSEP'	=>	''
								));
			$linkpathtext.=$L['myfiles_root']." ";
			$gmda->parse('MAIN.LINK_ITEM');	
		}
	} else {
		// no linkpath
		$nolinkpath=$L['myfiles_root'].'&nbsp;'.$myFiles['cfg_pathsep'];
		$gmda-> assign(array(
								'MINIDIR_FOLDERID'	=> 	'-1',
								'MINIDIR_LINKTITLE'	=> 	$L['myfiles_root'],
								'MINIDIR_LINKTEXT'	=> 	$L['myfiles_root'],
								'MINIDIR_LINKSEP'	=>	''
							));
		$linkpathtext.=$L['myfiles_root'].' ';
		$gmda->parse("MAIN.LINK_ITEM");	
	}
	
	//================================================================
	//==-- SUB Folders
	//================================================================
	$searchpathexcl = $searchpath."%".$myFiles['cfg_pathsep'];	
	$sql_subfolders = $db->query("SELECT * FROM ".$db_pfs_folders." WHERE pff_userid='$userid' AND pff_path LIKE '".$searchpath."' AND pff_path NOT LIKE '".$searchpathexcl."' ORDER BY pff_title");
	
	$folderselect	= "";
	$selecteditem=(int)$folderid;
	$publicstring="&nbsp;&nbsp;&nbsp;&nbsp;( ".$L['myfiles_ispublic_c']." )";
	$subfjson="[".$curfjson;
	if ($sql_subfolders->rowCount()>0) {
		$asep=",";
		if ($selecteditem==0) {
			$folderselect='<select class="fldr_select" size="1" name="folderid"><option value="0" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;</option>';
		} else {
			$folderselect='<select class="fldr_select" size="1" name="folderid"><option value="0">&nbsp;&nbsp;&nbsp;&nbsp;</option>';
		}
		while($srow = $sql_subfolders->fetch()) {
			$srow['subfoldercount']=myfiles_getSubfoldersCount($userid,$srow['pff_path']);
			$srow['lastchange'] = @date($cfg['dateformat'], $srow['pff_updated'] + $usr['timezone'] * 3600);
			$folderselect.='<option value="'.$srow['pff_id'].'"';
			if ($selecteditem==$srow['pff_id']) {
				$folderselect.=' selected="selected">';
			} else {
				$folderselect.='>';
			}			
			if ($srow['subfoldercount'] > 0) {
				$folderselect.='&#187;&nbsp;'.trim($srow['pff_title']);
			} else {
				$folderselect.='&nbsp;&nbsp;'.trim($srow['pff_title']);
			}
			if ($srow['pff_ispublic']==1) { $folderselect.=$publicstring; }	

			$folderselect.='</option>';
			$subfjson.=$asep.json_encode($srow);
			$asep=",";
		}
		$folderselect.='</select>';
		$folderact="GO_IN";
	} else {
		// no sub folders
		$folderselect='<select class="fldr_select" size="1" name="folderid"><option value="0" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;</option>';
		$folderselect.='</select>';
		$folderact='NEW';
	}
	$subfjson.=']';

	$gmda-> assign(array(
			'MINIDIR_FOLDERSEP'		=> $myFiles['cfg_pathsep'],
			'MINIDIR_PUBSTRING'		=> '"'.$publicstring.'"',
			'MINIDIR_FOLDERSELECT'	=> $folderselect,
			'MINIDIR_PREVFOLDERID'	=> $previousfolder,	
			'MINIDIR_CURFOLDERID'	=> ($folderid=="") ? '-1':$curfolderid,	
			'MINIDIR_FOLDERACTIONS'	=> $folderact,
			'MINIDIR_SUBFOLDERINFO'	=> $subfjson			
		));

	$gmda->parse('MAIN');
	return $gmda->text();
}	
	
	
//============================================================
//==--  Get complete myfiles fileslist HTML
//==--	if $userid=0, this means the sitefiles
//============================================================
function myfiles_getFilelist_html($folderid='',$userid='',$mode='compact',$folderrights,$showfolderpath='0') {
	global $cfg,$L,$usr,$myFiles;

	$username='';
	$showuser="0";
	$folderid=ltrim($folderid,'0');			// strip leading zeros if present

	if ($folderrights=="") {
		$folderrights=myfiles_getFolderRightsInfo($folderid,$userid);
	}	
	$pos = strpos($folderrights,"W");
	$writeaccess = ($pos === FALSE) ? FALSE : TRUE;
	$pos = strpos($folderrights,"R");
	$readaccess = ($pos === FALSE) ? FALSE : TRUE;
	
	if (!$readaccess) {
		return $L['myfiles_err_noaccess'];
	}	

	if ($userid!="") {
		$showuser="1";
		$username=myfiles_getusername($userid,$L['myfiles_sitefiles']);
	}
	
	$mskin1 = $cfg['plugins_dir'].'/myfiles/tpl/myfiles.filelist.tpl'; 
	$gmd = new XTemplate($mskin1);	
	$ajaxpart=myfiles_getFilelist_ajax_html($folderid,$userid,$mode,'',$folderrights,$showfolderpath);
	$gmd-> assign(array(
			'MYFILES_BASEDIR'		=> $cfg['plugins_dir'].'/myfiles',
			'FILES_CURFOLDERID'		=> ($folderid=="") ? "-1":$folderid,
			'FILES_SHOWUSERNAME'	=> $showuser,
			'FILES_SHOWEDITBTNS'	=> ($writeaccess) ? "1":"0",
			'FILES_USERNAME'		=> $username,
			'FILES_USERID'			=> $userid,
			'FILES_AJAXPART'		=> $ajaxpart
		));
	$gmd->parse('MAIN');
	return $gmd->text();
}	

//============================================================
function myfiles_getFilelist_ajax_html($folderid,$userid,$mode,$thumbnails='',$folderrights='',$showfolderpath='0') {
	global $db, $usr,$L,$cfg,$db_pfs;
	$folderid=ltrim($folderid,'0');			// strip leading zeros

	if ($folderid=='' || $folderid=='-1') {
		$folderid='0';
	}

	if ($folderrights=="") {
		$folderrights=myfiles_getFolderRightsInfo($folderid,$userid);
	}	
	$pos = strpos($folderrights,"W");
	$writeaccess = ($pos === FALSE) ? FALSE : TRUE;
	$pos = strpos($folderrights,"R");
	$readaccess = ($pos === FALSE) ? FALSE : TRUE;

	$userid=subtok($folderrights,',',1,1);
	
	if (!$readaccess) {
		return $L['myfiles_err_noaccess'];
	}	
	
	$thumbnails = ($thumbnails=='' || $thumbnails=='0') ? 0:1;
	
	$mskin2 = $cfg['plugins_dir'].'/myfiles/tpl/myfiles.filelist.ajax.tpl'; 
	$gmda = new XTemplate($mskin2);	
	
	if ($mode=='compact') {
		$sql_filefolders = $db->query("SELECT pfs_id,pfs_friendlyname,pfs_file,pfs_extension,pfs_size,pfs_desc,pfs_usrfolder FROM $db_pfs WHERE pfs_folderid='$folderid' AND pfs_userid='$userid'");
	} else {
		$sql_filefolders = $db->query("SELECT * FROM $db_pfs WHERE pfs_folderid='$folderid' AND pfs_userid='$userid'");
	}

	$totalfiles=$sql_filefolders->rowCount();
	if ($totalfiles>0) {
		$asep=",";
		while($srow = $sql_filefolders->fetch()) {
			// location of the file (url) is depending on $curfile['pfs_usrfolder']
			if ($srow['pfs_usrfolder']=='1') {
				// file uses FSM 
				$pfs_url= $cfg['pfs_dir'].$userid.'/'.$srow['pfs_file'];
				$thumb_url= $cfg['thumbs_dir'].'/'.$userid.'/'.$srow['pfs_file'];
			} else {
				// file uses root
				$pfs_url= $cfg['pfs_dir'].$srow['pfs_file'];
				$thumb_url=$cfg['thumbs_dir'].'/'.$srow['pfs_file'];
//				cot_print($thumb_url);
			}
			if (!file_exists($thumb_url)) {
				$thumb_url="";
			}
			$pfs_size = round($srow['pfs_size']/1024,1);
		
			$gmda->assign(array(
			    'FILE_SHOWCOMPACT'	=> ($mode == 'compact') ? '1' : '0',
			    'FILE_SHOWTHUMBS'	=> $thumbnails,
			    'FILE_ID'		=> $srow['pfs_id'],
			    'FILE_SHOWEDITBTN'	=> ($writeaccess) ? "1" : "0",
			    'FILE_FNAME'	=> ($srow['pfs_friendlyname'] == "") ? $srow['pfs_file'] : $srow['pfs_friendlyname'],
			    'FILE_NAME'		=> $srow['pfs_file'],
			    'FILE_URL'		=> $pfs_url,
			    'FILE_THUMBURL'	=> $thumb_url,
			    'FILE_SIZE'		=> $pfs_size,
			    'FILE_TYPE'		=> $srow['pfs_extension'],
			    'FILE_DESC'		=> $srow['pfs_desc']
			));
			$gmda->parse('MAIN.FILE_TABLE.FILE_ROW');	
		}
		$gmda->parse('MAIN.FILE_TABLE');
	} else {
		$message=$L['myfiles_folderistempty'];
	}
	
	$fpath='';
	if ($showfolderpath=='1') {
		$fpath=myfiles_getFolderPathText($folderid,0);
	}	
	
	$gmda-> assign(array(	'FILE_SHOWCOMPACT'		=> ($mode=='compact') ? '1':'0',
							'FILE_TOTALNUMBER'		=>	(string)$totalfiles,
							'FILE_SHOWFOLDERPATH'	=>	$showfolderpath,
							'FILE_FOLDERPATH'		=>	$fpath,
							'FILE_MESSAGE'			=> 	$message
						));
	$gmda->parse('MAIN');	
	return $gmda->text();
}
	
?>
