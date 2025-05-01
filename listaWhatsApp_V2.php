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

			$cod_campanha = fnLimpaCampo($_REQUEST['COD_CAMPANHA']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
			$pct_reserva = fnLimpaCampo($_REQUEST['PCT_RESERVA']);

			if (isset($_POST['COD_PERSONA'])){
				$Arr_COD_PERSONAS = $_POST['COD_PERSONA'];			 
				 
				   for ($i=0;$i<count($Arr_COD_PERSONAS);$i++) 
				   { 
					$cod_personas = $cod_personas.$Arr_COD_PERSONAS[$i].",";
				   } 
				   
				   $cod_personas = rtrim($cod_personas,",");
				   $cod_personas = ltrim($cod_personas,",");
					
			}else{$cod_personas = "0";}

			$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
				//verifica se gera lista completa
				//se tipo TIP_GATILHO = individual

				$sql = "SELECT COD_GATILHO, TIP_GATILHO FROM GATILHO_WHATSAPP WHERE COD_CAMPANHA = $cod_campanha";

				$qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

				$tip_gatilho = $qrCod['TIP_GATILHO'];
				$cod_gatilho = $qrCod['COD_GATILHO'];

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

				$sqlProcCad = "CALL SP_RELAT_WHATSAPP_CLIENTE($cod_empresa, $cod_campanha, '$pct_reserva', '$cod_personas', '$tipo')";
				// fnEscreve($sqlProcCad);
				
				
				$retorno = mysqli_query(connTemp($cod_empresa,''),$sqlProcCad);


				$qrTot = mysqli_fetch_assoc($retorno);

				// echo "<pre>";
				// print_r($qrTot);
				// echo "</pre>";

				// $sql2 = "DELETE FROM WHATSAPP_PARAMETROS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha; ";

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
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sqlCamp = "SELECT DES_CAMPANHA,
										   DES_OBSERVA,
										   DAT_INI,
										   HOR_INI,
										   DAT_FIM,
										   HOR_FIM,
										   TIP_CAMPANHA,
										   LOG_PROCESSA_WHATSAPP
									FROM CAMPANHA 
									WHERE COD_EMPRESA = $cod_empresa 
									AND COD_CAMPANHA = $cod_campanha
									AND LOG_ATIVO = 'S'";
						//fnEscreve($sqlCamp);
						$qrCamp = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCamp));

						$dat_ini = date("d/m/Y H:i",strtotime($qrCamp['DAT_INI']." ".$qrCamp['HOR_INI']));
						$dat_fim = date("d/m/Y H:i",strtotime($qrCamp['DAT_FIM']." ".$qrCamp['HOR_FIM']));
						$des_campanha = fnAcentos($qrCamp['DES_CAMPANHA']);
						$log_processa_whatsapp = $qrCamp['LOG_PROCESSA_WHATSAPP'];

						if($qrCamp['DES_OBSERVA'] != ''){
							$des_observa = fnAcentos($qrCamp['DES_OBSERVA']);
						}else{
							$des_observa = "SEM OBSERVACOES";
						}

						// include './_system/ibope/BuscarCampanha.php';
						// include './_system/ibope/FnIbotpe.php';

						// $cadastrocampanha = array(
						// 					    'nome'=> $des_campanha,
						// 					    'dataInicio'=> $dat_ini,
						// 					    'dataVencimento'=> $dat_fim,
						// 					    'tipoCampanha'=> 1,
						// 					    'objetivo'=> $des_observa,
						// 					    'ativacao'=>'true'
						// 				   	);

						// $retorno = cadastraCampanha ($User,$cadastrocampanha);

						// // echo '<pre>';
						// // print_r($retorno);
						// // echo '</pre>';

						// $cod_ext_campanha = $retorno['body']['envelope']['body']['cadastracampanharesponse']['cadastracampanharesult'];

						// $sql = "UPDATE CAMPANHA SET COD_EXT_CAMPANHA = $cod_ext_campanha, DAT_EXTERNA = NOW() WHERE COD_CAMPANHA = $cod_campanha AND COD_EMPRESA = $cod_empresa";
						// // fnEscreve($sql);
						// mysqli_query(connTemp($cod_empresa,''),$sql);

						$msgRetorno = "Lista gerada com <strong>sucesso!</strong>";	
					break;

					case 'ALT':
						$msgRetorno = "Lista alterada com <strong>sucesso!</strong>";		
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
		$cod_campanha = fnDecode($_GET['idc']);	
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}

	$sqlLista = "SELECT * FROM WHATSAPP_PARAMETROS 
			WHERE COD_LISTA = (
							 	SELECT MAX(COD_LISTA) FROM WHATSAPP_PARAMETROS
							 	WHERE COD_EMPRESA = $cod_empresa 
							 	AND COD_CAMPANHA = $cod_campanha
							  )";

	$qrTot = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlLista));

	$valores_pct = array(	
						0 => 0,
						1 => 5,
						2 => 10,
						3 => 15,
						4 => 20,
						5 => 25,
						6 => 30,
						7 => 35,
						8 => 40,
						9 => 45,
						10 => 50,
						11 => 55,
						12 => 60,
						13 => 65,
						14 => 70,
						15 => 75,
						16 => 80,
						17 => 85,
						18 => 90,
						19 => 95,
						20 => 100
				   );

	if(isset($qrTot)){
		$cod_lista = $qrTot['COD_LISTA'];
		$cod_persona = $qrTot['COD_PERSONAS'];
		$tot_personas = $qrTot['TOT_PERSONAS'];
		$pct_reserva = $qrTot['PCT_RESERVA'];
		$clientes_unicos = $qrTot['CLIENTES_UNICOS'];
		$clientes_unicos_whatsapp = $qrTot['CLIENTES_UNICOS_WHATSAPP'];
		$pct_unicos_whatsapp = ($clientes_unicos_whatsapp * 100 ) / $clientes_unicos;
		$clientes_unico_perc = $qrTot['CLIENTES_UNICO_PERC'];
		$pct_clientes_unico = ($clientes_unico_perc  * 100 ) / $clientes_unicos_whatsapp;		
		$total_cliente_whatsapp_nao = $qrTot['TOTAL_CLIENTE_WHATSAPP_NAO'];
		$pct_sem_whatsapp = ($total_cliente_whatsapp_nao * 100 ) / $clientes_unicos;
		$clientes_optout = $qrTot['CLIENTES_OPTOUT'];
		$pct_optout = ($clientes_optout * 100 ) / $clientes_unicos;
		$clientes_blacklist = $qrTot['CLIENTES_BLACKLIST'];
		$pct_blacklist = ($clientes_blacklist * 100 ) / $clientes_unicos;
		
		//$lista_envio = $clientes_unicos_whatsapp - $clientes_unico_perc - $clientes_blacklist - $clientes_optout;
    //elina pediu pra mudar.
    // $lista_envio = $clientes_unicos - $clientes_unico_perc - $clientes_blacklist - $clientes_optout;
    // AJUSTADO COM ADILSON 17/02/2022
		$lista_envio = $clientes_unicos_whatsapp - $clientes_unico_perc;
                
		$pct_lista = (($lista_envio * 100 ) / $clientes_unicos_whatsapp);
		
		//fnEscreve($clientes_unicos);

	}else{
		$cod_lista = 0;
		$cod_persona = 0;
		$pct_reserva = 0;
		$tot_personas = "0";
		$clientes_unicos = "0";
		$clientes_unicos_whatsapp = "0";
		$clientes_unico_perc = "0";
		$total_cliente_whatsapp_nao = "0";
		$clientes_optout = 0;
		$clientes_blacklist = 0;
		$pct_clientes_unico = 0;
		$pct_sem_whatsapp = 0;
		$pct_optout = 0;
		$pct_blacklist = 0;
	}

	$pct_reservaVl = $pct_reserva;
	$pct_reserva = array_search($pct_reserva, $valores_pct);

	if(fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"])=='1'){
	   $CarregaMaster='1';
	} else {
	   $CarregaMaster='0';
	}

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

      	case 'WHATSAPP':
          if($qrLista['TIP_LANCAMENTO'] == 'D'){
            $qtd_whatsapp = $qtd_whatsapp - $qrLista[QTD_PRODUTO];
          }else{
            $qtd_whatsapp = $qtd_whatsapp + $qrLista[QTD_PRODUTO];
          }
        break;
      
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
    $msgRetornoSaldo = "<span class='fal fa-exclamation-triangle f16'></span><strong> &nbsp;Atenção!</strong> Você possui <strong>".fnValor($qtd_whatsapp,0)."</strong> envios restantes. &nbsp;<a href='https://adm.bunker.mk/action.do?mod=".fnEncode(1485)."&id=".fnEncode($cod_empresa)."' target='_blank' style='color: #FFF; text-decoration: underline;'>Contratar mais envios</a>";

    $sql = "SELECT COD_GATILHO, TIP_GATILHO FROM GATILHO_WHATSAPP WHERE COD_CAMPANHA = $cod_campanha";

	$qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

	$tip_gatilho = $qrCod['TIP_GATILHO'];
	$cod_gatilho = fnLimpaCampoZero($qrCod['COD_GATILHO']);

	if($cod_gatilho != 0){

		if($tip_gatilho == 'individual'){
			$tipo = "CAD";
		}else{
			$tipo = "ANV";
		}

	}
	
	//fnEscreve($pct_reserva);
	//fnescreve($clientes_unicos_whatsapp);
	//fnescreve($clientes_unico_perc);
	//fnMostraForm();

