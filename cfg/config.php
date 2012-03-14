<?php
/* ====================
	Configuration file for Myfiles plugin
	Leo Lems (ez) Company: 2basix.nl
==================== */

// folder separator character (database+UI) Only change this on installation !!!
$myFiles['cfg_pathsep']='/'; 

// You can Set this to NO if you ONLY use Myfiles..
// Yes means that Myfiles will look for folders created by regular pfs and make those compatible to myfiles
$myFiles['cfg_compatible']='Yes'; 

// set this to 'Yes' if rloader.js in NOT included in jquery.js
// for regular cotonti sites 'Yes' is the default
$myFiles['cfg_loadrloader']='Yes';

//=====================================================================
//limits
// folder path   root /aaa/bbb/ccc/
$myFiles['cfg_maxfolderdepth'] = 5;	

// maximum number of subfolders inside 1 folder
$myFiles['cfg_maxsubfolders'] = 25;	

//sharing
// make the root folders public 0=NO, 1=YES  (all root folders including the site)
$myFiles['cfg_public_root'] = "0";

// mime type and content checking (Is it realy a valid file check !!)
$myFiles['cfg_filecheck'] = TRUE;

// if no mimetype is defined (see contants.php).... is the file valid ?
// FALSE is safest
$myFiles['cfg_pfsnomimepass'] = FALSE;

// TTD: GD support :: http://www.php.net/manual/en/function.imagetypes.php
// GD library filetype support
$myFiles['cfg_gdsupported'] = array('jpg', 'jpeg', 'png', 'gif');

?>
