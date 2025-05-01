<?php
	
	//echo "<h5>_".$opcao."</h5>";

	$hashLocal = mt_rand();

	if(isset($_GET['pop'])){
	    $popUp = fnLimpaCampo($_GET['pop']);
	  }else{
	    $popUp = '';
	  }
	
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

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
		}
	}

	if(isset($_GET['alert'])){
		$msgRetorno = "Campanha processada com <strong>sucesso!</strong>";
	    $msgTipo = 'alert-success';
	}      
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_campanha = fnDecode($_GET['idc']);	
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

	$sql = "SELECT * FROM EMAIL_PARAMETROS
			WHERE COD_LISTA = (
							 	SELECT MAX(COD_LISTA) FROM EMAIL_PARAMETROS
							 	WHERE COD_EMPRESA = $cod_empresa 
							 	AND COD_CAMPANHA = $cod_campanha
							  )";

	$qrTot = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

	if(isset($qrTot)){
		$cod_lista = $qrTot['COD_LISTA'];
		$cod_persona = $qrTot['COD_PERSONAS'];
		$tot_personas = $qrTot['TOT_PERSONAS'];
		$pct_reserva = $qrTot['PCT_RESERVA'];
		$clientes_unicos = $qrTot['CLIENTES_UNICOS'];
		$clientes_unicos_email = $qrTot['CLIENTES_UNICOS_EMAIL'];
		$pct_unicos_mail = ($clientes_unicos_email * 100 ) / $clientes_unicos;
		$clientes_unico_perc = $qrTot['CLIENTES_UNICO_PERC'];
		$pct_clientes_unico = ($clientes_unico_perc  * 100 ) / $clientes_unicos_email;		
		$total_cliente_email_nao = $qrTot['TOTAL_CLIENTE_EMAIL_NAO'];
		$pct_sem_mail = ($total_cliente_email_nao * 100 ) / $clientes_unicos;
		$clientes_optout = $qrTot['CLIENTES_OPTOUT'];
		$pct_optout = ($clientes_optout * 100 ) / $clientes_unicos;
		$clientes_blacklist = $qrTot['CLIENTES_BLACKLIST'];
		$pct_blacklist = ($clientes_blacklist * 100 ) / $clientes_unicos;
		
		$lista_envio = $clientes_unicos_email - $clientes_unico_perc - $clientes_blacklist - $clientes_optout;
		$pct_lista = (($lista_envio * 100 ) / $clientes_unicos_email);
	}else{
		$cod_lista = 0;
		$cod_persona = 0;
		$pct_reserva = 10;
		$tot_personas = "0";
		$clientes_unicos = "0";
		$clientes_unicos_email = "0";
		$clientes_unico_perc = "0";
		$total_cliente_email_nao = "0";
		$clientes_optout = 0;
		$clientes_blacklist = 0;
		$pct_clientes_unico = 0;
		$pct_sem_mail = 0;
		$pct_optout = 0;
		$pct_blacklist = 0;
	}

	$sqlCamp = "SELECT CP.COD_EXT_CAMPANHA,
					   CP.LOG_PROCESSA,
					   GM.DAT_INI AS DAT_INIAGENDAMENTO,
					   GM.DAT_FIM AS DAT_FIMAGENDAMENTO,
					   GM.HOR_INI,
					   GM.HOR_FIM,
					   GM.TIP_GATILHO
				FROM CAMPANHA CP
				LEFT JOIN GATILHO_EMAIL GM ON GM.COD_CAMPANHA = CP.COD_CAMPANHA 
				WHERE CP.COD_EMPRESA = $cod_empresa 
				AND CP.COD_CAMPANHA = $cod_campanha
				AND CP.LOG_ATIVO = 'S'";

	// fnEscreve($sqlCamp);

	$qrCamp = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCamp));

	// fnEscreve($qrCamp['COD_EXT_CAMPANHA']);

	$dat_iniagendamento = $qrCamp['DAT_INIAGENDAMENTO']." ".$qrCamp['HOR_INI'];
	$dat_fimagendamento = $qrCamp['DAT_FIMAGENDAMENTO']." ".$qrCamp['HOR_FIM'];
	$agora = date('Y-m-d H:i:s');
	$tip_gatilho = $qrCamp['TIP_GATILHO'];
	$log_processa = $qrCamp['LOG_PROCESSA'];

	// fnEscreve($tip_gatilho);

	// fnEscreve($dat_iniagendamento);
	// fnEscreve($agora);

	if($agora > $dat_iniagendamento){
		$dat_original = $dat_iniagendamento;
		$dat_iniagendamento = date('Y-m-d H:i:s');
	}else{
		$dat_original = $dat_iniagendamento;
	}

	$day1 = $dat_iniagendamento;
    $day1 = strtotime($day1);
    $day2 = $dat_fimagendamento;
    $day2 = strtotime($day2);

	$diffHours = round(($day2 - $day1) / 3600);


	$data_teste = fnDatesql($qrCamp['DAT_INIAGENDAMENTO']);

	$sql = "SELECT DES_CAMPANHA, COD_EXT_CAMPANHA, DAT_EXTERNA
			FROM CAMPANHA
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_CAMPANHA = $cod_campanha";

	$arrayIntegra = mysqli_query(connTemp($cod_empresa,''),$sql);

	$qrIntegra = mysqli_fetch_assoc($arrayIntegra);

	if($qrIntegra['COD_EXT_CAMPANHA'] != ''){
		$dat_cadastrIntegra = fnDataFull($qrIntegra['DAT_EXTERNA']);
		$integraSync = '<span class="fas fa-check text-success"></span>';
		$syncMsgIntegra = "Sincronizado";
		$sync = 1;
	}else{
		$dat_cadastrIntegra = "";
		$integraSync = '<span class="fas fa-times text-danger"></span>';
		$syncMsgIntegra = "Sincronizando... aguarde.";
		$sync = 0;
	}

	$sql2 = "SELECT DISTINCT ME.COD_TEMPLATE_EMAIL, ME.DAT_CADASTR, TE.NOM_TEMPLATE 
			 FROM MENSAGEM_EMAIL ME
			 LEFT JOIN TEMPLATE_EMAIL TE ON TE.COD_TEMPLATE = ME.COD_TEMPLATE_EMAIL
			 WHERE ME.COD_EMPRESA = $cod_empresa
			 AND ME.COD_CAMPANHA = $cod_campanha";
	
	$arrayTemplates = mysqli_query(connTemp($cod_empresa,''),$sql2);

	$sql = "SELECT COD_GERACAO FROM EMAIL_LOTE 
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_CAMPANHA = $cod_campanha
			AND COD_STATUSUP = 3
			AND LOG_TESTE = 'N'
			AND COD_MAILING_EXT IS NULL; ";

	// fnEscreve($sql);

	$arrayQtdNaoProcessados = mysqli_query(connTemp($cod_empresa,''),$sql);

	$lotesNaoProcessados = mysqli_num_rows($arrayQtdNaoProcessados);

	$sql = "SELECT PM.QTD_PRODUTO, 
                   PM.TIP_LANCAMENTO,
                   CC.DES_CANALCOM 
            FROM PEDIDO_MARKA PM
            INNER JOIN PRODUTO_MARKA PRM ON PRM.COD_PRODUTO = PM.COD_PRODUTO
            INNER JOIN CANAL_COMUNICACAO CC ON CC.COD_CANALCOM = PRM.COD_CANALCOM 
            WHERE PM.COD_ORCAMENTO > 0 
            AND PM.COD_EMPRESA = $cod_empresa";

    // fnEscreve($sql);

    $arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));

    while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

      // fnEscreve($qrLista[QTD_PRODUTO]);

      $count++;

      switch($qrLista['DES_CANALCOM']){
      
        case 'SMS':
          if($qrLista['TIP_LANCAMENTO'] == 'D'){
            $qtd_sms = $qtd_sms - $qrLista[QTD_PRODUTO];
          }else{
            $qtd_sms = $qtd_sms + $qrLista[QTD_PRODUTO];
          }
        break;

        case 'Whats App':
          if($qrLista['TIP_LANCAMENTO'] == 'D'){
            $qtd_wpp = $qtd_wpp - $qrLista[QTD_PRODUTO];
          }else{
            $qtd_wpp = $qtd_wpp + $qrLista[QTD_PRODUTO];
          }
        break;

        default:
          if($qrLista['TIP_LANCAMENTO'] == 'D'){
            $qtd_email = $qtd_email - $qrLista[QTD_PRODUTO];
          }else{
            $qtd_email = $qtd_email + $qrLista[QTD_PRODUTO];
          }
        break;

      }

    }

    $msgTipoSaldo = 'alert-info';
    $msgRetornoSaldo = "<span class='fal fa-exclamation-triangle f16'></span><strong> &nbsp;Atenção!</strong> Você possui <strong>".fnValor($qtd_email,0)."</strong> envios restantes. &nbsp;<a href='https://adm.bunker.mk/action.do?mod=".fnEncode(1485)."&id=".fnEncode($cod_empresa)."' target='_blank' style='color: #FFF; text-decoration: underline;'>Contratar mais envios</a>";

