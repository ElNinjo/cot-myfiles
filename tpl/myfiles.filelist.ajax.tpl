<!-- BEGIN: MAIN -->
	<!-- ajax part -->
	<!-- IF {FILE_SHOWFOLDERPATH} == "1" -->
		<span style="margin:5px 0px; display:inline-block;"><span style="width:150px; display:inline-block;">{PHP.L.myfiles_path}</span>{FILE_FOLDERPATH}</span><br />
		<hr style="border-style: dashed; border-color: #ddd; border-width: 1px 0pt 0pt;">
	<!-- ENDIF -->
	<!-- BEGIN: FILE_TABLE -->
	<table class="tblfiles">
		<tr class="hdr">
			<!-- IF {FILE_SHOWCOMPACT} == "1" -->
				<th>&nbsp;</th>
				<!-- IF {FILE_SHOWTHUMBS} == "1" -->
					<th>{PHP.L.myfiles_filethumb}</th>
				<!-- ENDIF -->	
				<th>&nbsp;</th>
				<th>{PHP.L.myfiles_filename}</th>
				<th>{PHP.L.myfiles_filetype}</th>
				<th>{PHP.L.myfiles_filesize} (kb)</th>
				<th>{PHP.L.myfiles_description}</th>
			<!-- ELSE -->
				<!-- extended -->
				<th>&nbsp;</th>
				<!-- IF {FILE_SHOWTHUMBS} == "1" -->
					<th>{PHP.L.myfiles_filethumb}</th>
				<!-- ENDIF -->	
				<th>&nbsp;</th>
				<th>{PHP.L.myfiles_filename}</th>
				<th>{PHP.L.myfiles_filetype}</th>
				<th>{PHP.L.myfiles_filesize} (kb)</th>
				<th>{PHP.L.myfiles_description}</th>
			<!-- ENDIF -->
		</tr>
		<!-- BEGIN: FILE_ROW -->
			<tr>
				<!-- IF {FILE_SHOWCOMPACT} == "1" -->
					<td><input type="checkbox" name="fileselect" value="{FILE_ID}" /></td>
					<!-- IF {FILE_SHOWTHUMBS} == "1" -->
						<td><img src="{FILE_THUMBURL}" title="{FILE_FNAME}" /></td>
					<!-- ENDIF -->
					<!-- IF {FILE_SHOWEDITBTN} == "1" -->
						<td><a href="javascript:void(0)" title="{PHP.L.myfiles_fileedit}" onclick="files_edit('{FILE_ID}'); return false;"><img style="vertical-align:middle; border:none;" src="{PHP.myFiles.con_img_fileedit}" /></a>&nbsp;<a href="javascript:void(0)" title="{PHP.L.myfiles_filedelete}" onclick="files_delete('{FILE_ID}'); return false;"><img style="vertical-align:middle; border:none;" src="{PHP.myFiles.con_img_filedelete16}" /></a></td>
					<!-- ELSE -->
						<td>&nbsp;</td>
					<!-- ENDIF -->
					<td><a target="_blank" href="{FILE_URL}">{FILE_FNAME}</a></td>
					<td>{FILE_TYPE}</td>
					<td>{FILE_SIZE}</td>
					<td>{FILE_DESC}</td>
				<!-- ELSE -->
					<!-- extended -->
					<td><input type="checkbox" name="fileselect" value="{FILE_ID}" /></td>
					<!-- IF {FILE_SHOWTHUMBS} == "1" -->
						<td><img src="{FILE_THUMBURL}" title="{FILE_FNAME}" /></td>
					<!-- ENDIF -->
					<!-- IF {FILE_SHOWEDITBTN} == "1" -->
						<td><a href="javascript:void(0)" title="{PHP.L.myfiles_fileedit}" onclick="files_edit('{FILE_ID}'); return false;"><img style="vertical-align:middle; border:none;" src="{PHP.myFiles.con_img_fileedit}" /></a>&nbsp;<a href="javascript:void(0)" title="{PHP.L.myfiles_filedelete}" onclick="files_delete('{FILE_ID}'); return false;"><img style="vertical-align:middle; border:none;" src="{PHP.myFiles.con_img_filedelete16}" /></a></td>
					<!-- ELSE -->
						<td>&nbsp;</td>
					<!-- ENDIF -->
					<td><a target="_blank" href="{FILE_URL}">{FILE_FNAME}</a></td>
					<td>{FILE_TYPE}</td>
					<td>{FILE_SIZE}</td>
					<td>{FILE_DESC}</td>
				<!-- ENDIF -->
			</tr>
		<!-- END: FILE_ROW -->
	</table>
	<!-- END: FILE_TABLE -->

	<!-- IF {FILE_MESSAGE} -->
	<div class="filesmsg">
		{FILE_MESSAGE}
	</div>
	<!-- ENDIF -->

	<!-- ajax part end -->
<!-- END: MAIN -->