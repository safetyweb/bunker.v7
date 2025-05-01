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
			
			$cod_empenho = fnLimpaCampoZero($_REQUEST['COD_EMPENHO']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
			$cod_contrat = fnLimpaCampoZero($_REQUEST['COD_CONTRAT']);
			$cod_recebim = fnLimpaCampoZero($_REQUEST['COD_RECEBIM']);
			$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
			$dat_nota = fnLimpaCampo($_REQUEST['DAT_NOTA']);
			$dat_empenho = fnLimpaCampo($_REQUEST['DAT_EMPENHO']);
			$num_nota = fnLimpaCampo($_REQUEST['NUM_NOTA']);
			$num_empenho = fnLimpaCampo($_REQUEST['NUM_EMPENHO']);
			$val_contpar = fnLimpaCampo($_REQUEST['VAL_CONTPAR']);
			$val_conveni = fnLimpaCampo($_REQUEST['VAL_CONVENI']);
			$val_valor = fnLimpaCampo($_REQUEST['VAL_VALOR']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){							
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO EMPENHO(
										COD_EMPRESA,
										COD_CONVENI,
										COD_CONTRAT,
										COD_RECEBIM,
										COD_CLIENTE,
										DAT_NOTA,
										DAT_EMPENHO,
										NUM_NOTA,
										NUM_EMPENHO,
										VAL_CONTPAR,
										VAL_CONVENI,
										VAL_VALOR
										) VALUES(
										$cod_empresa,
										$cod_conveni,
										$cod_contrat,
										$cod_recebim,
										$cod_cliente,
										'".fnDataSql($dat_nota)."',
										'".fnDataSql($dat_empenho)."',
										'$num_nota',
										'$num_empenho',
										'".fnValorSql($val_contpar)."',
										'".fnValorSql($val_conveni)."'
										'".fnValorSql($val_valor)."',
										)";
						
					//fnEscreve($sql);
	                mysqli_query(connTemp($cod_empresa,''),$sql);

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						$sql = "UPDATE EMPENHO SET
										DAT_NOTA='".fnDataSql($dat_nota)."',
										DAT_EMPENHO='".fnDataSql($dat_empenho)."',
										NUM_NOTA='$num_nota',
										NUM_EMPENHO='$num_empenho',
										VAL_CONTPAR='".fnValorSql($val_contpar)."',
										VAL_CONVENI='".fnValorSql($val_conveni)."',
										VAL_VALOR='".fnValorSql($val_valor)."'
								WHERE COD_EMPENHO = $cod_empenho
								";
							
						//fnEscreve($sql);
		                mysqli_query(connTemp($cod_empresa,''),$sql);

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
			$cod_recebim = fnDecode($_GET['idC']);

			$sql = "SELECT CR.*,CL.NOM_CLIENTE, CTT.COD_CONTRAT, CTT.NRO_CONTRAT  FROM CONTROLE_RECEBIMENTO CR
					LEFT JOIN CONTRATO CTT ON CTT.COD_CONTRAT = CR.COD_CONTRAT
					LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = CTT.COD_CLIENTE
					WHERE CR.COD_EMPRESA = $cod_empresa AND COD_RECEBIM = $cod_recebim;";	
			
			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$qrMedicao = mysqli_fetch_assoc($arrayQuery);
				
			if (isset($qrMedicao)){
				$cod_contrat = $qrMedicao['COD_CONTRAT'];
				$nro_contrat = $qrMedicao['NRO_CONTRAT'];
				$cod_conveni = $qrMedicao['COD_CONVENI'];
				$cod_cliente = $qrMedicao['COD_CLIENTE'];
				$nom_cliente = $qrMedicao['NOM_CLIENTE'];
				$num_medicao = $qrMedicao['NUM_MEDICAO'];
				$dat_medicao = fnDataShort($qrMedicao['DAT_MEDICAO']);
				$val_medicao =$qrMedicao['VAL_MEDICAO'];

			}

		$leitura = "disabled";
			
		}else{

			$cod_contrat = 0;
			$cod_recebim = 0;
			$nro_contrat = "";
			$nom_cliente = "";
			$num_medicao = "";
			$dat_medicao = "";
			$val_medicao = "";
			
		}
	}

	// $sqlSaldo = "SELECT SUM(VAL_CONTPAR) AS VAL_CONTPAR, SUM(VAL_CONVENI) AS VAL_CONVENI FROM EMPENHO
	// 				WHERE COD_EMPRESA = $cod_empresa AND COD_RECEBIM = $cod_recebim";

	// //fnEscreve($sqlSaldo);


	// $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlSaldo) or die(mysqli_error());
	// $qrSaldo = mysqli_fetch_assoc($arrayQuery);

	// if(isset($qrSaldo)){
	// 	$val_saldo = fnValorSql($val_medicao) - ($qrSaldo['VAL_CONTPAR'] + $qrSaldo['VAL_CONVENI']);
	// 	$val_contparac = fnValor($qrSaldo['VAL_CONTPAR'],2);
	// 	$val_conveniac = fnValor($qrSaldo['VAL_CONVENI'],2);
	// }else{
	// 	$val_saldo = fnValorSql($val_medicao);
	// }
	            
	//fnMostraForm();
	//fnEscreve($cod_contrat);

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
				
					<?php if ($popUp != "true"){ $abaFormalizacao = 1092; include "abasFormalizacaoEmp.php"; } ?>
										
					<h4>Formalização: 
						<br>Abertura do Julgamento / 
						<br>Adjudicação / 
						<br>Homologação / 
						<br>Comprovante de Publicação da Homologação / 
						<br>Comprovantede Publicação do Extrato do Contato
					</h4>
					
					<div class="push20"></div> 			
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_EMPENHO" id="COD_EMPENHO" value="<?=$cod_empenho?>">
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>" required>
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										</div>														
									</div>      
						
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Favorecido</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_FAVORECIDO" id="NOM_FAVORECIDO" value="<?php echo $nom_cliente ?>" required>
										</div>														
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data de Execução</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_EXECUCAO" id="DAT_EXECUCAO" value="<?php echo $dat_medicao ?>" required>
										</div>														
									</div>

								</div> 
						
								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código do Contrato</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NRO_CONTRAT" id="NRO_CONTRAT" value="<?=$nro_contrat?>">
											<input type="hidden" class="form-control input-sm" name="COD_CONTRAT" id="COD_CONTRAT" value="<?php echo $cod_contrat ?>">
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nro. da Nota</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NUM_NOTA" id="NUM_NOTA" value="<?php echo $num_medicao ?>" required>
										</div>														
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Valor da Execução</label>
											<input type="text" class="form-control input-sm leitura" readonly name="VAL_MEDICAO" id="VAL_MEDICAO" value="<?php echo fnValor($val_medicao,2)?>">
										</div>														
									</div>

								</div>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Saldo da Execução</label>
											<input type="text" class="form-control input-sm money leituraOff" readonly name="VAL_SALDO" id="VAL_SALDO" value="" data-mask="##0" data-mask-reverse="true" maxlength="11" >
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label ">Valor da Contrapartida</label>
											<input type="text" class="form-control input-sm money leituraOff" readonly name="VAL_CONTPARAC" id="VAL_CONTPARAC" value="<?=$val_contparac?>" data-mask="##0" data-mask-reverse="true" maxlength="11" >
										</div>
										<div class="help-block with-errors">ACUMULADA</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label ">Valor do Recurso do Convênio</label>
											<input type="text" class="form-control input-sm money leituraOff" readonly name="VAL_CONVENIAC" id="VAL_CONVENIAC" value="<?=$val_conveniac?>" data-mask="##0" data-mask-reverse="true" maxlength="11" >
										</div>
										<div class="help-block with-errors">ACUMULADO</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Nota Fiscal</label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_ANEXO" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
												</span>
												<input type="text" name="DES_ANEXO" id="DES_ANEXO" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" value="">
											</div>																
											<span class="help-block">(Comprovante / upload)</span>															
										</div>
									</div>

								</div>
								
						</fieldset>
								
						<div class="push20"></div>
						
						<fieldset>
							<legend>Dados da Nota Fiscal</legend> 
						
								<div class="row">

									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data da Nota Fiscal</label>
											<div class="input-group date datePicker">
												<input type='text' class="form-control input-sm data" name="DAT_NOTA" id="DAT_NOTA" value=""/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Número da Nota Fiscal</label>
											<input type="text" class="form-control input-sm" name="NUM_NOTA" id="NUM_NOTA" value="" maxlength="11" required>
										</div>
										<div class="help-block with-errors"></div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Número do Empenho</label>
											<input type="text" class="form-control input-sm" name="NUM_EMPENHO" id="NUM_EMPENHO" value="" maxlength="11" required>
										</div>
										<div class="help-block with-errors"></div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data do Empenho</label>
											<div class="input-group date datePicker">
												<input type='text' class="form-control input-sm data" name="DAT_EMPENHO" id="DAT_EMPENHO" value=""/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="push10"></div> 									

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor da Contrapartida</label>
											<input type="text" class="form-control input-sm money" name="VAL_CONTPAR" id="VAL_CONTPAR" value="000" data-mask="##0" data-mask-reverse="true" maxlength="11">
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-1 text-center">
									<div class="push20"></div>
									<span class="f21">+</span>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor de Recurso do Convênio</label>
											<input type="text" class="form-control input-sm money" name="VAL_CONVENI" id="VAL_CONVENI" value="000" data-mask="##0" data-mask-reverse="true" maxlength="11">
										</div>
										<div class="help-block with-errors"></div>
									</div>
									
									<div class="col-md-1 text-center">
									<div class="push20"></div>
									<span class="f21">=</span>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor Efetivo da Nota Fiscal</label>
											<input type="text" class="form-control input-sm money leituraOff" name="VAL_VALOR" id="VAL_VALOR" readonly value="" data-mask="##0" data-mask-reverse="true" maxlength="11">
										</div>
										<div class="help-block with-errors"></div>
									</div>
									
								</div>
								
								<div class="push10"></div>
								
						</fieldset>

						<!-- <div class="push20"></div> -->

						<!-- <fieldset>
							<legend>Dados Adicionais</legend>

							<div class="row">			
					
									<div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label">Comentário</label>
											<textarea type="text" class="form-control input-sm" rows="3" name="DES_DESCRIC" id="DES_DESCRIC" maxlength="250" ><?php echo ''; ?></textarea>
										</div>
										<div class="help-block with-errors"></div>
									</div> 

								</div>

								<div class="row">
									
									<div class="col-md-12">
										<div class="area">
										    <div id="dropZone">

										    	<div class="row">

										    		<div class="push15"></div>

										    		<div class="col-sm-1"></div>

											    	<div class="col-sm-2">
														<a type="button" name="btnBusca" id="btnBusca" class="btn btn-primary upload" idinput="DES_ANEXO" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
													</div>
													
													<div class="col-sm-7 text-center">
														<div class="push5"></div>
														<p>Upload de Arquivos</p>
														<input type="text" name="DES_ANEXO" id="DES_ANEXO" maxlength="100" hidden>
														<span class="help-block">(Tamanho máximo de 20MB por anexo)</span>
														<div class="push15"></div>
													</div>

													<div class="col-sm-1"></div>

												</div>

												
											</div>
										</div>
									</div>

								</div>

						</fieldset> -->
						
																
						<div class="push10"></div>
						<hr>	
						<div class="form-group text-right col-lg-12">
							
							  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							  <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
							
						</div>
						
						<!-- <input type="hidden" name="UPDATE_EFETIVO" id="UPDATE_EFETIVO" value=""> -->
						<input type="hidden" name="COD_RECEBIM" id="COD_RECEBIM" value="<?=$cod_recebim?>">
						<input type="hidden" name="COD_CONVENI" id="COD_CONVENI" value="<?=$cod_conveni?>">
						<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=$cod_cliente?>">
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
									  <th>Nro. da Nota</th>
									  <th>Data Nota</th>
									  <th>Valor da Contrapartida</th>
									  <th>Valor do Convênio</th>
									  <th>Valor Efetivo</th>
									</tr>
								  </thead>
									<tbody>
									
									<?php 
										$sql = "SELECT * FROM EMPENHO WHERE COD_EMPRESA = $cod_empresa AND COD_RECEBIM = $cod_recebim";
												
										
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
										
										$count=0;
										$val_total = 0;
										$val_totCont = 0;
										$val_totConv = 0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
										  {														  
											$count++;	
											echo"
												<tr>
												  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
												  <td>".$qrBuscaModulos['COD_EMPENHO']."</td>
												  <td>".$qrBuscaModulos['NUM_NOTA']."</td>
												  <td>".fnDataShort($qrBuscaModulos['DAT_NOTA'])."</td>
												  <td class='text-right'>".fnValor($qrBuscaModulos['VAL_CONTPAR'],2)."</td>
												  <td class='text-right'>".fnValor($qrBuscaModulos['VAL_CONVENI'],2)."</td>
												  <td class='text-right'>".fnValor($qrBuscaModulos['VAL_VALOR'],2)."</td>
												</tr>
												
												<input type='hidden' id='ret_COD_EMPENHO_".$count."' value='".$qrBuscaModulos['COD_EMPENHO']."'>
												<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$qrBuscaModulos['COD_EMPRESA']."'>
												<input type='hidden' id='ret_COD_CONTRAT_".$count."' value='".$qrBuscaModulos['COD_CONTRAT']."'>
												<input type='hidden' id='ret_COD_CONVENI_".$count."' value='".$qrBuscaModulos['COD_CONVENI']."'>
												<input type='hidden' id='ret_COD_CLIENTE_".$count."' value='".$qrBuscaModulos['COD_CLIENTE']."'>
												<input type='hidden' id='ret_COD_RECEBIM_".$count."' value='".$qrBuscaModulos['COD_RECEBIM']."'>
												<input type='hidden' id='ret_DAT_NOTA_".$count."' value='".fnDataShort($qrBuscaModulos['DAT_NOTA'])."'>
												<input type='hidden' id='ret_DAT_EMPENHO_".$count."' value='".fnDataShort($qrBuscaModulos['DAT_EMPENHO'])."'>
												<input type='hidden' id='ret_NUM_NOTA_".$count."' value='".$qrBuscaModulos['NUM_NOTA']."'>
												<input type='hidden' id='ret_NUM_EMPENHO_".$count."' value='".$qrBuscaModulos['NUM_EMPENHO']."'>
												<input type='hidden' id='ret_VAL_CONTPAR_".$count."' value='".fnValor($qrBuscaModulos['VAL_CONTPAR'],2)."'>
												<input type='hidden' id='ret_VAL_CONVENI_".$count."' value='".fnValor($qrBuscaModulos['VAL_CONVENI'],2)."'>
												<input type='hidden' id='ret_VAL_VALOR_".$count."' value='".fnValor($qrBuscaModulos['VAL_VALOR'],2)."'>
												"; 
												$val_total+= $qrBuscaModulos['VAL_VALOR'];
												$val_totCont+= $qrBuscaModulos['VAL_CONTPAR'];
												$val_totConv+= $qrBuscaModulos['VAL_CONVENI'];
											  }	

									$val_saldo = ($val_medicao-$val_total);	

									//fnEscreve($val_saldo);					  
									?>

									</tbody>

									<tfoot>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td class="text-right"><b><?=fnValor($val_totCont,2);?></b></td>
											<td class="text-right"><b><?=fnValor($val_totConv,2);?></b></td>
											<td class="text-right"><b><?=fnValor($val_total,2);?></b></td>
										</tr>
									</tfoot>

								</table>

								<input type="hidden" id="ret_VAL_SALDO" value="<?=fnValor($val_saldo,2);?>">

								
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
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
				
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			$('#VAL_SALDO').val($('#ret_VAL_SALDO').val());

			// $('#CAD,#ALT').click(function(e){
			// 	botao = $(this).attr('id'),
			// 	saldo = '<?=$val_saldo?>',
			// 	total = parseFloat(saldo) - parseFloat($('#VAL_VALOR').val().replace('.','').replace(',','.'));
			// 		if(botao == 'CAD' && total < 0){
			// 			e.preventDefault();
			// 			$.alert({
	  //                       title: "Erro ao efetuar o cadastro",
	  //                       content: 'A soma dos lançamentos excede o valor da execução.',
	  //                       type: 'red'
	  //                   });
			// 		}
			// 		else if(botao == 'ALT'){
			// 			total = parseFloat(saldo) + parseFloat($('#UPDATE_EFETIVO').val().replace('.','').replace(',','.')) - parseFloat($('#VAL_VALOR').val().replace('.','').replace(',','.'));
			// 			if(total < 0){
			// 				e.preventDefault();
			// 				$.alert({
		 //                        title: "Erro ao efetuar a atualização",
		 //                        content: 'A soma dos lançamentos excede o valor da execução.',
		 //                        type: 'red'
		 //                    });
			// 			}
			// 		}
			// });

		});

		$('#VAL_CONTPAR,#VAL_CONVENI').change(function(){
				$('#VAL_VALOR').unmask();
					if($('#VAL_CONTPAR').val() != ''){
						val_contpar = parseFloat($('#VAL_CONTPAR').val().replace('.','').replace(',','.'));
					}else{
						val_contpar = 0;
					}
					if($('#VAL_CONVENI').val() != ''){
						val_conveni = parseFloat($('#VAL_CONVENI').val().replace('.','').replace(',','.'));
					}else{
						val_conveni = 0;
					}
				total = (val_contpar+val_conveni).toFixed(2);
				$('#VAL_VALOR').val(total).toString().mask('##0');
			});
		
		function retornaForm(index){
			$("#formulario #COD_EMPENHO").val($("#ret_COD_EMPENHO_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
			$("#formulario #COD_CONTRAT").val($("#ret_COD_CONTRAT_"+index).val());
			$("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_"+index).val());
			$("#formulario #COD_CLIENTE").val($("#ret_COD_CLIENTE_"+index).val());
			$("#formulario #COD_RECEBIM").val($("#ret_COD_RECEBIM_"+index).val());
			$("#formulario #DAT_NOTA").val($("#ret_DAT_NOTA_"+index).val());
			$("#formulario #DAT_EMPENHO").val($("#ret_DAT_EMPENHO_"+index).val());
			$("#formulario #NUM_NOTA").val($("#ret_NUM_NOTA_"+index).val());
			$("#formulario #NUM_EMPENHO").val($("#ret_NUM_EMPENHO_"+index).val());
			$("#formulario #VAL_CONTPAR").val($("#ret_VAL_CONTPAR_"+index).val());
			$("#formulario #VAL_CONVENI").val($("#ret_VAL_CONVENI_"+index).val());
			$("#formulario #VAL_VALOR").val($("#ret_VAL_VALOR_"+index).val());
			// $("#formulario #UPDATE_EFETIVO").val($("#ret_VAL_VALOR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');
	
		}

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

        formData.append('arquivo', $('#' + idField)[0].files[0]);
        formData.append('diretorio', '../media/clientes/');
		formData.append('diretorioAdicional', 'banner');
        formData.append('id', <?php echo $cod_empresa ?>);
        formData.append('typeFile', typeFile);

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
            url: '../uploads/uploaddoc.php',
            type: 'POST',
            data: formData,
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
            success: function (data) {
                $('.jconfirm-open').fadeOut(300, function () {
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

