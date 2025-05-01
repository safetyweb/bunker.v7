<?php

	//echo fnDebug('true');

	function ajusta_calculos($cod_empresa){
		//CALCULA VALOR CONSUMIDO
		$sql = "UPDATE tarefa SET VAL_CONSUMIDO=(SELECT IFNULL(SUM(VAL_TOTAL),0) FROM controle_recebimento_itens c WHERE c.COD_TAREFA=tarefa.COD_TAREFA)";
		mysqli_query(connTemp($cod_empresa,''),$sql);

		//BUSCA TAREFAS PAI COM DIFERENÇA NO CALCULO E AJUSTA
		$sql = "SELECT tarefa.COD_TAREFA,tarefa.VAL_CONSUMIDO,(SELECT SUM(VAL_CONSUMIDO) FROM tarefa t WHERE t.COD_SUBTAREFA=tarefa.COD_TAREFA)
				+(SELECT IFNULL(SUM(VAL_TOTAL),0) FROM controle_recebimento_itens WHERE controle_recebimento_itens.COD_TAREFA=tarefa.COD_TAREFA) VAL_CONSUMIDO_SUB
				FROM tarefa
				WHERE COD_SUBTAREFA <= 0 AND (SELECT COUNT(0) FROM tarefa t1 WHERE t1.COD_SUBTAREFA=tarefa.COD_TAREFA) > 0
				HAVING VAL_CONSUMIDO_SUB <> tarefa.VAL_CONSUMIDO";
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
		while ($tarefa = mysqli_fetch_assoc($arrayQuery))
		{
			//CALCULA VALOR CONSUMIDO - PAI
			$sql = "UPDATE tarefa SET VAL_CONSUMIDO=0".$tarefa["VAL_CONSUMIDO_SUB"]." WHERE COD_TAREFA=0".$tarefa["COD_TAREFA"];
			mysqli_query(connTemp($cod_empresa,''),$sql);
		}

		//ATUALIZA PERCENTUAL DO PROJETO
		$sql = "UPDATE tarefa SET PCT_TAREFA=IF(VAL_PROJETO <= 0,0,(VAL_CONSUMIDO/VAL_PROJETO)*100)";
		mysqli_query(connTemp($cod_empresa,''),$sql);
	}

    $hashLocal = mt_rand();	
	$log_obrigat='N';

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
			
			$cod_recebim = fnLimpaCampoZero($_REQUEST['COD_RECEBIM']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
			$cod_contrat = fnLimpaCampoZero($_REQUEST['COD_CONTRAT']);
			$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
			$cod_tarefa = fnLimpaCampo($_REQUEST['COD_TAREFAS']);
			$cod_externo = fnLimpaCampoZero($_REQUEST['COD_EXTERNO']);
			$num_medicao = fnLimpaCampo($_REQUEST['NUM_MEDICAO']);
			$dat_medicao = fnLimpaCampo($_REQUEST['DAT_MEDICAO']);
			$val_evolucao = fnLimpaCampo($_REQUEST['VAL_EVOLUCAO']);
			$val_medicao = fnLimpaCampo($_REQUEST['VAL_MEDICAO']);
			$val_medicao_total = fnLimpaCampo($_REQUEST['VAL_MEDICAO_TOTAL']);
			$des_coment = fnLimpaCampo($_REQUEST['DES_COMENT']);
			$tip_controle = fnLimpaCampo($_REQUEST['TIP_CONTROLE']);
			$num_contador = fnLimpaCampo($_REQUEST['NUM_CONTADOR']);

			$pct_valor = $val_medicao/$val_medicao_total*100;


			$dat_medicao = ($dat_medicao == ""?date("d/m/Y"):$dat_medicao);

			//INICIALIZANDO VARIAVEIS
			// $cod_tarefa = "";
			// foreach ($_REQUEST['COD_TAREFA'] as $tarefa) {
			// 	//concatenando variaveis separadas por vírgula
			// 	$cod_tarefa .= $tarefa . ",";
			// }
			// // removendo última vírgula da variável
			// $cod_tarefa = rtrim($cod_tarefa, ",");
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){							
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						if ($pct_valor > 100){
							
							$msgTipo = 'alert-danger';
							$msgRetorno = "Valor da medição é maior que o valor do cronograma";

						}else{

							$sql = "INSERT INTO CONTROLE_RECEBIMENTO(
												COD_EMPRESA,
												COD_CONVENI,
												COD_CONTRAT,
												COD_CLIENTE,
												COD_TAREFA,
												COD_MEDICAO,
												NUM_MEDICAO,
												DAT_MEDICAO,
												VAL_MEDICAO,
												VAL_TOTAL,
												VAL_EVOLUCAO,
												DES_COMENT,
												TIP_CONTROLE,
												COD_USUCADA
												) VALUES(
												$cod_empresa,
												$cod_conveni,
												$cod_contrat,
												$cod_cliente,
												'$cod_tarefa',
												$cod_externo,
												'$num_medicao',
												'".fnDataSql($dat_medicao)."',
												'".fnValorSql($val_medicao)."',
												'".fnValorSql($val_medicao)."',
												'".fnValorSql($val_evolucao)."',
												'$des_coment',
												'$tip_controle',
												$cod_usucada
												)";
								
							//fnEscreve($sql);
							mysqli_query(connTemp($cod_empresa,''),$sql);

							if($cod_recebim == 0){

								$sqlCod = "SELECT MAX(COD_RECEBIM) COD_RECEBIM FROM CONTROLE_RECEBIMENTO WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
								$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlCod);
								$qrCod = mysqli_fetch_assoc($arrayQuery);
								$cod_recebim = $qrCod[COD_RECEBIM];

								$sqlArquivos = "SELECT 1 FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
								// fnEscreve($sqlArquivos);
								$arrayCont = mysqli_query(connTemp($cod_empresa,''),$sqlArquivos);

								if(mysqli_num_rows($arrayCont) > 0){
									$sqlUpd = "UPDATE ANEXO_CONVENIO SET COD_RECEBIM = $cod_recebim, LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
									mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
								}

							}else{
								// $sqlUpd = "UPDATE ANEXO_CONVENIO SET LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_LICITAC = $cod_licitac AND LOG_STATUS = 'N'";
								// mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
							}

							$sqlCod = "SELECT MAX(COD_RECEBIM) COD_RECEBIM FROM CONTROLE_RECEBIMENTO WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
							$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlCod);
							$qrCod = mysqli_fetch_assoc($arrayQuery);
							$cod_recebim = $qrCod["COD_RECEBIM"];

							//CADASTRA OS SUB-ITENS
							$sql = "INSERT INTO controle_recebimento_itens (COD_RECEBIM,COD_TAREFA,COD_SUBTAREFA,VAL_PARCIAL,PCT_VALOR)
										SELECT '$cod_recebim',COD_TAREFA,COD_SUBTAREFA,VAL_PROJETO-VAL_CONSUMIDO,0$pct_valor FROM tarefa
										WHERE cod_tarefa IN (0$cod_tarefa)";
							mysqli_query(connTemp($cod_empresa,''),$sql);

							$sql = "UPDATE controle_recebimento_itens SET VAL_TOTAL=(VAL_PARCIAL*PCT_VALOR/100) WHERE COD_RECEBIM='0$cod_recebim'";
							mysqli_query(connTemp($cod_empresa,''),$sql);

							$sql = "SELECT controle_recebimento_itens.*,
									controle_recebimento_itens.VAL_TOTAL-(SELECT IFNULL(SUM(VAL_TOTAL),0) FROM controle_recebimento_itens t2 WHERE t2.COD_SUBTAREFA=controle_recebimento_itens.cod_tarefa AND t2.COD_RECEBIM=controle_recebimento_itens.COD_RECEBIM) VAL_NOVO
									FROM controle_recebimento_itens WHERE COD_SUBTAREFA=0 AND COD_RECEBIM='0$cod_recebim'";
							$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
							while ($tarefa = mysqli_fetch_assoc($arrayQuery))
							{
								//AJUSTA VALORES
								$sql = "UPDATE controle_recebimento_itens SET VAL_TOTAL=0".$tarefa["VAL_NOVO"]." 
										WHERE COD_RECEBIM='0$cod_recebim' AND COD_TAREFA=0".$tarefa["COD_TAREFA"];
										//echo $sql;
								mysqli_query(connTemp($cod_empresa,''),$sql);
							}

							ajusta_calculos($cod_empresa);

							$msgTipo = 'alert-success';
							$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						}
						break;
					case 'EXC':

						$sql = "DELETE FROM CONTROLE_RECEBIMENTO WHERE COD_RECEBIM=0$cod_recebim";
						mysqli_query(connTemp($cod_empresa,''),$sql);
						
						ajusta_calculos($cod_empresa);

						$msgTipo = 'alert-success';
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}

				

				// atualizando o projeto -----------------------------------------------------------------------------------
