<!-- BEGIN: MAIN -->
	<script type="text/javascript">
		// Author: http://2basix.nl (Leo Lems)

		var language=[ "{PHP.L.myfiles_err_folder_noname}", "{PHP.L.myfiles_busy}" ];
		var basepath="{MYFILES_BASEDIR}";
		var closewhenfinished="{MYFILES_CLOSEONFINISH}";
		var folder_action="edit";		
		
		// load the resources we need.. (on the fly)
		// css will prevent browser caching.. for development purposes
		$.rloader([ {src:'{MYFILES_BASEDIR}/css/myfiles.css',cache:false},
					{src:'js/jquery.form.min.js'},
					{src:'js/jquery.blockui.min.js'},
					{src:'{MYFILES_BASEDIR}/js/myfiles.folder.js',cache:false}		]);	
	</script>
	<!-- IF {FOLDER_BROWSER} == "1" -->
		<div id="minidir">{MYFILES_FOLDERSELECT}</div>
	<!-- ENDIF -->
	<h4>{PHP.L.myfiles_folderedit}</h4>
	<form id="folder" action="{FOLDER_UPLOAD_ACTION}" method="post">
		<!-- IF {FOLDER_BROWSER} == "0" -->
			{PHP.L.myfiles_path} : {FOLDER_PATHSTART}{FOLDER_PATH}<br /><br />
		<!-- ENDIF -->
		<table class="cells">
		<tr><td>{PHP.L.myfiles_foldername}</td>
			<td><input type="text" class="text" name="rtitle" value="{FOLDER_TITLE}" size="32" maxlength="255" /></td></tr>
		<tr><td>{PHP.L.myfiles_description}</td>
			<td><input type="text" class="text" name="rdesc" value="{FOLDER_DESCR}" size="32" maxlength="255" /></td></tr>
		<tr><td>{PHP.L.myfiles_ispublic}</td>
			<td><input type="radio" class="radio" name="rispublic" value="1" {FOLDER_PUBLIC_Y_CHECKED} />{PHP.L.myfiles_yes}<input type="radio" class="radio" name="rispublic" value="0" {FOLDER_PUBLIC_N_CHECKED} />{PHP.L.myfiles_no}</td></tr>
		<tr><td>{PHP.L.myfiles_isgallery}</td>
			<td><input type="radio" class="radio" name="risgallery" value="1" {FOLDER_GALLERY_Y_CHECKED} />{PHP.L.myfiles_yes}<input type="radio" class="radio" name="risgallery" value="0" {FOLDER_GALLERY_N_CHECKED} />{PHP.L.myfiles_no}</td></tr>
		<tr><td colspan="2" style="text-align:center;">
			<input type="submit" class="submit" value="{PHP.L.myfiles_update}" /></td></tr>
		</table>
		<input type="text" class="text hidden" name="selectedfolderid" value="{FOLDER_ID}" />
		<br />
		<span id="icon"></span>&nbsp;&nbsp;<span id="result"></span>
	</form>
	<script type="text/javascript">
		$('input[name=rtitle]').focus();						
	</script>
<!-- END: MAIN -->