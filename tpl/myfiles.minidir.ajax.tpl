<!-- BEGIN: MAIN -->
	<!-- ajax part -->
	<script type="text/javascript">
		myfiles.mb_json_allfolderinfo={MINIDIR_SUBFOLDERINFO};
		myfiles.baseinfo[0]={MINIDIR_CURFOLDERID};
		myfiles.baseinfo[1]={MINIDIR_PREVFOLDERID};
		myfiles.pub_lastfldrselect="0";
		myfiles.s_pubstring={MINIDIR_PUBSTRING};
	</script>
	<!-- BEGIN: LINK_ITEM -->
		{MINIDIR_LINKSEP}<a href="javascript:folder_goto('{MINIDIR_FOLDERID}',true,true)" class="fldr_link" title="{MINIDIR_LINKTITLE}">{MINIDIR_LINKTEXT}</a>
	<!-- END: LINK_ITEM -->
	&nbsp;{MINIDIR_FOLDERSEP}&nbsp;{MINIDIR_FOLDERSELECT}&nbsp;&nbsp;
	<!-- IF {MINIDIR_FOLDERACTIONS}=="GO_IN" -->
		<span id="img_fgoin" style="display:none"><a href="javascript:void(0)" id="mb_btngoin" title="{PHP.L.myfiles_foldergoin}" onclick="folder_goto($('#minidir select[name=folderid]').val()); return false;"><img src="{PHP.myFiles.con_img_foldergoto}" /></a></span><span title="{PHP.L.myfiles_numbersubfolders}" id="fldrsubfolders"></span>
	<!-- ENDIF -->
	<!-- ajax part end -->
<!-- END: MAIN -->