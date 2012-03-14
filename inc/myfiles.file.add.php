<?php
/* ====================
 Cotonti - Website engine
 * Myfiles plugin
 * Author: 2basix.nl (ez)
 * BSD License
 ===================================*/

/* =================================================================
 * Checks a file to be sure it is valid
 *
 * @param string $path File path
 * @param string $name File name
 * @param string $ext File extension
 * @return bool
 =================================================================== */
function Myfiles_file_check($path, $name, $ext)
{
	global $myFiles;
	
	if($myFiles['cfg_filecheck'])
	{
		$fcheck = FALSE;
		if(in_array($ext, $myFiles['cfg_gdsupported']))
		{
			switch($ext)
			{
				case 'gif':
					$fcheck = @imagecreatefromgif($path);
				break;
				case 'png':
					$fcheck = @imagecreatefrompng($path);
				break;
				default:
					$fcheck = @imagecreatefromjpeg($path);
				break;
			}
			$fcheck = $fcheck !== FALSE;
		}
		else
		{
			if(!empty($myFiles['mimetype'][$ext]))
			{
				foreach($myFiles['mimetype'][$ext] as $mime)
				{
					$content = file_get_contents($path, 0, NULL, $mime[3], $mime[4]);
					$content = ($mime[2]) ? bin2hex($content) : $content;
					$mime[1] = ($mime[2]) ? strtolower($mime[1]) : $mime[1];
					$i++;
					if ($content == $mime[1])
					{
						$fcheck = TRUE;
						break;
					}
				}
			}
			else
			{
				$fcheck = ($myFiles['cfg_pfsnomimepass']) ? 1 : 2;
			}
		}
	}
	else
	{
		$fcheck = true;
	}
	return($fcheck);
}

/* ============================================================================
						AJAX upload part with JSON return
============================================================================== */	

if (!defined('COT_CODE')) { 
	echo '{"status":"error","message":"Wrong URL"}';
	exit;
}
if (!$usr['auth_write']) {
	echo '{"status":"error", "message":"'.$L['myfiles_err_nowrite'].'"}';
	exit;
}

//=================================
// userid comes from GET always !!!
// if its empty assume the current user
//=================================
$userid = cot_import('userid','G','TXT');
if ($userid=='') {
	// hmm this could even be userid = 0  (so guests)
	$userid=$usr['id'];
	if ($userid=='0') {
		// guests do not have personal files !!!!!!  (Sitefiles ??????? are for guests ????)
		echo '{"status":"error", "message":"'.$L['myfiles_err_noaccess'].' [User]"}';
		exit;
	}
}
if ($usr['id']!=$userid & !$usr['isadmin']) {
	// other then your own userid and not an admin
	echo '{"status":"error", "message":"'.$L['myfiles_err_noaccess'].' [User]"}';
	exit;
}

$disp_errors_ajax='';
//added path to function cot_userinfo
require_once cot_incfile('pfs', 'module');
$user_info = cot_userinfo($userid);
$maingroup = ($userid==0) ? 5 : $user_info['user_maingrp'];
$cfg['pfs_dir_user'] = cot_pfs_path($userid);
$cfg['th_dir_user'] = cot_pfs_thumbpath($userid);

//added path to function cot_get_uploadmax
require_once cot_incfile('uploads');

$sql = $db->query("SELECT grp_pfs_maxfile, grp_pfs_maxtotal FROM $db_groups WHERE grp_id='$maingroup'");
if ($row = $sql->fetch())
{
	$maxfile = min($row['grp_pfs_maxfile'], cot_get_uploadmax());
	$maxtotal = $row['grp_pfs_maxtotal'];
	if (($maxfile==0 || $maxtotal==0) && !$usr['isadmin']) {
		//================================================
		// TTD: other error message
		//================================================
		echo '{"status":"error", "message":"'.$L['myfiles_err_noaccess'].' [Sizes]"}';
		exit;
	}
} else { 
	//================================================
	// TTD: other error message
	//================================================
	echo '{"status":"error", "message":"'.$L['myfiles_err_noaccess'].' [User|UserGroup]"}';
	exit;
}

$sql = $db->query("SELECT SUM(pfs_size) FROM $db_pfs WHERE pfs_userid='$userid' ");
$pfs_totalsize = $sql->fetchColumn();

$folderid = cot_import('folder_id','P','INT');
$formid = cot_import('FORMID','P','TXT');
$desc = cot_import('desc','P','TXT');
$frname = cot_import('frname','P','TXT');
$formid = cot_import('FORMID','P','INT');
$folder_name = "/";

if ($folderid==-1) {
	$folderid=0;
}

if ($folderid!=0)
{
	$sql = $db->query("SELECT pff_id, pff_title FROM $db_pfs_folders WHERE pff_userid='$userid' AND pff_id='$folderid' ");
	if ($sql->rowCount()==0) {
		echo '{"status":"error", "message":"'.$L['myfiles_err_nofolder'].'"}';
		exit;
	}	
	$frow = $sql->fetch();
	$folder_name = $frow['pff_title'];
}

