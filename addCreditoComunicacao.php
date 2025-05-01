<?php

	//echo "<h5>_".$opcao."</h5>";
$itens_por_pagina = 20;
$pagina = 1;

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
		$cod_canalcom = fnLimpaCampoZero($_REQUEST['COD_CANALCOM']);
		$qtd_dias = fnLimpaCampoZero($_REQUEST['QTD_DIAS']);
		$qtd_cred = fnLimpaCampo(fnValorSql($_REQUEST['QTD_CRED']));
		$qtd_saldo = fnLimpaCampo(fnValorSql($_REQUEST['QTD_CRED']));
		if (empty($_REQUEST['LOG_DEBITO'])) {$log_debito='N';}else{$log_debito=$_REQUEST['LOG_DEBITO'];}

		if($log_debito == "S"){
			$qtd_saldo = 0;
		}

		if($log_debito == 'N'){
			$idSession = "CREDITO";
			$tipoTransac = "C";
		}else{
			$idSession = "DEBITO";
			$tipoTransac = "D";
		}

		if($cod_canalcom == 13){
			$cod_produto = 1;
		}else if($cod_canalcom == 21){
			$cod_produto = 2;
		}else{
			$cod_produto = 3;
		}

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != ''){

			if($log_debito == "S"){

				$arraydebitos=array('quantidadeEmailenvio'=>$qtd_cred,
					'COD_EMPRESA'=>$cod_empresa,
					'PERMITENEGATIVO'=>'N',
					'COD_CANALCOM'=>$cod_produto,
					'CONFIRMACAO'=>'S',
					'COD_CAMPANHA'=>0,    
					'LOG_TESTE'=> 'N',
					'DAT_CADASTR'=> date('Y-m-d H:i:s'),
					'CONNADM'=>$connAdm->connAdm()
				); 

				$retornoDeb=FnDebitos($arraydebitos);

			}else{

				$sql = "INSERT INTO PEDIDO_MARKA(
					COD_PRODUTO,
					COD_ORCAMENTO, 
					QTD_PRODUTO, 
					QTD_SALDO_ATUAL, 
					COD_EMPRESA, 
					COD_UNIVEND, 
					ID_SESSION_PAGSEGURO, 
					PAG_CONFIRMACAO, 
					TIP_LANCAMENTO, 
					QTD_DIAS,
					COD_CAMPANHA,
					COD_USUCADA, 
					LOG_TESTE
					) VALUES(
					$cod_produto,
					1,
					$qtd_cred,
					$qtd_saldo,
					$cod_empresa,
					1,
					'$idSession',
					'S',
					'$tipoTransac',
					'$qtd_dias',
					0,
					$cod_usucada,
					'N'
				)";

					// FNeSCREVE($sql);
					
					mysqli_query($connAdm->connAdm(),trim($sql));

					$sqlMax = "SELECT COD_VENDA, DAT_CADASTR 
					FROM PEDIDO_MARKA 
					WHERE COD_VENDA = 
					(SELECT MAX(COD_VENDA) COD_VENDA FROM PEDIDO_MARKA
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_USUCADA = $cod_usucada
						ORDER BY 1 DESC
						LIMIT 1)";

					$arrMax = mysqli_query($connAdm->connAdm(),trim($sqlMax));

					$qrMax = mysqli_fetch_assoc($arrMax);

					$dat_validade = date("Y-m-d", strtotime($qrMax[DAT_CADASTR]." + $qtd_dias days"));

					$sqlUpdt = "UPDATE PEDIDO_MARKA SET
					DAT_VALIDADE = '$dat_validade'
					WHERE COD_EMPRESA = $cod_empresa
					AND COD_VENDA = $qrMax[COD_VENDA]";

	                // fnEscreve($sqlMax);
	                // fnEscreve($sqlUpdt);

					mysqli_query($connAdm->connAdm(),trim($sqlUpdt));

				}

				
				?>
				<script>parent.$("#ATUALIZA_TELA").val('S');</script>
				
				<script>parent.$("#FEZ_AVULSO").val('S');</script>
				<?php				
				
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

	$dspValidade = "block";

	if($log_debito == "S"){
		$dspValidade = "none";
	}

	$modulo = fnDecode($_GET['mod']);

	if($modulo == 2061){
		$cod_canalcom = $_GET['tp'];
	}


	
	//fnMostraForm();

	?>

	<!-- <div class="push30"></div> -->

	<div class="row">				

		<div class="col-md-12 margin-bottom-30">
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
								<span class="text-primary"><?php echo $NomePg; ?></span>
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

						<div class="push30"></div> 

						<div class="login-form">

							<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

								<fieldset>
									<legend>Dados Gerais</legend> 

									<div class="row">

										<div class="col-md-4">
											<div class="form-group">

												<label for="inputName" class="control-label required">Tipo do Canal</label>
												<select data-placeholder="Selecione o canal" name="COD_CANALCOM" id="COD_CANALCOM" class="chosen-select-deselect" required>
													<!-- <option value="0">Todos</option>   -->
													<?php

													$sql = "SELECT COD_CANALCOM, DES_CANALCOM FROM CANAL_COMUNICACAO";
													$arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));
													while($qrCanal = mysqli_fetch_assoc($arrayQuery)){
														?>

														<option value="<?=$qrCanal[COD_CANALCOM]?>"><?=$qrCanal['DES_CANALCOM']?></option>

														<?php
													}
													?>                        
												</select> 
												<div class="help-block with-errors"></div>
												<script type="text/javascript">$("#formulario #COD_CANALCOM").val('<?=$cod_canalcom?>').trigger("chosen:updated");</script>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Quantidade</label>
												<input type="text" class="form-control input-sm int" name="QTD_CRED" id="QTD_CRED" required>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-3" id="validade" style="display: <?=$dspValidade?>;">
											<div class="form-group">
												<label for="inputName" class="control-label required">Validade</label>
												<input type="text" class="form-control input-sm int" name="QTD_DIAS" id="QTD_DIAS" required>
												<div class="help-block with-errors">Em dias</div>
											</div>
										</div>

										<div class="col-md-2">   
											<div class="form-group">
												<label for="inputName" class="control-label">Lançar como Débito</label> 
												<div class="push5"></div>
												<label class="switch">
													<input type="checkbox" name="LOG_DEBITO" id="LOG_DEBITO" class="switch" value="S">
													<span></span>
												</label>
											</div>
										</div>	

									</div>

								</fieldset>	

								<div class="push10"></div>
								<hr>	
								<div class="form-group text-right col-lg-12">

									<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Adicionar</button>

								</div>

								<input type="hidden" name="opcao" id="opcao" value="">
								<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
								<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		

								<div class="push5"></div> 

							</form>

							<?php if($modulo == 2061){ ?>
								<div class="push50"></div>

								<div class="col-lg-12">

									<div class="no-more-tables">

										<form name="formLista">

											<table class="table table-bordered table-striped table-hover tableSorter">
												<thead>
													<tr>
														<!-- <th>Cod</th> -->
														<th>Data</th>
														<th>ID</th>
														<th>Descrição</th>
														<th>Vl. Unitário</th>
														<th>Quantidade</th>
														<th>Total</th>
														<th>Situação</th>
														<th>Validade</th>
													</tr>
												</thead>
												<tbody id="relatorioConteudo">

													<?php

													if($cod_canalcom != 0){
														$andCanal = "AND prod.COD_CANALCOM = $cod_canalcom";
													}else{
														$andCanal = "";
													}
										                    //paginação
													$sqlcontador ="SELECT * FROM pedido_marka pedido
													INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
													INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
													INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA     
													WHERE pedido.COD_ORCAMENTO > 0
													AND pedido.COD_EMPRESA = $cod_empresa
													$andCanal
													";

													$retorno = mysqli_query($connAdm->connAdm() ,$sqlcontador);
													$total_itens_por_pagina=mysqli_num_rows($retorno);
													$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);
										                    //variavel para calcular o início da visualização com base na página atual
													$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
										                   // fnEscreve($numPaginas);
													$sqlCount = "SELECT pedido.TIP_LANCAMENTO,
													pedido.COD_VENDA,
													pedido.COD_CAMPANHA,
													emp.NOM_EMPRESA,
													pedido.DAT_CADASTR,
													CONCAT(DATE_FORMAT(MID(CONVERT(pedido.COD_ORCAMENTO,CHAR),1,6), '%d/%m/%Y')
														,' ' , MID(pedido.COD_ORCAMENTO,7,2),':',MID(pedido.COD_ORCAMENTO,9,2),':', MID(pedido.COD_ORCAMENTO,11,2)) AS DAT_CADASTRO,
													pedido.COD_ORCAMENTO,
													canal.DES_CANALCOM,
													round(pedido.QTD_PRODUTO,0) AS QTD_PRODUTO,
													pedido.VAL_UNITARIO,
													round(pedido.VAL_UNITARIO * pedido.QTD_PRODUTO,2) AS VAL_TOTAL ,
													if(pedido.PAG_CONFIRMACAO='S', 'Pagamento Confirmado', 'Aguardando Confirmação de Pagamento') AS DES_SITUACAO
														FROM pedido_marka pedido 
													INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
													INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
													INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
													WHERE pedido.COD_ORCAMENTO > 0 
													AND pedido.COD_EMPRESA = $cod_empresa
													$andCanal
													ORDER BY pedido.DAT_CADASTR DESC, canal.DES_CANALCOM";

										                   // fnEscreve($sqlCount);

													$arrayQueryCount = mysqli_query($connAdm->connAdm(), trim($sqlCount));

													$count = 0;

													$qtd_contrato = 0;
													$qtd_envio = 0;
													$qtd_email = 0;
													$qtd_sms = 0;
													$qtd_wpp = 0;
													$qtd_cred = 0;
													$qtd_deb = 0;
													$credSms = 0;
													$credEmail = 0;
													$debSms = 0;
													$debEmail = 0;

													while ($qrListaCount = mysqli_fetch_assoc($arrayQueryCount)) {
														switch($qrListaCount['DES_CANALCOM']){

															case 'SMS':
															if($qrListaCount['TIP_LANCAMENTO'] == 'D'){
																$qtd_sms = $qtd_sms - $qrListaCount[QTD_PRODUTO];
																$debSms = $debSms - $qrListaCount[QTD_PRODUTO];
															}else{
																$qtd_sms = $qtd_sms + $qrListaCount[QTD_PRODUTO];
																$credSms = $credSms + $qrListaCount[QTD_PRODUTO];
															}
															break;

															case 'WhatsApp':
															if($qrListaCount['TIP_LANCAMENTO'] == 'D'){
																$qtd_wpp = $qtd_wpp - $qrListaCount[QTD_PRODUTO];
															}else{
																$qtd_wpp = $qtd_wpp + $qrListaCount[QTD_PRODUTO];
															}
															break;

															default:
															if($qrListaCount['TIP_LANCAMENTO'] == 'D'){
																$qtd_email = $qtd_email - $qrListaCount[QTD_PRODUTO];
																$debEmail = $debEmail - $qrListaCount[QTD_PRODUTO];
															}else{
																$qtd_email = $qtd_email + $qrListaCount[QTD_PRODUTO];
																$credEmail = $credEmail + $qrListaCount[QTD_PRODUTO];
															}
															break;

														}
													}

													$sql = "SELECT pedido.TIP_LANCAMENTO,
													pedido.COD_VENDA,
													pedido.COD_CAMPANHA,
													emp.NOM_EMPRESA,
													pedido.DAT_CADASTR,
													pedido.DAT_VALIDADE,
													pedido.ID_SESSION_PAGSEGURO,
													CONCAT(DATE_FORMAT(MID(CONVERT(pedido.COD_ORCAMENTO,CHAR),1,6), '%d/%m/%Y')
														,' ' , MID(pedido.COD_ORCAMENTO,7,2),':',MID(pedido.COD_ORCAMENTO,9,2),':', MID(pedido.COD_ORCAMENTO,11,2)) AS DAT_CADASTRO,
													pedido.COD_ORCAMENTO,
													canal.DES_CANALCOM,
													round(pedido.QTD_PRODUTO,0) AS QTD_PRODUTO,
													pedido.VAL_UNITARIO,
													round(pedido.VAL_UNITARIO * pedido.QTD_PRODUTO,2) AS VAL_TOTAL ,
													if(pedido.PAG_CONFIRMACAO='S', 'Pagamento Confirmado', 'Aguardando Confirmação de Pagamento') AS DES_SITUACAO
														FROM pedido_marka pedido 
													INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
													INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
													INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
													WHERE pedido.COD_ORCAMENTO > 0 
													AND pedido.COD_EMPRESA = $cod_empresa
													$andCanal
													ORDER BY pedido.DAT_CADASTR DESC, canal.DES_CANALCOM LIMIT $inicio,$itens_por_pagina";

										                   // fnEscreve($sql);

													$arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql)) or die(mysqli_error());


													while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

														$count++;

														if($qrLista['TIP_LANCAMENTO'] == 'D'){

															$qtd_produto = "<span class='text-danger' style='font-size:14px;'><b>-</b></span>&nbsp;".fnValor($qrLista[QTD_PRODUTO],0);
															$val_unitario = "";
															$val_total = "";
															$qtd_envio = $qtd_envio + $qrLista[QTD_PRODUTO];
															$msg = ucfirst(strtolower($qrLista['ID_SESSION_PAGSEGURO']));
															$qtd_deb += $qrLista[QTD_PRODUTO];
															$dat_validade = "";

															$sql = "SELECT DES_CAMPANHA FROM CAMPANHA WHERE COD_CAMPANHA = $qrLista[COD_CAMPANHA]";
															$qrCamp = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''), trim($sql)));
															$id = $qrCamp[DES_CAMPANHA];


														}else{

															$qtd_produto = "<span class='text-success' style='font-size:14px;'><b>+</b></span>&nbsp;".fnValor($qrLista[QTD_PRODUTO],0);
															$val_unitario = fnValor($qrLista['VAL_UNITARIO'],6);
															$val_total = fnValor($qrLista['VAL_TOTAL'],2);
															$qtd_contrato = $qtd_contrato + $qrLista[QTD_PRODUTO];
															if($qrLista['COD_ORCAMENTO'] != ""){
																$msg = $qrLista['DES_SITUACAO'];
															}else{
																$msg = "Pagamento Confirmado";
															}
															$id = $qrLista[COD_ORCAMENTO];
															$qtd_cred += $qrLista[QTD_PRODUTO];
															$dat_validade = fnDataShort($qrLista[DAT_VALIDADE]);

															if($id == 1){
																$id = "Crédito Avulso";
																$msg = "Crédito Avulso";
															}

														}


														echo" <tr>                   
														<td><small>".fnDataFull($qrLista['DAT_CADASTR'])."</small></td>
														<td><small>".$id."</td>
														<td><small>".$qrLista['DES_CANALCOM']."</small></td>
														<td class='text-right'><small>".$val_unitario."</small></td>
														<td class='text-right'><small>".$qtd_produto."</small></td>
														<td class='text-right'><small>".$val_total."</small></td>   
														<td><small>".$msg."</small></td>
														<td><small>".$dat_validade."</small></td>
														</tr>
														";
													}
													?>

												</tbody>

												<tfoot>
													<tr>
														<th colspan="4"></th>
														<th colspan="2" class="text-right"><b>Créditos Email:</b> <?=fnValor($credEmail,0)?></th>
														<th colspan="2" class="text-right"><b>Débitos Email:</b> <?=fnValor($debEmail,0)?></th>
													</tr>
													<tr>
														<th colspan="4"></th>
														<th colspan="2" class="text-right"><b>Créditos SMS:</b> <?=fnValor($credSms,0)?></th>
														<th colspan="2" class="text-right"><b>Débitos SMS:</b> <?=fnValor($debSms,0)?></th>
													</tr>

													<tr>
														<th colspan="100">
															<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
														</th>
													</tr>
													<tr>
														<th class="" colspan="100">
															<center><ul id="paginacao" class="pagination-sm"></ul></center>
														</th>
													</tr>
												</tfoot>

											</table>

										</form>

									</div>

								</div>									
							<?php } ?>
							<div class="push"></div>

						</div>								

					</div>
				</div>
				<!-- fim Portlet -->
			</div>

		</div>					

		<div class="push20"></div> 

		<script type="text/javascript">

			$(function(){

				$("#LOG_DEBITO").change(function(){
					if($(this).prop("checked")){
						$("#QTD_DIAS").prop("required",false);
						$("#validade").fadeOut("fast");
					}else{
						$("#QTD_DIAS").prop("required",true);
						$("#validade").fadeIn("fast");
					}
					$('#formulario').validator('validate');
				});

			});

			function retornaForm(index){
				$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
				$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
				$('#formulario').validator('validate');			
				$("#formulario #hHabilitado").val('S');						
			}

		</script>	