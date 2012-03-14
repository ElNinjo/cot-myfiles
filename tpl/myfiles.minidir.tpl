<!-- BEGIN: MAIN -->
	<script type="text/javascript">
		if (myfiles===undefined) {	var myfiles={}; }
		$.rloader([ {src:'{MYFILES_BASEDIR}/css/myfiles.css',cache:false},
					{src:'js/jquery.blockui.min.js'},
					{src:'{MYFILES_BASEDIR}/js/myfiles.minidir.js', cache:false},{event:'onready', func:'folder_initrload'}]);		
		myfiles.basepath="{MYFILES_BASEDIR}";
		// was "1"
		myfiles.baseinfo=["{MINIDIR_CURFOLDERID}", "{MINIDIR_PREVFOLDERID}", "{MINIDIR_HIDEBTNS}", "{MINIDIR_USERID}", {MINIDIR_MAXFOLDERDEPTH} ];
		myfiles.browsepublic=	"{MINIDIR_PUBLICFOLDER}";								
		myfiles.minidirlang=	[	"{PHP.L.myfiles_busy}", 
									"{PHP.L.myfiles_foldernew}",
									"{PHP.L.myfiles_isgallery}",
									"{PHP.L.myfiles_ispublic}",
									"{PHP.L.myfiles_yes}",
									"{PHP.L.myfiles_no}",
									"{PHP.L.myfiles_description}",
									"{PHP.L.myfiles_folderedit}"];
	</script>
	<div id="foldercontent">
		<fieldset style="background-color:#eee; margin-bottom:10px">
			<legend>{PHP.L.myfiles_folders}</legend>
			<!-- IF {MINIDIR_HIDEBTNS} != "1") -->
				<div id="mb_editcontrols">
					<a href="javascript:void(0)" id="mb_btnnew" title="{PHP.L.myfiles_foldernew}" onclick="folder_new(); return false;"><span id="img_fnew" class="mb_fbtns"><img src="{PHP.myFiles.con_img_foldernew}" /> {PHP.L.myfiles_new}</span></a><span id="img_fnew_grey" class="mb_fbtns" style="display:none"><img src="{PHP.myFiles.con_img_foldernewgrey}" /> {PHP.L.myfiles_new}</span>
					<a href="javascript:void(0)" id="mb_btnfedit" title="{PHP.L.myfiles_folderedit}" onclick="folder_edit(); return false;" style="display:none"><span id="img_fedit" class="mb_fbtns"><img src="{PHP.myFiles.con_img_folderedit}" /> {PHP.L.myfiles_edit}</span></a><span id="img_fedit_grey" class="mb_fbtns"><img src="{PHP.myFiles.con_img_foldereditgrey}" /> {PHP.L.myfiles_edit}</span>
					<a href="javascript:void(0)" id="mb_btnfdelete" title="{PHP.L.myfiles_folderdelete}" onclick="folder_delete(); return false;" style="display:none"><span id="img_fdelete" class="mb_fbtns"><img src="{PHP.myFiles.con_img_folderdelete}" /> {PHP.L.myfiles_delete}</span></a><span id="img_fdelete_grey" class="mb_fbtns"><img src="{PHP.myFiles.con_img_folderdeletegrey}" /> {PHP.L.myfiles_delete}</span>
					<label style="float:none; margin-left:10px;"><input type="checkbox" id="mb_foldershowinfo" value="ShowFolderInfo" /> {PHP.L.myfiles_showfolderinfo}</label>					
				</div>
			<!-- ENDIF -->
			<div id="mb_foldererror" style="display:none; padding:10px; margin:10px 0; color:red; border:red solid 1px"></div>
			<!-- IF {MINIDIR_SHOWUSERNAME} == "1" AND {MINIDIR_USERNAME} -->
				<div id="mb_admin_user">
					{PHP.L.Username} : <b>{MINIDIR_USERNAME}</b>
				</div>
			<!-- ENDIF -->
			<!-- IF {MINIDIR_PUBLICFOLDER} != "1" -->
				<div id="mb_ajx">
					{MINIDIR_AJAXPART}
				</div>
				<div id="mb_fldrinfopart" style="display:none;">
					<hr style="border-style: dashed; border-color: #ddd; border-width: 1px 0pt 0pt;">
					<div style="margin-left: 20px; float:left;">
							<span style="width:150px; display:inline-block">{PHP.L.myfiles_folderurl}</span> &nbsp; <a id="fldrurl" href="#" title="{PHP.L.myfiles_folderurldesc}"> </a><br />				
							<span style="width:150px; display:inline-block">{PHP.L.myfiles_description}</span> &nbsp; <span id="fldrdescription"></span> &nbsp; &nbsp; &nbsp; &nbsp; <span id="fldrflags"></span><br />
							<span style="width:150px; display:inline-block">{PHP.L.myfiles_lastchanged}</span> &nbsp; <span id="fldrlastchange"></span>
					</div>
				</div>	
			<!-- ENDIF -->
		</fieldset>
	</div>
<!-- END: MAIN -->