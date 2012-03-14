<?php
/* ====================
Cotonti - Website engine
http://www.cotonti.com
/* ====================

[BEGIN_COT_EXT]
Code=myfiles
Hooks=ajax
Tags=
Order=10
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL.');

$plugdir=$cfg['plugins_dir'].'/myfiles';

require(cot_langfile('myfiles'));
require($plugdir.'/cfg/config.php');
require($plugdir.'/cfg/constants.php');

// get rights
list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('pfs', 'a');

switch($m) {

	case 'filespart':
		require($plugdir."/inc/myfiles.filelist.ajax.php");
		break;
		
	case 'minidir':
	case 'folderlist':
		require($plugdir."/inc/myfiles.minidir.php");
		break;

	case 'minidirpart':
		require($plugdir."/inc/myfiles.minidir.ajax.php");
		break;

	// folder list mode (the folderlist)
	case 'folderlistpart':
		require($plugdir."/inc/myfiles.folderlist.ajax.php");
		break;
		
	case 'storageinfopart':
		// ajax update for the storage part
		require($plugdir."/inc/myfiles.storageinfo.php");
		break;
		
	case 'fileupload':
		if ($a=='submit') {
			// real upload ajax json part
			require($plugdir."/inc/myfiles.file.add.php");
		} else {
			require($plugdir."/inc/myfiles.file.add.screen.php");
		}
		break;

	case 'fileedit':
		if ($a=='submit') {
			// real upload ajax json part
			require($plugdir."/inc/myfiles.file.edit.php");
		} else {
			require($plugdir."/inc/myfiles.file.edit.screen.php");
		}
		break;
		
	case 'filedelete':
		require($plugdir."/inc/myfiles.file.delete.php");
		break;

	case 'filemove':
		if ($a=='move') {
			// real upload ajax json part
			require($plugdir."/inc/myfiles.file.move.php");
		} else {
			require($plugdir."/inc/myfiles.file.move.screen.php");
		}
		break;
	
	case 'folderadd':
		if ($a=='submit') {
			// real upload ajax json part
			require($plugdir."/inc/myfiles.folder.add.php");
		} else {
			require($plugdir."/inc/myfiles.folder.add.screen.php");
		}
		break;

	case 'folderedit':
		if ($a=='submit') {
			// real upload ajax json part
			require($plugdir."/inc/myfiles.folder.edit.php");
		} else {
			require($plugdir."/inc/myfiles.folder.edit.screen.php");
		}
		break;

	case 'folderdelete':
		// folder delete
		// only empty folders can be deleted
		require($plugdir."/inc/myfiles.folder.delete.php");
		break;
		
	case 'windowbrowser':
		$outputmode='window';
		require($plugdir."/inc/myfiles.browser.php");
		break;

	//case 'XXajx_browser_data':
		//require($plugdir."/inc/myfiles.browser.data.php");
		//break;

	//break;
}

?>