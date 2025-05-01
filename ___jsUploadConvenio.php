<style>
.modalUpload {
    display: none;
    overflow: hidden;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1050;
    -webkit-overflow-scrolling: touch;
    outline: 0;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$("body").on("click", ".addUpload", function() {												
		var popLink = $(this).attr("data-url");
		var popTitle = $(this).attr("data-title");
		//alert(popLink);	
		setIframeModal(popLink, popTitle);
		$('.modalUpload').appendTo("body").modal('show');
	});	
});

function setIframeModal(src, title) {
	$(".modalUpload iframe").attr({
		'src': src
	});
	if (title) {
		$(".modal-title").text(title);
	} else {
		$(".modal-title").text("");
	}
}

function pre_upload(){
		if(!$('.upload').prop('disabled')){
	        var idField = 'arqUpload_' + $(this).attr('idinput');
	        var typeFile = $(this).attr('extensao');

	        $.dialog({
	            title: 'Arquivo',
	            content: '' +
	                    '<form method = "POST" enctype = "multipart/form-data">' +
	                    '<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
	                    '<div class="progress" style="display: none">' +
	                    '<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">'+
	                    '   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
	                    '</div>' +
	                    '<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
	                    '</form>'
	        });
    	}
    }

   function uploadFile(idField, typeFile) {
        var formData = new FormData();
        var nomeArquivo = $('#' + idField)[0].files[0]['name'];
        var extensao = nomeArquivo.substr( (nomeArquivo.lastIndexOf('.') +1) );
        var nomeFinal = <?=$cod_empresa;?>+"."+<?=$cod_conveni;?>+"."+<?=round(microtime(true));?>+"."+<?=$num_contador;?>+'.'+extensao;

        //alert(nomeFinal);

        formData.append('arquivo', $('#' + idField)[0].files[0]);
        formData.append('NOM_ARQUIVO', nomeFinal);
        formData.append('diretorio', '../media/clientes/');
        formData.append('diretorioAdicional', 'convenios/convenio.'+<?php echo $cod_conveni ?>);
        formData.append('id', <?php echo $cod_empresa ?>);
        formData.append('typeFile', typeFile);

        $('.progress').show();
        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                $('#btnUploadFile').addClass('disabled');
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        if (percentComplete !== 100) {
                            $('.progress-bar').css('width', percentComplete + "%");
                            $('.progress-bar > span').html(percentComplete + "%");
                        }
                    }
                }, false);
                return xhr;
            },
            url: '../uploads/uploaddocConvenio.php',
            type: 'POST',
            data: formData,
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
            success: function (data) {
                $('.jconfirm-open').fadeOut(300, function () {
                    $(this).remove();
                });
                if (!data.trim()) {
                    $('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
                    nom_referen = nomeArquivo,
                    tp_cont = '<?=$tp_cont?>',
                    tp_anexo = '<?=$tp_anexo?>',
                    cod_tpanexo = $('#<?=$cod_tpanexo?>').val(),
                    cod_empresa = <?php echo $cod_empresa ?>,
                    cod_conveni = <?php echo $cod_conveni ?>;
                    $.ajax({
                    	method: 'POST',
                    	url: 'ajxUploadConvenio.php',
                    	data: {NOM_ORIGEM:nomeFinal, NOM_REFEREN:nom_referen, COD_CONVENI:cod_conveni, TP_CONT:tp_cont, TP_ANEXO:tp_anexo, COD_TPANEXO:cod_tpanexo, COD_EMPRESA:cod_empresa},
                    	success:function(response){
                    		//console.log(response);
                    		$.alert({
		                        title: "Mensagem",
		                        content: "Upload feito com sucesso"+response,
		                        type: 'green'
		                    });
                            refreshUpload();
                    	},
                    	error:function(){
                    		$.alert({
		                        title: "Erro ao efetuar o upload",
		                        content: data,
		                        type: 'red'
		                    });
                    	}
                    });                    

                } else {
                    $.alert({
                        title: "Erro ao efetuar o upload",
                        content: data,
                        type: 'red'
                    });
                }
            }
        });
    }
</script>