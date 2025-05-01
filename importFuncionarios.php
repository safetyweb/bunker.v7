<?php
	
	//echo "<h5>_".$opcao."</h5>";

	$hashLocal = mt_rand();	
	
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
		$request = md5( implode( $_POST ) );
		
		if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
		{
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		}
		else
		{
			$_SESSION['last_request']  = $request;

			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

			$opcao = $_REQUEST['opcao']; 
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){		
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}			
				$msgTipo = 'alert-success';
				
			}  	

		}
	}
      
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}

?>
			
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg." - ".$nom_empresa; ?></span>
									</div>
									
									<?php 
									$formBack = "1019";
									?>	

								</div>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>	
									
									<?php $abaEmpresa = 1025; ?>
									
									<div class="push30"></div>

<style>

	.leitura2{
		border: none transparent !important;
		outline: none !important;
		background: #fff !important;
		font-size: 18px;
		padding: 0;
	}

	.container-fluid .passo:not(:first-of-type){
		display: none;
	}

	.wizard .col-md-2{
		padding: 0;
	}

	.btn-circle {
		background-color:#DDD;
		opacity: 1 !important;
	    border:2px solid #efefef;    
	    height:55px;
	    width:55px;
	    margin-top: -23px;
	    padding-top: 11px;
	    border-radius:50%;
	    -moz-border-radius:50%;
	    -webkit-border-radius:50%;
	    color: #fff;
	    font-size: 20px;
	}

	.fa-2x{
		font-size: 19px;
		margin-top: 5px;
	}

	.collapse-chevron .fa {
	  transition: .3s transform ease-in-out;
	}

	.collapse-chevron .collapsed .fa {
	  transform: rotate(-90deg);
	}

	.pull-right,.pull-left{
		margin-top: 3.5px;
	}

	.fundo{
		background: #D3D3D3;
		height: 10px;
		width: 100%;
	}

	.fundoAtivo{
		background: #2ed4e0;
	}

	.inicio{
		background: #2ed4e0;
		border-bottom-left-radius: 10px 7px;
		border-top-left-radius: 10px 7px;
	}

	.final{
		border-bottom-right-radius: 10px 7px;
		border-top-right-radius: 10px 7px;
	}


</style>
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

											<div class="row text-center wizard setup-panel">
												<div class="col-md-4"></div>
												<div class="col-md-2" id="step1">
													<div class="fundo inicio">
														<a type="button" class="btn btn-circle fundoAtivo disabled" id="btn1"><span>1</span></a>
													</div><br><br>
											        <p>Importação</p>
											    </div>
												<div class="col-md-2" id="step2">
													<div class="fundo">
														<a type="button" class="btn btn-circle disabled"><span>2</span></a>
													</div><br><br>
											        <p>Concluído</p>
												</div>
											<!--<div class="col-md-2" id="step3">
													<div class="fundo">
														<a type="button" class="btn btn-circle disabled"><span>3</span></a>
													</div><br><br>
											        <p>Confirmação</p>
												</div>
												<div class="col-md-2" id="step4">
													<div class="fundo final">
														<a type="button" class="btn btn-circle disabled"><span class="fas fa-check fa-2x"></span></a>
													</div><br><br>
											        <p>Concluído</p>
												</div> -->
												<div class="col-md-4"></div>
											</div>

											<div class="container-fluid">
																				
												<div class="passo" id="passo1">
													<div class="push100"></div>

														<div class="row">

															<div class="col-md-4"></div>

															<div class="col-md-4">
																<button type="button" class="btn btn-lg btn-default btn-block upload"><i class="fal fa-box-full" aria-hidden="true"></i>&nbsp; Atualizar Clientes Funcionário</button>
															</div>

														</div>

													<div class="push100"></div>

													<hr>

													<div class="col-md-8"></div>
													<div class="col-md-2">
														<button class="col-md-12 btn btn-default tmplt" id="tmplt" name="tmplt"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp;&nbsp; Template</button>
														<script>
															$("#tmplt").click(function(e) {
																e.preventDefault();
															 	location.href = "https://adm.bunker.mk/media/clientes/Template_atualiza_Funcionario.xlsx";
															});
														</script>
													</div>
													<div class="col-md-2">
														<button class="col-md-12 btn btn-primary next next1" name="next">Próximo<i class="fas fa-arrow-right pull-right"></i></button>
													</div>														

													<div class="push10"></div>

												</div>

												<div class="passo" id="passo2"></div>

												<div class="passo" id="passo3"></div>

												<div class="passo" id="passo4"></div>

											</div>

																				
										<div class="push10"></div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
										<input type="hidden" name="FEZ_UPLOAD" id="FEZ_UPLOAD" value="N">
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 
	
	<script type="text/javascript">
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}

		$(document).ready(function(){			

			$('.next1').click(function(){

				if($('#FEZ_UPLOAD').val() == "N"){

					$.alert({
                        title: "Mensagem",
                        content: "Voce precisa importar a lista de produtos(.xlsx) para poder avançar.",
                    });

				}else{

					$('#passo1').hide();
					$('#passo2').show();
					$("#step2 div.fundo, #step2 a.btn").addClass('fundoAtivo');

				}
			});

		});

	$('.upload').on('click', function (e) {
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
    });
		
