<!-- BEGIN: MAIN -->
	<script type="text/javascript">
		$.rloader([ {src:'{MYFILES_BASEDIR}/css/myfiles.css'},
					{src:'js/jquery.blockui.min.js'},
					{src:'{MYFILES_BASEDIR}/js/myfiles.filelist.js'},{event:'onready', func:'files_initrload'}]);		
		if (myfiles===undefined) {	var myfiles={}; }
		myfiles.filesfolder="{FILES_CURFOLDERID}";
		myfiles.filesloadingid="{FILES_CURFOLDERID}";
		myfiles.filesuserid="{FILES_USERID}";
		myfiles.fileslang=	["{PHP.L.myfiles_busy}","{PHP.L.myfiles_loading}","{PHP.L.myfiles_waiting}","{PHP.L.myfiles_q_suretodelete}","{PHP.L.myfiles_err_nofileselected}"];
		{PHP.myFiles.con_use_fileupload}
		{PHP.myFiles.con_use_fileedit}
	</script>
	<div id="filescontent">
		<fieldset style="background-color:#eee; margin-bottom:10px">
			<legend>{PHP.L.myfiles_files}</legend>
			<div id="mb_fileserror" style="display:none; padding:10px; margin:10px 0; color:red; border:red solid 1px"></div>
			<div id="mb_editcontrols">
				<!-- IF {FILES_SHOWEDITBTNS} == "1" -->
					<a href="javascript:void(0)" id="mb_btnnewfile" title="{PHP.L.myfiles_filenew}" onclick="files_new(); return false;"><span class="mb_filebtns"><span id="img_filenew"><img src="{PHP.myFiles.con_img_filenew}" /> {PHP.L.myfiles_new}</span></span></a>
					<a href="javascript:void(0)" id="mb_btnfdeletefile" title="{PHP.L.myfiles_filedelete}" onclick="files_delete(); return false;"><span class="mb_filebtns"><span id="img_filedelete"><img src="{PHP.myFiles.con_img_filedelete}" /> {PHP.L.myfiles_delete}</span></span></a>
					<a href="javascript:void(0)" id="mb_btnfmovefile" title="{PHP.L.myfiles_filemove}" onclick="files_move_screen(); return false;"><span class="mb_filebtns"><span id="img_filemove"><img src="{PHP.myFiles.con_img_move}" /> {PHP.L.myfiles_move}</span></span></a>
				<!-- ENDIF -->
				<a href="javascript:void(0)" id="mb_btnfrefresh" title="{PHP.L.myfiles_filerefresh}" onclick="files_refresh(); return false;"><span class="mb_filebtns"><span id="img_filerefresh"><img src="{PHP.myFiles.con_img_refresh}" /> {PHP.L.myfiles_refresh}</span></span></a>
				<input type="checkbox" id="mb_filesshowtn" value="ShowTN" /> {PHP.L.myfiles_showtn}
			</div>
			<div id="files_ajx">
				{FILES_AJAXPART}
			</div>
		</fieldset>
	</div>
<!-- END: MAIN -->