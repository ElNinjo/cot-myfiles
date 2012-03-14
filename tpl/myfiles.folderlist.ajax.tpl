<!-- BEGIN: MAIN -->
	<!-- ajax part -->
	<script type="text/javascript">
		myfiles.mb_json_allfolderinfo={MINIDIR_SUBFOLDERINFO};
		myfiles.baseinfo[0]={MINIDIR_CURFOLDERID};
		myfiles.baseinfo[1]={MINIDIR_PREVFOLDERID};
	</script>

	<div id="mb_editcontrols">
		<!-- IF {MINIDIR_HIDEBTNS} != "1" -->
			<a href="javascript:void(0)" title="{PHP.L.myfiles_foldernew}" onclick="folderlist_new(); return false;"><span class="mb_fbtns"><img src="{PHP.myFiles.con_img_foldernew}" /> {PHP.L.myfiles_new}</span></a>
		<!-- ENDIF -->
		
		<!-- BEGIN: LINK_ITEM -->
			{MINIDIR_LINKSEP}<a href="javascript:folderlist_goto('{MINIDIR_FOLDERID}')" class="fldr_link" title="{MINIDIR_LINKTITLE}">{MINIDIR_LINKTEXT}</a>
		<!-- END: LINK_ITEM -->
		&nbsp;{MINIDIR_FOLDERSEP}
	</div>
	<!-- IF {MINIDIR_HASSUBFOLDERS} == "1" -->
		<div id="mb_folderlist">
			<table class="tblfiles">
				<tr class="hdr"><th>&nbsp;</th><th>{PHP.L.myfiles_foldername}</th><th>{PHP.L.myfiles_description}</th><th>{PHP.L.myfiles_lastchanged}</th><th>{PHP.L.myfiles_ispublic}</th><th>{PHP.L.myfiles_isgallery}</th></tr>
				<!-- BEGIN: DIRLIST_ITEM -->
					<tr id="flrow_{FLDR_ID}">
						<!-- IF {MINIDIR_HIDEBTNS} != "1" -->
							<td><a href="javascript:void(0)" title="{PHP.L.myfiles_folderdelete}" onclick="folderlist_delete('{FLDR_ID}'); return false;"><img src="{PHP.myFiles.con_img_folderdelete16}" /></a>
								<a href="javascript:void(0)" title="{PHP.L.myfiles_folderedit}" onclick="folderlist_edit('{FLDR_ID}'); return false;"><img src="{PHP.myFiles.con_img_folderedit16}" /></a>
							</td>
						<!-- ELSE -->
							<td> </td>
						<!-- ENDIF -->
						<td><a href="javascript:void(0)" onclick="folderlist_goto('{FLDR_ID}'); return false;">{FLDR_NAME}&nbsp;</a></td>
						<td>{FLDR_DESC}</td>
						<td>{FLDR_LASTCHANGE}</td>
						<td class="cnt">&nbsp;<!-- IF {FLDR_PUBLIC} == "1" --><img title="{PHP.L.myfiles_ispublic}" src="{PHP.myFiles.con_img_world16}" /> <!-- ENDIF --></td>
						<td class="cnt">&nbsp;<!-- IF {FLDR_IMAGE} == "1" --><img title="{PHP.L.myfiles_isgallery}" src="{PHP.myFiles.con_img_picture16}" /> <!-- ENDIF --></td>
					</tr>
				<!-- END: DIRLIST_ITEM -->
			</table>
		</div>	
	<!-- ELSE -->			
		&nbsp;
	<!-- ENDIF -->			
	<!-- ajax part end -->
<!-- END: MAIN -->
