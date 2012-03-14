<!-- BEGIN: MAIN -->
	<script type="text/javascript">
		// Author: http://2basix.nl (Leo Lems)
		$.rloader([ {src:'{MYFILES_BASEDIR}/css/myfiles.css',cache:false} ]);
		function folder_movefiles_trigger() {
			if (opener!==null && !window.opener.closed) {
				try { 	window.opener.$('body').trigger('myfiles_folder_movefiles',myfiles.baseinfo[0]);
						window.close();}
				catch (err) {}
			}
		}
	</script>
	<h4>{PHP.L.myfiles_title} - {PHP.L.myfiles_filemove}</h4>
	{FOLDER_SELECTOR}
	<div style="float:right"><input type="button" style="padding:5px; font-weight:bold;" value="{PHP.L.myfiles_cancel}" onclick="window.close()" /><input type="button" style="padding:5px; font-weight:bold;" value="{PHP.L.myfiles_save}" onclick="folder_movefiles_trigger()" /></div>
	
<!-- END: MAIN -->