?>

<style>
	
body {
    overflow-y: scroll;
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none;  /* IE 10+ */
}
body::-webkit-scrollbar { /* WebKit */
    width: 0;
    height: 0;
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
    background-color: #fff;
    z-index: 1000;
    cursor: wait;
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

</style>

					<div id="blocker">
				       <div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Processando... ;-)<br/><small>(este processo pode demorar vários minutos)</small></div>
				    </div>
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30" id="corpo">
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
					              <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
					            </div>
					            <?php include "atalhosPortlet.php"; ?>
					          </div>
					          <?php } ?>
					          <div class="portlet-body">

					          	<div class="alert <?php echo $msgTipoSaldo; ?> top30 bottom30" role="alert" id="msgRetornoSaldo">
						            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						            <?php echo $msgRetornoSaldo; ?>
					        	</div>

					          	<?php if ($msgRetorno <> '') { ?> 
					            <div class="alert <?php echo $msgTipo; ?> top30 bottom30" role="alert" id="msgRetorno">
					            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					             <?php echo $msgRetorno; ?>
					            </div>
					            <?php } ?>
								
								<h4 style="margin: 0 0 5px 0;"><span class="bolder">Ativação da Campanha</span></h4>
								<div class="push20"></div>						
									
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										 
											
											<!-- <div class="row">
												
												<div class="col-sm-7">
													<div class="form-group">
														<label for="inputName" class="control-label">Personas para Geração da Lista</label>
														<div class="push10"></div>
														
															<select data-placeholder="Selecione a persona desejada" name="COD_PERSONA[]" id="COD_PERSONA" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
																<option value=""></option>
																<?php

																	// $sql = "SELECT * from persona where cod_empresa = $cod_empresa and LOG_ATIVO = 'S' order by DES_PERSONA  ";																		
																	// $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);																
																	// while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
																	// {
																																		
																	// echo"
																	// 	  <option value='".$qrListaPersonas['COD_PERSONA']."'>".ucfirst($qrListaPersonas['DES_PERSONA']). "</option> 
																	// 	";    
																	// }

																?>								
															</select>

													</div>             

												</div>
												
												<div class="col-md-5">
													<div class="form-group">
														<label for="inputName" class="control-label">Emails Extras</label>
														<div class="push10"></div>
														<input type="text" class="form-control input-sm" name="DES_EMAILEX" id="DES_EMAILEX" maxlength="500" value="">
													</div>
													<div class="help-block with-errors">Separar múltiplos emails por ";"</div>
												</div>			
																			
											</div> -->

											<div class="push10"></div>

											<div class="row">

												<div class="col-md-3 col-xs-4 text-center">
													<i class="fal fa-users fa-2x">&nbsp; </i><span class="f17" id="TOT_PERSONAS"><?=fnValor($tot_personas,0)?></span> 
													<h5>Personas Selecionadas</h5>
												</div>
																	
												<div class="col-md-3 col-xs-4 text-center">
													<i class="fal fa-user-tag fa-2x">&nbsp; </i><span class="f17" id="CLIENTES_UNICOS"><?=fnValor($clientes_unicos,0)?></span> 
													<h5>Clientes Únicos</h5>
												</div>

												<div class="col-md-3 col-xs-4 text-center">
													<i class="fal fa-envelope fa-2x">&nbsp; </i><span class="f17" id="CLIENTES_UNICOS_EMAIL"><?=fnValor($clientes_unicos_email,0)?> &nbsp;&nbsp;<span class="f12 c1"><?=fnValor($pct_unicos_mail,2)?>%</span> </span> 
													<h5>Clientes Únicos Com Email</h5>
												</div>

												<div class="col-md-3 col-xs-4 text-center">
													<i class="fal fa-user-slash fa-2x">&nbsp; </i><span class="f17" id="TOTAL_CLIENTE_EMAIL_NAO"><?=fnValor($total_cliente_email_nao,0)?> &nbsp;&nbsp;<span class="f12 c1"><?=fnValor($pct_sem_mail,2)?>%</span></span>  
													<h5>Clientes Sem Email</h5>
												</div>
												
												<div class="push20"></div>

												<div class="col-md-3 col-xs-4 text-center">
													<i class="fal fa-user-minus fa-2x">&nbsp; </i><span class="f17" id="CLIENTES_OPTOUT"><?=fnValor($clientes_optout,0)?> &nbsp;&nbsp;<span class="f12 c1"><?=fnValor($pct_optout,2)?>%</span> </span> 
													<h5>Clientes Opt Out</h5>
												</div>

												<div class="col-md-3 col-xs-4 text-center">
													<i class="fal fa-user-times fa-2x">&nbsp; </i><span class="f17" id="CLIENTES_BLACKLIST"><?=fnValor($clientes_blacklist,0)?> &nbsp;&nbsp;<span class="f12 c1"><?=fnValor($pct_blacklist,2)?>%</span> </span> 
													<h5>Clientes Black List</h5>
												</div>

												<div class="col-md-3 col-xs-4 text-center">
													<i class="fal fa-user-lock fa-2x">&nbsp; </i><span class="f17" id="CLIENTES_UNICO_PERC"><?=fnValor($clientes_unico_perc,0)?> &nbsp;&nbsp;<span class="f12 c1"><?=fnValor($pct_reservaVl,2)?>%</span> </span>  
													<h5>Grupo de Controle</h5>
												</div>

												<div class="col-md-3 col-xs-4 text-center">
													<i class="fal fa-paper-plane fa-2x">&nbsp; </i><span class="f17" id="LISTA_ENVIO"><?=fnValor($lista_envio,0)?> &nbsp;&nbsp;<span class="f12 c1"><?=fnValor($pct_lista,2)?>%</span> </span>  
													<h5>Lista de Envio</h5>
												</div>
												
											</div>
																				
											<div class="push10"></div>
											<hr>	
											<div class="row">

												<div class="col-md-offset-3 col-md-6 col-xs-offset-1 col-xs-10">

												<?php 
													// fnEscreve($tip_gatilho);
													if($tip_gatilho == 'individual'){ 

												?>
												
														<div class="col-md-4 col-xs-5">
															<div class="form-group">
																<label for="inputName" class="control-label">Data Início <small>(referência)</small></label>
																<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_INIAGENDAMENTO" id="DAT_INIAGENDAMENTO" value="<?=fnDataFull($dat_iniagendamento)?>"> 
																<div class="help-block with-errors"><?=fnDataFull($dat_original)?></div>
															</div>
														</div>	
														
														<div class="col-md-4 col-xs-5">
															<div class="form-group">
																<label for="inputName" class="control-label">Data Final <small>(referência)</small></label>
																<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_FIMAGENDAMENTO" id="DAT_FIMAGENDAMENTO" value="<?=fnDataFull($dat_fimagendamento)?>"> 
																<label>&nbsp;</label>
															</div>
														</div>	
														
														<div class="col-md-4 col-xs-2">
															<div class="form-group">
																<label for="inputName" class="control-label">Período <small>(referência)</small></label>
																<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_PERIODO" id="DES_PERIODO" value="<?=fnValor($diffHours,0)?>h(s)"> 
																<label>&nbsp;</label>
															</div>
														</div>

														<div class="col-md-4 col-xs-3">
															<div class="form-group">
																<!-- <label for="inputName" class="control-label">Lote</label> -->
																	<select data-placeholder="Lote" name="QTD_LOTE" id="QTD_LOTE" class="chosen-select-deselect pull-right" onchange="calculaPeriodo()" style="width:100%;">
																		<option value=""></option>																     
																		<option value="1">1</option>																     
																		<option value="2">2</option>																     
																		<option value="3">3</option>																     
																		<option value="4">4</option>																     
																		<option value="5">5</option>																     
																		<option value="6">6</option>																     
																		<option value="7">7</option>																     
																		<option value="8">8</option>																     
																		<option value="9">9</option>																     
																		<option value="10">10</option>																     
																		<option value="15">15</option>																     
																		<option value="20">20</option>																     
																	</select>
																<div class="help-block with-errors"></div>
															</div>
														</div>
														
														<div class="col-md-8 col-xs-3">
															<div class="form-group">
																<!-- <label for="inputName" class="control-label">Intervalo entre lotes</label> -->
																	<select data-placeholder="Intervalo entre lotes" name="DES_INTERVAL" id="DES_INTERVAL" class="chosen-select-deselect pull-right" onchange="calculaPeriodo()" style="width:100%;">
																		<option value=""></option>																     
																		<option value="1">1 horas</option>																     
																		<option value="2">2 horas</option>																     
																		<option value="3">3 horas</option>																     
																		<option value="4">4 horas</option>																     
																		<option value="5">5 horas</option>																     															     
																	</select>
																<div class="help-block with-errors"></div>
															</div>
														</div>
														
														<div class="push15"></div>

														<div class="col-md-4 col-xs-7" id="projecaoData">
															<div class="form-group">
																<label for="inputName" class="control-label">Data Final <small>(projetada)</small></label>
																<input type="text" class="form-control input-sm leituraOff" readonly="readonly" name="DES_PERIODOREF" id="DES_PERIODOREF" value=""> 
															</div>
														</div>

														<div class="col-md-8 col-xs-5">
															<div class="push10"></div>
															<div class="form-group">
																<?php


																	if($sync > 0 && $dat_iniagendamento != "" && $dat_fimagendamento != "" && mysqli_num_rows($arrayTemplates) > 0 && $qtd_email >= $lista_envio){

																?>

																	<a href="javascript:void(0)" class="btn btn-primary btn-block" onclick="gerarLotes()"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Gerar lote</a>

																<?php 

																	}else if($sync > 0 && $dat_iniagendamento != "" && $dat_fimagendamento != "" && mysqli_num_rows($arrayTemplates) > 0 && $qtd_email < $lista_envio){

																?>

																	<a href="javascript:void(0)" class="btn btn-warning btn-block disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Saldo insuficiente</a>

																<?php 

																	}else if($sync > 0 && $dat_iniagendamento == "" && $dat_fimagendamento == "" && mysqli_num_rows($arrayTemplates) > 0){

																?>

																	<a href="javascript:void(0)" class="btn btn-warning btn-block disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Gatilho não configurado</a>

																<?php 

																	}else if($sync == 0 && $dat_iniagendamento != "" && $dat_fimagendamento != "" && mysqli_num_rows($arrayTemplates) > 0){

																?>

																	<a href="javascript:void(0)" class="btn btn-warning btn-block disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Lista não configurada</a>

																<?php 

																	}else if($sync > 0 && $dat_iniagendamento != "" && $dat_fimagendamento != "" && mysqli_num_rows($arrayTemplates) == 0){

																?>

																	<a href="javascript:void(0)" class="btn btn-warning btn-block disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Nenhuma mensagem configurada na automação</a>

																<?php 

																	}else{

																?>

																	<a href="javascript:void(0)" class="btn btn-warning btn-block disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Gatilho/mensagem/lista não configurados</a>

																<?php 

																	}

																?>
															</div>
														</div>

												<?php 

												} 

												?>

													<div class="col-xs-12">

														<div class="push20"></div>

														<hr>

														<div class="push20"></div>
													
														<div class="col-md-12 col-xs-12">
															<div class="form-group">
																<?php

																	$sql = "SELECT MAX(LOG_OK) AS OK FROM EMAIL_CONTROLE 
																			WHERE COD_EMPRESA = $cod_empresa 
																			AND COD_CAMPANHA = $cod_campanha
																			AND COD_LISTA = (
																							 	SELECT MAX(COD_LISTA) FROM EMAIL_PARAMETROS
																							 	WHERE COD_EMPRESA = $cod_empresa 
																							 	AND COD_CAMPANHA = $cod_campanha
																							)";
																	$qrOk = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

																	if($qrOk['OK'] == 'S'){
																		$log_ok = 'S';
																	}else{
																		$log_ok = 'N';
																	}

																	$sqlVerLote = "SELECT COD_LOTE
																					FROM EMAIL_LOTE
																					WHERE COD_CAMPANHA = $cod_campanha 
																					AND COD_EMPRESA = $cod_empresa
																					AND COD_LOTE != 0
																					AND LOG_TESTE = 'N'
																					AND LOG_ENVIO = 'N'";

																	// fnEscreve($sqlVerLote);

																	$arrayVerLotes = mysqli_query(connTemp($cod_empresa,''),$sqlVerLote);

																	// fnEscreve($log_ok);
																	// fnEscreve(mysqli_num_rows($arrayTemplates));
																	// fnEscreve($tip_gatilho);

																	// fnEscreve($log_processa);

																	if($log_processa == 'S'){

																?>

																	<a href="javascript:void(0)" class="btn btn-success btn-block btn-lg getBtn disabled"><i class="fal fa-check" aria-hidden="true"></i>&nbsp; Campanha Processada</a>

																<?php

																	}else if($qtd_email >= $lista_envio && (($log_ok == 'S' && $sync > 0 && mysqli_num_rows($arrayTemplates) > 0 && mysqli_num_rows($arrayVerLotes) > 0) || ($log_ok == 'S' && $sync > 0 && mysqli_num_rows($arrayTemplates) > 0 && $tip_gatilho != 'individual'))){

																?>

																	<a href="javascript:void(0)" class="btn btn-info btn-block btn-lg getBtn" id="ENV" onclick="enviarLista()"><i class="fal fa-cogs" aria-hidden="true"></i>&nbsp; Processar campanha</a>

																<?php

																	}else if($qtd_email < $lista_envio && (($log_ok == 'S' && $sync > 0 && mysqli_num_rows($arrayTemplates) > 0 && mysqli_num_rows($arrayVerLotes) > 0) || ($log_ok == 'S' && $sync > 0 && mysqli_num_rows($arrayTemplates) > 0 && $tip_gatilho != 'individual'))){

																?>

																	<a href="javascript:void(0)" class="btn btn-warning btn-block btn-lg getBtn disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Saldo insuficiente para processamento da campanha</a>

																<?php

																	}else if($log_ok == 'N' && $sync > 0 && mysqli_num_rows($arrayTemplates) > 0 && mysqli_num_rows($arrayVerLotes) > 0){

																?>

																	<a href="javascript:void(0)" class="btn btn-warning btn-block btn-lg getBtn disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Necessária aprovação para processamento da lista</a>

																<?php

																	}else{

																?>
																
																	<a href="javascript:void(0)" class="btn btn-default btn-block btn-lg getBtn disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Nenhum lote a ser processado</a>

																<?php

																	}

																?>

															</div>
														</div>
													
													</div>														
												
												
												</div>
											
											</div>

											<div class="push50"></div>

											<div class="row">

												<div class="col-xs-12">
													<h5>Integrações</h5>
												</div>

												<div class="push10"></div>
												
												<div class="col-xs-12">

													<div class="no-more-tables">
														
														<table class="table table-bordered table-striped table-hover tableSorter">
														  	<thead>
																<tr>
																  <th>Item</th>
																  <th>Dt. Sincronização</th>
																  <th>Status</th>
																  <th class="{ sorter: false }"></th>
																</tr>
														  	</thead>
															<tbody id="relatorioConteudo">

																<?php

																	// QUERY ESTÁ ACIMA DO BOTÃO DE GERAR LOTE - CAMPANHA

																?>

																<tr>
																	<td><small>Integração da Campanha: <?=$qrIntegra['DES_CAMPANHA']?></small></td>
																	<td><small><?=$dat_cadastrIntegra?></small></td>
																	<td><small><?=$syncMsgIntegra?></small></td>
																	<td class="text-center"><small><?=$integraSync?></small></td>
																</tr>

																<?php

																	// QUERY ESTÁ ACIMA DO BOTÃO DE PROCESSAMENTO - TEMPLATE (MENSAGEM AUTOMAÇÃO)

																	while ($qrTemplate = mysqli_fetch_assoc($arrayTemplates)){

															        	if($qrTemplate['DAT_CADASTR'] != ''){
																			$dat_cadastrTempl = fnDataFull($qrTemplate['DAT_CADASTR']);
																			$templSync = '<span class="fas fa-check text-success"></span>';
																			$syncMsgTempl = "Sincronizado";
																		}else{
																			$dat_cadastrTempl = "";
																			$templSync = '<span class="fas fa-times text-danger"></span>';
																			$syncMsgTempl = "Sincronizando... aguarde.";
																		}
						                                                                                                                    
																?>

																		<tr>
																			<td><small>Template: <?=$qrTemplate['NOM_TEMPLATE']?></small></td>
																			<td><small><?=$dat_cadastrTempl?></small></td>
																			<td><small><?=$syncMsgTempl?></small></td>
																			<td class="text-center"><small><?=$templSync?></small></td>
																		</tr>

																<?php
																}
																?>

															</tbody>

														</table>

													</div>

												</div>

											</div>

											<div class="push10"></div>

											<?php 

												$sql3 = "SELECT ELT.COD_LOTE, 
																ELT.DAT_AGENDAMENTO, 
																ELT.DAT_CADASTR, 
																ELT.COD_STATUSUP,
																ELT.LOG_ENVIO,
																ELT.DES_PATHARQ,
																ELT.COD_PERSONAS,
																ELT.COD_CONTROLE,
																ELT.QTD_LISTA,
																ELT.COD_GERACAO,
																TE.NOM_TEMPLATE
														FROM EMAIL_LOTE ELT
														LEFT JOIN TEMPLATE_EMAIL TE ON TE.COD_EXT_TEMPLATE = ELT.COD_EXT_TEMPLATE
														WHERE ELT.COD_CAMPANHA = $cod_campanha 
														AND ELT.COD_EMPRESA = $cod_empresa
														AND COD_LOTE != 0
														AND LOG_TESTE = 'N'
														ORDER BY ELT.DAT_CADASTR DESC";

												$arrayLotes = mysqli_query(connTemp($cod_empresa,''),$sql3);

												if(mysqli_num_rows($arrayLotes) > 0){ 

											?>

												<div class="row">

													<div class="col-xs-12">
														<h5>Lotes</h5>
													</div>

													<div class="push10"></div>
													
													<div class="col-xs-12">

														<div class="no-more-tables">
															
															<table class="table table-bordered table-striped table-hover tableSorter">
															  <thead>
																<tr>
																  <th width="5%" class="{ sorter: false }"></th>
																  <th>Item</th>
																  <th>Template</th>
																  <th>Lista</th>
																  <th>Qtd. da Lista</th>
																  <th>Dt. Sincronização</th>
																  <th>Dt. Agendamento</th>
																  <th>Status</th>
																  <th class="{ sorter: false }"></th>
																</tr>
															  </thead>
															<tbody id="relatorioConteudo">														

															<?php

																$count = 0;
																$tot_qtd = 0;

																while ($qrLote = mysqli_fetch_assoc($arrayLotes)) {

																	$count++;

																	if($qrLote['DAT_CADASTR'] != ''){

																		$dat_cadastr = fnDataFull($qrLote['DAT_CADASTR']);
																		$dat_agendamento_lote = fnDataFull($qrLote['DAT_AGENDAMENTO']);
																		$urlAnexo = '<a href="'.$qrLote['DES_PATHARQ'].'" download><span class="fa fa-download"></span></a>';
																		$excluiLote = "<a href='javascript:void(0)' onclick='excluiLote(\"".fnEncode($qrLote['COD_GERACAO'])."\")'><span class='fas fa-times text-danger'></span></a>";

																		if($qrLote['COD_STATUSUP'] == 3 && $qrLote['LOG_ENVIO'] == 'N'){
																			$loteSync = '<span class="fas fa-clock text-warning"></span>';
																			$syncMsg = "Aguardando processamento";
																		}else if($qrLote['COD_STATUSUP'] == 3 && $qrLote['LOG_ENVIO'] == 'S'){
																			$loteSync = '<span class="fas fa-check text-success"></span>';
																			$syncMsg = "Sincronizado";
																			$excluiLote = "";
																		}else if($qrLote['COD_STATUSUP'] == 3 && $qrLote['LOG_ENVIO'] == 'C'){
																			$loteSync = '<span class="fas fa-ban text-danger"></span>';
																			$syncMsg = "Nova lista gerada";
																		}else{
																			$loteSync = '<span class="fas fa-exclamation-triangle text-danger"></span>';
																			$syncMsg = "Falha na geração do lote";
																			$urlAnexo = "";
																		}

																	}else{

																		$dat_cadastr = "";
																		$loteSync = '<span class="fas fa-clock text-danger"></span>';
																		$syncMsg = "Sincronizando... aguarde.";

																	}

																	$sqlPers = "SELECT DES_PERSONA FROM PERSONA WHERE COD_PERSONA IN($qrLote[COD_PERSONAS])";
																	$arrayPers = mysqli_query(connTemp($cod_empresa,''),$sqlPers);
																	$personas = "";

																	// fnescreve($qrLote[COD_PERSONAS]);

																	while ($qrPers = mysqli_fetch_assoc($arrayPers)) {
																		$personas = $personas.$qrPers['DES_PERSONA'].", ";
																	}

																	$personas = rtrim(rtrim($personas,' '),',');

															?>

																	<tr>
																		<td class="text-center"><small><?=$urlAnexo?></small></td>
																		<td><small><small><?=$qrLote['COD_GERACAO']?></small>&nbsp;Geração do lote #<?=$qrLote['COD_CONTROLE']?>/<?=$qrLote['COD_LOTE']?></small></td>
																		<td class="text-center"><small><?=$qrLote['NOM_TEMPLATE']?></small></td>
																		<td class="text-center"><small><?=$personas?></small></td>
																		<td class="text-center"><small><?=fnValor($qrLote['QTD_LISTA'],0)?></small></td>
																		<td><small><?=$dat_cadastr?></small></td>
																		<td><small><?=$dat_agendamento_lote?></small></td>
																		<td><small><?=$syncMsg?></small></td>
																		<td class="text-center"><small><?=$loteSync?></small></td>
																		<td class="text-center"><small><?=$excluiLote?></small></td>
																	</tr>

															<?php

																$tot_qtd += $qrLote['QTD_LISTA'];

																}    

															?>
																
																</tbody>

																<tfoot>
																	<tr>
																		<td colspan="4"></td>
																		<td class="text-center"><b><?=fnValor($tot_qtd,0)?></b></td>
																		<td colspan="4"></td>
																	</tr>
																</tfoot>

															</table>														

														</div>
														
													</div>

												</div>

											<?php } ?>

											<!-- <div class="col-xs-12">
												<a href="javascript:void(0)" class="btn btn-primary col-md-4" id="CRIAR_CAMP" <?=$disable_criar_camp?> onclick="wsIbope($(this).attr('id'))"><?=$txt_criar_camp?></a>
												<div class="push10"></div>
												<a href="javascript:void(0)" class="btn btn-primary col-md-4" id="ENVIAR_MODEL" onclick="wsIbope($(this).attr('id'))">Enviar Modelo de e-Mail (WS Ibope)</a>
												<div class="push10"></div>
												<a href="javascript:void(0)" class="btn btn-primary col-md-4 exportarCSV" id="ENVIAR_LISTA">Enviar Lista de e-Mail (WS Ibope)</a>
												<div class="push30"></div>
												<a href="javascript:void(0)" class="btn btn-danger col-md-4" id="DISPARA_LISTA" onclick="wsIbope($(this).attr('id'))">Disparar Lista de e-Mail (WS Ibope)</a>
											</div>	
 -->
											<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
											<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?=$cod_campanha?>">
											<input type="hidden" name="opcao" id="opcao" value="">
											<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

											<div class="push30"></div>

											<div class="col-xs-12" id="load">
												
											</div>

										
										
										
										</form>

										
										
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 

					<!-- <script src="js/plugins/ion.rangeSlider.js"></script> -->
	
	<script type="text/javascript">

		parent.$("#conteudoAba").css("height", $(".portlet").height() + "px");
		//alert($(document).height());

		$(function(){

			var cont = 0;

			$('#loadMore').click(function(){
				$("#load").html('<div class="loading" style="width:100%"></div>')
			});

			$("#CAD").click(function(){
				$("#relatorioConteudo").html('<div class="loading" style="width:100%"></div>')
			});

			var cod_persona = '<?php echo $cod_persona; ?>';
			//alert(cod_persona);
			if(cod_persona != 0 && cod_persona != ""){
				//retorno combo multiplo - USUARIOS_ENV
			$("#formulario #COD_PERSONA").val('').trigger("chosen:updated");

				var sistemasUni = cod_persona;				
				var sistemasUniArr = sistemasUni.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
				  $("#formulario #COD_PERSONA option[value=" + Number(sistemasUniArr[i]).toString() + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_PERSONA").trigger('chosen:updated');

			}

			// $(".exportarCSV").click(function() {
			// 	$.confirm({
			// 		title: 'Exportação',
			// 		content: '' +
			// 		'<form action="" class="formName">' +
			// 		'<div class="form-group">' +
			// 		'<label>Insira o nome do arquivo:</label>' +
			// 		'<input type="text" placeholder="Nome" class="nome form-control" required />' +				
			// 		'</div>' +
			// 		'</form>',
			// 		buttons: {
			// 			formSubmit: {
			// 				text: 'Gerar',
			// 				btnClass: 'btn-blue',
			// 				action: function () {
			// 					var nome = this.$content.find('.nome').val();
			// 					if(!nome){
			// 						$.alert('Por favor, insira um nome');
			// 						return false;
			// 					}
								
			// 					$.confirm({
			// 						title: 'Mensagem',
			// 						type: 'green',
			// 						icon: 'fa fa-check-square-o',
			// 						content: function(){
			// 							var self = this;
			// 							return $.ajax({
			// 								url: "ajxAtivacaoEmail.php?opcao=ENVIAR_LISTA&nomeRel="+nome,
			// 								data: {COD_CAMPANHA:"<?=fnEncode($cod_campanha)?>",COD_EMPRESA:"<?=fnEncode($cod_empresa)?>"},
			// 								method: 'POST'
			// 							}).done(function (response) {
			// 								self.setContentAppend('<div>Listas geradas com sucesso.</div>');
			// 								// var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
			// 								// SaveToDisk('_system/ibope/listas_envio/' + fileName, fileName);
			// 								$("#load").html(response);
			// 								$("#ENVIAR_LISTA").attr("disabled",true).html('Lista Criada!');
			// 							}).fail(function(){
			// 								self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
			// 							});
			// 						},							
			// 						buttons: {
			// 							fechar: function () {
			// 								//close
			// 							}									
			// 						}
			// 					});								
			// 				}
			// 			},
			// 			cancelar: function () {
			// 				//close
			// 			},
			// 		}
			// 	});				
			// });	

		});

		function excluiLote(cod_geracao){
			parent.$.alert({
              title: "Aviso",
              content: "Todos os lotes dessa geração serão excluídos.<br/> Deseja realmente excluir?",
              type: 'orange',
              buttons: {
                "EXCLUIR": {
                  btnClass: 'btn-danger',
                   action: function(){
                     $.ajax({
						method: 'POST',
						url: 'ajxLotesEmail.do?opcao=exc&idg='+cod_geracao,
						data: $("#formulario").serialize(),
						beforeSend:function(){
							$("#blocker").show();
						},
						success:function(data){
							console.log(data);
							$("#blocker").hide();
							parent.$.alert({
				              title: "Sucesso",
				              content: "Lotes excluídos com sucesso!",
				              type: 'green',
				              buttons: {
				                "OK": {
				                  btnClass: 'btn-blue',
				                   action: function(){
				                     location.reload();
				                   }
				                }
				              },
				              backgroundDismiss: true
		            		});
						},
						error:function(){
							console.log('erro 500');
						}
					});
                   }
                },
                "CANCELAR": {
                  btnClass: 'btn-default',
                   action: function(){
                     
                   }
                }
              },
              backgroundDismiss: true
    		});
		}

		function gerarLotes(){

			var qtdNaoProc = "<?=$lotesNaoProcessados?>";

			if($("#QTD_LOTE").val() == ""){

				parent.$.alert({
	              title: "Aviso",
	              content: "Quantidade de lotes não pode ser vazio",
	              type: 'orange',
	              buttons: {
	                "OK": {
	                  btnClass: 'btn-info',
	                    action: function(){
	                   		$("#QTD_LOTE").focus();
	                    }
	                }
	              },
	              backgroundDismiss: true
	    		});

			}else if(qtdNaoProc > 0){

				parent.$.alert({
	              title: "Aviso",
	              content: "Você possui lotes não processados que serão cancelados, caso prossiga.<br/> Deseja realmente prosseguir?",
	              type: 'orange',
	              buttons: {
	                "PROSSEGUIR": {
	                  btnClass: 'btn-primary',
	                    action: function(){
	                   		processaLotes();
	                    }
	                },
	                "CANCELAR": {
	                  btnClass: 'btn-default',
	                    action: function(){
	                     
	                    }
	                }
	              },
	              backgroundDismiss: true
	    		});

			}else{
				processaLotes();
			}
			
		}

		function processaLotes(){
			$.ajax({
				method: 'POST',
				url: 'ajxLotesEmail.do',
				data: $("#formulario").serialize(),
				beforeSend:function(){
					$("#blocker").show();
				},
				success:function(data){
					console.log(data);
					$("#blocker").hide();
					parent.$.alert({
		              title: "Sucesso",
		              content: "Lotes gerados com sucesso!",
		              type: 'green',
		              buttons: {
		                "OK": {
		                  btnClass: 'btn-blue',
		                   action: function(){
		                     location.reload();
		                   }
		                }
		              },
		              backgroundDismiss: true
            		});
				},
				error:function(){
					console.log('erro 500');
				}
			});
		}

		function wsIbope(tipo){
			if(!$("#"+tipo).attr("disabled")){
				// alert(tipo);
				$.ajax({
					method: 'POST',
					url: 'ajxAtivacaoEmail.php?opcao='+tipo,
					data: {COD_CAMPANHA:"<?=fnEncode($cod_campanha)?>",COD_EMPRESA:"<?=fnEncode($cod_empresa)?>"},
					beforeSend:function(){
						$("#"+tipo).html('<div class="loading" style="width:100%"></div>');
					},
					success:function(data){
						var texto = "";
						if(tipo == "CRIAR_CAMP"){
							texto = "Campanha EFM (WS Ibope) criada!";
						}else if(tipo == "ENVIAR_MODEL"){
							texto = "Campanha EFM (WS Ibope) criada!";
						}else if(tipo == "ENVIAR_LISTA"){
							texto = "Campanha EFM (WS Ibope) criada!";
						}else{
							texto = "Campanha EFM (WS Ibope) criada!";
						}
						$("#load").html(data);
						$("#"+tipo).attr("disabled",true).html(texto);
					}
				});
			}
		}

		function enviarLista(){

			$.ajax({
				type: "POST",
				url: "ajxEnvioLista.php?id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&opcao=envio&tipo=processar",
				beforeSend:function(){	
					$('#ENV').text('Processando...');
					$('#ENV').addClass('disabled');
				},
				success:function(data){
					if(data.trim() == "Necessária aprovação para o envio da lista"){
						$('#ENV').html("<span class='fa fa-times'></span>&nbsp;"+data).removeClass('disabled').removeClass('btn-primary').addClass('btn-danger');
					}else{
						// $('#ENV').html("<span class='fa fa-check'></span>&nbsp;Campanha Processada").removeClass('btn-info').addClass('btn-success');
						// window.location.href = "action.php?mod=<?php echo fnEncode(1517)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&alert=true&pop=true";
					}
					// $('#relatorioAjax').html(data);
					console.log(data);
				},
				error:function(){
					alert('Erro ao carregar...');
					// console.log(data);
				}
			});
									
		}
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}

		function calculaPeriodo(){
			periodo_hrs = '',
			qtd_lote = $('#QTD_LOTE').val(),
			des_interval = $('#DES_INTERVAL').val(),
			dat_iniagendamento = "<?=$dat_iniagendamento?>",
			dat_fimagendamento = "<?=$dat_fimagendamento?>";

			if(qtd_lote != "" && des_interval != ""){

				periodo_hrs = qtd_lote * des_interval;

				$.ajax({
					type: "POST",
					url: "ajxCalculaPeriodoLotes.php?id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>",
					data: {PERIODO_HRS:periodo_hrs, DAT_INIAGENDAMENTO:dat_iniagendamento,DAT_FIMAGENDAMENTO:dat_fimagendamento,DES_INTERVAL:des_interval},
					beforeSend:function(){	
						$("#projecaoData").html('<div class="loading" style="width:100%"></div>');
					},
					success:function(data){
						$("#projecaoData").html(data);
					}
				});

			}
		}
		
	</script>	