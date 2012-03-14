<?php
/* ====================
[BEGIN_COT_EXT]
Code=myfiles
Hooks=myfiles.header
Tags=
Order=10
[END_COT_EXT]
==================== */

//******************************
//*    Plugin:  Myfiles
//*    header Part
//******************************

defined('COT_CODE') or die('Wrong URL.');

require ($cfg['plugins_dir'].'/myfiles/cfg/constants.php');

if ($myFiles['cfg_loadrloader']=="Yes") {
//	$out['compopup'] .= "\n".'<script type="text/javascript" src="'.$cfg['plugins_dir'].'/myfiles/js/rloader.js"></script>';
	
    cot_rc_link_file('js/jquery.min.js');
    cot_rc_link_file('js/base.js');
    cot_rc_link_file($cfg['plugins_dir'].'/myfiles/js/rloader.js');

}

?>