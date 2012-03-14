<!-- BEGIN: MAIN -->
	<script type="text/javascript">
		// load the resources we need..
		$.rloader([ {src:'{MYFILES_BASEDIR}/js/myfiles.storageinfo.js'},{event:'onready', func:'storage_initload'}	]);		
		if (myfiles===undefined) {	var myfiles={}; }
		myfiles.storageinfo_userid="{STORAGEINFO_USERID}"; 
	</script>

	<fieldset style="background-color:#eee; margin-bottom:10px">
		<legend>{PHP.L.myfiles_myfilesinfo}</legend>
		<div id="myfilesstoragecontainer">
			<!-- same as storageinfo.ajax.tpl -->
			{FILE "plugins/myfiles/tpl/myfiles.storageinfo.ajax_c.tpl"}
		</div>	
	</fieldset>
<!-- END: MAIN -->