/*
				$sqlTarefa = "SELECT COD_TAREFA, VAL_MEDICAO FROM CONTROLE_RECEBIMENTO WHERE COD_RECEBIM = $cod_recebim";

				$qrTarefa = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlTarefa));

				$tarefasPai = "";

				if($qrTarefa[COD_TAREFA] != ""){

					$cod_tarefas = rtrim(ltrim($qrTarefa[COD_TAREFA],","),",");

					$cod_tarefa_medicao = explode(",", $cod_tarefas);

					$totProjeto = 0;
					$medicaoProjeto = $qrTarefa[VAL_MEDICAO];

					foreach ($cod_tarefa_medicao as $tarefa) {
						
						$sqlValor = "SELECT VAL_PROJETO, COD_TAREFA FROM TAREFA WHERE COD_TAREFA = $tarefa AND COD_EMPRESA = $cod_empresa AND COD_SUBTAREFA = 0";
						// fnEscreve($sqlValor);
						$qrValor = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlValor));
						$totProjeto += $qrValor['VAL_PROJETO'];
						$tarefasPai .= $qrValor['COD_TAREFA'].",";
					}

					$tarefasPai =rtrim(ltrim($tarefasPai,","),",");

					$andPai = "";
					if($tarefasPai != ""){
						$andPai = "AND COD_SUBTAREFA NOT IN($tarefasPai) AND COD_TAREFA NOT IN($tarefasPai)";
					}

					foreach ($cod_tarefa_medicao as $tarefa) {
						
						$sqlValor = "SELECT VAL_PROJETO, COD_TAREFA FROM TAREFA WHERE COD_TAREFA = $tarefa AND COD_EMPRESA = $cod_empresa $andPai";
						// fnEscreve($sqlValor);
						$qrValor = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlValor));
						$totProjeto += $qrValor['VAL_PROJETO'];
					}

					// x = medição / totalprojeto;

					// valor = valprojeto * x;

					$valUniforme = $medicaoProjeto / $totProjeto;

					$valSubtracao = "VAL_CONSUMIDO";
					$sqlHist = ", VAL_HISTORICO = VAL_CONSUMIDO ";

					if($opcao == "ALT"){
						$valSubtracao = "VAL_HISTORICO";
						$sqlHist = "";
					}

					$sqlPerc = "UPDATE TAREFA SET
										VAL_CONSUMIDO = ( ( VAL_PROJETO - VAL_CONSUMIDO ) * $valUniforme ),
										PCT_TAREFA = ((VAL_CONSUMIDO*100)/VAL_PROJETO) $sqlHist
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_TAREFA IN($cod_tarefas)";
					 FNeSCREVE($sqlPerc);
					mysqli_query(connTemp($cod_empresa,''),$sqlPerc);
				}
				*/

			}                
		}
	}
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
            
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
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

	/*******************************/
	ajusta_calculos($cod_empresa);
	/*******************************/

	if(isset($_GET['idC'])){
		if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){
		
			//busca dados do convênio
			$cod_conveni = fnDecode($_GET['idC']);
		}
	}

	if(isset($_GET['idC'])){
		if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){
		
			//busca dados do convênio
			$cod_contrat = fnDecode($_GET['idCT']);

			$sql = "SELECT CTT.*, CL.COD_CLIENTE, CL.NOM_CLIENTE FROM CONTRATO CTT 
					LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = CTT.COD_CLIENTE
					WHERE CTT.COD_CONTRAT = $cod_contrat AND CTT.COD_EMPRESA = $cod_empresa
					";

			//fnEscreve($sql);
			$arrayQuery =  mysqli_query(connTemp($cod_empresa,''),$sql);
			$qrContrat = mysqli_fetch_assoc($arrayQuery);

			if (isset($qrContrat)){
				$cod_conveni = $qrContrat['COD_CONVENI'];
				$cod_cliente = $qrContrat['COD_CLIENTE'];
				$nro_contrat = $qrContrat['NRO_CONTRAT'];
				$val_valor = $qrContrat['VAL_VALOR'];
				$nom_empContrat = $qrContrat['NOM_CLIENTE'];
			}

		}

	}



	$sqlAcumula = "SELECT SUM(VAL_MEDICAO) AS VAL_MEDAC, SUM(VAL_EVOLUCAO) AS VAL_EVOFIS 
	FROM CONTROLE_RECEBIMENTO WHERE COD_CONTRAT = $cod_contrat AND COD_EMPRESA = $cod_empresa";
	$arrayAcumula =  mysqli_query(connTemp($cod_empresa,''),$sqlAcumula);
	$qrAcumula = mysqli_fetch_assoc($arrayAcumula);

	if(isset($qrAcumula)){

		$val_medac = $qrAcumula['VAL_MEDAC'];
		$val_evofis = $qrAcumula['VAL_EVOFIS'];

	}else{

		$val_medac=0;
		$val_evofis=0;

	}

	//fnMostraForm();
	//fnEscreve($cod_contrat);

	$tp_cont = 'Anexo da Medição';
	$tp_anexo = 'COD_RECEBIM';
	$cod_tpanexo = 'COD_RECEBIM';
	$cod_busca = $cod_recebim;
	
	$sqlUpdtCont = "DELETE FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_RECEBIM != 0 AND LOG_STATUS = 'N'";
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);
	
	$sqlUpdtCont = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE DES_CONTADOR = '$tp_cont'";
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);

	$sqlCont = "SELECT NUM_CONTADOR FROM CONTADOR WHERE DES_CONTADOR = '$tp_cont'";
	$qrCont = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCont));
	$num_contador = $qrCont['NUM_CONTADOR'];

	// fnEscreve($num_contador);


