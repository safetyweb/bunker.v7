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
			
			$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo = 'N';}else{$log_ativo = $_REQUEST['LOG_ATIVO'];}


			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);
			$cod_gatilho = fnLimpaCampoZero($_REQUEST['COD_GATILHO']);
			$tip_gatilho = fnLimpaCampo($_REQUEST['TIP_GATILHO']);
			$tip_controle = fnLimpaCampoZero($_REQUEST['TIP_CONTROLE']);
			$tip_momento = fnLimpaCampoZero($_REQUEST['TIP_MOMENTO']);
			$hor_especif = fnLimpaCampoZero($_REQUEST['HOR_ESPECIF']);
			$des_periodo = fnLimpaCampoZero($_REQUEST['DES_PERIODO']);
			$des_periodomin = fnLimpaCampoZero($_REQUEST['DES_PERIODOMIN']);
			$des_periodomax = fnLimpaCampoZero($_REQUEST['DES_PERIODOMAX']);
			$dias_anteced = fnLimpaCampoZero($_REQUEST['DIAS_ANTECED']);
			$dias_hist = fnLimpaCampoZero($_REQUEST['DIAS_HIST']);
			$dat_ini_campanha = $_REQUEST['DAT_INI_CAMPANHA'];
			$dat_fim_campanha = $_REQUEST['DAT_FIM_CAMPANHA'];
			$dat_ini = fnDatasql($_REQUEST['DAT_INI']);
			$dat_fim = fnDatasql($_REQUEST['DAT_FIM']);
			$hor_ini = fnLimpaCampo($_REQUEST['HOR_INI']);
			$hor_fim = fnLimpaCampo($_REQUEST['HOR_FIM']);
			$tot_saldomin = fnLimpaCampo($_REQUEST['TOT_SALDOMIN']);
			$log_status = 'S';

			if($tip_gatilho == "venda"){
				$tot_saldomin = fnLimpaCampo($_REQUEST['TOT_SALDOMIN2']);
			}

			if(strpos($tot_saldomin, ',') !== false){
				$tot_saldomin = fnValorSql($tot_saldomin);
				// fnEscreve('if');
			}else{
				$tot_saldomin = number_format($tot_saldomin,0,',','.');
				// fnEscreve('else');
			}

			if($tip_gatilho == "aniv"){
				$dias_anteced = fnLimpaCampoZero($_REQUEST['DIAS_ANTECED_ANIV']);
			}

			if (empty($_REQUEST['LOG_DOMINGO'])) {$log_domingo='N';}else{$log_domingo=$_REQUEST['LOG_DOMINGO'];}
			if (empty($_REQUEST['LOG_SEGUNDA'])) {$log_segunda='N';}else{$log_segunda=$_REQUEST['LOG_SEGUNDA'];}
			if (empty($_REQUEST['LOG_TERCA'])) {$log_terca='N';}else{$log_terca=$_REQUEST['LOG_TERCA'];}
			if (empty($_REQUEST['LOG_QUARTA'])) {$log_quarta='N';}else{$log_quarta=$_REQUEST['LOG_QUARTA'];}
			if (empty($_REQUEST['LOG_QUINTA'])) {$log_quinta='N';}else{$log_quinta=$_REQUEST['LOG_QUINTA'];}
			if (empty($_REQUEST['LOG_SEXTA'])) {$log_sexta='N';}else{$log_sexta=$_REQUEST['LOG_SEXTA'];}
			if (empty($_REQUEST['LOG_SABADO'])) {$log_sabado='N';}else{$log_sabado=$_REQUEST['LOG_SABADO'];}
			$agora = date("Y-m-d H:i:s");
			$hoje_mais_hora = date("Y-m-d H:i:s");
			$dat_ini_gatilho = $dat_ini." ".$hor_ini;
			$dat_fim_gatilho = $dat_fim." ".$hor_fim;

			if($tip_gatilho == "credExp"){
				$des_periodomax = fnLimpaCampoZero($_REQUEST['DIAS_PRECED']);
			}
			
			if($_REQUEST['HOR_ESPECIF_VAL'] != ""){
				$hor_especif = fnLimpaCampoZero($_REQUEST['HOR_ESPECIF_VAL']);
			}
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			// fnEscreve($hor_especif);
			
			if($tip_controle == 99){
				$tip_momento = 99;
			}

			if($des_periodo == 99){
				$tip_momento = 99;
			}
                      
			if ($opcao != ''){

				$sqlDel = "DELETE FROM EMAIL_FILA WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";
				mysqli_query(connTemp($cod_empresa,''),$sqlDel);

				if($tip_gatilho == 'individual'){

					$hoje_mais_hora = date("Y-m-d H:i:s",strtotime($hor_ini));

					$dat_ini_campanha = $dat_ini_gatilho;

					if($dat_fim_gatilho <= $dat_fim_campanha){
						$dat_fim_campanha = $dat_fim_gatilho;
					}

				}else if($tip_gatilho == 'anivDia' || $tip_gatilho == 'anivMes' || $tip_gatilho == 'anivAno'){

					if($dat_ini_campanha >= $agora){
						$hoje_mais_hora = date("Y-m-d H:i:s",strtotime($dat_ini_campanha));
					}else{
						$hoje_mais_hora = date("Y-m-d H:i:s",strtotime($hor_especif.":00"));
					}

				}else{

					$hoje_mais_hora = $agora;
					
				}

				if($hoje_mais_hora >= $dat_ini_campanha){
					$dat_ini_campanha = $hoje_mais_hora;
				}

				if($dat_ini_campanha < $agora){
					$dat_ini_campanha = date("Y-m-d H:i:s",strtotime($dat_ini_campanha." +1 days"));
				}



				// fnEscreve($hoje_mais_hora);		
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$cod_gatilho = "(SELECT MAX(COD_GATILHO) 
													 FROM GATILHO_EMAIL 
													 WHERE COD_EMPRESA = $cod_empresa 
													 AND COD_CAMPANHA = $cod_campanha 
													 AND COD_USUARIO = $cod_usucada)";

						if($tip_gatilho == 'individual'){							

							$sql = "INSERT INTO GATILHO_EMAIL(
													COD_EMPRESA,
													COD_CAMPANHA,
													TIP_GATILHO,
													TIP_CONTROLE,
													TIP_MOMENTO,
													HOR_ESPECIF,
													DAT_INI,
													-- DAT_FIM,
													HOR_INI,
													-- HOR_FIM,
													LOG_DOMINGO,
													LOG_SEGUNDA,
													LOG_TERCA,
													LOG_QUARTA,
													LOG_QUINTA,
													LOG_SEXTA,
													LOG_SABADO,
													COD_USUARIO
												) VALUES(
													'$cod_empresa',
													'$cod_campanha',
													'$tip_gatilho',
													'$tip_controle',
													'$tip_momento',
													'$hor_especif',
													'$dat_ini',
													-- '$dat_fim',
													'$hor_ini',
													-- '$hor_fim',
													'$log_domingo',
													'$log_segunda',
													'$log_terca',
													'$log_quarta',
													'$log_quinta',
													'$log_sexta',
													'$log_sabado',
													'$cod_usucada'
												);";

								$sql .= "INSERT INTO AGENDA_MAIL(
													COD_EMPRESA,
													COD_CAMPANHA,
													COD_GATILHO,
													TIP_GATILHO,
													DAT_INIAGENDAMENTO,
													DAT_FIMAGENDAMENTO,
													COD_USUCADA
												) VALUES(
													'$cod_empresa',
													'$cod_campanha',
													'$cod_gatilho',
													'$tip_gatilho',
													 NOW(),
													'$dat_fimagendamento',
													'$cod_usucada'
												);";

							}else{

								$sql = "INSERT INTO GATILHO_EMAIL(
													COD_EMPRESA,
													COD_CAMPANHA,
													TIP_GATILHO,
													TIP_CONTROLE,
													TIP_MOMENTO,
													DES_PERIODO,
													DES_PERIODOMIN,
													DES_PERIODOMAX,
													HOR_ESPECIF,
													TOT_SALDOMIN,
													DIAS_ANTECED,
													DIAS_HIST,
													DAT_INI,
													-- DAT_FIM,
													HOR_INI,
													-- HOR_FIM,
													LOG_DOMINGO,
													LOG_SEGUNDA,
													LOG_TERCA,
													LOG_QUARTA,
													LOG_QUINTA,
													LOG_SEXTA,
													LOG_SABADO,
													COD_USUARIO
												) VALUES(
													'$cod_empresa',
													'$cod_campanha',
													'$tip_gatilho',
													'$tip_controle',
													'$tip_momento',
													'$des_periodo',
													'$des_periodomin',
													'$des_periodomax',
													'$hor_especif',
													$tot_saldomin,
													'$dias_anteced',
													'$dias_hist',
													 null,
													-- null,
													'$hor_ini',
													-- '$hor_fim',
													'$log_domingo',
													'$log_segunda',
													'$log_terca',
													'$log_quarta',
													'$log_quinta',
													'$log_sexta',
													'$log_sabado',
													'$cod_usucada'
												);";

								

							}

							$sql .= "INSERT INTO CONTROLE_SCHEDULE(
													COD_EMPRESA,
													COD_CAMPANHA,
													TIP_GATILHO,
													COD_USUCADA
												) VALUES(
													'$cod_empresa',
													'$cod_campanha',
													'$tip_gatilho',
													'$cod_usucada'
												);";
							
						// fnEscreve($sql);

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						if($tip_gatilho == 'individual'){

							$sql = "UPDATE GATILHO_EMAIL SET
													TIP_GATILHO = '$tip_gatilho', 
													TIP_CONTROLE = '$tip_controle', 
													TIP_MOMENTO = '$tip_momento',  
													HOR_ESPECIF = '$hor_especif',
													DAT_INI = '$dat_ini', 
													-- DAT_FIM = '$dat_fim', 
													HOR_INI = '$hor_ini', 
													-- HOR_FIM = '$hor_fim', 
													LOG_DOMINGO = '$log_domingo', 
													LOG_SEGUNDA = '$log_segunda', 
													LOG_TERCA = '$log_terca', 
													LOG_QUARTA = '$log_quarta', 
													LOG_QUINTA = '$log_quinta', 
													LOG_SEXTA = '$log_sexta', 
													LOG_SABADO = '$log_sabado' 
									WHERE COD_GATILHO = $cod_gatilho 
									AND COD_EMPRESA = $cod_empresa;";


						}else{

							$sql = "UPDATE GATILHO_EMAIL SET
													TIP_GATILHO = '$tip_gatilho', 
													TIP_CONTROLE = '$tip_controle', 
													TIP_MOMENTO = '$tip_momento', 
													DES_PERIODO = '$des_periodo', 
													DES_PERIODOMIN = '$des_periodomin', 
													DES_PERIODOMAX = '$des_periodomax', 
													HOR_ESPECIF = '$hor_especif', 
													TOT_SALDOMIN = $tot_saldomin,
													DIAS_ANTECED = '$dias_anteced',
													DIAS_HIST = '$dias_hist',
													DAT_INI = null, 
													DAT_FIM = null, 
													HOR_INI = '$hor_ini', 
													HOR_FIM = '$hor_fim', 
													LOG_DOMINGO = '$log_domingo', 
													LOG_SEGUNDA = '$log_segunda', 
													LOG_TERCA = '$log_terca', 
													LOG_QUARTA = '$log_quarta', 
													LOG_QUINTA = '$log_quinta', 
													LOG_SEXTA = '$log_sexta', 
													LOG_SABADO = '$log_sabado' 
									WHERE COD_GATILHO = $cod_gatilho 
									AND COD_EMPRESA = $cod_empresa;";

						}

						$sql .= "UPDATE CONTROLE_SCHEDULE SET
													TIP_GATILHO = '$tip_gatilho'
								 WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha;";
							
						// fnEscreve($sql);

						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
					break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
					break;
				}



				$sql .= "DELETE FROM AGENDA_MAIL WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha AND DAT_INIAGENDAMENTO >= '".date('Y-m-d H:i:s')."';";

				$nro_dias = fnDateDif($hoje_mais_hora,$dat_fim_campanha);
				$diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');

				// fnescreve($hoje_mais_hora);
				// fnescreve(date("Y-m-d H:i:s",strtotime($dat_fim_campanha)));
				// fnescreve($nro_dias);

				if($tip_gatilho == 'anivDia'){

					for ($i=0; $i <= $nro_dias ; $i++) {

						$datahora = date("Y-m-d H:i:s",strtotime($hoje_mais_hora." +".$i." days"));

						$diasemana_numero = date('w', strtotime($datahora));

						if($diasemana[$diasemana_numero]=='Domingo'){
	                        if($log_domingo == 'S'){
	                           $cod_diassemans='1';
	                        }    
	                    }elseif ($diasemana[$diasemana_numero]=='Segunda'){
	                        if($log_segunda == 'S'){
	                          $cod_diassemans='1';  
	                        }  
	                    } elseif ($diasemana[$diasemana_numero]=='Terça'){
	                        if($log_terca == 'S'){
	                           $cod_diassemans='1'; 
	                        }  
	                    }  elseif ($diasemana[$diasemana_numero]=='Quarta'){ 
	                        if($log_quarta == 'S'){
	                          $cod_diassemans='1';  
	                        }  
	                    } elseif ($diasemana[$diasemana_numero]=='Quinta'){
	                        if($log_quinta == 'S'){
	                           $cod_diassemans='1'; 
	                        }  
	                    } elseif ($diasemana[$diasemana_numero]=='Sexta'){
	                        if($log_sexta == 'S'){
	                           $cod_diassemans='1'; 
	                        }  
	                    } elseif ($diasemana[$diasemana_numero]=='Sabado'){
	                        if($log_sabado == 'S'){
	                           $cod_diassemans='1'; 
	                        }  
	                    } else {
	                      $cod_diassemans='0';  
	                    }

	                    // fnEscreve($cod_diassemans);

	                    if($cod_diassemans == '1'){
	                    	$sql .= "INSERT INTO AGENDA_MAIL(
											COD_EMPRESA,
											COD_CAMPANHA,
											COD_GATILHO,
											TIP_GATILHO,
											DAT_INIAGENDAMENTO,
											DAT_FIMAGENDAMENTO,
											COD_USUCADA
										) VALUES(
											'$cod_empresa',
											'$cod_campanha',
											 $cod_gatilho,
											'$tip_gatilho',
											'$datahora',
											'$dat_fim_campanha',
											'$cod_usucada'
										);";
							// fnEscreve($sql);
	                    }								

					}

				}

				?>
					<script>
						parent.mudaAba(parent.$('#conteudoAba').attr('src')+"&rnd="+Math.random());
					</script>
				<?php
				mysqli_multi_query(connTemp($cod_empresa,''),$sql);

				$msgTipo = 'alert-success';
			}                
		}
	}
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
            
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$cod_campanha = fnDecode($_GET['idC']);

		$sql = "SELECT NOM_EMPRESA, TIP_RETORNO, NUM_DECIMAIS_B FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;	
		
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
			$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
			$num_decimais_b = $qrBuscaEmpresa['NUM_DECIMAIS_B'];

			if($tip_retorno == 1){
				$casasDec = 0;
				$classeFormata = "int";
			}else{
				$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
				$classeFormata = "money";
			}
		}
												
	}else {	
		$nom_empresa = "";
		$num_decimais_b = 2;
		$classeFormata = "money";
	}

	// fnEscreve($cod_campanha);
	
	if($cod_campanha != 0){
		$sql = "SELECT * FROM GATILHO_EMAIL WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";
		$qrGat = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

		$cod_gatilho = $qrGat['COD_GATILHO'];
		$tip_gatilho = $qrGat['TIP_GATILHO'];
		$tip_controle = $qrGat['TIP_CONTROLE'];
		$tip_momento = $qrGat['TIP_MOMENTO'];
		$hor_especif = $qrGat['HOR_ESPECIF'];
		$tot_saldomin = fnValor($qrGat['TOT_SALDOMIN'],$casasDec);
		$des_periodo = $qrGat['DES_PERIODO'];
		$dias_anteced = $qrGat['DIAS_ANTECED'];
		$dias_hist = $qrGat['DIAS_HIST'];
		$des_periodomin = $qrGat['DES_PERIODOMIN'];
		$des_periodomax = $qrGat['DES_PERIODOMAX'];
		$dat_ini = fnDataShort($qrGat['DAT_INI']);
		$hor_ini = $qrGat['HOR_INI'];
		$dat_fim = fnDataShort($qrGat['DAT_FIM']);
		$hor_fim = $qrGat['HOR_FIM'];
		$log_domingo = $qrGat['LOG_DOMINGO'];
		$log_segunda = $qrGat['LOG_SEGUNDA'];
		$log_terca = $qrGat['LOG_TERCA'];
		$log_quarta = $qrGat['LOG_QUARTA'];
		$log_quinta = $qrGat['LOG_QUINTA'];
		$log_sexta = $qrGat['LOG_SEXTA'];
		$log_sabado = $qrGat['LOG_SABADO'];

	}else{
		$cod_gatilho = "";
		$tip_gatilho = "";
		$tip_controle = "";
		$tip_momento = "";
		$des_periodo = "";
		$des_periodomin = "";
		$des_periodomax = "";
		$hor_especif = "";
		$dias_anteced = "";
		$dias_hist = "";
		$dat_ini = "";
		$hor_ini = "";
		$dat_fim = "";
		$hor_fim = "";
		$log_domingo="N";
		$log_segunda="N";
		$log_terca="N";
		$log_quarta="N";
		$log_quinta="N";
		$log_sexta="N";
		$log_sabado="N";
	}

	$sql = "SELECT TIP_CAMPANHA,
				   DAT_INI,
				   HOR_INI,
				   DAT_FIM,
				   HOR_FIM 
			FROM CAMPANHA 
			WHERE COD_CAMPANHA = $cod_campanha 
			AND COD_EMPRESA = $cod_empresa";
	$qrTip = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
	$tip_campanha = $qrTip['TIP_CAMPANHA'];
	$dat_ini_campanha = date("Y-m-d H:i:s",strtotime($qrTip['DAT_INI']." ".$qrTip['HOR_INI']));
	$dat_fim_campanha = date("Y-m-d H:i:s",strtotime($qrTip['DAT_FIM']." ".$qrTip['HOR_FIM']));

	// fnEscreve($dat_ini);
	// fnEscreve($dat_fim);

	if($tip_campanha == 20){
		$inibSort = "";
		$inibCupom = "";
	}else{
		$inibSort = "disabled";
		$inibCupom = "disabled";
	}

	// if($log_domingo == 'S'){$checkDomingo = 'checked';}else{$checkDomingo = '';}
	// if($log_segunda == 'S'){$checkSegunda = 'checked';}else{$checkSegunda = '';}
	// if($log_terca == 'S'){$checkTerca = 'checked';}else{$checkTerca = '';}
	// if($log_quarta == 'S'){$checkQuarta = 'checked';}else{$checkQuarta = '';}
	// if($log_quinta == 'S'){$checkQuinta = 'checked';}else{$checkQuinta = '';}
	// if($log_sexta == 'S'){$checkSexta = 'checked';}else{$checkSexta = '';}
	// if($log_sabado == 'S'){$checkSabado = 'checked';}else{$checkSabado = '';}

	// fnEscreve($log_domingo);

	$sqlVerGat = "SELECT GE.COD_GATILHO, CP.DES_CAMPANHA 
				  FROM GATILHO_EMAIL GE
				  INNER JOIN CAMPANHA CP ON CP.COD_CAMPANHA = GE.COD_CAMPANHA
				  AND CP.COD_EMPRESA = GE.COD_EMPRESA
				  WHERE GE.COD_EMPRESA = $cod_empresa 
				  AND GE.COD_CAMPANHA != $cod_campanha
				  AND CP.LOG_ATIVO = 'S'
				  AND GE.TIP_GATILHO = '$tip_gatilho'";

	$arrayVer = mysqli_query(connTemp($cod_empresa,''),$sqlVerGat);

	$gatilhoExiste = mysqli_num_rows($arrayVer);