function uploadFile(idField, typeFile) {
    var formData = new FormData();

    if ($('#' + idField)[0].files.length > 0) {
        var nomeArquivo = $('#' + idField)[0].files[0]['name'];
    } else {
        $.alert({
            title: "Erro ao efetuar o upload",
            content: 'Nenhum arquivo selecionado.',
            type: 'red'
        });
        return;
    }

    formData.append('arquivo', $('#' + idField)[0].files[0]);
    formData.append('diretorio', '../media/clientes/');
    formData.append('id', <?php echo $cod_empresa ?>);
    formData.append('typeFile', typeFile);

    var value = $('#' + idField).val(),
        file = value.toLowerCase(),
        ext = file.substring(file.lastIndexOf('.') + 1);

    if(ext === 'xlsx'){        
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
            url: '../uploads/uploadAtualizaFunc.php?acao=gravar&id=<?php echo $cod_empresa; ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $('.jconfirm-open').fadeOut(300, function () {
                    $(this).remove();
                });

                var duplicados = data;

                $.ajax({
                    type: "POST",
                    url: "../uploads/uploadAtualizaFunc.php?acao=ler&id=<?php echo $cod_empresa; ?>",
                    success:function(data){    
                        var resu = duplicados.split(',');
                        if($.isNumeric(resu[0])){
                            $('#FEZ_UPLOAD').val('S');
                            $('#passo1').hide();
                            $('#passo2').show();
                            $("#passo2").html(data);
                            $("#step2 div.fundo, #step2 a.btn").addClass('fundoAtivo');

                            $.alert({
                                title: "Mensagem",
                                content: "Funcionário Atualizados com Sucesso",
                                type: 'green'
                            });
                            
                            $('#NOM_ARQUIVO').val(nomeArquivo);
                            $('#QTD_DUPLICADOS').val(resu[1]);
                            $('#QTD_LINHAS').val(resu[0]);
                            $('#QTD_SUCESS').val(resu[2]);
                        } else {
                            $.alert({
                                title: "Erro ao efetuar o upload",
                                content: duplicados,
                                type: 'red'
                            });
                        }
                         
                    }                        
                });
            },
            error:function(xhr, status, error){
                alert('Erro ao carregar...');
            },
            complete: function() {
                $('#btnUploadFile').removeClass('disabled');
                $('.progress-bar').css('width', "0%");
                $('.progress-bar > span').html("0%");
                $('.progress').hide();
            }
        });
    } else {
        $.alert({
            title: "Erro ao efetuar o upload",
            content: 'Somente arquivos .xlsx são suportados.',
            type: 'red'
        });
    }
}


	</script>	