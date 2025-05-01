<?php

	include '_system/functionwhatsapp.php'; 
	
	//echo "<h5>_".$opcao."</h5>";

	$hashLocal = mt_rand();
	// definir o numero de itens por pagina
	$itens_por_pagina = 50;	
	$pagina  = "1";
	
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
			$cod_lista = fnLimpaCampoZero($_REQUEST['COD_LISTA']);
			$des_mensagem = $_REQUEST['DES_MENSAGEM'];
			$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

			// fnEscreve($des_mensagem);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){				
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "UPDATE COMUNICAAV_PARAMETROS SET 
									   DES_MENSAGEM = '$des_mensagem',
									   COD_USUCADMSG = $cod_usucada,
									   DAT_CADMSG = NOW()
								WHERE COD_EMPRESA = $cod_empresa 
								AND COD_LISTA = $cod_lista";

						// fnEscreve($sql);

						mysqli_query(connTemp($cod_empresa,''),$sql);

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
		$cod_lista = fnDecode($_GET['idL']);

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
		$cod_lista = 0;	
		//fnEscreve('entrou else');
	}

	$sqlconfg = "SELECT * FROM CONFIGURACAO_ACESSO WHERE COD_EMPRESA = $cod_empresa AND COD_PARCOMU = 13 AND LOG_STATUS = 'S'";
	$arrayConfig = mysqli_query($connAdm->connAdm(), $sqlconfg);

	$rsconfig = mysqli_fetch_assoc($arrayConfig);

	$temConfig = mysqli_num_rows($arrayConfig);

	// FNeSCREVE($temConfig);

	if($temConfig > 0){
	
		$arraydados = array('conadmin'=> $connAdm->connAdm(),
			'conempresa' => connTemp($cod_empresa,''),
			'cod_empresa' => $cod_empresa,
			'url' => $rsconfig['DES_EMAILUS'],
			'Authorization' => $rsconfig['DES_AUTHKEY']
		);

		$retorno = fnstatuswhatsapp($arraydados);
		// $teste=fnqrcodwhatsapp($arraydados);
		// $retorno = fnreloadwhatsapp($arraydados);

		// echo "<pre>";
		// print_r($retorno);
		// echo "</pre>";

		if($retorno['connected']){
			$msgStatus = "<span class='f21'>Conexão <b>ativa</b></span>.";
			$msgStatusTipo = "alert-success";
		}else{
			$msgStatus = "<span class='f21'>Não conectado. <a href='javascript:void(0)' onclick='geraQrCode()' style='color: white; text-decoration: underline;'>Clique aqui</a> para gerar o <i>QrCode</i> de conexão.</span>";
			$msgStatusTipo = "alert-danger";
		}

	}else{
		$msgStatus = "<span class='f21'>TESTE <a href='javascript:void(0)' onclick='geraQrCode()' style='color: white; text-decoration: underline;'>Clique aqui</a> para gerar o <i>QrCode</i> de conexão.</span>";
		$msgStatusTipo = "alert-danger";
	}

	$sql = "SELECT DES_MENSAGEM FROM COMUNICAAV_PARAMETROS WHERE COD_LISTA = $cod_lista";
	$qrMsg = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

	$des_mensagem = $qrMsg['DES_MENSAGEM'];
	
	//fnMostraForm();