?>

<style type="text/css">
	.leitura2{
		border: none transparent !important;
		outline: none !important;
		background: #fff !important;
		font-size: 18px;
		padding: 0;
	}
	.chosen-container{
		width: 100%!important;
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

						<?php if ($gatilhoExiste == 1) { ?>	
						<div class="alert alert-warning top30 bottom30" role="alert">
						 Você já possui outra campanha ativa com esse gatilho.
						</div>
						<?php } ?>	
													
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
						<legend>Configuração da Geração e Envios</legend>

							<div class="row" >

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Início da Campanha</label>
										<input type="text" class="form-control input-sm leitura2" readonly="readonly" name="DAT_INI_REF" id="DAT_INI_REF" value="<?php echo fnDataFull($dat_ini_campanha); ?>"> 
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Fim da Campanha</label>
										<input type="text" class="form-control input-sm leitura2" readonly="readonly" name="DAT_FIM_REF" id="DAT_FIM_REF" value="<?php echo fnDataFull($dat_fim_campanha); ?>"> 
									</div>
								</div>

							</div>
					
							<div class="row">
					
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Gatilho</label>
											<select data-placeholder="Selecione o momento" name="TIP_GATILHO" id="TIP_GATILHO" class="chosen-select-deselect" style="width:100%;">
												<option value=""></option>

												<optgroup label="Disparos instantâneos ($$)">
													<?php
														$sqlGat1 = "SELECT TIP_GATILHO, DES_GATILHO 
																	FROM GATILHOS_COMUNICACAO
																	 WHERE COD_GRUPO = 0";
														$arrayGat1 = mysqli_query($connAdm->connAdm(),$sqlGat1);
														while($qrGat1 = mysqli_fetch_assoc($arrayGat1)){

															if($qrGat1[TIP_GATILHO] != "vendasFast"){

													?>																     
																<option value="<?=$qrGat1[TIP_GATILHO]?>"><?=$qrGat1['DES_GATILHO']?></option>
													<?php 
															}	
														} 
													?>
												</optgroup>

												<optgroup label="Disparos individuais">
													<?php
														$sqlGat1 = "SELECT TIP_GATILHO, DES_GATILHO 
																	FROM GATILHOS_COMUNICACAO
																	 WHERE COD_GRUPO = 1";
														$arrayGat1 = mysqli_query($connAdm->connAdm(),$sqlGat1);
														while($qrGat1 = mysqli_fetch_assoc($arrayGat1)){

													?>																     
														<option value="<?=$qrGat1[TIP_GATILHO]?>"><?=$qrGat1['DES_GATILHO']?></option>
													<?php 
														} 
													?>
												</optgroup>

												<optgroup label="Disparos em massa">
													<?php
														$sqlGat2 = "SELECT TIP_GATILHO, DES_GATILHO 
																	FROM GATILHOS_COMUNICACAO
																	 WHERE COD_GRUPO = 2";
														$arrayGat2 = mysqli_query($connAdm->connAdm(),$sqlGat2);
														while($qrGat2 = mysqli_fetch_assoc($arrayGat2)){

													?>																     
														<option value="<?=$qrGat2[TIP_GATILHO]?>"><?=$qrGat2['DES_GATILHO']?></option>
													<?php 
														} 
													?>
												</optgroup>

												<optgroup label="Disparos contínuos">
													<?php
														$sqlGat3 = "SELECT TIP_GATILHO, DES_GATILHO 
																	FROM GATILHOS_COMUNICACAO
																	WHERE COD_GRUPO = 3
																	AND COD_GATILHO NOT IN(7,8)";
														$arrayGat3 = mysqli_query($connAdm->connAdm(),$sqlGat3);
														while($qrGat3 = mysqli_fetch_assoc($arrayGat3)){

													?>																     
														<option value="<?=$qrGat3[TIP_GATILHO]?>"><?=$qrGat3['DES_GATILHO']?></option>
													<?php 
													} 
													?>
												</optgroup>												
												
											</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3" id="blocoToken" style="display: none;">
									<div class="form-group">
										<label for="inputName" class="control-label">Validade do Token</label>
											<select data-placeholder="Selecione o tempo" name="HOR_ESPECIF_VAL" id="HOR_ESPECIF_VAL" class="chosen-select-deselect" style="width:100%;">
												<option value=""></option>																     
												<option value="1">1 Hora</option>
											</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3" id="blocoFrequencia">
									<div class="form-group">
										<label for="inputName" class="control-label">Frequência Geração de Dados</label>
											<select data-placeholder="Selecione o período" name="DES_PERIODO" id="DES_PERIODO" class="chosen-select-deselect" style="width:100%;">
												<option value=""></option>
												<?php
													$sqlFreq = "SELECT TIP_FREQUENCIA, DES_FREQUENCIA 
																FROM FREQUENCIA_COMUNICACAO";
													$arrayFreq = mysqli_query($connAdm->connAdm(),$sqlFreq);
													while($qrFreq = mysqli_fetch_assoc($arrayFreq)){

												?>																     
													<option value="<?=$qrFreq[TIP_FREQUENCIA]?>"><?=$qrFreq['DES_FREQUENCIA']?></option>
												<?php 
												} 
												?>
											</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3" id="blocoAniv">
									<div class="form-group">
										<label for="inputName" class="control-label">Antecedência</label>
											<select data-placeholder="Selecione a quantidade" name="DIAS_ANTECED_ANIV" id="DIAS_ANTECED_ANIV" class="chosen-select-deselect" style="width:100%;">
												<option value=""></option>
												<option value="0">Nenhuma</option>											  
												<option value="1">1 dia</option>											  
												<option value="7">7 dias</option>											  
												<option value="15">15 dias</option>											  
												<option value="21">21 dias</option>											  
												<option value="30">30 dias</option>											  
												
											</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>
					
								<div class="col-md-3" id="blocoMomento" style="display: none;">
									<div class="form-group">
										<label for="inputName" class="control-label">Horário do Envio</label>
											<select data-placeholder="Selecione o momento" name="TIP_MOMENTO" id="TIP_MOMENTO" class="chosen-select-deselect" style="width:100%;">
												<option value=""></option>																     
												<?php
													$sqlHor = "SELECT TIP_HORARIO, DES_HORARIO 
																FROM HORARIO_COMUNICACAO";
													$arrayHor = mysqli_query($connAdm->connAdm(),$sqlHor);
													while($qrHor = mysqli_fetch_assoc($arrayHor)){

												?>																     
													<option value="<?=$qrHor[TIP_HORARIO]?>"><?=$qrHor['DES_HORARIO']?></option>
												<?php 
												} 
												?>
											</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3" id="blocoControle" style="display: none;">
									<div class="form-group">
										<label for="inputName" class="control-label">Controle do Envio</label>
											<select data-placeholder="Selecione o momento" name="TIP_CONTROLE" id="TIP_CONTROLE" class="chosen-select-deselect" style="width:100%;">
												<option value=""></option>																     
												<?php
													$sqlCtrl = "SELECT TIP_CONTROLE, DES_CONTROLE 
																FROM CONTROLE_COMUNICACAO";
													$arrayCtrl = mysqli_query($connAdm->connAdm(),$sqlCtrl);
													while($qrCtrl = mysqli_fetch_assoc($arrayCtrl)){

												?>																     
													<option value="<?=$qrCtrl[TIP_CONTROLE]?>"><?=$qrCtrl['DES_CONTROLE']?></option>
												<?php 
												} 
												?>
											</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2" id="blocoHora" style="display: none;">
									<div class="form-group">
										<label for="inputName" class="control-label">Hora</label>
										<input type="text" class="form-control input-sm text-center int" name="HOR_ESPECIF" id="HOR_ESPECIF" maxlength="2">
										<div class="help-block with-errors">Somente hora, sem minutos</div>
									</div>
								</div>	

							</div>

							<div class="row" id="blocoInativos" style="display: none;">
								<div class="push10"></div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Histórico de Corte</label>
										<input type="text" class="form-control input-sm text-center int" name="DIAS_HIST" id="DIAS_HIST">
										<div class="help-block with-errors">Em dias</div>
									</div>
								</div>
							</div>

							<div class="row" id="blocoValor" style="display: none;">

								<div class="push10"></div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Saldo Total Mínimo</label>
										<input type="text" class="form-control input-sm text-center <?=$classeFormata?>" name="TOT_SALDOMIN2" id="TOT_SALDOMIN2">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row" id="blocoCredito" style="display: none;">

								<div class="push10"></div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Saldo Total Mínimo</label>
										<input type="text" class="form-control input-sm text-center <?=$classeFormata?>" name="TOT_SALDOMIN" id="TOT_SALDOMIN">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Antecedência</label>
											<select data-placeholder="Selecione a quantidade" name="DIAS_ANTECED" id="DIAS_ANTECED" class="chosen-select-deselect" style="width:100%;">
												<option value=""></option>																     
												<option value="0">Nenhuma</option>
												<option value="1">1 dia</option>											  
												<option value="7">7 dias</option>											  
												<option value="15">15 dias</option>											  
												<option value="21">21 dias</option>											  
												<option value="30">30 dias</option>											  
												
											</select>
										<div class="help-block with-errors">De</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Histórico</label>
											<select data-placeholder="Selecione a quantidade" name="DIAS_PRECED" id="DIAS_PRECED" class="chosen-select-deselect" style="width:100%;">
												<option value=""></option>																     

												<option value="0">0 dias</option>											  
												<option value="1">1 dia</option>											  
												<option value="7">7 dias</option>											  
												<option value="15">15 dias</option>											  
												<option value="21">21 dias</option>											  
												<option value="30">30 dias</option>											  
												
											</select>
										<div class="help-block with-errors">Até</div>
									</div>
								</div>

							</div>

							<div class="row" id="blocoInativo" style="display: none;">

								<div class="push10"></div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Período Inicial</label>
										<input type="text" class="form-control input-sm text-center int" name="DES_PERIODOMIN" id="DES_PERIODOMIN">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Período Final</label>
										<input type="text" class="form-control input-sm text-center int" name="DES_PERIODOMAX" id="DES_PERIODOMAX">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>
							
							<div class="row" id="blocoData" style="display:none;">
							
								<div class="push10"></div>
								
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Inicial</label>
										
										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI"/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Hora Início</label>
										
										<div class='input-group date clockPicker'>
											<input type='text' class="form-control input-sm" name="HOR_INI" id="HOR_INI"/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-time"></span>
											</span>
										</div>

									</div>
								</div>
								
								<!-- <div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Final</label>
										
										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM"/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>	
								
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Hora Final</label>
										
										<div class='input-group date clockPicker'>
											<input type='text' class="form-control input-sm" name="HOR_FIM" id="HOR_FIM"/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-time"></span>
											</span>
										</div>

									</div>
								</div>	 -->					
						
							</div>
							
							<div class="push10"></div>
							
						</fieldset>						
																
						<div class="push10"></div>
						<hr>	
						<div class="form-group text-right col-lg-12">
							
							  <!--<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>-->
							  <?php
								if($cod_gatilho == 0){
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
						
						<input type="hidden" name="COD_GATILHO" id="COD_GATILHO" value="<?=$cod_gatilho?>">
						<!-- <input type="hidden" name="TIP_CONTROLE" id="TIP_CONTROLE" value=""> -->
						<input type="hidden" name="DAT_INI_CAMPANHA" id="DAT_INI_CAMPANHA" value="<?=$dat_ini_campanha?>">
						<input type="hidden" name="DAT_FIM_CAMPANHA" id="DAT_FIM_CAMPANHA" value="<?=$dat_fim_campanha?>">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
						<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?=$cod_campanha?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
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

	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" /> 
	
	<script type="text/javascript">
	
		$(function(){

			carregaTela();

			$("#DES_PERIODO").change(function(){

				$("#TIP_CONTROLE option").remove();

				if($("#TIP_GATILHO").val() == "aniv" || $("#TIP_GATILHO").val() == "anivCad"){
					$("#blocoAniv").fadeIn(1);
				}

				if($(this).val() == '99'){

					$("#blocoMomento").fadeOut(1);

					if($("#TIP_GATILHO").val() == "aniv" || $("#TIP_GATILHO").val() == "anivCad"){

						$("#TIP_CONTROLE")
						.append('<option value=""></option>')
						.append('<option value="7">1 vez na semana</option>')
						.append('<option value="15">1 vez a cada 15 dias</option>')
						.append('<option value="30">1 vez ao mês</option>')
						.trigger("chosen:updated");

						$("#blocoAniv").fadeOut(1);

					}else{

						$("#TIP_CONTROLE")
						.append('<option value=""></option>')
						.append('<option value="99">Enviar a cada evento</option>')
						.append('<option value="1">1 vez no dia</option>')
						.append('<option value="7">1 vez na semana</option>')
						.append('<option value="15">1 vez a cada 15 dias</option>')
						.append('<option value="30">1 vez ao mês</option>')
						.trigger("chosen:updated");

					}

				}else if($(this).val() == '7'){

					$("#TIP_CONTROLE")
						.append('<option value=""></option>')
						.append('<option value="7">1 vez na semana</option>')
						.append('<option value="15">1 vez a cada 15 dias</option>')
						.append('<option value="30">1 vez ao mês</option>')
						.trigger("chosen:updated");

						$("#blocoMomento").fadeIn(1);

				}else if($(this).val() == '15'){

					$("#TIP_CONTROLE")
						.append('<option value=""></option>')
						.append('<option value="15">1 vez a cada 15 dias</option>')
						.append('<option value="30">1 vez ao mês</option>')
						.trigger("chosen:updated");

						$("#blocoMomento").fadeIn(1);

				}else if($(this).val() == '30'){

					$("#TIP_CONTROLE")
						.append('<option value=""></option>')
						.append('<option value="30">1 vez ao mês</option>')
						.trigger("chosen:updated");

						$("#blocoMomento").fadeIn(1);

				}else{

					$("#blocoMomento").fadeIn(1);

					if($("#TIP_GATILHO").val() == "aniv" || $("#TIP_GATILHO").val() == "anivCad"){

						$("#TIP_CONTROLE")
						.append('<option value=""></option>')
						.append('<option value="1">1 vez no dia</option>')
						.append('<option value="7">1 vez na semana</option>')
						.append('<option value="15">1 vez a cada 15 dias</option>')
						.append('<option value="30">1 vez ao mês</option>')
						.trigger("chosen:updated");

						$("#blocoAniv").fadeOut(1);

					}else{

						$("#TIP_CONTROLE")
						.append('<option value=""></option>')
						.append('<option value="99">Enviar a cada evento</option>')
						.append('<option value="1">1 vez no dia</option>')
						.append('<option value="7">1 vez na semana</option>')
						.append('<option value="15">1 vez a cada 15 dias</option>')
						.append('<option value="30">1 vez ao mês</option>')
						.trigger("chosen:updated");

					}

				}

			});

			$("#TIP_MOMENTO").change(function(){

				if($(this).val() == '1'){

					$("#blocoHora").fadeIn(1);

				}else{

					$("#blocoHora").fadeOut(1);
					$("#HOR_ESPECIF").val('');

				}

			});

			$("#TIP_CONTROLE").change(function(){

				tip_gatilho = $("#TIP_GATILHO").val();


				if($(this).val() == '99'){

					$("#blocoMomento").fadeOut(1);
					$("#TIP_MOMENTO").val('').trigger("chosen:updated");

				}

			});

			$("#TIP_GATILHO").change(function(){

				zeraCampos();

				$("#blocoControle,#blocoMomento").fadeIn(1);

				if($(this).val() == 'individual'){ //----------------------------INDIV-----------------------------------------

					$("#blocoData").fadeIn(1);
					$("#blocoData").fadeIn(1);
					$("#blocoFrequencia,#blocoMomento,#blocoControle").fadeOut(1);

				}else if($(this).val() == 'inativos' || $(this).val() == 'cadastro' || $(this).val() == 'aniv' || $(this).val() == 'anivCad' || $(this).val() == 'venda' || $(this).val() == 'resgate' || $(this).val() == 'credVen'){ //--------------------------VENDA--------------------------

					$("#TIP_MOMENTO option,#TIP_controle option").remove();

					$("#TIP_MOMENTO")
						.append('<option value=""></option>')
						.append('<option value="6">as 6hs</option>')
						.append('<option value="7">as 7hs</option>')
						.append('<option value="8">as 8hs</option>')
						.append('<option value="9">as 9hs</option>')
						.append('<option value="10">as 10hs</option>')
						.append('<option value="11">as 11hs</option>')
						.append('<option value="12">as 12hs</option>')
						.append('<option value="13">as 13hs</option>')
						.append('<option value="14">as 14hs</option>')
						.append('<option value="15">as 15hs</option>')
						.append('<option value="16">as 16hs</option>')
						.append('<option value="17">as 17hs</option>')
						.append('<option value="18">as 18hs</option>')
						.append('<option value="19">as 19hs</option>')
						.append('<option value="20">as 20hs</option>')
						.append('<option value="21">as 21hs</option>')
						.append('<option value="22">as 22hs</option>')
						.trigger("chosen:updated");

					if($(this).val() == 'aniv' || $(this).val() == 'anivCad'){

						$("#TIP_CONTROLE")
						.append('<option value=""></option>')
						.append('<option value="1">1 vez no dia</option>')
						.append('<option value="7">1 vez na semana</option>')
						.append('<option value="15">1 vez a cada 15 dias</option>')
						.append('<option value="30">1 vez ao mês</option>')
						.trigger("chosen:updated");

						$("#blocoAniv").fadeIn(1);

					}else{

						$("#TIP_CONTROLE")
						.append('<option value=""></option>')
						.append('<option value="99">Enviar a cada evento</option>')
						.append('<option value="1">1 vez no dia</option>')
						.append('<option value="7">1 vez na semana</option>')
						.append('<option value="15">1 vez a cada 15 dias</option>')
						.append('<option value="30">1 vez ao mês</option>')
						.trigger("chosen:updated");

					}

					if($(this).val() == 'venda'){
						$("#blocoValor").fadeIn(1);
					}


				}else if($(this).val() == 'credExp'){ //------------------------CREDITOS A EXPIRAR----------------------------
					// alert('expirar');



					$("#blocoControle,#blocoMomento,#blocoCredito").fadeIn(1);
					$("#TIP_MOMENTO option").remove();
					$("#TIP_MOMENTO")
						.append('<option value=""></option>')
						.append('<option value="6">as 6hs</option>')
						.append('<option value="7">as 7hs</option>')
						.append('<option value="8">as 8hs</option>')
						.append('<option value="9">as 9hs</option>')
						.append('<option value="10">as 10hs</option>')
						.append('<option value="11">as 11hs</option>')
						.append('<option value="12">as 12hs</option>')
						.append('<option value="13">as 13hs</option>')
						.append('<option value="14">as 14hs</option>')
						.append('<option value="15">as 15hs</option>')
						.append('<option value="16">as 16hs</option>')
						.append('<option value="17">as 17hs</option>')
						.append('<option value="18">as 18hs</option>')
						.append('<option value="19">as 19hs</option>')
						.append('<option value="20">as 20hs</option>')
						.append('<option value="21">as 21hs</option>')
						.append('<option value="22">as 22hs</option>')
						.trigger("chosen:updated");
					// alert('passou');

				}else if($(this).val() == 'senhaApp' || $(this).val() == 'tokenCad' || $(this).val() == 'tokenVen' || $(this).val() == 'vendaFast' || $(this).val() == 'cadFast'){ //------------------------Token de Cadastro ou Venda----------------------------
					// alert('expirar');



					$("#blocoControle,#blocoMomento,#blocoCredito,#blocoFrequencia,#blocoAniv,blocoHora,#blocoInativos,#blocoData").fadeOut(1);
					// $("#blocoToken").fadeIn(1);
					// alert('passou');

					if($(this).val() == 'vendaFast'){

						$("#blocoControle,#blocoValor").fadeIn(1);

						$("#TIP_CONTROLE option").remove();

						$("#TIP_CONTROLE")
						.append('<option value=""></option>')
						.append('<option value="99">Enviar a cada evento</option>')
						.append('<option value="1">1 vez no dia</option>')
						.trigger("chosen:updated");

					}else if($(this).val() == 'cadFast'){

						$("#blocoControle").fadeIn(1);

						$("#TIP_CONTROLE option").remove();

						$("#TIP_CONTROLE")
						.append('<option value=""></option>')
						.append('<option value="99">Enviar a cada evento</option>')
						.append('<option value="1">1 vez no dia</option>')
						.trigger("chosen:updated");

					}

				}else if($(this).val() == 'vendaOn'){

					$("#blocoControle,#blocoMomento,#blocoCredito,#blocoDia,#blocoData,#blocoHora").fadeOut(1);

				}else{// SORTEIOS---------------------------------------------


					$("#TIP_MOMENTO option").remove();

					$("#TIP_MOMENTO")
						.append('<option value=""></option>')
						.append('<option value="99">Imediatamente</option>')
						.append('<option value="8">as 8hs</option>')
						.append('<option value="13">as 13hs</option>')
						.append('<option value="21">as 21hs</option>')
						.trigger("chosen:updated");

					$("#blocoMomento").fadeIn(1);

				}

				if($(this).val() == "<?=$tip_gatilho?>"){

					carregaTela();

				}

			});

			$('.datePicker').datetimepicker({
				format: 'DD/MM/YYYY',
				// minDate : 'now',
			}).on('changeDate', function(e){
				$(this).datetimepicker('hide');
			});
				
			$('.clockPicker').datetimepicker({
				format: 'LT',
				// minDate : 'now',
			}).on('changeDate', function(e){
				$(this).datetimepicker('hide');
			});

			$(':checkbox').on('change',function(){
				if($('#TIP_GATILHO').val() == 'anivSem'){
					if($(this).is(':checked')){
						$(":checkbox").not($(this)).prop('checked',false);
					}
				}
			});

		});

		function zeraCampos(){
			$("#HOR_ESPECIF,#DAT_INI,#HOR_INI,#DAT_FIM,#HOR_FIM,#DIAS_HIST").val('');
			$("#LOG_DOMINGO,#LOG_SEGUNDA,#LOG_TERCA,#LOG_QUARTA,#LOG_QUINTA,#LOG_SEXTA,#LOG_SABADO").prop('checked',false);
			$("#TIP_MOMENTO option[value='1']").remove();
			$("#TIP_CONTROLE,#TIP_MOMENTO,#DES_PERIODO,#DIAS_ANTECED").val('').trigger("chosen:updated");
			$("#blocoControle,#blocoMomento,#blocoCredito,#blocoDia,#blocoData,#blocoHora,#blocoAniv,#blocoInativos,#blocoValor").fadeOut(1);
		}

		function carregaTela(){

			zeraCampos();

			var tip_gatilho = "<?=$tip_gatilho?>",
				tip_momento = "<?=$tip_momento?>",
				des_periodo = "<?=$des_periodo?>",
				tip_controle = "<?=$tip_controle?>";


			$("#blocoMomento").fadeIn(1);

				// alert(tip_gatilho);

			if(tip_gatilho == 'individual'){

				$("#blocoData").fadeIn(1);
				$("#blocoData").fadeIn(1);
				$("#blocoFrequencia,#blocoMomento").fadeOut(1);

			}else if(tip_gatilho == 'inativos' || tip_gatilho == 'cadastro' || tip_gatilho == 'aniv' || tip_gatilho == 'anivCad' || tip_gatilho == 'venda' || tip_gatilho == 'resgate' || tip_gatilho == 'credVen'){

				// alert(tip_controle);

				if(tip_controle == 99){
					
					$("#blocoControle").fadeIn(1);

				}else{

					if(tip_gatilho != 'individual'){

						$("#blocoControle,#blocoMomento").fadeIn(1);

					}

					$("#TIP_MOMENTO option").remove();
					$("#TIP_MOMENTO")
						.append('<option value=""></option>')
						.append('<option value="6">as 6hs</option>')
						.append('<option value="7">as 7hs</option>')
						.append('<option value="8">as 8hs</option>')
						.append('<option value="9">as 9hs</option>')
						.append('<option value="10">as 10hs</option>')
						.append('<option value="11">as 11hs</option>')
						.append('<option value="12">as 12hs</option>')
						.append('<option value="13">as 13hs</option>')
						.append('<option value="14">as 14hs</option>')
						.append('<option value="15">as 15hs</option>')
						.append('<option value="16">as 16hs</option>')
						.append('<option value="17">as 17hs</option>')
						.append('<option value="18">as 18hs</option>')
						.append('<option value="19">as 19hs</option>')
						.append('<option value="20">as 20hs</option>')
						.append('<option value="21">as 21hs</option>')
						.append('<option value="22">as 22hs</option>')
						.trigger("chosen:updated");

				}

				if(tip_gatilho == 'aniv' || tip_gatilho == 'anivCad'){
					$("#TIP_CONTROLE option[value='99']").remove().trigger("chosen:updated");
					$("#blocoAniv").fadeIn(1);
				}

				if(tip_gatilho == 'venda'){
					$("#blocoValor").fadeIn(1);
				}

			}else if(tip_gatilho == 'credExp'){

				$("#blocoControle,#blocoMomento,#blocoCredito").fadeIn(1);

				$("#TIP_MOMENTO option").remove();
					$("#TIP_MOMENTO")
						.append('<option value=""></option>')
						.append('<option value="6">as 6hs</option>')
						.append('<option value="7">as 7hs</option>')
						.append('<option value="8">as 8hs</option>')
						.append('<option value="9">as 9hs</option>')
						.append('<option value="10">as 10hs</option>')
						.append('<option value="11">as 11hs</option>')
						.append('<option value="12">as 12hs</option>')
						.append('<option value="13">as 13hs</option>')
						.append('<option value="14">as 14hs</option>')
						.append('<option value="15">as 15hs</option>')
						.append('<option value="16">as 16hs</option>')
						.append('<option value="17">as 17hs</option>')
						.append('<option value="18">as 18hs</option>')
						.append('<option value="19">as 19hs</option>')
						.append('<option value="20">as 20hs</option>')
						.append('<option value="21">as 21hs</option>')
						.append('<option value="22">as 22hs</option>')
						.trigger("chosen:updated");

			}else if(tip_gatilho == 'vendaOn'){
				$("#blocoControle,#blocoMomento,#blocoCredito,#blocoDia,#blocoData,#blocoHora").fadeOut(1);
			}else if(tip_gatilho == 'senhaApp' || tip_gatilho == 'tokenCad' || tip_gatilho == 'tokenVen'){ //------------------------Token de Cadastro ou Venda----------------------------
					// alert('expirar');

					$("#blocoControle,#blocoMomento,#blocoCredito,#blocoFrequencia,#blocoAniv,blocoHora,#blocoInativos,#blocoData").fadeOut(1);
					$("#blocoToken").fadeIn(1);
					// alert('passou');

				}else{

				$("#blocoMomento").fadeIn(1);
			}

			$("#TIP_CONTROLE option").remove();

			if(des_periodo == '99'){

				$("#blocoMomento").fadeOut(1);

				if($("#TIP_GATILHO").val() == "aniv" || $("#TIP_GATILHO").val() == "anivCad"){

					$("#TIP_CONTROLE")
					.append('<option value=""></option>')
					.append('<option value="7">1 vez na semana</option>')
					.append('<option value="15">1 vez a cada 15 dias</option>')
					.append('<option value="30">1 vez ao mês</option>')
					.trigger("chosen:updated");

				}else{

					$("#TIP_CONTROLE")
					.append('<option value=""></option>')
					.append('<option value="99">Enviar a cada evento</option>')
					.append('<option value="1">1 vez no dia</option>')
					.append('<option value="7">1 vez na semana</option>')
					.append('<option value="15">1 vez a cada 15 dias</option>')
					.append('<option value="30">1 vez ao mês</option>')
					.trigger("chosen:updated");

				}

			}else if(des_periodo == '7'){

				$("#TIP_CONTROLE")
					.append('<option value=""></option>')
					.append('<option value="7">1 vez na semana</option>')
					.append('<option value="15">1 vez a cada 15 dias</option>')
					.append('<option value="30">1 vez ao mês</option>')
					.trigger("chosen:updated");

					$("#blocoMomento").fadeIn(1);

			}else if(des_periodo == '15'){

				$("#TIP_CONTROLE")
					.append('<option value=""></option>')
					.append('<option value="15">1 vez a cada 15 dias</option>')
					.append('<option value="30">1 vez ao mês</option>')
					.trigger("chosen:updated");

					$("#blocoMomento").fadeIn(1);

			}else if(des_periodo == '30'){

				$("#TIP_CONTROLE")
					.append('<option value=""></option>')
					.append('<option value="30">1 vez ao mês</option>')
					.trigger("chosen:updated");

					$("#blocoMomento").fadeIn(1);

			}else{

				$("#blocoMomento").fadeIn(1);

				if(tip_gatilho == "aniv" || tip_gatilho == 'anivCad'){

					$("#TIP_CONTROLE")
					.append('<option value=""></option>')
					.append('<option value="1">1 vez no dia</option>')
					.append('<option value="7">1 vez na semana</option>')
					.append('<option value="15">1 vez a cada 15 dias</option>')
					.append('<option value="30">1 vez ao mês</option>')
					.trigger("chosen:updated");

				}else if(tip_gatilho == 'individual'){
					$("#blocoMomento").fadeOut(1);
				}else{

					$("#TIP_CONTROLE")
					.append('<option value=""></option>')
					.append('<option value="99">Enviar a cada evento</option>')
					.append('<option value="1">1 vez no dia</option>')
					.append('<option value="7">1 vez na semana</option>')
					.append('<option value="15">1 vez a cada 15 dias</option>')
					.append('<option value="30">1 vez ao mês</option>')
					.trigger("chosen:updated");
					$("#blocoMomento").fadeIn(1);

				}

			}

			if(tip_gatilho == 'senhaApp' || tip_gatilho == 'tokenCad' || tip_gatilho == 'tokenVen' || tip_gatilho == 'vendaFast' || tip_gatilho == 'cadFast'){ //------------------------Token de Cadastro ou Venda----------------------------
				// alert('expirar');

				$("#blocoControle,#blocoMomento,#blocoCredito,#blocoFrequencia,#blocoAniv,blocoHora,#blocoInativos,#blocoData").fadeOut(1);
				// $("#blocoToken").fadeIn(1);
				// alert('passou');

				if(tip_gatilho == 'vendaFast'){

					$("#blocoControle,#blocoValor").fadeIn(1);

					$("#TIP_CONTROLE option").remove();

					$("#TIP_CONTROLE")
					.append('<option value=""></option>')
					.append('<option value="99">Enviar a cada evento</option>')
					.append('<option value="1">1 vez no dia</option>')
					.trigger("chosen:updated");

				}else if(tip_gatilho == 'cadFast'){

					$("#blocoControle").fadeIn(1);

					$("#TIP_CONTROLE option").remove();

					$("#TIP_CONTROLE")
					.append('<option value=""></option>')
					.append('<option value="99">Enviar a cada evento</option>')
					.append('<option value="1">1 vez no dia</option>')
					.trigger("chosen:updated");

				}

			}

			carregaCampos();

		}

		function carregaCampos(){
			$("#DAT_INI").val("<?=$dat_ini?>");
			$("#DAT_FIM").val("<?=$dat_fim?>");
			$("#HOR_INI").val("<?=$hor_ini?>");
			$("#HOR_FIM").val("<?=$hor_fim?>");
			$("#TOT_SALDOMIN,#TOT_SALDOMIN2").val("<?=$tot_saldomin?>");
			$("#HOR_ESPECIF").val("<?=$hor_especif?>");
			$("#DES_PERIODOMIN").val("<?=$des_periodomin?>");
			$("#DES_PERIODOMAX").val("<?=$des_periodomax?>");
			$("#TIP_GATILHO").val("<?=$tip_gatilho?>").trigger("chosen:updated");
			$("#TIP_CONTROLE").val("<?=$tip_controle?>").trigger("chosen:updated");
			$("#DES_PERIODO").val("<?=$des_periodo?>").trigger("chosen:updated");
			$("#DIAS_ANTECED").val("<?=$dias_anteced?>").trigger("chosen:updated");
			$("#TIP_MOMENTO").val("<?=$tip_momento?>").trigger("chosen:updated");

			if("<?=$tip_gatilho?>" == 'aniv'){
				$("#DIAS_ANTECED_ANIV").val("<?=$dias_anteced?>").trigger("chosen:updated");
			}

			if("<?=$tip_gatilho?>" == 'credExp'){
				$("#DIAS_PRECED").val("<?=$des_periodomax?>").trigger("chosen:updated");
			}

			if("<?=$tip_gatilho?>" == 'inativos'){
				$("#DIAS_HIST").val("<?=$dias_hist?>").trigger("chosen:updated");
			}

			if("<?=$tip_gatilho?>" == 'senhaApp' || "<?=$tip_gatilho?>" == 'tokenCad' || "<?=$tip_gatilho?>" == 'tokenVen'){
				$("#HOR_ESPECIF_VAL").val("<?=$hor_especif?>").trigger("chosen:updated");
			}
			// if("<?=$log_domingo?>" == 'S'){$("#LOG_DOMINGO").prop('checked',true)}else{$("#LOG_DOMINGO").prop('checked',false)}
			// if("<?=$log_segunda?>" == 'S'){$("#LOG_SEGUNDA").prop('checked',true)}else{$("#LOG_SEGUNDA").prop('checked',false)}
			// if("<?=$log_terca?>" == 'S'){$("#LOG_TERCA").prop('checked',true)}else{$("#LOG_TERCA").prop('checked',false)}
			// if("<?=$log_quarta?>" == 'S'){$("#LOG_QUARTA").prop('checked',true)}else{$("#LOG_QUARTA").prop('checked',false)}
			// if("<?=$log_quinta?>" == 'S'){$("#LOG_QUINTA").prop('checked',true)}else{$("#LOG_QUINTA").prop('checked',false)}
			// if("<?=$log_sexta?>" == 'S'){$("#LOG_SEXTA").prop('checked',true)}else{$("#LOG_SEXTA").prop('checked',false)}
			// if("<?=$log_sabado?>" == 'S'){$("#LOG_SABADO").prop('checked',true)}else{$("#LOG_SABADO").prop('checked',false)}
		}
		
	</script>