$disp_errors_ajax = '{';
// hook predefines (here loads faster when using multiple uploads. Myfiles is using single uploads !!!)
$extpum = cot_getextplugins('pfs.upload.moved');
$extpud = cot_getextplugins('pfs.upload.done');

$u_tmp_name = 	$_FILES['userfile']['tmp_name'];
//$u_type = 		$_FILES['userfile']['type'];			// mimetype
$u_name = 		$_FILES['userfile']['name'];
$u_size = 		$_FILES['userfile']['size'];

// remove quotes
$u_name  = str_replace("\'",'',$u_name );
$u_name  = trim(str_replace("\"",'',$u_name ));

if (!empty($u_name))
{
	if ($disp_errors_ajax!="{" && substr(trim($disp_errors_ajax),-1,1)!=",") { $disp_errors_ajax.=","; };
	$disp_errors_ajax .= ' "file": { "id":"'.$formid.'", "folderid":"'.$folderid.'", "foldername" : "'.$folder_name.'", "filename" : "'.$u_name.'", ';

	$u_name = mb_strtolower($u_name);
	$dotpos = mb_strrpos($u_name,".")+1;
	$f_extension = mb_substr($u_name, $dotpos);
	$f_extension_ok = 0;

	
	$u_newname = cot_safename($u_name, true, '_'.$userid);
	$u_sqlname = $db->prep($u_newname);
	if ($frname=="") {
		$frname=$u_name;	//original name
	}

	if ($f_extension!='php' && $f_extension!='php3' && $f_extension!='php4' && $f_extension!='php5') {
		foreach ($myFiles['extensions'] as $k => $line) {
			if (mb_strtolower($f_extension) == $line[0]) {
				$f_extension_ok = 1;
				break;
			}
		}
	}

	if (is_uploaded_file($u_tmp_name) && $u_size>0 && $u_size<($maxfile*1024) && $f_extension_ok && ($pfs_totalsize+$u_size)<$maxtotal*1024   )
	{
		// here the images and the mimetypes are tested !!!
		// image testing should be a config setting ???? (is slow when dealing with large images)
		// also the config setting for mimetype checking is done in this function
		$fcheck = Myfiles_file_check($u_tmp_name, $u_name, $f_extension);
		if($fcheck == 1)
		{
			$tfile=$cfg['pfs_dir_user'].$u_newname;
			// check if file exists... in theory this cannot happen because use a id prefix
			// this happens if the file was uploaded using old pfs !!!
			if (!file_exists($tfile))
			{
				$is_moved = true;
				// $cfg['pfsuserfolder'] == "1" or "0"
				// Ok, here is the user folder thing.... hmmm
				if ($cfg['pfsuserfolder'])
				{
					if (!is_dir($cfg['pfs_dir_user']))
					{ $is_moved &= mkdir($cfg['pfs_dir_user'], $cfg['dir_perms']); }
					if (!is_dir($cfg['th_dir_user']))
					{ $is_moved &= mkdir($cfg['th_dir_user'], $cfg['dir_perms']); }
				}

				$is_moved &= move_uploaded_file($u_tmp_name, $tfile);
				$is_moved &= chmod($tfile, $cfg['file_perms']);
				$u_size = filesize($tfile);

				if ($is_moved && (int)$u_size > 0)
				{
					/* === Hook upload moved === */
					if (is_array($extpum))
					{ foreach($extpum as $k => $pl) { include($cfg['plugins_dir'].'/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
					/* ===== */

					// we will use a record id as a filename prefix, BUT we do not have it until we add a record into
					// this database first. So we must make two statements for this in the first statement we leave the filename empty
					// new fields are pfs_friendlyname and pfs_usrfolder
					$sql = $db->query("INSERT INTO $db_pfs
					(pfs_userid,
					pfs_date,
					pfs_file,
					pfs_extension,
					pfs_folderid,
					pfs_desc,
					pfs_size,
					pfs_count,
					pfs_friendlyname,
					pfs_usrfolder)
					VALUES
					(".(int)$userid.",
					".(int)$sys['now_offset'].",
					'',
					'',
					".(int)$folderid.",
					'".$db->prep($desc)."',
					".(int)$u_size.",
					0,
					'".$db->prep($frname)."',
					'".$cfg['pfsuserfolder']."') ");
					
					$newrowid=$db->lastInsertId();
					
					// rename the file with a rowid prefix!!!
					$u_newname=$newrowid."_".$u_newname;
					$u_sqlname = $db->prep($u_newname);
					if (rename($tfile, $cfg['pfs_dir_user'].$u_newname)) {
						$tfile=$cfg['pfs_dir_user'].$u_newname;
						
						$sql = $db->query("UPDATE $db_pfs SET
							pfs_file='".$db->prep($u_sqlname)."',
							pfs_extension='".$db->prep($f_extension)."'
							WHERE pfs_userid='$userid' AND pfs_id='$newrowid'");

						$sql = $db->query("UPDATE $db_pfs_folders SET pff_updated='".$sys['now']."' WHERE pff_id='$folderid'");
						$disp_errors_ajax .= '"status":"ok","pfsfile":"'.$cfg['pfs_dir_user'].$u_sqlname.'","friendlyname":"'.$frname.'"';
						$pfs_totalsize += $u_size;

						/* === Hook Upload done === */
						if (is_array($extpud))
						{ foreach($extpud as $k => $pl) { include($cfg['plugins_dir'].'/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
						/* ===== */

						/* OK here is the thumbnail stuff */
						if (in_array($f_extension, $myFiles['cfg_gdsupported']) && $cfg['th_amode']!='Disabled' && file_exists($cfg['pfs_dir_user'].$u_newname))
						{
							@unlink($cfg['th_dir_user'].$u_newname);
							$th_colortext = array(hexdec(substr($cfg['th_colortext'],0,2)), hexdec(substr($cfg['th_colortext'],2,2)), hexdec(substr($cfg['th_colortext'],4,2)));
							$th_colorbg = array(hexdec(substr($cfg['th_colorbg'],0,2)), hexdec(substr($cfg['th_colorbg'],2,2)), hexdec(substr($cfg['th_colorbg'],4,2)));
//							cot_createthumb($cfg['pfs_dir_user'].$u_newname, $cfg['th_dir_user'].$u_newname, $cfg['th_x'],$cfg['th_y'], $cfg['th_keepratio'], $f_extension, $u_newname, floor($u_size/1024), $th_colortext, $cfg['th_textsize'], $th_colorbg, $cfg['th_border'], $cfg['th_jpeg_quality'], $cfg['th_dimpriority']);
							
							cot_imageresize($cfg['pfs_dir_user'].$u_newname, $cfg['th_dir_user'].$u_newname,
									$cfg['pfs']['th_x'], $cfg['pfs']['th_y'], 'fit',

								   '', 90, true
								);
//							cot_imageresize($cfg['pfs_dir_user'].$u_newname, $thumbs_dir_user  . $u_newname,
//
//								$cfg['pfs']['th_x'], $cfg['pfs']['th_y'], 'fit',
//								'', 90, true);
							$disp_errors_ajax .= ',"thumbfile":"'.$cfg['th_dir_user'].$u_newname.'"';
						}
					} else {
						// renaming with rowid prefix has failed
						// remove files
						@unlink($tfile);
						@unlink($cfg['pfs_dir_user'].$u_newname);
						// clean up database entry
						$sql = $db->query("DELETE FROM $db_pfs WHERE pfs_userid='$userid' AND pfs_id='$newrowid'");
						$disp_errors_ajax .= ' "status":"error", "message":"'.$L['myfiles_filerenamefailed'].'"';
					}
				}
				else
				{
					@unlink($tfile);
					$disp_errors_ajax .= ' "status":"error", "message":"'.$L['myfiles_filenotmoved'].'"';
				}
			} else {
				// this can never happen because of the id prefix, but leave it for now
				$disp_errors_ajax .= ' "status":"error", "message":"'.$L['myfiles_fileexists'].'"';
			}
		}
		elseif($fcheck == 2)
		{
			$disp_errors_ajax .= ' "status":"error", "message":"'.sprintf($L['myfiles_filemimemissing'], $f_extension).'"';
		}
		else
		{
			$disp_errors_ajax .= ' "status":"error", "message":"'.sprintf($L['myfiles_filenotvalid'], $f_extension).'"';
		}
	}
	else
	{
		// additional errors included
		if ($u_size>=($maxfile*1024)) {
			$disp_errors_ajax .= ' "status":"error", "message":"'.$L['myfiles_filetoobig'].'"';
		} elseif ($f_extension_ok == 0) {
			$disp_errors_ajax .= ' "status":"error", "message":"'.$L['myfiles_fileextnotallowed'].'"';
		} else {
				if (($pfs_totalsize+$u_size)>=$maxtotal*1024) {
					$disp_errors_ajax .= ' "status":"error", "message":"'.$L['myfiles_filetoobigmaxspace'].'"';
				} else {
					// size = 0 ????  (upload failed)
					$disp_errors_ajax .= ' "status":"error", "message":"'.$L['myfiles_fileuploadfailed'].'"';
				}
		}
	}
	$disp_errors_ajax .= '}';
}

if (substr(trim($disp_errors_ajax),-1,1)==',') { $disp_errors_ajax=substr(trim($disp_errors_ajax),0,-1); };
$disp_errors_ajax .= '}';

// In ajax mode, return results...
echo $disp_errors_ajax;
return;

?>
