<!-- BEGIN: MAIN -->
	<script type="text/javascript">
		// Author: http://2basix.nl 
		if (fileadd===undefined) {	var fileadd={}; }
		fileadd.language=[	"{PHP.L.myfiles_nofileselected}",
						"{PHP.L.myfiles_succesfull}",
						"{PHP.L.myfiles_nofilename}",
						"{PHP.L.myfiles_uploadbusy}",
						"{PHP.L.myfiles_addthumb}",
						"{PHP.L.myfiles_addimg}",
						"{PHP.L.myfiles_addlink}",
						"{PHP.L.myfiles_uploadfailed}",
						"{PHP.L.myfiles_fileextnotallowed2}"];
		fileadd.maxuploads="{MYFILES_MAXUPLOAD}";
		fileadd.basepath="{MYFILES_BASEDIR}";
		fileadd.add_icons="{MYFILES_ADDICONS}";
		fileadd.quick_action="{MYFILES_QUICKACTION}";
		fileadd.formitems=["{MYFILES_ADDFORM}","{MYFILES_ADDFIELD}"];
		fileadd.fixfolderid="{MYFILES_ADDFOLDERID}";
		fileadd.allowedext={MYFILES_ALLOWEDEXT};
		fileadd.browseritem="{FOLDER_BROWSER}";
		fileadd.maxfile="{MYFILES_INFO_MAXFILE}";
		fileadd.maxstorage="{MYFILES_INFO_MAXSTORAGE}";
						
		// load the resources we need.. (on the fly)
		// css will prevent browser caching.. for development purposes
		$.rloader([ {src:'{MYFILES_BASEDIR}/css/myfiles.css'},
					{src:'{MYFILES_BASEDIR}/js/myfiles.file.add.js'},
					{src:'js/jquery.form.min.js'}			]);		
	</script>
	<!-- IF {FOLDER_BROWSER} == "1" -->
		<div id="minidir">{MYFILES_FOLDERSELECT}</div>
	<!-- ENDIF -->
	<fieldset style="background-color:#eee; margin-bottom:10px">
		<legend>{PHP.L.myfiles_upload}</legend>
		<!-- BEGIN: UPLOAD_ROW -->
			<div id="uploadslot{UPLOAD_ROWID}" class="uploadslot {UPLOAD_CLASS}">
				<form id="myfilesupload{UPLOAD_ROWID}" enctype="multipart/form-data" action="{UPLOAD_ROWACTION}" method="post">
					<input type="hidden" name="FORMID" value="{UPLOAD_ROWID}" />
					<input type="hidden" name="MAX_FILE_SIZE" value="{UPLOAD_ROWMAXFILE}" />
					<input type="hidden" id="folder_id" name="folder_id" value="0" />
					<!-- IF {FOLDER_BROWSER}=="0" -->
						{PHP.L.myfiles_path} : {FOLDER_PATHSTART}{FOLDER_PATH}<br /><br />
					<!-- ENDIF -->
					<span class="leftcol">{PHP.L.myfiles_file}:</span> <input id="userfilea" name="userfile" type="file" onChange="FileChange(this.value,'{UPLOAD_ROWID}');" class="file" size="24" />&nbsp;&nbsp;&nbsp;<span class='mf_small'>( {PHP.L.myfiles_max}: {MYFILES_INFO_MAXFILE} KB )</span><br />
					<span class="leftcol">{PHP.L.myfiles_filename}:</span> <input id="frname" type="text" class="text" name="frname" value="" size="60" maxlength="255" /><br />
					<span class="leftcol">{PHP.L.myfiles_description}:</span> <input id="fdescr" type="text" class="text" name="desc" value="" size="40" maxlength="255" /><br />
					<input type="submit" id="submit{UPLOAD_ROWID}" title="{PHP.L.myfiles_upload}" value="{PHP.L.myfiles_upload}" style="margin-left:400px" />
					<span id="icon{UPLOAD_ROWID}"></span>&nbsp;&nbsp;<span id="result{UPLOAD_ROWID}"></span>
				</form>
			</div>
		<!-- END: UPLOAD_ROW -->
		<!-- BEGIN: UPLOAD_SMALLSLOT -->
			<div id="smallslot{UPLOAD_ROWID}" class="hidden">
				<input type="button" value="{PHP.L.myfiles_cancel}" onclick="upload_abort({UPLOAD_ROWID});"> <img title="{PHP.L.myfiles_busy}" style="text-align:baseline;" src="{MYFILES_BASEDIR}/img/ajxldr_small.gif"> &nbsp; <span id="uploadfilename">&nbsp;</span><br />				
			</div>
		<!-- END: UPLOAD_SMALLSLOT -->
	</fieldset>
	<fieldset style="background-color:#eee; margin-bottom:10px;">
		<legend>{PHP.L.myfiles_uploadresults}</legend>

		<div id="output2" style="overflow: auto; min-height: 10px; max-height: 200px;"></div>
	</fieldset>
	
<!-- END: MAIN -->