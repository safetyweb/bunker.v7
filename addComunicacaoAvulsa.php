<?php
	
	//echo fnDebug('true');
 
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
			
			$cod_comunica = fnLimpaCampoZero($_REQUEST['COD_COMUNICA']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);

			$nom_comunica = fnLimpaCampo($_REQUEST['NOM_COMUNICA']);
			$abv_comunica = fnLimpaCampo($_REQUEST['ABV_COMUNICA']);
			$des_comunica = fnLimpaCampo($_REQUEST['DES_COMUNICA']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
			
                      
			if ($opcao != ''){
				
				//mensagem de retorno
				switch ($opcao){

					case 'CAD':

						$sql = "SELECT MAX(COD_LISTA) AS COD_LISTA FROM COMUNICAAV_PARAMETROS 
								WHERE COD_EMPRESA = $cod_empresa AND COD_USUCADA = $cod_usucada";

						// fnEscreve($sql);

						$qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

						$cod_lista = $qrCod['COD_LISTA'];

						$sql = "INSERT INTO COMUNICACAO_AVULSA(
												COD_EMPRESA,
												COD_LISTA,
												NOM_COMUNICA,
												ABV_COMUNICA,
												DES_COMUNICA,
												COD_USUCADA
								   	  		)VALUES( 
												$cod_empresa,
												$cod_lista,
												'$nom_comunica',
												'$abv_comunica',
												'$des_comunica',
												$cod_usucada
											)";
						// fnEscreve($sql);
						mysqli_query(connTemp($cod_empresa,''),$sql);

						$sqlCom = "SELECT MAX(COD_COMUNICA) AS COD_COMUNICA 
								FROM COMUNICACAO_AVULSA 
								WHERE COD_EMPRESA = $cod_empresa 
								AND COD_USUCADA = $cod_usucada";

						$qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCom));

						$cod_comunica = $qrCod['COD_COMUNICA'];

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

						break;

					case 'ALT':

						$sql = "UPDATE COMUNICACAO_AVULSA SET
										NOM_COMUNICA='$nom_comunica',
										ABV_COMUNICA='$abv_comunica',
										DES_COMUNICA='$des_comunica',
										DAT_ALTERAC=CONVERT_TZ(NOW(),'America/Sao_Paulo','America/Sao_Paulo'),
										COD_ALTERAC=$cod_usucada
								WHERE COD_COMUNICA=$cod_comunica";

						// fnEscreve($sql);
						mysqli_query(connTemp($cod_empresa,''),$sql);

						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";

						break;

					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
				}
				//atualiza lista iframe				
				?>
				<script>
					try { parent.$('#REFRESH_TEMPLATES').val("S");} catch(err) {}
				</script>						
				<?php			
				$msgTipo = 'alert-success';
			}                
		}
	}
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
            
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);

		if($cod_comunica == 0 || $cod_comunica == ""){
			$cod_comunica = fnlimpaCampoZero(fnDecode($_GET['idC']));	
		}

		$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;	
		
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {	
		$nom_empresa = "";
	}
	
	if ($cod_comunica != 0 || $cod_comunica != ""){

		// fnEscreve($cod_comunica);

		//busca dados do convênio
		$sql = "SELECT * FROM COMUNICACAO_AVULSA WHERE COD_COMUNICA = $cod_comunica";
		
		// fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
		$qrComunica = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrComunica)){
		    $cod_lista = $qrComunica['COD_LISTA'];
		    $log_lista = "S";
			$nom_comunica = $qrComunica['NOM_COMUNICA'];
			$abv_comunica = $qrComunica['ABV_COMUNICA'];
			$des_comunica = $qrComunica['DES_COMUNICA'];		
		}
		
	}else{
		$cod_lista = 0;
		$log_lista = "";
		$cod_comunica = "";
		$nom_comunica = "";
		$abv_comunica = "";
		$des_comunica = "";

	}

