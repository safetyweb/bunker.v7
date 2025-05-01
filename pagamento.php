<?php
	
	//echo fnDebug('true');
 
    $hashLocal = mt_rand();	
	$cod_tpmodal = 0;
	
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
			
			$cod_caixa = fnLimpaCampoZero($_REQUEST['COD_CAIXA']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_conveni = fnLimpaCampo($_REQUEST['COD_CONVENI']);
			$cod_contrat = fnLimpaCampo($_REQUEST['COD_CONTRAT']);
			$cod_empenho = fnLimpaCampo($_REQUEST['COD_EMPENHO']);
			$cod_tipo = fnLimpaCampo($_REQUEST['COD_TIPO']);
			$num_ordem = fnLimpaCampo($_REQUEST['NUM_ORDEM']);
			$dat_credito = fnLimpaCampo($_REQUEST['DAT_CREDITO']);
			$des_coment = fnLimpaCampo($_REQUEST['DES_COMENT']);
			$val_credito = fnLimpaCampo($_REQUEST['VAL_CREDITO']);
			$num_contador = fnLimpaCampo($_REQUEST['NUM_CONTADOR']);

			//fnEscreve($cod_licitac);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){							
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO CAIXA(
											COD_EMPRESA,
											COD_CONVENI,
											COD_CONTRAT,
											COD_EMPENHO,
											COD_TIPO,
											NUM_ORDEM,
											DAT_CREDITO,
											DES_COMENT,
											VAL_CREDITO,
											COD_USUCADA
											) VALUES(
											$cod_empresa,
											$cod_conveni,
											$cod_contrat,
											$cod_empenho,
											'$cod_tipo',
											'$num_ordem',
											'".fnDataSql($dat_credito)."',
											'$des_coment',
											'".fnValorSql($val_credito)."',
											$cod_usucada
											)";
					
						//fnEscreve($sql);
		                //fnTestesql(connTemp($cod_empresa,''),$sql);
						mysqli_query(connTemp($cod_empresa,''),$sql);

						$sqlCod = "SELECT MAX(COD_CAIXA) COD_CAIXA FROM CAIXA WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
						$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlCod);
						$qrCod = mysqli_fetch_assoc($arrayQuery);
						$cod_caixa = $qrCod[COD_CAIXA];

						$sqlArquivos = "SELECT 1 FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
						$arrayCont = mysqli_query(connTemp($cod_empresa,''),$sqlArquivos);

						if(mysqli_num_rows($arrayCont) > 0){
							$sqlUpd = "UPDATE ANEXO_CONVENIO SET COD_PAGAMEN = $cod_caixa, LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
							mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
						}

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						$sql = "UPDATE CAIXA SET
											VAL_CREDITO='".fnValorSql($val_credito)."',
											COD_TIPO='$cod_tipo',
											NUM_ORDEM='$num_ordem',
											DAT_CREDITO='".fnDataSql($dat_credito)."',
											DES_COMENT='$des_coment',
											COD_ALTERAC=$cod_usucada
								WHERE COD_CAIXA = $cod_caixa";
					
						//fnEscreve($sql);
		                mysqli_query(connTemp($cod_empresa,''),$sql);

		                $sqlUpd = "UPDATE ANEXO_CONVENIO SET LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_PAGAMEN = $cod_caixa AND LOG_STATUS = 'N'";
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

	if(isset($_GET['idE'])){
		if (is_numeric(fnLimpacampo(fnDecode($_GET['idE'])))){

			$cod_empenho = fnDecode($_GET['idE']);
			$cod_recebim = fnDecode($_GET['idR']);

			$sql = "SELECT CR.*,
						(SELECT ifnull(SUM( case when B.TIP_OPERACAO = 'C' then
							   A.VAL_CREDITO
							END),0) -
						ifnull(SUM( case when B.TIP_OPERACAO = 'D' then
							   A.VAL_CREDITO
							END),0) saldo_em_conta

						FROM caixa a,tip_credito b
						WHERE 
						a.cod_tipo=b.COD_TIPO AND 
						a.cod_empresa=b.cod_empresa AND 
						a.COD_EMPRESA = CR.COD_EMPRESA AND 
						a.cod_conveni=CR.COD_CONVENI) VAL_SALDO_CONTA ,
							CL.NOM_CLIENTE, 
							CTT.NRO_CONTRAT, 
							EM.VAL_VALOR AS VAL_DEBITO, 
							CV.NUM_CONVENI AS NUM_CONVENI_CV, 
							CV.VAL_VALOR AS VAL_VALOR_CV, 
							CV.VAL_CONCED AS VAL_CONCED_CV, 
							CV.VAL_CONTPAR AS VAL_CONTPAR_CV
					FROM CONTROLE_RECEBIMENTO CR
					LEFT JOIN CONTRATO CTT ON CTT.COD_CONTRAT = CR.COD_CONTRAT
					LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = CTT.COD_CLIENTE
					LEFT JOIN EMPENHO EM ON EM.COD_CONTRAT = CR.COD_CONTRAT
					LEFT JOIN CONVENIO CV ON CV.COD_CONVENI = CR.COD_CONVENI
					WHERE EM.COD_EMPRESA = $cod_empresa 
					AND EM.COD_EMPENHO = $cod_empenho
					AND CR.COD_RECEBIM = $cod_recebim
					";

			// fnEscreve($sql);
			$arrayQuery =  mysqli_query(connTemp($cod_empresa,''),$sql);
			$qrContrat = mysqli_fetch_assoc($arrayQuery);

			if (isset($qrContrat)){
				$cod_conveni = $qrContrat['COD_CONVENI'];
				$cod_cliente = $qrContrat['COD_CLIENTE'];
				$nom_cliente = $qrContrat['NOM_CLIENTE'];
				$nro_contrat = $qrContrat['NRO_CONTRAT'];
				$cod_contrat = $qrContrat['COD_CONTRAT'];
				$dat_medicao = $qrContrat['DAT_MEDICAO'];
				$val_valor = $qrContrat['VAL_VALOR'];
				$val_debito = $qrContrat['VAL_DEBITO'];
				$nom_empContrat = $qrContrat['NOM_CLIENTE'];
				$num_conveni = $qrContrat['NUM_CONVENI_CV'];
				$val_valor = $qrContrat['VAL_VALOR_CV'];
				$val_conced = $qrContrat['VAL_CONCED_CV'];
				$val_contpar = $qrContrat['VAL_CONTPAR_CV'];
				$val_saldo_conta = $qrContrat['VAL_SALDO_CONTA'];
			}

		}

	}

	//fnEscreve($cod_contrat);

	$tp_cont = 'Anexo do Pagamento';
	$tp_anexo = 'COD_PAGAMEN';
	$cod_tpanexo = 'COD_PAGAMEN';
	$cod_busca = $cod_caixa;

	$sqlUpdtCont = "DELETE FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_PAGAMEN != 0 AND LOG_STATUS = 'N'";
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);

	$sqlUpdtCont = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE DES_CONTADOR = '$tp_cont'";
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);

	$sqlCont = "SELECT NUM_CONTADOR FROM CONTADOR WHERE DES_CONTADOR = '$tp_cont'";
	$qrCont = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCont));
	$num_contador = $qrCont['NUM_CONTADOR'];


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
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CAIXA" id="COD_CAIXA" value="<?=$cod_empenho?>">
											<input type="hidden" readonly="readonly" name="COD_PAGAMEN" id="COD_PAGAMEN" value="<?=$cod_empenho?>">
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										</div>														
									</div>      
						
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Favorecido</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_FAVORECIDO" id="NOM_FAVORECIDO" value="<?php echo $nom_cliente ?>">
										</div>														
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data de Execução</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_EXECUCAO" id="DAT_EXECUCAO" value="<?php echo fnDataShort($dat_medicao) ?>">
										</div>														
									</div>

								</div> 
						
								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Nro. do Convênio</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NUM_CONVENI" id="NUM_CONVENI" value="<?php echo $num_conveni ?>">
										</div>														
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor Contrapartida</label>
											<input type="text" class="form-control input-sm leitura" readonly name="VAL_CONTPAR" id="VAL_CONTPAR" value="<?php echo fnValor($val_contpar,2)?>">
										</div>														
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor Concedente</label>
											<input type="text" class="form-control input-sm leitura" readonly name="VAL_CONCED" id="VAL_CONCED" value="<?php echo fnValor($val_conced,2)?>">
										</div>														
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor do Convênio</label>
											<input type="text" class="form-control input-sm leitura" readonly name="VAL_VALOR" id="VAL_VALOR" value="<?php echo fnValor($val_valor,2)?>">
										</div>														
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Saldo em Conta</label>
											<input type="text" class="form-control input-sm leitura" readonly name="VAL_SALDO_CONTA" id="VAL_SALDO_CONTA" value="<?php echo fnValor($val_saldo_conta,2)?>">
										</div>														
									</div>

								</div>

								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor do Débito</label>
											<input type="text" class="form-control input-sm leituraOff" readonly name="VAL_DEBITO" id="VAL_DEBITO" value="<?php echo fnValor($val_debito,2)?>">
										</div>														
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor Pago</label>
											<input type="text" class="form-control input-sm money leituraOff" name="VAL_PAGO" id="VAL_PAGO" value="" readonly maxlength="11">
										</div>
										<div class="help-block with-errors">REAIS (R$)</div>
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor à Pagar</label>
											<input type="text" class="form-control input-sm money leituraOff" name="VAL_PAGAR" id="VAL_PAGAR" value="" readonly maxlength="11">
										</div>
										<div class="help-block with-errors">REAIS (R$)</div>
									</div>

								</div>

							</fieldset>

							<div class="push20"></div>
															
							<fieldset>
								<legend>Dados do Crédito</legend>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor do Débito</label>
											<input type="text" class="form-control input-sm money" name="VAL_CREDITO" id="VAL_CREDITO" value="" data-mask="##0" data-mask-reverse="true" maxlength="11">
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-3">
		                                <div class="form-group">
		                                    <label for="inputName" class="control-label required">Tipo de Débito</label>
		                                    <select data-placeholder="Selecione um tipo" name="COD_TIPO" id="COD_TIPO" class="chosen-select-deselect" required>
		                                    	<option value=""></option>
		                                   		<?php																	
													$sql = "SELECT * FROM TIP_CREDITO WHERE COD_EMPRESA = $cod_empresa AND TIP_OPERACAO = 'D' ORDER BY DES_TIPO ";
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													//fnEscreve($sql);
													while ($qrTip = mysqli_fetch_assoc($arrayQuery))
													 {													
														echo"
															  <option value='".$qrTip['COD_TIPO']."'>".$qrTip['DES_TIPO']."</option> 
															"; 
													}											
												?>
		                                    </select>   
		                                    <div class="help-block with-errors"></div>
		                                </div>
		                            </div>

		                            <div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nro. do Comprovante</label>
											<input type="text" class="form-control input-sm" name="NUM_ORDEM" id="NUM_ORDEM" value="" maxlength="100" required>
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data do Débito</label>
											<div class="input-group date datePicker">
												<input type='text' class="form-control input-sm data" name="DAT_CREDITO" id="DAT_CREDITO" value=""/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="row">		
									
									<div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label">Observação</label>
											<textarea type="text" class="form-control input-sm" rows="3" name="DES_COMENT" id="DES_COMENT" value="" maxlength="250"></textarea>
										</div>
										<div class="help-block with-errors"></div>
									</div>  
									
								</div>

								<div class="push10"></div>

								<?php include "uploadConvenio.php"; ?>
								
								<div class="push10"></div>
							
							</fieldset>
									
						<div class="push10"></div>
						<hr>	
						<div class="form-group text-right col-lg-12">
							
							  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							  <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
							
						</div>
						
						<input type="hidden" name="COD_CONVENI" id="COD_CONVENI" value="<?php echo $cod_conveni; ?>">
						<input type="hidden" name="COD_OBJETOANEXO" id="COD_OBJETOANEXO" value="">
						<input type="hidden" name="COD_CONTRAT" id="COD_CONTRAT" value="<?php echo $cod_contrat; ?>">
						<input type="hidden" name="COD_EMPENHO" id="COD_EMPENHO" value="<?php echo $cod_empenho; ?>">
						<input type="hidden" name="NUM_CONTADOR" id="NUM_CONTADOR" value="<?php echo $num_contador; ?>" />
						<!-- <input type="hidden" name="COD_TIPO" id="COD_TIPO" value="C"> -->
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
								  <thead>
									<tr>
									  <th width="40"></th>
									  <th>Código</th>
									  <th>Nro. Ordem</th>
									  <th>Data</th>
									  <th>Tipo</th>
									  <th>Valor</th>
									</tr>
								  </thead>
								<tbody>
								
								<?php 
									$sql = "SELECT CX.*, TC.DES_TIPO FROM CAIXA CX
									LEFT JOIN TIP_CREDITO TC ON TC.COD_TIPO = CX.COD_TIPO
									WHERE CX.COD_EMPRESA = $cod_empresa
									AND TC.TIP_OPERACAO ='D' AND CX.COD_EMPENHO = $cod_empenho";
											
									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error()); 
									
									$count=0;
									$val_total = 0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;	
										echo"
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
											  <td>".$qrBuscaModulos['COD_CAIXA']."</td>											
											  <td>".$qrBuscaModulos['NUM_ORDEM']."</td>											
											  <td>".fnDataShort($qrBuscaModulos['DAT_CADASTR'])."</td>
											  <td>".$qrBuscaModulos['DES_TIPO']."</td>
											  <td class='text-right'>".fnValor($qrBuscaModulos['VAL_CREDITO'],2)."</td>
											</tr>
											
											<input type='hidden' id='ret_COD_CAIXA_".$count."' value='".$qrBuscaModulos['COD_CAIXA']."'>
											<input type='hidden' id='ret_COD_TIPO_".$count."' value='".$qrBuscaModulos['COD_TIPO']."'>
											<input type='hidden' id='ret_COD_TIPO_".$count."' value='".$qrBuscaModulos['COD_TIPO']."'>
											<input type='hidden' id='ret_NUM_ORDEM_".$count."' value='".$qrBuscaModulos['NUM_ORDEM']."'>
											<input type='hidden' id='ret_DAT_CREDITO_".$count."' value='".fnDataShort($qrBuscaModulos['DAT_CREDITO'])."'>
											<input type='hidden' id='ret_DES_COMENT_".$count."' value='".$qrBuscaModulos['DES_COMENT']."'>
											<input type='hidden' id='ret_VAL_CREDITO_".$count."' value='".fnValor($qrBuscaModulos['VAL_CREDITO'],2)."'>
											";

											$val_total+=$qrBuscaModulos['VAL_CREDITO'];
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
											<td class="text-right"><b><?=fnValor($val_total,2);?></b></td>
										</tr>
									</tfoot>

								</table>
								
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

		let val_pagar = "<?=($val_debito-$val_total)?>";

		if(val_pagar == 0){
			$('#CAD').hide();
		}

		$(document).ready(function(){

			$('#VAL_PAGO').val((<?=$val_total?>).toFixed(2));
			$('#VAL_PAGAR').val((<?=($val_debito-$val_total)?>).toFixed(2));

			$('.upload').prop('disabled',true);
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 maxDate : 'now',
			}).on('changeDate', function(e){
				$(this).datetimepicker('hide');
			});

		});
	
		function retornaForm(index){
			$("#formulario #COD_CAIXA").val($("#ret_COD_CAIXA_"+index).val());
			$("#formulario #COD_PAGAMEN").val($("#ret_COD_CAIXA_"+index).val());
			$("#formulario #COD_OBJETOANEXO").val($("#ret_COD_CAIXA_"+index).val());
			$("#formulario #VAL_CREDITO").val($("#ret_VAL_CREDITO_"+index).val());
			$("#formulario #COD_TIPO").val($("#ret_COD_TIPO_"+index).val()).trigger("chosen:updated");
			$("#formulario #NUM_ORDEM").val($("#ret_NUM_ORDEM_"+index).val());
			$("#formulario #DAT_CREDITO").val($("#ret_DAT_CREDITO_"+index).val());
			$("#formulario #DES_COMENT").val($("#ret_DES_COMENT_"+index).val());
			$('.upload').prop('disabled',false).removeAttr('disabled');

			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');

			refreshUpload();			
		}
		
	</script>

	<?php include 'jsUploadConvenio.php'; ?>