?>
	
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
					
					<div class="tabbable-line">
						<ul class="nav nav-tabs">
							<li>
								<a href="action.do?mod=<?php echo fnEncode(1348)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_conveni); ?>" style="text-decoration: none;">
								<span class="fal fa-arrow-circle-left fa-2x"></span></a>
							</li>
						</ul>
					</div>										
					
					<div class="push20"></div> 			
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
							<fieldset>
								<legend>Dados Gerais</legend> 
							
								<div class="row">
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_RECEBIM" id="COD_RECEBIM" value="<?=$cod_recebim?>">
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $nom_empresa ?>" required>
										</div>														
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa Contratada</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empContrat ?>" required>
											<input type="hidden" class="form-control input-sm" name="COD_LICITAC" id="COD_LICITAC" value="<?php echo $cod_licitac ?>">
										</div>														
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Contrato</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NRO_CONTRAT" id="NRO_CONTRAT" value="<?php echo $nro_contrat ?>" required>
										</div>														
									</div>

								</div>	
									
								<div class="push20"></div>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor do Contrato</label>
											<input type="text" class="form-control input-sm money leitura" name="VAL_VALOR" id="VAL_VALOR" value="<?=fnValor($val_valor,2)?>" readonly maxlength="11">
										</div>
										<div class="help-block with-errors"></div>
									</div> 									
													
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Evolução Física Acumulada</label>
											<input type="text" class="form-control input-sm money leituraOff" name="VAL_EVOFIS" id="VAL_EVOFIS" value="<?=fnValor($val_evofis,2)?>" readonly maxlength="11">
										</div>
										<div class="help-block with-errors">PERCENTUAL (%)</div>
									</div> 
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor da Medição Acumulada</label>
											<input type="text" class="form-control input-sm money leituraOff" name="VAL_MEDAC" id="VAL_MEDAC" value="<?=fnValor($val_medac,2)?>" readonly maxlength="11">
										</div>
										<div class="help-block with-errors">REAIS (R$)</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Código Externo</label>
											<input type="text" class="form-control input-sm " name="COD_EXTERNO" id="COD_EXTERNO" value="" maxlength="11">
										</div>
										<div class="help-block with-errors"></div>
									</div>

								</div>
									
							</fieldset>
									
							<div class="push20"></div>
							
							<fieldset>
								<legend>Dados da Medição</legend> 

									<div class="row">

										<!-- <div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label required">Tarefas do Cronograma</label>
												<select data-placeholder="Selecione um projeto" name="COD_TAREFA[]" id="COD_TAREFA" multiple="multiple" class="chosen-select-deselect">
													<option value=""></option>
																	
													<?php 

														$sql = "SELECT * FROM TAREFA 
																WHERE COD_EMPRESA = $cod_empresa
																AND COD_CONVENIO = $cod_conveni
																AND LOG_ATIVO = 'S'
																AND COD_SUBTAREFA = 0";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

														while ($qrListaTarefa = mysqli_fetch_assoc($arrayQuery))
														{

													?>

															<optgroup label="<?=$qrListaTarefa[NOM_TAREFA]?>">

													<?php 															
															
															$sql2 = "SELECT * FROM TAREFA 
																	WHERE COD_EMPRESA = $cod_empresa
																	AND COD_CONVENIO = $cod_conveni
																	AND LOG_ATIVO = 'S'
																	AND COD_SUBTAREFA = $qrListaTarefa[COD_TAREFA]";
															$arrayQuery2 = mysqli_query(connTemp($cod_empresa,''),$sql2);

															while ($qrListaTarefa2 = mysqli_fetch_assoc($arrayQuery2))
															{																	
																
																echo "<option class='sub_".$qrListaTarefa2['COD_SUBTAREFA']."' value='".$qrListaTarefa2['COD_TAREFA']."' valor-projeto='".$qrListaTarefa2['VAL_PROJETO']."' data-id='".$qrListaTarefa2['COD_SUBTAREFA']."'>".$qrListaTarefa2['NOM_TAREFA']."</option>"; 
															} 
													?>

															</optgroup>

													<?php 
														}

													?>

												</select>
												<div class="help-block with-errors"></div>
											</div>
										</div> -->

										<div class="col-md-12">
											<div class="form-group" id="tarefas">
												<label for="inputName" class="control-label required">Tarefas do Cronograma</label>
												<!-- <select data-placeholder="Selecione um projeto" name="COD_TAREFA[]" id="COD_TAREFA" multiple="multiple" class="chosen-select-deselect"> -->

												<input type="text" id="example" placeholder="Selecione as tarefas" autocomplete="off">												
																	
													<?php 

														$tarefas = array();

														$sql = "SELECT * FROM TAREFA 
																WHERE COD_EMPRESA = $cod_empresa
																AND COD_CONVENIO = $cod_conveni
																AND LOG_ATIVO = 'S'
																AND COD_SUBTAREFA = 0";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

														while ($qrListaTarefa = mysqli_fetch_assoc($arrayQuery))
														{

															$subTarefas = array();
															
															$sql2 = "SELECT * FROM TAREFA 
																	WHERE COD_EMPRESA = $cod_empresa
																	AND COD_CONVENIO = $cod_conveni
																	AND LOG_ATIVO = 'S'
																	AND COD_SUBTAREFA = $qrListaTarefa[COD_TAREFA]";
															$arrayQuery2 = mysqli_query(connTemp($cod_empresa,''),$sql2);

															$valFilhos = 0;
															while ($qrListaTarefa2 = mysqli_fetch_assoc($arrayQuery2))
															{
																$valFilhos += $qrListaTarefa2[VAL_PROJETO]-$qrListaTarefa2[VAL_CONSUMIDO];
																$valPai = fnValor($qrListaTarefa2[VAL_PROJETO]-$qrListaTarefa2[VAL_CONSUMIDO],2);
																if ($valPai <= 0){continue;}
																$tarefaPai = "$qrListaTarefa2[NOM_TAREFA] ($valPai)";

																array_push($subTarefas, array(
																							"id" => $qrListaTarefa2["COD_TAREFA"],
																							"title" => $tarefaPai,
																							"value"=>$valPai,
																						));
																// echo "<option class='sub_".$qrListaTarefa2['COD_SUBTAREFA']."' value='".$qrListaTarefa2['COD_TAREFA']."' valor-projeto='".$qrListaTarefa2['VAL_PROJETO']."' data-id='".$qrListaTarefa2['COD_SUBTAREFA']."'>".$qrListaTarefa2['NOM_TAREFA']."</option>"; 
															} 
													?>

															<!-- </optgroup> -->

													<?php 

														$valFilho = fnValor($qrListaTarefa[VAL_PROJETO]-$qrListaTarefa[VAL_CONSUMIDO],2);
														if ($valFilho <= 0){continue;}
														$tarefaFilho = "$qrListaTarefa[NOM_TAREFA] ($valFilho)";
														if (count($subTarefas) > 0 && fnValor($valFilhos,2) <> $valFilho){
															$tarefaFilho = "<span class=\"text-danger\">".$tarefaFilho."</span> <small class=\"text-danger\">(Percentuais de rateios incompletos - ".fnValor($valFilhos,2).")</small>";
															foreach($subTarefas as $key=>$item){
																$subTarefas[$key]["isSelectable"] = false;
																$subTarefas[$key]["title"] = "<input type='checkbox' disabled> <span class=\"text-danger\">".$subTarefas[$key]["title"]."</span>";
															}
														}

														array_push($tarefas, array(
																				"id" => $qrListaTarefa["COD_TAREFA"],
																				"title" => $tarefaFilho,
																				"subs" => $subTarefas,
																				"value"=>$valFilho,
																				"isSelectable"=>(count($subTarefas)<=0)
																			));
														}

													?>

													<script type="text/javascript">
														var myData = <?=json_encode($tarefas,true)?>;
														//console.log(myData);
														// var myData = [
														//     {
														//       id: 1,
														//       title: 'Item 2',
														//       subs: [
														// 		        {
														// 		          id: 10,
														// 		          title: 'Item 2-1'
														// 		        }, {
														// 		          id: 11,
														// 		          title: 'Item 2-2'
														// 		        }, {
														// 		          id: 12,
														// 		          title: 'Item 2-3'
														// 		        }
														// 		    ]
														//     }, {
														//       id: 2,
														//       title: 'Item 3'
														//     },
														//     // more data here
														// ];
													</script>
												<div class="help-block with-errors"></div>
											</div>
										</div>

									</div>

									<div class="push10"></div>

									<div class="row">

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Número do Medição</label>
												<input type="text" class="form-control input-sm" name="NUM_MEDICAO" id="NUM_MEDICAO" value="" maxlength="11" required>
											</div>
											<div class="help-block with-errors"></div>
										</div> 
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Data do Medição</label>
												<div class="input-group date datePicker">
													<input type='text' class="form-control input-sm data" name="DAT_MEDICAO" id="DAT_MEDICAO" value=""/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label">Evolução Física</label>
												<input type="text" class="form-control input-sm money" name="VAL_EVOLUCAO" id="VAL_EVOLUCAO" value="" data-mask="##0" data-mask-reverse="true" maxlength="11" readonly>
											</div>
											<div class="help-block with-errors">PORCENTAGEM (%)</div>
										</div> 
										
										<div class="col-md-3" id="div_medicao">
											<div class="form-group">
												<label for="inputName" class="control-label required">Valor da Medição</label>
												<input type="text" class="form-control input-sm money" name="VAL_MEDICAO" id="VAL_MEDICAO" value="" data-mask="##0" data-mask-reverse="true" maxlength="11" data-medicao="medicao" r_eadonly required>
												<input type="hidden" class="form-control input-sm money" name="VAL_MEDICAO_TOTAL" id="VAL_MEDICAO_TOTAL" value="" data-mask="##0" data-mask-reverse="true" maxlength="11">
											</div>
											<div class="help-block with-errors">REAIS (R$)</div>
										</div>
										
									</div>



									<div class="push10"></div>

									<div class="row">		
						
										<div class="col-md-12">
											<div class="form-group">
												<label for="inputName" class="control-label">Comentário</label>
												<textarea type="text" class="form-control input-sm" rows="3" name="DES_COMENT" id="DES_COMENT" maxlength="250" ></textarea>
											</div>
											<div class="help-block with-errors"></div>
										</div> 

									</div>
									
									<div class="push10"></div>

									<?php include "uploadConvenio.php"; ?>
									
									<div class="push10"></div>
									
							</fieldset>

							<div class="push20"></div>												
																	
							<hr>	
							<div class="form-group text-right col-lg-12">
								
								  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								  <!-- <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button> -->
								  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
								
							</div>
							
							<input type="hidden" name="TIP_CONTROLE" id="TIP_CONTROLE" value="BLM">
							<input type="hidden" name="COD_TAREFAS" id="COD_TAREFAS" value="">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
							<input type="hidden" name="COD_CONVENI" id="COD_CONVENI" value="<?=$cod_conveni?>">
							<input type="hidden" name="COD_OBJETOANEXO" id="COD_OBJETOANEXO" value="">
							<input type="hidden" name="NUM_CONTADOR" id="NUM_CONTADOR" value="<?php echo $num_contador; ?>" />
							<input type="hidden" name="COD_CONTRAT" id="COD_CONTRAT" value="<?=$cod_contrat?>">
							<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=$cod_cliente?>">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">	
							
							<div class="push5"></div> 
						
						</form>
						
						<div class="push50"></div>
						
						<div class="col-lg-12">

							<div class="no-more-tables">
						
								
								
								<table class="table table-bordered table-striped table-hover">
								  <thead>
									<tr>
									  <th width="40"></th>
									  <th>Código</th>
									  <th>Comentário</th>
									  <th>Núm. Medição</th>
									  <th>Data Medição</th>
									  <th>Evolução</th>
									  <th>Valor</th>
									</tr>
								  </thead>
									<tbody>
									
									<?php 
										$sql = "SELECT * FROM CONTROLE_RECEBIMENTO WHERE COD_EMPRESA = $cod_empresa AND TIP_CONTROLE = 'BLM' AND COD_CONTRAT = $cod_contrat";
												
										
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
										
										$count=0;
										$val_totalMed = 0;
										$val_totalEvo = 0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
										  {														  
											$count++;	
											echo"
												<tr>
												  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
												  <td>".$qrBuscaModulos['COD_RECEBIM']."</td>
												  <td>".$qrBuscaModulos['DES_COMENT']."</td>
												  <td>".$qrBuscaModulos['NUM_MEDICAO']."</td>
												  <td>".fnDataShort($qrBuscaModulos['DAT_MEDICAO'])."</td>
												  <td class='text-right'>".fnValor($qrBuscaModulos['VAL_EVOLUCAO'],2)."</td>
												  <td class='text-right'>".fnValor($qrBuscaModulos['VAL_MEDICAO'],2)."</td>
												</tr>
												
												<input type='hidden' id='ret_COD_RECEBIM_".$count."' value='".$qrBuscaModulos['COD_RECEBIM']."'>
												<input type='hidden' id='ret_COD_CONVENI_".$count."' value='".$qrBuscaModulos['COD_CONVENI']."'>
												<input type='hidden' id='ret_COD_CLIENTE_".$count."' value='".$qrBuscaModulos['COD_CLIENTE']."'>
												<input type='hidden' id='ret_COD_TAREFA_".$count."' value='".$qrBuscaModulos['COD_TAREFA']."'>
												<input type='hidden' id='ret_COD_EXTERNO_".$count."' value='".$qrBuscaModulos['COD_MEDICAO']."'>
												<input type='hidden' id='ret_NUM_MEDICAO_".$count."' value='".$qrBuscaModulos['NUM_MEDICAO']."'>
												<input type='hidden' id='ret_DAT_MEDICAO_".$count."' value='".fnDataShort($qrBuscaModulos['DAT_MEDICAO'])."'>
												<input type='hidden' id='ret_VAL_EVOLUCAO_".$count."' value='".fnValor($qrBuscaModulos['VAL_EVOLUCAO'],2)."'>
												<input type='hidden' id='ret_VAL_MEDICAO_".$count."' value='".fnValor($qrBuscaModulos['VAL_MEDICAO'],2)."'>
												<input type='hidden' id='ret_DES_COMENT_".$count."' value='".$qrBuscaModulos['DES_COMENT']."'>
												";
												$val_totalMed+= $qrBuscaModulos['VAL_MEDICAO']; 
												$val_totalEvo+= $qrBuscaModulos['VAL_EVOLUCAO']; 
											  }												  
									?>

									</tbody>

									<tfoot>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td class="text-right"><b><?=fnValor($val_totalEvo,2);?></b></td>
											<td class="text-right"><b><?=fnValor($val_totalMed,2);?></b></td>
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
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="js/plugins/Drop-Down-Combo-Tree/comboTreePlugin.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
	<link rel="stylesheet" href="js/plugins/Drop-Down-Combo-Tree/style.css" />
	
	<script type="text/javascript">

		let comboTarefas;

		$(document).ready(function(){

			$('.upload').prop('disabled',true);
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});

			comboTarefas = $('#example').comboTree({
			  source : myData,
			  isMultiple: true,
			  cascadeSelect:true,
			  // selectableLastNode:true

			});
				
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';

			$('#formulario').validator({
			    // custom: {
			    //     'evolucao': function($el) {
			    //     	val_evofis = <?=$val_evofis?>,
			    //     	val_evolucao = parseFloat($el.val().replace('.','').replace(',','.'));
			    //     	if((val_evolucao+val_evofis) > 100){
			    //     		return true;
			    //     	}
			    //     },
			    //     'medicao': function($el) {
			    // 		val_valor = <?=$val_valor?>,
			    //     	val_medac = <?=$val_medac?>,
			    //     	val_medicao = parseFloat($el.val().replace('.','').replace(',','.'));
			    //     	if((val_medicao+val_medac) > val_valor){
			    //     		return true;
			    //     	}
			    //     }
			    // }
			});

			$("#COD_TAREFA").change(function(){
				let tipo = $("#COD_TAREFA option:selected").attr("data-tipo"),
					cod_tarefa = $("#COD_TAREFA option:selected").attr("data-id");
				console.log(tipo);
				console.log(" - ");
				console.log(cod_tarefa);
				$("."+tipo+"_"+cod_tarefa).attr('disabled',true);
				$("#COD_TAREFA").trigger("chosen:updated");
			});

			let quantity_timer;

			$("#example").change(function(){
				clearTimeout(quantity_timer);
			    quantity_timer = setTimeout(function() { 
			        $("#COD_TAREFAS").val(comboTarefas.getSelectedIds());
					console.log("ajxMedicao.do?id=<?=fnEncode($cod_empresa)?>&COD_TAREFAS="+$("#COD_TAREFAS").val())
			        $.ajax({
						type: "POST",                
						url: "ajxMedicao.do?id=<?=fnEncode($cod_empresa)?>",
						data: { COD_TAREFAS: $("#COD_TAREFAS").val()},
						beforeSend:function(){
							$("#div_medicao").html("<div class='loading' style='width:100%'></div>");
						},
						success: function(data) {
							console.log(data);
							$("#div_medicao").html(data);
							val_valor = <?=$val_valor?>,
							val_medicao = parseFloat($('#VAL_MEDICAO').val().replace('.','').replace(',','.')),
							$('#VAL_EVOLUCAO').unmask();

							if($.isNumeric(((val_medicao/val_valor)*100))){					
								$('#VAL_EVOLUCAO').val(((val_medicao/val_valor)*100).toFixed(2)).toString();
							}else{
								$('#VAL_EVOLUCAO').val('');
							}
						}
				    });
				    clearTimeout(quantity_timer);
			    }, 100);


			});

			// $('#CAD').click(function(e){
			// 	val_evofis = <?=$val_evofis?>,
	  //       	val_evolucao = parseFloat($('#VAL_EVOLUCAO').val().replace('.','').replace(',','.'));
	  //       	if((val_evolucao+val_evofis) > 100){
	        		
	  //       	}
			// });


			// $('#VAL_EVOLUCAO').change(function(){

			// 	val_evolucao = parseFloat($('#VAL_EVOLUCAO').val().replace('.','').replace(',','.')),
			// 	val_valor = <?=$val_valor?>,
			// 	$('#VAL_MEDICAO').unmask();

			// 	if($.isNumeric(((val_valor*val_evolucao)/100).toFixed(2))){
			// 		$('#VAL_MEDICAO').val(((val_valor*val_evolucao)/100).toFixed(2)).toString();
			// 	}else{
			// 		$('#VAL_MEDICAO').val('');
			// 	}
			// });

			// $('#VAL_MEDICAO').change(function(){

			// 	val_valor = <?=$val_valor?>,
			// 	val_medicao = parseFloat($('#VAL_MEDICAO').val().replace('.','').replace(',','.')),
			// 	$('#VAL_EVOLUCAO').unmask();

			// 	if($.isNumeric(((val_medicao/val_valor)*100))){					
			// 		$('#VAL_EVOLUCAO').val(((val_medicao/val_valor)*100).toFixed(2)).toString();
			// 	}else{
			// 		$('#VAL_EVOLUCAO').val('');
			// 	}
			// });

			$('#CAD,#ALT').click(function(e){
				if ($('#formulario').validator('validate').has('.has-error').length) {
					e.preventDefault();
				  }
			});

		});

		function retornaForm(index){
			$("#formulario #COD_RECEBIM").val($("#ret_COD_RECEBIM_"+index).val());
			$("#formulario #COD_OBJETOANEXO").val($("#ret_COD_RECEBIM_"+index).val());
			$("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_"+index).val());
			$("#formulario #COD_CLIENTE").val($("#ret_COD_CLIENTE_"+index).val());
			// $("#formulario #COD_TAREFA").val($("#ret_COD_TAREFA_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_"+index).val());
			$("#formulario #NUM_MEDICAO").val($("#ret_NUM_MEDICAO_"+index).val());
			$("#formulario #DAT_MEDICAO").val($("#ret_DAT_MEDICAO_"+index).val());
			$("#formulario #VAL_EVOLUCAO").val($("#ret_VAL_EVOLUCAO_"+index).val());
			$("#formulario #DES_COMENT").val($("#ret_DES_COMENT_"+index).val());
			// carregaComboMulti("formulario", "COD_TAREFA", $("#ret_COD_TAREFA_"+index).val());
			$('.upload').prop('disabled',false).removeAttr('disabled');
			//comboTarefas.setSelection([$("#ret_COD_TAREFA_"+index).val()]);
			//comboTarefas.clearSelection();
			// comboTarefas.destroy();
