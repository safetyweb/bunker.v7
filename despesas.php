<?php
	
	//echo fnDebug('true');
 
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
			$cod_medicao = fnLimpaCampoZero($_REQUEST['COD_MEDICAO']);
			$num_medicao = fnLimpaCampo($_REQUEST['NUM_MEDICAO']);
			$dat_medicao = fnLimpaCampo($_REQUEST['DAT_MEDICAO']);
			$val_evolucao = fnLimpaCampo($_REQUEST['VAL_EVOLUCAO']);
			$val_medicao = fnLimpaCampo($_REQUEST['VAL_MEDICAO']);
			$des_nomebem = fnLimpaCampo($_REQUEST['DES_NOMEBEM']);
			$tip_controle = fnLimpaCampo($_REQUEST['TIP_CONTROLE']);
			$num_contador = fnLimpaCampo($_REQUEST['NUM_CONTADOR']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){							
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO CONTROLE_RECEBIMENTO(
											COD_EMPRESA,
											COD_CONVENI,
											COD_CONTRAT,
											COD_CLIENTE,
											COD_MEDICAO,
											NUM_MEDICAO,
											DAT_MEDICAO,
											VAL_EVOLUCAO,
											VAL_MEDICAO,
											DES_NOMEBEM,
											TIP_CONTROLE,
											COD_USUCADA
											) VALUES(
											$cod_empresa,
											$cod_conveni,
											$cod_contrat,
											$cod_cliente,
											$cod_medicao,
											'$num_medicao',
											'".fnDataSql($dat_medicao)."',
											'".fnValorSql($val_evolucao)."',
											'".fnValorSql($val_medicao)."',
											'$des_nomebem',
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
							$arrayCont = mysqli_query(connTemp($cod_empresa,''),$sqlArquivos);

							if(mysqli_num_rows($arrayCont) > 0){
								$sqlUpd = "UPDATE ANEXO_CONVENIO SET COD_RECEBIM = $cod_recebim, LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
								mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
							}

						}else{
							// $sqlUpd = "UPDATE ANEXO_CONVENIO SET LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_LICITAC = $cod_licitac AND LOG_STATUS = 'N'";
							// mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
						}

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						$sql = "UPDATE CONTROLE_RECEBIMENTO SET
											COD_EXTERNO='$cod_externo',
											NUM_MEDICAO='$num_medicao',
											DAT_MEDICAO='".fnDataSql($dat_medicao)."',
											VAL_EVOLUCAO='".fnValorSql($val_evolucao)."',
											VAL_MEDICAO='".fnValorSql($val_medicao)."',
											DES_NOMEBEM='$des_nomebem',
											COD_ALTERAC=$cod_usucada
											WHERE COD_RECEBIM = $cod_recebim
											";
							
						//fnEscreve($sql);
		                mysqli_query(connTemp($cod_empresa,''),$sql);

		                $sqlUpd = "UPDATE ANEXO_CONVENIO SET LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_RECEBIM = $cod_recebim AND LOG_STATUS = 'N'";
						mysqli_query(connTemp($cod_empresa,''),$sqlUpd);

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

	if(isset($_GET['idC'])){
		if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){
		
			//busca dados do convênio
			$cod_conveni = fnDecode($_GET['idC']);

		}

	}


	if(isset($_GET['idCT'])){
		if (is_numeric(fnLimpacampo(fnDecode($_GET['idCT'])))){
		
			//busca dados do contrato
			$cod_contrat = fnDecode($_GET['idCT']);
			$tip_controle = fnDecode($_GET['idTp']);

			$sql = "SELECT CTT.*, CL.COD_CLIENTE, CL.NOM_CLIENTE FROM CONTRATO CTT 
					LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = CTT.COD_CLIENTE
					WHERE CTT.COD_CONTRAT = $cod_contrat 
					AND CTT.COD_EMPRESA = $cod_empresa
					AND CTT.COD_CONVENI = $cod_conveni
					";

			//fnEscreve($sql);
			$arrayQuery =  mysqli_query(connTemp($cod_empresa,''),$sql);
			$qrContrat = mysqli_fetch_assoc($arrayQuery);

			if (isset($qrContrat)){
				$cod_contrat = $qrContrat['COD_CONTRAT'];
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

	$tp_cont = 'Anexo do Recebimento';
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
	      
	//fnMostraForm();
	//fnEscreve($cod_empresa);

	if($tip_controle == "RCB"){
		$label1 = "Quantidade Itens";
		$label2 = "Valor Comprado";
		$valor2 = "";
		$txtCuringa = "Recebimento";
	}else{
		$label1 = "Evolução Física Acumulada";
		$label2 = "Valor da Medição Acumulada";
		$valor2 = fnValor($val_medac,2);
		$txtCuringa = "Medição";
	}

?>

<style>
	
.area {
  width: 100%;
  padding: 7px;
}

#dropZone {
  display: block;
  border: 2px dashed #bbb;
  -webkit-border-radius: 5px;
  border-radius: 5px;
  margin-left: -7px;
}

#dropZone p{
	font-size: 10pt;
	letter-spacing: -0.3pt;
	margin-bottom: 0px;
}

#dropzone .fa{
	font-size: 15pt;
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
				
					<div class="tabbable-line">
						<ul class="nav nav-tabs">
							<li>
								<a href="action.do?mod=<?php echo fnEncode(1348)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_conveni); ?>" style="text-decoration: none;">
								<span class="fal fa-arrow-circle-left fa-2x"></span></a>
							</li>
						</ul>
					</div>										
					
					<div class="push20"></div> 			
					
						<form role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
							<legend>Dados Gerais</legend> 
						
								<div class="row">
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
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
											<input type="text" class="form-control input-sm money leitura" name="VAL_CONTRATO" id="VAL_CONTRATO" value="<?=fnValor($val_valor,2)?>" readonly maxlength="11">
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label"><?=$label1?></label>
											<input type="text" class="form-control input-sm money leituraOff" name="VAL_EVOFIS" id="VAL_EVOFIS" value="<?=fnValor($val_evofis,2)?>" readonly maxlength="11">
										</div>
										<div class="help-block with-errors"></div>
									</div> 
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label"><?=$label2?></label>
											<input type="text" class="form-control input-sm money leituraOff" name="VAL_MEDAC" id="VAL_MEDAC" value="<?=$valor2?>" readonly maxlength="11">
										</div>
										<div class="help-block with-errors"></div>
									</div>

								</div>
								
						</fieldset>

						<div class="push20"></div>							

						<div class="row">
							
							<div class="push10"></div>

							<?php // include "uploadConvenio.php"; ?>
							
							<div class="push10"></div>

						</div>						
																
						<div class="push10"></div>
						<hr>	
						<!-- <div class="form-group text-right col-lg-12">
							
							  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
							
						</div> -->
						
						<input type="hidden" name="TIP_CONTROLE" id="TIP_CONTROLE" value="RCB">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
						<input type="hidden" name="COD_CONVENI" id="COD_CONVENI" value="<?=$cod_conveni?>">
						<input type="hidden" name="COD_OBJETOANEXO" id="COD_OBJETOANEXO" value="">
						<input type="hidden" name="COD_CONTRAT" id="COD_CONTRAT" value="<?=$cod_contrat?>">
						<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=$cod_cliente?>">
						<input type="hidden" name="NUM_CONTADOR" id="NUM_CONTADOR" value="<?php echo $num_contador; ?>" />
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
						
						<div class="push5"></div> 
						
						</form>
						
						<div class="push50"></div>
						
						<div class="col-lg-12">

							<div class="no-more-tables">
						
								<form name="formLista">
								
								<table class="table table-bordered table-striped table-hover">

									<?php
									$val_total_g = 0;
									$val_totmed_g = 0;
									$val_totevo_g = 0;
									$count = 0;
									$lote = 0;
									$list_lote_drop = "";
									
									$sqlote = "SELECT 0 COD_RECEBIM,'' DES_NOMEBEM UNION
											SELECT DISTINCT COD_RECEBIM,DES_NOMEBEM FROM controle_recebimento
											WHERE COD_EMPRESA = $cod_empresa AND  
											TIP_CONTROLE = 'RCB' AND 
											COD_CONTRAT = $cod_contrat AND  
											COD_CONVENI = $cod_conveni AND
											LOG_LOTE=1";
									//fnEscreve($sqlote);
									$arrayQueryLote = mysqli_query(connTemp($cod_empresa, ''), $sqlote);
									while ($qrLote = mysqli_fetch_assoc($arrayQueryLote)) {
										$lote++;
										?>
										<thead>
											<?php
											if ($qrLote["COD_RECEBIM"] > 0){
											?>
											<tr class="bg-primary">
												<th colspan=100>
													<div style="display: flex;align-content: center;align-items: center;">
														<div style="flex:auto"><input type='text' value='<?=$qrLote["DES_NOMEBEM"]?>' class='input-sm bg-primary' style='border:0;width:100%;font-size:15px;' onChange='renomeiaLote(<?=$qrLote["COD_RECEBIM"]?>);' name='nome_lote[<?=$qrLote["COD_RECEBIM"]?>]'></div>
													</div>
												</th>
											</tr>
											<?php
												$list_lote_drop .= "<li><a href='javascript:' onClick='addLote(".$qrLote["COD_RECEBIM"].")'>".$qrLote["DES_NOMEBEM"]."</a></li>";
											}
											?>
											<tr>
												<th>Código</th>
												<th>Descrição do Bem</th>
												<th>Núm. Recebimento</th>
												<th>Data Recebimento</th>
												<th class="text-right">Quantidade</th>
												<th class="text-right">Valor Un.</th>
												<th class="text-right">Valor</th>
											</tr>
										</thead>
										<tbody>
										<?php
										$sql = "SELECT * FROM CONTROLE_RECEBIMENTO 
										WHERE COD_EMPRESA = $cod_empresa AND  
										TIP_CONTROLE = 'RCB' AND 
										COD_CONTRAT = $cod_contrat AND  
										COD_CONVENI = $cod_conveni AND
										IFNULL(LOG_LOTE,0)=0 AND
										".($qrLote["COD_RECEBIM"] > 0?"IFNULL(COD_RECEBIM_LOTE,0) = ".$qrLote["COD_RECEBIM"]:"IFNULL(COD_RECEBIM_LOTE,0) <= 0");
								

										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

										$val_total = 0;
										$val_totmed = 0;
										$val_totevo = 0;
										while ($qrItem = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
											<tr>
												<td>" . $qrItem['COD_RECEBIM'] . "</td>
												<td>" . $qrItem['DES_NOMEBEM'] . "</td>
												<td>" . $qrItem['NUM_MEDICAO'] . "</td>
												<td>" . fnDataShort($qrItem['DAT_MEDICAO']) . "</td>
												<td class='text-right'>" . fnValor($qrItem['VAL_EVOLUCAO'], 2) . "</td>
												<td class='text-right'>" . fnValor($qrItem['VAL_MEDICAO'], 2) . "</td>
												<td class='text-right'>" . fnValor($qrItem['VAL_TOTAL'], 2) . "</td>
											</tr>
											
											<input type='hidden' id='ret_COD_RECEBIM_" . $count . "' value='" . $qrItem['COD_RECEBIM'] . "'>
											<input type='hidden' id='ret_COD_CONVENI_" . $count . "' value='" . $qrItem['COD_CONVENI'] . "'>
											<input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . $qrItem['COD_CLIENTE'] . "'>
											<input type='hidden' id='ret_COD_MEDICAO_" . $count . "' value='" . $qrItem['COD_MEDICAO'] . "'>
											<input type='hidden' id='ret_NUM_MEDICAO_" . $count . "' value='" . $qrItem['NUM_MEDICAO'] . "'>
											<input type='hidden' id='ret_DAT_MEDICAO_" . $count . "' value='" . fnDataShort($qrItem['DAT_MEDICAO']) . "'>
											<input type='hidden' id='ret_VAL_EVOLUCAO_" . $count . "' value='" . fnValor($qrItem['VAL_EVOLUCAO'], 2) . "'>
											<input type='hidden' id='ret_VAL_MEDICAO_" . $count . "' value='" . fnValor($qrItem['VAL_MEDICAO'], 2) . "'>
											<input type='hidden' id='ret_VAL_TOTAL_" . $count . "' value='" . fnValor($qrItem['VAL_TOTAL'], 2) . "'>
											<input type='hidden' id='ret_DES_NOMEBEM_" . $count . "' value='" . $qrItem['DES_NOMEBEM'] . "'>
											";


											$val_total += $qrItem['VAL_TOTAL'];
											$val_totmed += $qrItem['VAL_MEDICAO'];
											$val_totevo += $qrItem['VAL_EVOLUCAO'];
											$val_total_g += $qrItem['VAL_TOTAL'];
											$val_totmed_g += $qrItem['VAL_MEDICAO'];
											$val_totevo_g += $qrItem['VAL_EVOLUCAO'];
										}
										?>

										
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td class="text-right"><b><?= fnValor($val_totevo, 2); ?></b></td>
												<td class="text-right"><b><?= fnValor($val_totmed, 2); ?></b></td>
												<td class="text-right"><b><?= fnValor($val_total, 2); ?></b></td>
												<td></td>
											</tr>

											
										</tbody>
										<?php
									}

									if ($lote > 1){
									?>

									<tfoot class="bg-primary">
										<tr>
											<td colspan=4>Total Geral</td>
											<td class="text-right"><b><?= fnValor($val_totevo_g, 2); ?></b></td>
											<td class="text-right"><b><?= fnValor($val_totmed_g, 2); ?></b></td>
											<td class="text-right"><b><?= fnValor($val_total_g, 2); ?></b></td>
											<td></td>
										</tr>
									</tfoot>

									<?php
									}
									?>
							</table>

								<!-- <input type="hidden" id="ret_VAL_TOTAL" value="<?=fnValor($val_total,2);?>"> -->
								
								</form>

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
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
	
	<script type="text/javascript">

		$(document).ready(function(){

			// $("#formulario #VAL_MEDAC").val($("#ret_VAL_TOTAL").val());

			$('.upload').prop('disabled',true);
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
				
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			$('#VAL_EVOLUCAO,#VAL_MEDICAO').change(function(){
				$('#VAL_VALOR').unmask();
					if($('#VAL_EVOLUCAO').val() != ''){
						val_evolucao = parseFloat($('#VAL_EVOLUCAO').val().replace('.','').replace(',','.'));
					}else{
						val_evolucao = 0;
					}
					if($('#VAL_MEDICAO').val() != ''){
						val_medicao = parseFloat($('#VAL_MEDICAO').val().replace('.','').replace(',','.'));
					}else{
						val_medicao = 0;
					}
				total = (val_evolucao*val_medicao).toFixed(2);
				$('#VAL_VALOR').val(total).toString().mask('##0');
			});

		});	
		
		function retornaForm(index){
			$("#formulario #COD_RECEBIM").val($("#ret_COD_RECEBIM_"+index).val());
			$("#formulario #COD_OBJETOANEXO").val($("#ret_COD_RECEBIM_"+index).val());
			$("#formulario #COD_CONTRAT").val($("#ret_COD_EMPRESA_"+index).val());
			$("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_"+index).val());
			$("#formulario #COD_CLIENTE").val($("#ret_COD_CLIENTE_"+index).val());
			$("#formulario #COD_MEDICAO").val($("#ret_COD_MEDICAO_"+index).val());
			$("#formulario #NUM_MEDICAO").val($("#ret_NUM_MEDICAO_"+index).val());
			$("#formulario #DAT_MEDICAO").val($("#ret_DAT_MEDICAO_"+index).val());
			$("#formulario #VAL_EVOLUCAO").val($("#ret_VAL_EVOLUCAO_"+index).val());
			$("#formulario #VAL_MEDICAO").val($("#ret_VAL_MEDICAO_"+index).val());
			$("#formulario #VAL_VALOR").val($("#ret_VAL_VALOR_"+index).val());
			$("#formulario #DES_NOMEBEM").val($("#ret_DES_NOMEBEM_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');		
			refreshUpload();	
		}
		
	</script>	