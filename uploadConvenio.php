<style>

.collapse-chevron .fa {
  transition: .3s transform ease-in-out;
}
.collapse-chevron .collapsed .fa {
  transform: rotate(-90deg);
}
	
.area {
  width: 100%;
  padding: 7px;
}

#dropZone {
  display: block;
  border: 2px dashed #bbb;
  -webkit-border-radius: 5px;
  border-radius: 5px;
  margin-left: -7px;
}

#dropZone p{
	font-size: 10pt;
	letter-spacing: -0.3pt;
	margin-bottom: 0px;
}

#dropzone .fa{
	font-size: 15pt;
}

.rowUpload{
	padding: 5px;
}

.kv-file-content{display:none !important;}
.file-thumbnail-footer{height:36px !important;}
.file-footer-caption div{float:left !important;}
.file-caption-info{text-align:left !important;}
.file-size-info,.file-size-info *{font-weight:bold !important;}
.file-thumb-progress{margin-top: -10px !important;}
.file-drop-zone{border: 2px dashed #bbb !important;}
.file-preview{border:0 !important;padding:0 !important;}
.file-caption-main {display: none !important;}
.file-drop-zone-title{
	padding: 10px!important;
}

</style>
<script type="text/javascript">

</script>

<div class="row rowUpload">

	<link href="js/plugins/fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
	<script src="js/plugins/fileinput/js/plugins/piexif.min.js" type="text/javascript"></script>
	<script src="js/plugins/fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
	<script src="js/plugins/fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
	<script src="js/plugins/fileinput/js/fileinput.js?v=100"></script>
	<script src="js/plugins/fileinput/js/locales/pt-BR.js"></script>

	<input
		id="fileinput"
		name="file[]"
		type="file"
		multiple
	>

	<script>
	$(document).ready(function() {
		$("#fileinput").fileinput({
			allowedFileExtensions: ['jpg','jpeg', 'png', 'gif', 'doc', 'docx', 'pdf', 'zip'],
			uploadUrl: "ajxUpload.php?tipo=<?=$tp_anexo?>&ido="+$("#COD_OBJETOANEXO").val()+"&tpc=<?=fnEncode($tp_cont)?>&cod_empresa=<?=$cod_empresa?>&cod_conveni=<?=$cod_conveni?>&num_contador=<?=$num_contador?>",
			uploadAsync: true,
			//deleteUrl: "/site/file-delete",
			showUpload: false, // hide upload button
			showRemove: false, // hide remove button
			overwriteInitial: false, // append files to initial preview
			minFileCount: 1,
			maxFileCount: 5,
			initialPreviewAsData: true,
			previewFileIcon: '',
			allowedPreviewTypes: null, // disable preview of standard types
			allowedPreviewMimeTypes: ['image/jpeg', 'text/javascript'], // allow content to be shown only for certain mime types 
			previewFileIconSettings: {}
		}).on("filebatchselected", function(event, files) {
			$("#fileinput").fileinput("upload");
		}).on('filebatchuploadcomplete', function(event, file, previewId, index, reader) {
			refreshUpload();
		});

		
		
		$(".file-drop-zone-title").html("<div class='row'>"
											+"<div class='col-sm-1'></div>"
											+"<div class='col-sm-2'>"
											+"	<a href='javascript:' onClick=\"$('#fileinput').trigger('click');\" class='btn btn-primary upload' style='margin-top:5px;'>"
											+"		<i class='fa fa-cloud-upload' aria-hidden='true'></i>"
											+"	</a>"
											+"</div>"
											+"<div class='col-sm-7 text-center'>"
											+"	<span>Upload de Arquivos (Arraste e solte)</span>"
											+"	<span class='help-block'>(Tamanho máximo de 20MB por anexo)</span>"
											+"</div>"
										+"</div>");
	});
	</script>
	<style>
	.file-upload-indicator,
	.file-footer-buttons,
	.file-drag-handle,
	#kvFileinputModal{
		display:none!important;
	}
	</style>
</div>

<div id="relatorioConteudo2"></div>


<script type="text/javascript">
	
	function refreshUpload(limpa){
		// alert();
		if (limpa == undefined){
			limpa = true;
		}
		if (limpa){
			$('#relatorioConteudo2').html("");
			obj = "#relatorioConteudo2";
		}else{
			obj = "#relatorioConteudo";
		}

		if ($("#relatorioConteudo2 .page").length <= 0){
			page = 1;
		}else{
			page = parseInt($("#relatorioConteudo2 .page").val())+1;
		}

		//alert($('#<?=$cod_tpanexo?>').val());
		$.ajax({
			method: 'POST',
			url: 'ajxAttUploadConvenio.php',
			data: {COD_BUSCA:$('#<?=$cod_tpanexo?>').val(), TP_ANEXO:'<?=$tp_anexo?>', COD_EMPRESA:'<?=$cod_empresa?>', COD_CONVENI:'<?=$cod_conveni?>', page: page, NUM_CONTADOR:<?=$num_contador?> },
			beforeSend:function(){
				if ($("#relatorioConteudo2 .load").length <= 0){
					$('#relatorioConteudo2').append('<div class="loading" style="width: 100%;"></div>');
				}else{
					$('#relatorioConteudo2 .load').append('<div class="loading" style="width: 100%;"></div>');
				}
			},
			success:function(data){
				$('#relatorioConteudo2 .loading').hide();
				console.log(data);
				$(obj).append(data);
				$("#relatorioConteudo2 .page").val(page);

				$("#fileinput").fileinput("destroy").fileinput({
					allowedFileExtensions: ['jpg','jpeg', 'png', 'gif', 'doc', 'docx', 'pdf', 'zip'],
					uploadUrl: "ajxUpload.php?tipo=<?=$tp_anexo?>&ido="+$("#COD_OBJETOANEXO").val()+"&tpc=<?=fnEncode($tp_cont)?>&cod_empresa=<?=$cod_empresa?>&cod_conveni=<?=$cod_conveni?>&num_contador=<?=$num_contador?>",
					uploadAsync: true,
					//deleteUrl: "/site/file-delete",
					showUpload: false, // hide upload button
					showRemove: false, // hide remove button
					overwriteInitial: false, // append files to initial preview
					minFileCount: 1,
					maxFileCount: 5,
					initialPreviewAsData: true,
					previewFileIcon: '',
					allowedPreviewTypes: null, // disable preview of standard types
					allowedPreviewMimeTypes: ['image/jpeg', 'text/javascript'], // allow content to be shown only for certain mime types 
					previewFileIconSettings: {}
				}).on("filebatchselected", function(event, files) {
					$("#fileinput").fileinput("upload");
				}).on('filebatchuploadcomplete', function(event, file, previewId, index, reader) {
					// refreshUpload();
				});
				
				$(".file-drop-zone-title").html("<div class='row'>"
													+"<div class='col-sm-1'></div>"
													+"<div class='col-sm-2'>"
													+"	<a href='javascript:' onClick=\"$('#fileinput').trigger('click');\" class='btn btn-primary upload' style='margin-top:5px;'>"
													+"		<i class='fa fa-cloud-upload' aria-hidden='true'></i>"
													+"	</a>"
													+"</div>"
													+"<div class='col-sm-7 text-center'>"
													+"	<span>Upload de Arquivos (Arraste e solte)</span>"
													+"	<span class='help-block'>(Tamanho máximo de 20MB por anexo)</span>"
													+"</div>"
												+"</div>");
			}
		});
	}
	
	$(document).ready(function(){
		$('.modal').on('hidden.bs.modal', function () {
			// refreshUpload();
			$('.modal-backdrop').fadeOut(1);
		});
	});


</script>