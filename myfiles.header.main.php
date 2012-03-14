<?php
/* ====================
[BEGIN_COT_EXT]
Code=myfiles
Hooks=header.main
Tags=
Order=10
[END_COT_EXT]
==================== */

//******************************
//*    Plugin:  Myfiles
//*    header.main Part
//******************************

defined('COT_CODE') or die('Wrong URL.');

if ($location == "Plugins" && $e == 'myfiles'){

	require ($cfg['plugins_dir'].'/myfiles/cfg/constants.php');

	if ($myFiles['cfg_loadrloader']=="Yes") {
//		$out['compopup'] .= "\n".'<script type="text/javascript" src="'.$cfg['plugins_dir'].'/myfiles/js/rloader.js"></script>';

}	
}
?>