/*
			let selection = $.parseJSON("["+$("#ret_COD_TAREFA_"+index).val()+"]");
			
			comboTarefas.setSelection(selection);

			let quantity_timer2;
			clearTimeout(quantity_timer2);
		    quantity_timer2 = setTimeout(function() { 
		        $("#formulario #VAL_MEDICAO").val($("#ret_VAL_MEDICAO_"+index).val());
			    clearTimeout(quantity_timer2);
		    }, 300);
*/
			$("#formulario #VAL_MEDICAO").val($("#ret_VAL_MEDICAO_"+index).val());



			// alert(selection);



			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');

			refreshUpload();		
		}

		function carregaComboMulti(idForm, idCombo, cod) {

		var sistemasUni = cod;

		if (cod != "") {

			$("#" + idForm + " #" + idCombo).val('').trigger("chosen:updated");

			// explode a variavel e transforma em json
			var sistemasUniArr = sistemasUni.split(',');

			// looping no json pra pegar cada cod individualmente
			for (var i = 0; i < sistemasUniArr.length; i++) {
				//atribui cada codigo à combo
				$("#" + idForm + " #" + idCombo + " option[value=" + Number(sistemasUniArr[i]) + "]").prop("selected", "true");
			}

			//ATUALIZA O PLUGIN - CHOSEN
			$("#" + idForm + " #" + idCombo).trigger("chosen:updated");

		} else {

			$("#" + idForm + " #" + idCombo).val('').trigger("chosen:updated");

		}

	}
		
	</script>

	<?php include 'jsUploadConvenio.php'; ?>
