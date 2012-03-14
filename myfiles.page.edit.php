<?php
/* ====================
Cotonti - Website engine  http://www.cotonti.com
Author: Company: 2basix.nl

[BEGIN_COT_EXT]
Code=myfiles
Hooks=page.edit.tags
Tags=page.edit.tpl:{PLUGIN_MYFILES_JS},{PLUGIN_MYFILES_URLADDFILE},{PLUGIN_MYFILES_URLADDTEXT},{PLUGIN_MYFILES_BROWSER}
Order=10
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL.');

require_once(cot_langfile('myfiles'));
require_once($cfg['plugins_dir'].'/myfiles/cfg/config.php');
require_once($cfg['plugins_dir'].'/myfiles/cfg/constants.php');

$t-> assign(array(
	"PLUGIN_MYFILES_JS" => $myFiles['con_use_pageupdjs'],
	"PLUGIN_MYFILES_URLADDFILE" => $myFiles['con_btn_uploadfile'],
	"PLUGIN_MYFILES_BROWSER" => $myFiles['con_btn_selectfile'],
	"PLUGIN_MYFILES_URLADDTEXT" => $myFiles['con_btn_file2txt'] ));

?>