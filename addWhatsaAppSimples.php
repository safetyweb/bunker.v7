<?php

// echo fnDebug('true');

$hashLocal = mt_rand();	
$cod_template = "";
$cod_desafio = fnLimpaCampoZero(fnDecode($_GET['idD']));

// fnEscreve($cod_desafio);

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

		$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
		$cod_desafio = fnLimpaCampoZero($_REQUEST['COD_DESAFIO']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}

		$nom_template = fnLimpaCampo($_REQUEST['NOM_TEMPLATE']);
		$des_titulo = fnLimpaCampo($_REQUEST['DES_TITULO']);
		$abv_template = fnLimpaCampo($_REQUEST['ABV_TEMPLATE']);
		$des_template = addslashes($_REQUEST['DES_TEMPLATE']);
		$des_template2 = addslashes($_REQUEST['DES_TEMPLATE2']);
		$des_template3 = addslashes($_REQUEST['DES_TEMPLATE3']);
		$des_template4 = addslashes($_REQUEST['DES_TEMPLATE4']);
		$des_template5 = addslashes($_REQUEST['DES_TEMPLATE5']);
		$des_imagem = fnLimpaCampo($_REQUEST['NOM_ARQUIVO']);

			// fnEscreve(fnCHRHTML('<>/""~ç[]´'));
			// fnEscreve($des_template);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];


		if ($opcao != ''){			

				//mensagem de retorno
			switch ($opcao)
			{
				case 'CAD':

				$sql = "INSERT INTO TEMPLATE_WHATSAPP(
										COD_EMPRESA,
										COD_DESAFIO,
										LOG_ATIVO,
										NOM_TEMPLATE,
										DES_TITULO,
										ABV_TEMPLATE,
										DES_IMAGEM,
										COD_USUCADA
										)VALUES( 
										$cod_empresa,
										$cod_desafio,
										'$log_ativo',
										'$nom_template',
										'$des_titulo',
										'$abv_template',
										'$des_imagem',
										$cod_usucada
									)";

					// fnEscreve($sql);
					mysqli_query(connTemp($cod_empresa,''),$sql);

					$sqlCod = "SELECT MAX(COD_TEMPLATE) COD_TEMPLATE FROM TEMPLATE_WHATSAPP
					WHERE COD_EMPRESA = $cod_empresa AND COD_USUCADA = $cod_usucada";

					$arrCod = mysqli_query(connTemp($cod_empresa,''),$sqlCod) or die(mysqli_error());				
					$qrBuscaCod = mysqli_fetch_assoc($arrCod);

					$cod_template_whatsapp = $qrBuscaCod[COD_TEMPLATE];

					// $sql = "SELECT * FROM DESAFIO_V2 WHERE COD_EMPRESA = $cod_empresa AND COD_DESAFIO = $cod_desafio";

					// 		//fnEscreve($sql);
					// $arrayQuery = mysqli_query(ConnTemp($cod_empresa,''),$sql);
					// $qrBuscaDesafio = mysqli_fetch_assoc($arrayQuery);

					// $log_ativo = $qrBuscaDesafio['LOG_ATIVO'];
					// $log_email = $qrBuscaDesafio['LOG_EMAIL'];
					// $log_sms = $qrBuscaDesafio['LOG_SMS'];
					// $log_wpp = $qrBuscaDesafio['LOG_WPP'];
					// $log_push = $qrBuscaDesafio['LOG_PUSH'];
					// $log_nps = $qrBuscaDesafio['LOG_NPS'];

					// $cod_persona = $qrBuscaDesafio['COD_PERSONA'];
					// $cod_personas = $qrBuscaDesafio['COD_PERSONA'];
					// $cod_univend = $qrBuscaDesafio['COD_UNIVEND'];
					// $cod_filtro = $qrBuscaDesafio['COD_FILTRO'];
					// $cod_usuario = $qrBuscaDesafio['COD_USUARIO'];
					// $nom_desafio = $qrBuscaDesafio['NOM_DESAFIO'];
					// $des_desafio = $qrBuscaDesafio['DES_DESAFIO'];
					// $dat_ini = $qrBuscaDesafio['DAT_INI'];
					// $dat_fim = $qrBuscaDesafio['DAT_FIM'];
					// $des_img = $qrBuscaDesafio['DES_IMG'];
					// $des_icone = $qrBuscaDesafio['DES_ICONE'];
					// $des_cor = $qrBuscaDesafio['DES_COR'];
					// $val_metades = $qrBuscaDesafio['VAL_METADES']; 
					// $tip_divisao = $qrBuscaDesafio['TIP_DIVISAO']; 
					// $tip_geracao = $qrBuscaDesafio['TIP_GERACAO'];

						// criar campanha
					$sql = "CALL SP_ALTERA_CAMPANHA (
					'0', 
					'".$cod_empresa."', 
					'9999', 
					'S', 
					'Campanha WhatsApp (Automática)', 
					'DSF', 
					'fa-phone-alt', 
					'#5CFF66', 
					'N', 
					'Campanha automatica desafio', 
					'".$cod_usucada."', 
					'21',
					'N',
					'".$dat_ini."',
					'".$dat_fim."',
					'00:00:00',
					'23:59:59',
					'N',
					'CAD'    
				) ";

						// fnEscreve($sql);

						// exit();

				$result = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());				
				$qrBuscaNovo = mysqli_fetch_assoc($result);
				
						//fnEscreve($qrBuscaNovo["COD_NOVO"]);				
				$cod_campanha = $qrBuscaNovo["COD_NOVO"];

				$sqlUpdDesafio = "UPDATE CAMPANHA SET COD_DESAFIO = $cod_desafio 
				WHERE COD_EMPRESA = $cod_empresa 
				AND COD_CAMPANHA = $cod_campanha";
				mysqli_query(connTemp($cod_empresa,''),$sqlUpdDesafio) or die(mysqli_error());	

						//criar gatilho
				$sql = "INSERT INTO GATILHO_WHATSAPP(
					COD_EMPRESA,
					COD_CAMPANHA,
					TIP_GATILHO,
					TIP_CONTROLE,
					DES_PERIODO,
					TIP_MOMENTO,
					HOR_ESPECIF,
					DAT_INI,
					
					HOR_INI,
					
					LOG_DOMINGO,
					LOG_SEGUNDA,
					LOG_TERCA,
					LOG_QUARTA,
					LOG_QUINTA,
					LOG_SEXTA,
					LOG_SABADO,
					COD_USUARIO,
					LOG_STATUS
					) VALUES(
					'$cod_empresa',
					'$cod_campanha',
					'individualD',
					'99',
					'99',
					'99',
					'0',
					'$dat_ini',
					'00:00:00',
					'N',
					'N',
					'N',
					'N',
					'N',
					'N',
					'N',
					'$cod_usucada',
					'S'
				);";
					$sql .= "INSERT INTO CONTROLE_SCHEDULE_WHATSAPP(
						COD_EMPRESA,
						COD_CAMPANHA,
						TIP_GATILHO,
						COD_USUCADA
						) VALUES(
						'$cod_empresa',
						'$cod_campanha',
						'individualD',
						'$cod_usucada'
					);";

						mysqli_multi_query(ConnTemp($cod_empresa,''),$sql);

						//setar mensagem (template)
						$sql = "INSERT INTO TEMPLATE_AUTOMACAO_WHATSAPP(
							COD_EMPRESA,
							COD_CAMPANHA,
							COD_BLTEMPL
							) VALUES(
							$cod_empresa,
							$cod_campanha,
							25
						)";
						//fnEscreve($sql);

							mysqli_query(connTemp($cod_empresa,''),$sql);

							$sqlCod = "SELECT MAX(COD_TEMPLATE) COD_TEMPLATE FROM TEMPLATE_AUTOMACAO_WHATSAPP
							WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha AND COD_BLTEMPL = 25";

							$arrCod = mysqli_query(connTemp($cod_empresa,''),$sqlCod) or die(mysqli_error());				
							$qrBuscaCod = mysqli_fetch_assoc($arrCod);

							$cod_template_bloco = $qrBuscaCod[COD_TEMPLATE];

							$sql = "INSERT INTO MENSAGEM_WHATSAPP(
								COD_TEMPLATE_WHATSAPP,
								COD_TEMPLATE_BLOCO,
								COD_EMPRESA,
								COD_CAMPANHA,
								NUM_ORDENAC,
								LOG_PRINCIPAL,
								COD_USUCADA
								) VALUES(
								$cod_template_whatsapp,
								$cod_template_bloco,
								$cod_empresa,
								$cod_campanha,
								(SELECT NUM_ORDENAC FROM TEMPLATE_AUTOMACAO_WHATSAPP WHERE COD_TEMPLATE = $cod_template_bloco),
								'S',
								$cod_usucada
							)";
								
						// fnEscreve($sql);
								mysqli_query(connTemp($cod_empresa,''),$sql);

								$sql = "SELECT COD_GATILHO, TIP_GATILHO FROM GATILHO_WHATSAPP WHERE COD_CAMPANHA = $cod_campanha";

								$qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

								$tip_gatilho = $qrCod['TIP_GATILHO'];
								$cod_gatilho = $qrCod['COD_GATILHO'];
								$pct_reserva = 0;

								if($cod_gatilho != ""){

									if($tip_gatilho == 'individual'){
										$tipo = "CAD";
									}else{
										$tipo = "ANV";
									}

								}

								$sqlDel = "DELETE FROM WHATSAPP_LOTE 
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_CAMPANHA = $cod_campanha
								AND LOG_ENVIO = 'P'";
								
								mysqli_query(connTemp($cod_empresa,''),$sqlDel);

								$sqlProcCad = "CALL SP_RELAT_WHATSAPP_CLIENTE($cod_empresa, $cod_campanha, '$pct_reserva', '$cod_personas', 'ANV')";

								$retorno = mysqli_query(connTemp($cod_empresa,''),$sqlProcCad);

								$qrTot = mysqli_fetch_assoc($retorno);

								$sql2 = "INSERT INTO WHATSAPP_PARAMETROS(
									COD_EMPRESA,
									COD_CAMPANHA,
									COD_PERSONAS,
									PCT_RESERVA,
									TOT_PERSONAS,
									CLIENTES_UNICOS,
									CLIENTES_UNICOS_WHATSAPP,
									CLIENTES_UNICO_PERC,
									TOTAL_CLIENTE_WHATSAPP_NAO,
									CLIENTES_OPTOUT,
									CLIENTES_BLACKLIST,
									COD_USUCADA
									) VALUES(
									$cod_empresa,
									$cod_campanha,
									'$cod_personas',
									'$pct_reserva',
									'".fnLimpaCampoZero($qrTot['TOTAL_PERSONAS'])."',
									'".fnLimpaCampoZero($qrTot['CLIENTES_UNICOS'])."',
									'".fnLimpaCampoZero($qrTot['CLIENTES_UNICOS_WHATSAPP'])."',
									'".fnLimpaCampoZero($qrTot['CLIENTES_UNICO_PERC'])."',
									'".fnLimpaCampoZero($qrTot['TOTAL_CLIENTE_WHATSAPP_NAO'])."',
									'".fnLimpaCampoZero($qrTot['CLIENTES_OPTOUT'])."',
									'".fnLimpaCampoZero($qrTot['CLIENTES_BLACKLIST'])."',
									$cod_usucada
								)";

						// fnEscreve($sql2);
								mysqli_query(connTemp($cod_empresa,''),$sql2);

								$sqlControle = "UPDATE WHATSAPP_LISTA_CONTROLE
								SET COD_LISTA = (
									SELECT MAX(COD_LISTA) AS COD_LISTA 
									FROM WHATSAPP_PARAMETROS 
									WHERE COD_CAMPANHA = $cod_campanha 
									AND COD_USUCADA = $cod_usucada
									)
								WHERE COD_CAMPANHA = $cod_campanha
								AND COD_LISTA = 0";
								mysqli_query(connTemp($cod_empresa,''),$sqlControle);

								$sqlLista = "SELECT COD_CLIENTE, NUM_CELULAR FROM WHATSAPP_LISTA
								WHERE COD_EMPRESA = $cod_empresa 
								AND COD_CAMPANHA = $cod_campanha";

								$arrayLista = mysqli_query(connTemp($cod_empresa,''),$sqlLista);

								$sqlLimpaCel = "";

								while ($qrLista = mysqli_fetch_assoc($arrayLista)){

									$numCelular = fnlimpacelular($qrLista[NUM_CELULAR]);
									
									$sqlLimpaCel .= "UPDATE WHATSAPP_LISTA SET 
									NUM_CELULAR = '$numCelular'
									WHERE COD_CLIENTE = $qrLista[COD_CLIENTE]
									AND COD_CAMPANHA = $qrLista[COD_CAMPANHA]
									AND COD_EMPRESA = $cod_empresa;";

								}

								mysqli_multi_query(connTemp($cod_empresa,''),$sqlLimpaCel);

								unset($sqlLimpaCel);

								$sqlDelete = "DELETE FROM WHATSAPP_LISTA 
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_CAMPANHA = $cod_campanha 
								AND NUM_CELULAR = ''";

								mysqli_query(connTemp($cod_empresa,''),$sqlDelete);

						// processamento da campanha
								$sqlUpdt2 = "UPDATE CAMPANHA SET 
								LOG_PROCESSA_WHATSAPP = 'S',
								DAT_PROCESSA_WHATSAPP = NOW()
								WHERE COD_EMPRESA = $cod_empresa 
								AND COD_CAMPANHA = $cod_campanha";

								mysqli_query(connTemp($cod_empresa,''),$sqlUpdt2);

								$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

								break;

								case 'ALT':

								$sql = "UPDATE TEMPLATE_WHATSAPP SET
										LOG_ATIVO='$log_ativo',
										NOM_TEMPLATE='$nom_template',
										DES_TITULO='$des_titulo',
										ABV_TEMPLATE='$abv_template',
										DES_IMAGEM='$des_imagem',
										DAT_ALTERAC=NOW(),
										COD_ALTERAC=$cod_usucada
										WHERE COD_EMPRESA = $cod_empresa
										AND COD_TEMPLATE=$cod_template";

						// fnEscreve($sql);
								mysqli_query(connTemp($cod_empresa,''),$sql);

								$sqlCod = "SELECT COD_TEMPLATE FROM TEMPLATE SMS WHERE COD_EMPRESA = $cod_empresa ORDER BY 1 DESC LIMIT 1";
								$qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCod));
								$cod_template = $qrCod[COD_TEMPLATE];

								$sqlUpdDesafio = "UPDATE CAMPANHA SET 
													DAT_INI = '$dat_ini', 
													DAT_FIM = '$dat_fim' 
													WHERE COD_DESAFIO = $cod_desafio";
								mysqli_query(connTemp($cod_empresa,''),$sqlUpdDesafio);	

								$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";

								break;

								case 'EXC':
								$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
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
					$cod_campanha = fnDecode($_GET['idc']);

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

				$des_template = array();

				if($cod_desafio != ""){
					
	//busca dados do convênio
					$sql = "SELECT * FROM TEMPLATE_WHATSAPP 
					WHERE COD_EMPRESA = $cod_empresa 
					AND COD_DESAFIO = $cod_desafio";	
					
	// fnEscreve($sql);
					$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
					$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);


					if (isset($qrBuscaTemplate)){
						$cod_template = $qrBuscaTemplate['COD_TEMPLATE'];
						if($qrBuscaTemplate['LOG_ATIVO'] == 'S'){
							$checkAtivo = "checked";
						}else{
							$checkAtivo = "";
						}
						$nom_template = $qrBuscaTemplate['NOM_TEMPLATE'];
						$des_titulo = $qrBuscaTemplate['DES_TITULO'];
						$abv_template = $qrBuscaTemplate['ABV_TEMPLATE'];
						$des_template[0] = $qrBuscaTemplate['DES_TEMPLATE'];		
						$des_template[1] = $qrBuscaTemplate['DES_TEMPLATE2'];		
						$des_template[2] = $qrBuscaTemplate['DES_TEMPLATE3'];		
						$des_template[3] = $qrBuscaTemplate['DES_TEMPLATE4'];		
						$des_template[4] = $qrBuscaTemplate['DES_TEMPLATE5'];
						$des_imagem = $qrBuscaTemplate['DES_IMAGEM'];		
					}
					
				}else{
					$checkAtivo = "checked";
					$nom_template = "";
					$des_titulo = "";
					$abv_template = "";
					$des_template[0] = $qrBuscaTemplate['DES_TEMPLATE'];
				}

				?>

				<?php if ($popUp != "true"){  ?>							
					<div class="push30"></div> 
				<?php } ?>