?>
			
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									
									<?php 
									$formBack = "1019";
									include "atalhosPortlet.php"; 
									?>	

								</div>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<div class="push10"></div>
									<?php } ?>

									<?php if ($msgStatus <> '') { ?>	
									<div class="alert <?php echo $msgStatusTipo; ?> top30 bottom30" role="alert" id="msgStatus">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgStatus; ?>
									</div>
									<?php } ?>

									<div class="row">
										<div class="col-md-12 text-center">
											<div id="qrCode"></div>
										</div>
									</div>
									
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

											<fieldset>
												<legend>Banco de Variáveis <small>( <b>Clique e arraste</b> uma ou mais tags na área desejada )</small> </legend> 

												<div class="row">

													<div class="col-md-12">

														<?php

	                    									//fnEscreve($cod_campanha);

	                    									//busca dados da campanha
															$cod_campanha = fnDecode($_GET['idc']);  
															$sql = "SELECT TIP_CAMPANHA FROM CAMPANHA where COD_CAMPANHA = '".$cod_campanha."' ";
	                    									//fnEscreve($sql);
															$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
															$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

															if (isset($qrBuscaCampanha)){
																$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
															} 
	                    									//fnEscreve($tip_campanha);

															$sql = "select * from VARIAVEIS where COD_BANCOVAR in (3,21,22) order by NUM_ORDENAC";
															$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

															while ($qrBuscaFases = mysqli_fetch_assoc($arrayQuery)) {
														?>
																<a href="javascript:void(0)" class="btn btn-info btn-xs dragTag" draggable="true" style="margin: 0 4px 7px; box-shadow: 0 2px 2px -1px #D7DBDD;"
																dragTagName="<?=$qrBuscaFases[KEY_BANCOVAR]?>" 
																onclick="$(function(){quickCopy('<?=$qrBuscaFases[KEY_BANCOVAR]?>')});">
																	<span><?=$qrBuscaFases['ABV_BANCOVAR']?></span>
																</a>

														<?php
															}

															if ($tip_campanha == 20) {

																$sql2 = "select * from VARIAVEIS where COD_BANCOVAR in (33,34) order by NUM_ORDENAC";
																$arrayQuery = mysqli_query($connAdm->connAdm(), $sql2) or die(mysqli_error());
																while ($qrBuscaFasesCupom = mysqli_fetch_assoc($arrayQuery)) {
														?>
																	<a href="javascript:void(0)" class="btn btn-info btn-xs dragTag" draggable="true" style="margin: 0 4px 7px; box-shadow: 0 2px 2px -1px #D7DBDD;"
																	dragTagName="<?=$qrBuscaFasesCupom[KEY_BANCOVAR]?>" 
																	onclick="$(function(){quickCopy('<?=$qrBuscaFasesCupom[KEY_BANCOVAR]?>')});">
																		<span><?=$qrBuscaFasesCupom['ABV_BANCOVAR']?></span>
																	</a>

														<?php
																}

															}

														?>

													</div>

												</div>

											</fieldset>

											<div class="push20"></div>
																				
											<fieldset>
												<legend>Dados da Mensagem</legend> 
												
													<div class="row">

														<div class="col-md-12">
															<div class="form-group">
																<label for="inputName" class="control-label">Mensagem</label><br/>
																	<textarea class="form-control" rows="9" name="DES_MENSAGEM" id="DES_MENSAGEM" maxlength="1000"><?php echo $des_mensagem ?></textarea>
																<div class="help-block with-errors"></div>
															</div>
														</div>
																					
													</div>
													
											</fieldset>	
																					
											<div class="push10"></div>
											<hr>	
											<div class="form-group text-right col-lg-12">
												
												  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
												  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Cadastrar Mensagem</button>
												  <!-- <a name="CAD" id="CAD" class="btn btn-primary" onclick="ajxCad()"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</a> -->
												  <!-- <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
												  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
												
											</div>
											
											<input type="hidden" name="opcao" id="opcao" value="">
											<input type="hidden" name="COD_LISTA" id="COD_LISTA" value="<?=$cod_lista?>">
											<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
											<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
											
											<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>

										<div id="ajax"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
												
												<table class="table table-bordered table-striped table-hover tableSorter">
												    <thead>
										    			<tr>
										    				<th>Cliente</th>
										    				<th>Celular</th>
										    				<th>Email</th>
										    			</tr>
										    		</thead>

										    		<tbody id="relatorioConteudo">
												
														<?php

														$sql = "SELECT COD_LISTA FROM IMPORT_COMUNICAAV 
																WHERE cod_empresa = $cod_empresa 
																AND COD_LISTA = $cod_lista";	
																
														//fnEscreve($sql);
														$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
														$total_itens_por_pagina = mysqli_num_rows($retorno);
														
														$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	

														//variavel para calcular o início da visualização com base na página atual
														$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

														$sqlProd = "SELECT * FROM IMPORT_COMUNICAAV 
																	WHERE COD_EMPRESA = $cod_empresa
																	AND COD_LISTA = $cod_lista
																	ORDER BY NOM_CLIENTE
																    LIMIT $inicio, $itens_por_pagina;
																  ";

														$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlProd)) or die(mysqli_error());
														////fnEscreve($qrLinhas['LINHAS']);

														while($qrProd = mysqli_fetch_assoc($result)){

														?>
															<tr>
																<td><?=$qrProd['NOM_CLIENTE']?></td>
																<td class="sp_celphones"><?=fnCorrigeTelefone($qrProd['NUM_CELULAR'])?></td>
																<td><?=$qrProd['DES_EMAILUS']?></td>
															</tr>
														<?php
														}
														?>

													</tbody>

													<tfoot>
														<tr>
														  <th class="" colspan="100">
															<center><ul id="paginacao" class="pagination-sm"></ul></center>
														  </th>
														</tr>
													</tfoot>

												</table>

											</div>
											
										</div>										
									
									<div class="push"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div>

					<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
					<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	
	<script type="text/javascript">

		$(function(){

			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}

			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			$('.sp_celphones').mask(SPMaskBehavior, spOptions);

			$('.dragTag').on('dragstart', function (event) {
				var tag = $(this).attr('dragTagName');
				event.originalEvent.dataTransfer.setData("text", ' '+ tag +' ');
				event.originalEvent.dataTransfer.setDragImage(this, 0,0);
			}); 
			
			
			$('.dragTag').on('click', function (event) {
				  var $temp = $("<input>");
				  $("body").append($temp);
				  $temp.val(" @"+$(this).text()+" ").select();
				  document.execCommand("copy");
				  $temp.remove();
			});

			setInterval(function(){checaStatus()},3000);

		});	
	
		function quickCopy(tag) {
		  var $temp = $("<input>");
		  $("body").append($temp);
		  $temp.val("@"+tag+" ").select();
		  document.execCommand("copy");
		  $temp.remove();
		}

		var SPMaskBehavior = function (val) {
		  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
		},
		spOptions = {
		  onKeyPress: function(val, e, field, options) {
			  field.mask(SPMaskBehavior.apply({}, arguments), options);
			}
		};
		
		function reloadPage(idPage) {
			// alert($("#COD_USUARIO").val());
			$.ajax({
				type: "POST",
				url: "ajxListaComunicacaoAvulsa.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&itens_por_pagina=<?php echo $itens_por_pagina; ?>&idPage="+idPage,
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);											
				},
				error:function(){
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});		
		}

		function geraQrCode(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxComunicaAvulsa.do?id=<?php echo fnEncode($cod_empresa); ?>",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#qrCode').html('<center><div class="loading" style="width: 100%;"></div></center>');
				},
				success:function(data){
					$("#qrCode").html(data);											
				},
				error:function(){
					
				}
			});		
		}

		function checaStatus(){
			$.ajax({
				type: "POST",
				url: "ajxComunicaAvulsa.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=status",
				data: $('#formulario').serialize(),
				success:function(data){
					console.log(data);
					if(data.trim() != 'false'){
						$("#msgStatus").html("<span class='f21'>Conexão <b>ativa</b>.</span>");
						$("#msgStatus").removeClass('alert-danger').addClass('alert-success');
						$("#qrCode").html('');
					}else{
						$("#msgStatus").html("<span class='f21'>Não conectado. <a href='javascript:void(0)' onclick='geraQrCode()' style='color: white; text-decoration: underline;'>Clique aqui</a> para gerar o <i>QrCode</i> de conexão.</span>");
						$("#msgStatus").removeClass('alert-success').addClass('alert-danger');
					}									
				},
				error:function(){
					
				}
			});
		}
		
	</script>	