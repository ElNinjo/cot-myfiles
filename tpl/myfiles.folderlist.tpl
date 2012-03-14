<!-- BEGIN: MAIN -->
<!--temporarily addition-->
<!--
	<script src="js/jquery.min.js" type="text/javascript"></script>
	<script src="js/jquery.form.min.js" type="text/javascript"></script>
	<script src="js/jquery.blockui.min.js" type="text/javascript"></script>
	<script src="js/rloader.js" type="text/javascript"></script>-->
	

	<script type="text/javascript">
		if (myfiles===undefined) {	var myfiles={}; }
		$.rloader([ {src:'{MYFILES_BASEDIR}/css/myfiles.css',cache:false},
					{src:'js/jquery.blockui.min.js'},
					{src:'{MYFILES_BASEDIR}/js/myfiles.folderlist.js'},{event:'onready', func:'folderlist_initrload'}]);		
		myfiles.basepath="{MYFILES_BASEDIR}";
		myfiles.baseinfo=["{DIRLIST_CURFOLDERID}", "{DIRLIST_PREVFOLDERID}", "{DIRLIST_HIDEBTNS}", "{DIRLIST_USERID}", {DIRLIST_MAXFOLDERDEPTH} ];
		myfiles.browsepublic=	"{DIRLIST_PUBLICFOLDER}";								
		myfiles.minidirlang=	[	"{PHP.L.myfiles_busy}", "{PHP.L.myfiles_foldernew}",
									"{PHP.L.myfiles_isgallery}", "{PHP.L.myfiles_ispublic}",
									"{PHP.L.myfiles_yes}", "{PHP.L.myfiles_no}",
									"{PHP.L.myfiles_description}", "{PHP.L.myfiles_folderedit}"		];
	</script>

	<div id="folderlistcontent">
		<fieldset style="background-color:#eee; margin-bottom:10px">
			<legend>{PHP.L.myfiles_folders}</legend>

			<div id="mb_foldererror" style="display:none; padding:10px; margin:10px 0; color:red; border:red solid 1px"></div>

			<!-- IF {DIRLIST_SHOWUSERNAME} == "1" AND {DIRLIST_USERNAME} -->
				<div id="mb_admin_user">
					{PHP.L.Username} : <b>{DIRLIST_USERNAME}</b>
				</div>
			<!-- ENDIF -->

			<!-- IF {DIRLIST_PUBLICFOLDER} != "1" -->
				<div id="mb_listajx">
					{DIRLIST_AJAXPART}
				</div>
			<!-- ENDIF -->
		</fieldset>
	</div>
<!-- END: MAIN -->