<style type="text/css">
	body{
		overflow: hidden;
	}
.f9{
	font-size: 9px;
}
#blocker
{
	display:none; 
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	opacity: .8;
	background-color: #f2f2f2;
	z-index: 1000;
}

#blocker div
{
	position: absolute;
	top: 30%;
	left: 48%;
	width: 200px;
	height: 2em;
	margin: -1em 0 0 -2.5em;
	color: #000;
	font-weight: bold;
}

.whatsapp {
/*		  width: 300px;*/
/*		  margin: 50px auto;*/
border-radius: 15px;
background: #00a884;
color: #fff;
padding: 20px;
font-weight: 500;
font-family: Helvetica;
position: relative;
border:none!important;
overflow: hidden;
}


/* speech bubble 13 */

.sb13:before {
	content: "";
	width: 0px;
	height: 0px;
	position: absolute;
	border-left: 15px solid #00a884;
	border-right: 15px solid transparent;
	border-top: 15px solid #00a884;
	border-bottom: 15px solid transparent;
	right: 0px;
	top: 0px;
}


/* speech bubble 14 */

.sb14:before {
	content: "";
	width: 0px;
	height: 0px;
	position: absolute;
	border-left: 15px solid transparent;
	border-right: 15px solid #00a884;
	border-top: 15px solid #00a884;
	border-bottom: 15px solid transparent;
	left: -16px;
	top: 0px;
}
</style>


	
<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
				<?php } ?>

				<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

					<div class="row">  

						<div class="col-sm-1">
							<div class="form-group">
								<label for="inputName" class="control-label">Ativo</label>
								<div class="push5"></div>
								<label class="switch">
									<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" <?=$checkAtivo?>>
									<span></span>
								</label>
							</div>
						</div>      

						<div class="col-sm-3">
							<div class="form-group">
								<label for="inputName" class="control-label">Nome Template</label>
								<input type="text" class="form-control input-sm" name="NOM_TEMPLATE" id="NOM_TEMPLATE" value="<?php echo $nom_template ?>" maxlength="50">
							</div>
							<div class="help-block with-errors"></div>
						</div>       

						<div class="col-sm-2">
							<div class="form-group">
								<label for="inputName" class="control-label">Abreviação Template</label>
								<input type="text" class="form-control input-sm" name="ABV_TEMPLATE" id="ABV_TEMPLATE" value="<?php echo $abv_template ?>" maxlength="20">
							</div>
							<div class="help-block with-errors"></div>
						</div>

						<div class="col-sm-3">
							<div class="form-group">
								<label for="inputName" class="control-label">Título da Template</label>
								<input type="text" class="form-control input-sm" name="DES_TITULO" id="DES_TITULO" value="<?php echo $des_titulo ?>" maxlength="199">
							</div>
							<div class="help-block with-errors"></div>
						</div>

						<div class="col-sm-3">
							<label for="inputName" class="control-label">Imagem/vídeo da mensagem</label>
							<div class="input-group">
								<span class="input-group-btn">
									<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="NOM_ARQUIVO" extensao="all"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
								</span>
								<input type="text" name="NOM_ARQUIVO" id="NOM_ARQUIVO" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?= $des_imagem?>">
							</div>
							<span class="help-block">Caso houver</span>
						</div> 

					</div>	

					<div class="push10"></div>
					<hr>

					<div class="row">	
						<div class="form-group text-right col-lg-12">
							
							<!--<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>-->
							<?php
							if($cod_template == 0){
								?>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button> 
								<?php
							}else{
								?>
								<a href="javascript:void(0)" onclick='parent.window.location.href = "action.php?mod=<?=fnEncode(2033)?>&id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&idT=<?=fnEncode($cod_template)?>&idc=<?=fnEncode($cod_desafio)?>&tipo=<?=fnEncode('ALT')?>&pop=true&agenda=true"' class="btn btn-info pull-left"><i class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp; Acessar Template</a>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
								<?php
							}
							?>

						</div>

							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
							
					</div>
					
					<input type="hidden" name="opcao" id="opcao" value="">
					<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
					<input type="hidden" name="COD_TEMPLATE" id="COD_TEMPLATE" value="<?php echo $cod_template ?>">
					<input type="hidden" name="COD_DESAFIO" id="COD_DESAFIO" value="<?php echo $cod_desafio ?>">
					<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
					<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
					
					<div class="push5"></div> 
					
				</form>								
						
						<div class="push"></div>
						
			</div>								
					
		</div>
	</div>
	<!-- fim Portlet -->
