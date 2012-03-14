<!-- BEGIN: MAIN -->
	<script type="text/javascript">
		// Author: http://2basix.nl (Leo Lems)
		var language=[ "{PHP.L.myfiles_nofilename}", "{PHP.L.myfiles_busy}" ];
		var basepath="{MYFILES_BASEDIR}";
		var closewhenfinished="{MYFILES_CLOSEONFINISH}";
		var folder_action="edit";		
		// load the resources we need..  css will prevent browser caching.. for development purposes
		$.rloader([ {src:'{MYFILES_BASEDIR}/css/myfiles.css',cache:false},
					{src:'js/jquery.form.min.js'},
					{src:'js/jquery.blockui.min.js'},
					{src:'{MYFILES_BASEDIR}/js/myfiles.file.edit.js'},{event:'onready', func:'fileedit_initrload'}	]);		
	</script>
	<h4>{PHP.L.myfiles_title} - {PHP.L.myfiles_fileedit}</h4>
	<form id="fileedit" action="{FILE_UPLOAD_ACTION}" method="post">
		<table class="cells">
			<tr><th style="width:150px"></th><th></th></tr>
			<tr><td>{PHP.L.myfiles_filename}</td>
				<td><input id="frname" type="text" class="text" name="frname" value="{FILE_FRIENDLYNAME}" size="60" maxlength="255" /></td></tr>
			<tr><td>{PHP.L.myfiles_description}</td>
				<td><input id="fdescr" type="text" class="text" name="fdescr" value="{FILE_DESC}" size="60" maxlength="255" /></td></tr>
			<tr><td colspan="2" style="text-align:center;">
				<input type="submit" class="submit" value="{PHP.L.myfiles_update}" /><br /></td></tr>
		</table>
		<input type="hidden" name="fileid" value="{FILE_ID}" />
		<input type="hidden" class="text" name="selectedfolderid" value="{FOLDER_ID}" />
		<span id="icon"></span>&nbsp;&nbsp;<span id="result"></span>
	</form>
	<table class="cells">
		<tr><th></th><th style="width:150px"></th><th></th></tr>
		<tr><td colspan="3" style="background-color:#eee">{PHP.L.myfiles_fileinfo}</td></tr>
		<tr><td rowspan="4"><img style="margin:10px;" src="{FILE_THUMBURL}" /></td><td>{PHP.L.myfiles_filesize}</td><td>{FILE_SIZE} kb</td></tr>
		<tr><td>{PHP.L.myfiles_fileurl}</td><td><a href="{FILE_URL}">{FILE_FRIENDLYNAME}</a></td></tr>
		<tr><td>{PHP.L.myfiles_filedate}</td><td>{FILE_DATE}</td></tr>
		<tr><td>{PHP.L.myfiles_fileid}</td><td>{FILE_ID}</td></tr>
	</table>
<!-- END: MAIN -->