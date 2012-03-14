<?php
/* ====================
Cotonti - Website engine  http://www.cotonti.com
Author: Company: 2basix.nl

[BEGIN_COT_EXT]
Code=myfiles
Hooks=standalone
Tags=
Order=10
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL.');

$plugdir=$cfg['plugins_dir'].'/myfiles';

$outputmode="plugin";

require (cot_langfile('myfiles'));
require ($plugdir.'/cfg/config.php');
require ($plugdir.'/cfg/constants.php');

// get rights
list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('pfs', 'a');

require($plugdir."/inc/myfiles.browser.php");

?>