</div>

<!-- modal -->                  
<div class="modal fade" id="popModalEnvio" tabindex='-1'>
	<div class="modal-dialog" style="">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<form id="envioTeste" action="">
					<fieldset>
						<legend>Dados do envio</legend> 

						<div class="row">

							<div class="col-md-10">
								<div class="form-group">
									<label for="inputName" class="control-label">Celulares (com DDD)</label>
									<input type="text" class="form-control input-sm" name="NUM_CELULAR" id="NUM_CELULAR" maxlength="400">
									<div class="help-block with-errors">Separar múltiplos celulares com ";"</div>
								</div>
							</div>

							<div class="col-md-2">
								<div class="push10"></div>
								<div class="push5"></div>
								<a href="javascript:void(0)" id="dispararTeste" class="btn btn-primary btn-sm btn-block getBtn" style="margin-top: 2px;"><i class="fal fa-paper-plane" aria-hidden="true"></i>&nbsp; Envio de teste</a>
							</div>

							<input type="hidden" name="COD_TEMPLATE_ENVIO" id="COD_TEMPLATE_ENVIO" value="<?=$cod_template?>">
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">	

						</div>

					</fieldset>
				</form>
			</div>    
		</div>
	</div>
</div> 


<script type="text/javascript">

	// let valor = "",
	// 	campo = "";

	$('.upload').on('click', function(e) {
		var idField = 'arqUpload_' + $(this).attr('idinput');
		var typeFile = $(this).attr('extensao');

		$.dialog({
			title: 'Arquivo',
			content: '' +
			'<form method = "POST" enctype = "multipart/form-data">' +
			'<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
			'<div class="progress" style="display: none">' +
			'<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">' +
			'   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
			'</div>' +
			'<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
			'</form>'
		});
	});

	function uploadFile(idField, typeFile) {
		var formData = new FormData();
		var nomeArquivo = $('#' + idField)[0].files[0]['name'];

		formData.append('arquivo', $('#' + idField)[0].files[0]);
		formData.append('diretorio', '../media/clientes/');
		formData.append('diretorioAdicional', 'wpp');
		formData.append('id', <?php echo $cod_empresa ?>);
		formData.append('typeFile', typeFile);

		$('.progress').show();
		$.ajax({
			xhr: function() {
				var xhr = new window.XMLHttpRequest();
				$('#btnUploadFile').addClass('disabled');
				xhr.upload.addEventListener("progress", function(evt) {
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
			url: '../uploads/uploaddoc.php',
			type: 'POST',
			data: formData,
		processData: false, // tell jQuery not to process the data
		contentType: false, // tell jQuery not to set contentType
		success: function(data) {
			$('.jconfirm-open').fadeOut(300, function() {
				$(this).remove();
			});
			if (!data.trim()) {
				$('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
				$.alert({
					title: "Mensagem",
					content: "Upload feito com sucesso",
					type: 'green'
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