?>

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
	
		<?php if ($popUp != "true"){  ?>							
		<div class="push30"></div> 
		<?php } ?>
		
		<div class="row">				
		
			<div class="col-md12 margin-bottom-30">
				<!-- Portlet -->
				<?php if ($popUp != "true"){  ?>							
				<div class="portlet portlet-bordered">
				<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;" >
				<?php } ?>
				
					<?php if ($popUp != "true"){  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="glyphicon glyphicon-calendar"></i>
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
					<?php } ?>								
					
					<div class="portlet-body">
						
						<?php if ($msgRetorno <> '') { ?>	
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						 <?php echo $msgRetorno; ?>
						</div>
						<?php } ?>	
													
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
						<legend>Dados Gerais</legend> 
					
							<div class="row">
					
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_COMUNICA" id="COD_COMUNICA" value="<?php echo $cod_comunica ?>">
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									</div>														
								</div>           
					
								<div class="col-md-5">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome da Lista</label>
										<input type="text" class="form-control input-sm" name="NOM_COMUNICA" id="NOM_COMUNICA" value="<?php echo $nom_comunica ?>" maxlength="50">
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação da Lista</label>
										<input type="text" class="form-control input-sm" name="ABV_COMUNICA" id="ABV_COMUNICA" value="<?php echo $abv_comunica ?>" maxlength="20">
									</div>
									<div class="help-block with-errors"></div>
								</div>

							</div>

							<div class="row">
								
								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label">Descrição da Lista</label>
										<textarea type="text" class="form-control input-sm" rows="3" name="DES_COMUNICA" id="DES_COMUNICA" maxlength="200"><?php echo $des_comunica;?></textarea>
									</div>
									<div class="help-block with-errors"></div>
								</div>							
						
							</div>
							
							<div class="push10"></div>
							
					</fieldset>


					<?php 
						if($cod_lista == 0){ 
					?>

							<div class="push50"></div>

							<div class="row text-center wizard setup-panel">
								<div class="col-md-2 col-md-offset-3" id="step1">
									<div class="fundo inicio">
										<a type="button" class="btn btn-circle fundoAtivo disabled" id="btn1"><span>1</span></a>
									</div><br><br>
									<p>Importação</p>
								</div>
								<div class="col-md-2" id="step2">
									<div class="fundo">
										<a type="button" class="btn btn-circle disabled"><span>2</span></a>
									</div><br><br>
									<p>Sumário e Confirmação</p>
								</div>
								<div class="col-md-2" id="step3">
									<div class="fundo final">
										<a type="button" class="btn btn-circle disabled"><span class="fas fa-check fa-2x"></span></a>
									</div><br><br>
									<p>Concluído</p>
								</div>
							</div>

							<div class="container-fluid">

								<div class="passo" id="passo1">
									<div class="push100"></div>

									<div class="row">

										<div class="col-md-4"></div>

										<div class="col-md-4">
											<button type="button" class="btn btn-lg btn-default btn-block upload"><i class="fal fa-box-full" aria-hidden="true"></i>&nbsp; Importar Lista</button>
										</div>

									</div>

									<div class="push100"></div>

									<hr>

									<div class="col-md-8"></div>
									<div class="col-md-2">
										<button class="col-md-12 btn btn-default tmplt" name="tmplt"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp;&nbsp; Template</button>
										<script>
											$(".tmplt").click(function(e) {
												e.preventDefault();
												location.href = "http://adm.bunker.mk/media/clientes/7/Template_Importacao_Blacklist.xlsx";
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

					<?php 
						} 
					?>						
																
					<div class="push10"></div>
					<hr>	
					<div class="form-group text-right col-lg-12">
						
						  <!--<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>-->
						  <?php
							if($cod_comunica == 0 || $cod_comunica == ""){
								?>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button> 
								<?php
							}else{
								?>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
								<?php
							}
						  ?>
						  
						  <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
						
					</div>
					
					<input type="hidden" name="opcao" id="opcao" value="">
					<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
					<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
					<input type="hidden" name="FEZ_UPLOAD" id="FEZ_UPLOAD" value="N">
					<input type="hidden" name="IMPORTADO" id="IMPORTADO" value="<?=$log_lista?>" required>
					<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
					
					<div class="push5"></div> 
					
					</form>
					
					<div class="push50"></div>									
					
					<div class="push"></div>
					
					</div>								
				
				</div>
			</div>
			<!-- fim Portlet -->
		</div>
		
	</div>					
		
	<div class="push20"></div> 
	
	<script type="text/javascript">
	
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

			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();


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
        var nomeArquivo = $('#' + idField)[0].files[0]['name'];
		
		console.log($('#' + idField)[0].files[0]);

        formData.append('arquivo', $('#' + idField)[0].files[0]);
        formData.append('diretorio', '../media/clientes/');
        formData.append('id', <?php echo $cod_empresa ?>);
        formData.append('typeFile', typeFile);

        //capturando extensão do arquivo------------------
        var value = $('#' + idField).val(),
        file = value.toLowerCase(),
        ext = file.substring(file.lastIndexOf('.') + 1);
        //------------------------------------------------

        if(ext == 'xlsx'){		

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
	            url: '../uploads/uploadImportComunicacaoAvulsa.do?acao=gravar&id=<?php echo $cod_empresa; ?>',
	            type: 'POST',
	            data: formData,
	            processData: false,
	            contentType: false,
	            success: function (data) {
					console.log(data);
	                $('.jconfirm-open').fadeOut(300, function () {
	                    $(this).remove();
	                });

	                //alert(data);

	                var duplicados = data;
	                
	                    $.ajax({
							type: "POST",
							url: "../uploads/uploadImportComunicacaoAvulsa.do?acao=ler&id=<?php echo $cod_empresa; ?>",
							/*beforeSend:function(){
								$("#passo1").html('<div class="loading" style="width: 100%;"></div>');
							},*/
							success:function(data){	
								if($.isNumeric(duplicados)){
									$('#FEZ_UPLOAD').val('S');
									$('#passo1').hide();
									$('#passo2').show();
									$("#passo2").html(data);
									$("#step2 div.fundo, #step2 a.btn").addClass('fundoAtivo');

									$.alert({
				                        title: "Mensagem",
				                        content: "Upload feito com sucesso",
				                        type: 'green'
				                    });
									
									$('#NOM_ARQUIVO').val(nomeArquivo);
									$('#QTD_DUPLICADOS').val(duplicados);
								}else{
									$.alert({
				                        title: "Erro ao efetuar o upload",
				                        content: duplicados,
				                        type: 'red'
				                    });
								}
								 
							}						
						});
	                
	            }
	        });
	    }else{
	    	$.alert({
                title: "Erro ao efetuar o upload",
                content: 'Somente arquivos .xlsx são suportados.',
                type: 'red'
            });
	    }
    }
	
		function retornaForm(index){
			/*
			$("#formulario #COD_TEMPLATE").val($("#ret_COD_TEMPLATE_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
			$("#formulario #NOM_TEMPLATE").val($("#ret_NOM_TEMPLATE_"+index).val());
			$("#formulario #ABV_TEMPLATE").val($("#ret_ABV_TEMPLATE_"+index).val());
			$("#formulario #DES_TEMPLATE").val($("#ret_DES_TEMPLATE_"+index).val());
			if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);}else{$('#formulario #LOG_ATIVO').prop('checked', false);}
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');			
			*/
		}
		
	</script>	