?>
<link rel="stylesheet" href="css/ion.rangeSlider.css" />
<link rel="stylesheet" href="css/ion.rangeSlider.skinHTML5.css" />

<style>
	body{
		overflow: hidden;
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
	
	.c1
	{
		color: #cecece;
	}

	.c2{
		color: #808B96;
	}

	.c3{
		color: #17202A;
	}
	
	h5{
		margin-top: 1px;
		margin-bottom: 30px;
	}
</style>

					<div id="blocker">
				       <div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Processando... ;-)<br/><small>(este processo pode demorar vários minutos)</small></div>
				    </div>
					
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
					              <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
					            </div>
					            <?php include "atalhosPortlet.php"; ?>
					          </div>
					          <?php } 
					          $abaListaEmails = 1651;
					          include "abasListaSms.php";
							  //fnEscreve($sqlLista);						  
							  //fnEscreve($sqlProcCad);
					          ?>
					          <div class="portlet-body">

					          	<!-- <div class="alert <?php echo $msgTipoSaldo; ?> top30 bottom30" role="alert" id="msgRetornoSaldo">
						            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						            <?php echo $msgRetornoSaldo; ?>
					        	</div> -->
					            <?php 
					            if ($msgRetorno <> '') { 
					            ?> 
					            <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
					            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					             <?php echo $msgRetorno; ?>
					            </div>
					            <?php } 
					            ?>
								
								<div class="push30"></div>						
								<h4 style="margin: 0 0 5px 0;"><span class="bolder">Parâmetros de Geração da Lista</span></h4>
								<div class="push20"></div>						
									
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										 
											
											<div class="row">
												
												<div class="col-sm-7 col-xs-12">
													<div class="form-group">
														<label for="inputName" class="control-label">Personas para Geração da Lista</label>
														<div class="push10"></div>
														
															<select data-placeholder="Selecione a persona desejada" name="COD_PERSONA[]" id="COD_PERSONA" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
																<option value=""></option>
																<?php

																	if(fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"])=='1'){

																		$andUnidade = "";

																	} else {

																		$andUnidade = "AND PERSONA.COD_UNIVEND IN($_SESSION[SYS_COD_UNIVEND])";

																	}

																	$sql = "SELECT IFNULL(PERSONAREGRA.COD_REGRA,0) AS TEM_REGRA, 
																			PERSONA.* 
																		 	FROM PERSONA 
																			LEFT JOIN PERSONAREGRA ON PERSONAREGRA.COD_PERSONA = PERSONA.COD_PERSONA
																		 	WHERE COD_EMPRESA = $cod_empresa 
																		 	$andUnidade
																		 	ORDER BY DES_PERSONA ";

																	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

																	while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)){	
                                                                                                                                             
																		if ($qrListaPersonas['LOG_ATIVO'] == "N"){
																			$desabilitado = "disabled";
																			$desabilitadoOnTxt = " (Off)";
																		}else{
																			$desabilitado = "";
																			$desabilitadoOnTxt = "";
																		}

																		if ($qrListaPersonas['TEM_REGRA'] == "0"){
																			$desabilitadoRg = " disabled";
																			$desabilitadoRgTxt = " (s/ regra)";
																		}else{
																			$desabilitadoRg = "";
																			$desabilitadoRgTxt = "";
																		}
																																				
																		echo"
																			  <option value='".$qrListaPersonas['COD_PERSONA']."' ".$desabilitado.$desabilitadoRg.">".ucfirst($qrListaPersonas['DES_PERSONA']).$desabilitadoRgTxt.$desabilitadoOnTxt."</option> 
																			";

																	}

																?>								
															</select>

													</div>             

												</div>
												
												<div class="col-sm-5 col-xs-12">
												<?php if($tip_gatilho != 'individual'){ ?>
													<div class="disabledBlock"></div>
												<?php } ?>
													<div class="form-group">
														<label for="inputName" class="control-label">Percentual de Massa de Comparação (Grupo de Controle)</label>
														<div class="push5"></div>
														<label for="inputName" class="control-label">&nbsp;</label>
														<input type="text" name="PCT_RESERVA" id="PCT_RESERVA" value="" />
													</div>														
												</div>				
																			
											</div>

											<div class="push20"></div>

											<div class="flexrow">

												<div class="col text-center">
													<i class="fal fa-users c2 fa-2x"></i>
													<div class="push3"></div>
													<span class="f18" id="TOT_PERSONAS"><span class="c3 f18"><?=fnValor($tot_personas,0)?></span> &nbsp;
														<span class="f14 c1">&nbsp;</span>
													</span> 
													<h5 class="c2">Personas Selecionadas</h5>
													<div class="push20"></div>
												</div>
																	
												<div class="col text-center">
													<i class="fal fa-user-tag c2 fa-2x"></i>
													<div class="push3"></div>
													<span class="f18" id="CLIENTES_UNICOS"><span class="c3 f18"><?=fnValor($clientes_unicos,0)?></span> &nbsp;
														<span class="f14 c1">&nbsp;</span>
													</span> 
													<h5 class="c2">Clientes Únicos</h5>
													<div class="push20"></div>
												</div>

												<div class="col text-center">
													<i class="fal fa-phone c2 fa-2x"></i>
													<div class="push3"></div>
													<span class="f18" id="CLIENTES_UNICOS_WHATSAPP"><span class="c3 f18"><?=fnValor($clientes_unicos_whatsapp,0)?></span> &nbsp;
														<span class="f14 c1"><?=fnValor($pct_unicos_whatsapp,2)?>%</span> 
													</span> 
													<h5 class="c2">Clientes Únicos Com Celular</h5>
													<div class="push20"></div>
												</div>

												<div class="col text-center">
													<i class="fal fa-phone-slash c2 fa-2x"></i>
													<div class="push3"></div>
													<span class="f18" id="TOTAL_CLIENTE_WHATSAPP_NAO"><span class="c3 f18"><?=fnValor($total_cliente_whatsapp_nao,0)?></span> &nbsp;
														<span class="f14 c1"><?=fnValor($pct_sem_whatsapp,2)?>%</span>
													</span>  
													<h5 class="c2">Clientes Sem Celular</h5>
													<div class="push20"></div>
												</div>
												
											</div>

											<div class="flexrow">

												<div class="col text-center">
													<i class="fal fa-user-minus c2 fa-2x"></i>
													<div class="push3"></div>
													<span class="f18" id="CLIENTES_OPTOUT"><span class="c3 f18"><?=fnValor($clientes_optout,0)?></span> &nbsp;
														<span class="f14 c1"><?=fnValor($pct_optout,2)?>%</span> 
													</span> 
													<h5 class="c2">Clientes Opt Out</h5>
													<div class="push20"></div>
												</div>

												<div class="col text-center">
													<i class="fal fa-user-times c2 fa-2x"></i>
													<div class="push3"></div>
													<span class="f18" id="CLIENTES_BLACKLIST"><span class="c3 f18"><?=fnValor($clientes_blacklist,0)?></span> &nbsp;
														<span class="f14 c1"><?=fnValor($pct_blacklist,2)?>%</span> 
													</span> 
													<h5 class="c2">Clientes Black List</h5>
													<div class="push20"></div>
												</div>

												<div class="col text-center">
													<i class="fal fa-user-lock c2 fa-2x"></i>
													<div class="push3"></div>
													<span class="f18" id="CLIENTES_UNICO_PERC"><span class="c3 f18"><?=fnValor($clientes_unico_perc,0)?></span> &nbsp;
														<span class="f14 c1"><?=fnValor($pct_reservaVl,2)?>%</span> 
													</span>  
													<h5 class="c2">Grupo de Controle</h5>
													<div class="push20"></div>
												</div>

												<div class="col text-center">
													<i class="fal fa-paper-plane c2 fa-2x"></i>
													<div class="push3"></div>
													<span class="f18" id="LISTA_ENVIO"><span class="c3 f18"><?=fnValor($lista_envio,0)?></span> &nbsp;
														<span class="f14 c1"><?=fnValor($pct_lista,2)?>%</span> 
													</span>  
													<h5 class="c2">Lista de Envio</h5>
													<div class="push20"></div>
												</div>
												
											</div>

											<!-- <div class="push10"></div>

											<div class="row">
												
												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label required">Manter Pessoas em Cópia</label>
														<input type="text" class="form-control input-sm" name="DES_PESSOAS" id="DES_PESSOAS" value="<?php echo "" ?>">
													</div>														
												</div>

											</div> -->

											<div class="push10"></div>
																				
											<div class="push10"></div>
											<hr>	
											<div class="form-group text-right col-lg-12">
											
											<?php
												if($cod_gatilho == 0){ 
											?>
												<a href="javascript:void(0)" class="btn btn-warning disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Nenhum gatilho configurado na automação</a>
											<?php 
												}else if($cod_lista == 0){ 
											?>
												<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Gerar lista de envio</button>
											<?php 
												}else if($cod_lista != 0){ 
											?>
												<div class="col-xs-2 col-xs-offset-8">
					
													<div class="dropdown">
														<a class="dropdown-toggle btn btn-info" data-toggle="dropdown" href="#">
															<span class="fal fa-file-excel"></span> Exportar
														</a>
														<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
															<?php if($log_processa_whatsapp == "S"){$tipoExport = 'sent';}else{$tipoExport = 'list';} ?>
															<li><a tabindex="-1" href="javascript:void(0);" onclick='parent.exportaLista("<?=$tipoExport?>",0)'>Lista de Envio</a></li>
															<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaLista('ctrl',0)">Grupo de Controle</a></li>
															<!-- <li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaLista('sent',0)">Entregues</a></li>
															<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaLista('links',0)">Links</a></li>
															<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaLista('lidos',0)">Lidos</a></li>
															<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaLista('nlidos',0)">Não-lidos</a></li>
															<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaLista('hbounce',0)">Hardbounce</a></li>
															<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaLista('sbounce',0)">Softbounce</a></li>
															<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaLista('optout',0)">Opt-Out</a></li> -->
															<!-- <li class="divider"></li> -->
															<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
														</ul>
													</div>

												</div>

												<div class="col-xs-2">

													<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar lista de envio</button>

												</div>
											<?php 
												}
											?>

												
											
											</div>

										<input type="hidden" name="COD_GRUPOTR" id="COD_GRUPOTR" value="">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
										<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?=$cod_campanha?>">
										<input type="hidden" name="TOTAL_PERSONAS" id="TOTAL_PERSONAS" value="<?=0?>">
										<input type="hidden" name="CLIENTES_UNICOS" id="CLIENTES_UNICOS" value="<?=0?>">
										<input type="hidden" name="CLIENTES_UNICOS_WHATSAPP" id="CLIENTES_UNICOS_WHATSAPP" value="<?=0?>">
										<input type="hidden" name="CLIENTES_UNICO_PERC" id="CLIENTES_UNICO_PERC" value="<?=0?>">
										<input type="hidden" name="TOTAL_CLIENTE_WHATSAPP_NAO" id="TOTAL_CLIENTE_WHATSAPP_NAO" value="<?=0?>">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										
										
										</form>

										<div class="push30"></div>
										
									</div>

									<div class="col-md-12">
										<a href="javascript:void(0)" class="btn btn-primary" onclick="proximoPasso()">Próximo Passo&nbsp;&nbsp;<span class="fal fa-arrow-right"></span></a>
									</div>
									<div class="push100"></div>							
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 

					<script src="js/plugins/ion.rangeSlider.js"></script>
	
	<script type="text/javascript">

		parent.$("#conteudoAba").css("height", ($(".portlet").height()+50) + "px");

		$(function(){

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
				$("#formulario #COD_PERSONA").trigger("chosen:updated");
			}

			$("#PCT_RESERVA").ionRangeSlider({
		        grid: true,
		        from: <?=$pct_reserva?>,
		        values: [
		            0, 5, 10, 15, 20, 25,
		            30, 35, 40, 45, 50, 55,
		            60, 65, 70, 75, 80, 85, 90, 95, 100
		        ]
		    });

		});

		function proximoPasso(){

			var listaConfig = "<?=$cod_lista?>";

			if(listaConfig != 0){

				parent.$('#APROVACAO').click();

			}else{

				parent.$.alert({
	              title: "Aviso",
	              content: "Nenhuma lista foi gerada, e não será possível ativar a campanha. Deseja prosseguir?",
	              type: 'orange',
	              buttons: {
	                "PROSSEGUIR": {
	                  btnClass: 'btn-primary',
	                    action: function(){
	                   		parent.$('#APROVACAO').click();
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

		}
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	